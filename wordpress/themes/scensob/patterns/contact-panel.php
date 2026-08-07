<?php
/**
 * Title: Contact panel
 * Slug: scensob/contact-panel
 * Categories: scensob, contact
 * Description: Contact details beside an enquiry form slot. Drop the Contact Form 7 shortcode into the form column once the plugin is installed.
 *
 * @package scensob
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"className":"scensob-eyebrow"} -->
	<p class="scensob-eyebrow">[ Contact — details to be confirmed ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"},"margin":{"top":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:column {"width":"40%"} -->
		<div class="wp-block-column" style="flex-basis:40%">
			<!-- wp:heading {"level":2} -->
			<h2 class="wp-block-heading">Get in touch</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"muted"} -->
			<p class="has-muted-color has-text-color">[ Placeholder — opening line to be confirmed. ]</p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40)">
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size"><strong>Email</strong><br>[ address to be confirmed ]</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size"><strong>Phone</strong><br>[ number to be confirmed ]</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size"><strong>Address</strong><br>[ registered address to be confirmed ]</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"color":"var:preset|color|line","width":"1px","radius":"12px"}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group has-border-color has-surface-background-color has-background" style="border-color:var(--wp--preset--color--line);border-width:1px;border-radius:12px;padding:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading">Send an enquiry</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
				<p class="has-muted-color has-text-color has-small-font-size">[ Form slot — replace this paragraph with the Contact Form 7 shortcode once the plugin is installed and the recipient address is confirmed. ]</p>
				<!-- /wp:paragraph -->
				<!-- wp:html -->
				<div class="scensob-form-skeleton" aria-hidden="true">
					<span class="scensob-form-skeleton__field"></span>
					<span class="scensob-form-skeleton__field"></span>
					<span class="scensob-form-skeleton__field is-tall"></span>
					<span class="scensob-form-skeleton__button"></span>
				</div>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
