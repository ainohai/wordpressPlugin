<?php
/**Abstract base class for creating custom post types **/
abstract class klabCustomPostType
{
    public static function initiate() {
        static::createPostType();
        static::setTaxonomies();
        static::createMetaboxes();
    }

    protected static function createPostTypeUsingConstructor($slug, $labels, $supports = null, $titleHint =null, $removedFields = null)
    {
        $postTypeConstructor = new KlabBaseFunctionalities_CustomPostTypeConstructor($slug);
        $postTypeConstructor->initiateUsingDefaultArgs($slug, $labels, $supports);
        /* Possible removable fields:
        'title'
        'editor' (content)
        'author'
        'thumbnail' (featured image) (current theme must also support Post Thumbnails)
        'excerpt'
        'trackbacks'
        'custom-fields'
        'comments' (also will see comment count balloon on edit screen)
        'revisions' (will store revisions)
        'page-attributes' (template and menu order) (hierarchical must be true)
        'post-formats' removes post formats, see Post Formats
        */
        if ($removedFields !== null) {
            $postTypeConstructor->removeInputFields($removedFields);
        }
        if($titleHint !== null) {
            $postTypeConstructor->klab_changeTitleHint($titleHint);
        }
    }

    protected static function createMetaBox ($metaBoxProps, $postTypeName) {
        $metaBoxConstructor = new Klab_metaBoxConstructor($metaBoxProps, $postTypeName);
        $metaBoxConstructor->createAndSaveMetas();

    }

    abstract protected static function createPostType();
    abstract protected static function setTaxonomies();
    abstract protected static function createMetaboxes();

}

?>