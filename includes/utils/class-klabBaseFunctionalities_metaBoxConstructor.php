<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 17.11.2016
 * Time: 22:04
 */
class Klab_metaBoxConstructor
{
    private $metaBoxProps;
    private $postTypeName;
    private $pageTemplateName;
    private $nonceName;

    public function __construct ($metaBoxProps, $postTypeName, $pageTemplateName = null) {

        $this->metaBoxProps = $metaBoxProps;
        $this->postTypeName = $postTypeName;
        if (!property_exists ($metaBoxProps, 'nonceName')) {
            throw new InvalidArgumentException("Metabox needs to have nonce.");
        }
        $this->nonceName = $metaBoxProps->nonceName;
        $this->pageTemplateName = $pageTemplateName;
    }

    /** adds metabox on the screen adnd saves the result */
    public function createAndSaveMetas()
    {
        $addBoxesHookName = 'add_meta_boxes_' . $this->postTypeName;
        $savePostHookName = 'save_post_' . $this->postTypeName;

        add_action($addBoxesHookName, array($this, 'klabMetaConstructor_addMetabox'));
        add_action($savePostHookName, array($this, 'klabMetaConstructor_saveMetaBox_cb'));
    }

/*    public function createAndSaveExcerptAfterTitle () {
        add_action( 'admin_menu' , 'klab_remove_normal_excerpt' );
    }

    function my_add_excerpt_meta_box( $post_type ) {
        if ( in_array( $post_type, array( 'post', 'page' ) ) ) {
            add_meta_box(
                'postexcerpt', __( 'Excerpt' ), 'post_excerpt_meta_box', $post_type, 'test', // change to something other then normal, advanced or side
                'high'
            );
        }
    }
add_action( 'add_meta_boxes', 'my_add_excerpt_meta_box' );

    function my_run_excerpt_meta_box() {
        # Get the globals:
        global $post, $wp_meta_boxes;

        # Output the "advanced" meta boxes:
        do_meta_boxes( get_current_screen(), 'test', $post );

    }

add_action( 'edit_form_after_title', 'my_run_excerpt_meta_box' );

    function my_remove_normal_excerpt() {
        remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
    }
*/

    public function klabMetaConstructor_addMetabox() {
        /*example props */
        /*$labMemberMetaBoxProps = (object) [
            'metaboxTitle' => 'Lab member info',
            'metaboxId' => 'klab_labMemberInfo',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabMemberName',
                        'inputLabelText' => 'Name',
                    ],
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'textarea',
                        ],
                        'inputId' => 'klabMemberDescription',
                        'inputLabelText' => 'Description',
                    ]
                )
        ];*/
        global $post;
        $currentPageTemplate = get_post_meta( $post->ID, '_wp_page_template', true );
        if ($this ->postTypeName === 'page'
            && ($this->pageTemplateName != null
                && $currentPageTemplate !== $this->pageTemplateName)) {

            return;
        }
        $metaBoxId = $this->postTypeName . "_" . $this->metaBoxProps->metaboxId;
        add_meta_box($metaBoxId, $this->metaBoxProps->metaboxTitle, array($this, 'klabMetaConstructor_addMetaboxes_cb'), $this->postTypeName);

    }

    public function klabMetaConstructor_addMetaboxes_cb($post)
    {

        MetaboxUtil::echoNonce($this->nonceName);
        foreach ($this->metaBoxProps->inputFields as $input) {

            MetaboxUtil::echoMetaBox($post, $input, $this->postTypeName);

        }

    }

    public function klabMetaConstructor_saveMetaBox_cb() {

        global $post;
        if (empty($post)) {
            return;
        }
        $post_id = $post->ID;
        $currentPageTemplate = get_post_meta( $post->ID, '_wp_page_template', true );
        error_log("saving post meta");
        if ($this ->postTypeName === 'page'
            && $this->pageTemplateName != null
            && $currentPageTemplate != $this->pageTemplateName) {

            return;
        }

        foreach ($this->metaBoxProps->inputFields as $input) {

            $isCheckbox = $input->inputAttributes->type === 'checkbox';
            $this->klab_saveBoxMeta($post_id, $this->nonceName, $input->inputId, $this->postTypeName, $isCheckbox);

        }
    }

    /**
     * Saves the custom meta input
     */
    private static function klab_saveBoxMeta( $post_id, $nonceName, $metaIdWoPrefix, $postTypeName, $isCheckbox) {

        $metaId = $postTypeName . "_" . $metaIdWoPrefix;
        // Checks save status
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
        $is_valid_nonce = ( isset( $_POST[ $nonceName ] ) && wp_verify_nonce( $_POST[ $nonceName ], basename( __FILE__ ) ) ) ? 'true' : 'false';

        // Exits script depending on save status
        if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
            return;
        }
        // Checks for input and sanitizes/saves if needed
        if( isset( $_POST[$metaId] ) ) {

            //update_post_meta( $post_id, $metaId, sanitize_text_field( $_POST[$metaId] ) );
            update_post_meta( $post_id, $metaId, stripslashes( $_POST[$metaId] ) );
        }
        else if ($isCheckbox) {
            delete_post_meta( $post_id, $metaId);
        }

    }
}