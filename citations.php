<?php
/*
Plugin Name: Citations
Plugin URI: http://www.jennysharps.com
Description: Add citation post type and custom fields
Version: 1.0
Author: Jenny Sharps
Author URI: http://www.jennysharps.com
*/


require_once( dirname( __FILE__ ) . '/autoloader.php' );
require_once( dirname( __FILE__ ) . '/wrapper-functions.php' );

$citations = new JLS\Citations\Citation( __FILE__ );

/*
function save_citation_postdata( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

  if ( !wp_verify_nonce( $_POST['myplugin_noncename'], 'test-citations-options' ) )
      return;

  if ( !current_user_can( 'edit_post', $post_id ) )
        return;

  $mydata = $_POST['_test_kw_field'];

  update_post_meta( $post_id, '_slider_order', $mydata );
}
add_action( 'save_post', 'save_citation_postdata' );


function main_citations_admin_css() {
	global $post_type;

	if ( get_post_type() == 'main_citations' || $post_type == 'main_citations' ) {

		echo "<link type='text/css' rel='stylesheet' href='" . get_bloginfo('stylesheet_directory') . "/library/css/admin.css' />";
	}
}
add_action('admin_head', 'main_citations_admin_css');


function retrieve_citation_slider_images() {
	global $post;

	$args = array(
		'meta_key' => '_slider_order',
		'post_type' => 'main_citations',
		'meta_query' => array(
			array(
				'key' => '_main_citations_citations_slider_image_thumbnail_id',
				'value' => NULL,
				'compare' => '!='
			)
		)
	);

	$slider_query = new WP_Query($args);
	$attachments = array();

	if( $slider_query->have_posts() ) {
		while ($slider_query->have_posts()) : $slider_query->the_post();

			$order = get_post_meta( $post->ID, '_slider_order', true);

			$retrieved = MultiPostThumbnails::get_the_post_thumbnail_url('main_citations', 'citations_slider_image');

			$attachments[$order] = array(
				'img_url'=>$retrieved[0],
				'link'=>get_permalink(),
				'title'=>get_the_title());

			endwhile;
		}
		wp_reset_query();

		asort($attachments, SORT_NUMERIC);
		return $attachments;
}*/