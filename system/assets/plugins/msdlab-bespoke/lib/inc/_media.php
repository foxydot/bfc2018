<?php
/*
 * some media handling.
 */
//if genesis get image returns nothing, then we should select a random image out of our set of images and use that.

//add_filter('genesis_get_image','msdlab_placeholder_image', 10, 6);

function msdlab_placeholder_image($output, $args, $id, $html, $url, $src){
    return $output;
}


if(!function_exists('get_attachment_id_from_src')){
    function get_attachment_id_from_src ($src) {
        global $wpdb;
        $reg = "/-[0-9]+x[0-9]+?.(jpg|jpeg|png|gif)$/i";
        $src1 = preg_replace($reg,'',$src);
        if($src1 != $src){
            $ext = pathinfo($src, PATHINFO_EXTENSION);
            $src = $src1 . '.' .$ext;
        }
        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$src'";
        $id = $wpdb->get_var($query);
        return $id;
    }
}
