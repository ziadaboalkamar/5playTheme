<?php
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Base;
require_once plugin_dir_path(__FILE__) . '/BaseController.php';


class SettingsLink extends BaseController {
	public function register() {
		add_filter( "plugin_action_links_$this->plugin", array( $this,'settings_link' ) );
	}

	public function settings_link( $links )  {
		$settings_link = '<a href="admin.php?page=dt-apps-scrapper.php">Settings</a>';
		array_push( $links, $settings_link);
		return $links;
	}

    public function change_color(){
        global $wpdb;
//        $app_id = intval($_GET['id']);
        $table_settings=$this->table_settings;;
        $table_color= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'table_color'");
        $dw_button= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'dw_button'");
        $hover_line= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'hover_line'");
        $content_place= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'content_place'");

        if ($table_color){
            $wpdb->update($table_settings, array(
                'key' =>'table_color',
                'value' => $_GET['table']
            ),array(
                'id' => $table_color->id
            ));
        }else{
            $wpdb->insert($table_settings, array(
                'key' =>'table_color',
                'value' => $_GET['table']
            ));
        }

        if ($dw_button){
            $wpdb->update($table_settings, array(
                'key' =>'dw_button',
                'value' => $_GET['dw_button']
            ),array(
                'id' => $dw_button->id
            ));
        }else{
            $wpdb->insert($table_settings, array(
                'key' =>'dw_button',
                'value' => $_GET['dw_button']
            ));
        }


        if ($hover_line){
            $wpdb->update($table_settings, array(
                'key' =>'hover_line',
                'value' => $_GET['hover_line']
            ),array(
                'id' => $hover_line->id
            ));
        }else{
            $wpdb->insert($table_settings, array(
                'key' =>'hover_line',
                'value' => $_GET['hover_line']
            ));
        }
        if ($content_place){
            $content_array= json_encode($_GET['content_place']);
            $wpdb->update($table_settings, array(
                'key' =>'content_place',
                'value' => $content_array
            ),array(
                'id' => $content_place->id
            ));
        }else{
            $content_array= json_encode($_GET['content_place']);

            $wpdb->insert($table_settings, array(
                'key' =>'content_place',
                'value' => $content_array
            ));
        }
        $data = array(
            "success" => true,
            "status" => 200,
            "msg" => "The Color Changed"
        );
        wp_send_json($data);
    }
}
?>