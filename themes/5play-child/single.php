<?php 
get_header();
global $opt_themes; 
$countvisit			= ex_themes_get_post_view_2();
?>
    <div class="wrp-min speedbar">
        <div class="speedbar-panel">
            <?php if (function_exists('breadcrumbsX')) breadcrumbsX(); ?>
        </div>
    </div> 
    <div id="dle-content total-post-view-<?= ex_themes_set_post_view_(); ?><?php echo $countvisit; ?> post-id-<?php the_ID(); ?>">
        <article class="view-app" <?php if($opt_themes['ex_themes_scheme_seo_activate_']) { ?>itemscope="" itemtype="http://schema.org/MobileApplication"<?php } ?>>
            <div class="view-app-head dark-head dark-section">
                <div class="wrp-min">
                    <?php $title = get_dt_title(get_the_ID()); if($title && $title != ""){
                        $title = get_key_option(get_the_ID(),"app_name");
                    }else{
                        $title =get_the_title();

                    } ?>
                <h1<?php if($opt_themes['ex_themes_scheme_seo_activate_']) { ?> itemprop="name" <?php } ?> class="title" ><?php echo $title; ?></h1>
                    <div class="view-app-main ignore-select">
                    <?php
                    get_template_part('template/loop/images');
                    get_template_part('template/loop/apk_info');
                    ?>
                    </div>
					<?php
                    get_template_part('template/telegram');
                    ex_themes_adv_single_page_();
                    if($opt_themes['aktif_ex_themes_gallery_images_gpstore_']) {
                        ex_themes_gallery_images_gpstore_dt();
                    }
                    ?>
                </div>
                <?php get_template_part('template/loop/background'); ?>
            </div>

            <div class="wrp-min block-list">
                <?php get_template_part('template/loop/content'); ?>
                <div class="anchor-line"><span id="download-block"></span></div>
                <?php
                get_template_part('template/loop/download');
                ex_themes_version_dt();
                comments_template();
                ?>
            </div>
        </article>
        <?php ex_themes_related_posts_dt(); ?>
    </div>
	
<?php
get_footer(); 