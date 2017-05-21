<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 22.9.2016
 * Time: 22:09
 */
class KlabBaseFunctionalities_lab_member extends klabCustomPostType
{
    const SLUG = 'klab_lab_member';
    const POST_TITLE_HINT = 'Insert name here';

    protected function createPostType()
    {
        $labels = array(
            'name'               => _x( 'Lab members', 'post type general name', 'klab' ),
            'singular_name'      => _x( 'Lab member', 'post type singular name', 'klab' ),
            'menu_name'          => _x( 'Lab members', 'admin menu', 'klab' ),
            'name_admin_bar'     => _x( 'Lab members', 'add new on admin bar', 'klab' ),
            'add_new'            => _x( 'Add New', 'book', 'klab' ),
            'add_new_item'       => __( 'Add New lab member', 'klab' ),
            'new_item'           => __( 'New lab member', 'klab' ),
            'edit_item'          => __( 'Edit lab members', 'klab' ),
            'view_item'          => __( 'View lab members', 'klab' ),
            'all_items'          => __( 'All lab members', 'klab' ),
            'search_items'       => __( 'Search lab members', 'klab' ),
            'parent_item_colon'  => __( 'Parent lab member:', 'klab' ),
            'not_found'          => __( 'No lab members found.', 'klab' ),
            'not_found_in_trash' => __( 'No lab members found in Trash.', 'klab' )
        );

        $supports = array( 'title', 'thumbnail', 'page-attributes');
        parent::createPostTypeUsingConstructor(static::SLUG, $labels, $supports, static::POST_TITLE_HINT, null);

    }

    protected function createMetaboxes() {
        $labMemberMetaBoxProps = (object) [
            'metaboxTitle' => 'Lab member title and current position',
            'metaboxId' => 'labMemberTitle',
            'nonceName' => 'labMemberTitleNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabMemberTitle',
                        'inputLabelText' => 'Title'
                    ],
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabMemberCurrentPosition',
                        'inputLabelText' => 'Current position (for alumni)'
                    ]
                )
        ];

        $labMemberDescMetaBoxProps = (object) [
            'metaboxTitle' => 'Lab member description',
            'metaboxId' => 'description',
            'nonceName' => 'labMemberDescNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'textarea',
                        ],
                        'inputId' => 'klabMemberDescription',
                        'inputLabelText' => 'Description'
                    ]
                )
        ];
        parent::createMetaBox($labMemberMetaBoxProps, STATIC::SLUG);
        parent::createMetaBox($labMemberDescMetaBoxProps, STATIC::SLUG);

    }


    protected function setTaxonomies()
    {
        add_action('init', array($this, 'create_labMemberPosition_taxonomy_cb'), 0);
        return;
    }

    public function create_labMemberPosition_taxonomy_cb()
    {

        $labels = array(
            'name' => _x('Lab Member Category', 'taxonomy general name'),
            'singular_name' => _x('Lab Member Category', 'taxonomy singular name'),
            'search_items' => __('Search Lab Member Categories'),
            'popular_items' => __('Popular Lab Member Categories'),
            'all_items' => __('All Lab Member Categories'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Lab Member Category'),
            'update_item' => __('Update Lab Member Category'),
            'add_new_item' => __('Add New Lab Member Category'),
            'new_item_name' => __('New Lab Member Category Name'),
            'separate_items_with_commas' => __('Separate lab member categories with commas'),
            'add_or_remove_items' => __('Add or remove lab member categories'),
            'choose_from_most_used' => __('Choose from the most used lab member categories'),
            'menu_name' => __('Lab Member Categories'),
        );

        register_taxonomy('labMemberPosition', 'klab_lab_member', array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'labMemberPosition'),
        ));
    }
}