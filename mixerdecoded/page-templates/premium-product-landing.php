
<?php
/**
 * Template Name: Premium Product Landing Page
 * Template Post Type: page
 * Description: Conversion-focused product landing page for MixerDecoded.
 *
 * @package MixerDecoded
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'landing' );

while ( have_posts() ) :
	the_post();

	$id = get_the_ID();

	$eyebrow    = get_post_meta( $id, '_mdpl_eyebrow', true );
	$headline   = get_post_meta( $id, '_mdpl_headline', true );
	$subhead    = get_post_meta( $id, '_mdpl_subhead', true );

	$hero_image = absint( get_post_meta( $id, '_mdpl_hero_image', true ) );
	$hero_image_url = $hero_image
		? wp_get_attachment_image_url( $hero_image, 'large' )
		: get_the_post_thumbnail_url( $id, 'large' );

	$cta_text = get_post_meta( $id, '_mdpl_cta_text', true );
	$cta_url  = get_post_meta( $id, '_mdpl_cta_url', true );

	$benefits = get_post_meta( $id, '_mdpl_benefits', true );
	$benefits = is_array( $benefits ) ? $benefits : array();

	$description_heading = get_post_meta( $id, '_mdpl_description_heading', true );
	$product_description = get_post_meta( $id, '_mdpl_product_description', true );

	$pros = get_post_meta( $id, '_mdpl_pros', true );
	$cons = get_post_meta( $id, '_mdpl_cons', true );

	$pros_list = array_filter(
		array_map(
			'trim',
			preg_split( '/\r\n|\r|\n/', (string) $pros )
		)
	);

	$cons_list = array_filter(
		array_map(
			'trim',
			preg_split( '/\r\n|\r|\n/', (string) $cons )
		)
	);

	$specifications = get_post_meta( $id, '_mdpl_specifications', true );
	$specifications = is_array( $specifications ) ? $specifications : array();

	$review_cta_text = get_post_meta( $id, '_mdpl_review_cta_text', true );
	$review_cta_url  = get_post_meta( $id, '_mdpl_review_cta_url', true );

	$review_quote  = get_post_meta( $id, '_mdpl_review_quote', true );
	$review_source = get_post_meta( $id, '_mdpl_review_source', true );

	$price_label  = get_post_meta( $id, '_mdpl_price_label', true );
	$price_amount = get_post_meta( $id, '_mdpl_price_amount', true );
	$price_note   = get_post_meta( $id, '_mdpl_price_note', true );

	$purchase_heading = get_post_meta( $id, '_mdpl_purchase_heading', true );
	$purchase_cta_text = get_post_meta( $id, '_mdpl_purchase_cta_text', true );
	$purchase_cta_url  = get_post_meta( $id, '_mdpl_purchase_cta_url', true );

	$demo_video = get_post_meta( $id, '_mdpl_demo_video', true );
	$video_heading = get_post_meta( $id, '_mdpl_video_heading', true );

	$gallery_ids = get_post_meta( $id, '_mdpl_gallery', true );
	$gallery_ids = is_array( $gallery_ids )
		? array_values( array_unique( array_filter( array_map( 'absint', $gallery_ids ) ) ) )
		: array();

	$gallery_heading = get_post_meta( $id, '_mdpl_gallery_heading', true );

	?>

	<main id="mdpl-main" class="mdpl-shell">
		<article <?php post_class( 'mdpl-product-page' ); ?>>

			<!-- HERO -->
			<section class="mdpl-hero" aria-labelledby="mdpl-title">

				<div class="mdpl-hero-copy">

					<?php if ( $eyebrow ) : ?>
						<p class="mdpl-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>

					<h1 id="mdpl-title">
						<?php echo esc_html( $headline ? $headline : get_the_title() ); ?>
					</h1>

					<?php if ( $subhead ) : ?>
						<p class="mdpl-lede"><?php echo esc_html( $subhead ); ?></p>
					<?php endif; ?>

					<div class="mdpl-hero-actions">

						<?php if ( $cta_text && $cta_url ) : ?>
							<a
								class="mdpl-button mdpl-button-primary"
								href="<?php echo esc_url( $cta_url ); ?>"
								target="_blank"
								rel="sponsored nofollow noopener"
							>
								<?php echo esc_html( $cta_text ); ?>
								<span aria-hidden="true">→</span>
							</a>
						<?php endif; ?>

						<a class="mdpl-text-link" href="#mdpl-description">
							Explore product details
							<span aria-hidden="true">↓</span>
						</a>

					</div>

					<p class="mdpl-trust-note">
						<span class="mdpl-trust-dot"></span>
						Independent kitchen-appliance research from MixerDecoded
					</p>

				</div>

				<div class="mdpl-hero-visual">

					<?php if ( $hero_image_url ) : ?>

						<?php
						$hero_alt = $hero_image
							? get_post_meta( $hero_image, '_wp_attachment_image_alt', true )
							: '';

						if ( ! $hero_alt ) {
							$hero_alt = get_the_title();
						}
						?>

						<img
							src="<?php echo esc_url( $hero_image_url ); ?>"
							alt="<?php echo esc_attr( $hero_alt ); ?>"
							fetchpriority="high"
						>

					<?php else : ?>

						<div class="mdpl-image-placeholder">
							<span>Product image</span>
							<small>Choose a hero image in Product Landing Page Settings</small>
						</div>

					<?php endif; ?>

					<?php if ( get_post_meta( $id, '_mdpl_image_caption', true ) ) : ?>
						<div class="mdpl-image-caption">
							<span class="mdpl-caption-mark"></span>
							<span><?php echo esc_html( get_post_meta( $id, '_mdpl_image_caption', true ) ); ?></span>
						</div>
					<?php endif; ?>

				</div>

				<div class="mdpl-hero-orb mdpl-orb-one"></div>
				<div class="mdpl-hero-orb mdpl-orb-two"></div>

			</section>

			<!-- BENEFITS -->
			<?php if ( array_filter( $benefits ) ) : ?>

				<section class="mdpl-benefits" aria-label="Key product benefits">

					<?php foreach ( array_slice( $benefits, 0, 4 ) as $index => $benefit ) : ?>

						<?php if ( empty( $benefit['title'] ) ) : ?>
							<?php continue; ?>
						<?php endif; ?>

						<div class="mdpl-benefit">

							<span class="mdpl-benefit-number">
								0<?php echo esc_html( $index + 1 ); ?>
							</span>

							<div>
								<h2><?php echo esc_html( $benefit['title'] ); ?></h2>

								<?php if ( ! empty( $benefit['text'] ) ) : ?>
									<p><?php echo esc_html( $benefit['text'] ); ?></p>
								<?php endif; ?>
							</div>

						</div>

					<?php endforeach; ?>

				</section>

			<?php endif; ?>

			<!-- PRODUCT OVERVIEW -->
			<section class="mdpl-description mdpl-section" id="mdpl-description">

				<div class="mdpl-section-heading">
					<p class="mdpl-eyebrow">GET TO KNOW THE PRODUCT</p>

					<h2>
						<?php
						echo esc_html(
							$description_heading
								? $description_heading
								: 'Want to know more about this product?'
						);
						?>
					</h2>
				</div>

				<?php if ( $product_description ) : ?>

					<div class="mdpl-prose entry themeform">
						<?php
						// Process rich-text content using WordPress content filters.
						echo apply_filters( 'the_content', $product_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>

				<?php endif; ?>

				<?php if ( $pros_list || $cons_list ) : ?>

					<div class="mdpl-pros-cons">

						<?php if ( $pros_list ) : ?>

							<div class="mdpl-pros-card">
								<h3>Pros</h3>

								<ul>
									<?php foreach ( $pros_list as $pro ) : ?>
										<li><?php echo esc_html( $pro ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>

						<?php endif; ?>

						<?php if ( $cons_list ) : ?>

							<div class="mdpl-cons-card">
								<h3>Cons</h3>

								<ul>
									<?php foreach ( $cons_list as $con ) : ?>
										<li><?php echo esc_html( $con ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>

						<?php endif; ?>

					</div>

				<?php endif; ?>

				<?php if ( $review_cta_text && $review_cta_url ) : ?>

					<div class="mdpl-review-action">
						<a
							class="mdpl-button mdpl-button-primary"
							href="<?php echo esc_url( $review_cta_url ); ?>"
						>
							<?php echo esc_html( $review_cta_text ); ?>
							<span aria-hidden="true">→</span>
						</a>
					</div>

				<?php endif; ?>

			</section>

			<!-- SPECIFICATIONS -->
			<?php if ( $specifications ) : ?>

				<section id="mdpl-specifications" class="mdpl-specifications mdpl-section" aria-labelledby="mdpl-specifications-title">

					<div class="mdpl-section-heading">
						<p class="mdpl-eyebrow">PRODUCT DETAILS</p>
						<h2 id="mdpl-specifications-title">Product specifications</h2>
					</div>

					<div class="mdpl-spec-table-wrap">

						<table class="mdpl-spec-table">
							<tbody>

								<?php foreach ( $specifications as $specification ) : ?>

									<?php
									$label = isset( $specification['label'] ) ? $specification['label'] : '';
									$value = isset( $specification['value'] ) ? $specification['value'] : '';

									if ( '' === trim( $label ) && '' === trim( $value ) ) {
										continue;
									}
									?>

									<tr>
										<th scope="row"><?php echo esc_html( $label ); ?></th>
										<td><?php echo esc_html( $value ); ?></td>
									</tr>

								<?php endforeach; ?>

							</tbody>
						</table>

					</div>

				</section>

			<?php endif; ?>

			<!-- VIDEO -->
			<?php
			if ( $demo_video ) :
				$video_embed = wp_oembed_get( esc_url( $demo_video ) );

				if ( $video_embed ) :
					?>

					<section class="mdpl-video mdpl-section">

						<div class="mdpl-section-heading">
							<p class="mdpl-eyebrow">SEE IT IN ACTION</p>
							<h2>
								<?php echo esc_html( $video_heading ? $video_heading : 'Watch the product in action' ); ?>
							</h2>
						</div>

						<div class="mdpl-video-frame">
							<?php
							echo $video_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress oEmbed HTML.
							?>
						</div>

					</section>

				<?php endif; ?>
			<?php endif; ?>

			<!-- PRODUCT GALLERY -->
			<?php if ( $gallery_ids ) : ?>

				<section id="mdpl-gallery" class="mdpl-gallery mdpl-section" aria-label="Product gallery">

					<div class="mdpl-section-heading">
						<p class="mdpl-eyebrow">A CLOSER LOOK</p>
						<h2>
							<?php echo esc_html( $gallery_heading ? $gallery_heading : 'See the details' ); ?>
						</h2>
					</div>

					<div class="mdpl-gallery-grid">

						<?php foreach ( $gallery_ids as $gallery_id ) : ?>

							<?php
							$image = wp_get_attachment_image(
								absint( $gallery_id ),
								'large',
								false,
								array(
									'loading' => 'lazy',
								)
							);
							?>

							<?php if ( $image ) : ?>
								<figure><?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress attachment image HTML. ?></figure>
							<?php endif; ?>

						<?php endforeach; ?>

					</div>

				</section>

			<?php endif; ?>

			<!-- SOCIAL PROOF -->
			<?php if ( $review_quote ) : ?>

				<section class="mdpl-proof mdpl-section">

					<div class="mdpl-proof-mark">“</div>

					<p class="mdpl-eyebrow">REVIEW & CUSTOMER PERSPECTIVE</p>

					<blockquote><?php echo esc_html( $review_quote ); ?></blockquote>

					<?php if ( $review_source ) : ?>
						<p class="mdpl-proof-source"><?php echo esc_html( $review_source ); ?></p>
					<?php endif; ?>

					<p class="mdpl-proof-disclaimer">
						Use this space only for genuine, attributable customer feedback or clearly identified editorial testing.
					</p>

				</section>

			<?php endif; ?>

			<!-- PURCHASE -->
			<section class="mdpl-purchase mdpl-section" id="mdpl-purchase">

				<div class="mdpl-purchase-copy">

					<p class="mdpl-eyebrow">CHECK YOUR OPTIONS</p>

					<h2>
						<?php
						echo esc_html(
							$purchase_heading
								? $purchase_heading
								: 'Ready to take a closer look?'
						);
						?>
					</h2>

					<p>
						<?php
						echo esc_html(
							$price_note
								? $price_note
								: 'Check the retailer for current pricing, availability and product details.'
						);
						?>
					</p>

				</div>

				<div class="mdpl-purchase-card">

					<span class="mdpl-price-label">
						<?php echo esc_html( $price_label ? $price_label : 'Retailer pricing' ); ?>
					</span>

					<?php if ( $price_amount ) : ?>
						<strong class="mdpl-price-amount">
							<?php echo esc_html( $price_amount ); ?>
						</strong>
					<?php endif; ?>

					<?php if ( $purchase_cta_url && $purchase_cta_text ) : ?>

						<a
							class="mdpl-button mdpl-button-primary mdpl-button-wide"
							href="<?php echo esc_url( $purchase_cta_url ); ?>"
							target="_blank"
							rel="sponsored nofollow noopener"
						>
							<?php echo esc_html( $purchase_cta_text ); ?>
							<span aria-hidden="true">↗</span>
						</a>

					<?php else : ?>

						<p class="mdpl-purchase-unavailable">
							Add a retailer URL and button text in the landing-page settings to display the purchase link.
						</p>

					<?php endif; ?>

					<small>
						Prices and availability can change. Confirm details with the retailer.
					</small>

				</div>

			</section>

			<!-- RELATED RESOURCES -->
			<?php if ( get_post_meta( $id, '_mdpl_show_related', true ) ) : ?>

				<section class="mdpl-related mdpl-section">

					<div class="mdpl-section-heading">
						<p class="mdpl-eyebrow">KEEP EXPLORING</p>
						<h2>Related MixerDecoded resources</h2>
					</div>

					<div class="mdpl-related-content">
						<?php
						echo wp_kses_post(
							get_post_meta( $id, '_mdpl_related_content', true )
						);
						?>
					</div>

				</section>

			<?php endif; ?>

		</article>
	</main>

<?php endwhile; ?>

<?php get_footer( 'landing' ); ?>
