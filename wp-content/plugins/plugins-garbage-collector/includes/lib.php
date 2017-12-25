<?php
/* 
 * * User Role Editor plugin Lirary general staff
 * Author: Vladimir Garagulya vladimir@shinephp.com
 * 
 */


if (!function_exists("get_option")) {
  header('HTTP/1.0 403 Forbidden');
  die;  // Silence is golden, direct call is prohibited
}


function pgc_log($message, $showMessage = false) {
  include(ABSPATH .'wp-includes/version.php');

  $fileName = PGC_PLUGIN_DIR.'/log.txt';
  $fh = fopen($fileName,'a');
  $cr = "\n";
  $s = $cr.date("d-m-Y H:i:s").$cr.
      'WordPress version: '.$wp_version.', PHP version: '.phpversion().', MySQL version: '.mysql_get_server_info().$cr;
  fwrite($fh, $s);
  fwrite($fh, $message.$cr);
  fclose($fh);

  if ($showMessage) {
    pgc_show_message('Error is occur. Please check the log file.');
  }
}
// end of pgc_logEvent()


function pgc_show_message($message) {

  if ($message) {
    echo '<div class="updated" style="margin:0;">'.$message.'</div><br style="clear: both;"/>';
  }

}
// end of pgc_showMessage()


function pgc_get_not_wp_tables() {
    global $wpdb;

    $wp_tables = $wpdb->tables('all', true);
    $non_wp_tables = array();
    $query = "show tables";
    $existing_tables = $wpdb->get_col($query);
    foreach ($existing_tables as $existing_table) {
        $existing_table1 = strtolower($existing_table);
        $legalWPTable = in_array($existing_table1, $wp_tables);

        if (!$legalWPTable && strpos($existing_table, $wpdb->prefix, 0) !== false) {
            $query = "SHOW TABLE STATUS FROM `" . DB_NAME . "` LIKE '$existing_table'";
            $result = $wpdb->get_results($query);
            $non_wp_table = new stdClass;
            $non_wp_table->name = $existing_table1;
            $non_wp_table->name_without_prefix = strtolower(str_replace($wpdb->prefix, '', $existing_table));
            $non_wp_table->records = $result[0]->Rows; //$result[0]->table_rows;
            $non_wp_table->kbytes = ROUND(($result[0]->Data_length + $result[0]->Index_length) / 1024, 2); //$result[0]->kbytes;
            $non_wp_table->plugin_name = '';
            $non_wp_table->plugin_file = '';
            $non_wp_table->plugin_state = '';
            $non_wp_tables[] = $non_wp_table;
        }
    }

    $pgc_settings = get_option('pgc_settings');
    if (isset($pgc_settings['hidden']) && count($pgc_settings['hidden'])) {
        $update_needed = false;
        foreach ($pgc_settings['hidden'] as $table_name) {
            $found = false;
            foreach ($non_wp_tables as $table) {
                if ($table->name == $table_name) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                unset($pgc_settings['hidden'][$table_name]);
                $update_needed = true;
            }
        }
        if ($update_needed) {
            update_option('pgc_settings', $pgc_settings);
        }
    }

    return $non_wp_tables;
}
// end of pgc_get_not_wp_tables()


function pgc_set_plugin_state_for_tables() {
  $active_plugins = get_option('active_plugins');
  foreach ($_SESSION['plugins-garbage-collector']['tables'] as $table) {
    if ($table->plugin_file) {
      $plugin_active = false;
      foreach ($active_plugins as $active_plugin) {
        if ($table->plugin_file==$active_plugin) {
          $plugin_active = true;
          break;
        }
      }
      if ($plugin_active) {
        $table->plugin_state = esc_html__('active', 'plugins-garbage-collector');
      } else {
        $table->plugin_state = esc_html__('inactive', 'plugins-garbage-collector');
      }
    } else {
      $table->plugin_state = esc_html__('deleted?', 'plugins-garbage-collector');
    }    
  }
    
}


function pgc_scan_plugin_for_db_tables_use() {
        
    
    if (!isset($_POST['plugin']) || empty($_POST['plugin'])) {
        $answer = array('result'=>'error', 'message'=>'Invalid request - missed plugin parameter');
        echo json_encode($answer);
        return;
    }
    $plugin = filter_var_array($_POST['plugin'], FILTER_SANITIZE_STRING);
    
    $plugin_files = get_plugin_files($plugin['key']);
    if (empty($plugin_files)) {
        $answer = array('result'=>'error', 'message'=>'Invalid request - unexisted plugin '. $plugin['key']);
        echo json_encode($answer);
        return;
    }
    
    foreach ($plugin_files as $plugin_file) {
      $ext = pathinfo($plugin_file, PATHINFO_EXTENSION);
      if ($ext=='php' || $ext=='PHP') {
        $fh = fopen(WP_PLUGIN_DIR.'/'.$plugin_file, 'r');
        if (!$fh) {
          continue;
        }
        while(!feof($fh)) {
          $s = fgets($fh);
          $s = strtolower($s);
          foreach ($_SESSION['plugins-garbage-collector']['tables'] as $table) {
            if (!empty($table->plugin_name)) { 
                continue;
            }
            if (strpos($s, $table->name_without_prefix)!==false) {
                $table->plugin_name = $plugin['title'].' '.$plugin['version'];
                $table->plugin_file = $plugin['key'];
            }            
          } // foreach()
        }
        fclose($fh);
      }
    }  

    $answer = array('result'=>'success', 'message'=> $plugin['title'] .' '. esc_html__(' checked', 'plugins-garbage-collector'));
    
    echo json_encode($answer);
}
// end of pgc_scan_plugin_for_db_tables_use()


function pgc_delete_unused_db_tables() {
  global $wpdb;

  $tablesToDelete = array();
  foreach ($_POST as $key=>$value) {
    if (strpos($key, 'delete_')!==false) {
      $tablesToDelete[] = substr($key, 7);
    }
  }
  if (count($tablesToDelete)==0) {
    return;
  }

  $dbName = DB_NAME;
  $actionResult = '';
  foreach($tablesToDelete as $tableToDelete) {
    if ($actionResult) {
      $actionResult .= ', ';
    }
    $query = "drop table `$dbName`.`$tableToDelete`";
    $wpdb->query($query);
    if ($wpdb->last_error) {
      if ($actionResult) {
        $actionResult = esc_html__('Tables are deleted: ', 'plugins-garbage-collector') . $actionResult;
      }
      return $actionResult.'<br/>'.$wpdb->last_error;
    }
    $actionResult .= ' '.$tableToDelete;
  }

  return esc_html__('Tables are deleted successfully: ', 'plugins-garbage-collector') . $actionResult;
}
// end of pgc_delete_unused_db_tables()


function pgc_display_box_start($title='', $style='') {
  $html = '<div class="postbox" '. (empty($style) ? '' : 'style="'.$style.'"') .'  >'. PHP_EOL;
  if (!empty($title)) {
      $html .= '<h3 style="cursor:default;"><span>'.$title.'</span></h3>'. PHP_EOL;
  }
		$html .= '<div class="inside">'. PHP_EOL;

  return $html;
}
// 	end of thanks_displayBoxStart()


function pgc_display_box_end() {
  $html = '
				</div>
			</div>';

  return $html;
}
// end of pgc_display_box_end()


function pgc_display_column_headers_none_wp() {
  $html = '<tr>
              <th>'. esc_html__('Hide','plugins-garbage-collector').'</th>
              <th>'. esc_html__('Table Name','plugins-garbage-collector').'</th>
              <th>'. esc_html__('Records #','plugins-garbage-collector').'</th>
              <th>'. esc_html__('KBytes #','plugins-garbage-collector').'</th>
              <th>'. esc_html__('Plugin Name','plugins-garbage-collector').'</th>
              <th>'. esc_html__('Plugin State','plugins-garbage-collector').'</th>
            </tr>';
   return $html;
}
// pgc_display_column_headers_none_wp()


function pgc_show_tables() {
    $tables = $_SESSION['plugins-garbage-collector']['tables'];
  $html =  esc_html__('Let\'s see what tables in your database do not belong to the core WordPress installation:', 'plugins-garbage-collector');
  if (count($tables)>0) {
    $pgc_settings = get_option('pgc_settings');
    $html .= '
       <table id="pgc_plugin_tables" class="widefat" style="clear:none;" cellpadding="0" cellspacing="0">
          <thead>';
    $html .= pgc_display_column_headers_none_wp();
    $html .= '
          </thead>
          <tbody>';

    $showHiddenTables = isset($_POST['show_hidden_tables']) && $_POST['show_hidden_tables'];
    $showDeleteTablesButton = false; $hiddenTableExists  = false;
    $i = 0;
    foreach ($tables as $table) {
      if ($i & 1) {
        $rowClass = 'class="pgc_odd"';
      } else {
        $rowClass = 'class="pgc_even"';
      }      
      $hiddenTable = isset($pgc_settings['hidden'][$table->name]);
      if ($hiddenTable && !$showHiddenTables) {  // skip this table
        $hiddenTableExists  = true;
        continue;
      }
      $i++;
      $html .= '<tr '.$rowClass.' id="'.$table->name.'" >
                  <td>';
      if ($table->plugin_state=='active') {
        if ($hiddenTable) {
          $checked = 'checked="checked"';          
        } else {
          $checked = '';
        }
        $html .= '<input type="checkbox" name="hidden_'.$table->name.'" id="hidden_'.$table->name.'" onclick="pgc_hide_table(this, \''.$table->name.'\')" '.$checked.' />
                  <img id="ajax_'.$table->name.'" class="ajax_processing" src="'. admin_url('images/loading.gif') .'" alt="ajax request processing..." title="AJAX request processing..."/>';
      }
      $html .= '</td>
            <td style="vertical-align:top;width:100px;" >';
      $deleteCheckBox = '';
      if (!$table->plugin_name) {
        $color = 'red';
        $deleteCheckBox = '<input type="checkbox" name="delete_'.$table->name.'" />';
        $showDeleteTablesButton = true;
      } else if ($table->plugin_state=='active') {
        $color = 'green';
      } else {
        $color = 'blue';
      }
      $html .= $deleteCheckBox.' <span style="color:'.$color.';">'.$table->name.'</span>';
      $html .= '
            </td>
            <td>
              <span style="color:'.$color.';">'.$table->records.'</span>
            </td>
            <td>
              <span style="color:'.$color.';">'.$table->kbytes.'</span>
            </td>
            <td>';
      if ($table->plugin_name) {
        $html .= '<span style="color:'.$color.';">'.$table->plugin_name.'</span>';
      } else {
        $html .= '<span style="color:red;">Unknown</span>';
      }
      $html .= '
            </td>
            <td>
              <span style="color:'.$color.';">'.$table->plugin_state.'</span>
            </td>
          </tr>';
    }
    $html .= '
          </tbody>
          <tfoot>';

    $html .= pgc_display_column_headers_none_wp();
    $html .= '
          </tfoot>
      </table>';
    if ($hiddenTableExists) {
      $html .= '<span style="color: #bbb; font-size: 0.8em;">'. esc_html__('Some tables are hidden by you. Turn on "Show hidden DB tables" option and click "Scan" button again to show them.', 'plugins-garbage-collector').'</span>';
    }
    if ($showDeleteTablesButton) {
      $html .= '
      <table>
        <tr>
          <td>
            <div class="submit">
              <input type="submit" name="drop_table_action" value="'. esc_html__('Delete Tables', 'plugins-garbage-collector').'"/>
            </div>
          </td>
          <td>
            <div style="padding-left: 10px;"><span style="color: red; font-weight: bold;">'.esc_html__('Attention!','plugins-garbage-collector').'</span> '.
              esc_html__('Operation rollback is not possible. Consider to make database backup first. Please double think before click "Delete Tables" button.','plugins-garbage-collector').'
            </div>
          </td>
        </tr>
      </table>';
    }
  } else {
    $html .= pgc_display_box_start() .'
    <span style="color: green; text-align: center; font-size: 1.2em;">'.
      esc_html__('Congratulations! It seems that your WordPress database is clean.','plugins-garbage-collector').'
    </span>'.
    pgc_display_box_end();
  }
  
  unset($_SESSION['plugins-garbage-collector']['tables']);

  return $html;
}
// end of pgc_show_tables()


function pgc_display_column_headers_wp() {
  $html = '<tr>
              <th>'.esc_html__('Hide','plugins-garbage-collector').'</th>
              <th>'.esc_html__('Table Name','plugins-garbage-collector').'</th>
              <th>'.esc_html__('Extra Field','plugins-garbage-collector').'</th>
              <th>'.esc_html__('Plugin Name','plugins-garbage-collector').'</th>
              <th>'.esc_html__('Plugin State','plugins-garbage-collector').'</th>
            </tr>';
   return $html;
}
// end of pgc_display_column_headers_wp()


// Get all of the field names in the query from between the parens
function pgc_extract_field_names($query) {

  $columns = array(); 
  $match2 = array();
  preg_match("|\((.*)\)|ms", strtolower($query), $match2);
  $line = trim($match2[1]);
  // Separate field lines into an array
  $fields = explode("\n", $line);  
  // For every field line specified in the query
  foreach($fields as $field) {
    $validfield = true;
    $field = trim($field);
    // Extract the field name
    $fvalue = array();
    preg_match("|^([^ ]*)|", $field, $fvalue);
    $fieldname = strtolower(trim($fvalue[1], '`' ));
    // Verify the found field name
    switch ($fieldname) {
      case '':
      case 'primary':
      case 'index':
      case 'fulltext':
      case 'unique':
      case 'key':
        $validfield = false;
        break;
    }

    // If it's a valid field, add it to the field array
    if ($validfield) {
      $columns[$fieldname] = 1;
    }
  }

  return $columns;
}
// pgc_extract_field_names()


function pgc_check_wp_tables_structure() {

    global $wpdb;
    
    $admin_path = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() );
    require_once($admin_path .'includes/schema.php');

    $wp_db_schema = wp_get_db_schema('all');
// Separate individual queries into an array
  $queries = explode(';', $wp_db_schema);
  if (''==$queries[count($queries)-1]) {
    array_pop($queries);
  }

  $wpTablesList = array();

  foreach ($queries as $query) {
    if (preg_match("|CREATE TABLE ([^ ]*)|", $query, $matches)) {
      $wpTablesList[trim( strtolower($matches[1]), '`' )] = $query;
    }
  }

  $changedTables = array();
  $i = 1;
  foreach ($wpTablesList as $table=>$createQuery) {
    update_option('pgc_scanprogress_current', $i);
    update_option('pgc_scanprogress_status', $table);

    // orginal structure columns list
    $origColumns = pgc_extract_field_names($createQuery);

    // fact structrue columns list
    $query = "describe $table";
    $factColumns = $wpdb->get_results($query);
    foreach ($factColumns as $factColumn) {
      if (!isset($origColumns[strtolower($factColumn->Field)])) {
        if (!isset($changedTables[$table])) {
          $changedTables[$table] = array();
        }        
        $changedTables[$table][$factColumn->Field] = new stdClass();
        $changedTables[$table][$factColumn->Field]->plugin_name = '';
        $changedTables[$table][$factColumn->Field]->plugin_state = '';
      }
    }
  } 

  if (count($changedTables)>0) {
    
    $html = '
       <table id="pgc_plugin_tables" class="widefat" style="clear:none;" cellpadding="0" cellspacing="0">
          <thead>'
      .pgc_display_column_headers_wp().
          '</thead>
          <tbody>';
    $pgc_settings = get_option('pgc_settings');
    $showHiddenTables = isset($_POST['show_hidden_tables']) && $_POST['show_hidden_tables'];
    $showDeleteButton = false; $hiddenTableExists  = false;
    $i = 0;
    foreach ($changedTables as $tableName=>$columnData) {
      foreach ($columnData as $column=>$plugin) {
      if ($i & 1) {
        $rowClass = 'class="pgc_odd"';
      } else {
        $rowClass = 'class="pgc_even"';
      }
      $hiddenTable = isset($pgc_settings['hidden'][$tableName]);
      if ($hiddenTable && !$showHiddenTables) {  // skip this table
        $hiddenTableExists  = true;
        continue;
      }
      $i++;
      $html .= '<tr '.$rowClass.' id="'.$tableName.'" >
                  <td>';
      if ($plugin->plugin_state=='active') {
        if ($hiddenTable) {
          $checked = 'checked="checked"';
        } else {
          $checked = '';
        }
        $html .= '<input type="checkbox" name="hidden_'.$tableName.'" id="hidden_'.$tableName.'" onclick="pgc_hide_table(this, \''.$tableName.'\')" '.$checked.' />
                  <img id="ajax_'.$tableName.'" class="ajax_processing" src="'. admin_url('images/loading.gif') .'" alt="ajax request processing..." title="AJAX request processing..."/>';
      }
      $html .= '</td>
            <td style="vertical-align:top;width:100px;" >';
      $deleteCheckBox = '';
      if (!$plugin->plugin_name) {
        $color = 'red';
        $deleteCheckBox = '<input type="checkbox" name="delete_'.$tableName.'" />';
        $showDeleteButton = true;
      } else if ($plugin->plugin_state=='active') {
        $color = 'green';
      } else {
        $color = 'blue';
      }
      $html .= $deleteCheckBox.' <span style="color:'.$color.';">'.$tableName.'</span>';
      $html .= '
            </td>
            <td><span style="color:'.$color.';">'.$column.'</span></td><td>';
      if ($plugin->plugin_name) {
        $html .= '<span style="color:'.$color.';">'.$plugin->plugin_name.'</span>';
      } else {
        $html .= '<span style="color:red;">unknown</span>';
      }
      $html .= '</td>
            <td><span style="color:'.$color.';">'.$plugin->plugin_state.'</span></td>
          </tr>';
      }
    }
    $html .= '</tbody>
          <tfoot>'
    .pgc_display_column_headers_wp().
          '</tfoot>
      </table>';
    if ($hiddenTableExists) {
      $html .= '<span style="color: #bbb; font-size: 0.8em;">'.esc_html__('Some tables are hidden by you. Turn on "Show hidden DB tables" option and click "Scan" button again to show them.', 'plugins-garbage-collector').'</span>';
    }
    if ($showDeleteButton) {
      $html .= '
      <table>
        <tr>
          <td>
            <div class="submit">
              <input type="submit" name="delete_extra_columns_action" value="'.esc_html__('Delete Extra Columns', 'plugins-garbage-collector').'"/>
            </div>
          </td>
          <td>
            <div style="padding-left: 10px;"><span style="color: red; font-weight: bold;">'.esc_html__('Attention!','plugins-garbage-collector').'</span> '.
              esc_html__('Operation rollback is not possible. Consider to make database backup first. Please double think before click <code>Delete Extra Columns</code> button.','plugins-garbage-collector').'
            </div>
          </td>
        </tr>
      </table>';
    }
  } else {
    $html = '
    <span style="color: green; text-align: center; font-size: 1.2em;">'.
      esc_html__('Congratulations! It seems that your WordPress database tables structure is not changed','plugins-garbage-collector').'
    </span>';
  }

  $result = array('result'=>'success', 'html'=>$html);
  
  echo json_encode($result);
}
// end of pgc_check_wp_tables_structure()



function pgc_delete_extra_columns_from_wp_tables() {

  $message = esc_html__('This feature is still under development and will be realized in the future version', 'plugins-garbage-collector');
  
  return $message;
}
// end of pgc_deleteExtraColumnsFromWPTables()


function pgc_get_plugins_list() {
    $_SESSION['plugins-garbage-collector'] = null;
    $_SESSION['plugins-garbage-collector']['tables'] = pgc_get_not_wp_tables();
    $plugins = get_plugins();
    $plugins_list = array();
    foreach($plugins as $key=>$plugin) {
        $plugin_short = new stdClass();
        $plugin_short->key = $key;
        $plugin_short->title = $plugin['Title'];
        $plugin_short->version = $plugin['Version'];
        $plugins_list[] = $plugin_short;
    }
    
    $answer = array('result'=>'success', 'plugins_list'=>$plugins_list);
    
    echo json_encode($answer);
}


function pgc_get_plugins_scan_results() {
    
    pgc_set_plugin_state_for_tables();
    $html = pgc_show_tables();
    $result = array('result'=>'success', 'html'=>$html);
    
    echo json_encode($result);
}
// end of pgc_scan_db_tables()



function pgc_get_table_name_from_post() {
    global $wpdb;
        
    $table_name = filter_input(INPUT_POST, 'table_name', FILTER_SANITIZE_STRING);
    if (empty($table_name)) {
        $answer = array('result'=>'error', 'message'=>'Wrong request - required parameter table_name is missed.');
        echo json_encode($answer);
        return false;
    }
        
    return $table_name;
}
// end of pgc_get_table_name_from_post()


function pgc_hide_table() {
    
    $table_name = pgc_get_table_name_from_post();
    if (empty($table_name)) {
        return;
    }
       
    $pgc_settings = get_option('pgc_settings');
    if (!$pgc_settings) {
        $pgc_settings = array();
        $pgc_settings['hidden'] = array();
    }
    $pgc_settings['hidden'][$table_name] = 1;
    update_option('pgc_settings', $pgc_settings);
    $answer = array('result'=>'success', 'message'=> esc_html__('Table was hidden', 'plugins-garbage-collector'));
    echo json_encode($answer);
}
// end of pgc_hide_table()


function pgc_show_table() {
    $table_name = pgc_get_table_name_from_post();
    if (empty($table_name)) {
        return;
    }
    
    $pgc_settings = get_option('pgc_settings');
    if (!$pgc_settings) {
        $pgc_settings = array();
        $pgc_settings['hidden'] = array();
    } else if (isset($pgc_settings['hidden'][$table_name])) {
        unset($pgc_settings['hidden'][$table_name]);
    }
    update_option('pgc_settings', $pgc_settings);
    $answer = array('result'=>'success', 'message'=>esc_html__('Table was shown', 'plugins-garbage-collector'));
    echo json_encode($answer);
}
// end of pgc_show_table()


function plugins_garbage_collector_ajax() {
    if (!check_ajax_referer( "plugins-garbage-collector" )) {
        echo json_encode(array('result'=>false, 'message'=>'Error: wrong request, wp nonce value is not valid'));
        
        return false;
    }
    
    if (!isset($_POST['subaction'])) {
        echo json_encode(array('result'=>false, 'message'=>'Error: wrong action'));
        return false;
    }
    
    if (!session_id()) {
        @session_start();
    }
    
    $action = filter_input(INPUT_POST, 'subaction', FILTER_SANITIZE_STRING);
    switch ($action) {
        case 'get-plugins-list': {
            pgc_get_plugins_list();
            break;
        }
        case 'scan-plugin-for-db-tables-use': {
            pgc_scan_plugin_for_db_tables_use();
            break;
        }
        case 'get-plugins-scan-results': {
            pgc_get_plugins_scan_results();
            break;
        }
        case 'check-wp-tables-structure': {
            pgc_check_wp_tables_structure();            
            break;
        }
        case 'show-progress': {
            pgc_show_progress();
            break;
        }
        case 'hide-table': {
            pgc_hide_table();
            break;
        }
        case 'show-table': {
            pgc_show_table();
            break;
        }
        default: {
            echo json_encode(array('result'=>'error', 'message'=>'Unknown action'));
        }
                
    }   // end of switch    
    
    wp_die();
    
}
// end of plugins_garbage_collector_ajax()
