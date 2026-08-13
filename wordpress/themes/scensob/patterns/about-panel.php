<?php
/**
 * Title: About panel
 * Slug: scensob/about-panel
 * Categories: scensob, text
 * Description: Narrative column beside an image, with a row of figures underneath.
 *
 * @package scensob
 */

$scensob_photos = scensob_photos( 1 );
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"className":"scensob-eyebrow"} -->
	<p class="scensob-eyebrow">[ About &mdash; history and positioning to be confirmed ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"},"margin":{"top":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-top" style="margin-top:var(--wp--preset--spacing--40)">
		<!-- wp:column {"verticalAlignment":"top","width":"58%"} -->
		<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:58%">
			<!-- wp:heading {"level":2} -->
			<h2 class="wp-block-heading">Who we are</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"muted","fontSize":"large","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
			<p class="has-muted-color has-text-color has-large-font-size" style="margin-top:var(--wp--preset--spacing--30)">[ Placeholder &mdash; opening paragraph describing the division, to be confirmed. ]</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"muted","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
			<p class="has-muted-color has-text-color" style="margin-top:var(--wp--preset--spacing--30)">[ Placeholder &mdash; how the division operates, who it serves, and what sets it apart. Two or three short paragraphs work best here. ]</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"muted","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
			<p class="has-muted-color has-text-color" style="margin-top:var(--wp--preset--spacing--30)">[ Placeholder &mdash; history, founding, or the team behind the work. ]</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"top"} -->
		<div class="wp-block-column is-vertically-aligned-top">
			<!-- wp:html -->
			<?php if ( isset( $scensob_photos[0] ) ) : ?>
				<figure class="scensob-media">
					<img src="<?php echo esc_url( $scensob_photos[0]['url'] ); ?>" alt="<?php echo esc_attr( $scensob_photos[0]['alt'] ); ?>" loading="lazy" decoding="async" />
				</figure>
			<?php else : ?>
				<div class="scensob-media-placeholder"><span>[ Image ]</span></div>
			<?php endif; ?>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:columns {"className":"scensob-card-grid","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"},"margin":{"top":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns scensob-card-grid" style="margin-top:var(--wp--preset--spacing--50)">
		<?php for ( $scensob_i = 0; $scensob_i < 3; $scensob_i++ ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"scensob-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"},"border":{"color":"var:preset|color|line","width":"1px","radius":"12px"}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group scensob-card has-border-color has-surface-background-color has-background" style="border-color:var(--wp--preset--color--line);border-width:1px;border-radius:12px;padding:var(--wp--preset--spacing--40)">
				<!-- wp:paragraph {"textColor":"accent","style":{"typography":{"fontFamily":"var:preset|font-family|heading","fontSize":"34px","fontWeight":"600","lineHeight":"1"}}} -->
				<p class="has-accent-color has-text-color" style="font-family:var(--wp--preset--font-family--heading);font-size:34px;font-weight:600;line-height:1">[ &mdash; ]</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
				<p class="has-muted-color has-text-color has-small-font-size">[ Figure to be confirmed &mdash; only publish numbers the business will stand behind. ]</p>
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
