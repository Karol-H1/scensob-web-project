<?php
/**
 * Title: Hero
 * Slug: scensob/hero
 * Categories: scensob, banner
 * Description: Page-opening headline, supporting line, and two calls to action.
 *
 * @package scensob
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"className":"scensob-eyebrow"} -->
	<p class="scensob-eyebrow">[ Hero ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"className":"scensob-hero-title"} -->
	<h1 class="wp-block-heading scensob-hero-title">[ Headline ]</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"className":"scensob-lede","textColor":"muted","fontSize":"large","style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|50"}}}} -->
	<p class="scensob-lede has-muted-color has-text-color has-large-font-size" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--50)">[ Placeholder — positioning and opening copy to be confirmed with the division lead. ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"blockGap":"14px"}}} -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">See what we do</a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline","textColor":"contrast","style":{"border":{"color":"var:preset|color|line-strong","width":"1px"}}} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-contrast-color has-text-color has-border-color wp-element-button" style="border-color:var(--wp--preset--color--line-strong);border-width:1px" href="#">Contact us</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
