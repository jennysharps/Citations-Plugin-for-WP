<?php

namespace JLS\Citations;

class Citation {
    public static $postTypeName = 'jls_citation';
    public static $citationTypes = array(
        'field_id'  => 'citation_type',
        'options'   => array(
            'Book'                      => 'book',
            'Book Chapter'              => 'book_chapter',
            'Book (Electronic)'         => 'book_electronic',
            'Book Chapter (Electronic)' => 'book_chapter_electronic',
            'Conference Paper'          => 'conference',
            'Journal Article'           => 'journal',
            'Magazine Article'          => 'magazine',
            'Newspaper Article'         => 'newspaper'
        )
    );
    public static $TemplateRenderer;
        
    function __construct( $file ){
        add_action( 'admin_init', array( __CLASS__, 'createPostType' ) );
        add_action( 'save_post', array( __CLASS__, 'savePost' ) );
        
        require_once( dirname( $file ) . '/inc/class.TemplateRenderer.php' );
        self::$TemplateRenderer = new \TemplateRenderer( dirname( $file ) . '/views/templates' );
    }
    
    /**
     * Registers the custom post type
     * @mvc Controller
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function createPostType() {
        register_post_type( self::$postTypeName, // (http: //codex.wordpress.org/Function_Reference/register_post_type)
	 	 // let's now add all the options for this post type
		array(
                    'labels' => array(
			'name' => __('Citations', 'post type general name'), // This is the Title of the Group
			'all_items' => __('All Citations'),
			'singular_name' => __('Citation', 'post type singular name'), // This is the individual type
			'add_new' => __('Add New', 'custom post type item'), // The add new menu item
			'add_new_item' => __('Add New Citation'), // Add New Display Title
			'edit' => __( 'Edit' ), // Edit Dialog
			'edit_item' => __('Edit Citation'), // Edit Display Title
			'new_item' => __('New Citation'), // New Display Title
			'view_item' => __('View Citation'), // View Display Title
			'search_items' => __('Search Citations'), // Search Custom Type Title 
			'not_found' =>  __('No citations found in the database.'), // This displays if there are no entries yet 
			'not_found_in_trash' => __('Nothing found in Trash'), // This displays if there is nothing in the trash
			'parent_item_colon' => ''
                    ), // end of arrays
                    'description' => __( 'This is a content type for citations.' ), // Custom Type Description
                    'public' => true,
                    'publicly_queryable' => true,
                    'exclude_from_search' => false,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'query_var' => true,
                    'menu_position' => 8, // this is what order you want it to appear in on the left hand side menu 
                    'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', // the icon for the custom post type menu
                    'rewrite' => array( 'slug' => 'citations', 'with_front' => false ),
                    'capability_type' => 'post',
                    'hierarchical' => false,
                    'permalink_epmask' => 'EP_PERMALINK & EP_YEAR', 
                    'has_archive' => 'citations',
                    'register_meta_box_cb' => array( __CLASS__, 'addMetaBoxes' ),
                    // the next one is important, it tells what's enabled in the post editor
                    'supports' => array( 'title', 'thumbnail', 'excerpt', 'revisions', 'sticky'),
                    'taxonomies' => array('post_tag')
	 	) // end of options
	); // end of register post type
        
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
    public static function markupMetaBoxes( $post, $box ) {

        $meta = get_post_meta( $post->ID );
        $citation_meta = get_post_meta( $post->ID, 'citation' );
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
            echo self::$TemplateRenderer->renderInput( 'select', ${$type_field_id . '_options'} );
            
            /*Render specific citation fields*/
            if( $selected_type ) {
                echo self::buildInputGroups( $selected_type, $citation_meta );
            }
            ?>        

        </div>

    <?php }
    
    /**
    * Build citation group based on speific type
    * @mvc Controller
    * @param string $citation_type
    * @param array  $current_meta
    * @author Jenny Sharps <jsharps85@gmail.com>
    */
    public static function buildInputGroups( $citation_type, $citation_meta = NULL ) {        
        $return = '';
        switch( $citation_type ) {
            case 'book':
                $return = self::renderBookGroup( $citation_meta );
                break;
            case 'book_chapter':
                break;
            case 'book_electronic':
                break;
            case 'book_chapter_electronic':
                break;
            case 'conference':
                break;
            case 'journal':
                break;
            case 'magazine':
                break;
            case 'newspaper':
                break;
        }
        return $return;
    }
    
    public static function renderBookGroup( $citation_meta ) {

            $author_count = isset( $citation_meta[0]['author'] ) ? count( $citation_meta[0]['author'] ) : 1;
            
            $markup = '<label>Author Info</label>';
        
            for( $x = 0; $x < $author_count; $x++ ) {
                $author_meta_item = is_array( $citation_meta[0] ) && ( !empty( $citation_meta[0]['author'][$x] ) ) ? $citation_meta[0]['author'][$x] : '';
                $markup .= self::renderAuthorGroup( $x, $author_meta_item );
            }
            
            return $markup;
    }
    
    public static function renderAuthorGroup( $item, $author_meta = NULL ) {

        $author_options = array(
            'field_id'      => "citation[author][$item]",
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
        return '<div class="author_group">' . self::$TemplateRenderer->renderInputGroup( $author_options ) .'</div>';
    }
    
    /**
     * Saves values of the the custom post type's extra fields
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