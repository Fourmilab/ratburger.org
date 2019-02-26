    /* RATBURGER LOCAL CODE */

    /*  Test if we're running on the "Raw" test server.  If so,
        change the site name in the administration bar to alert
        the user that this is not the production site.  When
        testing, it's all too easy to change administration
        settings on the production site when you intend for the
        change to be on the test site.  This will make this
        far less likely to happen.

        This JavaScript is embedded into the body of user and
        administration pages by the rb_enqueue_check_test_server()
        PHP function in the theme's functions.php file, which is
        invoked via the wp_enqueue_scripts and admin_enqueue_scripts
        add_action() hooks.  */

    function RB_check_test_server() {
        if (location.host == "raw.ratburger.org") {
            var siteTitle = document.getElementById("wp-admin-bar-site-name");
            if (siteTitle) {
                var s = siteTitle.innerHTML;
                if (s) {
                    siteTitle.innerHTML = s.replace(/>Ratburger</,
                        "><span style='background-color: red;'>&nbsp;Ratburger RAW&nbsp;</span><");
                }
            }
        }
    }
    //  Call via jQuery so we don't run until page fully loaded
    jQuery(RB_check_test_server);

    /* END RATBURGER LOCAL CODE */
