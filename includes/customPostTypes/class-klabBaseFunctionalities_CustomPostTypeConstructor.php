<?php
/*
 *
 * User: aino
 * Date: 17.9.2016
 * Time: 11:22
 */

class KlabBaseFunctionalities_CustomPostTypeConstructor
{

    // default arguments.
    const DEFAULT_ARGS = [
        'public' => true,
        'exclude_from_search' => true,
        'show_in_nav_menus' => false,
        'query_var' => false,
        'delete_with_user' => false,
        'show_in_rest' => true,
        'supports' => array( 'title', 'editor', 'thumbnail')
    ];


    /**@param string $post_type Post type name. (max. 20 characters, cannot contain capital letters or spaces)*/
    private $post_type;
    /**@param array $args An array of arguments for register_post_type.*/
    private $args;
    /**@param input fields to be removed from default*/
    private $fieldsToBeRemoved;
    private $postTitleHint;


    /**
     * CustomPostTypeBuilder constructor.
     * @param string $post_type_name gives the name for post_type
     */
    public function __construct($post_type_name)
    {
        if( post_type_exists($post_type_name)) {
            throw new InvalidArgumentException("post type name exists");
        }
        $this->post_type = $post_type_name;
    }

    /**
     * @param $args
     * @param bool $useDefaultArgs if true uses default args as base.
     */
    public function init($args, $useDefaultArgs = true)
    {

        if ($useDefaultArgs) {
            //note that when using this as args override settings from defaul_args
            $args = array_replace(self::DEFAULT_ARGS, $args);
        }
        $this->args = $args;

        add_action( 'init', array (&$this, 'register_post_type_cb'));

        //moves excerpt on after title if supports excerpt:
        /*if (array_key_exists('supports', $this->args) &&
            in_array( 'excerpt', $this->args['supports'])) {
            $this->moveExcerptOnTop();
        }*/
    }

    public function initiateUsingDefaultArgs ($slug, $labels, $support = null)
    {
        $args = array('labels' => $labels,
                      'rewrite' => array( 'slug' => $slug )
                                            );
        if ( $support !== null ) {
            $args = array_merge($args, array('supports' => $support));
        }
        $this->init($args);
    }

    public function register_post_type_cb(){
        register_post_type($this->post_type, $this->args);
    }

    /**removes inputs from post edit admin view **/
    public function removeInputFields($arrayOfFields) {
        if ($arrayOfFields === null){
            return;
        }
        $this->fieldsToBeRemoved = $arrayOfFields;
        add_action('init', array($this, 'removeSupport'));


    }

    public function removeSupport() {
        foreach ($this->fieldsToBeRemoved as $fieldName) {
            remove_post_type_support($this->post_type, $fieldName);
        }
    }

    public function klab_changeTitleHint ( $postTitleHint) {
        if ($postTitleHint === null ){
            return;
        }
        $this->postTitleHint = $postTitleHint;
        add_filter( 'enter_title_here', array ($this, 'klab_changeTitleHint_cb' ));
    }

    public function klab_changeTitleHint_cb(){

        if  ( $this->post_type === get_current_screen()->post_type && $this->postTitleHint != null) {
            return $this->postTitleHint;
        }

    }

    public function moveExcerptOnTop() {
        add_action('admin_menu', array($this, 'removeExcerptMetaBox_cb'), 999);
        //adding new box for excerpt after title.
        add_action('edit_form_after_title', 'post_excerpt_meta_box');
    }

    public function removeExcerptMetaBox_cb() {
        remove_meta_box('postexcerpt', $this->post_type, 'normal');
    }

}

