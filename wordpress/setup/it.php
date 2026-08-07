<?php
/**
 * Provision the SCENSOB IT division site.
 *
 * @package scensob
 */

require_once '/wordpress/wp-load.php';
require_once __DIR__ . '/common.php';

// Only IT-relevant photography — the courier and warehouse shots belong to
// Transport & Delivery.
scensob_import_photos( '/photos', array( 'server-room', 'office-team', 'office-meeting' ) );
scensob_apply_variation( 'it' );

scensob_setup_site(
	'SCENSOB',
	'IT Division',
	array(
		'Home'     => scensob_pattern( 'hero' ) . "\n" . scensob_pattern( 'services-grid' ) . "\n" . scensob_pattern( 'cta-band' ),
		'Services' => scensob_pattern( 'services-grid' ) . "\n" . scensob_pattern( 'cta-band' ),
		'Products' => scensob_pattern( 'products-grid' ),
		'Gallery'  => scensob_pattern( 'gallery-grid' ),
		'Contact'  => scensob_pattern( 'contact-panel' ),
	)
);
