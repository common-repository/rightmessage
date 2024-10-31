<?php
/**
 * RightMessage General Settings class
 *
 * @package RightMessage
 * @author RightMessage
 */


class RightMessage_Settings_General extends RightMessage_Settings_Base {

	public function __construct() {
		$this->settings_key = WP_RightMessage::SETTINGS_PAGE_SLUG;
		$this->name         = 'general';
		$this->title        = __( 'General', 'rightmessage' );
		$this->tab_text     = __( 'General', 'rightmessage' );

		parent::__construct();
	}

	/**
	 * Register and add settings
	 */
	public function register_fields() {
		add_settings_field(
			'account_id',
			'Account ID',
			array( $this, 'account_id_callback' ),
			$this->settings_key,
			$this->name
		);

		add_settings_field(
			'default_area',
			'Default Embedded Widget',
			array( $this, 'default_area_callback'),
			$this->settings_key,
			$this->name
		);

	}

	/**
	 * Prints help info for this section
	 */
	public function print_section_info() {
		?>
        <p><?php esc_html_e( "Enter your account ID below and we'll include your RightMessage tracking script across your entire site.", 'rightmessage' ); ?></p>
				<p>There are also two shortcodes that you can use:</p>
				<ul>
					<li><code>[rm_area name="end-of-blog"]</code>: This will place one of our embedded widgets wherever you include this shortcode. Be sure to have the <code>name</code> match the name you set in your widget's configuration.</li>
					<li><code>[rm_trigger widget="wdg_*"]</code>: This will create a link that will trigger a widget of your choice. Set the <code>widget</code> attribute to the Widget ID you want triggered.</p>
				</ul>
		<?php
	}

	public function account_id_callback() {
		$html = sprintf(
			'<input type="text" class="regular-text code" id="account_id" name="%s[account_id]" value="%s" />',
			$this->settings_key,
			isset( $this->options['account_id'] ) ? esc_attr( $this->options['account_id'] ) : ''
		);

		$html .= '<p class="description">An account ID can be found by going to the dashboard of one of your RightMessage accounts. It\'s the number between <code>...rightmessage.com/</code> and <code>/dashboard/</code></p>';

		echo $html;
	}

	public function default_area_callback() {
		$html = sprintf(
			'<input type="text" class="regular-text code" id="default_area" name="%s[default_area]" value="%s" />',
			$this->settings_key,
			isset( $this->options['default_area'] ) ? esc_attr( $this->options['default_area'] ) : ''
		);

		$html .= '<p class="description">If set, the above embedded widget will be included at the bottom of every post or page (in single view only) across your site. Take the name in your embedded widget\'s "Internal Widget Id" field and set it above.</p>';

		echo $html;
	}

	public function sanitize_settings( $settings ) {

		return shortcode_atts( array(
			'account_id'      => '',
			'default_area'	  => '',
		), $settings );
	}
}
