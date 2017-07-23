<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 22.9.2016
 * Time: 22:09
 */
class KlabBaseFunctionalities_inMedia extends klabCustomPostType
{
    const SLUG = 'klab_lab_in_media';

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
        $supports = array('title', 'editor', 'page-attributes');
        parent::createPostTypeUsingConstructor(static::SLUG, $labels, $supports, null, null);
        add_action( 'edit_form_after_title', array($this, 'add_fetch_button_cb') );

    }

    protected function setTaxonomies() {
        return;
    }
    protected function createMetaboxes() {
        $inMediaMetaBoxProps = (object) [
            'metaboxTitle' => 'News in media',
            'metaboxId' => 'newsInMedia',
            'nonceName' => 'newsInMediaNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMediaUrl',
                        'inputLabelText' => 'Url'
                    ]

                )
        ];

        $inMediaDescMetaBoxProps = (object) [
            'metaboxTitle' => 'News in media excerpt',
            'metaboxId' => 'klabInMediaDesc',
            'nonceName' => 'klabInMediaDescNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMediaTitle',
                        'inputLabelText' => 'News title'
                    ]
                )
        ];

        $inMediaMediaUrlMetaBoxProps = (object) [
            'metaboxTitle' => 'News in media excerpt',
            'metaboxId' => 'klabInMediaMediaUrlBox',
            'nonceName' => 'klabInMediaMediaUrlNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMediaMediaUrl',
                        'inputLabelText' => 'External media url'
                    ]
                )
        ];

        parent::createMetaBox($inMediaMetaBoxProps, static::SLUG);
        parent::createMetaBox($inMediaDescMetaBoxProps, static::SLUG);
        parent::createMetaBox($inMediaMediaUrlMetaBoxProps, static::SLUG);
    }

    public function add_fetch_button_cb(){
        echo '<button id="inMediaFetchButton" name="inMediaFetchButton" value="Fetch news via url"/>
        
}
}