<?php 
/**
 * @package  dt-apps-scrapper
 */
namespace Inc\Base;
require_once plugin_dir_path(__FILE__) . '/BaseController.php';

use WpOrg\Requests\Exception;

class Websites extends BaseController {
	public $connected=0;
	public $success = false;
    public $id = "";
	public $name = "";
	public $url ="";
	public $error_msg ="";
    public $setting_table;

	public function __construct() {

    }
   
    public function getData() {
        try {
            Websites::insert_Website();

        }catch(\Exception $exception){
            session_start();
            $_SESSION['error_msg'] = $exception->getMessage();
        }
        header("Content-Type: application/json");

        $request = $_GET;
        
        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'name', 'dt' => 1 ),
            array( 'db' => 'url', 'dt' => 2),
            array( 'db' => 'status', 'dt' => 3),
        );
        
        global $wpdb;
        $table_website =$this->table_website;
        $limit = $request['length']; // number of rows in page
        $offset = $request['start'];
        $totalData = $wpdb->get_var("select count(*) as total from $table_website");
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
        
        $sql = "SELECT * FROM {$table_website} $searchQuery $order_by $limit_query";
        $results = $wpdb->get_results($sql);
        $rowcount = $wpdb->num_rows;
        $options = '';
        $data = array();
        if ($rowcount > 0) {
            foreach ($results as $row) {
                $nestedData = array();
                $nestedData['id'] = $row->id;
                $nestedData['name'] = $row->name;
                $nestedData['status'] =  $row->status;
                $nestedData['url'] = $row->url;
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

    static function insert_Website(){
        try {
            global $wpdb;
            $base = new BaseController();
            $table_settings = $base->table_settings;
            $table_website =$base->table_website;

            $api_url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
            $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
            $token =  $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_token'");

            $client = new \GuzzleHttp\Client();
            $response = $client->get("{$api_url->value}/api/project/websites?project_id={$project_setting->value}",[
                'headers' => [
                    'Authorization' => 'Bearer ' . $token->value,
                    'Accept'        => 'application/json',
                ],
            ]);
            $data_main = json_decode($response->getBody());
            $lastPage = $data_main->last_page;
            for ($i = 1; $i<= $lastPage; $i++) {
                $client2 = new \GuzzleHttp\Client();

                $response = $client2->request('GET', "{$api_url->value}/api/project/websites?project_id={$project_setting->value}&page=" . $i,[
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token->value,
                        'Accept'        => 'application/json',
                    ],
                ]);
                $data = json_decode($response->getBody(), true);

                foreach ($data["websites"] as $website){
                    $old_website = $wpdb->get_row("SELECT * FROM {$table_website} WHERE `api_id` = {$website["id"]}");
                    if (!$old_website){
                        $app_table = $wpdb->insert($table_website, array(
                            'name' => $website["name"], // set status to 'new' when inserting a new row
                            'url' => $website["url"],
                            'status' => $website["status"],
                            'api_id' => $website["id"]
                        ));
                    }

                }
            }
        }catch(Exception $exception){
            session_start();
            $_SESSION['error_msg'] = $exception->getMessage();
        }

    }


    }
