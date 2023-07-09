<?php

use Inc\Base\BaseController;
require_once WP_PLUGIN_DIR . '/dt-apps-scrapper/inc/Base/BaseController.php';
$child_domain = "dt-5play";
define("CHILD_THEME",$child_domain);
add_action('after_setup_theme', 'dt_setup');
function dt_setup() {
//    $cld= new CLD_Activation();
//    $default_settings = $cld->get_default_settings();
//    if(!get_option('cld_settings')){
//        update_option('cld_settings',$default_settings);
//    }
    // load_theme_textdomain('InstaplusChild', get_stylesheet_directory() . '/languages');
    ////////////////////load_child_theme_textdomain('instaplus-child', get_stylesheet_directory() . '/languages');
    $path = get_stylesheet_directory() . '/languages';
    $result = load_child_theme_textdomain('dt-5play', $path);

    if ( $result )
        return;

    $locale = apply_filters( 'theme_locale', get_locale(), '5play-child' );
//    echo ( "Could not find $path/$locale.mo." );
}
function get_key_option($post_id , $key){
    global $wpdb;
    $base= new \Inc\Base\BaseController();
    $value = "";
    $table_dt_meta = $base->table_dt_meta;
    $table_meta_app_post = $base->table_app_post;
    $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
    if ($results_of_post){
    $app_id = $results_of_post->app_id;
    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE status = 1 AND `key` = '{$key}' AND app_id = %d", $app_id ) );
    if ($results){
        $value = $results->value;
    }else{
            $value = "";
        }
    }else{
        $active_plugins             = get_option( 'active_plugins' );
        if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
            $current_language = apply_filters('wpml_current_language', NULL);
            $table_post_mapping_lang = $wpdb->prefix."icl_translations";
            $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
            $post_lang_id = $post_result->trid;
            $meta_app_count_child = 0;

            $query = $wpdb->prepare(
                "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
                $post_lang_id
            );
            $results = $wpdb->get_results($query, ARRAY_A);
            $element_ids = wp_list_pluck($results, 'element_id');
            $element_ids = array_map('intval', $element_ids);
            $element_ids = array_filter($element_ids);

            $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
            $query = $wpdb->prepare(
                "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ({$placeholders})",
                $element_ids
            );
            $matching_posts = $wpdb->get_col($query);
            if (isset($matching_posts[0]) ){


            $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $matching_posts[0] ) );
            if ($results_of_post){
                $app_id = $results_of_post->app_id;
                $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE status = 1 AND `key` = '{$key}' AND app_id = %d", $app_id ) );
                if ($results) {
                    $value = $results->value;
                }
            }
            }else{
                $value = "";
            }
        }else{
            $value = "";

        }

    }
    return $value;

}

function get_package($post_id){
    global $wpdb;
    $value = "";
    $base= new \Inc\Base\BaseController();
    $table_app = $base->table_app_info;
    $table_meta_app_post = $base->table_app_post;
     $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
     if ($results_of_post){
     $app_id = $results_of_post->app_id;
    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_app} WHERE id = %d", $app_id ) );
    if ($results){
        $value = $results->package_name;
    }else{
        $value = "";
    }
     }else{

         $active_plugins             = get_option( 'active_plugins' );
         if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
             $current_language = apply_filters('wpml_current_language', NULL);
             $table_post_mapping_lang = $wpdb->prefix."icl_translations";
             $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
             $post_lang_id = $post_result->trid;
             $meta_app_count_child = 0;

             $query = $wpdb->prepare(
                 "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
                 $post_lang_id
             );
             $results = $wpdb->get_results($query, ARRAY_A);
             $element_ids = wp_list_pluck($results, 'element_id');
             $element_ids = array_map('intval', $element_ids);
             $element_ids = array_filter($element_ids);

             $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
             $query = $wpdb->prepare(
                 "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ({$placeholders})",
                 $element_ids
             );
             $matching_posts = $wpdb->get_col($query);
             if (isset($matching_posts[0])){


             $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $matching_posts[0] ) );
             if ($results_of_post){
                 $app_id = $results_of_post->app_id;
                 $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_app} WHERE id = %d", $app_id ) );
                 if ($results) {
                     $value = $results->package_name;
                 }
             }
             }else{
                 $value = "";
             }
         }else{
             $value = "";

         }
     }
    return $value;

}

function get_dt_title($post_id){
    global $wpdb;
    $value = "";

    $base= new \Inc\Base\BaseController();
    $value = "";
    $table_dt_meta = $base->table_dt_meta;
    $table_meta_app_post = $base->table_app_post;
    $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
    if ($results_of_post){
    $app_id = $results_of_post->app_id;
    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE `key` = 'app_name' AND app_id = %d", $app_id ) );
    if ($results){
        $value = $results->value;
    }else{
        $value = "";
    }
}else{
        $active_plugins             = get_option( 'active_plugins' );
        if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
            $current_language = apply_filters('wpml_current_language', NULL);
            $table_post_mapping_lang = $wpdb->prefix."icl_translations";
            $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
            $post_lang_id = $post_result->trid;
            $meta_app_count_child = 0;

            $query = $wpdb->prepare(
                "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
                $post_lang_id
            );
            $results = $wpdb->get_results($query, ARRAY_A);
            $element_ids = wp_list_pluck($results, 'element_id');
            $element_ids = array_map('intval', $element_ids);
            $element_ids = array_filter($element_ids);

            $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
            $query = $wpdb->prepare(
                "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ({$placeholders})",
                $element_ids
            );
            $matching_posts = $wpdb->get_col($query);
            if (isset($matching_posts[0])){
                $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $matching_posts[0] ) );
                if ($results_of_post){
                    $app_id = $results_of_post->app_id;
                    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE status = 1 AND `key` = 'app_name' AND app_id = %d", $app_id ) );
                    if ($results) {
                        $value = $results->value;
                    }
                }
            }

        }else{
            $value = "";

        }

    }
    return $value;
}

function get_dt_get_settings($key){
    global $wpdb;
    $base= new \Inc\Base\BaseController();
    $value = "";
    $table_dt_setting = $base->table_settings;
        $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_dt_setting} WHERE `key` = %d",$key ) );
        if ($results){
            $value = $results->value;
        }else{
            $value = "";
        }

    return $value;
}

function get_old_version_file($post_id){
    global $wpdb;
    $base = new BaseController();
    $table_app = $base->table_app_info;
    $table_settings = $base->table_settings;
    $table_meta_app_post = $base->table_app_post;
    $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
    $id =  intval($results_of_post->app_id);
    $app = $wpdb->get_row("SELECT * FROM {$table_app} WHERE `id` = '{$id}'");
    $api_url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
    $token =  $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_token'");
    if ($app){
        $app_id = intval($app->api_app_id);
        $client = new \GuzzleHttp\Client();
        $response = $client->get("{$api_url->value}/api/project/old_files?app_id={$app_id}",[
            'headers' => [
                'Authorization' => 'Bearer ' . $token->value,
                'Accept'        => 'application/json',
            ],
        ]);
        $data = json_decode($response->getBody(), true);
        if ($data["status"] == 200){
            $data = [
                "success" => true,
                "files" => $data["old_files"],
                "api_url" => $api_url->value
            ];
            return $data;
        }
    }else{
        $active_plugins             = get_option( 'active_plugins' );
        if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
            $current_language = apply_filters('wpml_current_language', NULL);
            $table_post_mapping_lang = $wpdb->prefix."icl_translations";
            $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
            $post_lang_id = $post_result->trid;
            $meta_app_count_child = 0;

            $query = $wpdb->prepare(
                "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
                $post_lang_id
            );
            $results = $wpdb->get_results($query, ARRAY_A);
            $element_ids = wp_list_pluck($results, 'element_id');
            $element_ids = array_map('intval', $element_ids);
            $element_ids = array_filter($element_ids);

            $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
            $query = $wpdb->prepare(
                "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
                $element_ids
            );
            $matching_posts = $wpdb->get_col($query);
            $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $matching_posts[0] ) );
            if ($results_of_post){
                $app = $wpdb->get_row("SELECT * FROM {$table_app} WHERE `id` = '{$results_of_post->app_id}'");
                $api_url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
                $token =  $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_token'");
                if ($app){
                    $app_id = intval($app->api_app_id);
                    $client = new \GuzzleHttp\Client();
                    $response = $client->get("{$api_url->value}/api/project/old_files?app_id={$app_id}",[
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token->value,
                            'Accept'        => 'application/json',
                        ],
                    ]);
                    $data = json_decode($response->getBody(), true);
                    if ($data["status"] == 200){
                        $data = [
                            "success" => true,
                            "files" => $data["old_files"],
                            "api_url" => $api_url->value
                        ];
                        return $data;
            }
        }else{
            $value = "";

        }
    }
        }
    }




}
function update_key_option($post_id , $key,$value){
    global $wpdb;
    $base= new \Inc\Base\BaseController();
    $table_dt_meta = $base->table_dt_meta;
    $table_meta_app_post = $base->table_app_post;
    $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
    if ($results_of_post) {
        $app_id = $results_of_post->app_id;
        $wpdb->update($table_dt_meta, array(
            'value' => $value,
        ), array(
            'key' => $key, 'app_id' => $app_id
        ));
    }else{
        $active_plugins             = get_option( 'active_plugins' );
        if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
            $current_language = apply_filters('wpml_current_language', NULL);
            $table_post_mapping_lang = $wpdb->prefix."icl_translations";
            $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
            $post_lang_id = $post_result->trid;
            $meta_app_count_child = 0;

            $query = $wpdb->prepare(
                "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
                $post_lang_id
            );
            $results = $wpdb->get_results($query, ARRAY_A);
            $element_ids = wp_list_pluck($results, 'element_id');
            $element_ids = array_map('intval', $element_ids);
            $element_ids = array_filter($element_ids);

            $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
            $query = $wpdb->prepare(
                "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ({$placeholders})",
                $element_ids
            );
            $matching_posts = $wpdb->get_col($query);
            $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $matching_posts[0] ) );
            if ($results_of_post){
                $app_id = $results_of_post->app_id;
                $wpdb->update($table_dt_meta, array(
                    'value' => $value,
                ), array(
                    'key' => $key, 'app_id' => $app_id
                ));
            }
        }
    }
    return true;

}


function ex_themes_version_dt() {
    global $opt_themes, $wpdb, $post, $wp_query;
    $latest_version_on			= $opt_themes['activated_latest_version'];
    $search						= get_post_meta( $post->ID, 'wp_title_GP', true );
    $search						= preg_replace('/[^A-Za-z0-9\-]/', ' ', $search);
    $wp_gp_id					= get_post_meta( $post->ID, 'wp_GP_ID', true );
    $DT_package                 = get_package($post->ID);
    if ($DT_package && $DT_package!= ""){
        $wp_gp_id = $DT_package;
    }

//$search					= str_replace(array(':','-'), '', $search);
    $version_gp					= get_post_meta( $post->ID, 'wp_version_GP', true );
    $version_sc					= get_post_meta( get_the_ID(), 'wp_version', true );
//if ( $version_gp === FALSE or $version_gp == '' ) $version_gp = $version_sc;
    $appname_on					= $opt_themes['title_app_name_active_'];
    $title						= get_post_meta( $post->ID, 'wp_title_GP', true );
    $title_alt					= get_the_title();
    if ($latest_version_on) { if($wp_gp_id){
        ?>
        <div class="block ">
            <div class="box_download box_shadow">
                <div class="b-head">
                    <h3 class="section-title fbold"><i class="s-blue c-icon"><svg width="24" height="24"><use xlink:href="#i__vers"></use></svg></i>  <?php echo _e($opt_themes['title_latest_version'],CHILD_THEME); ?></h3>
                </div>
                <div class="version_history">
                    <?php
                    $files = get_old_version_file($post->ID);
                    if(isset($files["files"]) && count($files["files"]) > 0){
                        foreach ($files["files"] as $file){
                            if ($file["version"] != null){
                                $version = $file["version"];
                            }else{
                                $version ="";
                            }
                            $link = $files["api_url"].'/'.$file["file"];
                            ?>
                            <a id="no-link" href="<?php the_permalink() ?>/file/?urls=<?php echo $link ?>&names=<?php echo $file["file_name"];  ?> (<?php echo $version;?>)&id=<?php echo $post->ID; ?>" class="download-line s-button" target="_blank">
                                <div class="download-line-title">
                                    <i><svg width="24" height="24"><use xlink:href="#i__getapp"/></svg></i>
                                    <span><?php echo $file["file_name"]; ?>(<?php echo $version;?>)</span>
                                </div>
                                <span class="download-line-size">
	<?php global $opt_themes; if($opt_themes['exthemes_apk_info_Download']) { ?>
        <?php  echo esc_html__($opt_themes['exthemes_apk_info_Download'] , CHILD_THEME); ?>
    <?php } ?> - <?php echo $file["size"];  ?>
	</span>
                            </a>
                        <?php }}else{
                        $arg_version = array(
                            'post_type'			=> 'post',
                            'posts_per_page'	=> -1,
                            'meta_key'			=> 'wp_GP_ID',
                            'meta_value'		=> $wp_gp_id,
                            'orderby'			=> $version_gp,
                            'order'				=> 'DESC',
                        );
                        $post_version = new WP_Query($arg_version);
                        while($post_version->have_posts() ) : $post_version->the_post();
                            ?>

                            <?php
                            $image_id_alt					= get_post_thumbnail_id($post->ID);
                            $image_idx						= get_post_thumbnail_id();
                            $fullx							= 'post_thumb_version';
                            $image_urlx						= wp_get_attachment_image_src($image_idx, $fullx, true);
                            $imagex							= $image_urlx[0];
                            $version_gp			    		= get_post_meta( $post->ID, 'wp_version_GP', true );
                            $version_sc		    			= get_post_meta( get_the_ID(), 'wp_version', true );
                            //if ( $version_gp === FALSE or $version_gp == '' ) $version_gp = $version_sc;
                            $mods							= get_post_meta( get_the_ID(), 'wp_mods', true );
                            $updates						= get_the_modified_time('F j, Y');
                            $search							= get_post_meta( $post->ID, 'wp_title_GP', true );
                            $sizes							= get_post_meta( $post->ID, 'wp_sizes', true );
                            $sizes_alt						= get_post_meta( $post->ID, 'wp_sizes_GP', true );
                            if ( $sizes === FALSE or $sizes == '' ) $sizes = $sizes_alt;
                            $appname_on				    	= $opt_themes['title_app_name_active_'];
                            $title					    	= get_post_meta( $post->ID, 'wp_title_GP', true );
                            $title_alt				    	= get_the_title();
                            $poster_gp						= get_post_meta( $post->ID, 'wp_poster_GP', true );

                            ?>
                            <div class="list">
                                <div class="package_info open_info">
                                    <img src="<?php if($poster_gp){ echo $poster_gp; } else { echo $imagex; } ?>" class="icon " alt="<?php if ($title) { if($opt_themes['title_app_name_active_']) { echo ucwords($title); } else { echo $title_alt; } } else { echo $title_alt; } ?>" width="50" height="50">
                                    <div class="title">
                                        <span class="name"><?php if ($title) { echo ucwords($title); } ?></span>
                                        <span class="version"><?php echo $version_gp; ?></span>
                                        <span class="<?php if($mods){ ?>mod<?php } else { ?>apk<?php } ?>"><?php if($mods){ ?><?php echo $opt_themes['title_version_mod']; ?><?php } else { ?><?php echo $opt_themes['title_version_apk']; ?><?php } ?></span>
                                    </div>
                                    <div class="text">
                                        <span><?php echo the_modified_time('F j, Y '); ?></span>
                                        <?php if($sizes){ ?><span><?php echo $sizes; ?></span><?php } ?>
                                    </div>
                                </div>
                                <?php if($mods){ ?>
                                    <div class="info-fix">
                                        <div class="info_box">
                                            <p><strong><?php echo $opt_themes['exthemes_content_Mod_info']; ?></strong></p>
                                            <div class="whats_new"><?php echo $mods; ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="v_h_button button_down ">
                                    <a class="down" href="<?php the_permalink() ?>"><span><?php echo esc_html__($opt_themes['exthemes_apk_info_Download'] , CHILD_THEME) ;?></span></a>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_query(); ?>
                    <?php } ?>

                </div>
            </div>
        </div>



        <script id="rendered-js" >
            $(function() {
                $(".open_info").on("click", function(e) {
                    e.preventDefault();
                    $('.info_box').removeClass('show');
                    $(this).parent().addClass('show');
                    var content = $(this).closest("div").next().find(".info_box");
                    $(".info_box").not(content).slideUp();
                    content.slideToggle();
                });
            });
        </script>
    <?php }   }}
add_shortcode('ex_themes_version_', 'ex_themes_version_');

function ex_themes_gallery_images_gpstore_dt() {
    global $wpdb, $post, $opt_themes;
    $gallery            = get_post_meta( $post->ID, 'gallery_data', true );
    $gallery_dt_data    = get_key_option($post->ID , "screenshots");
    if ($gallery_dt_data && $gallery_dt_data != ""){
        $gallery =$gallery_dt_data;
    }

    $images_GP          = get_post_meta(get_the_ID(), 'wp_images_GP', true);
    if ( $gallery === FALSE or $gallery == '' ) $gallery = $images_GP;
    if ($gallery) {
        ?>
        <div class="block b-screens">
            <div class="b-icon-title">
                <i class="s-yellow c-icon"><svg width="24" height="24"><use xlink:href="#i__cam"></use></svg></i>
                <h3 class="b-title"><?php global $opt_themes; if($opt_themes['exthemes_Screenshots']) { ?><?php echo _e($opt_themes['exthemes_Screenshots'],CHILD_THEME) ?><?php } ?></h3>
            </div>
            <div class="b-cont">
                <div class="screenshots test">
                    <?php
                    global $wpdb, $post, $opt_themes;
                    $gallery_data       = get_post_meta( $post->ID, 'gallery_data', true );
                    $gallery_dt_data    = get_key_option($post->ID , "screenshots");
                    if ($gallery_dt_data){?>
                        <?php
                        global $post;
                        $gallery = json_decode($gallery_dt_data);
                        if ( isset($gallery_dt_data) ) :
                            for( $i = 0; $i < count( $gallery ); $i++ ) {
                                if ( '' != $gallery[$i] ) { ?>
                                    <a href="<?php echo  $gallery[$i] ; ?>" class="highslide" ><img data-src="<?php echo $gallery[$i] ; ?>" style="max-width:100%;" alt="" src="<?php echo  $gallery[$i] ; ?>" class="lazy-loaded"></a>
                                <?php }
                            }
                        endif;
                        ?>
                    <?php  }elseif ($gallery_data) { ?>
                        <?php
                        global $post;
                        $gallery_data = get_post_meta( $post->ID, 'gallery_data', true );
                        if ( '' != get_post_meta( $post->ID, 'gallery_data', true ) ) { $gallery = get_post_meta( $post->ID, 'gallery_data', true ); }
                        if ( isset( $gallery_data ) ) :
                            for( $i = 0; $i < count( $gallery['image_url'] ); $i++ ) {
                                if ( '' != $gallery['image_url'][$i] ) { ?>
                                    <a href="<?php echo  $gallery['image_url'][$i] ; ?>" class="highslide" ><img data-src="<?php echo  $gallery['image_url'][$i] ; ?>" style="max-width:100%;" alt="" src="<?php echo  $gallery['image_url'][$i] ; ?>" class="lazy-loaded"></a>
                                <?php }
                            }
                        endif;
                        ?>
                    <?php } else { ?>
                        <?php global $wpdb, $post, $opt_themes; if($opt_themes['aktif_ex_themes_gallery_images_gpstore_']) { ?>
                            <?php if (get_post_meta( $post->ID, 'wp_images_GP', true )) { ?>
                                <?php
                                $datos_imagenes = get_post_meta(get_the_ID(), 'wp_images_GP', true);
                                $i = 0;
                                $count = 4;
                                foreach($datos_imagenes as $elemento) {
                                    $i++;
                                    if ( $i <= $count ) { ?>
                                        <a href="<?php echo (!empty($datos_imagenes[$i])) ? $datos_imagenes[$i] : ''; ?>" class="highslide" ><img data-src="<?php echo (!empty($datos_imagenes[$i])) ? $datos_imagenes[$i] : ''; ?>" style="max-width:100%;" alt="" src="<?php echo (!empty($datos_imagenes[$i])) ? $datos_imagenes[$i] : ''; ?>" class="lazy-loaded"></a>
                                    <?php } else { ?>
                                    <?php   }  }  ?>
                            <?php } else { ?>
                            <?php }} ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php }}
add_shortcode('ex_themes_gallery_images_gpstore_dt', 'ex_themes_gallery_images_gpstore_dt');

function ex_themes_related_posts_dt() {
    global $opt_themes, $wpdb, $post, $wp_query;
    $activate           =  $opt_themes['ex_themes_related_posts_active_'];
    $numbers            = $opt_themes['ex_themes_related_posts_numbers_'];
    $titles             = $opt_themes['ex_themes_related_posts_title_'];
    $developer_terms    = get_the_terms( $post->ID , 'developer', 'string');
    $term_ids           = wp_list_pluck($developer_terms,'term_id');
    $developer_terms    = get_the_terms( $post->ID , 'developer', 'string');
    $term_ids           = wp_list_pluck($developer_terms,'term_id');
    $paged_categorie_apps = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $developer_query = new WP_Query( array(
        'post_type' => 'post',
        'tax_query' => array(
            array(
                'taxonomy' => 'developer',
                'field' => 'id',
                'terms' => $term_ids,
                /* 'operator'=> 'IN' //Or 'AND' or 'NOT IN' */
            )),
        'posts_per_page' => 10,
        'paged' => $paged_categorie_apps,
        'ignore_sticky_posts' => 1,
        'orderby' => 'rand',
        'post__not_in'=>array($post->ID)
    ) );
    if($developer_query->have_posts()){ ?>
        <section class="wrp section section-related">
        <div class="section-head">
            <h3 class="section-title"><i class="s-blue c-icon"><svg width="24" height="24"><use xlink:href="#i__explore"></use></svg></i><?php global $opt_themes; if($opt_themes['exthemes_more_by_developers']) { ?><?php echo _e($opt_themes['exthemes_more_by_developers'],CHILD_THEME) ; ?><?php } ?> </h3>
            <?php
            global $post;
            $developer = get_option('wp_developers_GP', 'developer');
            $terms = wp_get_post_terms($post->ID, $developer);
            if ($terms) {
                $output = array();
                foreach ($terms as $term) {
                    $output[] = '<a class="btn s-green btn-all" href="' .get_term_link( $term->slug, $developer) .'" title="Developer by ' .$term->name .'"><span>' .$term->name .'</span><svg width="24" height="24"><use xlink:href="#i__keyright"></use></svg></a>';
                }
                echo join( ', ', $output );	}
            ?>
        </div>
        <div class="entry-list list-c6">
        <?php while($developer_query->have_posts() ) : $developer_query->the_post();  ?>
            <?php get_template_part('template/loop/loop.item.home'); ?>
        <?php endwhile; wp_reset_query(); }  ?>
    </div>
    </section>
    <?php //if (($activate == '1'))
    if($activate) {
        $categories = get_the_category($post->ID);
        if ($categories) {
            $category_ids = array();
            foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
            $args=array(
                //'tag' => $tags->slug,
                'category__in' => $category_ids,
                'post__not_in' => array($post->ID),
                'posts_per_page'=> $numbers, // Number of related posts that will be shown.
                'caller_get_posts'=> 1
            );
            $my_query = new WP_Query( $args );
            if( $my_query->have_posts() ) {
                ?>
                <section class="wrp section section-related">
                <div class="section-head">
                    <h3 class="section-title"><i class="s-blue c-icon"><svg width="24" height="24"><use xlink:href="#i__explore"></use></svg></i><?php echo _e($titles,CHILD_THEME) ; ?></h3>
                    <?php
                    $category = get_the_category();
                    echo '<a class="btn s-green btn-all" href="'.get_category_link($category[0]->cat_ID).'"><span>'.esc_html__("All",CHILD_THEME).' ' .$category[0]->cat_name . '</span><svg width="24" height="24"><use xlink:href="#i__keyright"></use></svg></a>';
                    ?>

                </div>
                <div class="entry-list list-c6">
                <?php
                if( $my_query->have_posts() ) {
                    while ($my_query->have_posts()) : $my_query->the_post();
                        get_template_part('template/loop/loop.item.home');
                    endwhile; } wp_reset_query();
            }
            ?>
            </div>
            </section>
        <?php } } else { ?>
    <?php } ?>
<?php }
add_shortcode('ex_themes_related_posts_', 'ex_themes_related_posts_');

###### this code for apkdone, 5play and moddroid themes ######
###### Open your apk themes –> functions.php and insert this code on end line ######
function exthemes_no_home_noindex_nofollow() {
    global $wp_query;
    $noindex = "<meta name='robots' content='noindex,follow' />\n";
    if ( ! isset( $wp_query->query_vars['download'] ) || ! is_singular() ) {
        return;
    }
    echo $noindex;
}
add_action( 'wp_head', 'exthemes_no_home_noindex_nofollow',2);

function get_link_of_post($link){

    $explode_link = explode("/file/",$link);
    echo $explode_link[0];

}

function core_themes_style_child() {
    $css_dir		= get_stylesheet_directory_uri().'/assets/css';
    $sites			= home_url( '/' );
    wp_enqueue_style( 'custom.styles', $css_dir.'/custom.styles.css');
    wp_enqueue_style( 'custom.styles', $css_dir.'/footer.style.css');
    wp_enqueue_style( 'ionic.styles', $css_dir.'/ionicons.min.css');

}
add_action( 'wp_enqueue_scripts', 'core_themes_style_child', 1 );

//this function gert the last 2 news in code
function get_last_news(){
    $args = array(
        'post_type'      => 'news',
        'posts_per_page' => 2,
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Display the news information or perform desired operations
            $postTitle = get_the_title();
            $postContent = get_the_content();
            $news_post_id = get_the_ID(); // Get the ID of the news post
            $creation_date = get_the_date('F j, Y', $news_post_id); // Retrieve the creation date in a specific format
            $featured_image_id = get_post_thumbnail_id($news_post_id); // Get the ID of the featured image
            $featured_image_link = get_permalink($news_post_id); // Get the permalink of the news post
            $featured_image_url = wp_get_attachment_image_src($featured_image_id, 'small'); // Retrieve the URL of the featured image
            // Output the news information as per your requirements
            echo '<div class="block-21 mb-4 d-flex">
                    <a class="img mr-4 rounded" style="background-image: url('.$featured_image_url[0].'); background-size: cover;background-repeat: no-repeat;"></a>
                    <div class="text">
                        <h3 class="heading"><a href="'.$featured_image_link.'">'.$postTitle.'</a></h3>
                        <div class="meta">
                            <div><a href="#"><span class="icon-calendar"></span> '.$creation_date.'</a></div>
                         
                        </div>
                    </div>
                </div>
';

        }

        wp_reset_postdata();
    } else {
        echo 'No news found.';
    }

function custom_footer_widget_areas() {
    register_sidebar( array(
        'name'          => __( 'Footer Widget Area 1', 'CHILD_THEME' ),
        'id'            => 'footer-widget-area-1',
        'description'   => __( 'Add widgets here for the first column in the footer.', 'your-theme-textdomain' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Widget Area 2', 'CHILD_THEME' ),
        'id'            => 'footer-widget-area-2',
        'description'   => __( 'Add widgets here for the second column in the footer.', 'your-theme-textdomain' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Widget Area 3', 'CHILD_THEME' ),
        'id'            => 'footer-widget-area-3',
        'description'   => __( 'Add widgets here for the third column in the footer.', 'your-theme-textdomain' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'custom_footer_widget_areas' );




}


// register Dt_Widget widget
function register_dt_widget() {
    register_widget( 'WP_Widget_DT_Recent_Posts' );
}
add_action( 'widgets_init', 'register_dt_widget' );



class WP_Widget_DT_Recent_Posts extends WP_Widget {

    /**
     * Sets up a new Recent Posts widget instance.
     *
     * @since 2.8.0
     */
    public function __construct() {

        parent::__construct(
            'WP_Widget_DT_Recent_Posts', // Base ID
            esc_html__( 'DT Recent Posts', 'text_domain' ), // Name
            array( 'description' => esc_html__( 'Your recent Posts.', 'instaplus-child' ),) // Args
        );

    }

    /**
     * Outputs the content for the current Recent Posts widget instance.
     *
     * @since 2.8.0
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Recent Posts widget instance.
     */
    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $default_title =  __( "Recent Posts",'instaplus-child' );
        $title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $default_title;

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number ) {
            $number = 5;
        }
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        $r = new WP_Query(
        /**
         * Filters the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         * @since 4.9.0 Added the $instance parameter.
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args     An array of arguments used to retrieve the recent posts.
         * @param array $instance Array of settings for the current widget.
         */
            apply_filters(
                'widget_posts_args',
                array(
                    'posts_per_page'      => $number,
                    'no_found_rows'       => true,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                ),
                $instance
            )
        );

        if ( ! $r->have_posts() ) {
            return;
        }
        ?>

        <?php echo $args['before_widget']; ?>

        <?php
        if ( $title ) {
            echo $args['before_title'] .'<span  style="font-weight: bold;" >' .$title .'</span>'. $args['after_title'];
        }

        $format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';

        /** This filter is documented in wp-includes/widgets/class-wp-nav-menu-widget.php */
        $format = apply_filters( 'navigation_widgets_format', $format );

        if ( 'html5' === $format ) {
            // The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
            $title  = trim( strip_tags( $title ) );
            $aria_label = $title ? $title : $default_title;
            echo '<nav aria-label="' . esc_attr( $aria_label ) . '">';
        }
        ?>

        <ul class="blg_list">
            <?php foreach ( $r->posts as $recent_post ) : ?>
                <?php
                $size_icon = '<svg width="1.1em" height="1.1em" viewBox="0 0 24 24"><path d="M12 10c3.976 0 8-1.374 8-4s-4.024-4-8-4s-8 1.374-8 4s4.024 4 8 4z" fill="currentColor"></path><path d="M4 10c0 2.626 4.024 4 8 4s8-1.374 8-4V8c0 2.626-4.024 4-8 4s-8-1.374-8-4v2z" fill="currentColor"></path><path d="M4 14c0 2.626 4.024 4 8 4s8-1.374 8-4v-2c0 2.626-4.024 4-8 4s-8-1.374-8-4v2z" fill="currentColor"></path><path d="M4 18c0 2.626 4.024 4 8 4s8-1.374 8-4v-2c0 2.626-4.024 4-8 4s-8-1.374-8-4v2z" fill="currentColor"></path></svg>';


                $version_icon='<svg viewBox="0 0 20 20" height="1.2em" width="1.2em"><path d="M10.2 3.28c3.53 0 6.43 2.61 6.92 6h2.08l-3.5 4l-3.5-4h2.32a4.439 4.439 0 0 0-4.32-3.45c-1.45 0-2.73.71-3.54 1.78L4.95 5.66a6.965 6.965 0 0 1 5.25-2.38zm-.4 13.44c-3.52 0-6.43-2.61-6.92-6H.8l3.5-4c1.17 1.33 2.33 2.67 3.5 4H5.48a4.439 4.439 0 0 0 4.32 3.45c1.45 0 2.73-.71 3.54-1.78l1.71 1.95a6.95 6.95 0 0 1-5.25 2.38z" fill="currentColor"></path></svg> ';

                ?>
                <?php
                if (function_exists('icl_object_id')) {
                    global $sitepress;
                    $language = $sitepress->get_current_language(); // get current language
                    if ($language = 'ar'){
                        $post_title   =   $str = mb_substr( get_the_title( $recent_post->ID ) , 0, 35) . '..';
                    }
                    else{
                        $post_title   =   $str = mb_substr( get_the_title( $recent_post->ID ) , 0, 50) . '..';
                    }
                }

                //   $post_title   =   $str = mb_substr( get_the_title( $recent_post->ID ) , 0, 35) . '..';
                $title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
                $aria_current = '';




                if ( get_queried_object_id() === $recent_post->ID ) {
                    $aria_current = ' aria-current="page"';
                }
                ?>
                <li class="recent">
                    <div class="col-md-2  blg" >
                        <?php if(has_post_thumbnail($recent_post->ID)): ?>
                            <div class="blog__thumb" >
                                <?php echo get_the_post_thumbnail( $recent_post->ID, 'thumbnail', array('class'=>'img-responsive','alt'=>__('blog image','instaplus-child')) ); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <a class="wp-block-latest-posts__post-title" href="<?php the_permalink( $recent_post->ID ); ?>"<?php echo $aria_current; ?>><?php echo $title; ?></a> <br>

                    <?php if( get_field('size',$recent_post->ID) ): ?>

                        <?php echo '<span class="size"> '.$size_icon." ".get_field('size',$recent_post->ID).' </span>'; ?>
                    <?php endif; ?>

                    <?php if( get_field('version',$recent_post->ID) ): ?>
                        <?php echo '<span class="size"> '.$version_icon." ".get_field('version',$recent_post->ID).' </span>'; ?>
                    <?php endif; ?>
                    <?php if ($show_date): ?>
                        <span class="post-date"><?php echo get_the_date( '', $recent_post->ID ); ?></span>
                    <?php endif; ?>    </li>

            <?php endforeach; ?>
        </ul>

        <?php
        if ( 'html5' === $format ) {
            echo '</nav>';
        }

        echo $args['after_widget'];
    }

    /**
     * Handles updating the settings for the current Recent Posts widget instance.
     *
     * @since 2.8.0
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return


     */
    public function update( $new_instance, $old_instance ) {
        $instance              = $old_instance;
        $instance['title']     = sanitize_text_field( $new_instance['title'] );
        $instance['number']    = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        return $instance;
    }

    /**
     * Outputs the settings form for the Recent Posts widget.
     *
     * @since 2.8.0
     *
     * @param array $instance Current settings.
     */
    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;


        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
        </p>

        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?>  </label>


        </p>


        <?php
    }
}



?>

