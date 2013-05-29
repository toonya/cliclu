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
	foreach($cat_list as $key => $cat){
		$categories[$key] =  get_category($cat,false);		
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
				<div class="cat-area"></div>
			</li>
		    <?php  
	    }		
	}





?>
		    <li class="cat-li cat-c">
			    <div class="creat cat-title">新建导航</div>
			    <div class="cat-area">  
				
				    <form id="" action="" method="post">  
				  
				  
				            <div id="ty-response" style="background-color:#E6E6FA"></div>  
				  
 
				            <input type="text" id="creat-cat-name" name="creat-cat-name"/><br />  
			 
				  
				            <a class="button sub-cat" style="cursor: pointer">OK</a>  
				  
				    </form>  
			    </div> 
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

    $results = '';  
  	$cat_name = 'app';
  	$cat_parent_ID = get_cat_ID( $cat_name );

    $title = $_POST['cattitle'];  
  
    $cat_id = 	wp_create_category($title,$cat_parent_ID);
  
  
    if ( $cat_id != 0 )  
    {  
        $results = '*Post Added';  
    }  
    else {  
        $results = '*Error occurred while adding the post';  
    }  
    // Return the String  
    die($results);  
}
add_action('wp_ajax_nopriv_cliclu_cat', 'cliclu_cat');
add_action('wp_ajax_cliclu_cat', 'cliclu_cat');

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
new ty_page_buld('APP'); 
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
		    'supports' => array( 'title' )
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
    $restricted = array(__('Dashboard'),__('Posts'),__('Pages'), __('Media'), __('Links'),_('Commens'),__('评论'),_('Users'),_('Tools'),__('Appearance'),__('Plugins'),__('Settings'),__('APP'),__('用户'),__('工具'));
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
?>