<?php 
/**
 * @package  DTAppsScrapper
 */

namespace Inc\Callbacks;
require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/Apps.php';
require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/Project.php';
require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/BaseController.php';
use Inc\Base\Apps;
use Inc\Base\BaseController;
use Inc\Base\Project;

// use Inc\Scrapper;

class AdminCallbacks extends BaseController {
    private $url='';
	public function Dashboard() {
            if (isset($_GET["action"]) && $_GET["action"] == "view_apps" && isset($_GET["date"])){
                return require_once( "$this->plugin_path/templates/AppsUpdated.php" );

            }
        if (isset($_GET["action"]) && $_GET["action"] == "view_history" && isset($_GET["id"])){
            return require_once( "$this->plugin_path/templates/AppsHistory.php" );

        }
		return require_once( "$this->plugin_path/templates/Dashboard.php" );

	}
	public function ProjectScrapper() {
		return require_once( "$this->plugin_path/templates/ProjectScrapper.php" );
	}

	public function AppScrapper() {
		return require_once( "$this->plugin_path/templates/AppScrapper.php" );
	}
	public function CategoryScrapper() {
		return require_once( "$this->plugin_path/templates/CategoryScrapper.php" );

	}
	public function WebsiteScrapper() {
		return require_once( "$this->plugin_path/templates/WebsiteScrapper.php" );
	}
	public function RelatedPost() {
		return require_once( "$this->plugin_path/templates/RelatedPost.php" );
	}
	public function KeySelected() {
		return require_once( "$this->plugin_path/templates/KeySelected.php" );
	}
	public function Settings() {
		return require_once( "$this->plugin_path/templates/Settings.php" );
	}
	public function scrapperOptionsGroup( $input ) {
        Apps::EmptyTables();
        global $wpdb;
        $table_settings = $this->table_settings;
        ;
        $output = get_option('Project Name');
		  // Get the values submitted from the form
		  $row_api_email = isset($_POST['project']) ? sanitize_text_field($_POST['project']) : '';
		  $row_api_password = isset($_POST['use_tag']) ? sanitize_text_field($_POST['use_tag']) : '';
         $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
         $use_tag_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'use_tag'");

        // Update or insert the 'project' setting as necessary
        if (!$project_setting) {
            // Insert a new row
            $wpdb->insert($table_settings, array('value' => $row_api_email,'key' => 'project'), array('%s'));
        } else {
            // Update the existing row
            $wpdb->update($table_settings, array(
                'value' => $_POST['project'],
            ), array(
                'id' => $project_setting->id,
            ));
        }
        // Update or insert the 'use_tag' setting as necessary
        if (!$use_tag_setting) {
            // Insert a new row
            $wpdb->insert($table_settings, array('value' => $row_api_password,'key' => 'use_tag'), array('%s'));

        } else {
            // Update the existing row
            $wpdb->update($table_settings, array(
                'value' => $_POST['use_tag'],
            ), array(
                'id' => $use_tag_setting->id,
            ));

        }
        Project::storePUN();
        Apps::store_data();
		   // Send a response back to the form
        session_start();
        $_SESSION['success_msg'] = "Project Save Successfully Connect Apps with Post to end";
        header("Location: admin.php?page=scrapper_apps&process=true");
        exit();
	}

	public function scrapperAdminSection() {
		echo 'Check this beautiful section!';
	}

	public function scrapperProjectName() {
        try {
            global $wpdb;
            $table_settings = $this->table_settings;
            $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");
            $project_name_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
            $token =  $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_token'");
            $this->url = $project_setting->value;

            $client = new \GuzzleHttp\Client();
            $response = $client->get("{$this->url}/api/project",[
                'headers' => [
                    'Authorization' => 'Bearer ' . $token->value,
                    'Accept'        => 'application/json',
                ],
            ]);
            $data = json_decode($response->getBody());
            $select = '';
            echo '<select id="project" name="project">';
            echo '<option disabled>Select Project</option>';
            foreach ($data->projects as $project) {
                if ($project_name_setting){
                    if ($project_name_setting->value == $project->id ){
                        $select = "selected";
                    }else{
                        $select = "";
                    }
                }
                echo '<option '.$select.' value="' .$project->id . '">' . esc_html($project->name) . '</option>';
            }
            echo '</select>';
        }catch(\Exception $exception){
            $_SESSION['error_msg'] = $exception->getMessage();
        }

	}

	public function scrapperTag() {
        global $wpdb;
        $table_settings = $this->table_settings;
        $use_tag_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'use_tag'");
        $checked_to_one = '';
        $checked_to_zero = '';
        if ($use_tag_setting){
            if ($use_tag_setting->value == 1 ){
                $checked_to_one = "checked";
            }
            if ($use_tag_setting->value == 0 ){
                $checked_to_zero = "checked";
            }
        }
        echo '<br>
			<input type="radio" id="use_tag_0" name="use_tag"  required value="0" '.$checked_to_zero.'>
			<label for="use_tag_0"> Don\'t use Tag</label><br>
			<input type="radio" id="use_tag_1" name="use_tag"  value="1" '.$checked_to_one.'>
			<label for="use_tag_1"> Use Tag</label><br>
			<br><br>';
	}
}