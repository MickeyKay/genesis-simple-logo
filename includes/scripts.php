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

add_action( 'wp_head', 'genlogo_head_css' );
/**
 * Output custom CSS to control the look of the genesis simple logo in the <head>.
 *
 * @return null if we have no custom logo.
 * @since  1.0.0
 */
function genlogo_head_css() {
	// Do nothing if the user hasn't added a logo.
	if ( ! genlogo_has_logo() ) {
		return;
	}
	$styles    = genlogo_get_data();
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

/**
 * Format styles for display by setting fallbacks when the options haven't been set.
 *
 * @param  $styles An array of custom style settings.
 * @return $css    An array of formatted css rules.
 * @since  1.0.5
 */
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

/**
 * Set up styles for HTML5 themes and return them to be output in the head.
 *
 * @param  $styles       An array of custom style settings.
 * @param  $formatted    An array of formatted css rules.
 * @return $css          A string of HTML5 css rules.
 * @since  1.0.5
 */
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
		min-height: 0;
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

	@media only screen and (-webkit-min-device-pixel-ratio: 1.5),
		only screen and (-moz-min-device-pixel-ratio: 1.5),
		only screen and (-o-min-device-pixel-ratio: 3/2),
		only screen and (min-device-pixel-ratio: 1.5) {

		.header-image .site-header .wrap {
			background-image: url('<?php echo esc_url( $styles['logo'] ); ?>');
			background-size: contain;
		}
	}

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

	$css = ob_get_clean();
	return apply_filters( 'genlogo_html5_css', $css );
}

/**
 * Set up styles for xHTML themes and return them to be output in the head.
 *
 * @param  $styles       An array of custom style settings.
 * @param  $formatted    An array of formatted css rules.
 * @return $css          A string of xHTML css rules.
 * @since  1.0.5
 */
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

	.header-image #header #title-area,
	.header-image #header #title,
	.header-image #header #title > a {
		background-image: none;
	}

	.header-image.header-full-width #title-area,
	.header-image #header #title-area,
	.header-image #header #title,
	.header-image #header #title > a {
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

	$css = ob_get_clean();
	return apply_filters( 'genlogo_xhtml_css', $css );
}

add_filter( 'genlogo_html5_css', 'genlogo_centric_pro_css' );
/**
 * Add some additional CSS for the Centric Pro theme.
 *
 * @since   1.0.4
 * @uses    wp_get_theme()
 * @param   $css Current CSS output.
 * @return  $css Current CSS output with centric pro stiyles appended.
 */
function genlogo_centric_pro_css( $css ) {
	$theme_info = wp_get_theme();
	if ( ! in_array( $theme_info->Name, array( 'Centric Theme' ) ) ) {
		return $css;
	}
	ob_start();
	?>
	.site-header .wrap,
	.site-header.shrink .wrap {
		min-height: 0;
	}
	.header-image .site-header .site-title > a {
		background-size: contain !important;
		min-height: 0;
	}
	<?php
	$css .= ob_get_clean();
	return $css;
}
