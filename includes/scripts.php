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

function genlogo_formatted_css( $styles ) {
	if ( ! $styles ) {
		return;
	}
	$css = array(
		'height'   => ( $styles['height'] ? 'height:' . intval( $styles['height'] ) . 'px;' : '' ),
		'width'    => ( $styles['width'] ? 'width:' . intval( $styles['width'] ) . 'px;' : '' ),
		'margin_v' => ( $styles['margin_vertical'] ? intval( $styles['margin_vertical'] ) . 'px' : '0' ),
		'margin_h' => ( $styles['margin_horizontal'] ? intval( $styles['margin_horizontal'] ) . 'px' : '0' ),
	);
	return $css;
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
	$formatted = genlogo_formatted_css( $styles );

	$css = genlogo_html5_css( $styles, $formatted );
	// Use xHTML styles if Genesis HTML5 is not enabled.
	if ( ! genesis_html5() ) {
		$css = genlogo_xhtml_css( $styles, $formatted );
	}

	//* Minify the CSS a bit.
	$css = str_replace( "\t", '', $css );
	$css = str_replace( array( "\n", "\r" ), ' ', $css );

	//* Echo the CSS.
	echo '<style type="text/css" media="screen">' . $css . '</style>';
}


function genlogo_html5_css( $styles, $formatted ) {
	if ( ! $styles ) {
		return;
	}
	ob_start();
	?>
	.header-image .site-header .title-area,
	.header-image .site-header .site-title,
	.header-image .site-header .site-title > a {
		<?php echo $formatted['height']; ?>
		max-width: 100%;
	}

	.header-image .site-header .site-title,
	.header-image .site-header .site-title > a {
		width: 100%;
	}

	.header-image .site-header,
	.header-image .site-header .wrap,
	.header-image .site-header .title-area,
	.header-image .site-header .site-title,
	.header-image .site-header .site-title > a {
		background-image: none;
	}

	.header-image.header-full-width .title-area,
	.header-image .site-header .title-area,
	.header-image .site-header .site-title,
	.header-image .site-header .site-title > a {
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

	.header-image.header-full-width .site-header .title-area,
	.header-image .site-header .title-area {
		<?php if ( 'always' === $styles['center']  ) { ?>
			float: none;
			margin: <?php echo $formatted['margin_v']; ?> auto;
		<?php } else { ?>
			margin: <?php echo $formatted['margin_v'] . ' ' . $formatted['margin_h']; ?>;
		<?php } ?>
		<?php echo $formatted['width']; ?>
	}

	.header-image .site-header .site-title > a {
		background-image: url('<?php echo esc_url( $styles['logo'] ); ?>');
		background-repeat: no-repeat;
		background-position: center;
		-webkit-background-size: contain;
		-moz-background-size: contain;
		background-size: contain;
	}

	<?php if ( current_theme_supports( 'genesis-responsive-viewport' ) ) { ?>

		<?php if ( intval( $styles['width'] ) > 300 ) { ?>
			@media only screen and (max-width: 1139px) {
				.header-image.header-full-width .site-header .title-area,
				.header-image .site-header .title-area {
					width: 300px;
					max-width: 300px
				}
			}
		<?php } ?>
		<?php if ( 'mobile' === $styles['center']  ) { ?>
			@media only screen and (max-width: 1023px) {
				.header-image.header-full-width .site-header .title-area,
				.header-image .site-header .title-area {
					float: none;
					margin: <?php echo $formatted['margin_v']; ?> auto;
				}

				.header-image .site-header .site-title > a {
					float: none;
					max-width: 100%;
					width: 100%;
				}
			}
		<?php } ?>

	<?php

	} // End Mobile Viewport check.

	$css = ob_get_clean();
	return $css;
}

function genlogo_xhtml_css( $styles, $formatted ) {
	if ( ! $styles ) {
		return;
	}
	$css    = '';
	ob_start();
	?>
	.header-image #header #title-area,
	.header-image #header #title,
	.header-image #header #title > a {
		<?php echo $formatted['height']; ?>
		max-width: 100%;
	}

	.header-image #header #title,
	.header-image #header #title > a {
		width: 100%;
	}

	.header-image #header,
	.header-image #header .wrap,
	.header-image #header #title-area,
	.header-image #header #title,
	.header-image #header #title > a {
		background-image: none;
	}

	.header-image.header-full-width #title-area,
	.header-image #header #title-area,
	.header-image #header #title,
	.header-image #header #title > a {
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

	.header-image.header-full-width #header #title-area,
	.header-image #header #title-area {
		<?php if ( 'always' === $styles['center']  ) { ?>
			float: none;
			margin: <?php echo $formatted['margin_v']; ?> auto;
		<?php } else { ?>
			margin: <?php echo $formatted['margin_v'] . ' ' . $formatted['margin_h']; ?>;
		<?php } ?>
		<?php echo $formatted['width']; ?>
	}

	.header-image #header #title > a {
		background-image: url('<?php echo esc_url( $styles['logo'] ); ?>');
		background-repeat: no-repeat;
		background-position: center;
		-webkit-background-size: contain;
		-moz-background-size: contain;
		background-size: contain;
	}

	<?php if ( current_theme_supports( 'genesis-responsive-viewport' ) ) { ?>

		<?php if ( intval( $styles['width'] ) > 300 ) { ?>
			@media only screen and (max-width: 1139px) {
				.header-image.header-full-width .site-header .title-area,
				.header-image .site-header .title-area {
					width: 300px;
					max-width: 300px
				}
			}
		<?php } ?>
		<?php if ( 'mobile' === $styles['center']  ) { ?>
			@media only screen and (max-width: 1023px) {
				.header-image.header-full-width #header #title-area,
				.header-image #header #title-area {
					float: none;
					margin: <?php echo $formatted['margin_v']; ?> auto;
				}

				.header-image #header #title > a {
					float: none;
					max-width: 100%;
					width: 100%;
				}
			}
		<?php } ?>

	<?php

	} // End Mobile Viewport check.

	$css = ob_get_clean();
	return $css;
}
