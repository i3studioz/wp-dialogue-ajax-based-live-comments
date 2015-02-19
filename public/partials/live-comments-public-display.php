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
        <?php else: ?>
        <?php comment_form(); ?>
        <?php //if (have_comments()) :  ?>
        <h2 class="comments-title">
            <?php
            printf(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'live-comments'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
            ?>
        </h2>
        <a href="javascript:;" id="load-new-comments"></a>
        <ol class="comment-list"></ol><!-- .comment-list -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through    ?>
            <nav id="comment-nav-below" class="comment-navigation" role="navigation">
                <a href="javascript:;" class="nav-previous" id="load-old-comments"><?php _e('Older Comments', 'live-coments'); ?></a>
            </nav><!-- #comment-nav-below -->
        <?php endif; // check for comment navigation    ?>

    <?php endif; // have_comments()    ?>

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
    <time datetime="<%= comment_iso_time %>"><%= comment_date_readable %></time>
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

<script type="text/template" id="new-comments">
    <% if(count > 0){ %><span><%= count %> new comments</span><% } %>
</script>
