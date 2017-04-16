<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 22.9.2016
 * Time: 22:09
 */
class KlabBaseFunctionalities_research_topic extends klabCustomPostType
{
    const SLUG = 'klab_research_topic';
    const POST_TITLE_HINT = "Insert title for research topic.";

    protected static function createPostType()
    {
        $labels = array(
            'name'               => _x( 'Research topics', 'post type general name', 'klab' ),
            'singular_name'      => _x( 'Research topic', 'post type singular name', 'klab' ),
            'menu_name'          => _x( 'Research topics', 'admin menu', 'klab' ),
            'name_admin_bar'     => _x( 'Research topics', 'add new on admin bar', 'klab' ),
            'add_new'            => _x( 'Add New', 'research topic', 'klab' ),
            'add_new_item'       => __( 'Add New research topic', 'klab' ),
            'new_item'           => __( 'New research topic', 'klab' ),
            'edit_item'          => __( 'Edit research topics', 'klab' ),
            'view_item'          => __( 'View research topics', 'klab' ),
            'all_items'          => __( 'All research topics', 'klab' ),
            'search_items'       => __( 'Search research topica', 'klab' ),
            'parent_item_colon'  => __( 'Parent research topic:', 'klab' ),
            'not_found'          => __( 'No research topic found.', 'klab' ),
            'not_found_in_trash' => __( 'No research topic found in Trash.', 'klab' )
        );
        $supports = array( 'title', 'editor', 'thumbnail', 'page-attributes');
        parent::createPostTypeUsingConstructor(static::SLUG, $labels, $supports, static::POST_TITLE_HINT);

        add_action( 'edit_form_after_title', 'KlabBaseFunctionalities_research_topic::klab_researchTopic_topicForEditor_cb' );


    }

    protected static function setTaxonomies() {
        return;
    }
    protected static function createMetaboxes() {

        $detailMetaBoxProps = (object) [
            'metaboxTitle' => 'Research topic in detail',
            'metaboxId' => 'details',
            'nonceName' => 'researchTopicDetailsNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'textarea',
                        ],
                        'inputId' => 'klabResearchDescription',
                        'inputLabelText' => 'Description'
                    ]
                )
        ];

        parent::createMetaBox($detailMetaBoxProps, STATIC::SLUG);
        return;
    }

    public static function klab_researchTopic_topicForEditor_cb() {
            global $post;
            if(!empty($post) && STATIC::SLUG === $post->post_type) {

                echo '<div class="editorTitle>"<h4>Abstract for dummies:</h4></div>';
            }
        }


}