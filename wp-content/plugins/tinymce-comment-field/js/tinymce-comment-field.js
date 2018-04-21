jQuery.fn.tmcefBindFirst = function (name, fn) {
    this.on(name, fn);

    this.each(function () {
        var handlers = jQuery._data(this, 'events')[name.split('.')[0]];
        var handler = handlers.pop();
        handlers.splice(0, 0, handler);
    });
};

jQuery(function ($) {

    $('.comment-reply-link').click(function (e) {
        e.preventDefault();
        var args = $(this).data('onclick');

        if (args) {
            args = args.replace(/.*\(|\)/gi, '').replace(/\"|\s+/g, '');
            args = args.split(',');
        }

        tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
        addComment.moveForm.apply(addComment, args);
        tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
    });

    $("#commentform").tmcefBindFirst('submit', function () {
	/* RATBURGER LOCAL CODE
           If we're using the Quicktags HTML editor, don't
           overwrite the comment textarea with the inactive
           contents of the TinyMCE editor buffer. */
	if (!tinymce.activeEditor.isHidden() {
	/* END RATBURGER LOCAL CODE */
        if ($("#comment").length > 0) {
            $("#comment").val(tinymce.activeEditor.getContent());
        }
	/* RATBURGER LOCAL CODE */
	}
	/* END RATBURGER LOCAL CODE */
    });
});
