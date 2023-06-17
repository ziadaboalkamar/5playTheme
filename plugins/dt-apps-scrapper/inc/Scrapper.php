<?php

use GuzzleHttp\Client;

class Scrapper {

    function get_data()
    {
        if (isset($_POST['action']) && $_POST['action'] === 'my_plugin_submit_form') {
    
            $client = new \GuzzleHttp\Client();
            $project = $_POST['project'];
            $tag = $_POST['use_tag'];
            $response = $client->get("http://192.168.2.146:8000/api/project/apps?project_id={$project}&use_tag={$tag}");
            $data = json_decode($response->getBody());
            echo '<pre>'. print_r($data, true) . '</pre>';
         
            global $wpdb;
            $table_name_log = $this->table_name_log;
            $wpdb->insert($table_name_log, array(
                                'project'=> $project,
                                'tag'=> $tag,
                                'date' => current_time('mysql'),
                                'data' => json_encode($data) // encode data as JSON string
                                ));
    
            global $wpdb;
    
            // Set the table name
            $table_name = $this->table_settings;
    
            // Check if the 'project' setting exists in the database
            $project_setting = $wpdb->get_row("SELECT * FROM {$table_name} WHERE `key` = 'project'");
    
            // Update or insert the 'project' setting as necessary
            if (!$project_setting) {
                // Insert a new row
                $wpdb->insert($table_name, array(
                    'key' => 'project',
                    'value' => $_POST['project'],
                ));
            } else {
                // Update the existing row
                $wpdb->update($table_name, array(
                    'value' => $_POST['project'],
                ), array(
                    'id' => $project_setting->id,
                ));
            }
    
            // Check if the 'use_tag' setting exists in the database
            $use_tag_setting = $wpdb->get_row("SELECT * FROM {$table_name} WHERE `key` = 'use_tag'");
    
            // Update or insert the 'use_tag' setting as necessary
            if (!$use_tag_setting) {
                // Insert a new row
                $wpdb->insert($table_name, array(
                    'key' => 'use_tag',
                    'value' => $_POST['use_tag'],
                ));
            } else{
                // Update the existing row
                $wpdb->update($table_name, array(
                    'value' => $_POST['use_tag'],
                ), array(
                    'id' => $use_tag_setting->id,
                ));
            }
            $client = new \GuzzleHttp\Client();
            $response = $client->get("http://192.168.2.146:8000/api/project/project_data?project_id={$project}");
            $data_project = json_decode($response->getBody());
            echo '<pre>' . print_r($data, true) . '</pre>';
            if (isset($data_project->project) && is_array($data_project->project)) {
                $pun_status = 0;
                foreach ($data_project->project as $project) {
                    $pun_setting = $wpdb->get_row("SELECT * FROM {$table_name} WHERE `key` = 'project_update_number'");
                    $pun_setting_id = 0;
                    // Update or insert the 'project' setting as necessary
                    if (!$pun_setting){
                        // Insert a new row
                        $wpdb->insert($table_name,array(
                            "key" => "project_update_number",
                            'value' => $project->pun,
                        ));
                        $pun_status = 1;
                    } else {
                        if ($project->pun > $pun_setting->value) {
                            // Update the existing row
                            // Update the existing row
                            $wpdb->update($table_name, array(
                                'value' => $project->pun,
                            ), array(
                                'id' => $pun_setting->id,
                            ));
                            $pun_status = 2;
                        }
                    }
                }
                if ($pun_status > 0) {
    
                    // Insert data into history table
                    $table_name_new = $this->table_history;;
                    if (isset($data->app) && is_array($data->app)) {
                        foreach ($data->app as $app) {
                            $aun_status = 0;
                            $table_app_info =$this->table_app_info;
                            $app_data = $wpdb->get_row("SELECT * FROM " . $table_app_info . " WHERE api_app_id	= " . $app->id . "");
                            $aun_setting = $wpdb->get_row("SELECT * FROM {$table_app_info} WHERE `app_update_number` = " . $app->aun . "");
                            $aun_setting_id = 0;
                            $app_data_id = 0;
                            if (!$app_data) {
                                $app_table = $wpdb->insert($table_app_info, array(
                                    'name' => $app->name,
                                    'package_name' => $app->package->package_name,
                                    'app_update_number' => $app->aun,
                                    'api_app_id' => $app->id,
                                    'url' => $app->url,
                                    'status' => 'new' // set status to 'new' when inserting a new row
                                ));
                                $app_data_new = $wpdb->get_row("SELECT * FROM " . $table_app_info . " WHERE api_app_id	 = " . $app->id . "");
                                $app_data_id =  $app_data_new->id;
                                $aun_status = 1;
                        } else {
                                $app_data_id = $app_data->id;
                                if ($app->aun > $aun_setting) {
                                    // Update the existing row
                                    // Update the existing row
                                    $data = array(
                                        'name' => $app->name,
                                        'package_name' => $app->package->package_name,
                                        'app_update_number' => $app->aun,
                                        'url' => $app->url,
                                        'status' => 'updated' // set status to 'updated' when updating an existing row
                                    );
                                    $wpdb->update($table_app_info, $data, array('id' => $app_data_id), $format = null, $where_format = null);
    
                                    $aun_status = 2;
                                }
                            }
                        if (isset($app->process) && is_array($app->process)) {
                            foreach ($app->process as $process_item) {
                                $key = sanitize_text_field($process_item->key);
                                $value = sanitize_text_field($process_item->value);
                                $wpdb->insert($table_name_new, array(
                                    'key' => $key,
                                    'value' => $value,
                                    'app_id' => $app_data_id
                                ));
                                if ($aun_status > 0) {
                                    $table_dt_meta =  $this->table_dt_meta;;
                                    $app_data = $wpdb->get_rows("SELECT * FROM $table_dt_meta WHERE 'app_id' = '$app_data_id'");
    
                                    $table_app_post = $this->table_app_post;
                                    $app_data_post = $wpdb->get_rows("SELECT * FROM $table_app_post WHERE app_id = '$app_data_id'");
                                    if (count($app_data) > 0 && count($app_data_post) > 0) {
                                        $app_data_key = $wpdb->get_row("SELECT * FROM $table_dt_meta WHERE 'key' = '$key' AND 'app_id' = '$app_data_id'");
                                        if (!$app_data_key) {
                                            // Key does not exist, insert data
                                            $wpdb->insert($table_dt_meta, array(
                                                'app_id' => $app_data_id,
                                                'key' => $key,
                                                'value' => $value
                                            ));
                                        }else {
                                            // Key exists, update data
                                            $wpdb->update($table_dt_meta, array(
                                                'value' => $value
                                            ), array(
                                                'app_id'=> $app_data_id,
                                                'key'=> $key
                                            ));
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
    }
    



}


