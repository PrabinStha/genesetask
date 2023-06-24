<?php
/**
 * Create a custom taxonomy and write a function to fetch 10 terms from the taxonomy.
 * Genese Task III
 */

//create a custom taxonomy name it Puma Product Types for posts

function create_puma_product_type_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Puma Product Types', 'taxonomy general name', 'puma' ),
        'singular_name'              => _x( 'Puma Product Type', 'taxonomy singular name', 'puma' ),
        'search_items'               => __( 'Search Puma Product Types', 'puma' ),
        'popular_items'              => __( 'Popular Puma Product Types', 'puma' ),
        'all_items'                  => __( 'All Puma Product Types', 'puma' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Puma Product Type', 'puma' ),
        'update_item'                => __( 'Update Puma Product Type', 'puma' ),
        'add_new_item'               => __( 'Add New Puma Product Type', 'puma' ),
        'new_item_name'              => __( 'New Puma Product Type Name', 'puma' ),
        'separate_items_with_commas' => __( 'Separate Puma Product Types with commas', 'puma' ),
        'add_or_remove_items'        => __( 'Add or remove Puma Product Types', 'puma' ),
        'choose_from_most_used'      => __( 'Choose from the most used Puma Product Types', 'puma' ),
        'not_found'                  => __( 'No Puma Product Types found.', 'puma' ),
        'menu_name'                  => __( 'Puma Product Types', 'puma' ),
    );

    $args = array(
        'hierarchical'      => True, // Set to false if you don't want hierarchical taxonomy like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'puma-product-type' ), // Slug of the taxonomy
    );

    register_taxonomy( 'puma_product_type', array( 'product' ), $args );
}

// hook into the init action and call puma_product_type taxonomies when it fires

add_action( 'init', 'create_puma_product_type_taxonomy', 0 );


// function to fetch 10 terms from the taxonomy

function fetch_puma_product_terms() {
    $terms = get_terms( array(
        'taxonomy'   => 'puma_product_type',
        'hide_empty' => false,
        'number'     => 10,  // Fetch 10 terms from the taxonomy.
    ) );

    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {   // check for wp error
        echo '<ul>';
        foreach ( $terms as $term ) {
            echo '<li>' . esc_html( $term->name ) . '</li>'; // sanitize the term name before displaying
        }
        echo '</ul>';
    } else {
        echo esc_html( 'No terms found to display.' );
    }
}
