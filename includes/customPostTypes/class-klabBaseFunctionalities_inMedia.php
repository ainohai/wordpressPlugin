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
        $supports = array('title', 'editor', 'page-attributes', 'thumbnail');
        parent::createPostTypeUsingConstructor(static::SLUG, $labels, $supports, null, null);
        $action = 'add_meta_boxes_'. static::SLUG;
        add_action( $action, array($this, 'klab_add_fetch_button_cb') );
        add_action( 'wp_ajax_klab_in_media_fetch', array($this,'klab_in_media_fetch' ));
        add_action('save_post',array($this, 'klab_save_in_media_cb'));


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
                    ],
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMedia_site_name',
                        'inputLabelText' => 'Site name'
                    ],
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMedia_image',
                        'inputLabelText' => 'Image url'
                    ]/*,
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMedia_audio',
                        'inputLabelText' => 'Audio url'
                    ],
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMedia_audio:type',
                        'inputLabelText' => 'Audio type'
                    ],
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMedia_video',
                        'inputLabelText' => 'Video url'
                    ],
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMedia_video:type',
                        'inputLabelText' => 'Video type'
                    ],
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMedia_video:width',
                        'inputLabelText' => 'Video width'
                    ],

                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ],
                        'inputId' => 'klabInMedia_video:height',
                        'inputLabelText' => 'Video height'
                    ],*/

                )
        ];

        parent::createMetaBox($inMediaMetaBoxProps, static::SLUG);

    }

    public function klab_add_fetch_button_cb(){

        add_meta_box('fetchDataMetaBox', 'Fetch news data', array($this, 'fetchDataMetaBox_cb'), static::SLUG, 'side', 'high');
        ?>
        <?php

    }

    function fetchDataMetaBox_cb() {
        ?>
        <div>
            <form>
                <?php wp_nonce_field( 'klab_in_media_fetch', 'klab_in_media_fetch_nonce' ); ?>
                News url: <input id ="klab_in_media_fetch_url" type="text" name="klab_in_media_fetch_url" value="">
                <button class="button button-primary button-large" id="klab_inMediaFetchButton">
                    Fech data</button>
            </form>
        </div> <?php
    }

    function klab_in_media_fetch()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'klab_in_media_fetch')) {

            die('Security check');

        } else {

            $url = $_POST['url'];
            $response = wp_remote_get($url);

            if (is_wp_error($response)) {
                http_response_code(404);
                die();
            }

            $http_code = wp_remote_retrieve_response_code($response);
            http_response_code($http_code);

            if ($http_code != 200) {
                error_log("not 200");
                die();
            }

            libxml_use_internal_errors(true);
            $xml = new DOMDocument("1.0", "UTF-8");
            $xml->loadHTML('<?xml encoding="utf-8" ?>' . $response['body']);
            libxml_use_internal_errors(false);
            $xml->getElementsByTagName('meta');

            $resultArray = [];

            foreach ($xml->getElementsByTagName('meta') as $metaElement) {
                $property = $metaElement->getAttribute("property");
                $value = $metaElement->getAttribute("content");

                $resultArray[$property] = $value;
            }

            error_log("send json");
            error_log($http_code);
            http_response_code($http_code);
            wp_send_json($resultArray);


        }
    }

    function klab_save_in_media_cb () {
        global $post;
        if (!isset($post) || $post->post_type != static::SLUG) {
            return;
        }
        if (has_post_thumbnail($post)) {
            return;
        }
        error_log ("save cb");
        error_log($_POST['klab_lab_in_media_klabInMedia_image']);
        if (isset($_POST['klab_lab_in_media_klabInMedia_image'])){
            $this->generate_Featured_Image($_POST['klab_lab_in_media_klabInMedia_image'], $post->ID, $post->post_title);
        }

    }

    //Snippet from https://wordpress.stackexchange.com/questions/40301/how-do-i-set-a-featured-image-thumbnail-by-image-url-when-using-wp-insert-post
    /**
     * Downloads an image from the specified URL and attaches it to a post as a post thumbnail.
     *
     * @param string $file    The URL of the image to download.
     * @param int    $post_id The post ID the post thumbnail is to be associated with.
     * @param string $desc    Optional. Description of the image.
     * @return string|WP_Error Attachment ID, WP_Error object otherwise.
     */
    function generate_Featured_Image( $file, $post_id, $desc ){
        // Set variables for storage, fix file filename for query strings.
        preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
        if ( ! $matches ) {
            return new WP_Error( 'image_sideload_failed', __( 'Invalid image URL' ) );
        }

        $file_array = array();
        $file_array['name'] = basename( $matches[0] );

        // Download file to temp location.
        $file_array['tmp_name'] = download_url( $file );

        // If error storing temporarily, return the error.
        if ( is_wp_error( $file_array['tmp_name'] ) ) {
            return $file_array['tmp_name'];
        }

        // Do the validation and storage stuff.
        $id = media_handle_sideload( $file_array, $post_id, $desc );

        // If error storing permanently, unlink.
        if ( is_wp_error( $id ) ) {
            @unlink( $file_array['tmp_name'] );
            return $id;
        }
        return set_post_thumbnail( $post_id, $id );

    }
}