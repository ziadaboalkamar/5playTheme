<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
    <div class="wrp-min speedbar">
        <div class="speedbar-panel">
            <?php if (function_exists('breadcrumbsX')) breadcrumbsX(); ?>
        </div>
    </div>
    
    <div class="page-head-cat">
        <div class="wrp-min">
            <div class="head-cat-title">
                <h1 class="title"><?php printf( __( '%s', THEMES_NAMES ), '' . single_cat_title( '', false ) . '' ); ?></h1>
                <div class="head-cat-tools">
                    <button class="cat-menu-btn collapsed" type="button" title="Select a category" data-toggle="collapse" data-target="#collapse-menu"><svg width="24" height="24"><use xlink:href="#i__settings"></use></svg><span class="sr-only">Select a category</span></button>
                </div>
            </div>
            
            <div class="cat-menu-collapse collapse" id="collapse-menu">
                <div class="cat-menu-list">
                    <ul> 
                    <?php
					/* 
					https://stackoverflow.com/questions/38494637/show-subcategory-instead-of-parent-category 
					*/
						  $taxonomy     = 'category';
						  $orderby      = 'name';  
						  $show_count   = 0;      // 1 for yes, 0 for no
						  $pad_counts   = 0;      // 1 for yes, 0 for no
						  $hierarchical = 1;      // 1 for yes, 0 for no  
						  $title        = '';  
						  $empty        = 1;	  // 1 for yes, 0 for no 

						  $args = array(
								 'taxonomy'     => $taxonomy,
								 'orderby'      => $orderby,
								 'show_count'   => $show_count,
								 'pad_counts'   => $pad_counts,
								 'hierarchical' => $hierarchical,
								 'title_li'     => $title,
								 'hide_empty'   => $empty
						  );
						 $all_categories = get_categories( $args );
						 foreach ($all_categories as $cat) {
                            if($cat->category_parent == 0) {
                            //echo '<div class="cat-header__left-children-item">';
                            }
							if($cat->category_parent == 0) {
								$category_id = $cat->term_id;  
								$args2 = array(
										'taxonomy'     => $taxonomy,
										'child_of'     => 0,
										'parent'       => $category_id,
										'orderby'      => $orderby,
										'show_count'   => $show_count,
										'pad_counts'   => $pad_counts,
										'hierarchical' => $hierarchical,
										'title_li'     => $title,
										'hide_empty'   => $empty
								);
								$sub_cats = get_categories( $args2 );  
                                if (!empty($sub_cats)) {                                 
								echo '<li class="catalog-genres-item"><a href="'.get_term_link($cat->slug, 'category').'">'.$cat->name.'</a></li>';
                                }
								if($sub_cats) { 
									foreach($sub_cats as $sub_category) {
                                    echo '<li class="catalog-genres-item"><a href="'.get_term_link($sub_category->slug, 'category').'">'.$sub_category->name.'</a></li>';
									} 
								}
                                
                            if($cat->category_parent == 0) {
                            //echo '</div>';
                            }
							}       
						}
                        ?>                    
                    </ul>
                </div>
            </div>
        </div>
        <i class="bg-clouds"></i>
    </div>
    
    
    <div class="page-cat-bg">
        <div class="wrp page-cat-cont">
            <div class="entry-listpage list-c6">	 
			<?php ex_themes_adv_homes_(); ?>
                 
                    <?php $postcounter = 1; if (have_posts()) : ?>
                        <?php while (have_posts()) : $postcounter = $postcounter + 1; the_post(); $do_not_duplicate = $post->ID; $the_post_ids = get_the_ID(); ?>
                           <?php get_template_part('template/loop/loop.item.home'); ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                    <?php endif; ?>
                
			</div>
				<?php get_template_part('template/navy'); ?>
        </div>
    </div>
    <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <defs>
             
            <symbol id="i__search" viewBox="0 0 24 24">
                <path fill="currentColor" d="M15.5 14h-.79l-.28-.27c1.2-1.4 1.82-3.31 1.48-5.34-.47-2.78-2.79-5-5.59-5.34-4.23-.52-7.79 3.04-7.27 7.27.34 2.8 2.56 5.12 5.34 5.59 2.03.34 3.94-.28 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
            </symbol>
            <symbol id="i__user" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v1c0 .55.45 1 1 1h14c.55 0 1-.45 1-1v-1c0-2.66-5.33-4-8-4z" />
            </symbol>
            <symbol id="i__gamepad" viewBox="0 0 24 24">
                <path fill="currentColor" d="M21.58,16.09l-1.09-7.66C20.21,6.46,18.52,5,16.53,5H7.47C5.48,5,3.79,6.46,3.51,8.43l-1.09,7.66 C2.2,17.63,3.39,19,4.94,19h0c0.68,0,1.32-0.27,1.8-0.75L9,16h6l2.25,2.25c0.48,0.48,1.13,0.75,1.8,0.75h0 C20.61,19,21.8,17.63,21.58,16.09z M11,11H9v2H8v-2H6v-1h2V8h1v2h2V11z M15,10c-0.55,0-1-0.45-1-1c0-0.55,0.45-1,1-1s1,0.45,1,1 C16,9.55,15.55,10,15,10z M17,13c-0.55,0-1-0.45-1-1c0-0.55,0.45-1,1-1s1,0.45,1,1C18,12.55,17.55,13,17,13z" />
            </symbol>
            <symbol id="i__apps" viewBox="0 0 24 24">
                <path fill="currentColor" d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
            </symbol>
            <symbol id="i__cup" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19,5h-2V4c0-0.55-0.45-1-1-1H8C7.45,3,7,3.45,7,4v1H5C3.9,5,3,5.9,3,7v1c0,2.55,1.92,4.63,4.39,4.94 c0.63,1.5,1.98,2.63,3.61,2.96V19H8c-0.55,0-1,0.45-1,1v0c0,0.55,0.45,1,1,1h8c0.55,0,1-0.45,1-1v0c0-0.55-0.45-1-1-1h-3v-3.1 c1.63-0.33,2.98-1.46,3.61-2.96C19.08,12.63,21,10.55,21,8V7C21,5.9,20.1,5,19,5z M5,8V7h2v3.82C5.84,10.4,5,9.3,5,8z M19,8 c0,1.3-0.84,2.4-2,2.82V7h2V8z" />
            </symbol>
            <symbol id="i__flash" viewBox="0 0 24 24">
                <path fill="currentColor" d="M8,4v9a1,1,0,0,0,1,1h2v7.15a.5.5,0,0,0,.93.25l5.19-8.9a1,1,0,0,0-.37-1.37,1.05,1.05,0,0,0-.49-.13H14l2.49-6.65a1,1,0,0,0-.57-1.28A.92.92,0,0,0,15.56,3H9A1,1,0,0,0,8,4Z" />
            </symbol>
            <symbol id="i__coms" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12,23h0L9,20H5a2,2,0,0,1-2-2V4A2,2,0,0,1,5,2H19a2,2,0,0,1,2,2V18a2,2,0,0,1-2,2H15Zm4-13a1,1,0,1,0,1,1A1,1,0,0,0,16,10Zm-4,0a1,1,0,1,0,1,1A1,1,0,0,0,12,10ZM8,10a1,1,0,1,0,1,1A1,1,0,0,0,8,10Z" />
            </symbol>
            <symbol id="i__stats" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2s-2 .9-2 2v12c0 1.1.9 2 2 2zm-6 0c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2s-2 .9-2 2v4c0 1.1.9 2 2 2zm10-9v7c0 1.1.9 2 2 2s2-.9 2-2v-7c0-1.1-.9-2-2-2s-2 .9-2 2z" />
            </symbol>
            <symbol id="i__close" viewBox="0 0 24 24">
                <path fill="currentColor" d="M18.3 5.71c-.39-.39-1.02-.39-1.41 0L12 10.59 7.11 5.7c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41L10.59 12 5.7 16.89c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L12 13.41l4.89 4.89c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4z" />
            </symbol>
            <symbol id="i__order" viewBox="0 0 32 32">
                <path fill="#fede4a" d="M30,26h0a2,2,0,0,1,0-4,2,2,0,0,0,0-4H26a2,2,0,0,1,0-4h1a2,2,0,0,0,0-4H17a2,2,0,0,1,0-4h4a2,2,0,0,0,0-4H4A2,2,0,0,0,4,6H5a2,2,0,0,1,0,4H2a2,2,0,0,0,0,4H5a2,2,0,0,1,0,4,2,2,0,0,0,0,4H9a2,2,0,0,1,0,4H7a2,2,0,0,0,0,4H30a2,2,0,0,0,0-4Z" /><path fill="#4bca6f" d="M10,5H24a2,2,0,0,1,2,2V26a3,3,0,0,1-3,3H10a2,2,0,0,1-2-2V7A2,2,0,0,1,10,5Z" /><path fill="#fff" d="M16,18V15H13a1,1,0,0,1,0-2h3V10a1,1,0,0,1,2,0v3h3a1,1,0,0,1,0,2H18v3a1,1,0,0,1-2,0Z" /><path fill="#27987d" d="M20,26V24h0a2,2,0,0,0-2-2H4a2,2,0,0,0-2,2v2a3,3,0,0,0,3,3H23A3,3,0,0,1,20,26Z" />
            </symbol>
            <symbol id="i__android" viewBox="0 0 24 24">
                <path fill="currentColor" d="M7.2,16.8a.8.8,0,0,0,.8.8h.8v2.8a1.2,1.2,0,0,0,2.4,0V17.6h1.6v2.8a1.2,1.2,0,0,0,2.4,0h0V17.6H16a.8.8,0,0,0,.8-.8h0v-8H7.2Zm-2-8A1.2,1.2,0,0,0,4,10H4v5.6a1.2,1.2,0,0,0,2.4,0h0V10A1.2,1.2,0,0,0,5.2,8.8Zm13.6,0A1.2,1.2,0,0,0,17.6,10v5.6a1.2,1.2,0,0,0,2.4,0V10a1.2,1.2,0,0,0-1.2-1.2Zm-4-4.67,1-1a.41.41,0,0,0,0-.57h0a.39.39,0,0,0-.56,0h0L14.11,3.7A4.68,4.68,0,0,0,12,3.2a4.76,4.76,0,0,0-2.13.5L8.68,2.52a.4.4,0,0,0-.57,0h0a.41.41,0,0,0,0,.57h0l1.05,1A4.78,4.78,0,0,0,7.2,8h9.6A4.76,4.76,0,0,0,14.82,4.13ZM10.4,6.4H9.6V5.6h.8Zm4,0h-.8V5.6h.8Z" />
            </symbol>
            <symbol id="i__vers" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 2.02c-5.51 0-9.98 4.47-9.98 9.98s4.47 9.98 9.98 9.98 9.98-4.47 9.98-9.98S17.51 2.02 12 2.02zm-.52 15.86v-4.14H8.82c-.37 0-.62-.4-.44-.73l3.68-7.17c.23-.47.94-.3.94.23v4.19h2.54c.37 0 .61.39.45.72l-3.56 7.12c-.24.48-.95.31-.95-.22z" />
            </symbol>
            <symbol id="i__info" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 15c-.55 0-1-.45-1-1v-4c0-.55.45-1 1-1s1 .45 1 1v4c0 .55-.45 1-1 1zm1-8h-2V7h2v2z" />
            </symbol>
            <symbol id="i__arrowleft" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19 11H7.83l4.88-4.88c.39-.39.39-1.03 0-1.42-.39-.39-1.02-.39-1.41 0l-6.59 6.59c-.39.39-.39 1.02 0 1.41l6.59 6.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L7.83 13H19c.55 0 1-.45 1-1s-.45-1-1-1z" />
            </symbol>
            <symbol id="i__arrowright" viewBox="0 0 24 24">
                <path fill="currentColor" d="M5 13h11.17l-4.88 4.88c-.39.39-.39 1.03 0 1.42.39.39 1.02.39 1.41 0l6.59-6.59c.39-.39.39-1.02 0-1.41l-6.58-6.6c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41L16.17 11H5c-.55 0-1 .45-1 1s.45 1 1 1z" />
            </symbol>
            <symbol id="i__keyright" viewBox="0 0 24 24">
                <path fill="currentColor" d="M9.29 15.88L13.17 12 9.29 8.12c-.39-.39-.39-1.02 0-1.41.39-.39 1.02-.39 1.41 0l4.59 4.59c.39.39.39 1.02 0 1.41L10.7 17.3c-.39.39-1.02.39-1.41 0-.38-.39-.39-1.03 0-1.42z" />
            </symbol>
            <symbol id="i__getapp" viewBox="0 0 24 24">
                <path fill="currentColor" d="M16.59 9H15V4c0-.55-.45-1-1-1h-4c-.55 0-1 .45-1 1v5H7.41c-.89 0-1.34 1.08-.71 1.71l4.59 4.59c.39.39 1.02.39 1.41 0l4.59-4.59c.63-.63.19-1.71-.7-1.71zM5 19c0 .55.45 1 1 1h12c.55 0 1-.45 1-1s-.45-1-1-1H6c-.55 0-1 .45-1 1z" />
            </symbol>
            <symbol id="i__scrollup" viewBox="0 0 24 24">
                <path fill="currentColor" d="M8.06,11.71,12,7.83l3.88,3.88a1,1,0,1,0,1.52-1.3.57.57,0,0,0-.11-.11L12.65,5.71a1,1,0,0,0-1.41,0L6.65,10.3a1,1,0,0,0,1.41,1.41Zm9.18,5.17-4.59-4.59a1,1,0,0,0-1.41,0L6.65,16.88a1,1,0,0,0,1.41,1.41L12,14.41l3.88,3.88A1,1,0,1,0,17.35,17Z" />
            </symbol>
            <symbol id="i__reply" viewBox="0 0 24 24">
                <path fill="currentColor" d="M10 9V7.41c0-.89-1.08-1.34-1.71-.71L3.7 11.29c-.39.39-.39 1.02 0 1.41l4.59 4.59c.63.63 1.71.19 1.71-.7V14.9c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z" />
            </symbol>
            <symbol id="i__moon" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12,2.73a.5.5,0,0,0-.26-.66.49.49,0,0,0-.24,0A10,10,0,1,0,20.2,17.71a.51.51,0,0,0-.11-.7.43.43,0,0,0-.22-.09A10,10,0,0,1,12,2.73Z" />
            </symbol>
            <symbol id="i__sun" viewBox="0 0 24 24">
                <path fill="currentColor" d="M6.06,4.64l-.39-.39a1,1,0,0,0-1.4,0h0a1,1,0,0,0,0,1.4l.38.39a1,1,0,0,0,1.41,0h0A1,1,0,0,0,6.06,4.64ZM3,11H2a1,1,0,0,0-1,1H1a1,1,0,0,0,1,1H3a1,1,0,0,0,1-1H4A1,1,0,0,0,3,11Zm9-9.95h0a1,1,0,0,0-1,1V3a1,1,0,0,0,1,1h0a1,1,0,0,0,1-1V2A1,1,0,0,0,12,1.05Zm7.74,3.21a1,1,0,0,0-1.41,0L18,4.64A1,1,0,0,0,18,6h0a1,1,0,0,0,1.4,0l.39-.39A1,1,0,0,0,19.76,4.26ZM18,19.36l.39.39a1,1,0,0,0,1.4,0,1,1,0,0,0,0-1.41L19.36,18A1,1,0,0,0,18,19.36ZM20,12h0a1,1,0,0,0,1,1h1a1,1,0,0,0,1-1h0a1,1,0,0,0-1-1H21A1,1,0,0,0,20,12ZM12,6a6,6,0,1,0,6,6A6,6,0,0,0,12,6Zm0,17h0a1,1,0,0,0,1-1V21a1,1,0,0,0-1-1h0a1,1,0,0,0-1,1v1A1,1,0,0,0,12,23ZM4.26,19.74a1,1,0,0,0,1.4,0l.39-.39a1,1,0,0,0,0-1.4H6a1,1,0,0,0-1.41,0l-.39.39A1,1,0,0,0,4.26,19.74Z" />
            </symbol>
            <symbol id="i__update" viewBox="0 0 24 24">
                <path fill="currentColor" d="M21,9.5V4.21a.49.49,0,0,0-.85-.35L18.37,5.64a9,9,0,1,0,2.56,7.48,1,1,0,0,0-1-1.12,1,1,0,0,0-1,.86,7,7,0,0,1-7,6.14A7.1,7.1,0,0,1,5,12.1a7,7,0,0,1,12-5L14.86,9.14a.5.5,0,0,0,.35.86H20.5A.5.5,0,0,0,21,9.5Z" />
            </symbol>
            <symbol id="i__cat" viewBox="0 0 24 24">
                <path fill="currentColor" d="M8.28,11h7.43a1,1,0,0,0,.85-1.52L12.85,3.4a1,1,0,0,0-1.7,0L7.43,9.48A1,1,0,0,0,8.28,11Zm9.22,2A4.5,4.5,0,1,0,22,17.5,4.49,4.49,0,0,0,17.5,13Zm-7.5.5H4a1,1,0,0,0-1,1v6a1,1,0,0,0,1,1h6a1,1,0,0,0,1-1v-6A1,1,0,0,0,10,13.5Z" />
            </symbol>
            <symbol id="i__play" viewBox="0 0 24 24">
                <path fill="currentColor" d="M10.8 15.9l4.67-3.5c.27-.2.27-.6 0-.8L10.8 8.1c-.33-.25-.8-.01-.8.4v7c0 .41.47.65.8.4zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
            </symbol>
            <symbol id="i__linkopen" viewBox="0 0 24 24">
                <path fill="currentColor" d="M9 6c0 .56.45 1 1 1h5.59L4.7 17.89c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L17 8.41V14c0 .55.45 1 1 1s1-.45 1-1V6c0-.55-.45-1-1-1h-8c-.55 0-1 .45-1 1z" />
            </symbol>
            <symbol id="i__settings" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z" />
            </symbol>
            <symbol id="i__telegram" viewBox="0 0 40 40">
                <path fill="#c8daea" d="M14.87,32.83c-.91,0-.76-.34-1.07-1.2l-2.67-8.78L31.67,10.67Z" /><path fill="#a9c9dd" d="M14.87,32.83a1.77,1.77,0,0,0,1.4-.7L20,28.5l-4.66-2.8Z" /><path fill="#eff7fc" d="M15.34,25.7,26.63,34c1.28.71,2.21.35,2.53-1.2l4.6-21.64C34.23,9.31,33,8.45,31.81,9l-27,10.4C3,20.15,3,21.18,4.5,21.63l6.92,2.16,16-10.1c.75-.46,1.45-.22.88.29Z" />
            </symbol>
            <symbol id="i__hot" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19.48,12.35c-1.57-4.08-7.16-4.3-5.81-10.23c0.1-0.44-0.37-0.78-0.75-0.55C9.29,3.71,6.68,8,8.87,13.62 c0.18,0.46-0.36,0.89-0.75,0.59c-1.81-1.37-2-3.34-1.84-4.75c0.06-0.52-0.62-0.77-0.91-0.34C4.69,10.16,4,11.84,4,14.37 c0.38,5.6,5.11,7.32,6.81,7.54c2.43,0.31,5.06-0.14,6.95-1.87C19.84,18.11,20.6,15.03,19.48,12.35z M10.2,17.38 c1.44-0.35,2.18-1.39,2.38-2.31c0.33-1.43-0.96-2.83-0.09-5.09c0.33,1.87,3.27,3.04,3.27,5.08C15.84,17.59,13.1,19.76,10.2,17.38z" />
            </symbol>
        </defs>
    </svg>
<?php get_footer(); 