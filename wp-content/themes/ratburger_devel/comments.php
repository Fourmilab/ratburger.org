<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
        <?php /* RATBURGER LOCAL CODE
                 Prepare link to catch-up JavaScript handler.
                 Note that this code must execute regardless
                 of whether there are comments or not, as we
                 use the link below in the no-comments case.  */

            //  Determine candidates to mark read.  If nonzero, include link
            $rb_markread = false;
            if (($post->post_status == "publish") ||
                ($post->post_status == "private")) {
                $rb_markread = rb_catchup(true, time(), "post", $post->ID);
                $rb_catchlink = "";
                if ($rb_markread > 0) {
                    $reqsig = "k-post-t-" . time() . "-i-" . $post->ID;
                    $secsig = wp_create_nonce($reqsig);
                    $nots = ($rb_markread == 1) ? "" : "s";
                    $rb_catchlink = "<p><b><a id='rb_comment_catchup_1' " .
                        "href='#' onclick=\"rb_catchup('post', " .
                        $post->ID . ", " . time() . ", '" . $secsig . "'" .
                        "); return false;\">Clear " .
                        $rb_markread . " post notification" . $nots .
                        ".</a></b></p>\n";
                }
            }
        /* END RATBURGER LOCAL CODE */ ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				$comments_number = get_comments_number();
				if ( '1' === $comments_number ) {
					/* translators: %s: post title */
					printf( _x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'twentysixteen' ), get_the_title() );
				} else {
					printf(
						/* translators: 1: number of comments, 2: post title */
						_nx(
							'%1$s thought on &ldquo;%2$s&rdquo;',
							'%1$s thoughts on &ldquo;%2$s&rdquo;',
							$comments_number,
							'comments title',
							'twentysixteen'
						),
						number_format_i18n( $comments_number ),
						get_the_title()
					);
				}
			?>
		</h2>

        <?php /* RATBURGER LOCAL CODE
               Provide direct navigation to pages of comments.
            the_comments_navigation();
            */
            rb_comments_navigation();
        /* END RATBURGER LOCAL CODE */ ?>

        <?php /* RATBURGER LOCAL CODE
                 Include link to catch-up on notifications.  */
            //  Determine candidates to mark read.  If nonzero, include link
            if ($rb_markread > 0) {
                echo($rb_catchlink);
            }
        /* END RATBURGER LOCAL CODE */ ?>

		<ol class="comment-list">
			<?php
                /* RATBURGER LOCAL CODE */
                global $Ratburger_post_comment_number;
                $Ratburger_post_comment_number = 0;
                if (get_query_var('cpage')) {
                    $Ratburger_post_comment_number = get_query_var('comments_per_page') * (get_query_var('cpage') - 1);
                }
                /* END RATBURGER LOCAL CODE */
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 42,
				) );
			?>
		</ol><!-- .comment-list -->

        <?php /* RATBURGER LOCAL CODE
               Provide direct navigation to pages of comments.
            the_comments_navigation();
            */
            rb_comments_navigation();
        /* END RATBURGER LOCAL CODE */ ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'twentysixteen' ); ?></p>
	<?php endif; ?>

    <?php /* RATBURGER LOCAL CODE
             Include link to catch-up on notifications.  */
        if ($rb_markread > 0) {
            echo(str_replace("rb_comment_catchup_1", "rb_comment_catchup_2", $rb_catchlink));
        }
    /* END RATBURGER LOCAL CODE */ ?>

	<?php
		comment_form( array(
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h2>',
		) );
	?>

    <?php /* RATBURGER LOCAL CODE
             Generate navigation panel for multiple
             pages of comments. */
    function rb_comments_navigation() {
        echo("<div class=\"rb-commment-navigation\"><p>\n");
        paginate_comments_links();
        echo("</p></div>\n");
    }
    /* END RATBURGER LOCAL CODE */ ?>

</div><!-- .comments-area -->
