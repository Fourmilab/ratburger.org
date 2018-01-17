/*
	Quote Comments JS
*/


function jsEncode(str){

	// ugly hack
	str = " " + str;
	
	var aStr = str.split(''), i = aStr.length, aRet = [];

	while (--i) {
		var iC = aStr[i].charCodeAt();
		
		if (iC < 65 || iC > 127 || (iC>90 && iC<97)) {
			aRet.push('&#'+iC+';');
		} else {
			aRet.push(aStr[i]);
		}
	}
	
	return aRet.reverse().join('');
}

/*  This function is called when the user presses the "Quote" button in
    a comment.  It is responsible for determining the text to be quoted,
    whether by highlighing or the entire comment, cleaning up any material
    in the quoted text which should not be pasted into the comment box, and
    placing the result into the comment composition box.  */

function quote(postid, author, commentarea, commentID, mce) {
	try {
		// If you don't want quotes begin with "<author>:", uncomment the next line
		//author = null;

		// begin code
		var posttext = '';

		if (window.getSelection){
			posttext = window.getSelection();
		}

		else if (document.getSelection){
			posttext = document.getSelection();
		}

		else if (document.selection){
			posttext = document.selection.createRange().text;
		}

		else {
			return true;
		}

		if (posttext=='') {		// quoting entire comment

			// quoting the entire thing
			var selection = false;
			var commentID = commentID.split("div-comment-")[1];

			// quote entire comment as html
			var theQuote = "q-"+commentID;
			//var theQuote = "div-comment-"+commentID;
			var posttext = document.getElementById(theQuote).innerHTML;

			// remove nested divs
			//var posttext = posttext.replace(/<div(.*?)>((.|\n)*?)(<\/div>)/ig, "");

			// remove nested blockquotes
			//var posttext = posttext.replace(/<blockquote(.*?)>((.|\n)*?)(<\/blockquote>)/ig, "");
			//var posttext = posttext.replace(/<blockquote(.*?)>((.|\n)*?)(<\/blockquote>)/ig, "");

			// remove superfluous linebreaks
			// var posttext = posttext.replace(/\s\s/gm, "");

			// do basic cleanups
			var posttext = posttext.replace(/\t/g, " ");
			//var posttext = posttext.replace(/<p>/g, "\n");
			//var posttext = posttext.replace(/<\/\s*p>/g, "");
			//var posttext = posttext.replace(/<p>/g, "");
			//var posttext = posttext.replace(/<\/\s*p>/g, "\n\n");
			//var posttext = posttext.replace(/<br>/g, "")

			// remove nonbreaking space
			//var posttext = posttext.replace(/&nbsp;/g, " ");

			// remove nested spans
			//var posttext = posttext.replace(/<span(.*?)>((.|\n)*?)(<\/span>)/ig, "");

			// remove nested blockquotes
			//while (posttext != (posttext = posttext.replace(/<blockquote>[^>]*<\/\s*blockquote>/g, "")));

			// remove nested quote links
			var posttext = posttext.replace(/<a class="comment_quote_link"(.*?)>((.|\n)*?)(<\/a>)/ig, "");
			var posttext = posttext.replace(/<a class="comment_reply_link"(.*?)>((.|\n)*?)(<\/a>)/ig, "");

		}

		// build quote
		if (author) {
			
			// prevent xss stuff
			author = jsEncode(author);
			
			var quote='\n<blockquote cite="comment-'+postid+'">\n\n<strong><a href="#comment-'+postid+'">'+unescape(author) + "</a></strong>:<br /> " + posttext + '</blockquote>\n';

		} else {

			var quote='\n<blockquote cite="comment-'+postid+'">\n\n'+posttext+'</blockquote>\n';

		}

		// send quoted content
		if (mce == true) {		// TinyMCE detected

			/* RATBURGER LOCAL CODE
  			   This doesn't work for our configuration.
  			   We have to talk directly to TinyMCE.  See
  			   below.
			//addQuoteMCE(comment,quote);
			insertHTML(quote);
			insertHTML("<p>&nbsp;</p>");
			*/
			tinymce.execCommand('mceFocus', false, 'comment');
			tinymce.get("comment").execCommand('mceInsertContent', false, quote + "<p>&nbsp;</p>\n");
			document.getElementById(commentarea).scrollIntoView();
			/* END RATBURGER LOCAL CODE */

		} else {				// No TinyMCE detected

			var comment=document.getElementById(commentarea);
			addQuote(comment,quote);

		}

		return false;

	} catch (e) {

		alert("Quote Comments plugin is having some trouble! It could possibly be a problem with your Wordpress theme. Does it work if you use the default theme? Does it work if you disable all other plugins? If you look in the HTML source of a page with comments, can you find <div id='q-[id]'> where [id] is the ID of the comment?")

	}
}

	
/* RATBURGER LOCAL CODE
   New qc_quote_post function to quote main post. */

/*  This function is called when the user presses the "Quote" button for
    a post.  It is responsible for determining the text to be quoted,
    whether by highlighing or the entire post, cleaning up any material
    in the quoted text which should not be pasted into the comment box, and
    placing the result into the comment composition box.  */

function qc_quote_post(postid, author, commentarea) {
        try {
                // If you don't want quotes begin with "<author>:", uncomment the next line
                //author = null;

                // begin code
                var posttext = '';

                if (window.getSelection){
                        posttext = window.getSelection();
                }

                else if (document.getSelection){
                        posttext = document.getSelection();
                }

                else if (document.selection){
                        posttext = document.selection.createRange().text;
                }

                else {
                        return true;
                }

                if (posttext=='') {             // quoting entire post

                        // quoting the entire thing
                        var selection = false;

                        // Quote entire post as HTML: Extract post from complete article
                        var posttext = document.getElementById('post-' + postid).innerHTML;
			posttext = posttext.replace(/^[\s\S]*?<div\sclass="entry-content">/, '');
			posttext = posttext.replace(/<div\sid="wp-ulike-post[\s\S]*$/, '');

                        // remove nested divs
                        //var posttext = posttext.replace(/<div(.*?)>((.|\n)*?)(<\/div>)/ig, "");

                        // remove nested blockquotes
                        //var posttext = posttext.replace(/<blockquote(.*?)>((.|\n)*?)(<\/blockquote>)/ig, "");
                        //var posttext = posttext.replace(/<blockquote(.*?)>((.|\n)*?)(<\/blockquote>)/ig, "");

                        // remove superfluous linebreaks
                        // var posttext = posttext.replace(/\s\s/gm, "");

                        // do basic cleanups
                        var posttext = posttext.replace(/\t/g, " ");
                        //var posttext = posttext.replace(/<p>/g, "\n");
                        //var posttext = posttext.replace(/<\/\s*p>/g, "");
                        //var posttext = posttext.replace(/<p>/g, "");
                        //var posttext = posttext.replace(/<\/\s*p>/g, "\n\n");
                        //var posttext = posttext.replace(/<br>/g, "")

                        // remove nonbreaking space
                        //var posttext = posttext.replace(/&nbsp;/g, " ");

                        // remove nested spans
                        //var posttext = posttext.replace(/<span(.*?)>((.|\n)*?)(<\/span>)/ig, "");

                        // remove nested blockquotes
                        //while (posttext != (posttext = posttext.replace(/<blockquote>[^>]*<\/\s*blockquote>/g, "")));

                        // remove nested quote links
                        var posttext = posttext.replace(/<a class="comment_quote_link"(.*?)>((.|\n)*?)(<\/a>)/ig, "");
                        var posttext = posttext.replace(/<a class="comment_reply_link"(.*?)>((.|\n)*?)(<\/a>)/ig, "");

                }

                // build quote
                if (author) {

                        // prevent xss stuff
                        author = jsEncode(author);

                        var quote='\n<blockquote cite="post-'+postid+'">\n\n<strong><a href="#post-'+postid+'">'+unescape(author) + "</a></strong>:<br /> " + posttext + '</blockquote>\n';

                } else {

                        var quote='\n<blockquote cite="post-'+postid+'">\n\n'+posttext+'</blockquote>\n';

                }

		// Insert the quoted text into the TinyMCE editor
                tinymce.execCommand('mceFocus', false, 'comment');
                tinymce.get("comment").execCommand('mceInsertContent', false, quote + "<p>&nbsp;</p>\n");
                document.getElementById(commentarea).scrollIntoView();

                return false;

        } catch (e) {

                alert("Quote Comments plugin is having some trouble quoting a post! It could possibly be a problem with your Wordpress theme. Does it work if you use the default theme? Does it work if you disable all other plugins? If you look in the HTML source of a page with comments, can you find <article id='post-[id]'> where [id] is the ID of the post?")

        }
}
/* END RATBURGER LOCAL CODE */


/* RATBURGER LOCAL CODE
   We don't use this.
function inlinereply(postid, author, commentarea, commentID, mce) {
	try {
		
		// prevent xss stuff
		author = jsEncode(author);

		// build quote
		var quote='\n<strong><a href="#comment-'+postid+'">'+unescape(author)+'</a></strong>, \n\n';


		// send quoted content
		if (mce == true) {		// TinyMCE detected

			//addQuoteMCE(comment,quote);
			insertHTML(quote);
			insertHTML("<p>&nbsp;</p>");

		} else {				// No TinyMCE detected

			var comment=document.getElementById(commentarea);
			addQuote(comment,quote);

		}

		return false;

	} catch (e) {

		alert("Quote Comments plugin is having some trouble! It could possibly be a problem with your Wordpress theme. Does it work if you use the default theme? Does it work if you disable all other plugins? If you look in the HTML source of a page with comments, can you find <div id='q-[id]'> where [id] is the ID of the comment?")

	}
}
*/


function addQuote(comment,quote){

	/*
		Derived from Alex King's JS Quicktags code (http://www.alexking.org/)
		Released under LGPL license
	*/	

	

	// IE support
	if (document.selection) {
		comment.focus();
		sel = document.selection.createRange();
		sel.text = quote;
		comment.focus();
	}

	// Mozilla support

	else if (comment.selectionStart || comment.selectionStart == '0') {
		var startPos = comment.selectionStart;
		var endPos = comment.selectionEnd;
		var cursorPos = endPos;
		var scrollTop = comment.scrollTop;
		if (startPos != endPos) {

			comment.value = comment.value.substring(0, startPos)
						  + quote
						  + comment.value.substring(endPos, comment.value.length);
			cursorPos = startPos + quote.length

		}

		else {
			comment.value = comment.value.substring(0, startPos) 
							  + quote
							  + comment.value.substring(endPos, comment.value.length);
			cursorPos = startPos + quote.length;

		}

		comment.focus();
		comment.selectionStart = cursorPos;
		comment.selectionEnd = cursorPos;
		comment.scrollTop = scrollTop;

	}

	else {

		comment.value += quote;

	}

	// If Live Preview Plugin is installed, refresh preview
	try {
		ReloadTextDiv();
	}
	catch ( e ) {
	}

	

}
