<?php
/**
 * Plugin Name: RB My Comments
 * Description: Show all of a user's comments in multiple pages
 * Author: John Walker
 * Author URI: http://www.fourmilab.ch/
 * Version: 1.0.0
 */

add_action('admin_menu', 'rb_my_comments_create_menu');

function rb_my_comments_create_menu() {

    //  Create new top-level menu

    add_menu_page('RB My Comments Settings',
        'RB My Comments', 'administrator',
        'rb-my-comments', 'rb_my_comments_settings_page');

    //  Call register settings function

    add_action('admin_init', 'register_rb_my_comments_settings');
}

/*  Register our persistent settings.  */

function register_rb_my_comments_settings() {
    /*  Register our settings.  If you add any settings here
        be sure to also add them to rb_my_comments_uninstall()
        to clean them up at deactivation.  */
    register_setting('rb-my-comments-settings-group', 'rb_my_pagination');
    register_setting('rb-my-comments-settings-group', 'rb_my_comments_per_page');
    register_setting('rb-my-comments-settings-group', 'rb_my_avatar');
    register_setting('rb-my-comments-settings-group', 'rb_my_show_date');
    register_setting('rb-my-comments-settings-group', 'rb_my_open_new_tab');
    register_setting('rb-my-comments-settings-group', 'rb_my_comments_order');
    register_setting('rb-my-comments-settings-group', 'rb_my_show_post_link');
    register_setting('rb-my-comments-settings-group', 'rb_my_show_comment_link');
}

/*  When the administrator chooses our settings item
    from the dashboard menu, this function displays
    the settings page and handles changes in the
    settings.  */

function rb_my_comments_settings_page() {

    // Admin side page options
    $set_rb_my_pagination = get_option('rb_my_pagination');
    $set_rb_my_comments_per_page = get_option('rb_my_comments_per_page');

    if ($set_rb_my_comments_per_page == NULL) {
        $set_rb_my_comments_per_page = 10;
    }
    $set_rb_my_avatar = get_option('rb_my_avatar');

    $set_rb_my_show_date = get_option('rb_my_show_date');
    $set_rb_my_open_new_tab = get_option('rb_my_open_new_tab');
    $set_comments_order = get_option('rb_my_comments_order');
    $show_post_link = get_option('rb_my_show_post_link');
    $show_comment_link = get_option('rb_my_show_comment_link');
    ?>
    <div class="wrap">
        <h2>RB Show My Comments Settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields('rb-my-comments-settings-group'); ?>
            <?php do_settings_sections('rb-my-comments-settings-group'); ?>
            <table class="form-table">

                <tr valign="top">
                    <th scope="row"><?php _e('Pagination'); ?></th>
                    <td>
                        <fieldset>
                            <?php
                            if ($set_rb_my_pagination == 'yes') {
                                ?>
                                <label><input type="radio" value="yes" name="rb_my_pagination" checked="checked"> <span><?php
                                    _e('Yes'); ?></span></label><br>
                                <label><input type="radio" value="no" name="rb_my_pagination"> <span><?php
                                    _e('No'); ?></span></label>
                                <?php
                            } else {
                                ?>
                                <label><input type="radio" value="yes" name="rb_my_pagination"> <span><?php
                                    _e('Yes'); ?></span></label><br>
                                <label><input type="radio" value="no" name="rb_my_pagination" checked="checked"> <span><?php
                                    _e('No'); ?></span></label>
                                <?php
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Comments Per Page'); ?></th>
                    <td><input type="number" class="small-text" value="<?php
                        echo($set_rb_my_comments_per_page); ?>" min="1" step="1" name="rb_my_comments_per_page"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Enable post title link?'); ?></th>
                    <td>
                        <fieldset>
                            <label><input type="radio" value="yes" name="rb_my_show_post_link" <?php
                                echo($show_post_link == 'yes' ? 'checked' : ''); ?>> <span><?php _e('Yes'); ?></span></label><br>
                            <label><input type="radio" value="no" name="rb_my_show_post_link" <?php
                                echo($show_post_link == 'no' ? 'checked' : ''); ?>> <span><?php _e('No'); ?></span></label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Enable go to comment link?'); ?></th>
                    <td>
                        <fieldset>
                            <label><input type="radio" value="yes" name="rb_my_show_comment_link" <?php
                                echo($show_comment_link == 'yes' ? 'checked' : ''); ?>> <span><?php _e('Yes'); ?></span></label><br>
                            <label><input type="radio" value="no" name="rb_my_show_comment_link" <?php
                                echo($show_comment_link == 'no' ? 'checked' : ''); ?>> <span><?php _e('No'); ?></span></label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Avatar Size'); ?></th>
                    <td><input type="number" class="small-text" value="<?php
                        if ($set_rb_my_avatar == NULL) {
                            echo("50");
                        } else {
                            echo($set_rb_my_avatar);
                        }
                        ?>" id="rb_my_avatar" min="1" step="1" name="rb_my_avatar"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Show Comment Date'); ?></th>
                    <td>
                        <fieldset>
                            <?php {
                                if ($set_rb_my_show_date == 'on') {
                                    $checked = 'checked=checked';
                                }
                                ?>
                                <label><input type="checkbox" name="rb_my_show_date" <?php echo($checked); ?>></label><br>
                                <?php
                                $checked = '';
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Open Comment in New Tab'); ?></th>
                    <td>
                        <fieldset>
                            <?php {
                                if ($set_rb_my_open_new_tab == 'on') {
                                    $checked = 'checked=checked';
                                }
                                ?>
                                <label><input type="checkbox" name="rb_my_open_new_tab" <?php echo($checked); ?>></label><br>
                                <?php
                                $checked = '';
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Comments Order'); ?></th>
                    <td>
                        <fieldset>
                            <?php
                            if ($set_comments_order == 'no') {
                                ?>

                                <label><input type="radio" value="yes" name="rb_my_comments_order"> <span><?php
                                    _e('Newest comments First'); ?></span></label><br>
                                <label><input type="radio" value="no" name="rb_my_comments_order" checked="checked"> <span><?php
                                    _e('Oldest comments First'); ?></span></label>
                                <?php
                            } else {
                                ?>
                                <label><input type="radio"  value="yes" name="rb_my_comments_order" checked="checked"> <span><?php
                                    _e('Newest comments First'); ?></span></label><br>
                                <label><input type="radio"  value="no" name="rb_my_comments_order"> <span><?php
                                    _e('Oldest comments First'); ?></span></label>

                                <?php
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>

        </form>
    </div>
    <?php
}

/*  Add the shortcode, [rb_my_comments], which triggers display
    of the user's comments by the following function.  This is
    usually placed, by itself, on a Page named something like
    "My Comments".  */

add_shortcode('rb_my_comments', 'rb_my_comments');

/*  Generate the page containing the user's comments.
    The number of items shown per page and their order
    are controlled by the settings.  */

function rb_my_comments($attr) {
    $content = "";

    // Process settings

    $page = intval(get_query_var('cpage'));
    if (0 == $page) {
        $page = 1;
        set_query_var('cpage', $page);
    }

    $comments_order = get_option('rb_my_comments_order');
    if ($comments_order == 'yes') {
        $order = 'DESC';
    } else {
        $order = 'ASC';
    }

    $pagination = get_option('rb_my_pagination');
    $comments_per_page = get_option('rb_my_comments_per_page');

    $get_atts = shortcode_atts(array(
        'pagination' => $pagination,
        'comments_per_page' => $comments_per_page,
            ), $attr);
    if ($get_atts['pagination'] == 'yes') {
        set_query_var('comments_per_page', $get_atts['comments_per_page']);
    }
    $display_filter = false;

    //  End processing of settings

    $defaults = array(
        'user_id' => get_current_user_id(),
        'orderby' => 'comment_date',
        'order' => $order,
        'status' => 'approve',
        'count' => false,
        'date_query' => null,
    );

    /*  Now we're ready to retrieve the comments selected by
        the settings determined above.  If the user making
        the request isn't logged in, we simply return an
        empty array.  */

    if (get_current_user_id() != 0) {
        $comments = get_comments($defaults);
    } else {
        $comments = array();
    }

    $content .= '<div class="rb-my-content">';

    if ($comments != null) {

        $parent_comment_count = 0;
        foreach ($comments as $parent_comment) {

            if ($parent_comment->comment_parent == 0) {
                $parent_comment_count ++;
            }
        }

        $query_arg = array(
            'cpage' => '%#%',
        );

        ob_start();
        $content .= "<ul class=\"rb-my-comments\">";

        /*  If WP Ulike is installed, remove its filter for comment
            contents.  We don't want it inserting its like button
            and ugly count in the comment body.  */
        if (function_exists('wp_ulike_put_comments')) {
            remove_filter('comment_text', 'wp_ulike_put_comments');
        }

        /*  Now we call wp_list_comments() to generate the comments
            in HTML5 format, calling our rb_my_comments_template()
            function to actually generate the HTML for each
            comment.  */
        wp_list_comments(array(
            'walker' => null,
            'max_depth' => '',
            'style' => 'ul',
            'callback' => 'rb_my_comments_template',
            'end-callback' => '',
            'type' => 'all',
            'reply_text' => 'Reply',
            'avatar_size' => 32,
            'reverse_top_level' => null,
            'reverse_children' => '',
            'format' => 'HTML5',
            'short_ping' => false
                ), $comments);
        $content .= ob_get_clean();
        $content .= "</ul>";

        $content .= "<div class=\"rb-my-navigation\">";
        ob_start();

        /*  Generate the navigation links among pages of comments.  */

        paginate_comments_links(array(
            'base' => add_query_arg($query_arg),
            'total' => ceil($parent_comment_count / get_query_var('comments_per_page')),
            'current' => $page
        ));
        echo("</div>");
        $content .= ob_get_clean();
    } else {
        $content .= '<div class="rb-my-not-found">' .
            ((get_current_user_id() == 0) ?
                __('You must be logged in to display your comments.') :
                __('You have made no comments.')) . '</div>';
    }

    wp_enqueue_style('rb-my-style');
    $content .= '</div>';

    return $content;
}

//  Register our CSS files so they're loaded

add_action('wp_enqueue_scripts', 'rb_my_wp_enqueue_styles_and_scripts');

function rb_my_wp_enqueue_styles_and_scripts() {

    // CSS files
    wp_register_style('rb-my-style', plugins_url('css/rb-my-style.css', __FILE__));
}

add_filter('pre_option_page_comments', '__return_true');

/*  The following function generates the HTML for each comment.
    It is called as the comments are enumerated by
    wp_list_comments() above.  */

function rb_my_comments_template($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    $getAvatarSize = get_option('rb_my_avatar');
    $getdate = get_option('rb_my_show_date');
    $open_new_tab = get_option('rb_my_open_new_tab');
    $show_post_link = get_option('rb_my_show_post_link');
    $show_comment_link = get_option('rb_my_show_comment_link');
    $content_fixed_links = rb_my_adjust_quote_links($comment);
    ?>
    <li>
        <div class="rb-my-avatar-custom"><a href="/members/<?php
            echo(get_user_by('id', $comment->user_id)->user_nicename . '/profile/">');
            echo(get_avatar($comment, $getAvatarSize)); ?></a></div>
        <div class="rb-my-comment-wrap">
            <h4 class="rb-my-comment-meta">
                <?php _e('From'); ?> <span class="rb-my-comment-author"><?php
                    echo($comment->comment_author); ?></span>
                <?php _e('on'); ?>
                <?php
                if (isset($show_post_link) && $show_post_link == "yes") {
                    ?>
                    <span class="rb-my-comment-on-title"><a href = "<?php
                        echo(esc_url(get_permalink($comment->comment_post_ID)));
                        ?>" target = "_blank"><?php echo($comment->post_title); ?></a></span>
                    <?php
                } else {
                    ?>
                    <span class="rb-my-comment-on-title"><?php echo($comment->post_title); ?></span>
                    <?php
                }
                ?>

            </h4>
            <blockquote class="rb-my-comments-content">
            <?php echo(apply_filters("comment_text", do_shortcode($content_fixed_links))); ?>
            </blockquote>
            <?php

            /*  Generate the footer for the comment with the date and
                time, and the View and Edit links.  */

            if (isset($getdate) && $getdate == 'on') {
                ?>
                <span class="rb-my-comment-link"><?php
                    echo(date('Y-m-d H:i \U\T\C', strtotime($comment->comment_date))); ?></span>
                <?php
            }
            ?>
            &nbsp; &nbsp;
            <?php
            /* If the WP ULike plug-in is installed, display the number
               of likes on this comment. */
            if (function_exists('wp_ulike_get_comment_likes')) {
                    $likes = wp_ulike_get_comment_likes($comment->comment_ID);
                echo('<span class="rb-my-comment-link">');
                //  Turn all of the wild and wooly values of false into zero
                if (!$likes) {
                    $likes = '0';
                }
                echo("Likes: $likes</span> &nbsp; &nbsp;\n");
            }
            ?>
            <?php
            if (isset($show_comment_link) && ( $show_comment_link == 'yes' )) {
                if (isset($open_new_tab) && $open_new_tab == 'on') {
                    $new_tab = 'target="_blank"';
                } else {
                    $new_tab = "";
                }
                ?>
                <span class="rb-my-comment-link"><a href="<?php
                    echo(rb_my_comments_comment_URL($comment));
                    ?>" <?php echo($new_tab); ?>><?php _e('View in post'); ?></a></span>
                &nbsp; &nbsp;
                <span class="rb-my-comment-link"><a href="/wp-admin/comment.php?action=editcomment&c=<?php
                    echo($comment->comment_ID); ?>" <?php
                    echo($new_tab); ?>><?php _e('Edit'); ?></a></span><br />
                <?php
            }
            ?>
        </div>
    <?php
}

/*  The content of a comment may include text quoted from
    another comment on the same post.  In this case, the
    user name of the author of the quoted text will be
    wrapped with a link of the form:
        <a href="#comment-NNNN" ... >
    where NNNN is the comment's ID number.  This relative
    link will work within the post (neglecting, for the
    moment, the issue of comment pagination, which is
    another matter), but in the context of the My Comments
    page it will fail.  Here we find these relative links
    and rewrite them to cite the permalink of the post
    in which the quoted comment appeared and, if
    necessary, the page number.  Returns the comment
    content with relative links fixed.  */

function rb_my_adjust_quote_links($comment) {
    return preg_replace_callback('/href="#comment\-(\d+)"/',
        function ($rellink) {
            global $open_new_tab;
            
            $comment_no = $rellink[1];
            $qcomment = get_comment($comment_no);
            $abslink = rb_my_comments_comment_URL($qcomment);
            if (get_option('rb_my_open_new_tab') == 'on') {
                $new_tab = ' target="_blank"';
            } else {
                $new_tab = '';
            }
            return 'href="' . $abslink . '"' . $new_tab;
        }, $comment->comment_content);
}

/*  Determine the URL to link to a comment within a post.  This
    is called with the comment object and returns a URL to
    display the comment.  This is complicated by the fact
    that comments may be broken into multiple pages and
    that we must specify the page if the comment isn't on
    the first page.  */

function rb_my_comments_comment_URL($comment) {
    //  Naturally, defaults for get_page_of_comment() args don't work
    $comment_page = get_page_of_comment($comment->comment_ID,
        array(type => 'all', per_page => get_option('comments_per_page')));
    return get_permalink($comment->comment_post_ID) .
        (($comment_page > 1) ? ("comment-page-" . $comment_page . "/") : "") .
        '#comment-' . $comment->comment_ID;
}

/*  When the plug-in is deactivated, delete our persistent settings
    from the database.  */

register_uninstall_hook(__FILE__, 'rb_my_comments_uninstall'); // uninstall plug-in

function rb_my_comments_uninstall() {
    delete_option('rb_my_pagination');
    delete_option('rb_my_comments_per_page');
    delete_option('rb_my_avatar');
    delete_option('rb_my_show_date');
    delete_option('rb_my_open_new_tab');
    delete_option('rb_my_comments_order');
    delete_option('rb_my_show_post_link');
    delete_option('rb_my_show_comment_link');
}
