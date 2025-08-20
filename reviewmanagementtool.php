<?php
/*
Plugin Name: Review Management Tool Widget Embedder
Plugin URI: https://blog.reviewmanagementtool.com/2025/07/how-to-easily-add-testimonials-to-your.html
Description: Embed testimonial widgets from ReviewManagementTool.com using a shortcode and Gutenberg block.
Version: 1.0.1
Author: Review Management Tool Admin
Author URI: https://reviewmanagementtool.com/aboutUs
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: reviewmanagementtool
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Gutenberg Block
function rmt_register_block() {
    wp_register_script(
        'rmt-block',
        plugins_url('build/index.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
        filemtime(plugin_dir_path(__FILE__) . 'build/index.js'),
        true // Load in footer
    );

    register_block_type('rmt/embed', array(
        'editor_script' => 'rmt-block',
        'render_callback' => 'rmt_render_block',
        'attributes' => array(
            'widgetId' => array(
                'type' => 'string',
                'default' => '',
            ),
            'type' => array(
                'type' => 'string',
                'default' => 'iframe',
            ),
        ),
    ));
}
add_action('init', 'rmt_register_block');

// Render Gutenberg block on frontend
function rmt_render_block($attributes) {
    return rmt_render_embed(array(
        'widget_id' => $attributes['widgetId'] ?? '',
        'type' => $attributes['type'] ?? 'iframe',
    ));
}

// Register Shortcode
function rmt_register_shortcode($atts) {
    return rmt_render_embed($atts);
}
add_shortcode('rmtool', 'rmt_register_shortcode');

// Common embed renderer
function rmt_render_embed($atts) {
    $atts = shortcode_atts(array(
        'widget_id' => '',
        'type' => 'iframe',
    ), $atts);

    $id = esc_attr($atts['widget_id']);
    $type = esc_attr($atts['type']);

    if (empty($id)) {
        $id = '954985da-8deb-4369-b2a1-2d18c73b3486'; // Default fallback ID
    }

    if ($type === 'js') {
        return "<div class='rmt-widget' data-id='{$id}'></div>";
    } else {
        return "<iframe src='https://reviewmanagementtool.com/embed/widget/{$id}' width='100%' height='400' frameborder='0'></iframe>";
    }
}

// Enqueue JS only when shortcode is used
function rmt_maybe_enqueue_widget_script() {
    if (is_singular() || is_page()) {
        global $post;
        if (isset($post->post_content) && has_shortcode($post->post_content, 'rmtool')) {
            wp_enqueue_script(
                'rmt-embed',
                'https://reviewmanagementtool.com/widgets/embed.js',
                array(),
                '1.0.0',
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'rmt_maybe_enqueue_widget_script');

// Admin Help Page
add_action('admin_menu', function() {
    add_options_page(
        'Review Management Tool',
        'Review Management Tool',
        'manage_options',
        'review-management-tool',
        'rmt_settings_page'
    );
});

function rmt_settings_page() {
    ?>
    <div class="wrap">
        <h1>Review Management Tool – Quick Start Guide</h1>

        <h2>🚀 How to Use This Plugin (1-Minute Setup)</h2>
        <ol style="margin-left: 20px; padding-left: 0; list-style: decimal;">
            <li>Go to <a href="https://reviewmanagementtool.com" target="_blank">ReviewManagementTool.com</a></li>
            <li>Sign up & add your text/video/audio testimonials</li>
            <li>Tag testimonials and create a widget</li>
            <li>Copy the <strong>Widget ID</strong> from your dashboard</li>
            <li>Return to WordPress:
                <ul style="list-style: disc; margin-left: 20px;">
                    <li>Use shortcode: <code>[rmtool widget_id="YOUR_WIDGET_ID" type="iframe"]</code></li>
                    <li>Or insert the <strong>Review Management Widget</strong> Gutenberg block</li>
                </ul>
            </li>
            <li>Choose embed type: <strong>iframe</strong> (simple) or <strong>js</strong> (SEO-friendly)</li>
        </ol>

        <hr>

        <p>📘 <strong>Need more help?</strong> 
            <a href="https://blog.reviewmanagementtool.com/2025/07/how-to-easily-add-testimonials-to-your.html" target="_blank">
                Read the full tutorial with screenshots
            </a>
        </p>

        <p>📩 <strong>Need support?</strong> Email us at <a href="mailto:hello@reviewmanagementtool.com">hello@reviewmanagementtool.com</a></p>
    </div>
    <?php
}