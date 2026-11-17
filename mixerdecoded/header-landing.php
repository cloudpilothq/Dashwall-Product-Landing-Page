

<?php
/**
 * Dedicated header for MixerDecoded product landing pages.
 *
 * @package MixerDecoded
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id    = get_queried_object_id();
$review_url = get_post_meta( $page_id, '_mdpl_review_cta_url', true );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'mdpl-landing-site' ); ?>>
<?php wp_body_open(); ?>

<a class="mdpl-skip-link" href="#mdpl-main">Skip to content</a>

<header class="mdpl-site-header">
	<div class="mdpl-header-inner">

		<a class="mdpl-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> homepage">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="mdpl-brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<?php endif; ?>
		</a>

		<button
			class="mdpl-menu-toggle"
			type="button"
			aria-expanded="false"
			aria-controls="mdpl-primary-navigation"
			aria-label="Open navigation menu"
		>
			<span></span>
			<span></span>
			<span></span>
		</button>

		<nav
			class="mdpl-primary-nav"
			id="mdpl-primary-navigation"
			aria-label="Product navigation"
		>
			<a href="#mdpl-description">Product Overview</a>
			<a href="#mdpl-specifications">Specifications</a>
			<a href="#mdpl-gallery">Gallery</a>

			<?php if ( $review_url ) : ?>
				<a href="<?php echo esc_url( $review_url ); ?>">Our Review</a>
			<?php else : ?>
				<a href="#mdpl-description">Our Review</a>
			<?php endif; ?>

			<a class="mdpl-header-cta" href="#mdpl-purchase">
				Check Price <span aria-hidden="true">→</span>
			</a>
		</nav>

	</div>
</header>
