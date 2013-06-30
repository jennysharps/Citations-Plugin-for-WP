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

        echo wp_nonce_field( -1, self::$postTypeName . '_noncename', true, false ); ?>

        <div class="<?php echo self::$postTypeName; ?>-custom-fields">
            
            <?php
            
            ${self::$citationTypes['field_id'] . '_options'} = array(
                'label'         => 'Type',
                'field_id'      => self::$citationTypes['field_id'],
                'placeholder'   => 'Select Type',
                'options'       => self::$citationTypes['options'],
                'selected'      => $meta[self::$citationTypes['field_id']][0]
            );
            echo self::$TemplateRenderer->renderInput( 'select', ${self::$citationTypes['field_id'] . '_options'} );
            ?>      
            

        </div>

    <?php }
            
    /**
     * Saves values of the the custom post type's extra fields
     * @mvc Controller
     * @param int $postID
     * @param object $revision
     * @author Jenny Sharps <jsharps85@gmail.com>
     */
    public static function savePost( $postID, $revision = NULL ) {

        /*if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
                !wp_verify_nonce( $_POST[self::$postTypeName . '_noncename'] )  ||
                !current_user_can( 'edit_post', $postId ) ) {
            return;
        }*/


        update_post_meta( $postID, self::$citationTypes['field_id'], $_POST[self::$citationTypes['field_id']] );
  
    }
    
}