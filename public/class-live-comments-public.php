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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = 'live-comments';
		$this->version = '1.0.0';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

            wp_enqueue_script('jquery');
            wp_enqueue_script('underscore');
            wp_enqueue_script('backbone');
            wp_enqueue_script( $this->plugin_name.'-md5', plugin_dir_url( __FILE__ ) . 'js/libs/md5/md5.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name.'-model', plugin_dir_url( __FILE__ ) . 'js/models/comment.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name.'-collection', plugin_dir_url( __FILE__ ) . 'js/collections/comments.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name.'-view', plugin_dir_url( __FILE__ ) . 'js/views/comments.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name.'-app', plugin_dir_url( __FILE__ ) . 'js/app.js', array( 'jquery' ), $this->version, false );

	}

}
