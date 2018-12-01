<?php
add_filter( 'wpsl_meta_box_fields', 'bfc_meta_box_fields' );

function bfc_meta_box_fields( $meta_fields ) {
    unset($meta_fields['Location']['address']);
    unset($meta_fields['Location']['address2']);
    return $meta_fields;
}