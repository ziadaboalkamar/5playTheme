<?php
/**
 * @package  DtAppsScrapper
 */

namespace Inc;
require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Pages/Admin.php';
require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/Enqueue.php';
require_once WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/SettingsLink.php';

class Init {

    public static function get_services() {
        return[
             Pages\Admin::class,
             Base\Enqueue::class,
             Base\SettingsLink::class,
            //  Base\AuthController::class,
             
            

        ];
    }
    public static function register_services() {
        foreach(self::get_services() as $class ){
            $service=self:: instantiate($class ); 
            if (method_exists($service,'register')){
                $service->register();
            }
        }
    }
    /**
     * 
     * Initialize the class 
     * @param class $class     class from the services 
     * @return class instanse    new instance of the class
     */
    private static function  instantiate($class){
        //return new $class(); or
        $service = new $class();
        return $service;
    }
 }