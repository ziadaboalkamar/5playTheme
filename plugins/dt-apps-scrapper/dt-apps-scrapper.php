<?php
/**
  Plugin Name: DT Apps Scrapper
  Plugin URI: https://digital-trends.info/plugins/
  Description:This plugin scrapping data from other websites.
 Version: 1.0.0
  Author: DT Team
  Author URI: https://digital-trends.info/
  license: GPLv2 or later
 *  Text Domain:       dt-apps-scrapper
 * Domain Path:       /languages
 */
@ob_start();

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );
require_once plugin_dir_path(__FILE__) . 'inc/Base/CronJob.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/Apps.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/Websites.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/Category.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/Disconnect.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/RelatedPost.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/Front.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/SettingsLink.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/BaseController.php';
require_once plugin_dir_path(__FILE__) . 'inc/Base/AuthController.php';

use Inc\Base\AuthController;
use Inc\Base\Apps;
use Inc\Base\Websites;
use Inc\Base\Category;
use Inc\Base\Disconnect;
use Inc\Base\CronJob;
use Inc\Base\RelatedPost;
use Inc\Base\Dashboard;
use Inc\Base\Front;
use Inc\Base\SettingsLink;
use Inc\Base\BaseController;
// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

function dt_activate_plugin() {
    require_once plugin_dir_path(__FILE__) . 'inc/Base/Activate.php';

    new Inc\Base\Activate();
}

function dt_deActivate_plugin() {
    require_once plugin_dir_path(__FILE__) . 'inc/Base/DeActivate.php';

    new Inc\Base\DeActivate();

}
function dt_unInstall_plugin() {
    require_once plugin_dir_path(__FILE__) . 'inc/Base/UnInstall.php';

    new Inc\Base\Uninstall();
}

register_activation_hook(__FILE__,'dt_activate_plugin');
register_deactivation_hook(__FILE__,'dt_deActivate_plugin');
register_uninstall_hook(__FILE__, 'dt_unInstall_plugin');

/**
 * Initialize all the core classes of the plugin
 */
require_once plugin_dir_path(__FILE__) . 'inc/init.php';

if ( class_exists( 'Inc\Init' ) ) {


    Inc\Init::register_services();
}
try {
    global $wpdb;
    $base = new BaseController();
    $table_settings = $base->table_settings;
    $row_api_connected = $wpdb->get_row("SELECT * FROM $table_settings WHERE `key`= 'api_connected'");
    if (isset($_GET['page']) && in_array($_GET["page"],$base->all_pages) ){

    if($row_api_connected != null ) {
        if($row_api_connected->value == 0  && isset($_GET['page']) && $_GET['page'] != 'settings') {
            $_SESSION['error_msg'] = "You must Connect to Api first.";
            header("Location: admin.php?page=settings");
        }
    }
    }
    if(isset($_POST['SettingSubmit'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $url = $_POST['url'];
        $Auth = new AuthController($email, $password, $url);
        $Auth->validInput();
        $Auth->connectApi();
        $Auth->storeIntoDB();
        $Auth->print_Error();
        $Auth->redirect();


    }
    if(isset($_POST['SubmitDisabled'])) {
        $disabled = $_POST['api_connected'];
        $disconnect = new Disconnect($disabled);
    }
}catch (Exception $exception){
    $_SESSION['error_msg'] = $exception->getMessage();
}

add_action('wp_ajax_websites_datatables', 'websites_datatables_server_side_callback');
add_action('wp_ajax_nopriv_websites_datatables', 'websites_datatables_server_side_callback');
function websites_datatables_server_side_callback()
 {
    $Websites = new Websites();
    $Websites_data = $Websites->getData();
}


add_action('wp_ajax_related_post_datatables', 'related_post_datatables_server_side_callback');
add_action('wp_ajax_nopriv_related_post_datatables', 'related_post_datatables_server_side_callback');
function related_post_datatables_server_side_callback()
{
    $post = new RelatedPost();
    $post_data = $post->getData();
}



add_action('wp_ajax_delete_related_post_datatables', 'delete_related_post_datatables_server_side_callback');
add_action('wp_ajax_nopriv_delete_related_post_datatables', 'delete_related_post_datatables_server_side_callback');
function delete_related_post_datatables_server_side_callback()
{
    $post = new RelatedPost();
    $post_data = $post->delete_post();
}

add_action('wp_ajax_apps_datatables', 'apps_datatables_server_side_callback');
add_action('wp_ajax_nopriv_apps_datatables', 'apps_datatables_server_side_callback');
function apps_datatables_server_side_callback()
 {
    $Apps = new Apps();
    $Apps = $Apps->getData();
}

add_action('wp_ajax_post_app', 'post_app_server_side_callback');
add_action('wp_ajax_nopriv_post_app', 'post_app_server_side_callback');
function post_app_server_side_callback()
{
    $Apps = new Apps();
    $Apps = $Apps->connect_with_post();
}
add_action('wp_ajax_chart_js', 'chart_js_server_side_callback');
add_action('wp_ajax_nopriv_chart_js', 'chart_js_server_side_callback');
function chart_js_server_side_callback()
{
    $dashboard = new Dashboard();
    $dashboard = $dashboard->chartjs();
}
add_action('wp_ajax_categories_datatables', 'categories_datatables_server_side_callback');
add_action('wp_ajax_nopriv_categories_datatables', 'categories_datatables_server_side_callback');
function categories_datatables_server_side_callback()
{
    $Category = new Category();
    $Category = $Category->getData();
}

add_action('wp_ajax_process_app', 'process_app_server_side_callback');
add_action('wp_ajax_nopriv_process_app', 'process_app_server_side_callback');
function process_app_server_side_callback()
{
    $Apps = new Apps();
    $Apps = $Apps->store_process();
}

 //See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
add_filter( 'cron_schedules', 'isa_add_every_minutes' );
function isa_add_every_minutes( $schedules ) {
    $schedules['my_interval'] = array(
        'interval' => 60,
        'display' => __( 'Every Minute' ),
    );
    return $schedules;
}

if ( ! wp_next_scheduled( 'my_cron_hook' ) ) {
    wp_schedule_event( time(), 'my_interval', 'my_cron_hook' );
}
// Hook into that action that'll fire every five minutes
add_action( 'isa_add_every_minutes', 'every_minutes_event_func' );
add_action( 'my_cron_hook', 'every_minutes_event_func' );

function every_minutes_event_func() {
    $cronjob = new CronJob();
    $cronjob->my_cron_job();
}

add_action('wp_ajax_key_selected', 'key_selected_server_side_callback');
add_action('wp_ajax_nopriv_key_selected', 'key_selected_server_side_callback');
function key_selected_server_side_callback()
{
    $Apps = new Apps();
    $Apps = $Apps->change_status_of_key();
}
add_action('wp_ajax_change_color', 'change_color_server_side_callback');
add_action('wp_ajax_nopriv_change_color', 'change_color_server_side_callback');
function change_color_server_side_callback()
{
    $settings = new SettingsLink();
    $settings = $settings->change_color();
}
add_action('wp_ajax_disable_app', 'disable_app_server_side_callback');
add_action('wp_ajax_nopriv_disable_app', 'disable_app_server_side_callback');
function disable_app_server_side_callback()
{
    $Apps = new Apps();
    $Apps = $Apps->disable_app();
}
add_action('wp_ajax_bulk_disable_app', 'bulk_disable_app_server_side_callback');
add_action('wp_ajax_nopriv_bulk_disable_app', 'bulk_disable_app_server_side_callback');
function bulk_disable_app_server_side_callback()
{
    $Apps = new Apps();
    $Apps = $Apps->bulk_action();
}

function add_custom_metabox() {
    add_meta_box( 'custom_metabox', 'DT Custom Data', 'display_custom_metabox', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'add_custom_metabox' );

function display_custom_metabox( $post ) {
    global $wpdb;
    $base= new BaseController();
    $table_settings = $base->table_settings;
    $table_app_info = $base->table_app_info;
    $table_name_new = $base->table_history;
    $table_meta_app =  $base->table_dt_meta;
    $table_meta_app_post =  $base->table_app_post;
    $post_id = $post->ID;
    wp_nonce_field( 'custom_metabox', 'custom_metabox_nonce' );
    $inputs= '';
    $posts= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
    if ($posts && count($posts) > 0){

        foreach ($posts as $post_reference){
            $app_data= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_app_info} WHERE id = %d", $post_reference->app_id ) );
            if ($app_data->status == "disabled"){
                $disabled = "disabled";
            }else{
                $disabled = "";
            }

            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_meta_app} WHERE status = 1 AND app_id = %d", $post_reference->app_id ) );
            ?>
            <?php if (isset($results) && count($results)>0){ foreach ( $results as $result ) : ?>
            <?php $inputs .='
                <div class="mb-3">
                    <label for="custom_field_'.esc_attr( $result->key ).'" class="form-label">'.esc_html( $result->key ).':</label>
                    <input type="text" class="form-control" id="custom_field_'.esc_attr( $result->key ).'" name="custom_field_'.esc_attr( $result->key ).'" '.$disabled .'  value="'.esc_html($result->value).'">
                </div>
       '; ?>
            <?php endforeach;}
        }
        echo $inputs;

}}

function save_custom_metabox( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( !isset( $_POST['custom_metabox_nonce'] ) || !wp_verify_nonce( $_POST['custom_metabox_nonce'], 'custom_metabox' ) ) {
        return;
    }
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    global $wpdb;
    $post_id = absint( $post_id );
    $base = new BaseController();
    $table_meta_app_post = $base->table_app_post;
    $table_meta_app =  $base->table_dt_meta;

    $post_reference= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );

    if ($post_reference) {
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_meta_app} WHERE status = 1 AND app_id = %d", $post_reference->app_id));
        foreach ($results as $result) {
            $meta_key = 'custom_field_' . $result->key;
            if (isset($_POST[$meta_key])) {
                $meta_value = sanitize_text_field($_POST[$meta_key]);
                $wpdb->update($table_meta_app, array(
                    'value' =>$meta_value,
                ), array(
                    'key' => $result->key, 'app_id' => $post_reference->app_id
                ));
            }
        }
    }
}
add_action( 'save_post', 'save_custom_metabox' );



function my_template_array(){
    $temp = array();
    $temp["download-page-template.php"] = "Download Page";
    return $temp;
}
function download_template_register($page_templates,$theme,$post){
    $templates = my_template_array();
    foreach ($templates as $temp => $value){
        $page_templates[$temp] = $value;
    }
    return $page_templates;
}
add_filter( 'theme_page_template', 'download_template_register', 10,3 );
function my_template_selected($template){
    global  $post,$wp_query,$wpdb;

    if (is_page('download-templates') || (isset($_GET["page"]) && $_GET["page"]="download")){
        $template = plugin_dir_path(__FILE__).'templates/download-page-template.php';
    }
    return $template;
}
add_filter( 'template_include', 'my_template_selected', 99);

function wp_first_paragraph_excerpt( $id=null ) {
    // Set $id to the current post by default
    if( !$id ) {
        global $post;
        $id = get_the_id();
    }

    global $wpdb;
    // Get the post content
    $active_plugins = get_option( 'active_plugins' );

    if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
        // Get the ID of the child post in the current language
        $child_post_id = apply_filters( 'wpml_object_id', $id, 'post', true );

// Get the content of the child post
        $content = get_post_field( 'post_content', $child_post_id );

    }else{
        $content = get_post_field( 'post_content', $id );

    }

    // Remove all tags, except paragraphs
    $excerpt = strip_tags( $content, '<p></p>' );
    // Remove empty paragraph tags
    $excerpt = force_balance_tags( $excerpt );

    $excerpt = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $excerpt );
    $excerpt = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $excerpt );


    // Get the first paragraph
    $excerpt = substr( $excerpt, 0, strpos( $excerpt, '</p>' ) + 500 );

    // Remove remaining paragraph tags
    $excerpt = strip_tags( $excerpt );
    $link =  get_permalink($id);


    echo "<p>".$excerpt."<span class = 'read_paragraph' style='display: contents'>... <a target='_blank' class='read-more' href='".$link."'>  ".esc_html__('Read More...','dt-apps-scrapper')." </a></span></p>";
}
function get_key($app_id , $key){
    global $wpdb;
    $base= new BaseController();
    $table_dt_meta = $base->table_dt_meta;
    $table_meta_app_post = $base->table_app_post;
    $table_settings =$base->table_settings;

    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE status = 1 AND `key` = '{$key}' AND app_id = %d", $app_id ) );

    if ($results){
    $value = $results->value;
    }else{
        $value = "";
    }
    return $value;

}

function color_saved($key){
    global $wpdb;
    $base= new BaseController();
    $table_dt_meta = $base->table_dt_meta;;
    $table_meta_app_post = $base->table_app_post;;
    $table_settings = $base->table_settings;;
    if ($key == "table"){
        $table_color= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'table_color'");
        if ($table_color){
            echo $table_color->value;

        }else{
            echo "#2F0F5D";
        }

    }elseif ($key == "hover_line"){
        $hover_line= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'hover_line'");
        if ($hover_line){
            echo $hover_line->value;

        }else{
            echo "#2F0F5D";
        }

    }elseif ($key == "dw_button"){
        $dw_button= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'dw_button'");
        if ($dw_button){
            echo $dw_button->value;

        }else{
            echo "#0EA293";

        }

    }
}
function get_content($post_id){
    global $wpdb;
    $base = new BaseController();
    $table_dt_meta = $base->table_dt_meta;
    $table_meta_app_post = $base->table_app_post;
    $table_settings = $wpdb->prefix.'posts';
    // Get the post content
    $content = get_post_field( 'post_content', $post_id );

    // Remove all tags, except paragraphs
    $excerpt = strip_tags( $content, '<p></p>' );
    // Remove empty paragraph tags
    $excerpt = force_balance_tags( $excerpt );


    $excerpt = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $excerpt );
    $excerpt = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $excerpt );


    // Get the first paragraph
    $excerpt = substr( $excerpt, 0, strpos( $excerpt, '</p>' ) + 4 );

    // Remove remaining paragraph tags
    $excerpt = strip_tags( $excerpt );
    $link =  get_permalink($post_id);

    echo "<p>".$excerpt."</p><span class = 'read_paragraph'>... <a class='read-more' href='<?php echo $link;?>'> ".esc_html__('Read More...','dt-apps-scrapper')." </a></span>";
}

function my_plugin_log( $message ) {
    $log_file = WP_CONTENT_DIR . '/my-plugin-log.txt';
    $timestamp = date( 'Y-m-d H:i:s' );
    error_log( "[{$timestamp}] {$message}\n", 3, $log_file );
}

function get_key_changes_by_time( $key ) {
    global $wpdb;
    $base= new BaseController();
    $table_history = $base->table_history;
    $revisions = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$table_history} WHERE`key` = %s ORDER BY created_at DESC",
        $key
    ) );
    $changes = '';
    foreach ( $revisions as $index => $revision ) {
        $revision_date = strtotime( $revision->created_at );
        $revision_diff = '';

        if ( time() - $revision_date <= 60 ) {
            if ($index == 0 ) {
                $revision_diff = $revision->value;
            } else {
                $previous_revision = $revisions[ $index - 1 ];
                $revision_diff = wp_text_diff( $previous_revision->value, $revision->value );
            }
            $changes .= '<h4>Changes made at ' . date( 'h:i:s A', $revision_date) . ':</h4>';
            $changes .= '<p>' . $revision_diff . '</p>';
        }
    }

    return $changes;
}

// Load the plugin text domain
add_action( 'plugins_loaded', 'dt_apps_scrapper_load_textdomain' );
function dt_apps_scrapper_load_textdomain() {
    load_plugin_textdomain( 'dt-apps-scrapper', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
function add_logo_feature_image($logo_url="", $app_id=0){
    if ((isset($logo_url) && isset($app_id)) && ($logo_url != "" && $app_id !=0)) {
        include(ABSPATH . '/wp-load.php');
        global $wpdb;
        $base = new BaseController();
        $table_meta_app_post = $base->table_app_post;
        $table_app_info = $base->table_app_info;

        $app_data_post = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_meta_app_post} WHERE app_id = %d", $app_id));
        $app_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_app_info} WHERE id = %d", $app_id));

        if ($app_data_post && $app_data) {
            $app_name = $app_data->name;

            foreach ($app_data_post as $post) {
                $current_thumbnail_id = get_post_thumbnail_id($post->id);

                // If the post already has a thumbnail, delete it
                if ($current_thumbnail_id) {
                    wp_delete_attachment($current_thumbnail_id, true);
                }

                $imagetypeexplode = explode('/', getimagesize($logo_url)['mime']);
                $imagetype = end($imagetypeexplode);
                $filename = $app_name . '_logo.' . $imagetype;

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
                $post_id = (string)$post->id;
                wp_update_attachment_metadata( $attach_id, $attach_data );
                set_post_thumbnail( $post->id, $attach_id );
            }
        }

        return false; // Unable to find the app data or app data post
    }
}

add_action('init','add_logo_feature_image');
//// add content before the content
//function dt_scrapper_application_info_before_content($content) {
//    global $wpdb;
//    $base = new BaseController();
//    $table_settings =$base->table_settings;
//    $content_replace= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'content_place'");
//    $content_replace_array = json_decode($content_replace->value);
//    if( is_single() && $content_replace_array != null &&  in_array("in_top",$content_replace_array)) {
//
//        $active_plugins = get_option( 'active_plugins' );
//        $table_meta_app_post = $base->table_app_post;
//        $table_meta_app = $base->table_dt_meta;
//        $post_id = get_the_ID();
//        $meta_app_count = 0;
//        $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_id} ");
//        if (isset($have_post) && count($have_post)>0){
//            foreach ($have_post as $post){
//                $app_id = $post->app_id;
//                $have_meta_app = $wpdb->get_results("SELECT * FROM {$table_meta_app} WHERE app_id = {$app_id}");
//                if (isset($have_meta_app) && count($have_meta_app) > 0){
//                   $meta_app_count = 1 ;
//                }
//            }
//
//        }
//        if ($meta_app_count != 0){
//            $current_language = '';
//            if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
//                // WPML plugin is active
//                // do something
//                $current_language = apply_filters('wpml_current_language', NULL);
//                $my_default_lang = apply_filters('wpml_default_language', NULL );
//                $table_post_mapping_lang = $wpdb->prefix."icl_translations";
//                $post_result_defult = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE trid = {$post_id} AND language_code = '$current_language'");
//                $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//
//                if ($post_result_defult){
//                   $Front = new Front();
//                   $Front= $Front->application_info($post_id);
//                    $content = $Front.$content;
//                   return $content;
//               }elseif ($post_result){
//                    $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//                    $post_lang_id = $post_result->trid;
//                    $meta_app_count_child = 0;
//                    $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_lang_id} ");
//
//                    $query = $wpdb->prepare(
//                        "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
//                        $post_lang_id
//                    );
//                    $results = $wpdb->get_results($query, ARRAY_A);
//                    $element_ids = wp_list_pluck($results, 'element_id');
//                    $element_ids = array_map('intval', $element_ids);
//                    $element_ids = array_filter($element_ids);
//
//                    $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
//                    $query = $wpdb->prepare(
//                        "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
//                        $element_ids
//                    );
//                    $matching_posts = $wpdb->get_col($query);
//               if ($matching_posts && count($matching_posts) > 0){
//
//                    $Front = new Front();
//                    $Front= $Front->application_info($matching_posts[0]);
//                    $content = $Front.$content;
//                    return $content;
//
//                }else{
//                   $Front = new Front();
//                   $Front= $Front->application_info($post_lang_id);
//                   $content = $Front.$content;
//                   return $content;
//               }
//
//
//
//               }else{
//                    return $content;
//                }
//            }else{
//                $Front = new Front();
//                $Front= $Front->application_info($post_id);
//                $content = $Front.$content;
//                return $content;
//            }
//
//        }else{
//            if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
//
//                // WPML plugin is active
//                // do something
//                $current_language = apply_filters('wpml_current_language', NULL);
//                $my_default_lang = apply_filters('wpml_default_language', NULL );
//                $table_post_mapping_lang = $wpdb->prefix."icl_translations";
//                $post_result_defult = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE trid = {$post_id} AND language_code = '$current_language'");
//                $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//
//                if ($post_result_defult && $meta_app_count != 0){
//                    $Front = new Front();
//                    $Front= $Front->application_info($post_id);
//                    $content = $Front.$content;
//                    return $content;
//                }elseif ($post_result){
//                    $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//                    $post_lang_id = $post_result->trid;
//                    $meta_app_count_child = 0;
//                    $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_lang_id} ");
//
//                    $query = $wpdb->prepare(
//                        "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
//                        $post_lang_id
//                    );
//                    $results = $wpdb->get_results($query, ARRAY_A);
//                    $element_ids = wp_list_pluck($results, 'element_id');
//                    $element_ids = array_map('intval', $element_ids);
//                    $element_ids = array_filter($element_ids);
//
//                    $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
//                    $query = $wpdb->prepare(
//                        "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
//                        $element_ids
//                    );
//                    $matching_posts = $wpdb->get_col($query);
//
//                    if (isset($have_post) && count($have_post)>0){
//                        foreach ($have_post as $post){
//                            $app_id = $post->app_id;
//                            $have_meta_app = $wpdb->get_results("SELECT * FROM {$table_meta_app} WHERE app_id = {$app_id}");
//                            if (isset($have_meta_app) && count($have_meta_app) > 0){
//                                $meta_app_count_child = 1 ;
//                            }
//                        }
//
//                    }
//                    if ($meta_app_count_child != 0){
//                        $Front = new Front();
//                        $Front= $Front->application_info($post_lang_id);
//                        $content = $Front.$content;
//                        return $content;
//                    }elseif ($matching_posts && count($matching_posts) > 0){
//
//                        $Front = new Front();
//                        $Front= $Front->application_info($matching_posts[0]);
//                        $content = $Front.$content;
//                        return $content;
//
//                    }else{
//                        return $content;
//                    }
//
//
//                }else{
//                    return $content;
//
//                }
//            }else{
//                return $content;
//
//            }
//
//        }
//
//    }else{
//        return $content;
//    }
//}
//add_filter('the_content','dt_scrapper_application_info_before_content',9);
//// add content after the content end the content
//
//function dt_scrapper_application_info_after_content($content) {
//    global $wpdb;
//    $base = new BaseController();
//    $table_settings =$base->table_settings;
//    $content_replace= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'content_place'");
//    $content_replace_array = json_decode($content_replace->value);
//    if( is_single() && $content_replace_array != null &&  in_array("in_bottom",$content_replace_array)) {
//
//        $active_plugins = get_option( 'active_plugins' );
//        $table_meta_app_post = $base->table_app_post;
//        $table_meta_app = $base->table_dt_meta;
//        $post_id = get_the_ID();
//        $meta_app_count = 0;
//        $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_id} ");
//        if (isset($have_post) && count($have_post)>0){
//            foreach ($have_post as $post){
//                $app_id = $post->app_id;
//                $have_meta_app = $wpdb->get_results("SELECT * FROM {$table_meta_app} WHERE app_id = {$app_id}");
//                if (isset($have_meta_app) && count($have_meta_app) > 0){
//                    $meta_app_count = 1 ;
//                }
//            }
//
//        }
//        if ($meta_app_count != 0){
//            $current_language = '';
//            if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
//                // WPML plugin is active
//                // do something
//                $current_language = apply_filters('wpml_current_language', NULL);
//                $my_default_lang = apply_filters('wpml_default_language', NULL );
//                $table_post_mapping_lang = $wpdb->prefix."icl_translations";
//                $post_result_defult = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE trid = {$post_id} AND language_code = '$current_language'");
//                $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//
//                if ($post_result_defult){
//                    $Front = new Front();
//                    $Front= $Front->application_info($post_id);
//                    $content = $content.$Front;
//                    return $content;
//                }elseif ($post_result){
//                    $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//                    $post_lang_id = $post_result->trid;
//                    $meta_app_count_child = 0;
//                    $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_lang_id} ");
//
//                    $query = $wpdb->prepare(
//                        "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
//                        $post_lang_id
//                    );
//                    $results = $wpdb->get_results($query, ARRAY_A);
//                    $element_ids = wp_list_pluck($results, 'element_id');
//                    $element_ids = array_map('intval', $element_ids);
//                    $element_ids = array_filter($element_ids);
//
//                    $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
//                    $query = $wpdb->prepare(
//                        "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
//                        $element_ids
//                    );
//                    $matching_posts = $wpdb->get_col($query);
//                    if ($matching_posts && count($matching_posts) > 0){
//
//                        $Front = new Front();
//                        $Front= $Front->application_info($matching_posts[0]);
//                        $content = $content.$Front;
//                        return $content;
//
//                    }else{
//                        $Front = new Front();
//                        $Front= $Front->application_info($post_lang_id);
//                        $content = $content.$Front;
//                        return $content;
//                    }
//
//
//
//                }else{
//                    return $content;
//                }
//            }else{
//                $Front = new Front();
//                $Front= $Front->application_info($post_id);
//                $content = $content.$Front;
//                return $content;
//            }
//
//        }else{
//            if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
//
//                // WPML plugin is active
//                // do something
//                $current_language = apply_filters('wpml_current_language', NULL);
//                $my_default_lang = apply_filters('wpml_default_language', NULL );
//                $table_post_mapping_lang = $wpdb->prefix."icl_translations";
//                $post_result_defult = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE trid = {$post_id} AND language_code = '$current_language'");
//                $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//
//                if ($post_result_defult && $meta_app_count != 0){
//                    $Front = new Front();
//                    $Front= $Front->application_info($post_id);
//                    $content = $content.$Front;
//                    return $content;
//                }elseif ($post_result){
//                    $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//                    $post_lang_id = $post_result->trid;
//                    $meta_app_count_child = 0;
//                    $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_lang_id} ");
//
//                    $query = $wpdb->prepare(
//                        "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
//                        $post_lang_id
//                    );
//                    $results = $wpdb->get_results($query, ARRAY_A);
//                    $element_ids = wp_list_pluck($results, 'element_id');
//                    $element_ids = array_map('intval', $element_ids);
//                    $element_ids = array_filter($element_ids);
//
//                    $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
//                    $query = $wpdb->prepare(
//                        "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
//                        $element_ids
//                    );
//                    $matching_posts = $wpdb->get_col($query);
//
//                    if (isset($have_post) && count($have_post)>0){
//                        foreach ($have_post as $post){
//                            $app_id = $post->app_id;
//                            $have_meta_app = $wpdb->get_results("SELECT * FROM {$table_meta_app} WHERE app_id = {$app_id}");
//                            if (isset($have_meta_app) && count($have_meta_app) > 0){
//                                $meta_app_count_child = 1 ;
//                            }
//                        }
//
//                    }
//                    if ($meta_app_count_child != 0){
//                        $Front = new Front();
//                        $Front= $Front->application_info($post_lang_id);
//                        $content = $content.$Front;
//                        return $content;
//                    }elseif ($matching_posts && count($matching_posts) > 0){
//
//                        $Front = new Front();
//                        $Front= $Front->application_info($matching_posts[0]);
//                        $content = $content.$Front;
//                        return $content;
//
//                    }else{
//                        return $content;
//                    }
//
//
//                }else{
//                    return $content;
//
//                }
//            }else{
//                return $content;
//
//            }
//
//        }
//
//    }else{
//        return $content;
//    }
//}
//add_filter('the_content','dt_scrapper_application_info_after_content');
//
//
//// add content inside the content
//
//function dt_scrapper_application_info_inside_content($content) {
//    global $wpdb;
//    $base = new BaseController();
//    $table_settings =$base->table_settings;
//    $content_replace= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'content_place'");
//    $content_replace_array = json_decode($content_replace->value);
//    if( is_single() && $content_replace_array != null && in_array("inside_content",$content_replace_array)) {
//
//        $active_plugins = get_option( 'active_plugins' );
//        $table_meta_app_post = $base->table_app_post;
//        $table_meta_app = $base->table_dt_meta;
//        $post_id = get_the_ID();
//        $meta_app_count = 0;
//        $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_id} ");
//        if (isset($have_post) && count($have_post)>0){
//            foreach ($have_post as $post){
//                $app_id = $post->app_id;
//                $have_meta_app = $wpdb->get_results("SELECT * FROM {$table_meta_app} WHERE app_id = {$app_id}");
//                if (isset($have_meta_app) && count($have_meta_app) > 0){
//                    $meta_app_count = 1 ;
//                }
//            }
//
//        }
//        if ($meta_app_count != 0){
//            $current_language = '';
//            if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
//                // WPML plugin is active
//                // do something
//                $current_language = apply_filters('wpml_current_language', NULL);
//                $my_default_lang = apply_filters('wpml_default_language', NULL );
//                $table_post_mapping_lang = $wpdb->prefix."icl_translations";
//                $post_result_defult = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE trid = {$post_id} AND language_code = '$current_language'");
//                $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//
//                if ($post_result_defult){
//                    $Front = new Front();
//                    $Front= $Front->application_info($post_id);
//                    return prefix_insert_after_paragraph( $Front, 1, $content );
//                }elseif ($post_result){
//
//                    $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//                    $post_lang_id = $post_result->trid;
//                    $meta_app_count_child = 0;
//                    $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_lang_id} ");
//
//                    $query = $wpdb->prepare(
//                        "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
//                        $post_lang_id
//                    );
//                    $results = $wpdb->get_results($query, ARRAY_A);
//                    $element_ids = wp_list_pluck($results, 'element_id');
//                    $element_ids = array_map('intval', $element_ids);
//                    $element_ids = array_filter($element_ids);
//
//                    $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
//                    $query = $wpdb->prepare(
//                        "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
//                        $element_ids
//                    );
//                    $matching_posts = $wpdb->get_col($query);
//                    if ($matching_posts && count($matching_posts) > 0){
//
//                        $Front = new Front();
//                        $Front= $Front->application_info($matching_posts[0]);
//                        return prefix_insert_after_paragraph( $Front, 1, $content );
//
//
//                    }else{
//                        $Front = new Front();
//                        $Front= $Front->application_info($post_lang_id);
//                        return prefix_insert_after_paragraph( $Front, 1, $content );
//                    }
//
//
//                }else{
//                    return $content;
//                }
//            }else{
//                $Front = new Front();
//                $Front= $Front->application_info($post_id);
//                return prefix_insert_after_paragraph( $Front, 1, $content );
//            }
//
//        }else{
//            if ( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins )) {
//
//                // WPML plugin is active
//                // do something
//                $current_language = apply_filters('wpml_current_language', NULL);
//                $my_default_lang = apply_filters('wpml_default_language', NULL );
//                $table_post_mapping_lang = $wpdb->prefix."icl_translations";
//                $post_result_defult = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE trid = {$post_id} AND language_code = '$current_language'");
//                $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//
//                if ($post_result_defult && $meta_app_count != 0){
//                    $Front = new Front();
//                    $Front= $Front->application_info($post_id);
//                    return prefix_insert_after_paragraph( $Front, 1, $content );
//                }elseif ($post_result){
//                    $post_result = $wpdb->get_row("SELECT * FROM {$table_post_mapping_lang} WHERE element_id = {$post_id} AND language_code = '$current_language'");
//                    $post_lang_id = $post_result->trid;
//                    $meta_app_count_child = 0;
//                    $have_post = $wpdb->get_results("SELECT * FROM {$table_meta_app_post} WHERE post_id = {$post_lang_id} ");
//                    $query = $wpdb->prepare(
//                        "SELECT element_id FROM {$table_post_mapping_lang} WHERE trid = %d",
//                        $post_lang_id
//                    );
//                    $results = $wpdb->get_results($query, ARRAY_A);
//                    $element_ids = wp_list_pluck($results, 'element_id');
//                    $element_ids = array_map('intval', $element_ids);
//                    $element_ids = array_filter($element_ids);
//
//                    $placeholders = implode(', ', array_fill(0, count($element_ids), '%d'));
//                    $query = $wpdb->prepare(
//                        "SELECT post_id FROM {$table_meta_app_post} WHERE post_id IN ($placeholders)",
//                        $element_ids
//                    );
//                    $matching_posts = $wpdb->get_col($query);
//                    if (isset($have_post) && count($have_post)>0){
//                        foreach ($have_post as $post){
//                            $app_id = $post->app_id;
//                            $have_meta_app = $wpdb->get_results("SELECT * FROM {$table_meta_app} WHERE app_id = {$app_id}");
//                            if (isset($have_meta_app) && count($have_meta_app) > 0){
//                                $meta_app_count_child = 1 ;
//                            }
//                        }
//
//                    }
//                    if ($meta_app_count_child != 0){
//                        $Front = new Front();
//                        $Front= $Front->application_info($post_lang_id);
//                        return prefix_insert_after_paragraph( $Front, 1, $content );
//                    }elseif ($matching_posts && count($matching_posts) > 0){
//
//                        $Front = new Front();
//                        $Front= $Front->application_info($matching_posts[0]);
//                        $content = $content.$Front;
//                        return $content;
//
//                    }else{
//                        return $content;
//                    }
//
//
//                }else{
//
//                    return $content;
//
//                }
//            }else{
//                return $content;
//
//            }
//
//        }
//
//    }else{
//        return $content;
//    }
//}
//add_filter('the_content','dt_scrapper_application_info_inside_content');
//
//// Parent Function that makes the magic happen prefix_insert_after_paragraph
//function prefix_insert_after_paragraph( $insertion, $paragraph_id, $content ) {
//    $closing_p = '</p>';
//    $paragraphs = explode( $closing_p, $content );
//
//    foreach ($paragraphs as $index => $paragraph) {
//        if ( trim( $paragraph ) ) {
//            $paragraphs[$index] .= $closing_p;
//        }
//
//        if ( $paragraph_id == $index + 1 ) {
//            $paragraphs[$index] .= $insertion;
//        }
//    }
//    return implode( '', $paragraphs );
//}
?>