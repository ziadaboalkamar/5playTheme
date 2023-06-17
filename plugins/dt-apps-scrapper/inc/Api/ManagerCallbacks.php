<?php 
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Callbacks;
require_once  WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/BaseController.php';

use Inc\Base\BaseController;

class ManagerCallbacks extends BaseController {
	public function checkboxSanitize( $input ) {
		return ( isset($input) ? true : false );
	}

	public function adminSectionManager() {
		echo 'Manage the Sections and Features of this Plugin by activating the checkboxes from the following list.';
	}

	public function checkboxField($args) {
		$name = $args['label_for'];
		$classes = $args['class'];
		$checkbox = get_option( $name );
		echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $name . '" value="1" class="" ' . ($checkbox ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
	}
}