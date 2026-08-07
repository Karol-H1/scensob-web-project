<?php
/**
 * Shared site-provisioning helpers used by the Playground blueprints.
 *
 * These run once against a fresh WordPress to stand a division site up:
 * import photos, apply the division's style variation, and create the pages.
 * Nothing here is loaded by the theme at runtime.
 *
 * @package scensob
 */

/**
 * Copy photos from a mounted directory into the media library.
 *
 * Each division imports only its own photography — a warehouse shot has no
 * business on the IT site — so pass the slugs that belong to this site.
 *
 * @param string $dir  Directory of .jpg files.
 * @param array  $only Slugs to import. Empty imports everything in the directory.
 * @return int Number of images imported.
 */
function scensob_import_photos( $dir, $only = array() ) {
	if ( ! is_dir( $dir ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$count = 0;

	foreach ( (array) glob( $dir . '/*.jpg' ) as $path ) {
		$name = basename( $path );
		$slug = sanitize_title( pathinfo( $name, PATHINFO_FILENAME ) );

		if ( ! empty( $only ) && ! in_array( $slug, $only, true ) ) {
			continue;
		}

		if ( get_page_by_path( $slug, OBJECT, 'attachment' ) ) {
			continue;
		}

		$bits = wp_upload_bits( $name, null, file_get_contents( $path ) );

		if ( ! empty( $bits['error'] ) ) {
			continue;
		}

		$filetype = wp_check_filetype( $bits['file'] );

		$id = wp_insert_attachment(
			array(
				'post_mime_type' => $filetype['type'],
				'post_title'     => ucwords( str_replace( '-', ' ', $slug ) ),
				'post_name'      => $slug,
				'post_status'    => 'inherit',
			),
			$bits['file']
		);

		if ( ! $id ) {
			continue;
		}

		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $bits['file'] ) );
		$count++;
	}

	return $count;
}

/**
 * Apply one of the theme's style variations as the site's global styles.
 *
 * This is what makes a division site take its own accent colour without
 * anyone opening the Site Editor.
 *
 * The variation file is read straight off disk rather than through
 * WP_Theme_JSON_Resolver::get_style_variations(), because that method returns
 * the palette already keyed by origin ("palette" => array("theme" => ...)).
 * User global styles need the flat shape the file itself uses; storing the
 * origin-keyed version silently produces no colours at all.
 *
 * @param string $slug Variation file name without .json, e.g. 'it'.
 * @return bool True when the variation was found and applied.
 */
function scensob_apply_variation( $slug ) {
	if ( ! class_exists( 'WP_Theme_JSON_Resolver' ) ) {
		return false;
	}

	kses_remove_filters();

	$path = get_theme_file_path( 'styles/' . $slug . '.json' );

	if ( ! is_readable( $path ) ) {
		return false;
	}

	$match = json_decode( file_get_contents( $path ), true );

	if ( ! is_array( $match ) ) {
		return false;
	}

	unset( $match['$schema'] );

	$post = WP_Theme_JSON_Resolver::get_user_data_from_wp_global_styles( wp_get_theme(), true );
	$id   = is_array( $post ) ? $post['ID'] : $post->ID;

	if ( ! $id ) {
		return false;
	}

	unset( $match['title'] );

	// The variation files declare their own schema version; don't depend on a
	// core constant here, it isn't stable across WordPress releases.
	if ( empty( $match['version'] ) ) {
		$match['version'] = 3;
	}

	// Core ignores a global styles post whose JSON lacks this flag, falling back
	// to an empty config without warning.
	$match['isGlobalStylesUserThemeJSON'] = true;

	wp_update_post(
		array(
			'ID'           => $id,
			'post_content' => wp_json_encode( $match ),
		)
	);

	// Core links the global styles post to its theme through tax_input, which
	// wp_insert_post drops when there's no authenticated user — as here. Without
	// the term, WordPress never finds the post and the variation is ignored
	// even though it saved correctly.
	wp_set_object_terms( $id, get_stylesheet(), 'wp_theme' );

	// The resolver memoises user styles per request, so drop the cache or
	// anything rendered later in this request still uses the old palette.
	if ( method_exists( 'WP_Theme_JSON_Resolver', 'clean_cached_data' ) ) {
		WP_Theme_JSON_Resolver::clean_cached_data();
	}

	return true;
}

/**
 * Name the site, clear WordPress's demo content, and create the pages.
 *
 * @param string $name    Site title.
 * @param string $tagline Division label printed under the logo.
 * @param array  $pages   Page title => block markup.
 * @return array Page title => post ID.
 */
function scensob_setup_site( $name, $tagline, $pages ) {
	// Block markup is HTML comments, and KSES strips comments for requests with
	// no authenticated user. Without this, every page is saved empty.
	kses_remove_filters();

	update_option( 'blogname', $name );
	update_option( 'blogdescription', $tagline );
	update_option( 'permalink_structure', '/%postname%/' );

	foreach ( array( 'sample-page', 'privacy-policy' ) as $slug ) {
		$demo = get_page_by_path( $slug );
		if ( $demo ) {
			wp_delete_post( $demo->ID, true );
		}
	}

	$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
	if ( $hello ) {
		wp_delete_post( $hello->ID, true );
	}

	$ids   = array();
	$order = 1;

	foreach ( $pages as $title => $content ) {
		$slug = sanitize_title( $title );
		$data = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => $content,
			'menu_order'   => $order,
		);

		$existing = get_page_by_path( $slug );

		if ( $existing ) {
			$data['ID'] = $existing->ID;
			wp_update_post( $data );
			$ids[ $title ] = $existing->ID;
		} else {
			$ids[ $title ] = wp_insert_post( $data );
		}

		$order++;
	}

	update_option( 'show_on_front', 'page' );

	if ( isset( $ids['Home'] ) ) {
		update_option( 'page_on_front', $ids['Home'] );
	}

	return $ids;
}

/**
 * Shorthand for a block-markup reference to one of the theme's patterns.
 *
 * @param string $slug Pattern slug without the scensob/ prefix.
 * @return string
 */
function scensob_pattern( $slug ) {
	return '<!-- wp:pattern {"slug":"scensob/' . $slug . '"} /-->';
}
