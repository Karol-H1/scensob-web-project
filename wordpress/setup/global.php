<?php
/**
 * Provision the SCENSOB Global hub site.
 *
 * Global uses the theme's default palette, so no style variation is applied.
 *
 * @package scensob
 */

require_once '/wordpress/wp-load.php';
require_once __DIR__ . '/common.php';

// Neutral corporate photography only; divisional imagery lives on the
// divisional sites.
scensob_import_photos( '/photos', array( 'office-team', 'office-meeting' ) );

scensob_setup_site(
	'SCENSOB',
	'Global',
	array(
		'Home'      => scensob_pattern( 'hero' ) . "\n" . scensob_pattern( 'divisions-grid' ),
		'Divisions' => scensob_pattern( 'divisions-grid' ),
		'About'     => scensob_pattern( 'cta-band' ),
		'Gallery'   => scensob_pattern( 'gallery-grid' ),
		'Contact'   => scensob_pattern( 'contact-panel' ),
	)
);
