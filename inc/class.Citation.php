<?php

namespace JLS\Citations;

class Citation {
    public static $postTypeName = 'jls_citation';
    public static $citationTypes = array(
        'field_id'  => 'citation_type',
        'options'   => array(
            'Book'                                  => 'book',
            'Book Chapter'                          => 'book_chapter',
            'Book (Electronic)'                     => 'book_electronic',
            'Book Chapter (Electronic)'             => 'book_chapter_electronic',
            'Unpublished Conference Proceedings'    => 'conference',
            'Journal Article'                       => 'journal',
            'Magazine Article'                      => 'magazine',
            'Newspaper Article'                     => 'newspaper'
        )
    );
    public static $TemplateRenderer;
    public static $CitationMeta;
    public static $File;

    function __construct( $file ){
        self::$File = $file;

        require_once( dirname( self::$File ) . '/inc/class.TemplateRenderer.php' );
        self::$TemplateRenderer = new \TemplateRenderer( dirname( self::$File ) . '/views/templates' );

        add_action( 'init', array( __CLASS__, 'createPostType' ) );
        add_action( 'save_post', array( __CLASS__, 'savePost' ) );
        add_action( 'admin_init', array( __CLASS__, 'registerAdminScripts' ) );
        add_action( 'init', array( __CLASS__, 'registerScripts' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'loadAdminScripts' ) );
        add_action( 'enqueue_scripts', array( __CLASS__, 'loadScripts' ) );
        add_action( 'wp_ajax_get_citation_fields', array( __CLASS__, 'ajaxSwitchFields' ) );
    }

    /**
     * Registers the custom post type
     * @mvc Controller
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function createPostType() {
        $labels = array(
            'name' => 'Citations',
            'singular_name' => 'Citation',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Citation',
            'edit_item' => 'Edit Citation',
            'new_item' => 'New Citation',
            'all_items' => 'All Citation',
            'view_item' => 'View Citation',
            'search_items' => 'Search Citations',
            'not_found' =>  'No citations found',
            'not_found_in_trash' => 'No citations found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Citations'
          );

          $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => plugin_dir_url( self::$File ) . '/img/reference.png',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'citation', 'with_front' => false ),
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 8,
            'register_meta_box_cb' => array( __CLASS__, 'addMetaBoxes' ),
            'supports' => array( 'title', 'thumbnail', 'excerpt', 'sticky' )
          );

          register_post_type( self::$postTypeName, $args );

    }

    /**
     * Adds meta boxes for the custom post type
     * @mvc Controller
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function addMetaBoxes() {
        add_meta_box(
            self::$postTypeName . '-details',
            'Citation Details',
            array( __CLASS__, 'markupMetaBoxes' ),
            self::$postTypeName,
            'normal',
            'high'
        );
    }

    /**
     * Builds the markup for all meta boxes
     * @mvc Controller
     * @author Jenny Sharps <jsharps85@gmail.com>
     * @param object $post
     * @param array $box
     */

    /**
     * Register admin styles & scripts
     * @mvc Controller
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function registerAdminScripts() {
        wp_register_style( 'citations-admin-css', plugins_url( 'css/admin-style.css', self::$File ) );
        wp_register_script( 'citations-admin-js', plugins_url( 'js/admin-script.js', self::$File ) );
    }

    /**
     * Load admin styles & scripts
     * @mvc Controller
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function loadAdminScripts() {
        global $post_type;
        if( self::$postTypeName == $post_type ) {
            wp_enqueue_style( 'citations-admin-css' );
            wp_enqueue_script( 'citations-admin-js' );
        }
    }

    /**
     * Register frontend styles & scripts
     * @mvc Controller
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function registerScripts() {
        wp_register_style( 'citations-css', plugins_url( 'css/style.css', self::$File ) );
    }

    /**
     * Load frontend styles & scripts
     * @mvc Controller
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function loadScripts() {
        wp_enqueue_style( 'citations-css' );
    }

    /**
    * Load new inputs based on chosen field type
    * @mvc Controller
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public static function ajaxSwitchFields() {
        $response = array( );
        $response['result'] = "";
        $type = $_REQUEST['chosen_type'];
        $post_id = $_REQUEST['post_id'];

        if ( !$type  || !$post_id ) {
            $response['result'] .= !$type ? "field type not specified " : '';
            $response['result'] .= !$post_id ? "post id not specified" : '';
        } else {
            $response['markup'] = self::buildInputGroups( $_REQUEST['chosen_type'], $_REQUEST['post_id'] );
        }

        echo json_encode( $response );
        die();
    }

    /**
    * Builds the markup displayed on page load for all meta boxes
    * @mvc Controller
    * @author Jenny Sharps <jsharps85@gmail.com>
    * @param object $post
    * @param array $box
    */
    public static function markupMetaBoxes( $post, $box ) {

        $meta = get_post_meta( $post->ID );
        echo wp_nonce_field( -1, self::$postTypeName . '_noncename', true, false ); ?>

        <div class="<?php echo self::$postTypeName; ?>-custom-fields">

            <?php

            $type_field_id = self::$citationTypes['field_id'];
            $selected_type = isset( $meta[$type_field_id][0] ) ? $meta[$type_field_id][0] : '';
            ${$type_field_id . '_options'} = array(
                'label'         => 'Type',
                'field_id'      => $type_field_id,
                'placeholder'   => 'Select Type',
                'options'       => self::$citationTypes['options'],
                'current'      => $selected_type
            );
            ?>

            <div id="<?php echo $type_field_id; ?>_input">
            <?php echo self::$TemplateRenderer->renderInput( 'select', ${$type_field_id . '_options'} ); ?>
            </div>

            <div id="citation_data">
                <?php
                /*Render specific citation fields*/
                if( $selected_type ) {
                    echo self::buildInputGroups( $selected_type, $post->ID );
                }
                ?>
            </div>

        </div>

    <?php }

    /**
    * Build citation group based on speific type
    * @mvc Controller
    * @param string $citation_type
    * @param array  $current_meta
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public static function buildInputGroups( $citation_type, $post_id ) {

        self::$CitationMeta = get_post_meta( $post_id, 'citation' );
        self::$CitationMeta = self::$CitationMeta ? self::$CitationMeta : array( array() );

        $return = '';
        switch( $citation_type ) {
            case 'book':
                $return = self::getBookFields();
                break;
            case 'book_chapter':
                $return = self::getBookFields( FALSE, TRUE );
                break;
            case 'book_electronic':
                $return = self::getBookFields( TRUE );
                break;
            case 'book_chapter_electronic':
                $return = self::getBookFields( TRUE, TRUE );
                break;
            case 'conference':
                $return = self::getConferenceFields( $citation_type );
                break;
            case 'journal':
                $return = self::getJournalFields ( $citation_type );
                break;
            case 'magazine':
                break;
            case 'newspaper':
                break;
        }
        return $return;
    }

    /**
    * Get fields for book citation type
    * @mvc Controller
    * @param string $citation_meta
    * @param boolean $electronic
    * @param boolean $chapter
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public static function getBookFields( $electronic = FALSE, $chapter = FALSE ) {

            $markup  = self::getAuthorFieldGroup();
            $markup .= self::getAuthorFieldGroup( 'co_author', 'Co-Author Info' );
            $markup .= self::getTextField( 'year' );

            if( $chapter ) {
                $markup .= self::getTextField( 'chapter_title', 'Title of Chapter' );
            }

            $markup .= self::getTextField( 'title', 'Title of Book' );

            if( $chapter ) {
                $markup .= self::getTextField( 'section', 'Chapter or Section #' );
            }

            if( !$electronic ){
                $markup .= self::getTextField( 'location' );
                $markup .= self::getTextField( 'publisher' );
            }

            if( $electronic ) {
                $markup .= self::getTextField( 'url', 'URL' );
            }

            return $markup;

    }

    /**
    * Get fields for conference citation type
    * @mvc Controller
    * @param string $citation_meta
    * @param string  $field
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public static function getConferenceFields( $field ) {
            $title_label = $field == 'conference' ? ucfirst( $field ) . ' Paper' : ucfirst( $field );

            $markup  = self::getAuthorFieldGroup();
            $markup .= self::getAuthorFieldGroup( 'co_author', 'Co-Author Info' );
            $markup .= self::getTextField( 'year', 'Year' );
            $markup .= self::getTextField( 'month', 'Month' );
            $markup .= self::getTextField( 'title', 'Title of ' . $title_label );
            $markup .= self::getTextField( 'description' );
            $markup .= self::getTextField( 'location' );

            return $markup;

    }

    /**
    * Get fields for journal citation type
    * @mvc Controller
    * @param string $citation_meta
    * @param string  $field
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public static function getJournalFields( $field ) {

            $title_label = $field == 'conference' ? ucfirst( $field ) . ' Paper' : ucfirst( $field );

            $markup  = self::getAuthorFieldGroup();
            $markup .= self::getAuthorFieldGroup( 'co_author', 'Co-Author Info' );
            $markup .= self::getTextField( 'year', 'Year' );

            $markup .= self::getTextField( 'title', 'Title of Article' );
            $markup .= self::getTextField( 'journal_title', 'Title of ' . $title_label );
            $markup .= self::getTextField( 'volume' );
            $markup .= self::getTextField( 'issue' );
            $markup .= self::getTextField( 'pages' );

            return $markup;

    }

    /**
    * Get author default author field group
    * @mvc Controller
    * @param array  $current_meta
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public static function getAuthorFieldGroup( $field_id = 'author', $label = 'Author Info' ) {

            $author_count = isset( self::$CitationMeta[0][$field_id] ) ? count( self::$CitationMeta[0][$field_id] ) : 1;

            $author_markup = "<div class='{$field_id}_groups'>";
            $author_markup .= "<label>{$label}</label>";

            for( $x = 0; $x < $author_count; $x++ ) {
                $author_meta_item = !empty( self::$CitationMeta[0][$field_id][$x] ) ? self::$CitationMeta[0][$field_id][$x] : '';
                $author_markup .= self::renderAuthorFields( $x, $author_meta_item, $field_id );
            }

            $author_markup .= '</div>';
            return $author_markup;

    }

    /**
    * Get author fields markup
    * @mvc Controller
    * @param string $citation_type
    * @param array  $current_meta
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public static function renderAuthorFields( $item, $author_meta = NULL, $field_id = 'author' ) {

        $author_options = array(
            'field_id'      => "citation[$field_id][$item]",
            'fields'        => array(
                'last' => array(
                    'type'          => 'text',
                    'placeholder'   => 'Last Name',
                    'current'       => isset( $author_meta['last'] ) ? $author_meta['last'] : '',
                ),
                'first' => array(
                    'type'          => 'text',
                    'placeholder'   => 'First Name',
                    'current'       => isset( $author_meta['first'] ) ? $author_meta['first']  : '',
                ),
                'middle' => array(
                    'type'          => 'text',
                    'placeholder'   => 'MI',
                    'current'       => isset( $author_meta['middle'] ) ? $author_meta['middle'] : '',
                    'size'          => 'small'
                )
            ),
        );
        return '<div class="field_wrap author_group">' . self::$TemplateRenderer->renderInputGroup( $author_options ) .'</div>';
    }

    /**
    * Get publication citation type fields
    * @mvc Controller
    * @param string $citation_meta
    * @param array  $field
    * @param string label
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public  static function getTextField( $field, $label = NULL ) {

            $label = $label ? $label : ucfirst( $field );
            $current = isset( self::$CitationMeta[0]["text_{$field}"] ) ? self::$CitationMeta[0]["text_{$field}"] : '';
            $options = array(
                'label'         => $label,
                'field_id'      => "citation[text_{$field}]",
                'current'       => $current,
            );
            $input_type = 'text';

            switch( $field ) {
                case 'year':
                    $options['placeholder'] = 'YYYY';
                    $options['size'] = 'small';
                    break;
                case 'title':
                case 'journal_title':
                case 'chapter_title':
                case 'url':
                    $options['size'] = 'large';
                    break;
                case 'location':
                    $options['placeholder'] = 'ie: Miami, FL';
                    break;
                case 'section':
                    break;
                case 'description':
                    $options['placeholder'] = 'ie: Paper presented at the GIS Conference';
                    $options['size'] = 'large';
                    break;

            }

            $markup = '<div class="field_wrap ' . $field . '_field">';
            $markup .= self::$TemplateRenderer->renderInput( 'text', $options );
            $markup .= '</div>';

            return $markup;

    }

    /**
     * Saves values of the the custom post type's citation fields
     * @mvc Controller
     * @param int $postID
     * @param object $revision
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function savePost( $postID, $revision = NULL ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

        if ( isset( $_POST[self::$postTypeName . '_noncename'] ) ) {

            if( !wp_verify_nonce( $_POST[self::$postTypeName . '_noncename'] )  || !current_user_can( 'edit_post', $postID )) {
                return;
            }

            if( isset( $_POST[self::$citationTypes['field_id']] ) ) {
                update_post_meta( $postID, self::$citationTypes['field_id'], $_POST[self::$citationTypes['field_id']] );
            }
            if( isset( $_POST['citation'] ) ) {
                update_post_meta( $postID, 'citation', $_POST['citation'] );
            }
        }
    }

}