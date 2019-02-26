    /* RATBURGER LOCAL CODE */

    /*  Administration Toolbar Clock

        If the clock is present in the administration
        toolbar, set to update it.  We do not update the clock
        if we're in an iframe, as that means we're just the
        update notifications page which the user never sees.  */

    var RB_clock = null;                // Clock text item, if any
    var RB_clock_UTC = true;            // Show clock in UTC
    var RB_clock_timer = -1;            // Clock animation timer

    function RB_wind_clock() {
        if (!RB_clock_inIframe()) {
            RB_clock = document.getElementById("rb_toolbar_clock");
            if (RB_clock) {
                RB_update_clock();
                /*  Add an event listener for clicks within the
                    clock display.  Each click toggles the clock
                    display between UTC (the default) and local
                    time.  */
                RB_clock.addEventListener("click",
                    function() {
                        RB_clock_UTC = !RB_clock_UTC;
                        //  Cancel pending clock timer, if any
                        if (RB_clock_timer >= 0) {
                            clearTimeout(RB_clock_timer);
                            RB_clock_timer = -1;
                        }
                        RB_update_clock();
                    });
            }
        }
    }

    /*  We mustn't call RB_wind_clock() before the theme
        initialisation code has had a chance to create the
        rb_toolbar_clock item in the admin-bar menu.  If you
        trigger off the JavaScript "load" or "DOMContentLoaded"
        events, it's too early: the dynamically created menu
        does not yet exist.  Here we use the hack of calling our
        function via jQuery, which will wait until all of the
        initialisation code has run.  */
    jQuery(RB_wind_clock);          // Start clock after page has loaded

    /*  Update the toolbar clock.  This function is called
        to refresh the time in the clock field.  It is
        initially called when the page is loaded and then
        sets itself up to be called at the top of every
        subsequent minute to refresh the clock.  It is also
        called when the clock format (UTC/local) changes.  */

    function RB_update_clock() {
        if (RB_clock) {
            var now = new Date();

            //  Format a decimal number with leading zeroes

            function fdecz(n, w) {
                return ("0000" + n.toString(10)).substr(-w);
            }

            //  Format ISO 8601 date and time

            function fdate(yy, mo, dd, hh, mm) {
                return fdecz(yy, 4) + "-" + fdecz(mo + 1, 2) + "-" +
                       fdecz(dd, 2) + " " + fdecz(hh, 2) + ":" +
                       fdecz(mm, 2);
            }

            //  Format UTC date and time from Date object

            function fdateUTC(d) {
                return fdate(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate(),
                             d.getUTCHours(), d.getUTCMinutes());
            }

            //  Format local date and time from Date object

            function fdateLocal(d) {
                return fdate(d.getFullYear(), d.getMonth(), d.getDate(),
                             d.getHours(), d.getMinutes());
            }

            var td;
            if (RB_clock_UTC) {
                td = fdateUTC(now) + " UTC";
            } else {
                td = fdateLocal(now);
            }
            RB_clock.innerHTML = td;

            /*  Set a timer to re-invoke this procedure at
                the top of the next minute.  */
            var updateInterval = 60 * 1000;     // Clock update interval
            var tnow = now.getTime();
            var next = (tnow - (tnow % updateInterval)) + updateInterval;
            var doze = next - tnow;
            RB_clock_timer = setTimeout(RB_update_clock, doze);
        }
    }

    //  Test if we're running inside an iframe

    function RB_clock_inIframe() {
        try {
            return window.self !== window.top;
        } catch (e) {
            return true;
        }
    }

    /* END RATBURGER LOCAL CODE */
