<?php
/**
 * @package  dt-apps-scrapper
 */
namespace Inc\Base;
require_once plugin_dir_path(__FILE__) . '/BaseController.php';
require_once get_template_directory() .'/libs/addons/vendor/autoload.php';

class AuthController extends BaseController {
    public $email, $password, $url, $token="", $connected=0,$disconnet = 0;
    public $success = false;
    public $name = "";
    public $created_at ="";
    public $last_connect="";
    public $type="";
    public $error_msg ="";
    public $cron_checker= '0';


    public function __construct($email, $password, $url) {
        $this->email = $email;
        $this->password = $password;
        $this->url = $url;

    }

    public function validInput() {
        if($this->error_msg != "") return;
        if($this->email == "") {
            $this->error_msg = 'Email is required.';

        }
        if($this->password == "") {
            $this->error_msg = 'Password is required.';

        }
        if($this->url == "") {
            $this->error_msg = 'Api URL is required.';
        }
    }

    public function connectApi() {
        if($this->error_msg != "") return true;
        try {

            $client = new \GuzzleHttp\Client();
            $response = $client->post("{$this->url}/api/auth/login", [
                'form_params' => [
                    'email' => $this->email,
                    'password' => $this->password
                ]
            ]);
            if ($response->getStatusCode() != 200){
                return $this->error_msg = $response->getHeaders();

            }

            $current_date_time = date('Y-m-d H:i:s');
            $data = json_decode($response->getBody());
            if($data->success == true) {
                $this->token = $data->token;
                $this->name = $data->user->name;
                $this->created_at = $data->user->created_at;
                $this->type= $data->user->type;
                $this->last_connect = $current_date_time;
                $this->connected = '1';
                $this->success = true;
                $this->cron_checker = '1';
            }elseif($data->success == false){
                session_start();
                $_SESSION['error_msg'] = "You must Connect to Api first.";
                return header("Location: admin.php?page=settings");

            } }
        catch(\Exception $e){

            if ($e->getCode() == 400){
                return $this->error_msg ="The E-mail or Password not correct pleas checked him !";
            }else{
                return $this->error_msg = $e->getMessage();
            }
        }

    }

    public function storeIntoDB() {
        if($this->error_msg != "") return;
        global $wpdb;
        $table_settings = $this->table_settings;

        $wpdb->update($table_settings, array('value' => $this->email), array('key' => 'api_email'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->password), array('key' => 'api_password'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->url), array('key' => 'api_url'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->token), array('key' => 'api_token'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->connected), array('key' => 'api_connected'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->name), array('key' => 'name'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->type), array('key' => 'type'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->created_at), array('key' => 'created_at'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->last_connect), array('key' => 'last_connected'), array('%s'), array('%s'));
        $wpdb->update($table_settings, array('value' => $this->cron_checker), array('key' => 'cron_checker'), array('%s'), array('%s'));

    }

    public function redirect() {
        if($this->error_msg != "") return;

        if($this->success == true) {
            global $wpdb;
            $table_settings = $this->table_settings;
            $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
            $use_tag_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'use_tag'");

            if ($project_setting && $use_tag_setting ){
//                session_start();
                $_SESSION['success_msg'] = "Connection established successfully!";
                header("Location: admin.php?page=scrapper_apps");
            }else{
//                session_start();
                $_SESSION['success_msg'] = "Connection established successfully!";
                header("Location: admin.php?page=scrapper_projects");
            }


        }
        else header("Location: admin.php?page=settings");
    }
    public function print_Error() {
        $_SESSION['error_msg'] =$this->error_msg;

    }
}