
jQuery(function ($) {
	'use strict';

	/**
	 * Return a usable thumbnail URL from a WordPress media attachment.
	 */
	function getThumbnail(attachment) {
		if (attachment.sizes && attachment.sizes.thumbnail) {
			return attachment.sizes.thumbnail.url;
		}

		return attachment.url || '';
	}

	/**
	 * Single hero image selector.
	 */
	function selectSingle(target) {
		var frame = wp.media({
			title: 'Choose product image',
			button: {
				text: 'Use image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});

		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();
			var thumbnail = getThumbnail(attachment);

			$('#' + target).val(attachment.id);

			$('#' + target + '_preview').empty().append(
				$('<img>', {
					src: thumbnail,
					alt: ''
				})
			);
		});

		frame.open();
	}

	$('.mdpl-select-image').on('click', function (event) {
		event.preventDefault();
		selectSingle($(this).data('target'));
	});

	$('.mdpl-remove-image').on('click', function (event) {
		event.preventDefault();

		var target = $(this).data('target');

		$('#' + target).val('');
		$('#' + target + '_preview').empty();
	});

	/**
	 * Read the existing gallery IDs.
	 */
	function getGalleryIds() {
		var value = $('#mdpl_gallery').val();

		if (!value) {
			return [];
		}

		return value
			.split(',')
			.map(function (id) {
				return parseInt(id, 10);
			})
			.filter(function (id) {
				return Number.isInteger(id) && id > 0;
			});
	}

	/**
	 * Save the gallery IDs back to the hidden input.
	 */
	function saveGalleryIds(ids) {
		var uniqueIds = [];

		ids.forEach(function (id) {
			if (uniqueIds.indexOf(id) === -1) {
				uniqueIds.push(id);
			}
		});

		$('#mdpl_gallery').val(uniqueIds.join(','));
	}

	/**
	 * Add one gallery image preview.
	 */
	function addGalleryPreview(attachment) {
		var imageId = parseInt(attachment.id, 10);
		var thumbnail = getThumbnail(attachment);

		if (!imageId || !thumbnail) {
			return;
		}

		var existingIds = getGalleryIds();

		// Do not add the same image twice.
		if (existingIds.indexOf(imageId) !== -1) {
			return;
		}

		existingIds.push(imageId);
		saveGalleryIds(existingIds);

		var item = $('<div>', {
			'class': 'mdpl-gallery-item',
			'data-id': imageId
		});

		var image = $('<img>', {
			src: thumbnail,
			alt: ''
		});

		var removeButton = $('<button>', {
			type: 'button',
			'class': 'button mdpl-remove-gallery-image',
			text: 'Remove'
		});

		item.append(image, removeButton);

		$('#mdpl_gallery_preview').append(item);
	}

	/**
	 * Gallery selector.
	 *
	 * Existing selections are preserved. Newly selected images are appended.
	 */
	$('.mdpl-select-gallery').on('click', function (event) {
		event.preventDefault();

		var frame = wp.media({
			title: 'Add product gallery images',
			button: {
				text: 'Add selected images'
			},
			multiple: true,
			library: {
				type: 'image'
			}
		});

		frame.on('select', function () {
			frame.state().get('selection').each(function (attachment) {
				addGalleryPreview(attachment.toJSON());
			});
		});

		frame.open();
	});

	/**
	 * Remove an individual gallery image.
	 */
	$(document).on('click', '.mdpl-remove-gallery-image', function (event) {
		event.preventDefault();

		var item = $(this).closest('.mdpl-gallery-item');
		var imageId = parseInt(item.attr('data-id'), 10);

		var remainingIds = getGalleryIds().filter(function (id) {
			return id !== imageId;
		});

		saveGalleryIds(remainingIds);
		item.remove();
	});

	/**
	 * Add generic product specification rows.
	 */
	$('#mdpl-add-spec').on('click', function (event) {
		event.preventDefault();

		var template = $('#mdpl-spec-template').html();

		if (!template) {
			return;
		}

		// A timestamp avoids reusing an index after a row is removed.
		var index = 'new_' + Date.now();

		var row = template.replace(/__INDEX__/g, index);

		$('#mdpl-specifications').append(row);
	});

	/**
	 * Remove a specification row.
	 */
	$(document).on('click', '.mdpl-remove-spec', function (event) {
		event.preventDefault();

		$(this).closest('.mdpl-spec-row').remove();
	});
});
