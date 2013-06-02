<?php
/**
 * CliClu functions and definitions
 */
if ( ! isset( $content_width ) )
	$content_width = 584;

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'cliclu_setup' );

if ( ! function_exists( 'cliclu_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, custom headers
 * 	and backgrounds, and post formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function cliclu_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );
}

endif; // twentyeleven_setup

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_body_classes( $classes ) {

	if ( function_exists( 'is_multi_author' ) && ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'twentyeleven_body_classes' );





/**
 *add custom manage page
 */

function cliclu_init() {
	wp_enqueue_script('ty_section-page', get_stylesheet_directory_uri().'/js/cliclu.js');
	wp_enqueue_style('ty_section-page', get_stylesheet_directory_uri().'/css/cliclu.css');
}
function cliclu_front_init() {
	wp_enqueue_style('ty_section', get_stylesheet_directory_uri().'/style.css');
}
add_action('wp_enqueue_scripts','cliclu_front_init');//模版页面中包含 wp_head() 才能调用出来



add_action('admin_enqueue_scripts','cliclu_init');   //回调函数用函数组把指针和方程传过去 也可以直接用function(){code herr...}
 
add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_menu() {
    add_menu_page( '普通导航管理页面', '普通导航', 'edit_theme_options', 'nav-options', 'cliclu_nav_options' );
}

function cliclu_nav_options()
{
	$cat_list = get_option('catlist');
	if(!$cat_list)
		add_option( 'catlist');
	$cat_name = 'app';
	if(!get_cat_ID( $cat_name )) 
		wp_create_category( $cat_name);
  	$cat_parent_ID = get_cat_ID( $cat_name );

/*
	$args = array(
	 'hide_empty'				=> 0,
     'child_of'                 => $cat_parent_ID
      );
	$categories = get_categories($args);

*/
	if($cat_list){
		foreach($cat_list as $key => $cat){
			$categories[$key] =  get_category($cat,false);		
		}
	}
	?>
	<div class="cat-list">
		<ul>
	<?php
	if($categories!=0){
		foreach ($categories as $category) {
			?>
			<li class="cat-li" catno="<?php echo $category->cat_ID; ?>" catcount="<?php echo $category->count; ?>">
				<div class="cat-title"><?php echo $category->name; ?></div>
				<span class="sort_handle">=</span>
				<span class="del">删除</span>
			</li>
		    <?php  
	    }		
	}
?>
		    <li class="cat-c">
			    <div class="creat cat-title">+</div>
				
		    </li>
		    <li class="cat-temp" catno="" catcount="">
				<div class="cat-title"></div>
				<span class="sort_handle">=</span>
				<span class="del">删除</span>
			</li>

		    <div class="clear"></div> 
    	</ul>
	</div>
    <div class="cat-creat-area">
    	<ul>
    		<li>
				<form id="" action="" method="post">  
			  
			  
			            <label >输入新的导航标题</label>
			            
		
			            <input type="text" id="creat-cat-name" name="creat-cat-name"/><br />  
		 
			  
			            <a class="button sub-cat" style="cursor: pointer">OK</a>  
			  
			    </form>  
    		</li>
    	</ul>	
    </div>      

   	<?php
}

add_action( 'admin_enqueue_scripts', 'my_enqueue' );
function my_enqueue($hook) {
        
	wp_enqueue_script( 'ajax-script', get_template_directory_uri().'/js/cliclu-admin-ajax.js', array('jquery'));

	// in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'ajax-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));
}


 // Same handler function...

function cliclu_cat() {

  	$parent_name = 'app';
  	$cat_parent_ID = get_cat_ID( $parent_name );

    $cat_name = $_POST['cattitle'];  
  
    $id = wp_create_category($cat_name , $cat_parent_ID);
    $new_cat = get_category($id,false);
    $return = array(
    	'id'=>$new_cat->cat_ID,
    	'name'=>$new_cat->name,
    	'count'=> $new_cat->count
    );
    wp_send_json($return);	
    die(); 
}
add_action('wp_ajax_nopriv_cliclu_cat', 'cliclu_cat');
add_action('wp_ajax_cliclu_cat', 'cliclu_cat');

function cliclu_cat_del() {

    $cat_ID = $_POST['catid'];  

    $id = wp_delete_category( $cat_ID );
    $new_cat = get_category($id,false);
    if($new_cat->term_id)
    	echo 'false';
    die(); 
}
add_action('wp_ajax_nopriv_cliclu_cat_delt', 'cliclu_cat_del');
add_action('wp_ajax_cliclu_cat_del', 'cliclu_cat_del');


function cliclu_cat_list() {

    $results = '';  
    
    $catlist = $_POST['catlist'];
    update_option( 'catlist', $catlist );
    $catlistval =  get_option('catlist') ;
 
  
    if ( $catlistval )  
    {  
        $results = $catlistval ;  
    }  
    else {  
        $results = 'none of anything';  
    }  
    // Return the String  
    wp_send_json($results);  
}
add_action('wp_ajax_nopriv_cliclu_cat_list', 'cliclu_cat_list');
add_action('wp_ajax_cliclu_cat_list', 'cliclu_cat_list');
 
  
/**
 *add cunstom post type
 */
new ty_page_buld('app'); 
class ty_page_buld      
{
	protected $args = array(
		    'public' => true,
		    'publicly_queryable' => true,
		    'show_ui' => true, 
		    'show_in_menu' => true, 
		    'query_var' => true,
		    'capability_type' => 'post',
		    'has_archive' => true, 
		    'hierarchical' => false,
		    'menu_position' => null,
		    'supports' => array( '' )
		  );	
    protected $page_name;	  	  
    public function __construct($page_name)
    {	
        if($page_name) {
        	
        	$this->page_name = $page_name;
        
	    	$this->args['label'] = $this->page_name;

	    	$this->args['rewrite'] = array( 'slug' => $this->page_name, 'with_front' => false );
	    	
        }
        else{
	        $this->page_name = "section";
        }
        add_action( 'init', array($this, 'section_page_init') );
    }
    /**
     * Adds the custom post type
     */
     
    public 	function section_page_init() {
		register_post_type( $this->page_name, $this->args);
    }

}

/**
 * remove some unuseful menu
 */

function remove_menus() {
	//we can do this by another way using remove_menu_page() function
    global $menu;
    $restricted = array(__('Dashboard'),__('Posts'),__('Pages'), __('Media'), __('Links'),_('Commens'),__('评论'),_('Users'),_('Tools'),__('Appearance'),__('Plugins'),__('Settings'),__('用户'),__('工具'));
    end ($menu);
    while (prev($menu)){
        $value = explode(' ',$menu[key($menu)][0]);
        if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
    }
}

if ( is_admin() ) {
    // 删除左侧菜单
    add_action('admin_menu', 'remove_menus');
}

/**
 *remove some dashboard field
 */
function remove_dashboard_widgets(){
  global $wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); 
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); 
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
		
	global $user_ID; 
	if( $user_ID ) :  
		if( current_user_can('level_10') ) : show_admin_bar(true);           
		else : {  show_admin_bar(false); echo '<style type="text/css" >html{margin:0 !important;} #wpadminbar{display:none; visibility: hidden;}</style>'; } 
		endif; 
	endif;  
	
/*


Add costom meta box


*/

class ty_section_meta
{
	protected $prefix_sec = 'app_';  
	protected $section_meta_fields;
	protected $page_name = 'app';  

    public function __construct()
    {	
	    $this->section_meta_fields = array( 
		     array(  
		        'label'=> 'name',  
		        'desc'  => '在这里填写需要添加的 项目 标题',  
		        'id'    => /* $this->prefix_sec. */'title',  
		        'type'  => 'title'  
		    ),
	    	array(
		        'label'=> 'url',  
		        'desc'  => '添加 项目 网址.',  
		        'id'    => $this->prefix_sec.'url',  
		        'type'  => 'url'
	        ),
	        array(  
			    'name'  => 'Image',  
			    'desc'  => '项目图片',  
			    'id'    => $this->prefix_sec.'image',  
			    'type'  => 'image'  
			),
			array(  
			    'label' => 'Category',  
			    'id'    => 'category',  
			    'type'  => 'tax_select'  
			)       
	    );
		add_action('add_meta_boxes', array($this,'add_section_meta_box'));
		add_action('save_post', array($this,'save_section_meta'));    	 
    }
    
        // Add the Meta Box  
	public function add_section_meta_box() {  
	    add_meta_box(  
	        'section_meta_box', // $id  
	        '添加一个项目', // $title   
	        array($this,'show_section_meta_box'), // $callback  
	        $this->page_name, // $page  
	        'normal', // $context  
	        'core'); // $priority 
          
	}
		
	// add meta box
	public	function show_section_meta_box() {
		global $post;
		$section_meta_fields = $this->section_meta_fields;
		echo '<input type="hidden" name="section_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
	    // Begin the field table and loop  
	    echo '<table class="form-table">';  
	    foreach ($section_meta_fields as $field) {  
	        // get value of this field if it exists for this post  
	        $meta = get_post_meta($post->ID, $field['id'], true);
	        // begin a table row with  
	        echo '<tr>';  
	                switch($field['type']) {
						case 'title':  
						    echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /> 
						        <br /><span class="description">'.$field['desc'].'</span>';  
						break;
						case 'url':  
						    echo '<br /><br /><input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /> 
						        <br /><span class="description">'.$field['desc'].'</span><br /><br />';  
						break;
						case 'image':  
							add_thickbox();
						    $image = get_template_directory_uri().'/images/image.png';    
						    echo '<span class="custom_default_image" style="display:none">'.$image.'</span>';  
						    if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium'); $image = $image[0]; }                 
						    echo    '<input name="'.$field['id'].'" type="hidden" class="custom_upload_image" value="'.$meta.'" /> 
						                <img src="'.$image.'" class="custom_preview_image" alt="" /><br /> 
						                    <input class="custom_upload_image_button button" type="button" value="Choose Image" /> 
						                    <small> <a href="#" class="custom_clear_image_button">Remove Image</a></small> 
						                    <br clear="all" /><span class="description">'.$field['desc'].'';  
						break; 
						// tax_select  
						case 'tax_select':  
						    echo '<select name="'.$field['id'].'" id="'.$field['id'].'"> 
						            <option value="">Select One</option>'; // Select One  
						    	$cat_list = get_option('catlist');
								$categories;								
								if($cat_list){
									foreach($cat_list as $key => $cat){
										$categories[$key] =  get_category($cat,false);		
									}
								}
							    $selected = wp_get_object_terms($post->ID, $field['id']);  
							    foreach ($categories as $term) {  
							        if (!empty($selected) && !strcmp($term->slug, $selected[0]->slug))   
							            echo '<option value="'.$term->slug.'" selected="selected">'.$term->name.'</option>';   
							        else  
							            echo '<option value="'.$term->slug.'">'.$term->name.'</option>';   
							    }  
							    $taxonomy = get_taxonomy($field['id']);  
							    echo '</select><br /><span class="description"><a href="'.get_bloginfo('home').'/wp-admin/admin.php?page=nav-options">Manage '.$taxonomy->label.'</a></span>';  
						break;  
 	                } //end switch  
	        echo '</tr>';  
	    } // end foreach  
	    echo '</table>'; // end table  

	} 

	public function save_section_meta($post_id) {  
		$section_meta_fields = $this->section_meta_fields;
	      
	    // verify nonce  
	    if (!wp_verify_nonce($_POST['section_meta_box_nonce'], basename(__FILE__)))   
	        return $post_id;  
	    // check autosave  
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  
	        return $post_id;  
	    // check permissions  
	    if ('section' == $_POST['post_type']) {  
	        if (!current_user_can('edit_page', $post_id))  
	            return $post_id;  
	        } elseif (!current_user_can('edit_post', $post_id)) {  
	            return $post_id;  
	    }  
	      
	    // loop through fields and save the data  
	    foreach ($section_meta_fields as $field) {
	    	if($field['type'] == 'tax_select') continue;   
	        $old = get_post_meta($post_id, $field['id'], true);  
	        $new = $_POST[$field['id']]; 
	        $old_content =  get_post_meta($post_id, $field['content'], true);
   	        $new_content = $_POST[$field['content']]; 
	        if ($new && $new != $old) {  
	            update_post_meta($post_id, $field['id'], $new);  
	        } elseif ('' == $new && $old) {  
	            delete_post_meta($post_id, $field['id'], $old);  
	        }
	        if ($new_content && $new_content != $old_content) {  
	            update_post_meta($post_id, $field['content'], $new_content);  
	        } elseif ('' == $new_content && $old_content) {  
	            delete_post_meta($post_id, $field['content'], $old_content);  
	        }    
	    } // end foreach  
	    // save taxonomies  
		$post = get_post($post_id);  
		$category = $_POST['category'];  
		wp_set_object_terms( $post_id, $category, 'category' );  
	}  
}

new ty_section_meta();
        		
?>