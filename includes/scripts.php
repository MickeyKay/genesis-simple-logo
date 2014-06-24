<?php
/**
 * Load JavaScript and CSS
 *
 * @package     Genesis Simple Logo
 * @subpackage  Genesis
 * @copyright   Copyright (c) 2013, Flagship
 * @license     GPL-2.0+
 * @since       1.0.0
 */

// Exit if accessed directly
defined( 'WPINC' ) or die;

add_action( 'customize_controls_enqueue_scripts', 'genlogo_enqueue_customizer_assets' );
/**
 * Add customimzer JS to live-refresh controls when logos are added or removed.
 *
 * @see add_action('customize_preview_init',$func)
 */
function genlogo_enqueue_customizer_assets() {
	wp_enqueue_script(
		'genlogo-customizer',
		GENLOGO_URL . 'assets/js/genlogo-customizer.js',
		array( 'customize-loader', 'jquery' )
	);
}

add_action( 'wp_head', 'genlogo_head_css' );
/**
 * Output custom CSS to control the look of the genesis simple logo in the <head>.
 *
 * @return null if we have no custom styles.
 * @since  1.0.0
 */
function genlogo_head_css() {
	// Do nothing if the user hasn't added a logo.
	if ( ! genlogo_has_logo() ) {
		return;
	}
	$styles = genlogo_get_data();
	$css    = '';
	$height = ( $styles['height'] ? 'height:' . intval( $styles['height'] ) . 'px;' : '' );
	$width  = ( $styles['width'] ? 'width:' . intval( $styles['width'] ) . 'px;' : '' );
	ob_start();
	?>
	.header-image .title-area,
	.header-image .site-title,
	.header-image .site-title > a {
		<?php echo $height; ?>
		max-width: 100%;
	}

	.header-image .title-area {
		<?php echo $width; ?>
	}

	.header-image.header-full-width .title-area,
	.header-image .site-title,
	.header-image .site-title > a {
		width: 100%;
	}

	.header-image .site-header,
	.header-image .site-header .wrap,
	.header-image .title-area,
	.header-image .site-title,
	.header-image .site-title > a {
		background-image: none;
	}

	.header-image .title-area,
	.header-image .site-title,
	.header-image .site-title > a {
		background: transparent;
		display: block;
		line-height: 0;
		margin: 0;
		min-height: inherit;
		overflow: hidden;
		padding: 0;
		position: relative;
		text-indent: -9999px;
	}

	.header-image .site-title > a {
		background-image: url('<?php echo esc_url( $styles['logo'] ); ?>');
		background-repeat: no-repeat;
		background-position: center;
		-webkit-background-size: contain;
		-moz-background-size: contain;
		background-size: contain;
	}

	<?php if ( intval( $styles['width'] ) > 300 ) { ?>
		@media only screen and (max-width: 1139px) {
			.header-image .title-area {
				width: 300px;
			}
		}
	<?php } ?>
	@media only screen and (max-width: 1023px) {
		.header-image .title-area {
			float: none;
			margin: 0 auto;
			max-width: 300px
		}
		.header-image .site-title > a {
			float: none;
			max-width: 100%;
			width: 100%;
		}
	}
	<?php

	$css = ob_get_clean();

	//* Minify the CSS a bit.
	$css = str_replace( "\t", '', $css );
	$css = str_replace( array( "\n", "\r" ), ' ', $css );

	//* Echo the CSS.
	echo '<style type="text/css" media="screen">' . $css . '</style>';
}
