<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 22.9.2016
 * Time: 22:09
 */
class KlabBaseFunctionalities_intraLinks extends klabCustomPostType
{
    const SLUG = 'klab_intra_links';
    const POST_TITLE_HINT = 'Insert link title here';

    protected function createPostType()
    {
        $labels = array(
            'name'               => _x( 'Intra links', 'post type general name', 'klab' ),
            'singular_name'      => _x( 'Intra links', 'post type singular name', 'klab' ),
            'menu_name'          => _x( 'Intra links', 'admin menu', 'klab' ),
            'name_admin_bar'     => _x( 'Intra links', 'add new on admin bar', 'klab' ),
            'add_new'            => _x( 'Add New', 'book', 'klab' ),
            'add_new_item'       => __( 'Add New Intra link', 'klab' ),
            'new_item'           => __( 'New Intra link', 'klab' ),
            'edit_item'          => __( 'Edit Intra link', 'klab' ),
            'view_item'          => __( 'View Intra link', 'klab' ),
            'all_items'          => __( 'All Intra links', 'klab' ),
            'search_items'       => __( 'Search Intra links', 'klab' ),
            'parent_item_colon'  => __( 'Parent Intra link:', 'klab' ),
            'not_found'          => __( 'No Intra links found.', 'klab' ),
            'not_found_in_trash' => __( 'No Intra links found in Trash.', 'klab' )
        );
        $supports = array( 'title', 'page-attributes');
        parent::createPostTypeUsingConstructor(static::SLUG, $labels, $supports, static::POST_TITLE_HINT, null);
    }

    protected function setTaxonomies() {
        return;
    }
    protected function createMetaboxes() {
        $urlMetaBoxProps = (object) [
            'metaboxTitle' => 'Link url',
            'metaboxId' => 'linkUrl',
            'nonceName' => 'linkUrlNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabLinkUrl',
                        'inputLabelText' => 'Url'
                    ]
                )
        ];
        parent::createMetaBox($urlMetaBoxProps, static::SLUG);
        return;
    }
}