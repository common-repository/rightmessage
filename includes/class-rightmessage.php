<?php
// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WP_RightMessage
 */
class WP_RightMessage {

	const SETTINGS_NAME = '_wp_rightmessage_settings';

	const SETTINGS_PAGE_SLUG = '_wp_rightmessage_settings';

	private static $settings_defaults = array(
		'account_id' => '',
	);

	public static function init() {
		self::add_actions();
		self::add_filters();
		self::register_shortcodes();
	}

	private static function add_actions() {
		add_action( 'wp_footer', array( __CLASS__, 'rm_tracking_code' ) );
		add_action( 'the_content', array( __CLASS__, 'add_vars' ));
		add_filter( 'plugin_action_links_' . RIGHTMESSAGE_PLUGIN_FILE, array( __CLASS__, 'add_settings_page_link' ) );
	}

	private static function add_filters() {
		if ( ! is_admin() ) {
			add_filter( 'the_content', array( __CLASS__, 'append_area' ) );
		}
	}

	private static function register_shortcodes() {
		add_shortcode( 'rm_area', array( __CLASS__, 'shortcode_area' ) );
		add_shortcode( 'rm_trigger', array( __CLASS__, 'shortcode_trigger' ) );
	}

	public static function shortcode_area( $attributes, $content = null ) {
		if (isset($attributes['name'])) {
			return '<div class="rm-area-'.$attributes['name'].'"></div>';
		}
	}

	public static function shortcode_trigger( $attributes, $content = null ) {
		if (isset($attributes['widget'])) {
			return '<a href="#" data-rm-show="' . esc_attr($attributes['widget']) . '">' . esc_html($content) . '</a>';
		}
	}

	public static function append_area( $content ) {

		if ( is_singular( array( 'post' ) ) || is_page() ) {

			$area_id = self::_get_settings( 'default_area' );
			if (isset($area_id)) {
				$content .= "[rm_area name='" . esc_attr($area_id) . "']";
			}

		}
		return $content;
	}

	public static function add_vars($content) {
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( is_page() ) {
			$rmpanda_cmsdata = array(
				'cms' => 'wordpress',
				'pageId' => get_the_ID(),
			);
		} else if ( is_singular( array( 'post' ) ) ) {
			$postId = get_the_ID();

			$rmpanda_cmsdata = array(
				'cms' => 'wordpress',
				'postId' => $postId,
				'taxonomyTerms' => array(),
			);

			$taxonomy_names = get_post_taxonomies($postId);

			foreach ($taxonomy_names as $taxonomy_name) {
				$term_ids = wp_get_post_terms($postId, $taxonomy_name, array('fields' => 'ids'));
				$rmpanda_cmsdata['taxonomyTerms'][$taxonomy_name] = $term_ids;
			}
		}

		if (isset($rmpanda_cmsdata)) {
			ob_start();
			include(RIGHTMESSAGE_PLUGIN_PATH . "/views/rm-variables.php");
			$included_content = ob_get_clean();
			add_action('wp_footer', function() use ($included_content) {
				echo $included_content;
			});
		}

		return $content;
	}

	public static function rm_tracking_code($obj) {
		if ( self::_get_settings( 'account_id' ) ) {
			$account_id = esc_js(self::_get_settings( 'account_id' ));
			include( RIGHTMESSAGE_PLUGIN_PATH . "/views/tracking-code.php" );
		} else {
			echo '<!-- RightMessage: Set your account ID to add the RightMessage tracking script -->';
		}

	}

	public static function add_settings_page_link( $links ) {
		$settings_link = sprintf( '<a href="%s">%s</a>', self::_get_settings_page_link(), __( 'Settings', 'rightmessage' ) );

		return array(
			'settings' => $settings_link,
		) + $links;
	}

	public static function _get_settings( $settings_key = null ) {
		$settings = get_option( self::SETTINGS_NAME, self::$settings_defaults );

		return is_null( $settings_key ) ? $settings : ( isset( $settings[ $settings_key ] ) ? $settings[ $settings_key ] : null);
	}

	private static function _extract_slugs( $term ) {
		return $term->slug;
	}

	private static function _extract_ids( $term ) {
		return $term->term_id;
	}

	private static function _get_settings_page_link( $query_args = array() ) {
		$query_args = array(
			'page' => self::SETTINGS_PAGE_SLUG,
			) + $query_args;

		return add_query_arg( $query_args, admin_url( 'options-general.php' ) );
	}

}
