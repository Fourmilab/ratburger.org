<?php

if (!defined('ABSPATH')) die('No direct access.');

/**
 * Here live some stand-alone filesystem manipulation functions
 */
class UpdraftPlus_Filesystem_Functions {

	/**
	 * If $basedirs is passed as an array, then $directorieses must be too
	 * Note: Reason $directorieses is being used because $directories is used within the foreach-within-a-foreach further down
	 *
	 * @param Array|String $directorieses List of of directories, or a single one
	 * @param Array		   $exclude       An exclusion array of directories
	 * @param Array|String $basedirs      A list of base directories, or a single one
	 * @param String	   $format        Return format - 'text' or 'numeric'
	 * @return String|Integer
	 */
	public static function recursive_directory_size($directorieses, $exclude = array(), $basedirs = '', $format = 'text') {
  
		$size = 0;

		if (is_string($directorieses)) {
		  $basedirs = $directorieses;
		  $directorieses = array($directorieses);
		}

		if (is_string($basedirs)) $basedirs = array($basedirs);

		foreach ($directorieses as $ind => $directories) {
			if (!is_array($directories)) $directories = array($directories);

			$basedir = empty($basedirs[$ind]) ? $basedirs[0] : $basedirs[$ind];

			foreach ($directories as $dir) {
				if (is_file($dir)) {
					$size += @filesize($dir);
				} else {
					$suffix = ('' != $basedir) ? ((0 === strpos($dir, $basedir.'/')) ? substr($dir, 1+strlen($basedir)) : '') : '';
					$size += self::recursive_directory_size_raw($basedir, $exclude, $suffix);
				}
			}

		}

		if ('numeric' == $format) return $size;

		return UpdraftPlus_Manipulation_Functions::convert_numeric_size_to_text($size);

	}

	/**
	 * Get the html of "Web-server disk space" line which resides above of the existing backup table
	 *
	 * @param Boolean $will_immediately_calculate_disk_space Whether disk space should be counted now or when user click Refresh link
	 *
	 * @return String Web server disk space html to render
	 */
	public static function web_server_disk_space($will_immediately_calculate_disk_space = true) {
		if ($will_immediately_calculate_disk_space) {
			$disk_space_used = self::get_disk_space_used('updraft', 'numeric');
			if ($disk_space_used > apply_filters('updraftplus_display_usage_line_threshold_size', 104857600)) { // 104857600 = 100 MB = (100 * 1024 * 1024)
				$disk_space_text = UpdraftPlus_Manipulation_Functions::convert_numeric_size_to_text($disk_space_used);
				$refresh_link_text = __('refresh', 'updraftplus');
				return self::web_server_disk_space_html($disk_space_text, $refresh_link_text);
			} else {
				return '';
			}
		} else {
			$disk_space_text = '';
			$refresh_link_text = __('calculate', 'updraftplus');
			return self::web_server_disk_space_html($disk_space_text, $refresh_link_text);
		}
	}
	
	/**
	 * Get the html of "Web-server disk space" line which resides above of the existing backup table
	 *
	 * @param String $disk_space_text   The texts which represents disk space usage
	 * @param String $refresh_link_text Refresh disk space link text
	 *
	 * @return String - Web server disk space HTML
	 */
	public static function web_server_disk_space_html($disk_space_text, $refresh_link_text) {
		return '<li class="updraft-server-disk-space" title="'.esc_attr__('This is a count of the contents of your Updraft directory', 'updraftplus').'"><strong>'.__('Web-server disk space in use by UpdraftPlus', 'updraftplus').':</strong> <span class="updraft_diskspaceused"><em>'.$disk_space_text.'</em></span> <a class="updraft_diskspaceused_update" href="#">'.$refresh_link_text.'</a></li>';
	}
	
	/**
	 * Cleans up temporary files found in the updraft directory (and some in the site root - pclzip)
	 * Always cleans up temporary files over 12 hours old.
	 * With parameters, also cleans up those.
	 * Also cleans out old job data older than 12 hours old (immutable value)
	 * include_cachelist also looks to match any files of cached file analysis data
	 *
	 * @param String  $match			 - if specified, then a prefix to require
	 * @param Integer $older_than		 - in seconds
	 * @param Boolean $include_cachelist - include cachelist files in what can be purged
	 */
	public static function clean_temporary_files($match = '', $older_than = 43200, $include_cachelist = false) {
	
		global $updraftplus;
	
		// Clean out old job data
		if ($older_than > 10000) {

			global $wpdb;
			$table = is_multisite() ? $wpdb->sitemeta : $wpdb->options;
			$key_column = is_multisite() ? 'meta_key' : 'option_name';
			$value_column = is_multisite() ? 'meta_value' : 'option_value';
			
			// Limit the maximum number for performance (the rest will get done next time, if for some reason there was a back-log)
			$all_jobs = $wpdb->get_results("SELECT $key_column, $value_column FROM $table WHERE $key_column LIKE 'updraft_jobdata_%' LIMIT 100", ARRAY_A);
			
			foreach ($all_jobs as $job) {
				$val = maybe_unserialize($job[$value_column]);
				// TODO: Can simplify this after a while (now all jobs use job_time_ms) - 1 Jan 2014
				$delete = false;
				if (!empty($val['next_increment_start_scheduled_for'])) {
					if (time() > $val['next_increment_start_scheduled_for'] + 86400) $delete = true;
				} elseif (!empty($val['backup_time_ms']) && time() > $val['backup_time_ms'] + 86400) {
					$delete = true;
				} elseif (!empty($val['job_time_ms']) && time() > $val['job_time_ms'] + 86400) {
					$delete = true;
				} elseif (!empty($val['job_type']) && 'backup' != $val['job_type'] && empty($val['backup_time_ms']) && empty($val['job_time_ms'])) {
					$delete = true;
				}
				if ($delete) delete_site_option($job[$key_column]);
			}
		}
		$updraft_dir = $updraftplus->backups_dir_location();
		$now_time = time();
		$files_deleted = 0;
		if ($handle = opendir($updraft_dir)) {
			while (false !== ($entry = readdir($handle))) {
				$manifest_match = preg_match("/updraftplus-manifest.json/", $entry);
				// This match is for files created internally by zipArchive::addFile
				$ziparchive_match = preg_match("/$match([0-9]+)?\.zip\.tmp\.([A-Za-z0-9]){6}?$/i", $entry);
				// zi followed by 6 characters is the pattern used by /usr/bin/zip on Linux systems. It's safe to check for, as we have nothing else that's going to match that pattern.
				$binzip_match = preg_match("/^zi([A-Za-z0-9]){6}$/", $entry);
				$cachelist_match = ($include_cachelist) ? preg_match("/$match-cachelist-.*.tmp$/i", $entry) : false;
				$browserlog_match = preg_match('/^log\.[0-9a-f]+-browser\.txt$/', $entry);
				// Temporary files from the database dump process - not needed, as is caught by the catch-all
				// $table_match = preg_match("/${match}-table-(.*)\.table(\.tmp)?\.gz$/i", $entry);
				// The gz goes in with the txt, because we *don't* want to reap the raw .txt files
				if ((preg_match("/$match\.(tmp|table|txt\.gz)(\.gz)?$/i", $entry) || $cachelist_match || $ziparchive_match || $binzip_match || $manifest_match || $browserlog_match) && is_file($updraft_dir.'/'.$entry)) {
					// We delete if a parameter was specified (and either it is a ZipArchive match or an order to delete of whatever age), or if over 12 hours old
					if (($match && ($ziparchive_match || $binzip_match || $cachelist_match || $manifest_match || 0 == $older_than) && $now_time-filemtime($updraft_dir.'/'.$entry) >= $older_than) || $now_time-filemtime($updraft_dir.'/'.$entry)>43200) {
						$skip_dblog = (0 == $files_deleted % 25) ? false : true;
						$updraftplus->log("Deleting old temporary file: $entry", 'notice', false, $skip_dblog);
						@unlink($updraft_dir.'/'.$entry);
						$files_deleted++;
					}
				} elseif (preg_match('/^log\.[0-9a-f]+\.txt$/', $entry) && $now_time-filemtime($updraft_dir.'/'.$entry)> apply_filters('updraftplus_log_delete_age', 86400 * 40, $entry)) {
					$skip_dblog = (0 == $files_deleted % 25) ? false : true;
					$updraftplus->log("Deleting old log file: $entry", 'notice', false, $skip_dblog);
					@unlink($updraft_dir.'/'.$entry);
					$files_deleted++;
				}
			}
			@closedir($handle);
		}

		// Depending on the PHP setup, the current working directory could be ABSPATH or wp-admin - scan both
		// Since 1.9.32, we set them to go into $updraft_dir, so now we must check there too. Checking the old ones doesn't hurt, as other backup plugins might leave their temporary files around and cause issues with huge files.
		foreach (array(ABSPATH, ABSPATH.'wp-admin/', $updraft_dir.'/') as $path) {
			if ($handle = opendir($path)) {
				while (false !== ($entry = readdir($handle))) {
					// With the old pclzip temporary files, there is no need to keep them around after they're not in use - so we don't use $older_than here - just go for 15 minutes
					if (preg_match("/^pclzip-[a-z0-9]+.tmp$/", $entry) && $now_time-filemtime($path.$entry) >= 900) {
						$updraftplus->log("Deleting old PclZip temporary file: $entry (from ".basename($path).")");
						@unlink($path.$entry);
					}
				}
				@closedir($handle);
			}
		}
	}
	
	/**
	 * Find out whether we really can write to a particular folder
	 *
	 * @param String $dir - the folder path
	 *
	 * @return Boolean - the result
	 */
	public static function really_is_writable($dir) {
		// Suppress warnings, since if the user is dumping warnings to screen, then invalid JavaScript results and the screen breaks.
		if (!@is_writable($dir)) return false;
		// Found a case - GoDaddy server, Windows, PHP 5.2.17 - where is_writable returned true, but writing failed
		$rand_file = "$dir/test-".md5(rand().time()).".txt";
		while (file_exists($rand_file)) {
			$rand_file = "$dir/test-".md5(rand().time()).".txt";
		}
		$ret = @file_put_contents($rand_file, 'testing...');
		@unlink($rand_file);
		return ($ret > 0);
	}
	
	/**
	 * Remove a directory from the local filesystem
	 *
	 * @param String  $dir			 - the directory
	 * @param Boolean $contents_only - if set to true, then do not remove the directory, but only empty it of contents
	 *
	 * @return Boolean - success/failure
	 */
	public static function remove_local_directory($dir, $contents_only = false) {
		// PHP 5.3+ only
		// foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
		// $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
		// }
		// return rmdir($dir);

		if ($handle = @opendir($dir)) {
			while (false !== ($entry = readdir($handle))) {
				if ('.' !== $entry && '..' !== $entry) {
					if (is_dir($dir.'/'.$entry)) {
						self::remove_local_directory($dir.'/'.$entry, false);
					} else {
						@unlink($dir.'/'.$entry);
					}
				}
			}
			@closedir($handle);
		}

		return $contents_only ? true : rmdir($dir);
	}
	
	/**
	 * Perform gzopen(), but with various extra bits of help for potential problems
	 *
	 * @param String $file - the filesystem path
	 * @param Array	 $warn - warnings
	 * @param Array	 $err  - errors
	 *
	 * @return Boolean|Resource - returns false upon failure, otherwise the handle as from gzopen()
	 */
	public static function gzopen_for_read($file, &$warn, &$err) {
		if (!function_exists('gzopen') || !function_exists('gzread')) {
			$missing = '';
			if (!function_exists('gzopen')) $missing .= 'gzopen';
			if (!function_exists('gzread')) $missing .= ($missing) ? ', gzread' : 'gzread';
			$err[] = sprintf(__("Your web server's PHP installation has these functions disabled: %s.", 'updraftplus'), $missing).' '.sprintf(__('Your hosting company must enable these functions before %s can work.', 'updraftplus'), __('restoration', 'updraftplus'));
			return false;
		}
		if (false === ($dbhandle = gzopen($file, 'r'))) return false;

		if (!function_exists('gzseek')) return $dbhandle;

		if (false === ($bytes = gzread($dbhandle, 3))) return false;
		// Double-gzipped?
		if ('H4sI' != base64_encode($bytes)) {
			if (0 === gzseek($dbhandle, 0)) {
				return $dbhandle;
			} else {
				@gzclose($dbhandle);
				return gzopen($file, 'r');
			}
		}
		// Yes, it's double-gzipped

		$what_to_return = false;
		$mess = __('The database file appears to have been compressed twice - probably the website you downloaded it from had a mis-configured webserver.', 'updraftplus');
		$messkey = 'doublecompress';
		$err_msg = '';

		if (false === ($fnew = fopen($file.".tmp", 'w')) || !is_resource($fnew)) {

			@gzclose($dbhandle);
			$err_msg = __('The attempt to undo the double-compression failed.', 'updraftplus');

		} else {

			@fwrite($fnew, $bytes);
			$emptimes = 0;
			while (!gzeof($dbhandle)) {
				$bytes = @gzread($dbhandle, 262144);
				if (empty($bytes)) {
					$emptimes++;
					global $updraftplus;
					$updraftplus->log("Got empty gzread ($emptimes times)");
					if ($emptimes>2) break;
				} else {
					@fwrite($fnew, $bytes);
				}
			}

			gzclose($dbhandle);
			fclose($fnew);
			// On some systems (all Windows?) you can't rename a gz file whilst it's gzopened
			if (!rename($file.".tmp", $file)) {
				$err_msg = __('The attempt to undo the double-compression failed.', 'updraftplus');
			} else {
				$mess .= ' '.__('The attempt to undo the double-compression succeeded.', 'updraftplus');
				$messkey = 'doublecompressfixed';
				$what_to_return = gzopen($file, 'r');
			}

		}

		$warn[$messkey] = $mess;
		if (!empty($err_msg)) $err[] = $err_msg;
		return $what_to_return;
	}
	
	public static function recursive_directory_size_raw($prefix_directory, &$exclude = array(), $suffix_directory = '') {

		$directory = $prefix_directory.('' == $suffix_directory ? '' : '/'.$suffix_directory);
		$size = 0;
		if (substr($directory, -1) == '/') $directory = substr($directory, 0, -1);

		if (!file_exists($directory) || !is_dir($directory) || !is_readable($directory)) return -1;
		if (file_exists($directory.'/.donotbackup')) return 0;

		if ($handle = opendir($directory)) {
			while (($file = readdir($handle)) !== false) {
				if ('.' != $file && '..' != $file) {
					$spath = ('' == $suffix_directory) ? $file : $suffix_directory.'/'.$file;
					if (false !== ($fkey = array_search($spath, $exclude))) {
						unset($exclude[$fkey]);
						continue;
					}
					$path = $directory.'/'.$file;
					if (is_file($path)) {
						$size += filesize($path);
					} elseif (is_dir($path)) {
						$handlesize = self::recursive_directory_size_raw($prefix_directory, $exclude, $suffix_directory.('' == $suffix_directory ? '' : '/').$file);
						if ($handlesize >= 0) {
							$size += $handlesize;
						}
					}
				}
			}
			closedir($handle);
		}

		return $size;

	}

	/**
	 * Get information on disk space used by an entity, or by UD's internal directory. Returns as a human-readable string.
	 *
	 * @param String $entity - the entity (e.g. 'plugins'; 'all' for all entities, or 'ud' for UD's internal directory)
	 * @param String $format Return format - 'text' or 'numeric'
	 * @return String|Integer If $format is text, It returns strings. Otherwise integer value.
	 */
	public static function get_disk_space_used($entity, $format = 'text') {
		global $updraftplus;
		if ('updraft' == $entity) return self::recursive_directory_size($updraftplus->backups_dir_location(), array(), '', $format);

		$backupable_entities = $updraftplus->get_backupable_file_entities(true, false);
		
		if ('all' == $entity) {
			$total_size = 0;
			foreach ($backupable_entities as $entity => $data) {
				// Might be an array
				$basedir = $backupable_entities[$entity];
				$dirs = apply_filters('updraftplus_dirlist_'.$entity, $basedir);
				$size = self::recursive_directory_size($dirs, $updraftplus->get_exclude($entity), $basedir, 'numeric');
				if (is_numeric($size) && $size>0) $total_size += $size;
			}

			if ('numeric' == $format) {
				return $total_size;
			} else {
				return UpdraftPlus_Manipulation_Functions::convert_numeric_size_to_text($total_size);
			}
			
		} elseif (!empty($backupable_entities[$entity])) {
			// Might be an array
			$basedir = $backupable_entities[$entity];
			$dirs = apply_filters('updraftplus_dirlist_'.$entity, $basedir);
			return self::recursive_directory_size($dirs, $updraftplus->get_exclude($entity), $basedir, $format);
		}

		// Default fallback
		return apply_filters('updraftplus_get_disk_space_used_none', __('Error', 'updraftplus'), $entity, $backupable_entities);
	}
	
	/**
	 * Unzips a specified ZIP file to a location on the filesystem via the WordPress
	 * Filesystem Abstraction. Forked from WordPress core in version 5.1-alpha-44182.
	 * Forked to allow us to modify the behaviour (eventually, to provide feedback on progress)
	 *
	 * Assumes that WP_Filesystem() has already been called and set up. Does not extract
	 * a root-level __MACOSX directory, if present.
	 *
	 * Attempts to increase the PHP memory limit before uncompressing. However,
	 * the most memory required shouldn't be much larger than the archive itself.
	 *
	 * @global WP_Filesystem_Base $wp_filesystem WordPress filesystem subclass.
	 *
	 * @param String $file Full path and filename of ZIP archive.
	 * @param String $to   Full path on the filesystem to extract archive to.
	 *
	 * @return Boolean|WP_Error True on success, WP_Error on failure.
	 */
	public static function unzip_file($file, $to) {
		global $wp_filesystem;

		if (!$wp_filesystem || !is_object($wp_filesystem)) {
			return new WP_Error('fs_unavailable', __('Could not access filesystem.'));
		}

		// Unzip can use a lot of memory, but not this much hopefully.
		if (function_exists('wp_raise_memory_limit')) wp_raise_memory_limit('admin');

		$needed_dirs = array();
		$to = trailingslashit($to);

		// Determine any parent dir's needed (of the upgrade directory)
		if (!$wp_filesystem->is_dir($to)) { // Only do parents if no children exist
			$path = preg_split('![/\\\]!', untrailingslashit($to));
			for ($i = count($path); $i >= 0; $i--) {
			
				if (empty($path[$i])) continue;

				$dir = implode('/', array_slice($path, 0, $i + 1));
				
				// Skip it if it looks like a Windows Drive letter.
				if (preg_match('!^[a-z]:$!i', $dir)) continue;

				// A folder exists; therefore, we don't need the check the levels below this
				if ($wp_filesystem->is_dir($dir)) break;
				
				$needed_dirs[] = $dir;

			}
		}

		if (class_exists('ZipArchive', false) && apply_filters('unzip_file_use_ziparchive', true)) {
			// Should be able to use self::unzip_file_go() here - not yet tested
			$result = _unzip_file_ziparchive($file, $to, $needed_dirs);
			if (true === $result || (is_wp_error($result) && 'incompatible_archive' != $result->get_error_code())) return $result;
		}
		
		// Fall through to PclZip if ZipArchive is not available, or encountered an error opening the file.
		// Not yet ready to be able to use self::unzip_file_go() until that is ready for PclZip
		return _unzip_file_pclzip($file, $to, $needed_dirs);
	}
	
	/**
	 * Attempts to unzip an archive; forked from _unzip_file_ziparchive() in WordPress 5.1-alpha-44182, and modified to use the UD zip classes.
	 *
	 * Assumes that WP_Filesystem() has already been called and set up.
	 *
	 * @global WP_Filesystem_Base $wp_filesystem WordPress filesystem subclass.
	 *
	 * @param String $file		  Full path and filename of ZIP archive.
	 * @param String $to		  Full path on the filesystem to extract archive to.
	 * @param Array	 $needed_dirs A partial list of required folders needed to be created.
	 * @param String $method	  Either 'ziparchive' or 'pclzip'. DO NOT USE 'pclzip'; it's not yet ready.
	 *
	 * @return Boolean|WP_Error True on success, WP_Error on failure.
	 */
	public static function unzip_file_go($file, $to, $needed_dirs = array(), $method = 'ziparchive') {
		global $wp_filesystem;
		
		$class_to_use = ('ziparchive' == $method) ? 'UpdraftPlus_ZipArchive' : 'UpdraftPlus_PclZip';

		if (!class_exists($class_to_use)) require_once(UPDRAFTPLUS_DIR.'/includes/class-zip.php');
		
		$z = new $class_to_use;

		$flags = (version_compare(PHP_VERSION, '5.2.12', '>') && defined('ZIPARCHIVE::CHECKCONS')) ? ZIPARCHIVE::CHECKCONS : 4;
		
		$zopen = $z->open($file, $flags);
		
		if (true !== $zopen) {
			return new WP_Error('incompatible_archive', __('Incompatible Archive.'), array($method.'_error' => $zope));
		}

		$uncompressed_size = 0;

		$num_files = $z->numFiles;
		
		for ($i = 0; $i < $num_files; $i++) {
			if (!$info = $z->statIndex($i)) {
				return new WP_Error('stat_failed_'.$method, __('Could not retrieve file from archive.'));
			}

			if ('__MACOSX/' === substr($info['name'], 0, 9)) { // Skip the OS X-created __MACOSX directory
				continue;
			}

			// Don't extract invalid files:
			if (0 !== validate_file($info['name'])) {
				continue;
			}

			$uncompressed_size += $info['size'];

			if ('/' === substr($info['name'], -1)) {
				// Directory.
				$needed_dirs[] = $to . untrailingslashit($info['name']);
			} elseif ('.' !== $dirname = dirname($info['name'])) {
				// Path to a file.
				$needed_dirs[] = $to . untrailingslashit($dirname);
			}
		}

		/*
		* disk_free_space() could return false. Assume that any falsey value is an error.
		* A disk that has zero free bytes has bigger problems.
		* Require we have enough space to unzip the file and copy its contents, with a 10% buffer.
		*/
		if (wp_doing_cron()) {
			$available_space = @disk_free_space(WP_CONTENT_DIR);
			if ($available_space && ($uncompressed_size * 2.1) > $available_space) {
				return new WP_Error('disk_full_unzip_file', __('Could not copy files. You may have run out of disk space.'), compact('uncompressed_size', 'available_space'));
			}
		}

		$needed_dirs = array_unique($needed_dirs);
		foreach ($needed_dirs as $dir) {
			// Check the parent folders of the folders all exist within the creation array.
			if (untrailingslashit($to) == $dir) { // Skip over the working directory, We know this exists (or will exist)
				continue;
			}
			if (strpos($dir, $to) === false) { // If the directory is not within the working directory, Skip it
				continue;
			}

			$parent_folder = dirname($dir);
			while (!empty($parent_folder) && untrailingslashit($to) != $parent_folder && !in_array($parent_folder, $needed_dirs)) {
				$needed_dirs[] = $parent_folder;
				$parent_folder = dirname($parent_folder);
			}
		}
		asort($needed_dirs);

		// Create those directories if need be:
		foreach ($needed_dirs as $_dir) {
			// Only check to see if the Dir exists upon creation failure. Less I/O this way.
			if (!$wp_filesystem->mkdir($_dir, FS_CHMOD_DIR) && !$wp_filesystem->is_dir($_dir)) {
				return new WP_Error('mkdir_failed_'.$method, __('Could not create directory.'), substr($_dir, strlen($to)));
			}
		}
		unset($needed_dirs);

		for ($i = 0; $i < $num_files; $i++) {
			if (!$info = $z->statIndex($i)) {
				return new WP_Error('stat_failed_'.$method, __('Could not retrieve file from archive.'));
			}

			if ('/' == substr($info['name'], -1)) { // directory
				continue;
			}

			if ('__MACOSX/' === substr($info['name'], 0, 9)) { // Don't extract the OS X-created __MACOSX directory files
				continue;
			}

			// Don't extract invalid files:
			if (0 !== validate_file($info['name'])) {
				continue;
			}

			$contents = $z->getFromIndex($i);

			if (false === $contents) {
				return new WP_Error('extract_failed_'.$method, __('Could not extract file from archive.'), $info['name']);
			}

			if (!$wp_filesystem->put_contents($to . $info['name'], $contents, FS_CHMOD_FILE)) {
				return new WP_Error('copy_failed_'.$method, __('Could not copy file.'), $info['name']);
			}
		}

		$z->close();

		return true;
	}
}
