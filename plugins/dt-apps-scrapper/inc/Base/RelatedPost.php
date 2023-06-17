<?php 
/**
 * @package  dt-apps-scrapper
 */
namespace Inc\Base;

require_once plugin_dir_path(__FILE__) . '/BaseController.php';

class RelatedPost extends BaseController {

	

	public function __construct() {

    }
   
    public function getData() {
        global $wpdb;
        $app_id = intval($_GET['app_id']);

        $table_app = $this->table_app_info;
        $table_app_post = $this->table_app_post;
        $table_post = $wpdb->prefix .'posts';

        header("Content-Type: application/json");

        $request = $_GET;

        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'name', 'dt' => 1 ),
            array( 'db' => 'status', 'dt' => 3),
        );

        global $wpdb;
        $sql = "  SELECT p.ID, p.post_title, pm.post_id, pm.app_id,pm.id
        FROM $table_post p
        INNER JOIN $table_app_post pm ON p.ID = pm.post_id
        WHERE  pm.app_id = $app_id";
        $results = $wpdb->get_results($sql);
        $rowcount = $wpdb->num_rows;
        $options = '';
        $data = array();
        if ($rowcount > 0) {
            foreach ($results as $row) {
                $lang_select="Don't Have Translation";
                $active_plugins = get_option( 'active_plugins' );

                if (in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
                    $table_post_mapping_lang = $wpdb->prefix."icl_translations";
                    $table_post_flag_lang = $wpdb->prefix."wp_icl_flags";
                    $language_sql = "SELECT * FROM {$table_post_mapping_lang} WHERE trid = {$row->ID}";
                    $lang_results = $wpdb->get_results($language_sql);
                    if ($lang_results && count($lang_results) > 0){
                        $lang_select = "<select class='country' name='wcpbc-manual-country' id='country'>";
                        foreach ($lang_results as $lang){
                            $languages = apply_filters( 'wpml_active_languages', NULL, array( 'skip_missing' => 0 ) );
                            if(!empty($languages)){
                                $flag= $languages[$lang->language_code]["country_flag_url"];
                            }else{
                                $flag="";
                            }
                            $lang_select.="<option data-iconurl='".$flag."'> $lang->language_code</option>";
                        }
                        $lang_select .= "</select>";
                    }else{
                        $lang_select= "Don't Have Translation";
                    }
                }
                $nestedData = array();
                $nestedData['id'] = $row->ID;
                $nestedData['lang_code'] = $lang_select;

                $nestedData['post_title'] = '<a href="'.get_permalink($row->ID).'"  target="blank">'.$row->post_title.'</a>';
                $nestedData['action'] = '<button type="button" onclick="delete_post('.$row->id.')" class="btn btn-danger" title="Edit">Disjoin</button>';
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw" => 10,
                "iTotalRecords" => count($results),
                "iTotalDisplayRecords" =>  10,
                "aaData" => $data
            );
            echo json_encode($json_data);
            die();
        }}

//        global $wpdb;
//        $sql = "  SELECT p.ID, p.post_title, pm.post_id, pm.app_id,pm.id
//        FROM $table_post p
//        INNER JOIN $table_app_post pm ON p.ID = pm.post_id
//        WHERE  pm.app_id = $app_id";
//        $results = $wpdb->get_results($sql);
//        $rowcount = $wpdb->num_rows;
//        $options = '';
//        $data = array();
//        if ($rowcount > 0) {
//            foreach ($results as $row) {
//                $nestedData = array();
//                $nestedData['id'] = $row->ID;
//                $nestedData['post_title'] = '<a href="'.get_permalink($row->ID).'"  target="blank">'.$row->post_title.'</a>';
//
//                $nestedData['action'] = '<button type="button" onclick="delete_post('.$row->id.')" class="btn btn-primary" title="Edit">disJoin</button>';
//                $data[] = $nestedData;
//            }
//
//            $json_data = array(
//                "draw" => 10,
//                "iTotalRecords" => count($results),
//                "iTotalDisplayRecords" =>  10,
//                "aaData" => $data
//            );
//            echo json_encode($json_data);
//            die();
//    }
//    }

    public function delete_post(){
        global $wpdb;
        $app_id = intval($_GET['id']);
        $table_app_post = $this->table_app_post;;

        $app_data_post = $wpdb->get_row("SELECT * FROM $table_app_post WHERE id = '$app_id'");
        if ($app_data_post){
            $wpdb->delete(
                $table_app_post,
                array( 'id' => $app_id ),
                array( '%d' )
            );
            $data = array(
                "success" => false,
                "status" => 200,
                "msg" => "The Post Deleted Successfully"
            );
        }else{
            $data = array(
                "success" => false,
                "status" => 200,
                "msg" => "they don't have a post like this"
            );
        }
        wp_send_json($data);

    }

}