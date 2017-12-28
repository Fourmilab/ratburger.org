/**
 * @package Inline Spoilers
 */

jQuery(function () {
    jQuery(".spoiler-head").removeClass("no-icon");
    jQuery(".spoiler-head").on('click', function (event) {
        $this = jQuery(this);
        if ($this.hasClass("expanded")) {
            $this.removeClass("expanded");
            $this.addClass("collapsed");
            $this.prop('title', title.expand);
            $this.next().slideUp("fast");
        } else {
            $this.removeClass("collapsed");
            $this.addClass("expanded");
            $this.prop('title', title.collapse);
            $this.next().slideDown("fast");
        }
    });
});
