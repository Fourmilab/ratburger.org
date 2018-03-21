/* global screenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

( function( $ ) {
	var body, masthead, menuToggle, siteNavigation, socialNavigation, siteHeaderMenu, resizeTimer;

	function initMainNavigation( container ) {

		// Add dropdown toggle that displays child menu items.
		var dropdownToggle = $( '<button />', {
			'class': 'dropdown-toggle',
			'aria-expanded': false
		} ).append( $( '<span />', {
			'class': 'screen-reader-text',
			text: screenReaderText.expand
		} ) );

		container.find( '.menu-item-has-children > a' ).after( dropdownToggle );

		// Toggle buttons and submenu items with active children menu items.
		container.find( '.current-menu-ancestor > button' ).addClass( 'toggled-on' );
		container.find( '.current-menu-ancestor > .sub-menu' ).addClass( 'toggled-on' );

		// Add menu items with submenus to aria-haspopup="true".
		container.find( '.menu-item-has-children' ).attr( 'aria-haspopup', 'true' );

		container.find( '.dropdown-toggle' ).click( function( e ) {
			var _this            = $( this ),
				screenReaderSpan = _this.find( '.screen-reader-text' );

			e.preventDefault();
			_this.toggleClass( 'toggled-on' );
			_this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );

			// jscs:disable
			_this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
			// jscs:enable
			screenReaderSpan.text( screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand );
		} );
	}
	initMainNavigation( $( '.main-navigation' ) );

	masthead         = $( '#masthead' );
	menuToggle       = masthead.find( '#menu-toggle' );
	siteHeaderMenu   = masthead.find( '#site-header-menu' );
	siteNavigation   = masthead.find( '#site-navigation' );
	socialNavigation = masthead.find( '#social-navigation' );

	// Enable menuToggle.
	( function() {

		// Return early if menuToggle is missing.
		if ( ! menuToggle.length ) {
			return;
		}

		// Add an initial values for the attribute.
		menuToggle.add( siteNavigation ).add( socialNavigation ).attr( 'aria-expanded', 'false' );

		menuToggle.on( 'click.twentysixteen', function() {
			$( this ).add( siteHeaderMenu ).toggleClass( 'toggled-on' );

			// jscs:disable
			$( this ).add( siteNavigation ).add( socialNavigation ).attr( 'aria-expanded', $( this ).add( siteNavigation ).add( socialNavigation ).attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
			// jscs:enable
		} );
	} )();

	// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
	( function() {
		if ( ! siteNavigation.length || ! siteNavigation.children().length ) {
			return;
		}

		// Toggle `focus` class to allow submenu access on tablets.
		function toggleFocusClassTouchScreen() {
			if ( window.innerWidth >= 910 ) {
				$( document.body ).on( 'touchstart.twentysixteen', function( e ) {
					if ( ! $( e.target ).closest( '.main-navigation li' ).length ) {
						$( '.main-navigation li' ).removeClass( 'focus' );
					}
				} );
				siteNavigation.find( '.menu-item-has-children > a' ).on( 'touchstart.twentysixteen', function( e ) {
					var el = $( this ).parent( 'li' );

					if ( ! el.hasClass( 'focus' ) ) {
						e.preventDefault();
						el.toggleClass( 'focus' );
						el.siblings( '.focus' ).removeClass( 'focus' );
					}
				} );
			} else {
				siteNavigation.find( '.menu-item-has-children > a' ).unbind( 'touchstart.twentysixteen' );
			}
		}

		if ( 'ontouchstart' in window ) {
			$( window ).on( 'resize.twentysixteen', toggleFocusClassTouchScreen );
			toggleFocusClassTouchScreen();
		}

		siteNavigation.find( 'a' ).on( 'focus.twentysixteen blur.twentysixteen', function() {
			$( this ).parents( '.menu-item' ).toggleClass( 'focus' );
		} );
	} )();

	// Add the default ARIA attributes for the menu toggle and the navigations.
	function onResizeARIA() {
		if ( window.innerWidth < 910 ) {
			if ( menuToggle.hasClass( 'toggled-on' ) ) {
				menuToggle.attr( 'aria-expanded', 'true' );
			} else {
				menuToggle.attr( 'aria-expanded', 'false' );
			}

			if ( siteHeaderMenu.hasClass( 'toggled-on' ) ) {
				siteNavigation.attr( 'aria-expanded', 'true' );
				socialNavigation.attr( 'aria-expanded', 'true' );
			} else {
				siteNavigation.attr( 'aria-expanded', 'false' );
				socialNavigation.attr( 'aria-expanded', 'false' );
			}

			menuToggle.attr( 'aria-controls', 'site-navigation social-navigation' );
		} else {
			menuToggle.removeAttr( 'aria-expanded' );
			siteNavigation.removeAttr( 'aria-expanded' );
			socialNavigation.removeAttr( 'aria-expanded' );
			menuToggle.removeAttr( 'aria-controls' );
		}
	}

	// Add 'below-entry-meta' class to elements.
	function belowEntryMetaClass( param ) {
		if ( body.hasClass( 'page' ) || body.hasClass( 'search' ) || body.hasClass( 'single-attachment' ) || body.hasClass( 'error404' ) ) {
			return;
		}

		$( '.entry-content' ).find( param ).each( function() {
			var element              = $( this ),
				elementPos           = element.offset(),
				elementPosTop        = elementPos.top,
				entryFooter          = element.closest( 'article' ).find( '.entry-footer' ),
				entryFooterPos       = entryFooter.offset(),
				entryFooterPosBottom = entryFooterPos.top + ( entryFooter.height() + 28 ),
				caption              = element.closest( 'figure' ),
				newImg;

			// Add 'below-entry-meta' to elements below the entry meta.
			if ( elementPosTop > entryFooterPosBottom ) {

				// Check if full-size images and captions are larger than or equal to 840px.
				if ( 'img.size-full' === param ) {

					// Create an image to find native image width of resized images (i.e. max-width: 100%).
					newImg = new Image();
					newImg.src = element.attr( 'src' );

					$( newImg ).on( 'load.twentysixteen', function() {
						if ( newImg.width >= 840  ) {
							element.addClass( 'below-entry-meta' );

							if ( caption.hasClass( 'wp-caption' ) ) {
								caption.addClass( 'below-entry-meta' );
								caption.removeAttr( 'style' );
							}
						}
					} );
				} else {
					element.addClass( 'below-entry-meta' );
				}
			} else {
				element.removeClass( 'below-entry-meta' );
				caption.removeClass( 'below-entry-meta' );
			}
		} );
	}

	$( document ).ready( function() {
		body = $( document.body );

		$( window )
			.on( 'load.twentysixteen', onResizeARIA )
			.on( 'resize.twentysixteen', function() {
				clearTimeout( resizeTimer );
				resizeTimer = setTimeout( function() {
					belowEntryMetaClass( 'img.size-full' );
					belowEntryMetaClass( 'blockquote.alignleft, blockquote.alignright' );
				}, 300 );
				onResizeARIA();
			} );

		belowEntryMetaClass( 'img.size-full' );
		belowEntryMetaClass( 'blockquote.alignleft, blockquote.alignright' );
	} );
} )( jQuery );


    /* RATBURGER LOCAL CODE */

    /*  The following implements the automatic update of
        notifications in the administration toolbar.  If the
        document contains our hidden "RB_notif_update" iframe,
        whenever RB_updateNotifications() is invoked, the dummy
        "/index.php/update-notifications" page (used only to
        obtain the current notifications) is loaded into it, and
        then its "wp-admin-bar-bp-notifications" HTML content
        copied into that of the parent page.  The time is then
        reset to call RB_updateNotifications() for the next
        update.  */

    var RB_notif_timer = null;          // Update notification timer
    var RB_notif_interval = 300000;     // Update notification interval, ms
    var RB_notif_last_update = 0;       // Millisecond time of last update
    var RB_notif_min_interval = 60000;  // Minimum time between updates

    //  Test if we're running inside an iframe

    function RB_inIframe() {
        try {
            return window.self !== window.top;
        } catch (e) {
            return true;
        }
    }

    //  Test whether the current page is fully or partially visible

    function RB_isPageVisible() {
        if (document.visibilityState) {
            //  W3C Page Visibility Level 2 supported
            return document.visibilityState == "visible";
        }
        //  Hack for visibility test in older browsers
        return !(document.hidden || document.msHidden ||
                 document.webkitHidden || document.mozHidden);
    }

    //  Update the notification information in the admin bar

    function RB_updateNotifications() {
//console.log("Tick...");
        /*  We update only if:
                We're not in an iframe
                The user is logged in and notifications are shown
                The page is visible (see RB_isPageVisible() above)
                More time than RB_notif_min_interval has elapsed
                    since the last update.  */
        if ((!RB_inIframe()) && RB_isPageVisible() &&
            ((((new Date()).getTime()) - RB_notif_last_update) >= RB_notif_min_interval) &&
            document.getElementById("wp-admin-bar-bp-notifications")) {
            var rnu = document.getElementById("RB_notif_update");
            if (rnu) {
               rnu.onload = function() {
                    var ru = document.getElementById("RB_notif_update");
                    //  Replace the notifications in the main page with those from the iframe
                    if (ru && ru.contentWindow.document.
                            getElementById("wp-admin-bar-bp-notifications")) {
                        document.getElementById("wp-admin-bar-bp-notifications").innerHTML =
                            document.getElementById("RB_notif_update").contentWindow.document.
                                getElementById("wp-admin-bar-bp-notifications").innerHTML;
//console.log("Updated notifications bubble");
                    }
                    //  Replace the notifications item in the avatar drop-down menu
                    if (ru && ru.contentWindow.document.
                            getElementById("wp-admin-bar-my-account-notifications") &&
                        document.getElementById("wp-admin-bar-my-account-notifications")) {
                        document.getElementById("wp-admin-bar-my-account-notifications").innerHTML =
                            ru.contentWindow.document.
                                getElementById("wp-admin-bar-my-account-notifications").innerHTML;
//console.log("Updated notifications in avatar menu");
                    }
//else { console.log("No notifications to update"); }
                    rnu.onload = null;          // Cancel onload for reset of iframe to empty
                    rnu.src = "about:empty";    // Empty the iframe
//console.log("Emptied iframe");
                    //  Save time of last update
                    RB_notif_last_update = (new Date()).getTime();
               };
               //  Load the update-notifications page into the update iframe
               rnu.src = "/index.php/update-notifications/";
            }
        }
//else { console.log("In an iframe--ignoring"); }

        // Wind the cat
        RB_notif_timer = window.setTimeout(RB_updateNotifications, RB_notif_interval);
    }

    /*  If we're not inside the RB_notif_update iframe
        schedule the first notification update.  This also
        guarantees we'll have had plenty of time for the page
        to load before we need to reference elements within it.  */

    if (!RB_inIframe()) {
        RB_notif_timer = window.setTimeout(RB_updateNotifications, RB_notif_interval);
        //  Set last update to initial page load time
        RB_notif_last_update = (new Date()).getTime();

        /*  When the window gets focus, cancel the timer if it
            is running and force an immediate notification
            update, which will restart the timer.  On every
            browser I've tested, this does not fire when the page
            is initially displayed, only once the page has lost
            focus and subsequently regained it.  */

        window.onfocus = function(e) {
            if (RB_notif_timer !== null) {
                window.clearTimeout(RB_notif_timer);
                RB_notif_timer = null;
            }
            RB_updateNotifications();
        }
    }
//else { console.log("In iframe--notification timer not started"); }

    /* END RATBURGER LOCAL CODE */
