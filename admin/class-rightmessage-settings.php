<?php
/**
 * RightMessage Settings class
 *
 * @package RightMessage
 * @author RightMessage
 */


class RightMessage_Settings {

	public $sections = array();
	public $settings_key  = WP_RightMessage::SETTINGS_PAGE_SLUG;


	public function __construct() {
		$general_options = get_option( $this->settings_key );
		$account_id      = $general_options && array_key_exists( 'account_id', $general_options ) ? $general_options['account_id'] : null;

		add_action( 'admin_head', array( $this, 'add_settings_css' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_sections' ) );
	}

	public function add_settings_css() {
		//wp_enqueue_style( 'rightmessage', plugins_url( 'style.css', __FILE__ ));
	}

	public function add_settings_page() {
		add_options_page(
			__( 'RightMessage', 'rightmessage' ),
			__( 'RightMessage', 'rightmessage' ),
			'manage_options',
			$this->settings_key,
			array( $this, 'display_settings_page' )
		);
	}

	public function display_settings_page() {
		if ( isset( $_GET['tab'] ) ) {
			$active_section = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
		} else {
			$active_section = $this->sections[0]->name;
		}

		?>
        <div class="wrap rightmessage-settings-wrap">
			<?php
			if ( count( $this->sections ) > 1 ) {
				$this->display_section_nav( $active_section );
			} else {
				?>
                <h2><?php esc_html_e( 'RightMessage Settings', 'rightmessage' ); ?></h2>
				<?php
			}
			?>

            <form method="post" action="options.php">
				<?php
				foreach ( $this->sections as $section ) :
					if ( $active_section === $section->name ) :
						$section->render();
					endif;
				endforeach;

				// Check for Multibyte string PHP extension.
				if ( ! extension_loaded( 'mbstring' ) ) {
					?><p><strong><?php
						echo  sprintf( __( 'Note: Your server does not support the %s functions - this is required for better character encoding. Please contact your webhost to have it installed.', 'woocommerce' ), '<a href="https://php.net/manual/en/mbstring.installation.php">mbstring</a>' ) . '</mark>';
						?></strong></p><?php
				}
				?>
            </form>
        </div>
		<?php
	}


	public function display_section_nav( $active_section ) {
		?>
        <h1><?php esc_html_e( 'RightMessage', 'rightmessage' ); ?></h1>
        <h2 class="nav-tab-wrapper">
			<?php
			foreach ( $this->sections as $section ) :
				printf(
					'<a href="?page=%s&tab=%s" class="nav-tab right %s">%s</a>',
					esc_html( $this->settings_key ),
					esc_html( $section->name ),
					$active_section === $section->name ? 'nav-tab-active' : '',
					esc_html( $section->tab_text )
				);
			endforeach;
			?>
        </h2>
		<?php
	}

	public function register_section( $section ) {
		$section_instance = new $section();

		if ( $section_instance->is_registerable ) {
			array_push( $this->sections, $section_instance );
		}
	}

	public function register_sections() {
		$this->register_section( 'RightMessage_Settings_General' );
	}

}
