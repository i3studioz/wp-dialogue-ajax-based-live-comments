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
    <?php else: ?>
        <?php
        if (get_option('lc_form_position') == 'top') {
            comment_form();
            ?>
            <h2 class="comments-title">
                <?php
                //printf(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wp-dialogue'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
                ?>
            </h2>
            <a href="javascript:;" id="load-new-comments"></a>
        <?php } elseif (get_comment_pages_count() > 1 && get_option('page_comments')) { // are there comments to navigate through
            ?>
            <h2 class="comments-title">
                <?php
                //printf(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wp-dialogue'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
                ?>
            </h2>
            <nav id="comment-nav-above" class="comment-navigation" role="navigation">
                <a href="javascript:;" class="nav-previous" id="load-old-comments"><?php _e('Older Comments', 'live-coments'); ?></a>
            </nav><!-- #comment-nav-below -->
        <?php } // check for comment navigation  ?>
        <ol class="comment-list"></ol><!-- .comment-list -->
        <?php
        if (get_option('lc_form_position') == 'bottom') {
            echo '<a href="javascript:;" id="load-new-comments"></a>';
            comment_form();
        } elseif (get_comment_pages_count() > 1 && get_option('page_comments')) { // are there comments to navigate through
            ?>
            <nav id="comment-nav-below" class="comment-navigation" role="navigation">
                <a href="javascript:;" class="nav-previous" id="load-old-comments"><?php _e('Older Comments', 'live-coments'); ?></a>
            </nav><!-- #comment-nav-below -->
        <?php } // check for comment navigation ?>
    <?php endif; // have_comments()       ?>

</div><!-- #comments -->