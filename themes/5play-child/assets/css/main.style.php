<?php global $opt_themes;
$active_plugins             = get_option( 'active_plugins' );
$rtl_on = false;
if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
    if ( apply_filters( 'wpml_is_rtl', NULL) ) {
        $rtl_on = true;

    }
}

if($opt_themes['ex_themes_rtl_activate_'] || $rtl_on) {

get_template_part( '/assets/css.rtl/root.styles' ); ?>

<link id="core-rtl-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css.rtl/core.css" type="text/css" rel="stylesheet">
<link id="styles-rtl-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css.rtl/styles.css" type="text/css" rel="stylesheet">
<link id="short-rtl-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css.rtl/short.css" type="text/css" rel="stylesheet">
<link id="fullstory-rtl-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css.rtl/fullstory.css" type="text/css" rel="stylesheet">
<link id="comments-rtl-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css.rtl/comments.css" type="text/css" rel="stylesheet">
<link id="other-rtl-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css.rtl/other.css" type="text/css" rel="stylesheet">
<?php if ( is_user_logged_in() ) { ?>
<link id="user-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css/user.styles.css" type="text/css" rel="stylesheet">
<?php } ?>
<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/manrope-v3-cyrillic-700.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/manrope-v3-cyrillic-regular.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/css/cores.styles.css" as="style">
<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/css/styles.css" as="style">
<?php } else {  ?>        
<link id="cores-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/cores.styles.css" type="text/css" rel="stylesheet">
<link id="style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css/styles.css" type="text/css" rel="stylesheet">
<link id="short-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css/short.styles.css" type="text/css" rel="stylesheet">
<link id="fullstory-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css/fullstory.styles.css" type="text/css" rel="stylesheet">
<link id="comments-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css/comments.styles.css" type="text/css" rel="stylesheet">
<link id="other-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css/other.styles.css" type="text/css" rel="stylesheet">
<?php if ( is_user_logged_in() ) { ?>
<link id="user-style-<?php echo EX_THEMES_NAMES_; ?>-v.<?php echo EXTHEMES_VERSION; ?>" href="<?php echo get_template_directory_uri(); ?>/assets/css/user.styles.css" type="text/css" rel="stylesheet">
<?php } ?>
<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/manrope-v3-cyrillic-700.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/manrope-v3-cyrillic-regular.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/css/cores.styles.css?v=67" as="style">
<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/css/styles.css" as="style">
<?php } 