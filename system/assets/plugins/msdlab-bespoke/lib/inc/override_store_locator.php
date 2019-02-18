<?php
add_filter( 'wpsl_meta_box_fields', 'bfc_meta_box_fields' );

function bfc_meta_box_fields( $meta_fields ) {
    unset($meta_fields['Location']['address']);
    unset($meta_fields['Location']['address2']);
    return $meta_fields;
}
add_filter ( 'wpsl_info_window_template', 'bfc_obfuscate_email', 20);
add_filter ( 'wpsl_listing_template', 'bfc_obfuscate_email', 20);

function bfc_obfuscate_email($template){
    $pattern = '/<span><strong>Email<\/strong>: (.*?)<\/span>/m';
    $replace = '<span><strong>Contact</strong>: <a href="/contact?coven_id=<%= id %>"><%= store %></a></span>';
    $template = preg_replace($pattern,$replace,$template);

    return $template;
}

add_filter( 'gform_field_value_recipient_email', 'bfc_get_recipient_email');

function bfc_get_recipient_email(){
    if($coven_id = $_GET['coven_id']){
        $email = get_post_meta($coven_id,'wpsl_email',true);
        return $email;
    } else {
        return 'kate@msdlab.com';
    }
}

add_filter( 'gform_field_value_recipient_name', 'bfc_get_recipient_name');

function bfc_get_recipient_name(){
    if($coven_id = $_GET['coven_id']){
        $title = get_the_title($coven_id);
        return $title;
    } else {
        return 'Seeker Coordinator';
    }
}

add_filter( 'wpsl_store_meta', 'custom_store_meta', 10, 2 );

function custom_store_meta( $store_meta, $store_id ) {

    $terms = wp_get_post_terms( $store_id, 'wpsl_store_category' );
    ts_data($terms);
    if ( $terms ) {
        if ( !is_wp_error( $terms ) ) {
            if ( isset( $_GET['filter'] ) && $_GET['filter'] ) {
                $filter_ids = explode( ',', $_GET['filter'] );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->term_id, $filter_ids ) ) {
                        $cat_marker = msdlab_get_marker( $term );

                        if ( $cat_marker ) {
                            $store_meta['categoryMarkerUrl'] = $cat_marker;
                        }
                    }
                }
            } else {
                $store_meta['categoryMarkerUrl'] = msdlab_get_marker( $terms[0] );
            }
        }
    }
    return $store_meta;
}

function msdlab_get_marker($term){
    return '/';
}

add_filter('post_type_labels_wpsl_stores','store_to_covens');

function store_to_covens($labels){
    $labels->name = 'Coven Locator';
    $labels->singular_name = 'Coven';
    $labels->add_new = 'New Coven';
    $labels->add_new_item = 'Add New Coven';
    $labels->edit_item = 'Edit Coven';
    $labels->new_item = 'New Coven';
    $labels->view_item = 'View Covens';
    $labels->view_items = 'View Covens';
    $labels->search_items = 'Search Covens';
    $labels->not_found = 'No Covens found';
    $labels->not_found_in_trash = 'No Covens found in trash';
    $labels->parent_item_colon = '';
    $labels->all_items = 'All Covens';
    $labels->archives = 'All Covens';
    $labels->attributes = 'Coven Attributes';
    $labels->insert_into_item = 'Insert into coven';
    $labels->uploaded_to_this_item = 'Uploaded to this coven';
    $labels->featured_image = 'Featured Image';
    $labels->set_featured_image = 'Set featured image';
    $labels->remove_featured_image = 'Remove featured image';
    $labels->use_featured_image = 'Use as featured image';
    $labels->filter_items_list = 'Filter coven list';
    $labels->items_list_navigation = 'Covens list navigation';
    $labels->items_list = 'Covens list';
    $labels->item_published = 'Coven published.';
    $labels->item_published_privately = 'Coven published privately.';
    $labels->item_reverted_to_draft = 'Coven reverted to draft.';
    $labels->item_scheduled = 'Coven scheduled.';
    $labels->item_updated = 'Coven updated.';
    $labels->menu_name = 'Coven Locator';
    $labels->name_admin_bar = 'Coven';
    return $labels;
}

add_filter('taxonomy_labels_wpsl_store_category','store_tax_to_coven_tax');

function store_tax_to_coven_tax($labels){
    $labels->name = 'Coven Categories';
    $labels->singular_name = 'Coven Category';
    $labels->search_items = 'Search Coven Categories';
    $labels->popular_items = '';
    $labels->all_items = 'All Coven Categories';
    $labels->parent_item = 'Parent Coven Category';
    $labels->parent_item_colon = 'Parent Coven Category:';
    $labels->edit_item = 'Edit Coven Category';
    $labels->view_item = 'View Category';
    $labels->update_item = 'Update Coven Category';
    $labels->add_new_item = 'Add New Coven Category';
    $labels->new_item_name = 'New Coven Category Name';
    $labels->separate_items_with_commas = '';
    $labels->add_or_remove_items = '';
    $labels->choose_from_most_used = '';
    $labels->not_found = 'No categories found.';
    $labels->no_terms = 'No categories';
    $labels->items_list_navigation = 'Categories list navigation';
    $labels->items_list = 'Categories list';
    $labels->most_used = 'Most Used';
    $labels->back_to_items = '← Back to Categories';
    $labels->menu_name = 'Coven Categories';
    $labels->name_admin_bar = 'Coven Category';
    $labels->archives = 'All Coven Categories';
    return $labels;
}

add_action( 'init', 'register_clan_tax' );


function register_clan_tax(){
    $labels = array(
        'name' => _x( 'Clans', 'clan' ),
        'singular_name' => _x( 'Clan', 'clan' ),
        'search_items' => _x( 'Search clans', 'clan' ),
        'popular_items' => _x( 'Popular clans', 'clan' ),
        'all_items' => _x( 'All clans', 'clan' ),
        'parent_item' => _x( 'Parent clan', 'clan' ),
        'parent_item_colon' => _x( 'Parent clan:', 'clan' ),
        'edit_item' => _x( 'Edit clan', 'clan' ),
        'update_item' => _x( 'Update clan', 'clan' ),
        'add_new_item' => _x( 'Add new clan', 'clan' ),
        'new_item_name' => _x( 'New clan name', 'clan' ),
        'separate_items_with_commas' => _x( 'Separate clans with commas', 'clan' ),
        'add_or_remove_items' => _x( 'Add or remove clans', 'clan' ),
        'choose_from_most_used' => _x( 'Choose from the most used clans', 'clan' ),
        'menu_name' => _x( 'Clans', 'clan' ),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => false,
        'hierarchical' => true, //we want a "category" style taxonomy, but may have to restrict selection via a dropdown or something.

        'rewrite' => array('slug'=>'clan','with_front'=>false),
        'query_var' => true
    );

    register_taxonomy( 'clan', 'wpsl_stores', $args );
}