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

			// quoteing the entire thing
			var selection = false;
			var commentID = commentID.split("div-comment-")[1];

			// quote entire comment as html
			var theQuote = "q-"+commentID;
			//var theQuote = "div-comment-"+commentID;
			var posttext = document.getElementById(theQuote).innerHTML;

			// remove nested divs
			var posttext = posttext.replace(/<div(.*?)>((.|\n)*?)(<\/div>)/ig, "");

			// remove nested blockquotes
			var posttext = posttext.replace(/<blockquote(.*?)>((.|\n)*?)(<\/blockquote>)/ig, "");
			var posttext = posttext.replace(/<blockquote(.*?)>((.|\n)*?)(<\/blockquote>)/ig, "");

			// remove superfluous linebreaks
			var posttext = posttext.replace(/\s\s/gm, "");

			// do basic cleanups
			var posttext = posttext.replace(/	/g, "");
			//var posttext = posttext.replace(/<p>/g, "\n");
			//var posttext = posttext.replace(/<\/\s*p>/g, "");
			var posttext = posttext.replace(/<p>/g, "");
			var posttext = posttext.replace(/<\/\s*p>/g, "\n\n");
			var posttext = posttext.replace(/<br>/g, "")

			// remove nonbreaking space
			var posttext = posttext.replace(/&nbsp;/g, " ");

			// remove nested spans
			var posttext = posttext.replace(/<span(.*?)>((.|\n)*?)(<\/span>)/ig, "");

			// remove nested blockquotes
			while (posttext != (posttext = posttext.replace(/<blockquote>[^>]*<\/\s*blockquote>/g, "")));

			// remove nested quote links
			var posttext = posttext.replace(/<a class="comment_quote_link"(.*?)>((.|\n)*?)(<\/a>)/ig, "");
			var posttext = posttext.replace(/<a class="comment_reply_link"(.*?)>((.|\n)*?)(<\/a>)/ig, "");

		}

		// build quote
		if (author) {
			
			// prevent xss stuff
			author = jsEncode(author);
			
			var quote='\n<blockquote cite="comment-'+postid+'">\n\n<strong><a href="#comment-'+postid+'">'+unescape(author)+'</a></strong>: '+posttext+'</blockquote>\n';

		} else {

			var quote='\n<blockquote cite="comment-'+postid+'">\n\n'+posttext+'</blockquote>\n';

		}

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



