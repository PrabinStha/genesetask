<?php
/**
 * Plugin Name: Puma API Master
 * Description: Custom API endpoint for retrieving products by category and author ID and adding Pagination to API.
 * Version: 1.0
 * Genese Task II
 */

defined('ABSPATH') || exit;

// Register the API endpoint
add_action('rest_api_init', 'puma_api_master_register_endpoint');

function puma_api_master_register_endpoint() {
	register_rest_route(
		'puma-api-master/v1',
		'/products',
		array(
			'methods'  => 'GET',  // Get Method
			'callback' => 'puma_api_master_get_products',
		)
	);
}

// Callback function to retrieve products
function puma_api_master_get_products($request) {
	// Retrieve parameters from the request
	$category  = $request->get_param('category');
	$author_id = $request->get_param('author_id');
	$page      = $request->get_param('page');
	$per_page  = $request->get_param('per_page');

	// Set default values for page and per_page if not provided
	$page     = !empty($page) ? absint($page) : 1;
	$per_page = !empty($per_page) ? absint($per_page) : 10;

	// Query arguments to retrieve products
	$query_args = array(
		'post_type'      => 'product', // Post Type Slug
		'post_status'    => 'publish',
		'paged'          => $page,
		'posts_per_page' => $per_page,
		'tax_query'      => array(),
		'author'         => $author_id,
	);

	// Add category filter if provided
	if (!empty($category)) {
		$query_args['tax_query'][] = array(
			'taxonomy' => 'product_category', // Taxonomy Slug
			'field'    => 'slug',
			'terms'    => $category,
		);
	}

	// Perform the query
	$products = new WP_Query($query_args);

	// Prepare the response
	$data = array(
		'page'           => $page,
		'per_page'       => $per_page,
		'total_pages'    => $products->max_num_pages,
		'total_products' => $products->found_posts,
		'products'       => array(),
	);

	// Loop through the products and add them to the response
	if ($products->have_posts()) {
		while ($products->have_posts()) {
			$products->the_post();

			$product_data = array(
				'id'    => get_the_ID(),
				'title' => get_the_title(),
				// Add more product data as needed
			);

			$data['products'][] = $product_data;
		}
	}

	// Restore original post data
	wp_reset_postdata();

	// Return the response
	$response = new WP_REST_Response($data);
	$response->header('X-WP-Total', (int) $products->found_posts);
	$response->header('X-WP-TotalPages', (int) $products->max_num_pages);
	return $response;
}



// Test the API Endpoint
http://yoursite.com/wp-json/puma-api-master/v1/products?category=your-category&author_id=
