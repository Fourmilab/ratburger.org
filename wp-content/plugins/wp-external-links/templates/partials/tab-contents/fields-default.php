<?php
/**
 * Tab Default Content
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 *
 * @var array $vars
 *      @option array  "tabs"
 *      @option string "current_tab"
 */

$current_tab = $vars [ 'current_tab' ];
$tab_values = $vars[ 'tabs' ][ $current_tab ];
$fields = $tab_values[ 'fields' ];

settings_fields( $fields->get_setting( 'option_group' ) );
do_settings_sections( $fields->get_setting( 'page_id' ) );
