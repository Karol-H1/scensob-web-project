<?php
/**
 * Title: SCENSOB logo
 * Slug: scensob/logo
 * Categories: scensob
 * Inserter: no
 *
 * @package scensob
 */

$scensob_label = scensob_division_label();
?>
<!-- wp:html -->
<a class="scensob-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
	<span class="screen-reader-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
	<?php echo scensob_logo_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG file. ?>
	<?php if ( '' !== $scensob_label ) : ?>
		<span class="scensob-logo__division"><?php echo esc_html( $scensob_label ); ?></span>
	<?php endif; ?>
</a>
<!-- /wp:html -->
