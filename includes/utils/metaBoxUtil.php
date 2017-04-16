<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 13.11.2016
 * Time: 14:14
 */
/**  */

//please note that metabox id:s are prefixed with post type name.
class metaBoxUtil
{
    const LABEL_DEFAULT_CLASS = 'klab_metaboxTitle';
    const INPUT_DEFAULT_CLASS = 'klab_metaboxInput';
    const TEXTAREA_DEFAULT_CLASS = 'theEditor';

    public static function echoNonce($nonceName) {
        wp_nonce_field( basename( __FILE__ ), $nonceName );
        return;
    }

/*(object) [
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
]*/
    public static function echoMetaBox ($post, $inputObject, $postTypeName) {

        $postMetaId = $postTypeName. "_" .$inputObject->inputId;
        $inputTitle = $inputObject->inputLabelText;

        $attributes = "";
        foreach ($inputObject->inputAttributes as $attributeName => $value) {
            // can't give id or name as attributes, they are the same as metaboxId.
            if ($attributeName !== 'id' || $attributeName !== 'name') {
                $attributes = $attributes . ' ' . $attributeName . ' = "' . $value . '"';
            }
        }
        if (!property_exists ($inputObject->inputAttributes, 'class')) {
             $attributes = $attributes . ' class = "' . STATIC::INPUT_DEFAULT_CLASS . '"';
        }

        if (strtolower($inputObject->inputAttributes->type === 'textarea')) {
            STATIC::echoTextAreaMetaBox ($post, $postMetaId, $attributes);
        }
        else {
            STATIC::echoTextMetaBox ($post, $postMetaId, $inputTitle, $attributes);
        }

        return;
    }

    public static function echoTextMetaBox ($post, $postMetaId, $inputTitle, $attributes) {

        $postMeta = get_post_meta( $post->ID );
        echo '
            <p>
            <label for="'. $postMetaId . '" class="'. STATIC::LABEL_DEFAULT_CLASS .'">' . $inputTitle . '</label></p>
            <input name="'. $postMetaId .'" id="'. $postMetaId .'" '. $attributes .'" value="';
        echo (isset ( $postMeta[$postMetaId] )  ? ($postMeta[$postMetaId][0]) : "");
        echo '" />
            </p>';
    }


    public static function echoTextAreaMetaBox ($post, $postMetaId, $attributes) {
        $postMeta = get_post_meta( $post->ID );
        $inputValue = isset ( $postMeta[$postMetaId] )  ? ($postMeta[$postMetaId][0]) : "";
        wp_editor( $inputValue, $postMetaId, $attributes );
    }

}