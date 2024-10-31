<?php
/**
 * RightMessage Settings class
 *
 * @package RightMessage
 * @author RightMessage
 */


abstract class RightMessage_Settings_Base {


	public $is_registerable = true;
	public $name;
	public $title;
	public $tab_text;
	public $settings_key;
	public $options;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options  = get_option( $this->settings_key );
		if ( empty( $this->tab_text ) ) {
			$this->tab_text = $this->title;
		}

		$this->register_section();
	}

	public function register_section() {
		if ( false === get_option( $this->settings_key ) ) {
			add_option( $this->settings_key );
		}

		add_settings_section(
			$this->name,
			$this->title,
			array( $this, 'print_section_info' ),
			$this->settings_key
		);

		$this->register_fields();

		register_setting(
			$this->settings_key,
			$this->settings_key,
			array( $this, 'sanitize_settings' )
		);
	}


	public function render() {
		do_settings_sections( $this->settings_key );
		settings_fields( $this->settings_key );
		submit_button();
	}


	abstract public function register_fields();
	abstract public function print_section_info();
}
