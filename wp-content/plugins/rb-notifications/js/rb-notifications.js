    /*
            Client-pull notification query and update

        This JavaScript module is loaded on every user and
        administration page by the main PHP plug-in file.  It starts a
        timer and periodically submits a ?rb_notifications request to
        the server, receives the JSON reply, extracts the unread
        notifications and messages, and updates the information in the
        administration bar and its menus.  The time intervals for the
        updates are set by the variables below. */

    "use strict";

    var rb_notifications_initial = 250;         // Update notification initial time, ms
    var rb_notifications_interval = 300000;     // Update notification interval, ms
    var rb_notifications_last_update = 0;       // Millisecond time of last update
    var rb_notifications_min_interval = 30000;  // Minimum time between updates
    var rb_notifications_max_menu = 45;         // Maximum notifications to return for menu

    var rb_notifications_timer = null;          // Update notification timer

    //  Flag notification numbers for debugging

    function rb_notification_flag(n) {
//      return "{" + n + "}";
      return n;
    }

    //  Test if we're running inside an iframe

    function rb_notifications_inIframe() {
        try {
            return window.self !== window.top;
        } catch (e) {
            return true;
        }
    }

    //  Test whether the current page is fully or partially visible

    function rb_notifications_isPageVisible() {
        if (document.visibilityState) {
            //  W3C Page Visibility Level 2 supported
            return document.visibilityState == "visible";
        }
        //  Hack for visibility test in older browsers
        return !(document.hidden || document.msHidden ||
                 document.webkitHidden || document.mozHidden);
    }

    //  Perform a HTTP query and receive a JSON reply

    function rb_notifications_getJSON(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.responseType = "json";
        xhr.onload = function() {
            var status = xhr.status;
            if (status == 200) {
                callback(null, xhr.response);
            } else {
                callback(status, xhr.response);
            }
        };
        xhr.send();
    }

    //  Update the notification information in the admin bar

    function rb_notifications_updateNotifications() {

        /*  We update only if:
                We're not in an iframe
                The user is logged in and notifications are shown
                The page is visible (see RB_isPageVisible() above)
                More time than RB_notif_min_interval has elapsed
                    since the last update.  */
        if ((!rb_notifications_inIframe()) && rb_notifications_isPageVisible() &&
            ((((new Date()).getTime()) - rb_notifications_last_update) >= rb_notifications_min_interval) &&
            document.getElementById("wp-admin-bar-bp-notifications")) {

            rb_notifications_getJSON(location.origin + "/?rb_notifications&rb_not_max=" + rb_notifications_max_menu,
                function(stat, n) {
                    if (!stat) {
//console.log("Retrieved notifications.");

                        //  Patch references to number of unread notifications

                        var unread = rb_notification_flag(n.unread_notifications_count);

                        //  Notifications bubble in admin bar, including class
                        document.getElementById("ab-pending-notifications").innerHTML = unread;
                        document.getElementById("ab-pending-notifications").setAttribute("class",
                            (n.unread_notifications_count == 0) ? "count no-alert" : "pending-count alert");

                        //  Unread count in avatar drop-down and fly-out menus
                        var ucount = (n.unread_notifications_count == 0) ? "" :
                            " <span class=\"count\">" + unread + "</span>";
                        document.getElementById("wp-admin-bar-my-account-notifications").innerHTML =
                            document.getElementById("wp-admin-bar-my-account-notifications").innerHTML.replace(
                                /(<\/span>Notifications).*?(<\/a>)/, "$1" + ucount + "$2");
                        document.getElementById("wp-admin-bar-my-account-notifications").innerHTML =
                            document.getElementById("wp-admin-bar-my-account-notifications").innerHTML.replace(
                                /(>Unread).*?(<\/a>)/, "$1" + ucount + "$2");

                        //  Replace drop-down notifications menu
                        rb_notifications_updateDropdownMenu(n);

                        //  Update count of unread Inbox messages
                        ucount = (n.unread_messages_count == 0) ? "" :
                            " <span class=\"count\">" + rb_notification_flag(n.unread_messages_count) + "</span>";
                        document.getElementById("wp-admin-bar-my-account-messages").innerHTML =
                            document.getElementById("wp-admin-bar-my-account-messages").innerHTML.replace(
                                /(<\/span>Messages).*?(<\/a>)/, "$1" + ucount + "$2");
                        document.getElementById("wp-admin-bar-my-account-messages-inbox").innerHTML =
                            document.getElementById("wp-admin-bar-my-account-messages-inbox").innerHTML.replace(
                                /(>Inbox).*?(<\/a>)/, "$1" + ucount + "$2");
 
                        rb_notifications_last_update = (new Date()).getTime();
                   }
else { console.log("Error " + stat + " querying notifications."); }
                }
            );
        }
//else { console.log("Notifications update skipped."); }

        // Wind the cat
        rb_notifications_timer = window.setTimeout(rb_notifications_updateNotifications, rb_notifications_interval);
    }

    //  Cancel any running timer and force an immediate update

    function rb_notifications_forceUpdate() {
        if (rb_notifications_timer !== null) {
            window.clearTimeout(rb_notifications_timer);
            rb_notifications_timer = null;
        }
        rb_notifications_last_update = 0;
        rb_notifications_updateNotifications();
    }

    //  Update the notifications drop-down menu

    function rb_notifications_updateDropdownMenu(n) {
        var u = n.unread_notifications_head;

        //  Append notification menu items
        if (n.unread_notifications) {
            for (var i = 0; i < n.unread_notifications.length; i++) {
                u += rb_notifications_transformMenuItem(n.unread_notifications[i].desc,
                    n.unread_notifications[i].id,
                    n.unread_notifications[i].action);
            }
        }

        document.getElementById("wp-admin-bar-bp-notifications-default").innerHTML = u;
    }

    //  Transform a notification link to a menu item in our style

    function rb_notifications_transformMenuItem(i, notID, notAction) {
        //  Determine class for link text from notification action
        var custom_class = "";
        /*  The following code should set custom_class the same as the
            code in ~/plug/wp-ulike/inc/general-hooks.php function
            wp_ulike_format_buddypress_notifications().  */
        if (notAction.match(/^wp_ulike_/)) {
            //  Notifications from WP ULike and our custom code
            switch (notAction.match(/wp_ulike_(.*?)_action/)[1]) {
                case "liked":               // Post liked
                    custom_class = "rb_notif_post_like rb_notif_highlight";
                    break;

                case "topicliked":          // Group post liked
                    custom_class = "rb_notif_group_topic_like rb_notif_highlight";
                    break;

                case "commentliked":        // Comment on post liked
                    custom_class = "rb_notif_comment_like rb_notif_highlight";
                    break;

                case "activityliked":       // Group post or comment liked
                    var what = i.match(/liked your (\w+) in group/)[1];
                    custom_class = "rb_notif_group_" + what + "_like rb_notif_highlight";
                    break;

                case "commentadded":        // New comment
                    custom_class = "rb_notif_new_comment rb_notif_highlight";
                    break;

                case "grouppost":           // New post in group
                    custom_class = "rb_notif_new_group_post rb_notif_highlight";
                    break;

                case "groupcomment":        // New comment in a group
                    custom_class = "rb_notif_new_group_comment rb_notif_highlight";
                    break;
            }
            //  Notifications from core BuddyPress
        } else if (notAction == "new_message") {
            custom_class = "rb_notif_new_message rb_notif_highlight";
        }
        i = i.replace(/\s+title=".*?"/, "");            // Elide title= attribute
        i = i.replace(/^<a\s+href=/, "<a class=\"ab-item\" href=");  // Insert class for link
        i = i.replace(/^(<a.*?"\s*>)\s*([^<]*)(<\/a>)/,
            "$1<span class=\"" + custom_class + "\">$2</span>$3");
        return "<li id=\"wp-admin-bar-notification-" + notID + "\">" + i + "</li>";
    }

    /*  If we're not inside the RB_notif_update iframe
        schedule the first notification update.  This also
        guarantees we'll have had plenty of time for the page
        to load before we need to reference elements within it.  */

    if (!rb_notifications_inIframe()) {
        rb_notifications_timer =
            window.setTimeout(rb_notifications_updateNotifications,
                              rb_notifications_initial);
        rb_notifications_last_update = 0;
//console.log("Starting notifications update timer.");

        /*  If the browser supports W3C Page Visibility Level 2,
            detect when the page becomes visible after being hidden,
            cancel any existing update timer, and perform an immediate
            update (which will restart the timer).  This causes the
            notifications to be immediately updated when the user opens
            a minimised window or makes a tab active which is viewing
            the site.  */

        if (document.visibilityState) {
            document.addEventListener("visibilitychange", function() {
//console.log("Document is " + document.visibilityState);
                if (document.visibilityState == "visible") {
                    rb_notifications_forceUpdate();
                }
            });
        } else {
            /*  For antiquated browsers, try to hack it with an onfocus
                handler.  This seems to work in some but not in others.  */
             window.onfocus = function(e) {
//console.log("Onfocus: Notifications restarted.");
                rb_notifications_forceUpdate();
            };
        }
    }
