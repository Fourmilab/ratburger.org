<?php
/**
/*
 * Plugin Name:   RB Notifications
 * Plugin URI:
 * Description:   Lightweight query for current unread BuddyPress notifications with JSON return
 * Version:       1.0
 * Author:        John Walker
 * Author URI:    https://www.fourmilab.ch/
 * License: GPLv3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

    //  rb_notifications  --  Process query for notifications

    function rb_notifications() {

        //  Process arguments

        $rb_not_max = 100000000;        // rb_not_max = n           Maximum number to return
        if (isset($_GET["rb_not_max"])) {
            $rb_not_max = intval($_GET["rb_not_max"]);
        }

        $rb_not_order = "ASC";          // rb_not_order = ASC/DESC  Sort order
        if (isset($_GET["rb_not_order"])) {
            $rb_not_order = sanitize_text_field($_GET["rb_not_order"]);
        }

        header("Content-Type: application/json; charset=utf-8");

        $result = "{\n    \"source\": \"rb_notifications\",\n" .
                  "    \"version\": \"1.0\",\n";

        if (is_user_logged_in()) {
            $result .= "    \"user_id\": " . bp_loggedin_user_id() . ",\n";

            //  Messages

            $result .= "    \"unread_messages_count\": " .
                bp_get_total_unread_messages_count(bp_loggedin_user_id()) . ",\n";

            //  Notifications

            if (bp_has_notifications(
                    array("is_new" => 1,
                          "sort_order" => $rb_not_order,
                          "max" => false,
                          "per_page" => $rb_not_max))) {
                $result .= "    \"unread_notifications_head\": " .
                           json_encode("<li id=\"wp-admin-bar-notification-mark-all-read\">" .
                           "<a class=\"ab-item\" href=\"" .
                           bp_get_root_domain() . "/members/" .
                           wp_get_current_user()->user_login .
                           "/notifications/unread/" .
                           wp_nonce_url("", "bp_notification_mark_read_all") .
                           "&action=read&notification_id=all\">" .
                           "<span class=\"rb_notif_mark_all_read rb_notif_highlight\">" .
                           "Mark all notifications read</span></a></li>") . ",\n";
                $result .= "    \"unread_notifications_count\": " .
                    buddypress()->notifications->query_loop->total_notification_count . ",\n";
                $result .= "    \"unread_notifications\": [\n";
                while (bp_the_notifications()) {
                    $notif = bp_the_notification();
                    $notf = (object) array(
                        "id" => bp_get_the_notification_id(),
                        "item" => bp_get_the_notification_item_id(),
                        "sec" => bp_get_the_notification_secondary_item_id(),
                        "comp" => bp_get_the_notification_component_name(),
                        "action" => bp_get_the_notification_component_action(),
                        "time" => bp_get_the_notification_date_notified(),
                        "desc" => bp_get_the_notification_description()
                    );
                    $result .= "        " . json_encode($notf) . ",\n";
                }
                $result = substr($result, 0, strlen($result) - 2) . "\n    ]\n";
            } else {
                $result .= "    \"unread_notifications_head\": " .
                           json_encode("<li id=\"wp-admin-bar-no-notifications\">" .
                           "<a class=\"ab-item\" href=\"" .
                           trailingslashit(bp_loggedin_user_domain() . bp_get_notifications_slug()) . "\">" .
                           "No new notifications</a></li>") . ",\n";
                $result .= "    \"unread_notifications_count\": 0\n";
            }
        } else {
            $result .= "    \"user_id\": 0\n";      // No user logged in
        }

        $result .= "}\n";
        print($result);
    }

    //  rb_notifications_query_vars  --  Register our query with the redirector

    add_filter("query_vars", "rb_notifications_query_vars");

    function rb_notifications_query_vars($query_vars) {
        $query_vars[] = "rb_notifications";
        return $query_vars;
    }

    //  rb_notifications_parse_request  --  Parse request from redirected URL

    add_action("parse_request", "rb_notifications_parse_request");

    function rb_notifications_parse_request($wp) {
        if (array_key_exists("rb_notifications", $wp->query_vars)) {
            rb_notifications();
            die();
        }
    }

    //  rb_notifications_register_script  --  Register our JavaScript to be loaded

    add_action("wp_enqueue_scripts", "rb_notifications_register_script");
    add_action("admin_enqueue_scripts", "rb_notifications_register_script");

    function rb_notifications_register_script() {
//if (RB_me()) {
        //  Enqueue and register JavaScript in the footer
        wp_enqueue_script('rb_notifications', plugins_url("/js/rb-notifications.js" , __FILE__),
            array(), "1.0", true);
        //  Enqueue and register our CSS style sheet
        wp_enqueue_style('rb_notifications', plugins_url("/css/rb-notifications.css" , __FILE__),
            array(), "1.0", "all");
//}
    }
?>
