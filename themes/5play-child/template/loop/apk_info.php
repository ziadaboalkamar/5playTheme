<?php
global $wpdb, $post, $wp_query;
$linkX							= get_bloginfo('url'); 
$parse							= parse_url($linkX); 
$watermark1						= $parse['host'];
$thumbnails						= get_post_meta( $post->ID, 'wp_poster_GP', true );
$rate_GP						= get_post_meta( $post->ID, 'wp_rated_GP', true );
$ratings_GP						= get_post_meta( $post->ID, 'wp_ratings_GP', true );
$rate_GP1						= get_post_meta( $post->ID, 'wp_rated_GP', true );
if ( $rate_GP === FALSE or $rate_GP == '' ) $rate_GP = $rate_GP1;
$version						= get_post_meta( $post->ID, 'wp_version', true );
$version_alt					= get_post_meta( $post->ID, 'wp_version_GP', true );
$version_dt					    = get_key_option( $post->ID, 'version');

if ($version_dt && $version_dt != ""){
    $version = $version_dt;
}else{
    if ( $version === FALSE or $version == '' ) $version = $version_alt;
}

$developer						 = get_option('wp_developers_GP', 'developer');
$developer_dt					 = get_key_option( $post->ID, 'author');
$developer_dt_link				 = get_key_option( $post->ID, 'author_link');

if ($developer_dt && $developer_dt != ""){
    $developer = $developer_dt;
    $terms =(object) [["name" => $developer]];



}else{
    $terms = wp_get_post_terms($post->ID, $developer);

}
$sizes							= get_post_meta( $post->ID, 'wp_sizes', true );
$sizesX1						= get_post_meta( $post->ID, 'wp_sizes', true );
$sizesX							= '-';
$size_dt                        = get_key_option( $post->ID, 'size');
if ($size_dt && $size_dt != ""){
    $sizes = $size_dt;
}else{
    if ( $sizes === FALSE or $sizes == '' ) $sizes = $sizesX;

}
$whatnews_GP					= get_post_meta( $post->ID, 'wp_whatnews_GP', true );
$whatnews_GP1					= get_post_meta( $post->ID, 'wp_whatnews_GP', true );
if ( $whatnews_GP === FALSE or $whatnews_GP == '' ) $whatnews_GP = $whatnews_GP1;
$developersx1					= get_post_meta( $post->ID, 'wp_developers_GP', true );
$updates						= get_post_meta( $post->ID, 'wp_updates_GP', true );
$updatesX1						= get_post_meta( $post->ID, 'wp_updates_GP', true );
$updatesX						= '-';
$updates_dt                     =  get_key_option( $post->ID, 'last_update');
if ($updates_dt && $updates_dt != ""){
    $updates = $updates_dt;
}else{
    if ( $updates === FALSE or $updates == '' ) $updates = $updatesX;
}
$requiresX2						= get_post_meta( $post->ID, 'wp_requires_GP', true );
$requires						= str_replace('and up', '', $requiresX2);
$requiresX1						= get_post_meta( $post->ID, 'wp_requires_GP', true );
$requiresX						= '-';
if ( $requires === FALSE or $requires == '' ) $requires = $requiresX;
$post_id 						= (!empty($atts['id'])) ? intval($atts['id']) : get_the_ID();

$like_count 					= get_post_meta($post_id, 'pld_like_count', true);
$dislike_count 					= get_post_meta($post_id, 'pld_dislike_count', true);
if (empty($like_count)) {
    $like_count = 0;
}
if (empty($dislike_count)) {
    $dislike_count = 0;
}
$all_likes						= $like_count+$dislike_count;

$viewview						= ex_themes_get_post_view_2();
$numbers						= array( $viewview, 500 );
$everything						= array_sum( $numbers ); 
 
function percentageOf( $number, $everything, $decimals = 2 ){
return round( $number / $everything * 100, $decimals );
} 
$popularity						= percentageOf( $numbers[0], $everything );

$best							= get_option('kksr_stars');
$score							= get_post_meta(get_the_ID(), '_kksr_ratings', true) ? ((int) get_post_meta(get_the_ID(), '_kksr_ratings', true)) : 0;
$votes							= get_post_meta(get_the_ID(), '_kksr_casts', true) ? ((int) get_post_meta(get_the_ID(), '_kksr_casts', true)) : 0;
$avg							= $score && $votes ? round((float)(($score/$votes)*($best/5)), 1) : 0;
?>
<div class="view-app-data">
    <div class="specs-list">
        <ul>
            <li class="specs-item">
                <i class="spec-icon c-green"><svg width="24" height="24"><use xlink:href="#i__update"></use></svg></i>
                <span class="spec-label"><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Updated']) { ?><?php echo  _e($opt_themes['exthemes_apk_info_Updated'],CHILD_THEME);  ?><?php } ?> </span>
                <time class="spec-cont"<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="datePublished"<?php } ?> datetime="<?php echo $updates; ?>"><?php global $opt_themes; if($opt_themes['ex_themes_rtl_activate_']) { ?><?php echo RTL_Nums($updates); ?><?php } else { ?><?php echo $updates; ?><?php } ?></time> 
            </li>
			<?php if($version){?>
            <li class="specs-item">
                <i class="spec-icon c-green"><svg width="24" height="24"><use xlink:href="#i__vers"></use></svg></i>
                <span class="spec-label"><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Version']) { ?><?php echo _e($opt_themes['exthemes_apk_info_Version'],CHILD_THEME); ?><?php } ?></span>
                <span class="spec-cont"<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="softwareVersion"<?php } ?>><?php echo $version; ?></span>
            </li>
			<?php } if($requires){ ?>
            <li class="specs-item">
                <i class="spec-icon c-green"><svg width="24" height="24"><use xlink:href="#i__android"></use></svg></i>
                <span class="spec-label"><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Requirements']) { ?><?php echo _e($opt_themes['exthemes_apk_info_Requirements'],CHILD_THEME); ?><?php } ?></span>
                <span class="spec-cont"<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="operatingSystem"<?php } ?>><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Android']) { ?><?php echo $opt_themes['exthemes_apk_info_Android']; ?><?php } ?> <?php echo $requires; ?></span>
            </li>
			<?php } if ($terms) { ?>
            <li class="specs-item">
                <i class="spec-icon c-green"><svg width="24" height="24"><use xlink:href="#i__android"></use></svg></i>
                <span class="spec-label"><?php global $opt_themes; if($opt_themes['exthemes_apk_info_developer']) { ?><?php echo  _e($opt_themes['exthemes_apk_info_developer'],CHILD_THEME); ?><?php } ?> </span>
				 
				<?php
				if ($terms) {
				$output = array();
				foreach ($terms as $term) {
                    $term = (object) $term;
                    if ($developer_dt_link && $developer_dt_link != ""){
                        $link = $developer_dt_link;
                    }elseif (isset($term->slug)){
                        $link = get_term_link( $term->slug, $developer);
                    }else{
                        $link = "#";
                    }
                    $output[] = '<span class="spec-cont"><a href="' .$link .'" title="Developer by  ' .$term->name .'">' .$term->name .'</a></span>';
				}
				echo join( ', ', $output );	}
                ?>
            </li>
			<?php } if(get_the_category()){ ?>
            <li class="specs-item">
                <i class="spec-icon c-green"><svg width="24" height="24"><use xlink:href="#i__cat"></use></svg></i>
                <span class="spec-label"><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Genre']) { ?><?php echo _e($opt_themes['exthemes_apk_info_Genre'],CHILD_THEME) ; ?><?php } ?></span>
                <span class="spec-cont"<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="applicationCategory"<?php } ?>>
				<?php 
				$i = 0;
				foreach((get_the_category()) as $cat) { ?>
				<a href="<?php echo get_category_link($cat->cat_ID); ?>" ><?php echo $cat->cat_name; ?></a>
				<?php if (++$i == 1) break;
				}  ?>				
				</span> 
            </li> 
			<?php }?>
			<?php $package_id = get_package($post->ID);if ($package_id && $package_id != "" && str_contains($package_id,'com.')){  ?>
                <li class="specs-item google">
                    <i class="spec-icon c-green"><svg width="24" height="24"><use xlink:href="#i__play"></use></svg></i>
                    <span class="spec-label"><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Google_Play']) { ?><?php echo _e($opt_themes['exthemes_apk_info_Google_Play'],CHILD_THEME) ; ?><?php } ?></span>
                    <div class="spec-cont fw-b" >
                        <span<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="url" content="<?php the_permalink() ?>"<?php } ?>></span>
                        <a href="https://play.google.com/store/apps/details?id=<?php echo $package_id;  ?>" rel="nofollow noopener" target="_blank"<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemscope="" itemprop="offers" itemtype="http://schema.org/Offer"<?php } ?>><meta<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="price" content="0"<?php } ?>><meta<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="priceCurrency" content="USD"<?php } ?>>Open <svg class="c-green" width="24" height="24"><use xlink:href="#i__linkopen"></use></svg></a>
                    </div>
                </li>

            <?php }else { ?>
            <?php  if(get_post_meta( $post->ID, 'wp_GP_ID', true ) && str_contains(get_post_meta( $post->ID, 'wp_GP_ID', true ),'com.')){ ?>
            <li class="specs-item ">
                <i class="spec-icon c-green"><svg width="24" height="24"><use xlink:href="#i__play"></use></svg></i>
                <span class="spec-label"><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Google_Play']) { ?><?php echo _e($opt_themes['exthemes_apk_info_Google_Play'],CHILD_THEME) ; ?><?php } ?></span>
                <div class="spec-cont fw-b" >
                    <span<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="url" content="<?php the_permalink() ?>"<?php } ?>></span>
                    <a href="https://play.google.com/store/apps/details?id=<?php echo esc_html( get_post_meta( $post->ID, 'wp_GP_ID', true ) ); ?>" rel="nofollow noopener" target="_blank"<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemscope="" itemprop="offers" itemtype="http://schema.org/Offer"<?php } ?>><meta<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="price" content="0"<?php } ?>><meta<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="priceCurrency" content="USD"<?php } ?>>Open <svg class="c-green" width="24" height="24"><use xlink:href="#i__linkopen"></use></svg></a>
                </div>
            </li>
            <?php } }?>
        </ul>
    </div>
    <div class="view-app-rate">

        <?php echo do_shortcode('[posts_like_dislike]'); ?>
        <ul class="rate-nums muted">
            <li><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Votes']) { ?><?php echo _e($opt_themes['exthemes_apk_info_Votes'],CHILD_THEME) ; ?><?php } ?>
                <span id="vote-num-id" <?php global $opt_themes; if($opt_themes['ex_themes_rtl_activate_']) { ?>style="font-size: medium;"<?php } ?>><?php global $opt_themes; if($opt_themes['ex_themes_rtl_activate_']) { ?><?php echo RTL_Nums($all_likes); ?><?php } else { ?><?php echo $all_likes; ?><?php } ?></span>
            </li>
            <li><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Comments']) { ?><?php echo _e($opt_themes['exthemes_apk_info_Comments'],CHILD_THEME) ; ?><?php } ?> <a href="#allcomments"><?php comments_number('0', '1', '%'); ?></a></li>
        </ul>


        <?php if (function_exists('kk_star_ratings')) { ?> 
		<center><?php echo kk_star_ratings(); ?>
		<span<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating"<?php } ?>>
		<span<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="ratingValue" content=<?php if( $avg ) { ?>"<?php echo $avg; ?>"<?php } else { ?>"1"<?php } }?>></span>		
		<span<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="bestRating" content="<?php echo $best; ?>"<?php } ?>></span>
		<span<?php global $opt_themes; if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="reviewCount" content=<?php if( $votes ) { ?>"<?php echo $votes; ?>"<?php } else { ?>"1"<?php } }?>></span>
		</span>
		</center>
		<?php } echo edit_post_link( __('edit post',CHILD_THEME ), ' ', ' ' ); ?>

        <div class="popularity">
		<div class="popularity-number">
			<div class="rating_status">
			<div class="rating_progress_bar" title="Application popularity">
			<b><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Popularity']) { ?><?php echo  _e($opt_themes['exthemes_apk_info_Popularity'],CHILD_THEME) ; ?><?php } ?> <?php global $opt_themes; if($opt_themes['ex_themes_rtl_activate_']) { ?><b <?php global $opt_themes; if($opt_themes['ex_themes_rtl_activate_']) { ?>style="font-size: medium;"<?php } ?>><?php echo RTL_Nums($popularity); ?></b><?php } else { ?><?php echo $popularity; ?><?php } ?>%</b>
			<span style="width: <?php echo $popularity; ?>%"><?php echo $popularity; ?>%</span>
			</div>
			</div>
		</div> 
        </div>
        <div class="ya-share2" title="<?php global $opt_themes; if($opt_themes['exthemes_apk_info_Share_friends']) { ?><?php echo  _e($opt_themes['exthemes_apk_info_Share_friends'],CHILD_THEME); ?><?php } ?>" data-size="m" data-shape="round" data-services="telegram,vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp"></div>
		<script src="https://yastatic.net/share2/share.js"></script>
    </div>
    
    <div class="btn-group">
        <a class="btn btn-lg btn-icon s-button anchor" href="#download-block"><svg width="24" height="24"><use xlink:href="#i__getapp"></use></svg><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Download']) { ?><?php echo _e( $opt_themes['exthemes_apk_info_Download'],CHILD_THEME) ; ?><?php } ?></a>
		<?php global $opt_themes; if($opt_themes['report_active']) { ?>		
		<button class="btn btn-lg btn-border needreg-btn"><span><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Request_update']) { ?><?php echo  _e($opt_themes['exthemes_apk_info_Request_update'],CHILD_THEME) ; ?><?php } ?></span></button>
		<div id="needreg" title="Information" style="display:none;">Guests cannot request for updates!</div>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

            <script>
		$('.needreg-btn').click(function () {
			$('#needreg').dialog({
				show: 'fade',
				hide: 'fade',
				width: 400,
				buttons: {
					"Close" : function() { $(this).dialog("close"); }
				}
			});
		});
		</script>
		<?php } else { ?>
		<a href="#" post-id="<?php echo $post->ID; ?>" class="report-post-link btn btn-lg btn-border "><span><?php global $opt_themes; if($opt_themes['exthemes_apk_info_Request_update']) { ?><?php  echo  _e($opt_themes['exthemes_apk_info_Request_update'],CHILD_THEME) ; ?><?php } ?></span></a>
		<?php } ?>
    </div>
</div>