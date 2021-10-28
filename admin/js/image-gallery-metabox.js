/**
 * The image upload and edit functionality of the 'Image Gallery' meta box.
 *
 * Uses the default WordPress media uploader.
 *
 * @since 1.0.0
 */

(function( $ ) {

	// Prepare the variable that holds our custom media manager.
	var media_frame;

	// Bind to our click event in order to open up the new media experience.
	$( document ).on( 'click.igmbpAddMediaManager', '#image-gallery-meta-box a.gallery-add', function( e ) {

		// Prevent the default action from occuring.
		e.preventDefault();

		// If the frame already exists, close it.
		if ( media_frame ) {
			media_frame.close();
		}

		// Create custom media frame. Refer to the wp-includes/js/media-views.js file for more default options.
		media_frame = wp.media.frames.media_frame = wp.media( {

			// Custom class name for our media frame.
			className: 'media-frame igmb-add-media-frame',
			// Assign 'select' workflow since we only want to upload an image. Use the 'post' workflow for posts.
			frame: 'select',
			// Allow mutiple file uploads.
			multiple: true,
			// Set custom media workflow title using the localized script object 'image_gallery_metabox'.
			title: image_gallery_metabox.add_title,
			// Limit media library access to images and video only.
			library: {
				type: [ 'image', 'video' ]
			},
			// Set custom button text using the localized script object 'image_gallery_metabox'.
			button: {
				text: image_gallery_metabox.add_button
			}

		} );

		media_frame.on( 'select', function() {

			var listIndex = $( '#gallery-metabox-list li' ).index( $( '#gallery-metabox-list li:last' ) ),
			selection = media_frame.state().get( 'selection' );

			var attachmentPreview;

			selection.map( function( attachment, i ) {
			attachment = attachment.toJSON(),
			index = listIndex + ( i + 1 );

			// Check if attachment is an image or video file.
			if ( attachment.mime.includes('image') ) {
				// Check if thumbnail size exists, if not use full size.
				if ( attachment.sizes.thumbnail ) {
					attachmentSource = attachment.sizes.thumbnail.url;
				} else {
					attachmentSource = attachment.url;
				}

				attachmentPreview = '<img class="image-preview" src="' + attachmentSource + '">';
			} else {
				attachmentSource = attachment.url;

				attachmentPreview = '<div class="thumbnail"><div class="centered"><img src="' + image_gallery_metabox.site_url + '/wp-includes/images/media/video.png" class="icon" alt=""></div><div class="filename"><div>' + attachment.filename + '</div></div></div>';
			}

			$( '#gallery-metabox-list' ).append( '<li class="attachment"><input type="hidden" name="_igmb_image_gallery_id[' + index + ']" value="' + attachment.id + '">' + attachmentPreview + '<a class="edit-image" href="#" title="' + image_gallery_metabox.link_edit_title + '"></a><a class="remove-image" href="#" title="' + image_gallery_metabox.link_remove_title + '"></a></li>' );
			} );

		} );

		makeSortable();

		// Now that everything has been set, let's open up the frame.
		media_frame.open();

	} );

	// Bind to our click event in order to open up the media frame.
	$( document ).on( 'click.igmbEditMediaManager', '#image-gallery-meta-box a.edit-image', function( e ) {

		// Prevent the default action from occuring.
		e.preventDefault();

		var that = $( this );

		// If the frame already exists, close it.
		if ( media_frame ) {
			media_frame.close();
		}

		// Create custom media frame. Refer to the wp-includes/js/media-views.js file for more default options.
		media_frame = wp.media.frames.media_frame = wp.media( {

			// Custom class name for our media frame.
			className: 'media-frame igmb-edit-media-frame',
			// Assign 'select' workflow since we only want to upload an image. Use the 'post' workflow for posts.
			frame: 'select',
			// Allow mutiple file uploads.
			multiple: false,
			// Set custom media workflow title using the localized script object 'image_gallery_metabox'.
			title: image_gallery_metabox.edit_title,
			// Limit media library access to images and video only.
			library: {
				type: [ 'image', 'video' ]
			},
			// Set custom button text using the localized script object 'image_gallery_metabox'.
			button: {
				text: image_gallery_metabox.edit_button
			}

		} );

		// Pre-select current image when opening our media frame.
		media_frame.on( 'open', function() {

			var selection = media_frame.state().get( 'selection' );
			id = that.parent().find( 'input:hidden' ).val();

			attachment = wp.media.attachment( id );
			attachment.fetch();
			selection.add( attachment ? [ attachment ] : [] );

		} );

		media_frame.on( 'select', function() {

			attachment = media_frame.state().get( 'selection' ).first().toJSON();

			// Check if thumbnail size exists, if not use full size
			if ( attachment.sizes.thumbnail ) {
				attachmentSource = attachment.sizes.thumbnail.url;
			} else {
				attachmentSource = attachment.url;
			}

			that.parent().find( 'input:hidden' ).attr( 'value', attachment.id );
			that.parent().find( 'img.image-preview' ).attr( 'src', attachmentSource );

		} );

		// Now that everything has been set, let's open up the frame.
		media_frame.open();

	} );

	function resetIndex() {

		$( '#gallery-metabox-list li' ).each( function( i ) {
			$( this ).find( 'input:hidden' ).attr( 'name', '_igmb_image_gallery_id[' + i + ']' );
		} );

	}

	function makeSortable() {

		$( '#gallery-metabox-list' ).sortable( {
			opacity: 0.8,
			stop: function() {
				resetIndex();
			}
		} );

	}

	// Bind to our click event in order to remove an image from the gallery.
	$( document ).on( 'click.igmbRemoveMedia', '#image-gallery-meta-box a.remove-image', function( e ) {

		// Prevent the default action from occuring.
		e.preventDefault();

		$( this ).parents( 'li' ).animate( { opacity: 0 }, 200, function() {
			$( this ).remove();
			resetIndex();
		} );

	} );

	$( document ).ready( function() {
		makeSortable();
	} );

} ) ( jQuery );
