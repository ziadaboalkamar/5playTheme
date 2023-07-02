<?php

defined('ABSPATH') or die('No script kiddies please');

/*
  Plugin Name: Posts Like Dislike
  Description: A simple plugin to add like dislike for your WordPress Posts
  Version:     1.1.0
  Author:      WP Happy Coders
  Author URI:  http://wphappycoders.com
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
  Text Domain: posts-like-dislike
 */


if (!class_exists('Posts_like_dislike')) {
    class Posts_like_dislike {
        public function __construct() {
            $this->define_constants();
            $this->includes();
        }

        /**
         * Include all the necessary files
         *
         * @since 1.0.0
         */
        public function includes() {
            require_once PLD_PATH . '/inc/classes/pld-library.php';
            require_once PLD_PATH . '/inc/classes/pld-activation.php';
            require_once PLD_PATH . 'inc/classes/pld-init.php';
            require_once PLD_PATH . 'inc/classes/pld-admin.php';
            require_once PLD_PATH . 'inc/classes/pld-enqueue.php';
            require_once PLD_PATH . 'inc/classes/pld-hook.php';
            require_once PLD_PATH . 'inc/classes/pld-ajax.php';
        }

        /**
         * Define necessary constants
         *
         * @since 1.0.0
         */
        public function define_constants() {
            defined('PLD_TD') or define('PLD_TD', 'posts-like-dislike');
            defined('PLD_VERSION') or define('PLD_VERSION', '1.1.0');
            defined('PLD_PATH') or define('PLD_PATH', EX_THEMES_DIR.'/'.PLD_TD.'/');
            defined('PLD_IMG_DIR') or define('PLD_IMG_DIR', EX_THEMES_URI.'/'.PLD_TD.'/images');
            defined('PLD_CSS_DIR') or define('PLD_CSS_DIR', EX_THEMES_URI.'/'.PLD_TD.'/css');
            defined('PLD_JS_DIR') or define('PLD_JS_DIR', EX_THEMES_URI.'/'.PLD_TD.'/js');
            defined('PLD_BASENAME') or define('PLD_BASENAME', PLD_TD);
        }
    }

    $pld_object = new Posts_like_dislike();
}
