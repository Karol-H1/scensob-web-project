<?php
/**
 * Title: Credentials and compliance
 * Slug: scensob/trust-signals
 * Categories: scensob, featured
 * Description: Licences, insurance, accreditations and service commitments. The brief lists these as a Transport & Delivery site priority.
 *
 * @package scensob
 */

$scensob_signals = array(
	array(
		'Licensing',
		'[ Placeholder &mdash; operator licence or equivalent authorisation, to be confirmed. ]',
	),
	array(
		'Insurance',
		'[ Placeholder &mdash; cover held and limits, to be confirmed. ]',
	),
	array(
		'Accreditation',
		'[ Placeholder &mdash; industry accreditations, including any specific to pharmaceutical handling. ]',
	),
	array(
		'Service commitment',
		'[ Placeholder &mdash; only publish response or delivery commitments the business will stand behind. ]',
	),
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"className":"scensob-eyebrow"} -->
	<p class="scensob-eyebrow">[ Trust &amp; compliance &mdash; a site priority in the brief ]</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":2,"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|50"}}}} -->
	<h2 class="wp-block-heading" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--50)">Credentials and compliance</h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"className":"scensob-card-grid","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-columns scensob-card-grid">
		<?php foreach ( $scensob_signals as $scensob_signal ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"scensob-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"},"border":{"color":"var:preset|color|line","width":"1px","radius":"12px"}},"backgroundColor":"accent-soft","layout":{"type":"default"}} -->
			<div class="wp-block-group scensob-card has-border-color has-accent-soft-background-color has-background" style="border-color:var(--wp--preset--color--line);border-width:1px;border-radius:12px;padding:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading"><?php echo esc_html( $scensob_signal[0] ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
				<p class="has-muted-color has-text-color has-small-font-size"><?php echo wp_kses_post( $scensob_signal[1] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
