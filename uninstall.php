<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://www.alexisvillegas.com
 * @since      1.0.0
 *
 * @package    Image_Gallery_Metabox
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// If user doesn't have the right permissions, then exit.
if ( ! current_user_can( 'activate_plugins' ) ) {
	return;
}

// If action didn't originate on the proper page, then exit.
if ( __FILE__ != WP_UNINSTALL_PLUGIN ) {
	return;
}

// Delete plugin options from database.
if ( is_multisite() ) {
	
	global $wpdb;
	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
		
	if ( $blogs ) {
		
		foreach( $blogs as $blog ) {
			
			switch_to_blog( $blog['blog_id'] );
				
				// Delete post meta data
				$posts = get_posts( array( 'posts_per_page' => -1 ) );

		        foreach ( $posts as $post ) {
		            $post_meta = get_post_meta( $post->ID );
		            delete_post_meta( $post->ID, '_igmb_image_gallery_id' );
		        }
				
			restore_current_blog();
		}
	}
	
} else {
	
	// Delete post meta data
	$posts = get_posts( array( 'posts_per_page' => -1 ) );

    foreach ( $posts as $post ) {
        $post_meta = get_post_meta( $post->ID );
        delete_post_meta( $post->ID, '_igmb_image_gallery_id' );
    }
	
}
