<?php
/**
 * @package  dt-apps-scrapper
 */
namespace Inc\Base;
require_once plugin_dir_path(__FILE__) . '/BaseController.php';

class Apps extends BaseController {
    private $table_name;
    private $db;

    public function __construct() {

    }
    public function getData() {
        header("Content-Type: application/json");

        $request = $_GET;

        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'name', 'dt' => 1 ),
            array( 'db' => 'package_name', 'dt' => 2 ),
            array( 'db' => 'api_app_id', 'dt' => 3 ),
            array( 'db' => 'url', 'dt' => 4 ),
            array( 'db' => 'status', 'dt' => 5 ),
        );

        global $wpdb;
        $table_meta_app_post = $this->table_app_post;
        $table_apps_downloader = $this->table_app_info;
        $table_posts = $wpdb->prefix . "posts"; // table from WordPress not from plugin
        $table_meta_app = $this->table_dt_meta;

        $limit = $request['length']; //number of rows in page
        $offset = $request['start'];
        $totalData = $wpdb->get_var("select count(*) as total from $table_apps_downloader");
        $filterDataCount = $totalData;

        $searchValue = $request['search']['value']; // Search value
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " WHERE name LIKE '%" . $searchValue . "%' OR package_name LIKE '%" . $searchValue . "%' OR status LIKE '%" . $searchValue . "%'";
        }

        $order_by = "";
        if ( isset($request['order']) && !empty($request['order']) ) {
            $order_column = $request['order'][0]['column'];
            $order_direction = $request['order'][0]['dir'];
            $order_by = "ORDER BY " . $columns[$order_column]['db'] . " " . $order_direction;
        }

        $limit_query = "";
        if ( isset($request['start']) && isset($request['length']) ) {
            $offset = intval($request['start']);
            $limit_query = "LIMIT $offset, " . intval($request['length']);
        }

        $sql = "SELECT * FROM {$table_apps_downloader} $searchQuery $order_by $limit_query";
        $results = $wpdb->get_results($sql);
        $rowcount = $wpdb->num_rows;
        $all_posts = $wpdb->get_results( "SELECT * FROM  {$table_posts}  WHERE post_type = 'post' AND post_status = 'publish' AND post_title !='' ");

        $data = array();
        if ($rowcount > 0) {
            foreach ($results as $row) {
                if ($row->status != "disabled"){
                    $dis_button = '<button id="disabled_button_'.$row->id.'"  type="button" onclick="disable_app('.$row->id.',1)" class="btn btn-danger" title="Edit">Disable</button>';
                }else{
                    $dis_button = '<button id="disabled_button_'.$row->id.'"  type="button" onclick="disable_app('.$row->id.',0)" class="btn btn-danger" title="Edit">Enable</button>';
                }
                $key_button = $wpdb->get_results("SELECT * FROM {$table_meta_app} WHERE  app_id = {$row->id}");
                if ($key_button && count($key_button) > 0){
                    $key_button= "d-inline";
                }else{
                    $key_button= "d-none";
                }
                $options = '';
                foreach ( $all_posts as $result ) {
                    $has_app = $wpdb->get_row( "SELECT * FROM  {$table_meta_app_post}  WHERE app_id = {$row->id} AND post_id = {$result->ID}");
                    if ($has_app){
                        $selected = "selected";
                    }else{
                        $selected = "";
                    }
                    $options .= '<option '.$selected.' value="' . $result->ID . '">' . $result->post_title . '</option>';
                }
                $nestedData = array();
                $nestedData['record_select'] = '<div class="animated-checkbox"><label class="m-0"><input type="checkbox" class="record__select" value="'.$row->id.'"><span class="label-text"></span></label></div>';
                $nestedData['id'] = $row->id;
                $nestedData['name'] ='<a class="app_name" href="'.$row->url.'" target="_blank">' . $row->name . '</a>';
                $nestedData['package_name'] = $row->package_name;
                $nestedData['api_app_id'] = $row->api_app_id;
                $nestedData['status'] = '<span class="status-' . $row->id . '">' . $row->status . '</span>';
                $nestedData['posts'] = "<select multiple name='post_id' id='post_id_".$row->id."' class='select2'>'.$options.'</select>";
                $nestedData['action'] = '<button type="button" onclick="connect_the_post('.$row->id.')" class="btn btn-success" title="Edit">Join</button> 
                <a type="button" href="'.admin_url("admin.php?page=KeySelected&app_id={$row->id}").'" class="btn btn-primary '.$key_button.'" title="Edit">Keys</a> '.$dis_button.'';
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw" => intval($request['draw']),
                "iTotalRecords" => intval($totalData),
                "iTotalDisplayRecords" =>  intval($filterDataCount),
                "aaData" => $data
            );
            echo json_encode($json_data);
            die();
        }
    }
    public function connect_with_post(){
        global $wpdb;
        $posts = $_GET['post_id'];
        $app_id = intval($_GET['id']);
        $table_app_post = $this->table_app_post;
        $table_app = $this->table_app_info;
        if (!isset($posts)){
            $wpdb->query( "DELETE FROM $table_app_post WHERE app_id ={$app_id}" );

            $data = array(
                "success" => true,
                "status" => 200,
                "msg" => "the post Delete all"
            );
        }else{
            foreach ($posts as $post_id){
                $app_data_post = $wpdb->get_row("SELECT * FROM $table_app_post WHERE app_id = '$app_id'AND post_id = '$post_id'");
                if ($app_data_post){
                    $wpdb->query( "DELETE FROM $table_app_post WHERE app_id ={$app_id}" );

                    $wpdb->insert($table_app_post, array(
                        'app_id' => $app_id,
                        'post_id' => $post_id,
                    ));
                    $data = array(
                        "success" => true,
                        "status" => 200,
                        "msg" => "the post registered with this app"
                    );
                    $wpdb->update($table_app, array(
                        'status' => "connected"
                    ), array(
                        'id' => $app_id
                    ));

                }else{
                    $wpdb->insert($table_app_post, array(
                        'app_id' => $app_id,
                        'post_id' => $post_id,
                    ));
                    $data = array(
                        "success" => true,
                        "status" => 200,
                        "msg" => "the post registered with this app"
                    );
                    $wpdb->update($table_app, array(
                        'status' => "connected"
                    ), array(
                        'id' => $app_id
                    ));
                }
            }
        }


     wp_send_json($data);

    }
    public static function AppsUpdated($date_of_apps){
        global $wpdb;
        $base = new BaseController();
        $table_event = $base->table_event;
        $app_ids = array();
        $updated_app_ids = $wpdb->get_results("
    SELECT *
    FROM (
      SELECT DATE(created_at) AS event_date, updated_app_id , updated_app
      FROM {$table_event}
      WHERE updated_app_id IS NOT NULL
        AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        AND  updated_app != 0
    ) AS daily_events
    JOIN (
      SELECT DATE(created_at) AS event_date, updated_app_id, id,updated_app
      FROM {$table_event}
      WHERE updated_app_id IS NOT NULL
      AND  updated_app != 0

    ) AS event 
    ON DATE(event.event_date) = daily_events.event_date
      AND CONCAT(',', event.updated_app_id, ',') LIKE CONCAT('%,', daily_events.updated_app_id, ',%');
        ");
        foreach($updated_app_ids as $date){
            if (isset($app_ids[$date->event_date])){
                foreach (json_decode($date->updated_app_id) as $id){
                    if (!in_array($id,$app_ids[$date->event_date])){
                        array_push($app_ids[$date->event_date],$id);
                    }
                }
            }else{
                $app_ids[$date->event_date] = json_decode($date->updated_app_id);

            }

        }
        return $app_ids[$date_of_apps];
    }
    public static function get_apps($app_ids){
        global $wpdb;

        $my_array_string = implode(',', $app_ids);
        $base = new BaseController();
        $table_app = $base->table_app_info;


        $results = $wpdb->get_results('SELECT * FROM '.$table_app.' WHERE id IN ('.$my_array_string.')' );
        return $results;
    }
    public static function get_apps_history($app_id,$date){
        global $wpdb;
        $id = intval($app_id);
        $base = new BaseController();
        $table_history = $base->table_history;

        $results = $wpdb->get_results('SELECT * FROM '.$table_history.' AS history WHERE app_id = '.$id.' AND Date(created_at) = "'.$date.'" ORDER BY `id` DESC');

// Group the results by date and time
        $grouped_results = array();
        foreach ($results as $row) {
            $datetime = date('Y-m-d H:i', strtotime($row->created_at));
            if (!isset($grouped_results[$datetime])) {
                $grouped_results[$datetime] = array(
                    'datetime' => $datetime,
                    'rows' => array(),
                );
            }
            $grouped_results[$datetime]['rows'][] = $row;
        }
// Flatten the nested array structure
        $output = array_values($grouped_results);

        return $output;

    }
    public static function EmptyTables(){
        global $wpdb;
        $base = new BaseController();

        $table_app_info = $base->table_app_info;
        $table_history = $base->table_history;
        $table_meta_app = $base->table_dt_meta;
        $table_meta_app_post = $base->table_app_post;
        $table_category = $base->table_category;
        $table_website = $base->table_website;
        Apps::truncate_table($table_app_info);
        Apps::truncate_table($table_meta_app_post);
        Apps::truncate_table($table_history);
        Apps::truncate_table($table_meta_app);
        Apps::truncate_table($table_category);
        Apps::truncate_table($table_website);

    }
    public static function store_data(){
        global $wpdb;
        $base = new BaseController();
        $table_settings = $base->table_settings;
        $table_app_info = $base->table_app_info;
        $table_history = $base->table_history;
        $table_meta_app = $base->table_dt_meta;
        $table_meta_app_post = $base->table_app_post;
        $table_event=$base->table_event;

        Apps::truncate_table($table_app_info);
        Apps::truncate_table($table_meta_app_post);
        Apps::truncate_table($table_history);
        Apps::truncate_table($table_meta_app);
        Apps::truncate_table($table_event);


        $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
        $api_url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
        $use_tag_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'use_tag'");
        $token =  $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_token'");

        $client = new \GuzzleHttp\Client();
        $response = $client->get("{$api_url->value}/api/project/apps?project_id={$project_setting->value}&use_tag={$use_tag_setting->value}",[
            'headers' => [
                'Authorization' => 'Bearer ' . $token->value,
                'Accept'        => 'application/json',
            ],
        ]);
        $data_main = json_decode($response->getBody());
        $lastPage = $data_main->last_page;
        for ($i = 1; $i<= $lastPage; $i++) {
            $client2 = new \GuzzleHttp\Client();

            $response = $client2->request('GET', "{$api_url->value}/api/project/apps?project_id={$project_setting->value}&use_tag={$use_tag_setting->value}&page=" . $i,[
                'headers' => [
                    'Authorization' => 'Bearer ' . $token->value,
                    'Accept'        => 'application/json',
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            if (isset($data["app"]) && is_array($data["app"])) {
                foreach ($data["app"] as $app) {
                    $aun_status = 0;
                    $app_data = $wpdb->get_row("SELECT * FROM " . $table_app_info . " WHERE api_app_id	= " . $app["id"] . "");
                    $aun_setting_id = 0;
                    $app_data_id = 0;
                    if (isset($app["package"]["package_name"]) &&  $app["package"]["package_name"] != ''){
                        $package = $app["package"]["package_name"];
                    }else{
                        $package = "not found";
                    }
                    if (!$app_data) {
                        $app_table = $wpdb->insert($table_app_info, array(
                            'name' => $app["name"],
                            'package_name' => $package,
                            'app_update_number' => $app["aun"],
                            'api_app_id' =>$app["id"] ,
                            'url' =>$app["url"],
                            'status' => 'new' // set status to 'new' when inserting a new row
                        ));
                    } else {
                        $app_data_id = $app_data->id;
                        if ($app["aun"] > $app_data->app_update_number) {
                            // Update the existing row
                            $data = array(
                                'name' => $app["name"],
                                'package_name' => $app["package"]["package_name"],
                                'app_update_number' => $app["aun"],
                                'url' =>$app["url"],
                                'status' => 'updated' // set status to 'new' when inserting a new row
                            );
                            $wpdb->update($table_app_info, $data, array('id' => $app_data_id), $format = null, $where_format = null);

                            $aun_status = 2;
                        }
                    }

                }
            }

        }
        // Insert data into history table


    }
    public function store_process(){
        global $wpdb;
        $table_settings = $this->table_settings;
        $table_app_info = $this->table_app_info;
        $table_history = $this->table_history;
        $table_event = $this->table_event;
        $table_meta_app_post = $this->table_app_post;
        $table_meta_app = $this->table_dt_meta;

        $event=$wpdb->insert($table_event,array(
            "created_at" => date("Y-m-d H:i:s"),
            'updated_app' => 0,
        ));
        if ($event !== false) {
            $id = $wpdb->insert_id;
        }
        $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
        $api_url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
        $use_tag_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'use_tag'");
        $token =  $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_token'");

        $client = new \GuzzleHttp\Client();
        $response = $client->get("{$api_url->value}/api/project/apps?project_id={$project_setting->value}&use_tag={$use_tag_setting->value}",[
            'headers' => [
                'Authorization' => 'Bearer ' . $token->value,
                'Accept'        => 'application/json',
            ],
        ]);
        $data_main = json_decode($response->getBody());
        $lastPage = $data_main->last_page;
        for ($i = 1; $i<= $lastPage; $i++) {
            $client2 = new \GuzzleHttp\Client();

            $response = $client2->request('GET', "{$api_url->value}/api/project/apps?project_id={$project_setting->value}&use_tag={$use_tag_setting->value}&page=" . $i,[
                'headers' => [
                    'Authorization' => 'Bearer ' . $token->value,
                    'Accept'        => 'application/json',
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            if (isset($data["app"]) && is_array($data["app"])) {
                foreach ($data["app"] as $app) {
                    $aun_status = 0;
                    if (isset($app["id"]) && $app["id"] != null){
                    $app_data = $wpdb->get_row("SELECT * FROM  {$table_app_info} WHERE api_app_id = {$app["id"]}");

                    $aun_setting = $wpdb->get_row("SELECT * FROM {$table_app_info} WHERE `app_update_number` = " .  $app["aun"] . "");
                    $aun_setting_id = 0;
                    if (isset($app_data) && $app_data != null ){
                        $app_data_id = intval($app_data->id);
                    $app_data_post = $wpdb->get_row("SELECT * FROM {$table_meta_app_post} WHERE app_id = {$app_data_id}");
                    if ($app_data_post) {
                        if (isset($app["process"]) && is_array($app["process"]) && count($app["process"]) > 0) {

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


                            foreach ($app["process"] as $process_item) {
                                $key = trim($process_item["key"]);
                                $value = $process_item["value"];

                                    $wpdb->insert($table_history, array(
                                        'key' => $key,
                                        'value' => $value,
                                        'app_id' => $app_data_id
                                    ));
                                $app_data_key = $wpdb->get_row("SELECT * FROM {$table_meta_app} WHERE `key`='$key' AND `app_id` = {$app_data_id}");

                                    // Key does not exist, insert data
                                    if (isset($app_data_key)&&$app_data_key!="") {

                                        if (trim($key) == "logo") {
                                            try {
                                                $logo = Apps::addLogoImage($value, $app_data_id);
                                                if (isset($logo) && $logo != null){
                                                    $wpdb->update($table_meta_app, array(
                                                        'value' => $logo
                                                    ), array(
                                                        'app_id' => $app_data_id,
                                                        'key' => $key
                                                    ));
                                                }

                                            }catch (\Exception $exception) {
                                                my_plugin_log('Error' . $exception->getMessage());

                                            }
                                        }elseif (trim($key) == "thumbnail") {
                                            try {

                                                $logo = self::addFeaturedImage($value, $app_data_id);
                                                if (isset($logo) && $logo != null) {
                                                    $wpdb->update($table_meta_app, array(
                                                        'value' => $logo
                                                    ), array(
                                                        'app_id' => $app_data_id,
                                                        'key' => $key
                                                    ));
                                                }
                                            }catch (\Exception $exception) {
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

                                                $screenshot = Apps::redesign_the_screenshot($value,$app["name"],$app_data_id);
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
                                        }else{

                                            $wpdb->update($table_meta_app, array(
                                                'value' => $value,
                                                'key' => $key
                                            ), array(
                                                'app_id' => $app_data_id,
                                                'key' => $key
                                            ));
                                        }
                                    }else{
                                        if (trim($key) == "logo") {
                                            try {
                                                $logo = Apps::addLogoImage($value, $app_data_id);
                                                if (isset($logo) && $logo != null) {
                                                    $wpdb->insert($table_meta_app, array(
                                                        'app_id' => $app_data_id,
                                                        'key' => $key,
                                                        'value' => $logo
                                                    ));
                                                }
                                            }catch (\Exception $exception) {
                                                my_plugin_log('Error' . $exception->getMessage());
                                            }
                                        }elseif(trim($key) == "thumbnail") {
                                            try {
                                                $logo = Apps::addFeaturedImage($value, $app_data_id);
                                                if (isset($logo) && $logo != null) {
                                                    $wpdb->insert($table_meta_app, array(
                                                        'app_id' => $app_data_id,
                                                        'key' => $key,
                                                        'value' => $logo
                                                    ));
                                                }
                                            }catch (\Exception $exception) {
                                                my_plugin_log('Error' . $exception->getMessage());

                                            }
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

                                                $screenshot = Apps::redesign_the_screenshot($value,$app["name"],$app_data_id);

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
                                        }else{
                                            $wpdb->insert($table_meta_app, array(
                                                'app_id' => $app_data_id,
                                                'key' => $key,
                                                'value' => $value
                                            ));
                                        }
                                    }

                            }
                        }
                    }}
                }
                }
                $event_app_updated =  $wpdb->get_row("SELECT * FROM {$table_event} WHERE `id` = {$id} ");
                $app_updated=$event_app_updated->updated_app ;
                $table_meta_app_post = $this->table_app_post;;
                $app_post_count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_meta_app_post} ");
                $data=array(
                    "status" => 200,
                    "success" =>true,
                    "msg" => "Scrapping Data Successfully <br> the number of app updated now : $app_updated  <br> the Count of  Post Related with Apps  : $app_post_count "

                );

            } else{
                $data=array(
                    "status" => 200,
                    "success" =>false,
                    "msg" => "they don't have any data"
                );
            }
        }
        // Insert data into history table

        wp_send_json($data);

    }
    static function truncate_table($table_name) {
        global $wpdb;

        // Disable foreign key checks
        $wpdb->query('SET FOREIGN_KEY_CHECKS = 0;');

        // Truncate the table
        $wpdb->query("TRUNCATE TABLE $table_name;");

        // Enable foreign key checks
        $wpdb->query('SET FOREIGN_KEY_CHECKS = 1;');
    }
    public static function save_post_meta($app_id,$key,$value){
        global $wpdb;
        $base = new BaseController();
        $table_meta_app_post = $base->table_app_post;;
        $posts = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE app_id = {$app_id}");
        foreach ($posts as $post){
            update_post_meta($post->ID,"dt_".$key,$value);

        }
    }
    public function change_status_of_key(){
        global $wpdb;
        $id=$_GET["id"];
        $status=$_GET["status"];
        $table_meta_app = $this->table_dt_meta;
        // $posts = $wpdb->get_row("SELECT * FROM {$table_dt_meta} WHERE app_id = {$app_id} && ");
        $wpdb->update($table_meta_app, array(
            'status' => $status,
        ), array(
            'id' => $id,
        ));
        $data = array(
            "success" => true,
            "status" => 200,
            "msg" => "the status of key is changed"
        );
        wp_send_json($data);

    }
    public function disable_app(){
        global $wpdb;
        $app_id = intval($_GET['id']);
        $table_app = $this->table_app_info;
        $wpdb->update($table_app, array(
            'status' => $_GET['status']
        ), array(
            'id' => $app_id
        ));
        $data = array(
            "success" => true,
            "status" => 200,
            "msg" => "the App is disabled successfully"
        );
        wp_send_json($data);

    }
    public static function addFeaturedImage($image_url,$app_id )
    {

        my_plugin_log('3.1-Start Add Feature Image ');

        include(ABSPATH . '/wp-load.php');
        include_once(ABSPATH . '/wp-admin/includes/image.php');
        global $wpdb;
        $base = new BaseController();
        $table_meta_app_post = $base->table_app_post;
        $table_app_info=$base->table_app_info;
        $app_data_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE app_id = {$app_id}");
        $app_data = $wpdb->get_row("SELECT * FROM {$table_app_info} WHERE id = {$app_id}");
        $app_name=$app_data->name;
        my_plugin_log('3.1- URL:'.$image_url);



        if ($app_data_post){
            foreach ($app_data_post as $post){
                $current_thumbnail_id = get_post_thumbnail_id($post->id);

                // If the post already has a thumbnail, delete it
                if ($current_thumbnail_id) {
                    wp_delete_attachment($current_thumbnail_id, true);
                }

                $imagetypeexplode = explode('/', getimagesize($image_url)['mime']);
                $imagetype = end($imagetypeexplode);
                $filename = $app_name . '_thumbnail.' . $imagetype;

                $uploaddir = wp_upload_dir();
                $uploadfile = $uploaddir['path'] . '/' . $filename;

                $contents = file_get_contents($image_url);

                if ($contents != FALSE) {
                    $savefile = fopen($uploadfile, 'w');
                    fwrite($savefile, $contents);
                    fclose($savefile);

                    $wp_filetype = wp_check_filetype(basename($filename), null);
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => $filename,
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    $attach_id = wp_insert_attachment($attachment, $uploadfile);
                    $imagenew = get_post($attach_id);
                    $fullsizepath = get_attached_file($imagenew->ID);
                    $attach_data = wp_generate_attachment_metadata($attach_id, $fullsizepath);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    // Update the post thumbnail meta
                    update_post_meta($post->id, '_thumbnail_id', $attach_id);

                    return get_the_post_thumbnail_url($post->id);
                }

            }
        }
    }
    public static function addLogoImage($logo_url, $app_id)
    {
        include(ABSPATH . '/wp-load.php');
        include_once(ABSPATH . '/wp-admin/includes/image.php');
        global $wpdb;
        $base = new BaseController();
        $table_meta_app_post = $base->table_app_post;
        $table_app_info = $base->table_app_info;

        $post = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_meta_app_post} WHERE app_id = %d", $app_id));
        $app_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_app_info} WHERE id = %d", $app_id));

        if ($post && $app_data) {
            $app_name = $app_data->package_name;


                $current_thumbnail_id = get_post_thumbnail_id($post->post_id);

                // If the post already has a thumbnail, delete it
                if ($current_thumbnail_id) {
                    wp_delete_attachment($current_thumbnail_id, true);
                }

                $imagetypeexplode = explode('/', getimagesize($logo_url)['mime']);
                $imagetype = end($imagetypeexplode);
                $name = $app_name . '_logo.' . $imagetype;
                $normalized_file_name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
                $filename = preg_replace('/[^\w\-_.]/', '', $normalized_file_name);
                $uploaddir = wp_upload_dir();
                $uploadfile = $uploaddir['path'] . '/' . $filename;

                // Download the logo image and save it to the upload directory
                $contents = file_get_contents($logo_url);
                if ($contents === false) {
                    return false; // Unable to fetch the logo image from the provided URL
                }

                $savefile = fopen($uploadfile, 'w');
                if (!$savefile) {
                    return false; // Unable to create or open the logo image file for writing
                }

                if (fwrite($savefile, $contents) === false) {
                    fclose($savefile);
                    return false; // Unable to write the logo image contents to the file
                }

                fclose($savefile);

            $wp_filetype				= wp_check_filetype(basename($filename), null );
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => $filename,
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id					= wp_insert_attachment( $attachment, $uploadfile );
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data				= wp_generate_attachment_metadata( $attach_id, $uploadfile );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            set_post_thumbnail( $post->post_id, $attach_id );
            return wp_get_attachment_url($attach_id);

        }


        return false; // Unable to find the app data or app data post
    }

    public function bulk_action(){
        $decoded_string = stripslashes($_POST["record_ids"]);
        $array = json_decode($decoded_string, true);
        if(count($array) != 0){

            foreach ($array as $recordId) {

                $this->disable_apps($recordId);
            }//end of for each
            $data = array(
                "success" => true,
                "status" => 200,
                "msg" => "the App is disabled successfully"
            );
            wp_send_json($data);
        }else{
            $data = array(
                "success" => false,
                "status" => 400,
                "msg" => "Don't have Apps"
            );
            wp_send_json($data);
        }

    }
    public function disable_apps($app_id){
        global $wpdb;
        $table_app = $this->table_app_info;
        $wpdb->update($table_app, array(
            'status' => 'disabled'
        ), array(
            'id' => $app_id
        ));


    }
    public static function check_if_key_change($new_date,$old_date,$key){
        global $wpdb;
        $base = new BaseController();
        $table_history = $base->table_history;
        if ($old_date != ''){
             $old_key = $wpdb->get_row("SELECT * FROM {$table_history} WHERE `key` = '{$key}' AND DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = '{$old_date}'");
            $new_key=  $wpdb->get_row("SELECT * FROM {$table_history} WHERE `key` = '{$key}' AND DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = '{$new_date}'");

            if ($old_key->value != $new_key->value){
                return $data=array(
                    "old_value" => $old_key->value,
                    "new_value" => $new_key->value,
                    "key" => $key,
                );
            }else{
                return $data = null;
            }
        }





    }
    public static function check_if_has_old_files($id){
        global $wpdb;
        $base = new BaseController();
        $table_app = $base->table_app_info;
        $table_settings = $base->table_settings;
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
    public static function add_post_title($app_id,$value){
        global $wpdb;
        $base = new BaseController();
        $table_meta_app_post = $base->table_app_post;
        $post_app = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_meta_app_post} WHERE app_id = %d", $app_id));
        // Step 1: Get the post ID
        $post =get_post( $post_app->post_id );//call the post data
        $new_title= $value;
// Step 3: Update the post title
        $post->post_title = $new_title;
// Step 4: Save the updated post
        wp_update_post($post);

    }
    public static function edit_post_content($app_id,$value){
        global $wpdb;
        $base = new BaseController();
        $table_meta_app_post = $base->table_app_post;
        $post_app = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_meta_app_post} WHERE app_id = %d", $app_id));
        // Step 1: Get the post ID
        $content= $value;
        $filtered_content = wp_kses_post($content);
        $post =get_post( $post_app->post_id );//call the post data
// Step 3: Update the post title
        $post->post_content  = $content;
// Step 4: Save the updated post
        wp_update_post($post);

    }
    public static function redesign_the_screenshot($value,$name,$id){

        global $wpdb;
        $base = new  BaseController();
        $table_app_info = $base->table_dt_meta;
        $screenshots = $wpdb->get_row("SELECT * FROM  {$table_app_info} WHERE app_id = {$id} AND `key` = 'screenshots' ");
        $screenshots_html = $wpdb->get_row("SELECT * FROM  {$table_app_info} WHERE app_id = {$id} AND `key` = 'screenshots_html' ");
        if (isset($screenshots_html)){
            if ($value != $screenshots_html->value){
                $wpdb->update($table_app_info, array(
                    'value' => json_encode($value)
                ), array(
                    'app_id' => $id,
                    'key' => 'screenshots_html'
                ));
                $htmlCode = $value; // Replace with the HTML code you want to extract the links from

// Regular expression pattern to match the image URLs
                $pattern = '/<img[^>]+src="([^">]+)"/';

// Perform the regular expression match
                preg_match_all($pattern, $htmlCode, $matches);

// Extract the matched URLs from the regex matches and store them in an array
                $imageURLs = $matches[1];

// Print the extracted image URLs
                $data = self::get_offical_image_size($imageURLs);
                $save_screenshot = self::store_screen_shot_in_file($data,$name,$id);

            }else{
                $save_screenshot = $screenshots->value;
            }

        }else{
            $wpdb->insert($table_app_info, array(
                'app_id' => $id,
                'key' => 'screenshots_html',
                'value' => json_encode($value)
            ));
            $htmlCode = $value; // Replace with the HTML code you want to extract the links from

// Regular expression pattern to match the image URLs
            $pattern = '/<img[^>]+src="([^">]+)"/';

// Perform the regular expression match
            preg_match_all($pattern, $htmlCode, $matches);

// Extract the matched URLs from the regex matches and store them in an array
            $imageURLs = $matches[1];

// Print the extracted image URLs
            $data = self::get_offical_image_size($imageURLs);
            $save_screenshot = self::store_screen_shot_in_file($data,$name,$id);

        }


     return $save_screenshot ;


    }

    public static function get_offical_image_size($values){
        $redisign_value = [];
        foreach ($values as $value){
            if (str_contains($value, "https://play-lh.googleusercontent.com/")) {
                $url_logo = explode("=",$value);
                $url = $url_logo[0]."=w5120-h2880-rw";
            }elseif (str_contains($value, "https://image.winudf.com/")){
                $url_logo = explode("?",$value);
                $url = $url_logo[0]."?fakeurl=1&type=.webp";
            }elseif (str_contains($value, "https://lh3.googleusercontent.com/")) {
                $url_logo = explode("=",$value);
                $url = $url_logo[0];
            }elseif (str_contains($value, "https://img.utdstc.com/")){
                $url = $value.":800";
            }else{
                $url = $value;
            }

            array_push($redisign_value,$url);
        }

        return $redisign_value;
    }

    public static function store_screen_shot_in_file($data,$app_name,$id){
        global $wpdb;
        $base = new  BaseController();
        $table_app_info = $base->table_dt_meta;
        $table_app = $base->table_app_info;
        $app_data = $wpdb->get_row("SELECT * FROM  {$table_app_info} WHERE app_id = {$id} AND `key` = 'screenshots' ");

        $app_data_info = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_app} WHERE id = %d", $id));
        $app_name = $app_data_info->package_name;


//        if (isset($app_data->value)){
        $old_screenshots = json_decode($app_data->value);
        if ($old_screenshots != null  && $old_screenshots != ""){
            if (is_array($old_screenshots) && count($old_screenshots) > 0){
                foreach ($old_screenshots as $imageUrl) {
                    $filePath = parse_url($imageUrl, PHP_URL_PATH);
                    // Convert the URL-encoded characters in the path
                    $filePath = urldecode($filePath);
                    // Get the absolute path on the server
                    $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $filePath;
                    if (file_exists($absolutePath)) {

                        unlink($absolutePath); // Delete the image file
                    }
                }

            }
        }
//        }
        $image_urls = array();
        if ($data != "" && count($data)>0){
             foreach ($data as $single_data){
                 $response =file_get_contents($single_data);
                 $destination_folder =  WP_PLUGIN_DIR  . '/dt-apps-scrapper/assets/screenshot/';
                 $name = $app_name . '_' . time() . '.jpg';
                 $normalized_file_name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
                 $filename = preg_replace('/[^\w\-_.]/', '', $normalized_file_name);
                 $destination_file = $destination_folder . $filename;
                 file_put_contents($destination_file, $response);
                 $image_url = site_url("/wp-content/plugins/dt-apps-scrapper/assets/screenshot/" . $filename);
                 array_push($image_urls,$image_url);

             }
        }
        return $image_urls;
    }
}
