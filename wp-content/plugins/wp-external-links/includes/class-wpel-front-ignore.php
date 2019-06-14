<?php
/**
 * Class WPEL_Front_Ignore
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 */
final class WPEL_Front_Ignore extends WPRun_Base_1x0x0
{

    /**
     * @var array
     */
    private $content_placeholders = array();

    /**
     * @var WPEL_Settings_Page
     */
    private $settings_page = null;

    /**
     * Initialize
     * @param WPEL_Settings_Page $settings_page
     */
    protected function init( WPEL_Settings_Page $settings_page )
    {
        $this->settings_page = $settings_page;
    }

    /**
     * Get option value
     * @param string $key
     * @param string|null $type
     * @return string
     * @triggers E_USER_NOTICE Option value cannot be found
     */
    protected function opt( $key, $type = null )
    {
        return $this->settings_page->get_option_value( $key, $type );
    }

    /**
     * Skip complete pages
     * @return boolean
     */
    protected function filter_wpel_apply_settings()
    {
        if ( ! is_single() && ! is_page() ) {
            return true;
        }

        $current_post_id = get_queried_object_id();
        $skip_post_ids = $this->opt( 'skip_post_ids', 'exceptions' );
        $skip_post_ids_arr = explode( ',', $skip_post_ids );

        foreach ( $skip_post_ids_arr as $post_id ) {
            if ( intval( $post_id ) === $current_post_id ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Action for "wpel_before_apply_link"
     * @param WPEL_Link $link
     */
    protected function action_wpel_before_apply_link_10000000000( WPEL_Link $link )
    {
        // ignore mailto links
        if ( $this->opt( 'ignore_mailto_links' ) && $link->is_mailto() ) {
            $link->set_ignore();
        }

        // ignore WP Admin Bar Links
        if ( $link->has_attr_value( 'class', 'ab-item' ) ) {
            $link->set_ignore();
        }

        // ignore links containing ignored classes
        if ( $this->has_ignore_class( $link ) ) {
            $link->set_ignore();
        }
    }

    private function has_ignore_class( WPEL_Link $link )
    {
        $ignore_classes = $this->opt( 'ignore_classes', 'exceptions' );
        $ignore_classes_arr = explode( ',', $ignore_classes );

        foreach ( $ignore_classes_arr as $ignore_class ) {
            if ( $link->has_attr_value( 'class', trim( $ignore_class ) ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Filter for "_wpel_before_filter"
     * @param string $content
     * @return string
     */
    protected function filter__wpel_before_filter_10000000000( $content )
    {
        $ignore_tags = array( 'head' );

        if ( $this->opt( 'ignore_script_tags' ) ) {
            $ignore_tags[] = 'script';
        }

        foreach ( $ignore_tags as $tag_name ) {
            $content = preg_replace_callback(
                $this->get_tag_regexp( $tag_name )
                , $this->get_callback( 'skip_tag' )
                , $content
            );
        }

        return $content;
    }

    /**
     * Filter for "_wpel_after_filter"
     * @param string $content
     * @return string
     */
    protected function filter__wpel_after_filter_10000000000( $content )
    {
       return $this->restore_content_placeholders( $content );
    }

    /**
     * @param type $tag_name
     * @return type
     */
    protected function get_tag_regexp( $tag_name )
    {
        return '/<'. $tag_name .'[\s.*>|>](.*?)<\/'. $tag_name .'[\s+]*>/is';
    }

    /**
     * Pregmatch callback
     * @param array $matches
     * @return string
     */
    protected function skip_tag( $matches )
    {
        $skip_content = $matches[ 0 ];
        return $this->get_placeholder( $skip_content );
    }

    /**
     * Return placeholder text for given content
     * @param string $placeholding_content
     * @return string
     */
    protected function get_placeholder( $placeholding_content )
    {
        $placeholder = '<!--- WPEL PLACEHOLDER '. count( $this->content_placeholders ) .' --->';
        $this->content_placeholders[ $placeholder ] = $placeholding_content;
        return $placeholder;
    }

    /**
     * Restore placeholders with original content
     * @param string $content
     * @return string
     */
    protected function restore_content_placeholders( $content )
    {
        foreach ( $this->content_placeholders as $placeholder => $placeholding_content ) {
            $content = str_replace( $placeholder, $placeholding_content, $content );
        }

        return $content;
    }

}

/*?>*/
