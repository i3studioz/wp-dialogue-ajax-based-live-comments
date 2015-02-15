<?php
if (post_password_required())
    return;
?>

<div id="comments" class="comments-area">

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php _e('Comments are closed.', 'live-comments'); ?></p>
    <?php endif; ?>



    <?php
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
            '<label for="author">' . __('Name', 'live-comments') . '</label> ' .
            ( $req ? '<span class="required">*</span>' : '' ) .
            '<input id="author" name="author" class="form-control" type="text" value="' . esc_attr($commenter['comment_author']) .
            '" size="30"' . $aria_req . ' /></div>',
            'email' =>
            '<div class="form-group"><label for="email">' . __('Email', 'live-comments') . '</label> ' .
            ( $req ? '<span class="required">*</span>' : '' ) .
            '<input id="email" name="email" class="form-control" type="text" value="' . esc_attr($commenter['comment_author_email']) .
            '" size="30"' . $aria_req . ' /></div>',
            'url' =>
            '<div class="form-group><label for="url">' .
            __('Website', 'live-comments') . '</label>' .
            '<input id="url" name="url" class="form-control" type="text" value="' . esc_attr($commenter['comment_author_url']) .
            '" size="30" /></div>'
                )
        ),
    );


    comment_form($args);
    ?>

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            printf(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'live-comments'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
            ?>
        </h2>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through   ?>
            <nav id="comment-nav-above" class="comment-navigation" role="navigation">
                <h1 class="screen-reader-text"><?php _e('Comment navigation', 'live-comments'); ?></h1>
                <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'live-comments')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'live-comments')); ?></div>
            </nav><!-- #comment-nav-above -->
        <?php endif; // check for comment navigation   ?>

        <ol class="comment-list"></ol><!-- .comment-list -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through   ?>
            <nav id="comment-nav-below" class="comment-navigation" role="navigation">
                <h1 class="screen-reader-text"><?php _e('Comment navigation', 'live-coments'); ?></h1>
                <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'live-coments')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'live-coments')); ?></div>
            </nav><!-- #comment-nav-below -->
        <?php endif; // check for comment navigation   ?>

    <?php endif; // have_comments()   ?>

</div><!-- #comments -->


<script type="text/template" id="comments-template">
    <li id="comment-<%= comment_id %>" <%= comment_class %>>
    <article id="div-comment-<%= comment_id %>" class="comment-body row">
    <footer class="comment-meta">
    <div class="comment-author vcard col-md-2 col-xs-3">
    <%= avatar %>
    </div>
    <div class="comment-metadata col-md-10 col-xs-7">
    <cite class="fn"><% if(website){ %><a href="<%= website %>" rel="external nofollow" class="url"><%= author %></a><% } else { %><%= author %><% } %></cite>
    on <a href="<%= comment_post_link %>">
    <time datetime="<%= comment_iso_time %>"><%= comment_date %></time>
    </a>
    </div>
    <% if(moderation_required){ %><p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'live-comments'); ?></p><% } %>
    </footer>
    <div class="comment-content col-md-10 col-xs-7">
    <p><%= comment %></p>
    </div>
    <%= reply_link %>
    </article>
    <ol class="children"></ol>
    </li>
</script>
