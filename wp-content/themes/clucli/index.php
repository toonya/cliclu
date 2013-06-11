<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<header></header>

	<div id="main" class="wrapper">
	<div id="nav">
		<ul>
			<?php 
					$cat_list = get_option('catlist');
					$cat_parent_ID = get_cat_ID( 'app' );

					if($cat_list){
						foreach($cat_list as $key => $cat){
							$categories[$key] =  get_category($cat,false);		
						}
						
						foreach ($categories as $category) {
						?>
						<li class="cat-li" catno="<?php echo $category->cat_ID; ?>" catcount="<?php echo $category->count; ?>">
							<a href="#" class="cat-title"><?php echo $category->name; ?></a>
						</li>
					    <?php  
						}		

					}
					
			
				
			?>
		</ul>
	</div>
	<div id="primary" class="site-content">
		<div id="content" role="main">
			<div class="list-nav">
				<ul>
					<?php
					
					$args = array(
				        'numberposts'     => 5,
				        'post_type'       => 'app');
				
					// The Query
					$the_query = new WP_Query( $args );
					
					// The Loop
					while ( $the_query->have_posts() ) :
						$the_query->the_post();
						$id =  get_the_ID();
						$custom_text = get_post_custom($id);
						echo '<li><a href="'.$custom_text['app_url'][0].'">';
						$img = $custom_text['app_image'][0];
						echo '<div class="nav-img"><img src="'.$img.'" /></div>';
						echo '<div class="nav-title">';
						the_title();
						echo '</div></a></li>';
					endwhile;
					
					/* Restore original Post Data 
					 * NB: Because we are using new WP_Query we aren't stomping on the 
					 * original $wp_query and it does not need to be reset.
					*/
					wp_reset_postdata();
		
					
					?>
				</ul>
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->

	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>