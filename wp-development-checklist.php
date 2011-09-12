<?php
/*
Plugin Name: WP Developer Checklist
Description: For theme developers to track progress.
Version: 0.1
Author: Brandon Burke
Author URI: http://www.brandonburke.com
Text Domain: wpdeveloperchecklist
*/

class BB_WP_Developer_Checklist_Plugin {
	public static $instance;

	public function __construct() {
		self::$instance = $this;
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		
		add_filter('adminmenu', array( $this, 'wpdcMenu' ) );
		
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name' => _x( 'Checklist', 'taxonomy general name' ),
		    'singular_name' => _x( 'Checklist', 'taxonomy singular name' ),
		    'search_items' =>  __( 'Search Checklists' ),
		    'all_items' => __( 'All Checklists' ),
		    'parent_item' => __( 'Parent Checklists' ),
		    'parent_item_colon' => __( 'Parent Checklists:' ),
		    'edit_item' => __( 'Edit Checklist' ), 
		    'update_item' => __( 'Update Checklist' ),
		    'add_new_item' => __( 'Add New Checklist' ),
		    'new_item_name' => __( 'New Checklist' ),
		    'menu_name' => __( 'Checklists' )
		  ); 	

		  register_taxonomy('checklist', array('wpdeveloperchecklist') , array(
		    'hierarchical' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'query_var' => true
		  ));
	
		// Register the wpdeveloperchecklist post type
		register_post_type( 'wpdeveloperchecklist',
			array(
				'label' => _x( 'Developer Checklist', 'post type label', 'wpdeveloperchecklist' ),
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => false,
				'hierarchical' => true,
				'supports' => array( 'title', 'editor', 'page-attributes' ),
				'taxonomies' => array('checklist'),
				'capabilities' => array(
					'publish_posts' => 'manage_options',
					'edit_posts' => 'manage_options',
					'edit_others_posts' => 'manage_options',
					'delete_posts' => 'manage_options',
					'read_private_posts' => 'manage_options',
					'edit_post' => 'manage_options',
					'delete_post' => 'manage_options',
					'read_post' => 'read'
				),
				'labels' => array (
					'name' => __( 'Checklist Items', 'wpdeveloperchecklist' ),
					'singular_name' => __( 'Checklist item', 'wpdeveloperchecklist' ),
					'add_new' => _x( 'Add New', 'i.e. Add new Checklist Item', 'wpdeveloperchecklist' ),
					'add_new_item' => __( 'Add New Checklist Item', 'wpdeveloperchecklist' ),
					'edit' => _x( 'Edit', 'i.e. Edit Checklist Item', 'wpdeveloperchecklist' ),
					'edit_item' => __( 'Edit Checklist Item', 'wpdeveloperchecklist' ),
					'new_item' => __( 'New Checklist Item', 'wpdeveloperchecklist' ),
					'view' => _x( 'View', 'i.e. View Checklist Item', 'wpdeveloperchecklist' ),
					'view_item' => __( 'View Checklist Item', 'wpdeveloperchecklist' ),
					'search_items' => __( 'Search Checklist Items', 'wpdeveloperchecklist' ),
					'not_found' => __( 'No Checklist Items Found', 'wpdeveloperchecklist' ),
					'not_found_in_trash' => __( 'No Checklist Items found in Trash', 'wpdeveloperchecklist' ),
					'parent' => __( 'Parent Checklist Item', 'wpdeveloperchecklist' )
				)
			)
		);

		add_action( 'restrict_manage_posts', 'my_restrict_manage_posts' );
		function my_restrict_manage_posts() {
			global $typenow;
			$taxonomy = 'checklist';
			if( $typenow == 'wpdeveloperchecklist' ){
				$filters = array($taxonomy);
				foreach ($filters as $tax_slug) {
					$tax_obj = get_taxonomy($tax_slug);
					$tax_name = $tax_obj->labels->name;
					$terms = get_terms($tax_slug);
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>Show All $tax_name</option>";
					foreach ($terms as $term) { echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; }
					echo "</select>";
				}
			}
		}

	}

	public function wpdcMenu() { 
	?>
		<li class='wp-has-submenu menu-top menu-top-first menu-top-last'>
		    <div class='wp-menu-image'><br /></div>
		    <div class='wp-menu-toggle'><br /></div>
		    <a href='edit.php?post_type=wpdeveloperchecklist' class='wp-has-submenu menu-top menu-top-first menu-top-last' tabindex='1'>
				Development Checklist
			</a>
	    </li>
	<?php }

}

new BB_WP_Developer_Checklist_Plugin;