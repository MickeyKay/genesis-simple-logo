<?php
/**
 * Customize Genesis Simple Logo Class
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly.
defined( 'WPINC' ) or die;

// Do nothing if the WP_Customize_Image_Control class doesn't exist.
if ( ! class_exists( 'WP_Customize_Image_Control' ) ) {
	return;
}

/**
 * Customize Genesis Simple Logo Class
 *
 * Extend WP_Customize_Image_Control allowing access to uploads made within
 * the same context.
 */
class Genesis_Simple_Logo_Image_Control extends WP_Customize_Image_Control {
	/**
	 * Constructor.
	 *
	 * @since 1.0.2
	 * @uses WP_Customize_Image_Control::__construct()
	 *
	 * @param WP_Customize_Manager $manager
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Search for images within the defined context
	 */
	public function tab_uploaded() {
		$simple_logos = get_posts( array(
		    'post_type'  => 'attachment',
		    'meta_key'   => '_wp_attachment_context',
		    'meta_value' => $this->context,
		    'orderby'    => 'post_date',
		    'nopaging'   => true,
		) );
		?>

		<div class="uploaded-target"></div>

		<?php
		if ( empty( $simple_logos ) ) {
		    return;
		}

		foreach ( (array) $simple_logos as $simple_logo ) {
		    $this->print_tab_image( esc_url_raw( $simple_logo->guid ) );
		}
	}
}
