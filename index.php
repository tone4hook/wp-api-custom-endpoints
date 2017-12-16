<?php
/**
* Plugin Name: WP-API Custom Endpoints
* Plugin URI: https://github.com/tone4hook
* Description: This plugin creates custom wp-api endpoints
* Version: 1.0.0
* Author: tone4hook
* Author URI: https://github.com/tone4hook
* License: MIT
*/

// WP-API Custom Endpoints -> wace ;)

require( 'wace_query.php' ); // posts query function

require( 'wace_utils.php' ); // utilities function

// used to limit query to these categories
// example: $included_categories = array(1,4,7,9);
$included_categories = array();

// get random posts
function wace_random_posts() {
	$args = array(
		'orderby'   => 'rand',
		'cat' => $included_categories,
		'posts_per_page' => 20
	);
	$data = wace_query_posts($args, 'rand');
	return $data;
} // /wace_random_posts

// get posts by category
function wace_category_posts($param) {
	$args = array(
		'cat' => $param['id'],
		'posts_per_page' => 15
	);
	$data = wace_query_posts($args, 'cat');
	return $data;
} // /wace_category_posts

// get posts by tag
function wace_tag_posts($param) {
	$args = array(
		'tag_id' => $param['id'],
		'cat' => $included_categories,
		'posts_per_page' => 15
	);
	$data = wace_query_posts($args, 'tag');
	return $data;
} // /wace_tag_posts

// get post
function wace_post($param) {
	$args = array(
		'p' => $param['id']
	);
	$data = wace_query_posts($args, 'post');
	return $data;
} // /wace_post

// search posts
function wace_search($param) {
	$args = array(
		's' => str_replace("-"," ",$param['term']),
		'cat' => $included_categories,
		'posts_per_page' => 20
	);
	$data = wace_query_posts($args, 'rand');
	return $data;
} // /wace_post

// get post categories and tags
function wace_categories_tags () {
	$args = array(
		'orderby'   => 'rand',
		'cat' => $included_categories,
		'posts_per_page' => 4
	);
	$data = wace_query_posts($args, 'home');
	return $data;
}

// register API routes
add_action( 'rest_api_init', function() {

	register_rest_route( 'wace/v2', '/random/', array(
		'methods' => 'GET',
		'callback' => 'wace_random_posts'
	));

	register_rest_route( 'wace/v2', '/cat/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'wace_category_posts',
		'args' => array(
	    'id' => array(
	      'validate_callback' => function($param, $request, $key) {
	        return is_numeric( $param );
	      }
	    ),
	  ),
	));

	register_rest_route( 'wace/v2', '/tag/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'wace_tag_posts',
		'args' => array(
	    'id' => array(
	      'validate_callback' => function($param, $request, $key) {
	        return is_numeric( $param );
	      }
	    ),
	  ),
	));

	register_rest_route( 'wace/v2', '/post/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'wace_post',
		'args' => array(
	    'id' => array(
	      'validate_callback' => function($param, $request, $key) {
	        return is_numeric( $param );
	      }
	    ),
	  ),
	));

	register_rest_route( 'wace/v2', '/search=(?P<term>[a-zA-Z0-9-]+)', array(
		'methods' => 'GET',
		'callback' => 'wace_search'
	));

	register_rest_route( 'wace/v2', '/home', array(
		'methods' => 'GET',
		'callback' => 'wace_categories_tags'
	));

}); // /add_action