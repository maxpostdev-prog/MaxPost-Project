(function ($) {
	'use strict';

	function syncField($field) {
		var ids = [];
		$field.find('.maxpost-media-item').each(function () {
			var id = parseInt($(this).attr('data-id'), 10);
			if (id > 0 && ids.indexOf(id) === -1) {
				ids.push(id);
			}
		});
		$field.find('.maxpost-media-value').val(ids.join(','));
	}

	function renderItem(attachment) {
		var preview = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
		return $('<div>', {
			'class': 'maxpost-media-item',
			'data-id': attachment.id
		}).append(
			$('<img>', {
				src: preview,
				alt: attachment.alt || attachment.title || ''
			}),
			$('<button>', {
				type: 'button',
				'class': 'button-link-delete maxpost-media-remove',
				text: MaxPostMedia.remove
			})
		);
	}

	$(document).on('click', '.maxpost-media-select', function (event) {
		event.preventDefault();

		var $field = $(this).closest('.maxpost-media-field');
		var multiple = $field.attr('data-multiple') === '1';
		var frame = wp.media({
			title: multiple ? MaxPostMedia.screensTitle : ($(this).attr('data-title') || MaxPostMedia.iconTitle),
			button: { text: multiple ? MaxPostMedia.useScreenshots : MaxPostMedia.useImage },
			library: { type: 'image' },
			multiple: multiple
		});

		frame.on('select', function () {
			var selection = frame.state().get('selection').toJSON();
			var $preview = $field.find('.maxpost-media-preview');

			if (!multiple) {
				$preview.empty();
			}

			selection.forEach(function (attachment) {
				if ($preview.find('[data-id="' + attachment.id + '"]').length === 0) {
					$preview.append(renderItem(attachment));
				}
			});

			syncField($field);
		});

		frame.open();
	});

	$(document).on('click', '.maxpost-media-remove', function (event) {
		event.preventDefault();
		var $field = $(this).closest('.maxpost-media-field');
		$(this).closest('.maxpost-media-item').remove();
		syncField($field);
	});
}(jQuery));
