<?php
/**
 * Provision the SCENSOB Transport & Delivery division site.
 *
 * Page set follows the brief's core pages for this division: service catalog,
 * booking / quote, and trust & compliance signals.
 *
 * @package scensob
 */

require_once '/wordpress/wp-load.php';
require_once __DIR__ . '/common.php';

// Fleet, warehouse and delivery photography only.
scensob_import_photos(
	'/photos',
	array(
		'couriers-loading-van',
		'courier-handing-parcel',
		'courier-checking-parcel',
		'driver-in-van-bw',
		'warehouse-aisle',
		'pharma-vaccine-carton',
	)
);

scensob_apply_variation( 'transport-delivery' );

scensob_setup_site(
	'SCENSOB',
	// Stored as plain text; the logo pattern escapes it on output.
	'Transport & Delivery',
	array(
		'Home'     => scensob_pattern( 'hero' ) . "\n"
			. scensob_pattern( 'services-grid' ) . "\n"
			. scensob_pattern( 'trust-signals' ) . "\n"
			. scensob_pattern( 'cta-band' ),
		'Services' => scensob_pattern( 'services-grid' ) . "\n"
			. scensob_pattern( 'cta-band' ),
		'Catalog'  => scensob_pattern( 'products-grid' ) . "\n"
			. scensob_pattern( 'cta-band' ),
		'About'    => scensob_pattern( 'about-panel' ) . "\n"
			. scensob_pattern( 'trust-signals' ),
		'Contact'  => scensob_pattern( 'contact-panel' ),
	)
);
