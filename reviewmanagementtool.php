<?php
/*
Plugin Name: Review Management Tool Widget Embedder
Plugin URI: https://blog.reviewmanagementtool.com/2025/07/how-to-easily-add-testimonials-to-your.html
Description: Embed testimonial widgets from ReviewManagementTool.com using a shortcode and Gutenberg block.
Version: 1.1.2
Author: Review Management Tool Admin
Author URI: https://reviewmanagementtool.com/aboutUs
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: review-management-tool-widget-embedder
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Load textdomain */
add_action( 'init', function () {
	load_plugin_textdomain(
		'review-management-tool-widget-embedder',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
} );

/**
 * Register Gutenberg block (script only if /build exists).
 * NOTE: The block name stays 'rmt/embed' to match the compiled build/index.js.
 * If you later rebuild the block under a new name, update both JS and PHP.
 */
function revimato_register_block() {
	$block_js = plugin_dir_path( __FILE__ ) . 'build/index.js';
	$deps     = array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components' );

	if ( file_exists( $block_js ) ) {
		wp_register_script(
			'revimato-block',
			plugins_url( 'build/index.js', __FILE__ ),
			$deps,
			filemtime( $block_js ),
			true
		);

		register_block_type( 'rmt/embed', array(
			'editor_script'   => 'revimato-block',
			'render_callback' => 'revimato_render_block',
			'attributes'      => array(
				'widgetId' => array( 'type' => 'string', 'default' => '' ),
				'type'     => array( 'type' => 'string', 'default' => 'iframe' ), // iframe | js | static
			),
		) );
	}
}
add_action( 'init', 'revimato_register_block' );

/**
 * Core renderer used by both shortcode and block.
 * Supports:
 *   type="iframe" (default)
 *   type="js"     (shared dynamic loader)
 *   type="static" (widget-specific CDN JS)
 */
function revimato_render_embed( $atts ) {
	$atts = shortcode_atts( array(
		'widget_id' => '',
		'type'      => 'iframe',
	), $atts, 'rmtool' );

	$id   = sanitize_text_field( $atts['widget_id'] );
	$type = in_array( $atts['type'], array( 'iframe', 'js', 'static' ), true ) ? $atts['type'] : 'iframe';

	// Optional UUID check to keep IDs tidy.
	if ( ! empty( $id ) && ! preg_match( '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id ) ) {
		return '<!-- Review Management Tool: invalid widget_id format -->';
	}

	// Avoid any external calls unless a widget is actually placed.
	if ( empty( $id ) ) {
		if ( is_admin() ) {
			return '<div class="notice notice-info"><p>' .
				esc_html__( 'Review Management Tool: Please set a Widget ID to render the embed.', 'review-management-tool-widget-embedder' ) .
			'</p></div>';
		}
		if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			return '<!-- Review Management Tool: Add widget_id to shortcode/block to render. -->';
		}
		return '';
	}

	// Static JS: widget-specific file served from CDN
	if ( 'static' === $type ) {
		$handle = 'revimato-static-' . $id;
		$src    = 'https://cdn.reviewmanagementtool.com/widget-static/testimonial-widget-' . rawurlencode( $id ) . '.js';

		if ( ! wp_script_is( $handle, 'enqueued' ) ) {
			wp_enqueue_script( $handle, $src, array(), null, true );
			if ( function_exists( 'wp_script_add_data' ) ) {
				wp_script_add_data( $handle, 'defer', true );
			}
		}
		return '<div data-rmt-testimonial-widget="widget-' . esc_attr( $id ) . '"></div>';
	}

	// Dynamic JS: shared loader
	if ( 'js' === $type ) {
		if ( ! wp_script_is( 'revimato-embed', 'enqueued' ) ) {
			wp_enqueue_script(
				'revimato-embed',
				'https://reviewmanagementtool.com/widgets/embed.js',
				array(),
				'1.0.0',
				true
			);
			if ( function_exists( 'wp_script_add_data' ) ) {
				wp_script_add_data( 'revimato-embed', 'defer', true );
			}
		}
		return '<div class="rmt-widget" data-id="' . esc_attr( $id ) . '"></div>';
	}

	// Default: iframe (safest)
	$src = 'https://reviewmanagementtool.com/embed/widget/' . rawurlencode( $id );
	return '<iframe src="' . esc_url( $src ) . '" width="100%" height="400" frameborder="0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
}

/** Shortcode: [rmtool widget_id="..." type="iframe|js|static"] */
function revimato_register_shortcode( $atts ) {
	return revimato_render_embed( $atts );
}
add_shortcode( 'rmtool', 'revimato_register_shortcode' );

/** Block render proxy */
function revimato_render_block( $attributes ) {
	return revimato_render_embed( array(
		'widget_id' => isset( $attributes['widgetId'] ) ? $attributes['widgetId'] : '',
		'type'      => isset( $attributes['type'] ) ? $attributes['type'] : 'iframe',
	) );
}

/** Admin Help Page */
add_action( 'admin_menu', function () {
	add_options_page(
		__( 'Review Management Tool', 'review-management-tool-widget-embedder' ),
		__( 'Review Management Tool', 'review-management-tool-widget-embedder' ),
		'manage_options',
		'review-management-tool',
		'revimato_settings_page'
	);
} );

function revimato_settings_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Review Management Tool – Quick Start Guide', 'review-management-tool-widget-embedder' ); ?></h1>

		<h2><?php echo esc_html__( 'How to Use This Plugin (1-Minute Setup)', 'review-management-tool-widget-embedder' ); ?></h2>
		<ol style="margin-left:20px;list-style:decimal;">
			<li><a href="https://reviewmanagementtool.com" target="_blank" rel="noopener noreferrer">ReviewManagementTool.com</a></li>
			<li><?php echo esc_html__( 'Create your testimonials (text / video / audio) and build a widget', 'review-management-tool-widget-embedder' ); ?></li>
			<li><?php echo esc_html__( 'Copy the Widget ID from your dashboard', 'review-management-tool-widget-embedder' ); ?></li>
			<li><?php echo esc_html__( 'Return to WordPress and choose ONE embed method:', 'review-management-tool-widget-embedder' ); ?>
				<ul style="list-style:disc;margin-left:20px;">
					<li><code>[rmtool widget_id="YOUR_ID" type="iframe"]</code> – <?php echo esc_html__( 'Safest, works everywhere (default)', 'review-management-tool-widget-embedder' ); ?></li>
					<li><code>[rmtool widget_id="YOUR_ID" type="static"]</code> – <?php echo esc_html__( 'Fast, static JS file per widget (CDN)', 'review-management-tool-widget-embedder' ); ?></li>
					<li><code>[rmtool widget_id="YOUR_ID" type="js"]</code> – <?php echo esc_html__( 'Dynamic loader script (one shared JS)', 'review-management-tool-widget-embedder' ); ?></li>
				</ul>
			</li>
			<li><?php echo esc_html__( 'Or insert the “Review Management Widget” Gutenberg block and set Widget ID + Method in the sidebar', 'review-management-tool-widget-embedder' ); ?></li>
		</ol>

		<hr/>

		<p>
			<strong><?php echo esc_html__( 'Privacy note:', 'review-management-tool-widget-embedder' ); ?></strong>
			<?php echo esc_html__( 'This plugin only requests assets from reviewmanagementtool.com and cdn.reviewmanagementtool.com when you embed a widget on a page. No requests are made on new installs until you place a widget.', 'review-management-tool-widget-embedder' ); ?>
			<br />
			<a href="https://reviewmanagementtool.com/privacy-policy" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Privacy Policy', 'review-management-tool-widget-embedder' ); ?></a>
			|
			<a href="https://reviewmanagementtool.com/terms-and-conditions" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Terms & Conditions', 'review-management-tool-widget-embedder' ); ?></a>
		</p>

		<p>
			<strong><?php echo esc_html__( 'Need more help?', 'review-management-tool-widget-embedder' ); ?></strong>
			<a href="https://blog.reviewmanagementtool.com/2025/07/how-to-easily-add-testimonials-to-your.html" target="_blank" rel="noopener noreferrer">
				<?php echo esc_html__( 'Read the full tutorial with screenshots', 'review-management-tool-widget-embedder' ); ?>
			</a>
		</p>

		<p>
			<strong><?php echo esc_html__( 'Support:', 'review-management-tool-widget-embedder' ); ?></strong>
			<a href="mailto:hello@reviewmanagementtool.com">hello@reviewmanagementtool.com</a>
		</p>
	</div>
	<?php
}
