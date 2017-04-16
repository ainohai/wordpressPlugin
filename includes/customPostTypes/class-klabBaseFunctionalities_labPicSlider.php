<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 22.9.2016
 * Time: 22:09
 */
class KlabBaseFunctionalities_lab_slideshow extends klabCustomPostType
{
    const SLUG = 'klab_lab_slideshow';
    const POST_TITLE_HINT = 'Insert title here';

    protected static function createPostType()
    {
        $labels = array(
            'name'               => _x( 'Lab slideshow pics', 'post type general name', 'klab' ),
            'singular_name'      => _x( 'Lab slideshow pic', 'post type singular name', 'klab' ),
            'menu_name'          => _x( 'Lab slideshow pics', 'admin menu', 'klab' ),
            'name_admin_bar'     => _x( 'Lab slideshow pics', 'add new on admin bar', 'klab' ),
            'add_new'            => _x( 'Add New', 'book', 'klab' ),
            'add_new_item'       => __( 'Add New slideshow pic', 'klab' ),
            'new_item'           => __( 'New slideshow pic', 'klab' ),
            'edit_item'          => __( 'Edit slideshow pics', 'klab' ),
            'view_item'          => __( 'View slideshow pics', 'klab' ),
            'all_items'          => __( 'All slideshow pics', 'klab' ),
            'search_items'       => __( 'Search slideshow pics', 'klab' ),
            'parent_item_colon'  => __( 'Parent slideshow pic:', 'klab' ),
            'not_found'          => __( 'No slideshow pics found.', 'klab' ),
            'not_found_in_trash' => __( 'No slideshow pics found in Trash.', 'klab' )
        );

        $supports = array( 'title', 'thumbnail', 'page-attributes');
        parent::createPostTypeUsingConstructor(static::SLUG, $labels, $supports, static::POST_TITLE_HINT, null);

    }

    protected static function createMetaboxes() {
        $labSlideshowOrderMetaBoxProps = (object) [
            'metaboxTitle' => 'Slide order',
            'metaboxId' => 'labSlideShowOrder',
            'nonceName' => 'labSlideShowOrderNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'number',
                            'min' => '0'
                        ],
                        'inputId' => 'labSlideShowOrder',
                        'inputLabelText' => 'Order number which defines the order of slides'
                    ]
                )
        ];

        parent::createMetaBox($labSlideshowOrderMetaBoxProps, STATIC::SLUG);

    }


    protected static function setTaxonomies()
    {
        return;
    }

}