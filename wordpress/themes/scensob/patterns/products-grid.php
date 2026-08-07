<?php
/**
 * Title: Products grid
 * Slug: scensob/products-grid
 * Categories: scensob, featured
 * Description: Image-led cards for products or equipment. Duplicate a card per item.
 *
 * @package scensob
 */

$scensob_photos = scensob_photos( 3 );
$scensob_slots  = 3;
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"className":"scensob-eyebrow"} -->
	<p class="scensob-eyebrow">[ Products — catalogue to be confirmed with the division lead ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":2,"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|50"}}}} -->
	<h2 class="wp-block-heading" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--50)">What we supply</h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"className":"scensob-card-grid","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-columns scensob-card-grid">
		<?php for ( $scensob_i = 0; $scensob_i < $scensob_slots; $scensob_i++ ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"scensob-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|20"},"border":{"color":"var:preset|color|line","width":"1px","radius":"12px"}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group scensob-card has-border-color has-surface-background-color has-background" style="border-color:var(--wp--preset--color--line);border-width:1px;border-radius:12px;padding:var(--wp--preset--spacing--30) var(--wp--preset--spacing--30) var(--wp--preset--spacing--40)">
				<!-- wp:html -->
				<?php if ( isset( $scensob_photos[ $scensob_i ] ) ) : ?>
					<figure class="scensob-media">
						<img src="<?php echo esc_url( $scensob_photos[ $scensob_i ]['url'] ); ?>" alt="<?php echo esc_attr( $scensob_photos[ $scensob_i ]['alt'] ); ?>" loading="lazy" decoding="async" />
					</figure>
				<?php else : ?>
					<div class="scensob-media-placeholder"><span>[ Image ]</span></div>
				<?php endif; ?>
				<!-- /wp:html -->
				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading">[ Item <?php echo (int) $scensob_i + 1; ?> ]</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
				<p class="has-muted-color has-text-color has-small-font-size">[ Placeholder — item description and any specification to be confirmed. ]</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"className":"scensob-card__link","fontSize":"small","style":{"typography":{"fontWeight":"700"}}} -->
				<p class="scensob-card__link has-small-font-size" style="font-weight:700"><a href="#">Enquire →</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<?php endfor; ?>
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
