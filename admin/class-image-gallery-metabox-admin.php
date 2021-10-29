<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.alexisvillegas.com
 * @since      1.0.0
 *
 * @package    Image_Gallery_Metabox
 * @subpackage Image_Gallery_Metabox/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Image_Gallery_Metabox
 * @subpackage Image_Gallery_Metabox/admin
 * @author     Alexis J. Villegas <alexis@ajvillegas.com>
 */
class Image_Gallery_Metabox_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		// Define suffix for debugging.
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . "css/image-gallery-metabox-admin{$suffix}.css",
			array(),
			$this->version,
			'all'
		);

	}

	/**
	 * Register, localize and enqueue the JavaScript for the image gallery meta box.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		// Define suffix for debugging.
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_register_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . "js/image-gallery-metabox{$suffix}.js",
			array( 'jquery', 'jquery-ui-sortable' ),
			$this->version,
			false
		);

		wp_localize_script(
			$this->plugin_name,
			'image_gallery_metabox',
			array(
				'add_title'         => __( 'Add Images to Gallery', 'image-gallery-metabox' ),
				'add_button'        => __( 'Add to Gallery', 'image-gallery-metabox' ),
				'edit_title'        => __( 'Edit or Change Image', 'image-gallery-metabox' ),
				'edit_button'       => __( 'Update Image', 'image-gallery-metabox' ),
				'link_edit_title'   => __( 'Edit/Change Image', 'image-gallery-metabox' ),
				'link_remove_title' => __( 'Remove Image', 'image-gallery-metabox' ),
				'site_url'          => get_site_url(),
			)
		);

		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Register the image gallery meta box.
	 *
	 * @since 1.0.0
	 */
	public function add_image_gallery_meta_box() {

		// Get post ID.
		$post_id = isset( $_GET['post'] ) ? $_GET['post'] : isset( $_POST['post_ID'] ); // phpcs:ignore

		// Get Front page ID.
		$frontpage_id = get_option( 'page_on_front' );

		// Get Posts page ID.
		$postspage_id = get_option( 'page_for_posts' );

		// Get page template.
		$page_template = get_post_meta( $post_id, '_wp_page_template', true );

		// Define default values.
		$default = apply_filters(
			'igmb_display_meta_box',
			array(
				'title'          => __( 'Image Gallery', 'image-gallery-metabox' ),
				'post_type'      => array( 'page' ),
				'post_id'        => array(),
				'page_template'  => array(),
				'page_on_front'  => false,
				'page_for_posts' => false,
				'priority'       => 'high',
			)
		);

		// Sanitize default filter values.
		$title     = ! empty( $default['title'] ) ? $default['title'] : __( 'Image Gallery', 'image-gallery-metabox' );
		$post_type = ! empty( $default['post_type'] ) ? $default['post_type'] : array();
		$ids       = ! empty( $default['post_id'] ) ? $default['post_id'] : array();
		$templates = ! empty( $default['page_template'] ) ? $default['page_template'] : array();
		$frontpage = isset( $default['page_on_front'] ) ? $default['page_on_front'] : false;
		$postspage = isset( $default['page_for_posts'] ) ? $default['page_for_posts'] : false;
		$priority  = ! empty( $default['priority'] ) ? $default['priority'] : 'high';

		// Add meta box to specific post types.
		if ( $post_type ) {
			add_meta_box(
				'image-gallery-meta-box',
				$title,
				array( $this, 'image_gallery_meta_box' ),
				$post_type,
				'normal',
				$priority
			);
		}

		// Add meta box to specific post IDs.
		if ( $ids && in_array( $post_id, $ids, true ) ) {
			foreach ( $ids as $value ) {
				// Get post type.
				$post_type = get_post_type( $value );

				add_meta_box(
					'image-gallery-meta-box',
					$title,
					array( $this, 'image_gallery_meta_box' ),
					$post_type,
					'normal',
					$priority
				);
			}
		}

		// Add meta box to specific page templates.
		if ( $templates && in_array( $page_template, $templates, true ) ) {
			add_meta_box(
				'image-gallery-meta-box',
				$title,
				array( $this, 'image_gallery_meta_box' ),
				'page',
				'normal',
				$priority
			);
		}

		// Add meta box to Front page or Posts page.
		if (
			( $frontpage && true === $frontpage && $post_id === $frontpage_id ) ||
			( $postspage && true === $postspage && $post_id === $postspage_id )
		) {
			add_meta_box(
				'image-gallery-meta-box',
				$title,
				array( $this, 'image_gallery_meta_box' ),
				'page',
				'normal',
				$priority
			);
		}

	}

	/**
	 * Define the image gallery meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param object $post The WP_Post object.
	 */
	public function image_gallery_meta_box( $post ) {

		wp_nonce_field( basename( __FILE__ ), 'igmb_image_gallery_nonce' );
		$gallery_stored_meta = get_post_meta( $post->ID, '_igmb_image_gallery_id', true );

		// Meta box markup.
		include plugin_dir_path( __FILE__ ) . 'partials/image-gallery-metabox-markup.php';

	}

	/**
	 * Save the image gallery meta box values.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_meta_box_values( $post_id ) {

		// Check save status.
		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = isset( $_POST['igmb_image_gallery_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['igmb_image_gallery_nonce'] ), basename( __FILE__ ) ) ? 'true' : 'false';

		// Exit depending on save status.
		if ( $is_autosave || $is_revision || ! $is_valid_nonce || ! isset( $_POST['igm_honeypot'] ) ) {
			return;
		}

		// Check for input and sanitize/save if needed.
		if ( isset( $_POST['_igmb_image_gallery_id'] ) ) {
			update_post_meta( $post_id, '_igmb_image_gallery_id', array_map( 'absint', $_POST['_igmb_image_gallery_id'] ) );
		} else {
			delete_post_meta( $post_id, '_igmb_image_gallery_id' );
		}

	}

}
