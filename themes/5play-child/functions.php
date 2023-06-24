<?php

use Inc\Base\BaseController;

require_once WP_PLUGIN_DIR . '/dt-apps-scrapper/inc/Base/BaseController.php';
$child_domain = "dt-5play";
define("CHILD_THEME",$child_domain);
add_action('after_setup_theme', 'dt_setup');
function dt_setup() {
    // load_theme_textdomain('InstaplusChild', get_stylesheet_directory() . '/languages');
    ////////////////////load_child_theme_textdomain('instaplus-child', get_stylesheet_directory() . '/languages');
    $path = get_stylesheet_directory() . '/languages';
    $result = load_child_theme_textdomain('dt-5play', $path);

    if ( $result )
        return;

    $locale = apply_filters( 'theme_locale', get_locale(), '5play-child' );
    echo ( "Could not find $path/$locale.mo." );
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
                "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
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
                 "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
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
                "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
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
                "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
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

