/**
 * @package Inline Spoilers
 */

jQuery(function () {
    jQuery(".spoiler-head").removeClass("no-icon");
    jQuery(".spoiler-head").on('click', function (event) {
        $this = jQuery(this);
        $isExpanded = $this.hasClass("expanded");

        $this.toggleClass("expanded").toggleClass("collapsed");
        $this.prop('title', $isExpanded ? title.collapse : title.expand);

        if($isExpanded) {
            $this.next().slideUp("fast");
        } else {
            $this.next().slideDown("fast");
        }
    });
});
