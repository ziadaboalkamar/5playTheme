<?php
/* 
Template Name: Template - Popular Apps
*/
global $opt_themes;
$pages						= (get_query_var('paged')) ? get_query_var('paged') : 1; 
$cat_ids					= $opt_themes['categorie_apps_id']; 
$limits						= $opt_themes['limit_categorie'];
$today						= date('Y-m-d'); 				
$popular_ranges				= $opt_themes['popular_ranges']; 
$ranges						= date('Y-m-d', strtotime($today.' - '.$popular_ranges));
get_header(); ?>
<div class="wrp-min speedbar" style='display:none'>
	<div class="speedbar-panel"><?php if (function_exists('breadcrumbsX')) breadcrumbsX(); ?></div>
</div>
<?php if( !function_exists('dynamic_sidebar') || !dynamic_sidebar('intros-homes') ) : endif; ?>
 
<section class="wrp section">
	<div class="entry-list list-c6">
                <?php                    
                    query_posts( array(
							'date_query' => array(
								array(
								'before' => $today,
								'after' => $ranges,
								'inclusive' => true
								),
							),
                            'paged' => $pages,
                            'cat' => $cat_ids,
                            'posts_per_page' => $limits,
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC' 
							)
                    );
                    if (have_posts()) : while (have_posts()) : the_post();
                    get_template_part('template/loop/loop.item.home'); 
					endwhile; 
					wp_reset_postdata();  
					else : endif; ?>
	</div>
<?php get_template_part('template/navy'); ?>
</section>
<?php get_footer(); 