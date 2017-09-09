<?php

/**
 * Created by PhpStorm.
 * User: aino
 * Date: 22.9.2016
 * Time: 22:09
 */
class KlabBaseFunctionalities_publication extends klabCustomPostType
{
    const SLUG = 'klab_publication';

    protected function createPostType()
    {
        $labels = array(
            'show_in_rest' => true,
            'name'               => _x( 'Publications', 'post type general name', 'klab' ),
            'singular_name'      => _x( 'Publication', 'post type singular name', 'klab' ),
            'menu_name'          => _x( 'Publications', 'admin menu', 'klab' ),
            'name_admin_bar'     => _x( 'Publications', 'add new on admin bar', 'klab' ),
            'add_new'            => _x( 'Add New', 'publication', 'klab' ),
            'add_new_item'       => __( 'Add New publication', 'klab' ),
            'new_item'           => __( 'New publication', 'klab' ),
            'edit_item'          => __( 'Edit publications', 'klab' ),
            'view_item'          => __( 'View publications', 'klab' ),
            'all_items'          => __( 'All publications', 'klab' ),
            'search_items'       => __( 'Search publications', 'klab' ),
            'parent_item_colon'  => __( 'Parent publication:', 'klab' ),
            'not_found'          => __( 'No publications found.', 'klab' ),
            'not_found_in_trash' => __( 'No publications found in Trash.', 'klab' )
        );



        $supports = array( 'title','authors', 'page-attributes');
        $titleHint = 'Insert publication title';

        parent::createPostTypeUsingConstructor(static::SLUG, $labels, $supports, $titleHint);

    }


    protected function setTaxonomies() {
        return;
    }
    protected function createMetaboxes() {

        /*"25081398": {
            "uid": "25081398",
            "pubdate": "2014 Jul-Aug",
            "epubdate": "2014 May 20",
            "source": "Prog Cardiovasc Dis",
            "authors": [
                {
                    "name": "Villarraga HR",
                    "authtype": "Author",
                    "clusterid": ""
                }
            ],
            "lastauthor": "Nkomo VT",
            "title": "Cardio-oncology: role of echocardiography.",
            "sorttitle": "cardio oncology role of echocardiography",
            "volume": "57",
            "issue": "1",
            "pages": "10-8",
            "lang": [
                "ENG"
            ],
            "nlmuniqueid": "0376442",
            "issn": "0033-0620",
            "essn": "1873-1740",
            "pubtype": [
                "Journal Article",
                "Review"
            ],
            "recordstatus": "PubMed - indexed for MEDLINE",
            "pubstatus": "4",
            "articleids": [
                {
                    "idtype": "pubmed",
                    "idtypen": 1,
                    "value": "25081398"
                }
            ],
            "history": [
                {
                    "pubstatus": "entrez",
                    "date": "2014/08/02 06:00"
                },
                {
                    "pubstatus": "pubmed",
                    "date": "2014/08/02 06:00"
                },
                {
                    "pubstatus": "medline",
                    "date": "2014/10/24 06:00"
                }
            ],
            "references": [
            ],
            "attributes": [
                "Has Abstract"
            ],
            "pmcrefcount": 5,
            "fulljournalname": "Progress in cardiovascular diseases",
            "elocationid": "doi: 10.1016/j.pcad.2014.05.002",
            "doctype": "citation",
            "srccontriblist": [
            ],
            "booktitle": "",
            "medium": "",
            "edition": "",
            "publisherlocation": "",
            "publishername": "",
            "srcdate": "",
            "reportnumber": "",
            "availablefromurl": "",
            "locationlabel": "",
            "doccontriblist": [
            ],
            "docdate": "",
            "bookname": "",
            "chapter": "",
            "sortpubdate": "2014/01/01 00:00",
            "sortfirstauthor": "Villarraga HR",
            "vernaculartitle": ""
        },*/

        $publicationDetailsMetaboxProps = (object) [
            'metaboxTitle' => 'Publication details',
            'metaboxId' => 'klab_publicationDetails',
            'nonceName' => 'publicationDetailsNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputId' => 'uid',
                        'inputLabelText' => 'Pubmed uid',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'pubdate',
                        'inputLabelText' => 'Publication date',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'source',
                        'inputLabelText' => 'Pubmed source',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'authors',
                        'inputLabelText' => 'Authors',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    /*(object) [
                        'inputId' => 'publicationDetails',
                        'inputLabelText' => 'Publication details',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],*/
                    (object) [
                        'inputId' => 'volume',
                        'inputLabelText' => 'Volume',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'issue',
                        'inputLabelText' => 'Issue',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'pages',
                        'inputLabelText' => 'Pages',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'fulljournalname',
                        'inputLabelText' => 'Full journal name',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'booktitle',
                        'inputLabelText' => 'Book title',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'medium',
                        'inputLabelText' => 'Medium',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'edition',
                        'inputLabelText' => 'Edition',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'publisherlocation',
                        'inputLabelText' => 'Publisher location',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],
                    (object) [
                        'inputId' => 'publishername',
                        'inputLabelText' => 'Publisher name',
                        'inputAttributes' => (object) [
                            'type' => 'text',
                        ]
                    ],

                )
        ];

        $abstractMetaBoxProps = (object) [
            'metaboxTitle' => 'Publication abstract',
            'metaboxId' => 'abstractMetaBox',
            'nonceName' => 'publicationAbstractNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'textarea',
                        ],
                        'inputId' => 'abstract',
                        'inputLabelText' => 'Abstract'
                    ]
                )
        ];

        $selectedPubsMetaBoxProps = (object) [
            'metaboxTitle' => 'Selected publications',
            'metaboxId' => 'selectedPubMetaBox',
            'nonceName' => 'selectedPubNonce',
            'inputFields' =>
                array(
                    (object) [
                        'inputAttributes' => (object) [
                            'type' => 'checkbox',
                        ],
                        'inputId' => 'selectedPublication',
                        'inputLabelText' => 'this is a selected publication'
                    ]
                )
        ];

        parent::createMetaBox($selectedPubsMetaBoxProps, STATIC::SLUG);
        parent::createMetaBox($publicationDetailsMetaboxProps, STATIC::SLUG);

        add_action( 'rest_api_init', function(){
            $array = array("authors", "source", "uid", "pubdate", "volume", "issue", "pages", "fulljournalname", "booktitle", "medium", "edition", "publisherlocation", "publishername");

            error_log("pubsapi");
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
        });

    }

}