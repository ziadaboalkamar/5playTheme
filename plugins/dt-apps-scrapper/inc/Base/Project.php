<?php 
/**
 * @package  dt-apps-scrapper
 */
namespace Inc\Base;

require_once plugin_dir_path(__FILE__) . '/BaseController.php';

class Project extends BaseController {

    private $table_name;
    private $db;

	public function __construct() {

    }
   
public static function storePUN(){
        global $wpdb;
    $base = new BaseController();
    $table_settings = $base->table_settings;
    $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
    $api_url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
    $token =  $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_token'");

    $client = new \GuzzleHttp\Client();
    $response = $client->get("{$api_url->value}/api/project/project_data?project_id={$project_setting->value}",[
        'headers' => [
            'Authorization' => 'Bearer ' . $token->value,
            'Accept'        => 'application/json',
        ],
    ]);
    $data_project = json_decode($response->getBody());
    if (isset($data_project->project) && is_array($data_project->project)) {
        $pun_status = 0;
        foreach ($data_project->project as $project) {
            $pun_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project_update_number'");
            $pun_setting_id = 0;
            // Update or insert the 'project' setting as necessary
            if (!$pun_setting){
                // Insert a new row
                $wpdb->insert($table_settings,array(
                    "key" => "project_update_number",
                    'value' => $project->pun,
                ));
                $wpdb->insert($table_settings,array(
                    "key" => "project_name",
                    'value' => $project->name,
                ));
                $wpdb->insert($table_settings,array(
                    "key" => "tag_name",
                    'value' => $project->tag,
                ));
                $pun_status = 1;
            }else{
                $project_name_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project_name'");
                $tag_name_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'tag_name'");

                $wpdb->update($table_settings, array(
                    'value' => $project->pun,
                ), array(
                    'id' => $pun_setting->id,
                ));
                if ($project_name_setting){
                    $wpdb->update($table_settings, array(
                        'value' => $project->name,
                    ), array(
                        'id' => $project_name_setting->id,
                    ));
                }else{
                    $wpdb->insert($table_settings,array(
                        "key" => "project_name",
                        'value' => $project->name,
                    ));
                }

                if ($tag_name_setting){
                    $wpdb->update($table_settings, array(
                        'value' => $project->tag,
                    ), array(
                        'id' => $tag_name_setting->id,
                    ));
                }else{
                    $wpdb->insert($table_settings,array(
                        "key" => "tag_name",
                        'value' => $project->tag,
                    ));
                }


            }
            }
        }
    }





    }
