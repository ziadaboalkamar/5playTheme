<?php
global $opt_themes;
$fav_on						= $opt_themes['aktif_favicon'];
$fav						= $opt_themes['favicon']['url'];
$scroll_up					= $opt_themes['exthemes_Scroll_up'];
//print_r($opt_themes);
if (is_home() || is_front_page()) {?>
	<div class="dark-foot dark-section"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('home-footers')): endif; ?>
        <a class="upper scrollup" href="#" title="<?php echo get_option("blogname") ?>"><span class="sr-only"><?php if($scroll_up) { ?><?php echo $scroll_up; ?><?php } ?></span><svg width="24" height="24"><use xlink:href="#i__scrollup"></use></svg></a>

        <footer class="footer-01">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb_dt mb-md-0">
                        <h2 class="footer-heading"><?php echo get_option('blogname'); ?></h2>
                        <p>  <?php if ( is_active_sidebar( 'footer-widget-area-1' ) ) : ?>
                                <?php dynamic_sidebar( 'footer-widget-area-1' ); ?>
                            <?php endif; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-3 mb_dt mb-md-0">
                        <h2 class="footer-heading"><?php echo esc_html__('Latest News', CHILD_THEME); ?></h2>
                     <?php get_last_news(); ?>
                    </div>
                    <div class="col-md-6 col-lg-3 pl-lg-5 mb_dt mb-md-0">
                        <h2 class="footer-heading"><?php echo esc_html__('Quick Links', CHILD_THEME); ?></h2>
                        <ul class="list-unstyled">
                            <?php if ( is_active_sidebar( 'footer-widget-area-2' ) ) : ?>
                                <?php dynamic_sidebar( 'footer-widget-area-2' ); ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="col-md-6 col-lg-3 mb_dt mb-md-0">
                        <h2 class="footer-heading"><?php echo esc_html__('Find Us', CHILD_THEME); ?></h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <?php if ( is_active_sidebar( 'footer-widget-area-3' ) ) : ?>


                               <li><a href="#"><i class="c-icon dt_icons"><svg height="30px" width="30px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                       viewBox="0 0 512 512"  xml:space="preserve"><g>
                                                    <path class="st0" d="M440.917,67.925H71.083C31.827,67.925,0,99.752,0,139.008v233.984c0,39.256,31.827,71.083,71.083,71.083
                                                    h369.834c39.255,0,71.083-31.827,71.083-71.083V139.008C512,99.752,480.172,67.925,440.917,67.925z M178.166,321.72l-99.54,84.92
                                                    c-7.021,5.992-17.576,5.159-23.567-1.869c-5.992-7.021-5.159-17.576,1.87-23.567l99.54-84.92c7.02-5.992,17.574-5.159,23.566,1.87
                                                    C186.027,305.174,185.194,315.729,178.166,321.72z M256,289.436c-13.314-0.033-26.22-4.457-36.31-13.183l0.008,0.008l-0.032-0.024
                                                    c0.008,0.008,0.017,0.008,0.024,0.016L66.962,143.694c-6.98-6.058-7.723-16.612-1.674-23.583c6.057-6.98,16.612-7.723,23.582-1.674
                                                    l152.771,132.592c3.265,2.906,8.645,5.004,14.359,4.971c5.706,0.017,10.995-2.024,14.44-5.028l0.074-0.065l152.615-132.469
                                                    c6.971-6.049,17.526-5.306,23.583,1.674c6.048,6.97,5.306,17.525-1.674,23.583l-152.77,132.599
                                                    C282.211,284.929,269.322,289.419,256,289.436z M456.948,404.771c-5.992,7.028-16.547,7.861-23.566,1.869l-99.54-84.92
                                                    c-7.028-5.992-7.861-16.546-1.869-23.566c5.991-7.029,16.546-7.861,23.566-1.87l99.54,84.92
                                                    C462.107,387.195,462.94,397.75,456.948,404.771z"/>
                                                                                            </g>
                                                    </svg></i><span class="text">  <?php dynamic_sidebar( 'footer-widget-area-3' ); ?></span></a></li>
                                <?php endif; ?>

                                <?php echo ex_themes_footers_social_media_(); ?>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12 text-center">

                        <p class="copyright">

                            <?php ex_themes_copyright_(); ?>

                        </p>
                    </div>
                </div>
            </div>
        </footer>

        <div class="background" style="display:none;">
        <i class="bg-circle-green"></i>
        <i class="bg-clouds"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2550 700" width="2550" height="700"><path fill="#142636" d="M554,600h-4a50,50,0,0,0,0,100h4a50,50,0,0,0,0-100ZM802,0H50a50,50,0,0,0,0,100h78.08a50,50,0,0,1,.11,100H98a50,50,0,0,0,0,100H238a50,50,0,1,1,0,100H194a50,50,0,0,0,0,100h56a50,50,0,0,1,.3,100H238a50,50,0,0,0,0,100H430a50,50,0,0,0,0-100H413.55a50,50,0,0,1,.05-100H630a50,50,0,0,0,0-100H521.55a50,50,0,0,1,0-100H526a50,50,0,0,0,0-100H445.55a50,50,0,0,1,.08-100H802A50,50,0,0,0,802,0Z" /><path fill="#142636" d="M2073,500a50,50,0,0,1,0-100h16.43a50,50,0,0,0,0-100H1901a50,50,0,0,1,0-100h216.42a49.92,49.92,0,0,0,34.94-14.64,50,50,0,0,0-34.9-85.36H2097a50,50,0,0,1-50-50h0a50,50,0,0,1,50-50h252a50,50,0,0,1,0,100h-28a50,50,0,1,0,0,100h24a50,50,0,0,1,0,100h-24a50,50,0,1,0,0,100h179a50,50,0,0,1,0,100Z" /></svg></i>
    </div>
</div>
<?php
ex_themes_footer_on_sections_();
  } elseif (is_single() || is_page() || is_search() || is_archive() || is_404() || is_tag()) {
?>
    <a class="upper scrollup" href="#" title="<?php echo get_option("blogname") ?>"><span class="sr-only"><?php if($scroll_up) { ?><?php echo $scroll_up; ?><?php } ?></span><svg width="24" height="24"><use xlink:href="#i__scrollup"></use></svg></a>

    <footer class="footer-01">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 mb_dt mb-md-0">
                    <h2 class="footer-heading"><?php echo get_option('blogname'); ?></h2>
                    <p>  <?php if ( is_active_sidebar( 'footer-widget-area-1' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-widget-area-1' ); ?>
                        <?php endif; ?></p>
                </div>
                <div class="col-md-6 col-lg-3 mb_dt mb-md-0">
                    <h2 class="footer-heading"><?php echo esc_html__('Latest News', CHILD_THEME); ?></h2>
                    <?php get_last_news(); ?>
                </div>
                <div class="col-md-6 col-lg-3 pl-lg-5 mb_dt mb-md-0">
                    <h2 class="footer-heading"><?php echo esc_html__('Quick Links', CHILD_THEME); ?></h2>
                    <ul class="list-unstyled">
                        <?php if ( is_active_sidebar( 'footer-widget-area-2' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-widget-area-2' ); ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-3 mb_dt mb-md-0">
                    <h2 class="footer-heading"><?php echo esc_html__('Find Us', CHILD_THEME); ?></h2>
                    <div class="block-23 mb-3">
                        <ul>
                            <?php if ( is_active_sidebar( 'footer-widget-area-3' ) ) : ?>


                                <li><a href="#"><i class="c-icon dt_icons"><svg height="30px" width="30px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                viewBox="0 0 512 512"  xml:space="preserve"><g>
                                                    <path class="st0" d="M440.917,67.925H71.083C31.827,67.925,0,99.752,0,139.008v233.984c0,39.256,31.827,71.083,71.083,71.083
                                                    h369.834c39.255,0,71.083-31.827,71.083-71.083V139.008C512,99.752,480.172,67.925,440.917,67.925z M178.166,321.72l-99.54,84.92
                                                    c-7.021,5.992-17.576,5.159-23.567-1.869c-5.992-7.021-5.159-17.576,1.87-23.567l99.54-84.92c7.02-5.992,17.574-5.159,23.566,1.87
                                                    C186.027,305.174,185.194,315.729,178.166,321.72z M256,289.436c-13.314-0.033-26.22-4.457-36.31-13.183l0.008,0.008l-0.032-0.024
                                                    c0.008,0.008,0.017,0.008,0.024,0.016L66.962,143.694c-6.98-6.058-7.723-16.612-1.674-23.583c6.057-6.98,16.612-7.723,23.582-1.674
                                                    l152.771,132.592c3.265,2.906,8.645,5.004,14.359,4.971c5.706,0.017,10.995-2.024,14.44-5.028l0.074-0.065l152.615-132.469
                                                    c6.971-6.049,17.526-5.306,23.583,1.674c6.048,6.97,5.306,17.525-1.674,23.583l-152.77,132.599
                                                    C282.211,284.929,269.322,289.419,256,289.436z M456.948,404.771c-5.992,7.028-16.547,7.861-23.566,1.869l-99.54-84.92
                                                    c-7.028-5.992-7.861-16.546-1.869-23.566c5.991-7.029,16.546-7.861,23.566-1.87l99.54,84.92
                                                    C462.107,387.195,462.94,397.75,456.948,404.771z"/>
                                                </g>
                                                    </svg></i><span class="text">  <?php dynamic_sidebar( 'footer-widget-area-3' ); ?></span></a></li>
                            <?php endif; ?>

                            <?php echo ex_themes_footers_social_media_(); ?>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12 text-center">

                    <p class="copyright">

                        <?php ex_themes_copyright_(); ?>

                    </p>
                </div>
            </div>
        </div>
    </footer>

    <?php
ex_themes_footer_on_sections_();
}
wp_footer();
maintenances_notices();
?>


</body>
<script>

    (function($) {

        "use strict";

        $('[data-toggle="tooltip"]').tooltip()

    })(jQuery);
</script>
</html>