# Image Gallery Metabox

This plugin adds an intuitive image gallery meta box to the page editor screen.

**Contributors**: [ajvillegas](http://profiles.wordpress.org/ajvillegas)  
**Tags**: [image gallery](http://wordpress.org/plugins/tags/image-gallery), [meta box](http://wordpress.org/plugins/tags/meta-box), [metabox](http://wordpress.org/plugins/tags/metabox), [admin](http://wordpress.org/plugins/tags/admin)  
**Requires at least**: 4.5  
**Tested up to**: 5.8  
**Stable tag**: 1.1.0  
**License**: [GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)

## Description

This plugin adds an intuitive image gallery meta box to the page editor screen. Designed for theme developers, it outputs an array of attachment IDs that can be used to manage galleries and sliders in the front-end.

### Display Options

By default, the plugin adds the image gallery meta box to pages only, but can be displayed on other post types, specific posts or pages, and page templates using the `igmb_display_meta_box` filter.

The example below demonstrates how you can implement this filter on your theme (the values shown below are the filter's default values):

```php
add_filter( 'igmb_display_meta_box', 'myprefix_display_gallery_meta_box' );
/**
 * Filter to add the image gallery meta box to specific screens.
 *
 * @param   array   An array containing default values.
 * @return  array   An array containing new values.
 */
function myprefix_display_gallery_meta_box( $display ) {

    $display = array(
        'title'          => __( 'Image Gallery', 'my-text-domain' ), // meta box title
        'post_type'      => array( 'page' ), // array of post type slugs
        'post_id'        => array(), // array of post IDs (any post type)
        'page_template'  => array(), // array of page template file names
        'page_on_front'  => false, // display on front page (true or false)
        'page_for_posts' => false, // display on posts page (true or false)
        'priority'       => 'high', // meta box priority
    );

    return $display;

}
```

You can override all parameters at once or only include the ones you want to override in the filter. The following example would display the meta box only on the page assigned as Front page under Settings > Reading:

```php
add_filter( 'igmb_display_meta_box', 'myprefix_display_gallery_meta_box' );
/**
 * Filter to add the image gallery meta box to specific screens.
 *
 * @param   array   An array containing default values.
 * @return  array   An array containing new values.
 */
function myprefix_display_gallery_meta_box( $display ) {

    $display = array(
        'page_on_front' => true,
    );

    return $display;

}
```

### Front-end Usage

To retrieve the gallery attachment IDs use `get_post_meta` like this:

`$images = get_post_meta( $post_id, '_igmb_image_gallery_id', true );`

You could then loop through each ID and use `wp_get_attachment_link` or `wp_get_attachment_image` to display the images. However, a more flexible option involves the use of the `wp_prepare_attachment_for_js` function which returns an array of the attachment post object that can be used to extract specific information for each image.

The following example demonstrates how to display a basic list of images using data extracted from the `wp_prepare_attachment_for_js` array inside a theme's template file:

```php
<?php

// Attachment IDs
$images = get_post_meta( get_the_ID(), '_igmb_image_gallery_id', true );

// Display attachments
if ( $images ) {
    ?>
    <div class="attachment-images">
        <?php
        foreach( $images as $image ) {

            // Get attachment details
            $attachment = wp_prepare_attachment_for_js( $image );
            
            ?>
            <div>
                <a href="<?php echo $attachment['link']; ?>">
                    <img src="<?php echo $attachment['sizes']['medium']['url']; ?>" alt="<?php echo $attachment['alt']; ?>" />
                </a>
                <p><?php echo $attachment['caption']; ?></p>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
```

**Please note** that new image sizes added through `add_image_size` will not be automatically accessible for use by `wp_prepare_attachment_for_js`. You'll have to add the new image sizes using the `image_size_names_choose` filter. The following function illustrates how to add this in your theme.

```php
add_filter( 'image_size_names_choose', 'myprefix_custom_image_sizes' );
/**
 * Add new image sizes to list of default image sizes so
 * wp_prepare_attachment_for_js() can access them.
 *
 * @param   array   An array containing default image sizes and their names.
 * @return  array   Merged array containing new image sizes and their names.
 */
function myprefix_custom_image_sizes( $size_names ) {

    // Add new image sizes to array
    $new_size_names = array(
        'featured-image'    => __( 'Featured Image', 'my-text-domain' ),
        'portfolio-archive' => __( 'Portfolio Archive', 'my-text-domain' ),
    );

    // Combine the two arrays
    $size_names = array_merge( $new_size_names, $size_names );

    return $size_names;

}
```

Alternatively, this examples shows how to create a gallery using the built-in WordPress gallery shortcode:

```php
<?php

// Attachment IDs
$images = get_post_meta( get_the_ID(), '_igmb_image_gallery_id', true );

$wp_gallery = '[gallery ids="' . implode( ',', $images ) . '"]';

echo do_shortcode( $wp_gallery );
```

For more information, please refer to the following pages:

* [get_post_meta()](https://developer.wordpress.org/reference/functions/get_post_meta/)
* [wp_get_attachment_link()](https://developer.wordpress.org/reference/functions/wp_get_attachment_link/)
* [wp_get_attachment_image()](https://developer.wordpress.org/reference/functions/wp_get_attachment_image/)
* [wp_prepare_attachment_for_js()](https://developer.wordpress.org/reference/functions/wp_prepare_attachment_for_js/)
* [Gallery Shortcode](https://codex.wordpress.org/Gallery_Shortcode)

## Installation

### Using The WordPress Dashboard

1. Navigate to the 'Add New' Plugin Dashboard
2. Click on 'Upload Plugin' and select `image-gallery-metabox.zip` from your computer
3. Click on 'Install Now'
4. Activate the plugin on the WordPress Plugins Dashboard

### Using FTP

1. Extract `image-gallery-metabox.zip` to your computer
2. Upload the `image-gallery-metabox` directory to your `wp-content/plugins` directory
3. Activate the plugin on the WordPress Plugins Dashboard

## Screenshots

*Image gallery meta box*
![Image gallery meta box](wp-assets/screenshot-1.png?raw=true)

## Changelog

### 1.1.0

* Enable video file selection in the meta box.
* Fixed quick edit bug that deleted all selected media from meta box on save.

### 1.0.1

* Updated the admin CSS for better integration with Gutenberg editor.

### 1.0.0

* Initial release.
