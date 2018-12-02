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