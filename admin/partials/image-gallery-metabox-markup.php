<?php

/**
 * Provide an admin area view for the meta boxes.
 *
 * This file is used to markup the 'Image Gallery' meta box.
 *
 * @link       http://www.alexisvillegas.com
 * @since      1.0.0
 *
 * @package    Image_Gallery_Metabox
 * @subpackage Image_Gallery_Metabox/admin/partials
 */

?>

<table class="form-table">
	<tbody>
		<tr valign="top">
			<td>
				
				<ul id="gallery-metabox-list">
				<?php if ( $gallery_stored_meta ) : foreach ( $gallery_stored_meta as $key => $value ) : $image = wp_get_attachment_image_src( $value ); ?>
					<li>
						<input type="hidden" name="_igmb_image_gallery_id[<?php echo $key; ?>]" value="<?php echo $value; ?>">
						<img class="image-preview" src="<?php echo $image[0]; ?>">
						<a class="edit-image" href="#" title="<?php _e('Edit/Change Image', 'image-gallery-metabox'); ?>"></a>
						<a class="remove-image" href="#" title="<?php _e('Remove Image', 'image-gallery-metabox'); ?>"></a>
					</li>
				<?php endforeach; endif; ?>
				</ul>
				
				<div>
					<a class="gallery-add button button-primary" href="#"><?php _e('Add Images', 'image-gallery-metabox'); ?></a>
				</div>
			
			</td>
		</tr>
	</tbody>
</table>
