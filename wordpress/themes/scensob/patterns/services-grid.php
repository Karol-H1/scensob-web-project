<?php
/**
 * Title: Services grid
 * Slug: scensob/services-grid
 * Categories: scensob, featured
 * Description: Three-across grid of service cards. Shared by every division — duplicate a card to add a service.
 *
 * @package scensob
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"className":"scensob-eyebrow"} -->
	<p class="scensob-eyebrow">[ Services — list to be confirmed with the division lead ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":2,"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|50"}}}} -->
	<h2 class="wp-block-heading" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--50)">What we do</h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"className":"scensob-card-grid","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-columns scensob-card-grid">
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"scensob-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"},"border":{"color":"var:preset|color|line","width":"1px","radius":"12px"}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group scensob-card has-border-color has-surface-background-color has-background" style="border-color:var(--wp--preset--color--line);border-width:1px;border-radius:12px;padding:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading">[ Service <?php echo (int) $i; ?> ]</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
				<p class="has-muted-color has-text-color has-small-font-size">[ Placeholder — describe the service in one or two sentences once the offering is confirmed. ]</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"className":"scensob-card__link","fontSize":"small","style":{"typography":{"fontWeight":"700"}}} -->
				<p class="scensob-card__link has-small-font-size" style="font-weight:700"><a href="#">Learn more →</a></p>
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
