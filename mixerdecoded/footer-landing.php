
<?php
/**
 * Dedicated compact footer for MixerDecoded product landing pages.
 *
 * @package MixerDecoded
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_links = array(
	'About' => array( 'about', 'about-us' ),
	'Contact' => array( 'contact', 'contact-us' ),
	'Affiliate Disclosure' => array( 'affiliate-disclosure', 'affiliate-disclosure-policy' ),
);

$resolved_links = array();

foreach ( $footer_links as $label => $slugs ) {
	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );

		if ( $page && 'publish' === $page->post_status ) {
			$resolved_links[] = array(
				'label' => $label,
				'url'   => get_permalink( $page->ID ),
			);
			break;
		}
	}
}

$privacy_url = get_privacy_policy_url();

if ( $privacy_url ) {
	$resolved_links[] = array(
		'label' => 'Privacy Policy',
		'url'   => $privacy_url,
	);
}
?>

<footer class="mdpl-site-footer">
	<div class="mdpl-footer-inner">

		<a class="mdpl-footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
		</a>

		<nav class="mdpl-footer-nav" aria-label="Footer navigation">
			<?php foreach ( $resolved_links as $link ) : ?>
				<a href="<?php echo esc_url( $link['url'] ); ?>">
					<?php echo esc_html( $link['label'] ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<p class="mdpl-footer-copyright">
			&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?>
			<?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
			All rights reserved.
		</p>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
