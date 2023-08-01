<?php
namespace Inc\Base;
require_once plugin_dir_path(__FILE__) . '/BaseController.php';

class Activate extends BaseController {
    
    function __construct() {
        flush_rewrite_rules();
        global $wpdb;

        $tbl_settings = $this->table_settings;
        $tbl_history = $this->table_history;
        $tbl_app_info = $this->table_app_info;
        $tbl_dt_meta = $this->table_dt_meta;
        $tbl_app_post = $this->table_app_post;
        $tbl_name_log = $this->table_name_log;
        $tbl_category = $this->table_category;
        $tbl_website = $this->table_website;
        $tbl_event = $this->table_event;
//        $tbl_settings = new BaseController();

        $charset_collate = $wpdb->get_charset_collate();

        #2 Create Tables

        $sql_settings = "CREATE TABLE IF NOT EXISTS $tbl_settings (
                            id int(11) NOT NULL AUTO_INCREMENT,
                            `key` varchar(255) NOT NULL,
                            value longtext NOT NULL,
                            PRIMARY KEY  (id)
                        ) $charset_collate;";

//        id int(11) NOT NULL AUTO_INCREMENT,

        $sql_app_info = "CREATE TABLE IF NOT EXISTS $this->table_app_info (                        
                            id int(11) NOT NULL,
                            `name` varchar(255) NOT NULL,
                            package_name varchar(255) NOT NULL,
                            api_app_id int(11) NOT NULL,
                            `url` varchar(255) NOT NULL,
                            app_update_number int(11) NOT NULL,
                            `status` enum('new','updated','connected','disabled') NOT NULL DEFAULT 'new',
                            PRIMARY KEY  (id)
                        )$charset_collate;";
        $sql_history = "CREATE TABLE IF NOT EXISTS $this->table_history (
            id int(11) NOT NULL AUTO_INCREMENT,
            `key` varchar(255) NOT NULL,
            `value` text NOT NULL,
            app_id int(11) NOT NULL,
            created_at timestamp NOT NULL,
            FOREIGN KEY (app_id) REFERENCES $this->table_app_info (id),
            PRIMARY KEY (id)
        )$charset_collate;";

        $sql_dt_meta = "CREATE TABLE IF NOT EXISTS $this->table_dt_meta (
                            id int(11) NOT NULL AUTO_INCREMENT,
                            app_id int(11) NOT NULL,
                            `key` varchar(255) NOT NULL,
                            `value` text NOT NULL,
                            `status` tinyint(4) NOT NULL DEFAULT 1 ,
                            PRIMARY KEY (id)
                        )$charset_collate;";

        $sql_post = "CREATE TABLE IF NOT EXISTS  $this->table_app_post (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        app_id INT NOT NULL,
                        post_id BIGINT(20) UNSIGNED NOT NULL,
                        FOREIGN KEY (app_id) REFERENCES $this->table_app_info (id),
                         PRIMARY KEY (id)
                        )$charset_collate;";

        $sql_log = "CREATE TABLE IF NOT EXISTS $this->table_name_log (
                        id mediumint(9) NOT NULL AUTO_INCREMENT,
                        project int(11) NOT NULL,
                        tag varchar(100) NOT NULL,
                        date datetime NOT NULL,
                        data  longtext NOT NULL,
                        PRIMARY KEY  (id)
                    ) $charset_collate;";

          $sql_events = "CREATE TABLE IF NOT EXISTS $this->table_event (
            id int(11) NOT NULL AUTO_INCREMENT,
            created_at timestamp NOT NULL,
            updated_at timestamp NOT NULL,
            updated_app int(11) NOT NULL ,
            updated_app_id text NOT NULL ,
            PRIMARY KEY  (id)
        ) $charset_collate;";

         
        $sql_category = "CREATE TABLE IF NOT EXISTS $this->table_category (
             id int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `status` int(11) NOT NULL ,
             api_id INT NOT NULL,
            PRIMARY KEY  (id) 
        ) $charset_collate;";

        $sql_website = "CREATE TABLE IF NOT EXISTS $this->table_website (
             id int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `url` varchar(255) NOT NULL,
            `status` int(11) NOT NULL ,
            api_id INT NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";



        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_settings);
        dbDelta($sql_history);
        dbDelta($sql_app_info);
        dbDelta($sql_dt_meta);
        dbDelta($sql_post);
        dbDelta($sql_log);
        dbDelta($sql_category);
        dbDelta($sql_website);
        dbDelta($sql_events);

            // Update keys that are NULL
            $row_api_url = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'api_url'");
            if($row_api_url == NULL) {
                $settings_insert_api_url = "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'api_url', '');";
                dbDelta($settings_insert_api_url);
            }
        
            $row_api_email = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'api_email'");
            if($row_api_email == NULL) {
                $settings_insert_api_email = "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'api_email', '');";
                dbDelta($settings_insert_api_email);
            }
        
            $row_api_password = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'api_password'");
            if($row_api_password == NULL) {
                $settings_insert_api_password = "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'api_password', '');";
                dbDelta($settings_insert_api_password);
            } 

            $row_api_token = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'api_token'");
            if($row_api_token == NULL) {
                $settings_insert_api_token = "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'api_token', '');";
                dbDelta($settings_insert_api_token);
            }

            $row_api_connected = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'api_connected'");
            if($row_api_connected == NULL) {
                $settings_insert_api_connected = "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'api_connected', '0');";
                dbDelta($settings_insert_api_connected);
            }
            $row_name = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'name'");
            if($row_name == NULL) {
                $settings_insert_name= "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'name', '');";
                dbDelta($settings_insert_name);
            }
            $row_type = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'type'");
            if($row_type == NULL) {
                $settings_insert_type= "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'type', '');";
                dbDelta($settings_insert_type);
            }

            $row_created_at = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'created_at'");
            if($row_created_at == NULL) {
                $settings_insert_created_at= "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'created_at', '');";
                dbDelta($settings_insert_created_at);
            }
            $row_last_connected= $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'last_connected'");
            if($row_last_connected == NULL) {
                $settings_insert_last_connected= "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'last_connected', '');";
                dbDelta($settings_insert_last_connected);
            }

        $row_cron_checker = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'cron_checker'");
        if($row_cron_checker == NULL) {
            $settings_insert_cron_checker= "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'cron_checker', '0');";
            dbDelta($settings_insert_cron_checker);
        }
        $row_content_place = $wpdb->get_row("SELECT * FROM $this->table_settings WHERE `key` = 'content_place'");
        if($row_content_place == NULL) {
            $content_place = ["in_top"];
            $json = json_encode($content_place);
            $settings_insert_content_place = "INSERT INTO $this->table_settings (`id`, `key`, `value`) VALUES (NULL, 'content_place', '$json')";
            dbDelta($settings_insert_content_place);
        }
    
    
        }

    }