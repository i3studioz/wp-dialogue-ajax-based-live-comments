<?php
if (post_password_required())
    return;
?>

<div id="comments" class="comments-area">

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php _e('Comments are closed.', 'wp-dialogue'); ?></p>
    <?php
    else:
        $args = array();
        if (get_option('lc_enable_bootstrap')){
        //Displaying the Comment Form

        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ( $req ? " aria-required='true'" : '' );

        $args = array(
            'comment_field' => '<div class="form-group"><label for="comment">' . _x('Comment', 'noun') .
            '</label><textarea id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true">' .
            '</textarea></div>',
            'fields' => apply_filters('comment_form_default_fields', array(
                'author' =>
                '<div class="form-group">' .
                '<label for="author">' . __('Name', 'live-comments') .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '</label> <input id="author" name="author" class="form-control" type="text" value="' . esc_attr($commenter['comment_author']) .
                '" size="30"' . $aria_req . ' /></div>',
                'email' =>
                '<div class="form-group"><label for="email">' . __('Email', 'live-comments') . 
                ( $req ? '<span class="required">*</span>' : '' ) .
                '</label><input id="email" name="email" class="form-control" type="text" value="' . esc_attr($commenter['comment_author_email']) .
                '" size="30"' . $aria_req . ' /></div>',
                'url' =>
                '<div class="form-group><label for="url">' .
                __('Website', 'live-comments') . '</label>' .
                '<input id="url" name="url" class="form-control" type="text" value="' . esc_attr($commenter['comment_author_url']) .
                '" size="30" /></div>'
                    )
            ),
        );
        }
        ?>
        <?php
        if (get_option('lc_form_position') == 'top') {
            comment_form($args);
            ?>
            <h2 class="comments-title">
                <?php
                //printf(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wp-dialogue'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
                ?>
            </h2>
            <a href="javascript:;" id="load-new-comments"></a>
        <?php } elseif (get_comment_pages_count() > 0 && get_option('page_comments')) { // are there comments to navigate through
            ?>
            <h2 class="comments-title">
                <?php
                //printf(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wp-dialogue'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
                ?>
            </h2>
            <nav id="comment-nav-above" class="comment-navigation" role="navigation">
                <a href="javascript:;" class="nav-previous" id="load-old-comments"><?php _e('Older Comments', 'live-coments'); ?></a>
            </nav><!-- #comment-nav-below -->
        <?php } // check for comment navigation   ?>
        <?php $comment_format = get_option('lc_comment_format'); echo "<$comment_format class='comment-list'></$comment_format>"; ?><!-- .comment-list -->
        <?php
        if (get_option('lc_form_position') == 'bottom') {
            echo '<a href="javascript:;" id="load-new-comments"></a>';
            comment_form($args);
        } elseif (get_comment_pages_count() > 1 && get_option('page_comments')) { // are there comments to navigate through
            ?>
            <nav id="comment-nav-below" class="comment-navigation" role="navigation">
                <a href="javascript:;" class="nav-previous" id="load-old-comments"><?php _e('Older Comments', 'live-coments'); ?></a>
            </nav><!-- #comment-nav-below -->
        <?php } // check for comment navigation  ?>
    <?php endif; // have_comments()       ?>

</div><!-- #comments -->