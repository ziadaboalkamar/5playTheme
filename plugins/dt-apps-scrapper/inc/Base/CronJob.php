<?php
namespace Inc\Base;
require_once plugin_dir_path(__FILE__) . 'BaseController.php';
require_once plugin_dir_path(__FILE__) . 'Apps.php';

use Inc\Base\Apps;
use Inc\Base\BaseController;
class CronJob extends BaseController {


    public function __construct() {

    }


    public function my_cron_job() {

        try {
            global $wpdb;
            $id = 0;
            $table_settings = $this->table_settings;
            $table_app_info = $this->table_app_info;
            $table_name_new = $this->table_history;
            $table_meta_app = $this->table_dt_meta;
            $table_meta_app_post = $this->table_app_post;
            $table_event = $this->table_event;
            $cron_checker = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'cron_checker'");
            $api_connected = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_connected'");
            $cron_checker_value = intval($cron_checker->value);
            $api_connected_value = intval($api_connected->value);
            if ($api_connected_value == 1) {
            if ($cron_checker_value == 5) {
                $email = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_email'");
                $password = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_password'");
                $url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
                $Auth = new AuthController($email->value, $password->value, $url->value);
                $Auth->connectApi();
                $Auth->storeIntoDB();
                $wpdb->update($table_settings, array(
                    'value' => '1'
                ), array(
                    'id' => $cron_checker->id
                ));
            } else {
                $wpdb->update($table_settings, array(
                    'value' => $cron_checker_value + 1
                ), array(
                    'id' => $cron_checker->id
                ));
            }
            my_plugin_log('1.1- Start cron_job Function');
            $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
            $api_url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
            $use_tag_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'use_tag'");
            $token = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_token'");
            if (isset($project_setting->value) && $project_setting->value != null) {
                my_plugin_log('1.2-request Api_url $api_url');
                $client = new \GuzzleHttp\Client();
                $response = $client->get("{$api_url->value}/api/project/project_data?project_id={$project_setting->value}", [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token->value,
                        'Accept' => 'application/json',
                    ],
                ]);
                $data_project = json_decode($response->getBody());
                my_plugin_log('1.3-store events of the jop periodically during process ');
                $event = $wpdb->insert($table_event, array(
                    "created_at" => date("Y-m-d H:i:s"),
                    'updated_app' => 0,
                ));
                if ($event !== false) {
                    $id = $wpdb->insert_id;
                }
                my_plugin_log('1.4- fetch data from Api');

                if (isset($data_project->project) && is_array($data_project->project)) {
                    $pun_status = 0;
                    my_plugin_log('1.5- View data that returned from API');
                    my_plugin_log('1.6- get and View projects & tags that returned from  API');
                    my_plugin_log('1.7- get data of each project to select ');
                    foreach ($data_project->project as $project) {
                        my_plugin_log('1.8- The selected project Name:' . $project->name . '. id: ' . $project->id);
                        $pun_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project_update_number'");
                        $pun_setting_id = 0;

                        my_plugin_log('1.9- Update or insert this Project:' . $project->name . ' in setting as necessary to deal with it ');
                        my_plugin_log('1.10- check project update number [PUN]if not found');
                        if (!$pun_setting) {
                            // Insert a new row
                            my_plugin_log('1.11- [insert PUN]  insert a new row project update number [PUN]');
                            $wpdb->insert($table_settings, array(
                                "key" => "project_update_number",
                                'value' => $project->pun,
                            ));
                            $pun_status = 1;
                        } else {
                            my_plugin_log('2.1- [update PUN] check if [PUN] changed :  to update it');
                            if ($project->pun > $pun_setting->value) {
                                //Update the existing row
                                $wpdb->update($table_settings, array(
                                    'value' => $project->pun,
                                ), array(
                                    'id' => $pun_setting->id,
                                ));
                                $pun_status = 2;
                            }
                        }
                    }
                    if ($pun_status > 0) {
                        my_plugin_log('2.2- check if project update number[pun > 0]');
                        my_plugin_log('2.3- store data into history table');
                        // Insert data into history table
                        $client = new \GuzzleHttp\Client();
                        my_plugin_log('2.4- get all project name: ' . $project_setting->value . ' & all tag : ' . $use_tag_setting->value . ' from table');
                        $response = $client->request('GET', "{$api_url->value}/api/project/apps?project_id={$project_setting->value}&use_tag={$use_tag_setting->value}", [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $token->value,
                                'Accept' => 'application/json',
                            ],
                        ]);
                        $data_main = json_decode($response->getBody(), true);

                        $lastPage = $data_main["last_page"];
                        for ($i = 1; $i <= $lastPage; $i++) {
                            $client2 = new \GuzzleHttp\Client();
                            $response = $client2->request('GET', "{$api_url->value}/api/project/apps?project_id={$project_setting->value}&use_tag={$use_tag_setting->value}&page=" . $i, [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . $token->value,
                                    'Accept' => 'application/json',
                                ],
                            ]);
                            $data = json_decode($response->getBody(), true);
                            if (isset($data["app"]) && is_array($data["app"])) {
                                foreach ($data["app"] as $app) {
                                    $aun_status = 0;
                                    $app_data = $wpdb->get_row("SELECT * FROM " . $table_app_info . " WHERE api_app_id	= " . $app["id"] . "");
                                    $aun_setting_id = 0;
                                    $app_data_id = 0;
                                    if (!$app_data) {
                                        if (isset($app["package"]["package_name"]) && $app["package"]["package_name"] != '') {
                                            $package = $app["package"]["package_name"];
                                        } else {
                                            $package = "not found";
                                        }
                                        my_plugin_log("2.5-Inserting new app with ID : {$app['id']} & Name: {$app['name']} & package_name:{$package}");
                                        $app_table = $wpdb->insert($table_app_info, array(
                                            'name' => $app["name"],
                                            'package_name' => $package,
                                            'app_update_number' => $app["aun"],
                                            'api_app_id' => $app["id"],
                                            'url' => $app["url"],
                                            'status' => 'new',
                                        ));
                                        my_plugin_log("2.6- set status of App {'new'}");
                                        $app_data_new = $wpdb->get_row("SELECT * FROM {$table_app_info} WHERE api_app_id = {$app["id"]}");
                                        $app_data_id = $app_data_new->id;
                                        $aun_status = 1;
                                    } else {
                                        $app_data_id = $app_data->id;
                                        $app_data_post = $wpdb->get_row("SELECT * FROM {$table_meta_app_post} WHERE app_id = {$app_data_id}");

                                        if ($app["aun"] > $app_data->app_update_number && $app_data_post) {
                                            // Update the existing row
                                            my_plugin_log("2.7-changed the new pun {$app['aun']} ");
                                            if ($app_data->status != 'disabled') {
                                                my_plugin_log("2.8- register updated_app in event table");
                                                $event_app_updated = $wpdb->get_row("SELECT * FROM {$table_event} WHERE `id` = {$id} ");

                                                $updated_app_id = json_decode($event_app_updated->updated_app_id);
                                                if (is_array($updated_app_id)) {
                                                    $add_app_to_array = array_push($updated_app_id, $app_data_id);

                                                } elseif ($updated_app_id == null) {
                                                    $updated_app_id = array();
                                                    $add_app_to_array = array_push($updated_app_id, $app_data_id);

                                                }
                                                $wpdb->update($table_event, array(
                                                    "updated_at" => date("Y-m-d H:i:s"),
                                                    "updated_app" => $event_app_updated->updated_app + 1,
                                                    "updated_app_id" => json_encode($updated_app_id)
                                                ), array(
                                                    'id' => $id,
                                                ));
                                                if (isset($app["package"]["package_name"]) && $app["package"]["package_name"] != '') {
                                                    $package = $app["package"]["package_name"];
                                                } else {
                                                    $package = "not found";
                                                }
                                                $data = array(
                                                    'name' => $app["name"],
                                                    'package_name' => $package,
                                                    'app_update_number' => $app["aun"],
                                                    'url' => $app["url"],
                                                    'status' => 'updated'// set status to 'new' when inserting a new row
                                                );
                                                my_plugin_log("2.9- set status {'updated'}");
                                                $wpdb->update($table_app_info, $data, array('id' => $app_data_id), $format = null, $where_format = null);
                                                $aun_status = 2;
                                            }
                                        }
                                    }
                                    my_plugin_log('3.1- start_process');
                                    if (isset($app["process"]) && is_array($app["process"])) {
                                        my_plugin_log('3.2- get last aun and last meta_app_post for app [' . $app_data_id . ']');
                                        my_plugin_log('3.3- if aun status > 0 ');
                                        foreach ($app["process"] as $process_item) {
                                            if ($aun_status > 0) {
                                                $key = sanitize_text_field($process_item["key"]);
                                                $value = sanitize_text_field($process_item["value"]);
                                                $app_data_post = $wpdb->get_row("SELECT * FROM {$table_meta_app_post} WHERE app_id = {$app_data_id}");
                                                if ($app_data_post) {
                                                    $wpdb->insert($table_name_new, array(
                                                        'key' => $key,
                                                        'value' => $value,
                                                        'app_id' => $app_data_id
                                                    ));

                                                    $app_data_key = $wpdb->get_row("SELECT * FROM {$table_meta_app} WHERE `key` = '$key' AND `app_id` = {$app_data_id}");
                                                    my_plugin_log("3.5 check  the app have key '.$app_data_key->key.' if not found ");
                                                    if (!$app_data_key) {
                                                        if (trim($key) == "logo") {
                                                            try {
                                                                $logo = Apps::addLogoImage($value, $app_data_id);
                                                                $wpdb->insert($table_meta_app, array(
                                                                    'app_id' => $app_data_id,
                                                                    'key' => $key,
                                                                    'value' => $logo
                                                                ));
                                                            } catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());

                                                            }
                                                        } elseif (trim($key) == "thumbnail") {
                                                            try {
                                                                $logo = Apps::addFeaturedImage($value, $app_data_id);
                                                                $wpdb->insert($table_meta_app, array(
                                                                    'app_id' => $app_data_id,
                                                                    'key' => $key,
                                                                    'value' => $logo
                                                                ));
                                                            } catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());

                                                            }
                                                            my_plugin_log("3.4- Insert logo & thumbnail");
                                                        }elseif (trim($key) == "app_name"){
                                                            try {

                                                                $post_name = Apps::add_post_title($app_data_id,$value);
                                                                    $wpdb->insert($table_meta_app, array(
                                                                        'app_id' => $app_data_id,
                                                                        'key' => $key,
                                                                        'value' => $value
                                                                    ));




                                                            }catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());
                                                            }
                                                        }elseif (trim($key) == "description"){
                                                            try {

                                                                $post_name = Apps::edit_post_content($app_data_id,$value);
                                                                if ($post_name){
                                                                    $wpdb->insert($table_meta_app, array(
                                                                        'app_id' => $app_data_id,
                                                                        'key' => $key,
                                                                        'value' => $value
                                                                    ));
                                                                }



                                                            }catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());
                                                            }
                                                        }elseif (trim($key) == "screenshots"){
                                                            try {

                                                                $screenshot = Apps::redesign_the_screenshot($value);

                                                                if ($screenshot){
                                                                    $wpdb->insert($table_meta_app, array(
                                                                        'app_id' => $app_data_id,
                                                                        'key' => $key,
                                                                        'value' => json_encode($screenshot)
                                                                    ));
                                                                }



                                                            }catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());
                                                            }
                                                        } else {
                                                            // Key does not exist, insert data
                                                            $wpdb->insert($table_meta_app, array(
                                                                'app_id' => $app_data_id,
                                                                'key' => $key,
                                                                'value' => $value
                                                            ));
                                                        }
                                                        my_plugin_log("3.6- Insert  all keys ");
                                                    } else {
                                                        my_plugin_log("3.7 check if the keys exists, update data");
                                                        if (trim($key) == "logo") {
                                                            try {
                                                                $logo = Apps::addLogoImage($value, $app_data_id);
                                                                $wpdb->update($table_meta_app, array(
                                                                    'value' => $logo
                                                                ), array(
                                                                    'app_id' => $app_data_id,
                                                                    'key' => $key
                                                                ));
                                                            } catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());
                                                            }
                                                        } elseif (trim($key) == "thumbnail") {
                                                            try {
                                                                $logo = Apps::addFeaturedImage($value, $app_data_id);
                                                                $wpdb->update($table_meta_app, array(
                                                                    'value' => $logo
                                                                ), array(
                                                                    'app_id' => $app_data_id,
                                                                    'key' => $key
                                                                ));
                                                            } catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());
                                                            }
                                                        }elseif (trim($key) == "app_name"){
                                                            try {

                                                                $post_name = Apps::add_post_title($app_data_id,$value);
                                                                    $wpdb->update($table_meta_app, array(
                                                                        'value' => $value
                                                                    ), array(
                                                                        'app_id' => $app_data_id,
                                                                        'key' => $key
                                                                    ));




                                                            }catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());
                                                            }
                                                        }elseif (trim($key) == "description"){
                                                            try {

                                                                $post_name = Apps::edit_post_content($app_data_id,$value);
                                                                if ($post_name){
                                                                    $wpdb->update($table_meta_app, array(
                                                                        'value' => $value
                                                                    ), array(
                                                                        'app_id' => $app_data_id,
                                                                        'key' => $key
                                                                    ));
                                                                }



                                                            }catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());
                                                            }
                                                        }elseif (trim($key) == "screenshots"){
                                                            try {

                                                                $screenshot = Apps::redesign_the_screenshot($value);
                                                                if ($screenshot){
                                                                    $wpdb->update($table_meta_app, array(
                                                                        'value' => json_encode($screenshot)
                                                                    ), array(
                                                                        'app_id' => $app_data_id,
                                                                        'key' => $key
                                                                    ));
                                                                }



                                                            }catch (\Exception $exception) {
                                                                my_plugin_log('Error' . $exception->getMessage());
                                                            }
                                                        } else {
                                                            $wpdb->update($table_meta_app, array(
                                                                'value' => $value
                                                            ), array(
                                                                'app_id' => $app_data_id,
                                                                'key' => $key
                                                            ));
                                                        }
                                                        my_plugin_log("3.8- update all keys & logo & thumbnail ");

                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                my_plugin_log('3.9- Finish cron_job Function');
            }

        }
        }catch (\Exception $exception){
            my_plugin_log('Error'.$exception->getMessage());

        }


    }

}
