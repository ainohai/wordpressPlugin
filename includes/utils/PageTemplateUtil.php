<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 26.11.2016
 * Time: 8:11
 */

/**temporary code, to be refactored**/
class PageTemplateUtil
{
    const BIO_PAGE_TEMPLATE_NAME = "template-bio.php";
    const POST_TYPE_NAME = "page";
    const THREE_COLUMN_PAGE_TEMPLATE_NAME = "template-three-column.php";

    public static function addTemplateMetaboxes()
    {
        STATIC::bioMetaBoxes();
		STATIC::threeColumnMetaBoxes();

        add_action( 'edit_form_after_title', 'PageTemplateUtil::klab_pageTemplate_topicForEditor_cb' );

    }

    public static function threeColumnMetaBoxes() {
        $secondColumnMetaBoxProps = (object)[
            'metaboxTitle' => 'Second column',
            'metaboxId' => 'secondColumn',
            'nonceName' => 'secondColumnNonce',
            'inputFields' =>
                array(
                    (object)[
                        'inputAttributes' => (object)[
                            'type' => 'textarea',
                        ],
                        'inputId' => 'secondColumn',
                        'inputLabelText' => 'Second column text'
                    ]
                )
        ];

        $thirdColumnMetaBoxProps = (object)[
            'metaboxTitle' => 'Third column',
            'metaboxId' => 'thirdColumn',
            'nonceName' => 'thirdColumnNonce',
            'inputFields' =>
                array(
                    (object)[
                        'inputAttributes' => (object)[
                            'type' => 'textarea',
                        ],
                        'inputId' => 'thirdColumn',
                        'inputLabelText' => 'Third column text'
                    ]
                )
        ];

        $secondColMetaBox = new Klab_metaBoxConstructor($secondColumnMetaBoxProps, static::POST_TYPE_NAME, static::THREE_COLUMN_PAGE_TEMPLATE_NAME);
        $secondColMetaBox->createAndSaveMetas();

        $thirdColMetaBox = new Klab_metaBoxConstructor($thirdColumnMetaBoxProps, static::POST_TYPE_NAME, static::THREE_COLUMN_PAGE_TEMPLATE_NAME);
        $thirdColMetaBox->createAndSaveMetas();

    }

    public static function bioMetaBoxes() {

            $bioCVMetaBoxProps = (object)[
                'metaboxTitle' => 'CV',
                'metaboxId' => 'CV',
                'nonceName' => 'CVNonce',
                'inputFields' =>
                    array(
                        (object)[
                            'inputAttributes' => (object)[
                                'type' => 'textarea',
                            ],
                            'inputId' => 'cv',
                            'inputLabelText' => 'CV'
                        ]
                    )
            ];

            $metaBoxConstructor = new Klab_metaBoxConstructor($bioCVMetaBoxProps, static::POST_TYPE_NAME, static::BIO_PAGE_TEMPLATE_NAME);
            $metaBoxConstructor->createAndSaveMetas();

    }

    public static function klab_pageTemplate_topicForEditor_cb() {
        global $post;
        $currentPageTemplate = get_post_meta( $post->ID, '_wp_page_template', true );
        if(!empty($post) && STATIC::POST_TYPE_NAME === $post->post_type && $currentPageTemplate === STATIC::BIO_PAGE_TEMPLATE_NAME) {

            echo '<div class="editorTitle"><h3>Bio:</h3></div>';
        }

        if (!empty($post) && STATIC::POST_TYPE_NAME === $post->post_type && $currentPageTemplate === STATIC::THREE_COLUMN_PAGE_TEMPLATE_NAME) {
            echo '<div class="editorTitle"><h3>First column:</h3></div>';
        }
    }


}