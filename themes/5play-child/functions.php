<?php

use Inc\Base\BaseController;

require_once WP_PLUGIN_DIR . '/dt-apps-scrapper/inc/Base/BaseController.php';

function get_key_option($post_id , $key){
    global $wpdb;
    $base= new \Inc\Base\BaseController();
    $table_dt_meta = $base->table_dt_meta;
    $table_meta_app_post = $base->table_app_post;
    $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
    $app_id = $results_of_post->app_id;
    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE status = 1 AND `key` = '{$key}' AND app_id = %d", $app_id ) );
    if ($results){
        $value = $results->value;
    }else{
        $value = "";
    }
    return $value;

}

function get_package($post_id){
    global $wpdb;
    $base= new \Inc\Base\BaseController();
    $table_app = $base->table_app_info;
    $table_meta_app_post = $base->table_app_post;
     $results_of_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
     $app_id = $results_of_post->app_id;
    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_app} WHERE id = %d", $app_id ) );
    if ($results){
        $value = $results->package_name;
    }else{
        $value = "";
    }
    return $value;

}

function get_dt_title($post_id){
    global $wpdb;
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
    $value="";}
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
    }




}
