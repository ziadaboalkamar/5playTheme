<?php
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Base;
class Disconnect {

    function __construct($disconnect) {
        global $wpdb;
        $base= new BaseController();
        $table_settings = $base->table_settings;
        $project_setting = $wpdb->get_row("SELECT * FROM $table_settings WHERE `key` = 'api_connected'");
        if ($project_setting){
            $wpdb->update($table_settings, array(
                'value' => $disconnect,
            ), array(
                'id' => $project_setting->id,
            ));
//            session_start();
            $_SESSION['success_msg'] = "Disconnect Successfully";
            return header("Location: admin.php?page=settings");

        }else{
//            session_start();
            $_SESSION['error_msg'] = "Disconnect Failed";
            return header("Location: admin.php?page=settings");

        }

    }
}