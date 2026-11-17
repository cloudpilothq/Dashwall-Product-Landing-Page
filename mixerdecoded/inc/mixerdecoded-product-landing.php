
<?php
/**
 * MixerDecoded product landing-page fields and settings.
 *
 * @package MixerDecoded
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the landing-page template.
 */
function mdpl_register_page_template( $templates ) {
	$templates['page-templates/premium-product-landing.php'] = 'Premium Product Landing Page';
	return $templates;
}
add_filter( 'theme_page_templates', 'mdpl_register_page_template' );

/**
 * Load admin media tools only on the Page editor.
 */
function mdpl_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();

	wp_enqueue_script(
		'mdpl-admin',
		get_template_directory_uri() . '/assets/js/mdpl-admin.js',
		array( 'jquery', 'media-editor', 'media-views' ),
		'1.1.0',
		true
	);
}
add_action( 'admin_enqueue_scripts', 'mdpl_admin_assets' );

/**
 * Load frontend assets only when this template is being used.
 */
function mdpl_front_assets() {
	if ( ! is_page() ) {
		return;
	}

	$page = get_queried_object();

	if ( ! $page || get_page_template_slug( $page->ID ) !== 'page-templates/premium-product-landing.php' ) {
		return;
	}

	wp_enqueue_style(
		'mdpl-product-landing',
		get_template_directory_uri() . '/assets/css/product-landing.css',
		array( 'dashwall-style' ),
		'1.1.0'
	);

	wp_enqueue_script(
		'mdpl-product-landing',
		get_template_directory_uri() . '/assets/js/product-landing.js',
		array(),
		'1.1.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'mdpl_front_assets', 30 );

/**
 * Register the settings meta box.
 */
function mdpl_add_meta_box() {
	add_meta_box(
		'mdpl-product-settings',
		'MixerDecoded Product Landing Page Settings',
		'mdpl_render_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_page', 'mdpl_add_meta_box' );

/**
 * Output a standard settings field.
 */
function mdpl_field( $label, $key, $value, $type = 'text', $extra = '' ) {
	$id = str_replace( array( '[', ']' ), array( '_', '' ), $key );

	echo '<p class="mdpl-admin-field">';
	echo '<label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label>';

	if ( 'textarea' === $type ) {
		echo '<textarea class="widefat" rows="3" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea>';
	} else {
		echo '<input class="widefat" type="' . esc_attr( $type ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" ' . $extra . '>';
	}

	echo '</p>';
}

/**
 * Render the landing-page settings.
 */
function mdpl_render_meta_box( $post ) {
	wp_nonce_field( 'mdpl_save_settings', 'mdpl_nonce' );

	$get = function( $key ) use ( $post ) {
		return get_post_meta( $post->ID, '_mdpl_' . $key, true );
	};

	$benefits = $get( 'benefits' );
	$benefits = is_array( $benefits ) ? $benefits : array();

	$gallery = $get( 'gallery' );
	$gallery = is_array( $gallery ) ? array_values( array_unique( array_filter( array_map( 'absint', $gallery ) ) ) ) : array();

	$specifications = $get( 'specifications' );
	$specifications = is_array( $specifications ) ? $specifications : array();
	?>

	<div class="mdpl-admin-wrap">

		<p>
			Configure the product landing page below. The main product overview, pros and cons,
			specifications, gallery and calls to action are managed here.
		</p>

		<hr>
		<h3>Hero section</h3>

		<?php
		mdpl_field( 'Eyebrow / category label', 'mdpl_eyebrow', $get( 'eyebrow' ) );
		mdpl_field( 'Hero headline (leave blank to use page title)', 'mdpl_headline', $get( 'headline' ) );
		mdpl_field( 'Short supporting introduction', 'mdpl_subhead', $get( 'subhead' ), 'textarea' );
		mdpl_field( 'Hero CTA button text', 'mdpl_cta_text', $get( 'cta_text' ) );
		mdpl_field( 'Hero CTA destination URL', 'mdpl_cta_url', $get( 'cta_url' ), 'url' );
		mdpl_field( 'Image caption (optional)', 'mdpl_image_caption', $get( 'image_caption' ) );
		mdpl_field( 'Product demonstration video URL (YouTube/Vimeo)', 'mdpl_demo_video', $get( 'demo_video' ), 'url' );
		mdpl_field( 'Video section heading', 'mdpl_video_heading', $get( 'video_heading' ) );
		?>

		<p>
			<strong>Hero image</strong><br>
			<input type="hidden" id="mdpl_hero_image" name="mdpl_hero_image" value="<?php echo esc_attr( $get( 'hero_image' ) ); ?>">
			<button type="button" class="button mdpl-select-image" data-target="mdpl_hero_image">Choose hero image</button>
			<button type="button" class="button mdpl-remove-image" data-target="mdpl_hero_image">Remove</button>
			<span class="mdpl-admin-preview" id="mdpl_hero_image_preview">
				<?php
				if ( $get( 'hero_image' ) ) {
					echo wp_get_attachment_image( absint( $get( 'hero_image' ) ), 'thumbnail' );
				}
				?>
			</span>
		</p>

		<hr>
		<h3>Product benefits</h3>
		<p>These are the short benefit highlights displayed below the hero section. Up to four can be displayed.</p>

		<?php
		for ( $i = 0; $i < 4; $i++ ) {
			$benefit = isset( $benefits[ $i ] ) && is_array( $benefits[ $i ] ) ? $benefits[ $i ] : array();

			echo '<div class="mdpl-benefit-admin">';
			echo '<h4>Benefit ' . esc_html( $i + 1 ) . '</h4>';

			mdpl_field(
				'Benefit title',
				'mdpl_benefits[' . $i . '][title]',
				isset( $benefit['title'] ) ? $benefit['title'] : ''
			);

			mdpl_field(
				'Short benefit explanation',
				'mdpl_benefits[' . $i . '][text]',
				isset( $benefit['text'] ) ? $benefit['text'] : '',
				'textarea'
			);

			echo '</div>';
		}
		?>

		<hr>
		<h3>Product overview</h3>
		<p>This content appears in the main product overview section on the frontend.</p>

		<?php
		mdpl_field(
			'Section heading',
			'mdpl_description_heading',
			$get( 'description_heading' ) ?: 'Want to know more about this product?'
		);
		?>

		<p><strong>Product description</strong></p>

		<?php
		wp_editor(
			$get( 'product_description' ),
			'mdpl_product_description',
			array(
				'textarea_name' => 'mdpl_product_description',
				'textarea_rows' => 9,
				'media_buttons' => false,
				'teeny'         => false,
				'quicktags'     => true,
			)
		);
		?>

		<p class="description">Use the editor for the product overview. You can format paragraphs, add links, headings and lists.</p>

		<?php
		mdpl_field( 'Pros (one item per line)', 'mdpl_pros', $get( 'pros' ), 'textarea' );
		mdpl_field( 'Cons (one item per line)', 'mdpl_cons', $get( 'cons' ), 'textarea' );
		?>

		<hr>
		<h3>Product specifications</h3>
		<p>Add as many specification rows as needed. The labels are generic, so this section can be used for mixers, blenders, food processors and other appliances.</p>

		<div id="mdpl-specifications">
			<?php
			if ( ! empty( $specifications ) ) :
				foreach ( $specifications as $index => $specification ) :
					$label = isset( $specification['label'] ) ? $specification['label'] : '';
					$value = isset( $specification['value'] ) ? $specification['value'] : '';
					?>
					<div class="mdpl-spec-row">
						<div>
							<label><strong>Specification label</strong></label>
							<input class="widefat" type="text" name="mdpl_specifications[<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $label ); ?>">
						</div>
						<div>
							<label><strong>Specification value</strong></label>
							<input class="widefat" type="text" name="mdpl_specifications[<?php echo esc_attr( $index ); ?>][value]" value="<?php echo esc_attr( $value ); ?>">
						</div>
						<button type="button" class="button mdpl-remove-spec">Remove</button>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</div>

		<button type="button" class="button" id="mdpl-add-spec">Add specification</button>

		<script type="text/html" id="mdpl-spec-template">
			<div class="mdpl-spec-row">
				<div>
					<label><strong>Specification label</strong></label>
					<input class="widefat" type="text" name="mdpl_specifications[__INDEX__][label]" value="">
				</div>
				<div>
					<label><strong>Specification value</strong></label>
					<input class="widefat" type="text" name="mdpl_specifications[__INDEX__][value]" value="">
				</div>
				<button type="button" class="button mdpl-remove-spec">Remove</button>
			</div>
		</script>

		<hr>
		<h3>Product gallery</h3>

		<?php mdpl_field( 'Gallery heading', 'mdpl_gallery_heading', $get( 'gallery_heading' ) ); ?>

		<input
			type="hidden"
			id="mdpl_gallery"
			name="mdpl_gallery"
			value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>"
		>

		<button type="button" class="button mdpl-select-gallery">Add gallery images</button>

		<div id="mdpl_gallery_preview" class="mdpl-gallery-admin-preview">
			<?php foreach ( $gallery as $image_id ) : ?>
				<div class="mdpl-gallery-item" data-id="<?php echo esc_attr( $image_id ); ?>">
					<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
					<button type="button" class="button mdpl-remove-gallery-image">Remove</button>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="description">Add images without replacing existing selections. Use Remove to delete individual images.</p>

		<hr>
		<h3>Review link</h3>
		<p>Use this to direct visitors to the full MixerDecoded product review. This should normally be an internal link.</p>

		<?php
		mdpl_field( 'Review button text', 'mdpl_review_cta_text', $get( 'review_cta_text' ) ?: 'Read our full review' );
		mdpl_field( 'Review page URL', 'mdpl_review_cta_url', $get( 'review_cta_url' ), 'url' );
		?>

		<hr>
		<h3>Social proof</h3>

		<?php
		mdpl_field( 'Genuine review excerpt or testing observation', 'mdpl_review_quote', $get( 'review_quote' ), 'textarea' );
		mdpl_field( 'Attribution / source', 'mdpl_review_source', $get( 'review_source' ) );
		?>

		<hr>
		<h3>Pricing and purchase</h3>

		<?php
		mdpl_field( 'Purchase section heading', 'mdpl_purchase_heading', $get( 'purchase_heading' ) );
		mdpl_field( 'Price label (e.g. Current retailer price)', 'mdpl_price_label', $get( 'price_label' ) );
		mdpl_field( 'Displayed price (verify before publishing)', 'mdpl_price_amount', $get( 'price_amount' ) );
		mdpl_field( 'Pricing note / retailer context', 'mdpl_price_note', $get( 'price_note' ), 'textarea' );
		mdpl_field( 'Purchase CTA button text', 'mdpl_purchase_cta_text', $get( 'purchase_cta_text' ) ?: 'Check price on Amazon' );
		mdpl_field( 'Purchase CTA retailer URL', 'mdpl_purchase_cta_url', $get( 'purchase_cta_url' ), 'url' );
		?>

		<hr>
		<h3>Related resources</h3>

		<p>
			<label>
				<input type="checkbox" name="mdpl_show_related" value="1" <?php checked( $get( 'show_related' ), '1' ); ?>>
				Display related resources section
			</label>
		</p>

		<?php
		mdpl_field( 'Related resource content (HTML links allowed)', 'mdpl_related_content', $get( 'related_content' ), 'textarea' );
		?>

	</div>
	<?php
}

/**
 * Save landing-page settings.
 */
function mdpl_save_meta( $post_id ) {
	if (
		! isset( $_POST['mdpl_nonce'] ) ||
		! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['mdpl_nonce'] ) ),
			'mdpl_save_settings'
		)
	) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	$text_keys = array(
		'eyebrow',
		'headline',
		'subhead',
		'cta_text',
		'image_caption',
		'description_heading',
		'pros',
		'cons',
		'gallery_heading',
		'review_cta_text',
		'review_quote',
		'review_source',
		'purchase_heading',
		'price_label',
		'price_amount',
		'price_note',
		'purchase_cta_text',
		'video_heading',
	);

	foreach ( $text_keys as $key ) {
		$field_name = 'mdpl_' . $key;

		if ( isset( $_POST[ $field_name ] ) ) {
			update_post_meta(
				$post_id,
				'_mdpl_' . $key,
				sanitize_textarea_field( wp_unslash( $_POST[ $field_name ] ) )
			);
		}
	}

	$url_keys = array(
		'demo_video',
		'cta_url',
		'review_cta_url',
		'purchase_cta_url',
	);

	foreach ( $url_keys as $key ) {
		$field_name = 'mdpl_' . $key;

		if ( isset( $_POST[ $field_name ] ) ) {
			update_post_meta(
				$post_id,
				'_mdpl_' . $key,
				esc_url_raw( wp_unslash( $_POST[ $field_name ] ) )
			);
		}
	}

	if ( isset( $_POST['mdpl_hero_image'] ) ) {
		update_post_meta(
			$post_id,
			'_mdpl_hero_image',
			absint( $_POST['mdpl_hero_image'] )
		);
	}

	// Save rich-text product description.
	if ( isset( $_POST['mdpl_product_description'] ) ) {
		update_post_meta(
			$post_id,
			'_mdpl_product_description',
			wp_kses_post( wp_unslash( $_POST['mdpl_product_description'] ) )
		);
	}

	// Save benefit cards.
	if ( isset( $_POST['mdpl_benefits'] ) && is_array( $_POST['mdpl_benefits'] ) ) {
		$clean_benefits = array();

		foreach ( $_POST['mdpl_benefits'] as $benefit ) {
			if ( ! is_array( $benefit ) ) {
				continue;
			}

			$title = isset( $benefit['title'] ) ? sanitize_text_field( wp_unslash( $benefit['title'] ) ) : '';
			$text  = isset( $benefit['text'] ) ? sanitize_textarea_field( wp_unslash( $benefit['text'] ) ) : '';

			if ( '' !== $title || '' !== $text ) {
				$clean_benefits[] = array(
					'title' => $title,
					'text'  => $text,
				);
			}
		}

		update_post_meta( $post_id, '_mdpl_benefits', $clean_benefits );
	}

	// Save generic specification rows.
	$clean_specifications = array();

	if ( isset( $_POST['mdpl_specifications'] ) && is_array( $_POST['mdpl_specifications'] ) ) {
		foreach ( $_POST['mdpl_specifications'] as $specification ) {
			if ( ! is_array( $specification ) ) {
				continue;
			}

			$label = isset( $specification['label'] ) ? sanitize_text_field( wp_unslash( $specification['label'] ) ) : '';
			$value = isset( $specification['value'] ) ? sanitize_text_field( wp_unslash( $specification['value'] ) ) : '';

			if ( '' !== $label || '' !== $value ) {
				$clean_specifications[] = array(
					'label' => $label,
					'value' => $value,
				);
			}
		}
	}

	update_post_meta( $post_id, '_mdpl_specifications', $clean_specifications );

	// Save gallery IDs.
	if ( isset( $_POST['mdpl_gallery'] ) ) {
		$raw_gallery = sanitize_text_field( wp_unslash( $_POST['mdpl_gallery'] ) );
		$gallery_ids = array_filter( array_map( 'absint', explode( ',', $raw_gallery ) ) );
		$gallery_ids = array_values( array_unique( $gallery_ids ) );

		update_post_meta( $post_id, '_mdpl_gallery', $gallery_ids );
	}

	update_post_meta(
		$post_id,
		'_mdpl_show_related',
		isset( $_POST['mdpl_show_related'] ) ? '1' : '0'
	);

	if ( isset( $_POST['mdpl_related_content'] ) ) {
		update_post_meta(
			$post_id,
			'_mdpl_related_content',
			wp_kses_post( wp_unslash( $_POST['mdpl_related_content'] ) )
		);
	}
}
add_action( 'save_post_page', 'mdpl_save_meta' );
