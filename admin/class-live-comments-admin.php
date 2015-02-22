<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Live_Comments
 * @subpackage Live_Comments/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Live_Comments
 * @subpackage Live_Comments/admin
 * @author     Arun Singh <devarun444@gmail.com>
 */
class Live_Comments_Admin {

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
     * @var      string    $plugin_name       The name of this plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Live_Comments_Admin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Live_Comments_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('wp-color-picker');

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/live-comments-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Live_Comments_Admin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Live_Comments_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/live-comments-admin.js', array('jquery', 'wp-color-picker'), $this->version, false);
    }

    /**
     * Add new setting options for the discussion section to
     * allow admins to customize the plugin behavior
     */
    //add_action('admin_init', 'lc_settings');
    function lc_settings() {
        add_settings_section('lc_settings', __('Live Comment Settings', $this->plugin_name), array(&$this, 'lc_discussion_options'), 'discussion');

        add_settings_field(
                'lc_avatar_size', __('Avatar Size', $this->plugin_name), array(&$this, 'lc_get_setting_field'), 'discussion', 'lc_settings', array('name' => 'lc_avatar_size', 'type' => 'number', 'description' => __('Set sefault avatar size to be displayed in comments area.', $this->plugin_name)
                )
        );

        add_settings_field(
                'lc_form_position', __('Comment Form Position', $this->plugin_name), array(&$this, 'lc_get_setting_field'), 'discussion', 'lc_settings', array('name' => 'lc_form_position', 'type' => 'radio', 'options' => array('top' => 'Above Comments', 'bottom' => 'Below Comments'))
        );

        add_settings_field(
                'lc_refresh_interval', __('Comments Refresh Interval', $this->plugin_name), array(&$this, 'lc_get_setting_field'), 'discussion', 'lc_settings', array('name' => 'lc_refresh_interval', 'type' => 'number', 'min' => 5000, 'steps' => 500, 'description' => __('Set refresh interval for fetching live comments.', $this->plugin_name)
                )
        );

        add_settings_field(
                'lc_highlight_color', __('New Comment Highlight Color', $this->plugin_name), array(&$this, 'lc_get_setting_field'), 'discussion', 'lc_settings', array('name' => 'lc_highlight_color', 'type' => 'color')
        );

        // Finally, we register the fields with WordPress
        register_setting('discussion', 'lc_avatar_size');

        register_setting('discussion', 'lc_form_position');

        register_setting('discussion', 'lc_refresh_interval');

        register_setting('discussion', 'lc_highlight_color');
    }

    function lc_discussion_options() {
        echo '<p>' . __('', $this->plugin_name) . '</p>';
    }

    function lc_get_setting_field($args) {
        //print_r($args);
        //echo get_option($args['name']);
        $html = '<fieldset>';
        switch ($args['type']) {
            case 'checkbox':
                $html .= '<input type="checkbox" id="' . $args['name'] . '" name="' . $args['name'] . '" value="1" ' . checked(1, get_option($args['name']), false) . '/>';
                $html .= '<label for="' . $args['name'] . '"> ' . $args['description'] . '</label>';
                break;

            case 'radio':
                foreach ($args['options'] as $key => $value) {
                    $html .= '<label for="' . $args['name'] . '_' . $key . '">';
                    $html .= '<input type="radio" id="' . $args['name'] . '_' . $key . '" name="' . $args['name'] . '" value="' . $key . '" ' . checked($key, get_option($args['name']), false) . '/>';
                    $html .= $value . '</label><br />';
                }
                break;

            case 'number':
                $step = isset($args["steps"]) ? $args["steps"] : 1;
                $min = isset($args["min"]) ? $args["min"] : 1;
                $html = '<input type="number" min="' . $min . '" step="' . $step . '" id="' . $args['name'] . '" name="' . $args['name'] . '" value="' . get_option($args['name']) . '" />';
                $html .= '<label for="' . $args['name'] . '"> ' . $args['description'] . '</label>';
                break;

            case 'color':
                $html .= '<input type="text" class="color-field" id="' . $args['name'] . '" name="' . $args['name'] . '" value="' . get_option($args['name']) . '" />';
                //$html .= '<label for="' . $args['name'] . '"> ' . $args['description'] . '</label>';
                break;

            default:

                $html .= '<input type="text" id="' . $args['name'] . '" name="' . $args['name'] . '" value="' . get_option($args['name']) . '" />';
                $html .= '<label for="' . $args['name'] . '"> ' . $args['description'] . '</label>';
                break;
        }

        $html .= '</fieldset>';

        echo $html;
    }

}
