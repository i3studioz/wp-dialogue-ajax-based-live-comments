<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Live_Comments
 * @subpackage Live_Comments/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Live Comments
 * @subpackage Live_Comments/public
 * @author     Arun Singh <devarun444@gmail.com>
 */
class Live_Comments_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string    $plugin_name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = 'live-comments';
        $this->version = '1.0.0';
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/live-comments-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScripts for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script('jquery');
        wp_enqueue_script('comment-reply');
        wp_enqueue_script('underscore');
        wp_enqueue_script('backbone');
        wp_enqueue_script($this->plugin_name . '-model', plugin_dir_url(__FILE__) . 'js/models/comment.js', array('jquery', 'comment-reply'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-collection', plugin_dir_url(__FILE__) . 'js/collections/comments.js', array('jquery', 'comment-reply'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-view', plugin_dir_url(__FILE__) . 'js/views/comments.js', array('jquery', 'comment-reply'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-app', plugin_dir_url(__FILE__) . 'js/app.js', array('jquery', 'comment-reply'), $this->version, true);

        // Now we can localize the script with our data.
        $app_vars = array('db_comments' => $this->lc_get_db_comments(get_the_ID(), 0, false));
        wp_localize_script($this->plugin_name . '-app', 'app_vars', $app_vars);
    }

    /**
     * override the comments template from live comments comment template
     * 
     * @global object $post
     * @param string $comment_template
     * @return string
     */
    public function lc_comments_template($comment_template) {
        global $post;
        if (!( is_singular() && ( have_comments() || 'open' == $post->comment_status ) )) {
            return;
        } else {
            return plugin_dir_path(__FILE__) . 'partials/live-comments-public-display.php';
        }
    }

    public function lc_get_db_comments($post_id = 0, $start_id = 0, $doing_ajax = true) {
        global $comment_depth; //, $post;
        if ($post_id == 0 && isset($_GET['post_id']))
            $post_id = $_GET['post_id'];

        $args = array(
            'post_id' => $post_id,
            'order' => 'ASC'
        );

        if (isset($_GET['type'])) {
            switch ($_GET['type']) {
                case 'newer':
                    $args['date_query'] = array('after' => $_GET['new_start']);

                    break;
                case 'older':
                    $args['date_query'] = array('before' => strtotime($_GET['old_start']));
                    break;

                default:
                    //$args['date_query'] = array('before' => strtotime($_GET['new_start']));
            }
        }
        $comments = get_comments($args);
        $localized_comment = array();
        foreach ($comments as $comment) {
            if ($comment->comment_approved != 'spam') {
                $comment_depth = $this->lc_get_comment_depth($comment->comment_ID);
                $localized_comment[] = array
                    (
                    'comment_id' => $comment->comment_ID,
                    'comment_post_id' => $comment->comment_post_ID,
                    'comment_parent' => $comment->comment_parent,
                    'comment_class' => comment_class('', $comment->comment_ID, $post_id, false),
                    'author' => $comment->comment_author,
                    'email' => $comment->comment_author_email,
                    'website' => $comment->comment_author_url,
                    'avatar' => get_avatar($comment->comment_author_email, 96),
                    'avatar_size' => 96,
                    'comment_post_link' => esc_url(get_comment_link($comment->comment_ID)),
                    'comment_iso_time' => get_comment_date('c', $comment->comment_ID),
                    'comment_date' => get_comment_date('d F Y', $comment->comment_ID),
                    'comment' => $comment->comment_content,
                    'moderation_required' => !$comment->comment_approved,
                    'reply_link' => get_comment_reply_link(array('depth' => $comment_depth, 'max_depth' => get_option('thread_comments_depth')), $comment->comment_ID, $comment->comment_post_ID)
                );
            }
        }
        if ($doing_ajax) {
            echo json_encode($localized_comment);
            die();
        } else {
            return $localized_comment;
        }
    }

    public function lc_get_comment_from_db($comment_id) {
        $comment_vars = get_comment($comment_id);
        //print_r($comment);
        $comment_data = array(
            'comment_id' => $comment_vars->comment_ID,
            'comment_post_id' => $comment_vars->comment_post_ID,
            'comment_class' => comment_class('', $comment_vars->comment_ID, $comment_vars->comment_post_ID, false),
            'author' => $comment_vars->comment_author,
            'email' => $comment_vars->comment_author_email,
            'website' => $comment_vars->comment_author_url,
            'avatar' => get_avatar($comment_vars->comment_author_email, 96),
            'avatar_size' => 96,
            'comment_post_link' => esc_url(get_comment_link($comment_vars->comment_ID)),
            'comment_iso_time' => get_comment_date('c', $comment_vars->comment_ID),
            'comment_date' => get_comment_date('d F Y', $comment_vars->comment_ID),
            'comment' => $comment_vars->comment_content,
            'moderation_required' => !$comment_vars->comment_approved
        );

        return $comment_data;
    }

    public function lc_add_comment_to_db() {
        global $comment_depth;
        $time = current_time('mysql');
        $post_vars = json_decode(file_get_contents("php://input"), true);
############################### Temp Comment ###################################
//        $data = array(
//            'comment_post_ID' => $post_vars['comment_post_id'],
//            'comment_author' => $post_vars['author'],
//            'comment_author_email' => $post_vars['email'],
//            'comment_author_url' => $post_vars['website'],
//            'comment_content' => $post_vars['comment'],
//            'comment_type' => '',
//            'comment_parent' => 0,
//            'user_id' => 1,
//            'comment_author_IP' => '127.0.0.1',
//            'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
//            'comment_date' => $time,
//            'comment_approved' => 1,
//        );
//
//        $comment_id = wp_insert_comment($data);
        //print_r($comment);
########################### Replacing with default comment posting #######################

        nocache_headers();

        $comment_post_ID = isset($post_vars['comment_post_id']) ? (int) $post_vars['comment_post_id'] : 0;

        $post = get_post($comment_post_ID);

        if (empty($post->comment_status)) {
            /**
             * Fires when a comment is attempted on a post that does not exist.
             *
             * @since 1.5.0
             *
             * @param int $comment_post_ID Post ID.
             */
            do_action('comment_id_not_found', $comment_post_ID);
            exit;
        }

// get_post_status() will get the parent status for attachments.
        $status = get_post_status($post);

        $status_obj = get_post_status_object($status);

        if (!comments_open($comment_post_ID)) {
            /**
             * Fires when a comment is attempted on a post that has comments closed.
             *
             * @since 1.5.0
             *
             * @param int $comment_post_ID Post ID.
             */
            do_action('comment_closed', $comment_post_ID);
            //wp_die(__('Sorry, comments are closed for this item.'));
            echo json_encode(array('error' => 'Sorry, comments are closed for this item.'));
            die();
        } elseif ('trash' == $status) {
            /**
             * Fires when a comment is attempted on a trashed post.
             *
             * @since 2.9.0
             *
             * @param int $comment_post_ID Post ID.
             */
            do_action('comment_on_trash', $comment_post_ID);
            exit;
        } elseif (!$status_obj->public && !$status_obj->private) {
            /**
             * Fires when a comment is attempted on a post in draft mode.
             *
             * @since 1.5.1
             *
             * @param int $comment_post_ID Post ID.
             */
            do_action('comment_on_draft', $comment_post_ID);
            exit;
        } elseif (post_password_required($comment_post_ID)) {
            /**
             * Fires when a comment is attempted on a password-protected post.
             *
             * @since 2.9.0
             *
             * @param int $comment_post_ID Post ID.
             */
            do_action('comment_on_password_protected', $comment_post_ID);
            exit;
        } else {
            /**
             * Fires before a comment is posted.
             *
             * @since 2.8.0
             *
             * @param int $comment_post_ID Post ID.
             */
            do_action('pre_comment_on_post', $comment_post_ID);
        }

        $comment_author = ( isset($post_vars['author']) ) ? trim(strip_tags($post_vars['author'])) : null;
        $comment_author_email = ( isset($post_vars['email']) ) ? trim($post_vars['email']) : null;
        $comment_author_url = ( isset($post_vars['website']) ) ? trim($post_vars['website']) : null;
        $comment_content = ( isset($post_vars['comment']) ) ? trim($post_vars['comment']) : null;

// If the user is logged in
        $user = wp_get_current_user();
        if ($user->exists()) {
            if (empty($user->display_name))
                $user->display_name = $user->user_login;
            $comment_author = wp_slash($user->display_name);
            $comment_author_email = wp_slash($user->user_email);
            $comment_author_url = wp_slash($user->user_url);
            if (current_user_can('unfiltered_html')) {
                if (!isset($post_vars['_wp_unfiltered_html_comment']) || !wp_verify_nonce($post_vars['_wp_unfiltered_html_comment'], 'unfiltered-html-comment_' . $comment_post_ID)
                ) {
                    kses_remove_filters(); // start with a clean slate
                    kses_init_filters(); // set up the filters
                }
            }
        } else {
            if (get_option('comment_registration') || 'private' == $status) {
                //wp_die(__('Sorry, you must be logged in to post a comment.'));
                echo json_encode(array('error' => 'Sorry, you must be logged in to post a comment.'));
                die();
            }
        }

        $comment_type = '';

        if (get_option('require_name_email') && !$user->exists()) {
            if (6 > strlen($comment_author_email) || '' == $comment_author) {
                //wp_die(__('<strong>ERROR</strong>: please fill the required fields (name, email).'));
                echo json_encode(array('error' => '<strong>ERROR</strong>: please fill the required fields (name, email).'));
                die();
            } elseif (!is_email($comment_author_email)) {
                //wp_die(__('<strong>ERROR</strong>: please enter a valid email address.'));
                echo json_encode(array('error' => '<strong>ERROR</strong>: please enter a valid email address.'));
                die();
            }
        }

        if ('' == $comment_content) {
            //wp_die(__('<strong>ERROR</strong>: please type a comment.'));
            echo json_encode(array('error' => '<strong>ERROR</strong>: please type a comment.'));
            die();
        }

        $comment_parent = isset($post_vars['comment_parent']) ? absint($post_vars['comment_parent']) : 0;

        $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

        $comment_id = wp_new_comment($commentdata);
        if (!$comment_id) {
            //wp_die(__("<strong>ERROR</strong>: The comment could not be saved. Please try again later."));
            echo json_encode(array('error' => '<strong>ERROR</strong>: The comment could not be saved. Please try again later.'));
            die();
        }

        $comment = get_comment($comment_id);
        if ($comment->comment_approved != 'spam') {
            $comment_depth = $this->lc_get_comment_depth($comment_id);
            $comment_data = array(
                'comment_id' => $comment->comment_ID,
                'comment_post_id' => $comment->comment_post_ID,
                'comment_parent' => $comment->comment_parent,
                'comment_class' => comment_class('', $comment->comment_ID, $comment->comment_post_ID, false),
                'author' => $comment->comment_author,
                'email' => $comment->comment_author_email,
                'website' => $comment->comment_author_url,
                'avatar' => get_avatar($comment->comment_author_email, 96),
                'avatar_size' => 96,
                'comment_post_link' => esc_url(get_comment_link($comment->comment_ID)),
                'comment_iso_time' => get_comment_date('c', $comment->comment_ID),
                'comment_date' => get_comment_date('d F Y', $comment->comment_ID),
                'comment' => $comment->comment_content,
                'moderation_required' => !$comment->comment_approved,
                'reply_link' => get_comment_reply_link(array('depth' => $comment_depth, 'max_depth' => get_option('thread_comments_depth')), $comment->comment_ID, $comment->comment_post_ID)
            );
        }

        /**
         * Perform other actions when comment cookies are set.
         *
         * @since 3.4.0
         *
         * @param object $comment Comment object.
         * @param WP_User $user   User object. The user may not exist.
         */
        do_action('set_comment_cookies', $comment, $user);

//        @todo Check possible solutiond for redirection
//        
//        $location = empty($post_vars['redirect_to']) ? get_comment_link($comment_id) : $post_vars['redirect_to'] . '#comment-' . $comment_id;
//
//        /**
//         * Filter the location URI to send the commenter after posting.
//         *
//         * @since 2.0.5
//         *
//         * @param string $location The 'redirect_to' URI sent via $_POST.
//         * @param object $comment  Comment object.
//         */
//        $location = apply_filters('comment_post_redirect', $location, $comment);
//
//        wp_safe_redirect($location);
########################### Replacing with default comment posting #######################
//        $comment_data = array();
//        if ($comment_id) {
//            $comment_data = $this->lc_get_comment_from_db($comment_id);
//        }
//wp_send_json($comment_data);

        echo json_encode($comment_data);

        die();
    }

    /**
     * 
     * @global reference $wpdb
     * @param int $comment_id
     * @return type
     */
    function lc_get_comment_depth($comment_id, $count = 1) {
        global $wpdb;
        $parent = $wpdb->get_var("SELECT comment_parent FROM $wpdb->comments WHERE comment_ID = '$comment_id'");
        //$count = 0;
        if ($parent == 0) {
            return $count;
        } else {
            $count += 1;
            return $this->lc_get_comment_depth($parent, $count);
        }
    }

    /**
     * Add required hidden fields for logged in users
     */
    function lc_logged_user_hidden_fields() {
        global $current_user;
        //print_r($current_user);



        $fields = '';
        $fields .= '<input type="hidden" value="' . $current_user->display_name . '" id="author" />';
        $fields .= '<input type="hidden" value="' . $current_user->user_email . '" id="email" />';
        $fields .= '<input type="hidden" value="' . $current_user->user_url . '" id="url" />';
        echo $fields;
    }

    /**
     * Global JavaScript object vatiables used by the app
     */
    function lc_global_js_vars() {
        global $wpdb, $current_user;
        $post_id = get_the_ID();
        $new_start = $wpdb->get_var("SELECT comment_date from $wpdb->comments WHERE comment_post_ID = '$post_id' AND comment_ID = (SELECT MAX(comment_ID) from $wpdb->comments)");
        $old_start = $wpdb->get_var("SELECT comment_date from $wpdb->comments WHERE comment_post_ID = '$post_id' AND comment_ID = (SELECT MIN(comment_ID) from $wpdb->comments)");
        //print_r($new_start);
        echo '<script type="text/javascript">
             /* <![CDATA[ */
             var lc_vars = ' . json_encode(array('post_id' => $post_id, 'current_user' => $current_user->ID, 'ajax_url' => admin_url('admin-ajax.php'), 'new_start' => $new_start, 'old_start' => $old_start)) .
        '/* ]]> */
            </script>';
    }

}
