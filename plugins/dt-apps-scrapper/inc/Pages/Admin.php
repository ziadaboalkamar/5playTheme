<?php 
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Pages;

require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/BaseController.php';
require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Callbacks/AdminCallbacks.php';
require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Api/settingsApi.php';

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Callbacks\AdminCallbacks;

class Admin extends BaseController {

	public $settings;
	public $callbacks;
	public $pages = array();
	public $subpages = array();

	public function register() {

		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->setPages();
		$this->setSubpages();
		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();
	}

	public function setPages() {
		$this->pages = array(
			array(
				'page_title' => 'DT Apps Scrapper ', 
				'menu_title' => 'Scrapper', 
				'capability' => 'manage_options', 
				'menu_slug' => 'dt-apps-Scrapper', 
				'callback' => array( $this->callbacks,'Dashboard' ), 
				'icon_url' => 'dashicons-store', 
				'position' => 110
			)
		);
	}

	public function setSubpages() {
		$this->subpages = array(
			array(
				'parent_slug' => 'dt-apps-Scrapper', 
				'page_title' => 'Custom Post Types', 
				'menu_title' => 'Projects', 
				'capability' => 'manage_options', 
				'menu_slug' => 'scrapper_projects', 
				'callback' => array( $this->callbacks, 'ProjectScrapper' )
			),
			array(
				'parent_slug' => 'dt-apps-Scrapper', 
				'page_title' => 'Custom Widgets', 
				'menu_title' => 'Applications', 
				'capability' => 'manage_options', 
				'menu_slug' => 'scrapper_apps', 
				'callback' => array( $this->callbacks,'AppScrapper' )
			)
			,
			array(
				'parent_slug' => 'dt-apps-Scrapper', 
				'page_title' => 'Custom Widgets', 
				'menu_title' => 'Website', 
				'capability' => 'manage_options', 
				'menu_slug' => 'scrapper_website', 
				'callback' => array( $this->callbacks,'WebsiteScrapper' )
			),
			array(
				'parent_slug' => 'dt-apps-Scrapper', 
				'page_title' => 'Custom Widgets', 
				'menu_title' => 'Categories', 
				'capability' => 'manage_options', 
				'menu_slug' => 'scrapper_Category', 
				'callback' => array( $this->callbacks,'CategoryScrapper' )
			),
			array(
				'parent_slug' => 'dt-apps-Scrapper', 
				'page_title' => 'Custom Widgets', 
				'menu_title' => 'RelatedPost', 
				'capability' => 'manage_options', 
				'menu_slug' => 'related_post', 
				'callback' => array( $this->callbacks,'RelatedPost')
			),	array(
				'parent_slug' => 'dt-apps-Scrapper', 
				'page_title' => 'Custom Widgets', 
				'menu_title' => 'KeySelected', 
				'capability' => 'manage_options', 
				'menu_slug' => 'KeySelected', 
				'callback' => array( $this->callbacks,'KeySelected'),
				'args' => array(
					'class' => 'example-class'
				)
			),
			array(
				'parent_slug' => 'dt-apps-Scrapper', 
				'page_title' => 'Custom Widgets', 
				'menu_title' => 'settings', 
				'capability' => 'manage_options', 
				'menu_slug' => 'settings', 
				'callback' => array( $this->callbacks,'Settings' )
			)
		);
	}

	public function setSettings() {
		$args = array(
			array(
				'option_group' => 'scrapper_options_group',
				'option_name' => 'project_name',
				'callback' => array( $this->callbacks,'ScrapperOptionsGroup')
			),
			array(
				'option_group' => 'Scrapper_options_group',
				'option_name' => 'tag'
			)
		);

		$this->settings->setSettings($args);
	}

	public function setSections() {
		$args = array(
			array(
				'id' => 'scrapper_admin_index',
				'title' => 'Settings',
				'callback' => array( $this->callbacks,'ScrapperAdminSection'),
				'page' => 'dt-apps-Scrapper'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields() {
		$args = array(
			array(
				'id' => 'project_name',
				'title' => 'Project Name',
				'callback' => array( $this->callbacks, 'scrapperProjectName'),
				'page' => 'dt-apps-Scrapper',
				'section' => 'scrapper_admin_index',
				'args' => array(
					'label_for' => 'Project Name',
					'class' => 'example-class'
				)
			),
			array(
				'id' => 'tag',
				'title' => ' Tag',
				'callback' => array( $this->callbacks, 'scrapperTag' ),
				'page' => 'dt-apps-Scrapper',
				'section' => 'scrapper_admin_index',
				'args' => array(
					'label_for' =>'Tag',
					'class' =>'example-class'
				)
			)
		);

		$this->settings->setFields($args);
	}
}
?>