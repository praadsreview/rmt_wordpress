<?php
/**
 * Plugin Name: Review Management Tool
 * Plugin URI:  https://blog.reviewmanagementtool.com/2025/07/how-to-easily-add-testimonials-to-your.html
 * Description: Collect, manage, and showcase testimonials on your WordPress site with a shortcode and settings screen.
 * Version:     1.1.2
 * Author:      Review Management Tool Team
 * Author URI:  https://reviewmanagementtool.com/aboutUs
 * Text Domain: reviewmanagementtool
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * -----------------------------------------------------------------------------
 * Constants
 * -----------------------------------------------------------------------------
 */
define( 'RMT_FILE', __FILE__ );
define( 'RMT_BASENAME', plugin_basename( __FILE__ ) );
define( 'RMT_DIR', plugin_dir_path( __FILE__ ) );
define( 'RMT_URL', plugins_url( '/', __FILE__ ) );
define( 'RMT_VERSION', '1.1.2' );

/**
 * Default options (used as fallbacks; no settings UI shown)
 */
function rmt_default_options() {
    return array(
        'default_widget_id' => '',
        // Example CDN base: https://cdn.reviewmanagementtool.com/widget-static
        'cdn_base_url'      => 'https://cdn.reviewmanagementtool.com/widget-static',
    );
}

/**
 * (Optional) On activation, ensure an options row exists with sensible defaults.
 * Keeping this is harmless even without a settings form.
 */
register_activation_hook( __FILE__, function () {
    $defaults = rmt_default_options();
    $existing = get_option( 'reviewmanagementtool_options' );
    if ( ! is_array( $existing ) ) {
        update_option( 'reviewmanagementtool_options', $defaults );
    } else {
        update_option( 'reviewmanagementtool_options', wp_parse_args( $existing, $defaults ) );
    }
} );

/**
 * -----------------------------------------------------------------------------
 * Instructions block (replaces previous field)
 * -----------------------------------------------------------------------------
 */
function rmt_instructions_block() {
    ?>
    <div class="rmt-instructions" style="margin-top:12px;padding:12px;border:1px solid #ccd0d4;border-radius:6px;background:#fff;">
        <h2 style="margin:0 0 10px;"><?php echo esc_html__( 'How to embed testimonials from ReviewManagementTool.com', 'reviewmanagementtool' ); ?></h2>
        <ol style="margin:0 0 12px 18px;">
            <li><?php
                printf(
                    /* translators: %s is the platform URL */
                    esc_html__( 'Sign up or log in at %s.', 'reviewmanagementtool' ),
                    '<a href="' . esc_url( 'https://reviewmanagementtool.com' ) . '" target="_blank" rel="noopener noreferrer">reviewmanagementtool.com</a>'
                );
            ?></li>
            <li><?php echo esc_html__( 'Add your testimonials into the platform. You can organize them with tags (e.g., "SaaS Clients", "Web Design Projects") to create different collections.', 'reviewmanagementtool' ); ?></li>
            <li><?php echo esc_html__( 'Navigate to the Widgets section in your dashboard.', 'reviewmanagementtool' ); ?></li>
            <li><?php echo esc_html__( 'Create a new widget. Customize its appearance and choose which testimonials (or tags) to include.', 'reviewmanagementtool' ); ?></li>
            <li><?php echo esc_html__( 'Copy the unique Widget ID.', 'reviewmanagementtool' ); ?></li>
        </ol>

        <h3 style="margin:8px 0 6px;"><?php echo esc_html__( 'Use this shortcode in WordPress (current plugin):', 'reviewmanagementtool' ); ?></h3>
        <pre style="margin:0 0 10px;white-space:pre-wrap;"><code>[rmt_widget id="YOUR_ID"]</code></pre>

        <h3 style="margin:8px 0 6px;"><?php echo esc_html__( 'Alternate shortcode forms (if available in your install):', 'reviewmanagementtool' ); ?></h3>
        <pre style="margin:0 0 6px;white-space:pre-wrap;"><code>[rmtool widget_id="YOUR_ID" type="static"]  ; <?php echo esc_html__( 'lightweight & fast (static JS via CDN)', 'reviewmanagementtool' ); ?></code></pre>
        <pre style="margin:0 0 6px;white-space:pre-wrap;"><code>[rmtool widget_id="YOUR_ID" type="iframe"]  ; <?php echo esc_html__( 'iframe-based embed', 'reviewmanagementtool' ); ?></code></pre>
        <pre style="margin:0;white-space:pre-wrap;"><code>[rmtool widget_id="YOUR_ID" type="js"]      ; <?php echo esc_html__( 'dynamic JS loader', 'reviewmanagementtool' ); ?></code></pre>

        <p class="description" style="margin-top:10px;">
            <?php echo esc_html__( 'Note: This plugin’s built-in shortcode is [rmt_widget]. The [rmtool] variants are shown for compatibility with setups that provide them.', 'reviewmanagementtool' ); ?>
        </p>
    </div>
    <?php
}

/**
 * -----------------------------------------------------------------------------
 * Admin: menu + page (instructions only; no form, no save)
 * -----------------------------------------------------------------------------
 */
add_action( 'admin_menu', function () {
    add_menu_page(
        esc_html__( 'Review Management Tool', 'reviewmanagementtool' ),
        esc_html__( 'Review Tool', 'reviewmanagementtool' ),
        'manage_options',
        'reviewmanagementtool',
        'rmt_render_settings_page',
        'dashicons-testimonial',
        56
    );
} );

function rmt_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__( 'Review Management Tool – Instructions', 'reviewmanagementtool' ); ?></h1>
        <p class="description"><?php echo esc_html__( 'Follow these steps to generate a widget and embed it on your WordPress site.', 'reviewmanagementtool' ); ?></p>
        <hr />
        <?php rmt_instructions_block(); ?>
    </div>
    <?php
}

/**
 * Add "Settings" link on the Plugins list page.
 */
add_filter( 'plugin_action_links_' . RMT_BASENAME, function ( $links ) {
    $url      = admin_url( 'admin.php?page=reviewmanagementtool' );
    $settings = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'reviewmanagementtool' ) . '</a>';
    array_unshift( $links, $settings );
    return $links;
} );

/**
 * -----------------------------------------------------------------------------
 * Admin assets (optional): load build/index.js + style-index.css on our page
 * -----------------------------------------------------------------------------
 */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'toplevel_page_reviewmanagementtool' !== $hook ) {
        return;
    }

    // Enqueue style if present
    $style_path = RMT_DIR . 'build/style-index.css';
    if ( file_exists( $style_path ) ) {
        wp_enqueue_style(
            'reviewmanagementtool-admin',
            RMT_URL . 'build/style-index.css',
            array(),
            filemtime( $style_path )
        );
    }

    // Enqueue script if present with proper deps from index.asset.php
    $asset_file_path = RMT_DIR . 'build/index.asset.php';
    $script_path     = RMT_DIR . 'build/index.js';

    if ( file_exists( $script_path ) ) {
        $deps  = array();
        $ver   = filemtime( $script_path );

        if ( file_exists( $asset_file_path ) ) {
            $asset = include $asset_file_path;
            if ( is_array( $asset ) ) {
                $deps = isset( $asset['dependencies'] ) ? (array) $asset['dependencies'] : array();
                $ver  = isset( $asset['version'] ) ? $asset['version'] : $ver;
            }
        }

        wp_enqueue_script(
            'reviewmanagementtool-admin',
            RMT_URL . 'build/index.js',
            $deps,
            $ver,
            true
        );

        // JS translations (if your JS uses @wordpress/i18n) — guard for folder existence
        if ( is_dir( RMT_DIR . 'languages' ) ) {
            wp_set_script_translations(
                'reviewmanagementtool-admin',
                'reviewmanagementtool',
                RMT_DIR . 'languages'
            );
        }
    }
} );

/**
 * -----------------------------------------------------------------------------
 * Frontend: stylesheet (shared)
 * -----------------------------------------------------------------------------
 */
add_action( 'wp_enqueue_scripts', function () {
    $style_path = RMT_DIR . 'build/style-index.css';
    if ( file_exists( $style_path ) ) {
        wp_enqueue_style(
            'reviewmanagementtool-frontend',
            RMT_URL . 'build/style-index.css',
            array(),
            filemtime( $style_path )
        );
    }
} );

/**
 * -----------------------------------------------------------------------------
 * Shortcode: [rmt_widget id="..."]
 * -----------------------------------------------------------------------------
 * Renders the container and ENQUEUES the remote CDN script (no inline <script>).
 */
add_shortcode( 'rmt_widget', function ( $atts ) {
    $opts = get_option( 'reviewmanagementtool_options', rmt_default_options() );

    $atts = shortcode_atts(
        array(
            'id' => '', // Widget ID (required if no default is set)
        ),
        $atts,
        'rmt_widget'
    );

    $widget_id = $atts['id'];

    if ( '' === $widget_id ) {
        // If you ever decide to support a default ID again, you can restore the line below:
        // $widget_id = isset( $opts['default_widget_id'] ) ? $opts['default_widget_id'] : '';
    }

    if ( '' === $widget_id ) {
        return esc_html__( 'No widget ID provided.', 'reviewmanagementtool' );
    }

    $widget_id = preg_replace( '/[^a-zA-Z0-9\-\_]/', '', $widget_id );

    $cdn_base = isset( $opts['cdn_base_url'] ) ? $opts['cdn_base_url'] : '';
    if ( '' === $cdn_base ) {
        $cdn_base = 'https://cdn.reviewmanagementtool.com/widget-static';
    }
    $cdn_base = rtrim( $cdn_base, '/' );

    // Example: testimonial-widget-{WIDGET_ID}.js
    $src    = $cdn_base . '/testimonial-widget-' . rawurlencode( $widget_id ) . '.js';
    $handle = 'rmt-widget-' . md5( $widget_id . '|' . $src );

    // Register & enqueue the remote widget script (include version for cache-busting).
    wp_register_script( $handle, esc_url_raw( $src ), array(), RMT_VERSION, true );
    wp_enqueue_script( $handle );

    // Return only the container. No inline <script> tags (passes Plugin Check).
    $html  = '<div class="rmt-testimonial-widget" data-rmt-testimonial-widget="widget-' . esc_attr( $widget_id ) . '">';
    $html .= esc_html__( 'Loading testimonials…', 'reviewmanagementtool' );
    $html .= '</div>';

    return $html;
} );

/**
 * -----------------------------------------------------------------------------
 * Uninstall cleanup (optional)
 * -----------------------------------------------------------------------------
 * To enable, create uninstall.php or use register_uninstall_hook.
 */
// register_uninstall_hook( __FILE__, function () {
//     delete_option( 'reviewmanagementtool_options' );
// } );
