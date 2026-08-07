<?php
/**
 * Title: Gallery grid
 * Slug: scensob/gallery-grid
 * Categories: scensob, gallery
 * Description: Image grid drawn from this site's media library. Shows placeholder boxes until photos are uploaded.
 *
 * @package scensob
 */

$scensob_photos = scensob_photos( 6 );
$scensob_slots  = max( 6, count( $scensob_photos ) );
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"className":"scensob-eyebrow"} -->
	<p class="scensob-eyebrow">[ Gallery — replace with the division's own photography ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":2,"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|50"}}}} -->
	<h2 class="wp-block-heading" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--50)">Gallery</h2>
	<!-- /wp:heading -->

	<!-- wp:html -->
	<div class="scensob-gallery">
		<?php for ( $scensob_i = 0; $scensob_i < $scensob_slots; $scensob_i++ ) : ?>
			<?php if ( isset( $scensob_photos[ $scensob_i ] ) ) : ?>
				<figure class="scensob-media">
					<img src="<?php echo esc_url( $scensob_photos[ $scensob_i ]['url'] ); ?>" alt="<?php echo esc_attr( $scensob_photos[ $scensob_i ]['alt'] ); ?>" loading="lazy" decoding="async" />
				</figure>
			<?php else : ?>
				<div class="scensob-media-placeholder"><span>[ Image ]</span></div>
			<?php endif; ?>
		<?php endfor; ?>
	</div>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
