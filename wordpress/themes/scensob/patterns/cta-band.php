<?php
/**
 * Title: Call to action band
 * Slug: scensob/cta-band
 * Categories: scensob, call-to-action
 * Description: Tinted full-width band with a heading and a single action. Picks up the division accent automatically.
 *
 * @package scensob
 */

?>
<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}},"border":{"radius":"14px"}},"backgroundColor":"accent-soft","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-accent-soft-background-color has-background" style="border-radius:14px;margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50);padding:var(--wp--preset--spacing--60) var(--wp--preset--spacing--50)">
	<!-- wp:heading {"textAlign":"center","level":2} -->
	<h2 class="wp-block-heading has-text-align-center">[ Closing prompt ]</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"muted","style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|40"}}}} -->
	<p class="has-text-align-center has-muted-color has-text-color" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--40)">[ Placeholder — one line inviting an enquiry, wording to be confirmed. ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Get in touch</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
