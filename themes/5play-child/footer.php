<<<<<<< HEAD
<?php 
global $opt_themes;
$fav_on						= $opt_themes['aktif_favicon']; 
$fav						= $opt_themes['favicon']['url']; 
$scroll_up					= $opt_themes['exthemes_Scroll_up']; 
if (is_home() || is_front_page()) { 
if (function_exists('is_dynamic_sidebar') && is_active_sidebar('home-footers')){ ?>
	<div class="dark-foot dark-section"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('home-footers')): endif; ?>    
	<footer class="footer">
        <div class="wrp-min">
            <div class="footer-panel">
                <a class="logotype scrollup" style="display:none;" href="#" title="<?php echo get_option("blogname") ?>">
                    <span class="sr-only"><?php echo get_option("blogname") ?></span>
                    <i class="logo-icon"><img src="<?php global $opt_themes; if($fav_on) { ?><?php echo $fav; ?><?php } else { ?><?php echo get_template_directory_uri(); ?>/assets/img/logo_footer.png<?php } ?>"  width="36" height="36" alt="<?php echo get_option("blogname") ?>"></i>
				</a>
                <a class="upper scrollup" href="#" title="<?php echo get_option("blogname") ?>"><span class="sr-only"><?php if($scroll_up) { ?><?php echo $scroll_up; ?><?php } ?></span><svg width="24" height="24"><use xlink:href="#i__scrollup"></use></svg></a>
                <div class="footer-cont">
                    <div class="copyright"><?php ex_themes_copyright_(); ?></div>
                    <?php ex_themes_footers_social_media_(); ?>
                </div>
            </div>
        </div>
    </footer>
	<div class="background" style="display:none;">
=======
<?php
global $opt_themes;
$fav_on						= $opt_themes['aktif_favicon'];
$fav						= $opt_themes['favicon']['url'];
$scroll_up					= $opt_themes['exthemes_Scroll_up'];
//print_r($opt_themes);
if (is_home() || is_front_page()) {?>
	<div class="dark-foot dark-section"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('home-footers')): endif; ?>
        <footer class="footer-01">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                        <h2 class="footer-heading"><?php echo get_option('blogname'); ?></h2>
                        <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
                        <?php echo ex_themes_footers_social_media_(); ?>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                        <h2 class="footer-heading">Latest News</h2>
                     <?php get_last_news(); ?>
                    </div>
                    <div class="col-md-6 col-lg-3 pl-lg-5 mb-4 mb-md-0">
                        <h2 class="footer-heading">Quick Links</h2>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-2 d-block">Home</a></li>
                            <li><a href="#" class="py-2 d-block">About</a></li>
                            <li><a href="#" class="py-2 d-block">Services</a></li>
                            <li><a href="#" class="py-2 d-block">Works</a></li>
                            <li><a href="#" class="py-2 d-block">Blog</a></li>
                            <li><a href="#" class="py-2 d-block">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                        <h2 class="footer-heading">Have a Questions?</h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <li><i class="c-icon dt_icons"><svg fill="white"  width="30px" height="30px" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path d="M49,18.92A23.74,23.74,0,0,0,25.27,42.77c0,16.48,17,31.59,22.23,35.59a2.45,2.45,0,0,0,3.12,0c5.24-4.12,22.1-19.11,22.1-35.59A23.74,23.74,0,0,0,49,18.92Zm0,33.71a10,10,0,1,1,10-10A10,10,0,0,1,49,52.63Z"/></svg></i>
                                    <span class="text">203 Fake St. Mountain View, San Francisco, California, USA</span></li>
                                <li><a href="#"><i class="c-icon dt_icons"><svg fill="white" width="30px" height="30px" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg">
                                                <path fill="white"  d="M15.1008 15.0272L15.6446 15.5437V15.5437L15.1008 15.0272ZM15.5562 14.5477L15.0124 14.0312V14.0312L15.5562 14.5477ZM17.9729 14.2123L17.5987 14.8623H17.5987L17.9729 14.2123ZM19.8834 15.312L19.5092 15.962L19.8834 15.312ZM20.4217 18.7584L20.9655 19.275L20.9655 19.2749L20.4217 18.7584ZM19.0012 20.254L18.4574 19.7375L19.0012 20.254ZM17.6763 20.9631L17.75 21.7095L17.6763 20.9631ZM7.8154 16.4752L8.3592 15.9587L7.8154 16.4752ZM3.75185 6.92574C3.72965 6.51212 3.37635 6.19481 2.96273 6.21701C2.54911 6.23921 2.23181 6.59252 2.25401 7.00613L3.75185 6.92574ZM9.19075 8.80507L9.73454 9.32159L9.19075 8.80507ZM9.47756 8.50311L10.0214 9.01963L9.47756 8.50311ZM9.63428 5.6931L10.2467 5.26012L9.63428 5.6931ZM8.3733 3.90961L7.7609 4.3426V4.3426L8.3733 3.90961ZM4.7177 3.09213C4.43244 3.39246 4.44465 3.86717 4.74498 4.15244C5.04531 4.4377 5.52002 4.42549 5.80529 4.12516L4.7177 3.09213ZM11.0632 13.0559L11.607 12.5394L11.0632 13.0559ZM10.6641 19.8123C11.0148 20.0327 11.4778 19.9271 11.6982 19.5764C11.9186 19.2257 11.8129 18.7627 11.4622 18.5423L10.6641 19.8123ZM15.113 20.0584C14.7076 19.9735 14.3101 20.2334 14.2252 20.6388C14.1403 21.0442 14.4001 21.4417 14.8056 21.5266L15.113 20.0584ZM15.6446 15.5437L16.1 15.0642L15.0124 14.0312L14.557 14.5107L15.6446 15.5437ZM17.5987 14.8623L19.5092 15.962L20.2575 14.662L18.347 13.5623L17.5987 14.8623ZM19.8779 18.2419L18.4574 19.7375L19.545 20.7705L20.9655 19.275L19.8779 18.2419ZM8.3592 15.9587C4.48307 11.8778 3.83289 8.43556 3.75185 6.92574L2.25401 7.00613C2.35326 8.85536 3.13844 12.6403 7.27161 16.9917L8.3592 15.9587ZM9.73454 9.32159L10.0214 9.01963L8.93377 7.9866L8.64695 8.28856L9.73454 9.32159ZM10.2467 5.26012L8.98569 3.47663L7.7609 4.3426L9.02189 6.12608L10.2467 5.26012ZM9.19075 8.80507C8.64695 8.28856 8.64626 8.28929 8.64556 8.29002C8.64533 8.29028 8.64463 8.29102 8.64415 8.29152C8.6432 8.29254 8.64223 8.29357 8.64125 8.29463C8.63928 8.29675 8.63724 8.29896 8.63515 8.30127C8.63095 8.30588 8.6265 8.31087 8.62182 8.31625C8.61247 8.32701 8.60219 8.33931 8.5912 8.3532C8.56922 8.38098 8.54435 8.41511 8.51826 8.45588C8.46595 8.53764 8.40921 8.64531 8.36117 8.78033C8.26346 9.0549 8.21022 9.4185 8.27675 9.87257C8.40746 10.7647 8.99202 11.9644 10.5194 13.5724L11.607 12.5394C10.1793 11.0363 9.82765 10.1106 9.7609 9.65511C9.72871 9.43536 9.76142 9.31957 9.77436 9.28321C9.78163 9.26277 9.78639 9.25709 9.78174 9.26437C9.77948 9.26789 9.77498 9.27451 9.76742 9.28407C9.76363 9.28885 9.75908 9.29437 9.75364 9.30063C9.75092 9.30375 9.74798 9.30706 9.7448 9.31056C9.74321 9.31231 9.74156 9.3141 9.73985 9.31594C9.739 9.31686 9.73813 9.31779 9.73724 9.31873C9.7368 9.3192 9.73612 9.31992 9.7359 9.32015C9.73522 9.32087 9.73454 9.32159 9.19075 8.80507ZM10.5194 13.5724C12.0422 15.1757 13.1924 15.806 14.0699 15.9485C14.5201 16.0216 14.8846 15.9632 15.1606 15.8544C15.2955 15.8012 15.4023 15.7387 15.4824 15.6819C15.5223 15.6535 15.5556 15.6266 15.5825 15.6031C15.5959 15.5913 15.6078 15.5803 15.6181 15.5703C15.6233 15.5654 15.628 15.5606 15.6324 15.5562C15.6346 15.554 15.6368 15.5518 15.6388 15.5497C15.6398 15.5487 15.6408 15.5477 15.6417 15.5467C15.6422 15.5462 15.6429 15.5454 15.6432 15.5452C15.6439 15.5444 15.6446 15.5437 15.1008 15.0272C14.557 14.5107 14.5577 14.51 14.5583 14.5093C14.5586 14.509 14.5592 14.5083 14.5597 14.5078C14.5606 14.5069 14.5615 14.506 14.5623 14.5051C14.5641 14.5033 14.5658 14.5015 14.5675 14.4998C14.5708 14.4965 14.574 14.4933 14.577 14.4904C14.5831 14.4846 14.5885 14.4796 14.5933 14.4754C14.6029 14.467 14.61 14.4616 14.6146 14.4584C14.6239 14.4517 14.623 14.454 14.6102 14.459C14.5909 14.4666 14.5001 14.4987 14.3103 14.4679C13.9078 14.4025 13.0391 14.0472 11.607 12.5394L10.5194 13.5724ZM8.98569 3.47663C7.9721 2.04305 5.94388 1.80119 4.7177 3.09213L5.80529 4.12516C6.32812 3.57471 7.24855 3.61795 7.7609 4.3426L8.98569 3.47663ZM18.4574 19.7375C18.1783 20.0313 17.8864 20.1887 17.6026 20.2167L17.75 21.7095C18.497 21.6357 19.1016 21.2373 19.545 20.7705L18.4574 19.7375ZM10.0214 9.01963C10.9889 8.00095 11.0574 6.40678 10.2467 5.26012L9.02189 6.12608C9.44404 6.72315 9.3793 7.51753 8.93377 7.9866L10.0214 9.01963ZM19.5092 15.962C20.3301 16.4345 20.4907 17.5968 19.8779 18.2419L20.9655 19.2749C22.2705 17.901 21.8904 15.6019 20.2575 14.662L19.5092 15.962ZM16.1 15.0642C16.4854 14.6584 17.086 14.5672 17.5987 14.8623L18.347 13.5623C17.2485 12.93 15.8862 13.1113 15.0124 14.0312L16.1 15.0642ZM11.4622 18.5423C10.4785 17.9241 9.43149 17.0876 8.3592 15.9587L7.27161 16.9917C8.42564 18.2067 9.56897 19.1241 10.6641 19.8123L11.4622 18.5423ZM17.6026 20.2167C17.0561 20.2707 16.1912 20.2842 15.113 20.0584L14.8056 21.5266C16.0541 21.788 17.0742 21.7762 17.75 21.7095L17.6026 20.2167Z" fill="#1C274C"/>
                                            </svg></i><span class="text">+2 392 3929 210</span></a></li>
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
                                                    </svg></i><span class="text">info@yourdomain.com</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12 text-center">

                        <p class="copyright"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->

                            <?php ex_themes_copyright_(); ?>
                           </p>
                    </div>
                </div>
            </div>
        </footer>

        <div class="background" style="display:none;">
>>>>>>> 29e03d40789390669c5cfdec1dacc71ac59f0467
        <i class="bg-circle-green"></i>
        <i class="bg-clouds"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2550 700" width="2550" height="700"><path fill="#142636" d="M554,600h-4a50,50,0,0,0,0,100h4a50,50,0,0,0,0-100ZM802,0H50a50,50,0,0,0,0,100h78.08a50,50,0,0,1,.11,100H98a50,50,0,0,0,0,100H238a50,50,0,1,1,0,100H194a50,50,0,0,0,0,100h56a50,50,0,0,1,.3,100H238a50,50,0,0,0,0,100H430a50,50,0,0,0,0-100H413.55a50,50,0,0,1,.05-100H630a50,50,0,0,0,0-100H521.55a50,50,0,0,1,0-100H526a50,50,0,0,0,0-100H445.55a50,50,0,0,1,.08-100H802A50,50,0,0,0,802,0Z" /><path fill="#142636" d="M2073,500a50,50,0,0,1,0-100h16.43a50,50,0,0,0,0-100H1901a50,50,0,0,1,0-100h216.42a49.92,49.92,0,0,0,34.94-14.64,50,50,0,0,0-34.9-85.36H2097a50,50,0,0,1-50-50h0a50,50,0,0,1,50-50h252a50,50,0,0,1,0,100h-28a50,50,0,1,0,0,100h24a50,50,0,0,1,0,100h-24a50,50,0,1,0,0,100h179a50,50,0,0,1,0,100Z" /></svg></i>
    </div>
</div>
<<<<<<< HEAD
<?php 
ex_themes_footer_on_sections_(); 
} else { 
?>
<footer class="footer">
        <div class="wrp-min">
            <div class="footer-panel">
                <a class="logotype scrollup" style="display:none;" href="#" title="<?php echo get_option("blogname") ?>">
                    <span class="sr-only"><?php echo get_option("blogname") ?></span>
                    <i class="logo-icon"><img alt="<?php echo get_option("blogname") ?>" src="<?php if($fav_on) { ?><?php echo $fav; ?><?php } else { ?><?php echo get_template_directory_uri(); ?>/assets/img/logo_footer.png<?php } ?>" width="36" height="36"></i>
				</a>
                <a class="upper scrollup" href="#" title="<?php echo get_option("blogname") ?>"><span class="sr-only"><?php if($scroll_up) { ?><?php echo $scroll_up; ?><?php } ?></span><svg width="24" height="24"><use xlink:href="#i__scrollup"></use></svg></a>
                <div class="footer-cont">
                    <div class="copyright"><?php ex_themes_copyright_(); ?></div>
                    <?php ex_themes_footers_social_media_(); ?>
                </div>
            </div>
        </div>
    </footer>
<?php 
ex_themes_footer_on_sections_(); 
}  } elseif (is_single() || is_page() || is_search() || is_archive() || is_404() || is_tag()) { 
?>
<footer class="footer">
        <div class="wrp-min">
            <div class="footer-panel">
                <a class="logotype scrollup" style="display:none;" href="#" title="<?php echo get_option("blogname") ?>">
                    <span class="sr-only"><?php echo get_option("blogname") ?></span>
                    <i class="logo-icon"><img alt="<?php echo get_option("blogname") ?>" src="<?php if($fav_on) { ?><?php echo $fav; ?><?php } else { ?><?php echo get_template_directory_uri(); ?>/assets/img/logo_footer.png<?php } ?>"  width="36" height="36"></i>
				</a>
                <a class="upper scrollup" href="#" title="<?php echo get_option("blogname") ?>"><span class="sr-only"><?php if($scroll_up) { ?><?php echo $scroll_up; ?><?php } ?></span><svg width="24" height="24"><use xlink:href="#i__scrollup"></use></svg></a>
                <div class="footer-cont">
                    <div class="copyright"><?php ex_themes_copyright_(); ?></div>
                    <?php ex_themes_footers_social_media_(); ?>
                </div>
            </div>
        </div>
    </footer>
<?php 
ex_themes_footer_on_sections_(); 
} 
wp_footer(); 
=======
<?php
ex_themes_footer_on_sections_();
  } elseif (is_single() || is_page() || is_search() || is_archive() || is_404() || is_tag()) {
?>


    <?php
ex_themes_footer_on_sections_();
}
wp_footer();
>>>>>>> 29e03d40789390669c5cfdec1dacc71ac59f0467
maintenances_notices();
?>


</body>
<<<<<<< HEAD
=======
<script>

    (function($) {

        "use strict";

        $('[data-toggle="tooltip"]').tooltip()

    })(jQuery);
</script>
>>>>>>> 29e03d40789390669c5cfdec1dacc71ac59f0467
</html>