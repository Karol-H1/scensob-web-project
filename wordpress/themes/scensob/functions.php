<?php
/**
 * SCENSOB block theme.
 *
 * Almost all presentation is declared in theme.json. This file only wires up
 * theme supports, the stylesheet, and the shared logo mark.
 *
 * @package scensob
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme supports.
 */
function scensob_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
}
add_action( 'after_setup_theme', 'scensob_setup' );

/**
 * Pattern category, so SCENSOB patterns group together in the inserter.
 */
function scensob_register_pattern_category() {
	register_block_pattern_category(
		'scensob',
		array( 'label' => __( 'SCENSOB', 'scensob' ) )
	);
}
add_action( 'init', 'scensob_register_pattern_category' );

/**
 * Front-end stylesheet.
 */
function scensob_enqueue_styles() {
	wp_enqueue_style(
		'scensob-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'scensob_enqueue_styles' );

/**
 * The division name shown under the logo ("Global", "IT", "Transport & Delivery").
 *
 * Editors set this per site in Settings > General > Tagline, so each division
 * can label its own site without touching code.
 *
 * @return string
 */
function scensob_division_label() {
	return (string) apply_filters( 'scensob_division_label', get_bloginfo( 'description', 'display' ) );
}

/**
 * Images from this site's media library, newest first.
 *
 * Patterns use this instead of naming files, so each division shows its own
 * photography without the pattern needing to know which site it's on.
 *
 * @param int $limit Maximum number of images.
 * @return array List of array( url, alt ) pairs.
 */
function scensob_photos( $limit = 6 ) {
	$attachments = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'numberposts'    => $limit,
			'orderby'        => 'date',
			'order'          => 'ASC',
		)
	);

	$photos = array();

	foreach ( $attachments as $attachment ) {
		$url = wp_get_attachment_image_url( $attachment->ID, 'large' );

		if ( ! $url ) {
			continue;
		}

		$alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );

		$photos[] = array(
			'url' => $url,
			'alt' => $alt ? $alt : $attachment->post_title,
		);
	}

	return $photos;
}

/**
 * The SCENSOB logo mark, read from assets/logo.svg.
 *
 * Inlined rather than uploaded to the media library so it inherits page colours
 * and stays sharp, and so the site never needs SVG uploads enabled.
 *
 * @return string Raw SVG markup, or an empty string if the file is missing.
 */
function scensob_logo_svg() {
	static $svg = null;

	if ( null === $svg ) {
		$path = get_theme_file_path( 'assets/logo.svg' );
		$svg  = is_readable( $path ) ? (string) file_get_contents( $path ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	return $svg;
}
