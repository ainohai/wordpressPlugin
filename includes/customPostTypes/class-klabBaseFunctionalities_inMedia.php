<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 22.9.2016
 * Time: 22:09
 */
class KlabBaseFunctionalities_inMedia extends klabCustomPostType
{
    protected function createPostType()
    {
        $labels = array(
            'name'               => _x( 'Lab in media', 'post type general name', 'klab' ),
            'singular_name'      => _x( 'Lab in media', 'post type singular name', 'klab' ),
            'menu_name'          => _x( 'Lab in media', 'admin menu', 'klab' ),
            'name_admin_bar'     => _x( 'Lab in media', 'add new on admin bar', 'klab' ),
            'add_new'            => _x( 'Add New', 'Lab in media', 'klab' ),
            'add_new_item'       => __( 'Add New Item', 'klab' ),
            'new_item'           => __( 'New Item', 'klab' ),
            'edit_item'          => __( 'Edit Item', 'klab' ),
            'view_item'          => __( 'View All media items', 'klab' ),
            'all_items'          => __( 'All items', 'klab' ),
            'search_items'       => __( 'Search Lab in media items', 'klab' ),
            'parent_item_colon'  => __( 'Parent item:', 'klab' ),
            'not_found'          => __( 'No Lab in media items found.', 'klab' ),
            'not_found_in_trash' => __( 'No Lab in media items found in Trash.', 'klab' )
        );
        $postConstructor = new KlabBaseFunctionalities_CustomPostTypeConstructor('klab_labInMedia');
        $postConstructor->initiateUsingDefaultArgs('klab_labInMedia', $labels, array('title', 'editor', 'thumbnail', 'page-attributes'));
    }

    protected function setTaxonomies() {
        return;
    }
    protected function createMetaboxes() {
        return;
    }
}