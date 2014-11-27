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
        wp_enqueue_script('underscore');
        wp_enqueue_script('backbone');
        wp_enqueue_script($this->plugin_name . '-model', plugin_dir_url(__FILE__) . 'js/models/comment.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-collection', plugin_dir_url(__FILE__) . 'js/collections/comments.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-view', plugin_dir_url(__FILE__) . 'js/views/comments.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-app', plugin_dir_url(__FILE__) . 'js/app.js', array('jquery'), $this->version, true);

        // Now we can localize the script with our data.
        $app_vars = array('post_id' => get_the_ID(), 'current_user' => get_current_user_id(), 'ajax_url' => admin_url('admin-ajax.php'), 'db_comments' => $this->lc_get_db_comments(get_the_ID()));
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

    public function lc_get_db_comments($post_id) {

        $args = array(
            'post_id' => $post_id,
            'order' => 'ASC'
        );

        $comments = get_comments($args);
        $localized_comment = array();
        foreach ($comments as $comment) {
            $localized_comment[] = array(
                'comment_id' => $comment->comment_ID,
                'comment_post_id' => $comment->comment_post_ID,
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
                'moderation_required' => !$comment->comment_approved);
        }

        return $localized_comment;
    }

    public function lc_get_comment_from_db($comment_id) {
        
    }

    public function lc_add_comment_to_db() {

        $time = current_time('mysql');

        $data = array(
            'comment_post_ID' => $_REQUEST['comment_post_id'],
            'comment_author' => $_REQUEST['author'],
            'comment_author_email' => $_REQUEST['email'],
            'comment_author_url' => $_REQUEST['url'],
            'comment_content' => $_REQUEST['comment'],
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => 1,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );

        $comment = wp_insert_comment($data);

        print_r($comment);
        die();
    }

}
