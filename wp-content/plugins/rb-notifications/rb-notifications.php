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
                  "    \"version\": \"1.0\",\n" .
                  "    \"status\": 200,\n";

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
                $t = time();
                $secsig = wp_create_nonce("rb-markread-t-" . $t);
                $result .= "    \"unread_notifications_head\": " .
                           json_encode("<li id=\"wp-admin-bar-notification-mark-all-read\">" .
                           "<a class=\"ab-item\" href=\"#\" " .
                           "onclick=\"rb_markread(" .
                           $t . ", '" . $secsig . "'); return false;\">" .
                           "<span class=\"rb_notif_mark_all_read rb_notif_highlight\">" .
                           "Mark all notifications read</span></a></li>") . ",\n";
/* OBSOLETE
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
*/
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
                           trailingslashit(bp_loggedin_user_domain() . bp_get_notifications_slug()) . "read\">" .
                           "No new notifications</a></li>") . ",\n";
                $result .= "    \"unread_notifications_count\": 0\n";
            }
        } else {
            $result .= "    \"user_id\": 0\n";      // No user logged in
        }

        $result .= "}\n";
        print($result);
    }

    /*  rb_catchup  --  Scan for notifications to mark read for the
                        item and time given by query arguments.
                        If $counting is true, the number of items
                        to be marked read is returned as an integer.
                        Otherwise, the items are actually marked read
                        and rb_notifications() is called to prepare
                        and output a JSON list of notifications after
                        marking those selected read.  In any case,
                        the number marked read is returned.  */

    function rb_catchup($counting = false, $co_time = 0, $co_what = "post", $co_id = 0) {

        if ($counting) {
            $rb_ca_time = $co_time;
            $rb_ca_what = $co_what;
            $rb_ca_id = $co_id;
        } else {
            //  Process arguments

            //  Unix time to catch up to
            $rb_ca_time = time();
            if (isset($_GET["rb_ca_time"])) {
                $rb_ca_time = intval($_GET["rb_ca_time"]);
            }

            //  Type of catch up
            $rb_ca_what = "post";
            if (isset($_GET["rb_ca_what"])) {
                $rb_ca_what = $_GET["rb_ca_what"];
            }

            //  Identifier of item we're catching up
            $rb_ca_id = 0;
            if (isset($_GET["rb_ca_id"])) {
                $rb_ca_id = intval($_GET["rb_ca_id"]);
            }

            //  Security hash
            $rb_ca_hash = "";
            if (isset($_GET["rb_ca_hash"])) {
                $rb_ca_hash = $_GET["rb_ca_hash"];
            }

            //  Validate security hash

            $reqsig = "k-" . $rb_ca_what . "-t-" . $rb_ca_time . "-i-" . $rb_ca_id;
            if (!wp_verify_nonce($rb_ca_hash, $reqsig)) {
                $result = "{\n    \"source\": \"rb_notifications\",\n" .
                          "    \"version\": \"1.0\",\n" .
                          "    \"status\": 403,\n" .
                          "    \"error_message\": \"invalid security hash on rb_catchup request\"\n" .
                          "}\n";
error_log($result);

                header("Content-Type: application/json; charset=utf-8");
                print($result);
                return;
            }
        }

        $ntomark = 0;                       // Number to mark read

        if (is_user_logged_in()) {
            if (bp_has_notifications(
                array("is_new" => 1,
                      "sort_order" => "ASC",
                      "max" => false,
                      "per_page" => 100000000))) {

                while (bp_the_notifications()) {

                    $notif = bp_the_notification();

                    /*  If the notification is later than the catch-up
                        date, ignore it.  */

                    $ntime = bp_get_the_notification_date_notified();
                    $RB_time = explode(':', str_replace(' ', ':', $ntime));
                    $RB_date = explode('-', str_replace(' ', '-', $ntime));
                    $RB_not_time  = gmmktime((int) $RB_time[1], (int) $RB_time[2], (int) $RB_time[3],
                                         (int) $RB_date[1], (int) $RB_date[2], (int) $RB_date[0]);

                    $mark_read = FALSE;

                    if ($RB_not_time < $rb_ca_time) {

                        $action = bp_get_the_notification_component_action();
                        preg_match('/wp_ulike_(.*?)_action/', $action, $type);

                        //  Catching up on a post

                        if ($rb_ca_what == "post") {

                            //  This post liked

                            if ($type[1] == "liked") {
                                if (bp_get_the_notification_item_id() == $rb_ca_id) {
                                    $mark_read = TRUE;          // Like of this post
                                }
                            }

                            //  Comment on this post liked

                            elseif ($type[1] == "commentliked") {
                                if (get_comment(bp_get_the_notification_item_id())->comment_post_ID ==
                                    $rb_ca_id) {
                                    $mark_read = TRUE;          // Like of comment on this post
                                }
                            }

                            //  New comment on this post

                            elseif ($type[1] == "commentadded") {
                                if (get_comment(bp_get_the_notification_item_id())->comment_post_ID ==
                                    $rb_ca_id) {
                                    $mark_read = TRUE;          // New comment on this post
                                }
                            }
                        }

                        //  Catching up on a group

                        elseif ($rb_ca_what == "group") {

                            //  New post in group

                            if ($type[1] == "grouppost") {
                                if (bp_get_the_notification_secondary_item_id() == $rb_ca_id) {
                                    $mark_read = TRUE;
                                }
                            }

                            //  New comment in group

                            elseif ($type[1] == "groupcomment") {
                                $RB_grp = (new BP_Groups_Group((new BP_Activity_Activity((new BP_Activity_Activity(bp_get_the_notification_item_id()))->item_id))->item_id))->id;
                                if ($RB_grp == $rb_ca_id) {
                                    $mark_read = TRUE;
                                }
                            }

                            //  Group post or comment liked

                            elseif ($type[1] == "activityliked") {
                                $zzitem = bp_get_the_notification_item_id();
                                $zzact = new BP_Activity_Activity($zzitem); // Activity for comment
                                if ($zzact->type == "activity_comment") {
                                    $zzact = new BP_Activity_Activity($zzact->item_id); // Activity for parent group
                                }
                                $zzgrp = new BP_Groups_Group($zzact->item_id); // Parent group object
                                if ($zzgrp->id == $rb_ca_id) {
                                    $mark_read = TRUE;
                                }
                            }
                        }
                    }

                    if ($mark_read) {
                        $ntomark++;
                        if (!$counting) {
                            /*  Note that we can't use
                                bp_notifications_mark_notification()
                                here because it requires to be
                                called in the context of a profile
                                page.  We must call the lower level
                                update method here in order to
                                bypass that check.  */
                            $mr = BP_Notifications_Notification::update(
                                            array("is_new" => false),
                                            array("id" => bp_get_the_notification_id())
                                      );
                        }
                    }
                }
            }
        }

        if (!$counting) {
            rb_notifications();
        }

        return $ntomark;
   }

    /*  rb_markread  --  Marks all notifications which were posted
                         after the Unix time specified by the
                         rb_ca_time argument read.  The items
                         are marked read and rb_notifications()
                         is called to prepare and output a JSON
                         list of notifications after marking
                         those selected read.  The number marked
                         read is returned.  */


    function rb_markread() {

        //  Process arguments

        //  Unix time to catch up to
        $rb_ca_time = time();
        if (isset($_GET["rb_ca_time"])) {
            $rb_ca_time = intval($_GET["rb_ca_time"]);
        }

        //  Security hash
        $rb_ca_hash = "";
        if (isset($_GET["rb_ca_hash"])) {
            $rb_ca_hash = $_GET["rb_ca_hash"];
        }

        //  Validate security hash

        if (!wp_verify_nonce($rb_ca_hash, "rb-markread-t-" . $rb_ca_time)) {
            $result = "{\n    \"source\": \"rb_notifications\",\n" .
                      "    \"version\": \"1.0\",\n" .
                      "    \"status\": 403,\n" .
                      "    \"error_message\": \"invalid security hash on rb_markread request\"\n" .
                      "}\n";
error_log($result);

            header("Content-Type: application/json; charset=utf-8");
            print($result);
            return;
        }

        $ntomark = 0;                       // Number to mark read

        if (is_user_logged_in()) {
            if (bp_has_notifications(
                array("is_new" => 1,
                      "sort_order" => "ASC",
                      "max" => false,
                      "per_page" => 100000000))) {

                while (bp_the_notifications()) {

                    $notif = bp_the_notification();

                    /*  If the notification is later than the catch-up
                        date, ignore it.  */

                    $ntime = bp_get_the_notification_date_notified();
                    $RB_time = explode(':', str_replace(' ', ':', $ntime));
                    $RB_date = explode('-', str_replace(' ', '-', $ntime));
                    $RB_not_time  = gmmktime((int) $RB_time[1], (int) $RB_time[2], (int) $RB_time[3],
                                         (int) $RB_date[1], (int) $RB_date[2], (int) $RB_date[0]);

                    $mark_read = $RB_not_time < $rb_ca_time;

                    if ($RB_not_time <= $rb_ca_time) {
                        $ntomark++;
                        $mr = BP_Notifications_Notification::update(
                                        array("is_new" => false),
                                        array("id" => bp_get_the_notification_id())
                                  );
                    }
                }
            }
        }

        rb_notifications();

        return $ntomark;
   }

    //  rb_notifications_query_vars  --  Register our queries with the redirector

    add_filter("query_vars", "rb_notifications_query_vars");

    function rb_notifications_query_vars($query_vars) {
        $query_vars[] = "rb_notifications";
        $query_vars[] = "rb_catchup";
        $query_vars[] = "rb_markread";
        return $query_vars;
    }

    //  rb_notifications_parse_request  --  Parse request from redirected URL

    add_action("parse_request", "rb_notifications_parse_request");

    function rb_notifications_parse_request($wp) {
        if (array_key_exists("rb_catchup", $wp->query_vars)) {
            rb_catchup(false);
            die();
        } elseif (array_key_exists("rb_markread", $wp->query_vars)) {
            rb_markread();
            die();
        } elseif (array_key_exists("rb_notifications", $wp->query_vars)) {
            rb_notifications();
            die();
        }
    }

    //  rb_notifications_register_script  --  Register our JavaScript to be loaded

    add_action("wp_enqueue_scripts", "rb_notifications_register_script");
    add_action("admin_enqueue_scripts", "rb_notifications_register_script");

    function rb_notifications_register_script() {
        //  Enqueue and register JavaScript in the footer
        wp_enqueue_script('rb_notifications', plugins_url("/js/rb-notifications.js" , __FILE__),
            array(), "1.0", true);
        //  Enqueue and register our CSS style sheet
        wp_enqueue_style('rb_notifications', plugins_url("/css/rb-notifications.css" , __FILE__),
            array(), "1.0", "all");
    }

    /*  rb_notifications_group_catchup  --  Add catch-up to BuddyPress
                                            group navigation menu  */

    if (function_exists("is_buddypress")) {     // Is BuddyPress installed ?
        add_action("bp_group_options_nav", "rb_notifications_group_catchup");
    }

    function rb_notifications_group_catchup() {
        $rb_markread = rb_catchup(true, time(), "group", bp_get_current_group_id());
        if ($rb_markread > 0) {
            $nots = ($rb_markread == 1) ? "" : "s";
            echo("<li id=\"rb-catchup-li\"><a id='rb_bp_group_catchup' href='#' ".
                 "onclick=\"rb_catchup('group', " .
                        bp_get_current_group_id() . ", " . time() . ", " .
                        "'" . wp_create_nonce("k-group-t-" . time() . "-i-" .
                                              bp_get_current_group_id()) . "'" .
                        "); return false;\">Clear " .
                        $rb_markread . " notification" . $nots .
                        "</a></li>");
        }
    }
?>
