<?php 
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Base;

class BaseController {
	public $plugin_path;
	public $plugin_url;
	public $plugin;
	public $assets_image;
	public $assets_js;

    public const table_prefix = "wp_dt_scrapper_";
    #1 Tables Name
    public $table_settings =  self::table_prefix .'settings';
    public $table_history =self::table_prefix .'history';
    public $table_app_info = self::table_prefix .'table_app_info';
    public $table_dt_meta = self::table_prefix . 'meta_app';
    public $table_app_post = self::table_prefix . 'meta_app_post';
    public $table_name_log = self::table_prefix .'plugin_log';
    public $table_category = self::table_prefix .'category';
    public $table_website = self::table_prefix .'website';

    public $table_event = self::table_prefix .'event';
	public $all_pages = array('dt-apps-Scrapper','scrapper_projects','scrapper_apps','scrapper_website','scrapper_Category','related_post','settings');
	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . 'dt-apps-scrapper.php';
		$this->assets_css = $this->plugin_url.'assets/css/';
		$this->assets_js = $this->plugin_url.'assets/js/';
        $this->assets_image = $this->plugin_url.'assets/images/';

	}
}
?>