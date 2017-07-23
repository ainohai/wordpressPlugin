<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class KlabBaseFunctionalities_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'remodal/css',  plugin_dir_url( __FILE__ ) . '../bower_components/remodal/dist/remodal.css', array() );
        wp_enqueue_style( 'remodal-default-theme/css',  plugin_dir_url( __FILE__ ) . '../bower_components/remodal/dist/remodal-default-theme.css', array() );

    }

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/klabNewsLink-admin.js', array( 'jquery' ), $this->version, false );

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/klabPubFunctionalities-admin.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( $this->plugin_name, 'session', array(
            'current_user_id' => get_current_user_id(),
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
        ));
        wp_enqueue_script( 'remodal/js',  plugin_dir_url( __FILE__ ) . '../bower_components/remodal/dist/remodal.min.js', array( 'jquery' ), $this->version, false );

    }

	public function remove_top_level_menus () {
        remove_menu_page( 'edit.php' );                   //Posts
        remove_menu_page( 'edit-comments.php' );          //Comments
    }

    public function init_pub_rest_api(){
        $array = array("authors", "source", "uid", "pubdate", "volume", "issue", "pages", "fulljournalname", "booktitle", "medium", "edition", "publisherlocation", "publishername");

        foreach ($array as $fieldName) {
            register_rest_field( 'klab_publication',
                'klab_publication_'.$fieldName,
                array(
                    'get_callback'    => function($object, $field_name ){
                        return get_post_meta( $object[ 'id' ], $field_name, true );
                    },
                    'update_callback' => function($value, $object, $field_name ){
                        return update_post_meta( $object->ID, $field_name, $value );
                    },
                    'schema'          => null,
                )
            );
        }
    }


    public function klab_addWelcomePanel () {
        $authorName = "klefstrom+j";

        echo '<div class="welcome-panel-content">';
        echo '<h2> Fetch publications </h2>';
        echo '<p class="about-description"> Fetch publications from pubmed and add them to the site. </p>';
        echo '<div class="welcome-panel-column">';
        echo '<h3>Search by author</h3>';
        echo '<input class="klab_publication_author" value="'. $authorName .'"/><br/>';
        echo '<button class="button button-primary button-hero load-customize hide-if-no-customize" id="klab_fetchPublications">Fetch publications</button>';

        echo '<p>If you need to re-fetch a publication for some reason, delete it from the publications and empty the trash bin. The fetcher has not been tested using very large resultsets. </p>';
        echo '</div></div>';

        echo '<div data-remodal-id="modal" class="klab_fetchPublicationsModal">
	          <button data-remodal-action="close" class="remodal-close"></button>
              <h2 class="klab_modalTitle">Fetching new data from pubmed</h2>  
              <div class="klab_modalContents"></div>
                </div>';
    }


}
