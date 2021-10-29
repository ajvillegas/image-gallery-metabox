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
				<?php
				if ( $gallery_stored_meta ) {
					foreach ( $gallery_stored_meta as $key => $value ) {
						$mime     = get_post_mime_type( $value );
						$filename = basename( get_attached_file( $value ) );

						// Check if attachment is an image or video file.
						if ( preg_match( '/\bimage\b/', $mime ) ) {
							$image = wp_get_attachment_image_src( $value );
							$thumb = '<img class="image-preview" src="' . esc_url( $image[0] ) . '">';
						} else {
							$thumb = '<div class="thumbnail"><div class="centered"><img src="' . esc_url( get_site_url() ) . '/wp-includes/images/media/video.png" class="icon" alt=""></div><div class="filename"><div>' . esc_html( $filename ) . '</div></div></div>';
						}

						?>
						<li class="attachment">
							<input type="hidden" name="_igmb_image_gallery_id[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>">
							<?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<a class="edit-image" href="#" title="<?php echo esc_html__( 'Edit/Change Image', 'image-gallery-metabox' ); ?>"></a>
							<a class="remove-image" href="#" title="<?php echo esc_html__( 'Remove Image', 'image-gallery-metabox' ); ?>"></a>
						</li>
						<?php
					}
				}
				?>
				</ul>

				<input type="hidden" name="igm_honeypot" value="true">

				<div>
					<a class="gallery-add button button-primary" href="#"><?php echo esc_html__( 'Add Images', 'image-gallery-metabox' ); ?></a>
				</div>
			</td>
		</tr>
	</tbody>
</table>
