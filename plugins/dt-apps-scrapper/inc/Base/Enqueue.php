<?php
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Base;

require_once plugin_dir_path(__FILE__) . '/BaseController.php';

class Enqueue extends BaseController {

    public function register() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
//        add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue' ));
    }

    function enqueue() {
        // enqueue all our scripts
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_media();
        wp_enqueue_style( 'dtScrapperStyle', $this->assets_css . 'style.css' );
        wp_enqueue_style( 'dtHoverStyle', $this->assets_css . 'hover.css' );
//.

        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.7.0.min.js', array(), null, true);

        // wp_enqueue_style( 'authstyle', $this->assets_css .'auth.css');
        // wp_enqueue_script( 'authscript', $this->assets_js .'auth.js');

        // JS
        wp_register_script('Bootstrap-JS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js');
        wp_enqueue_script('Bootstrap-JS');

        // DataTable JS
        wp_register_script('jquery-dataTables', 'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js', array( 'jquery' ), '11.1.3', true);
        wp_enqueue_script('jquery-dataTables');
        // DataTable JS
        wp_register_script('dataTables-bootstrap5-js', 'https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js', array( 'jquery' ), '11.1.3', true);
        wp_enqueue_script('dataTables-bootstrap5-js');

        // CSS
        wp_register_style('Bootstrap-CSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css');
        wp_enqueue_style('Bootstrap-CSS');

        // Datatable CSS
        wp_register_style('twitter-bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css');
        wp_enqueue_style('twitter-bootstrap');

        wp_register_style('dataTables-bootstrap5', 'https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css');
        wp_enqueue_style('dataTables-bootstrap5');

        wp_register_script('toaster-js', 'https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js');
        wp_enqueue_script('toaster-js');

        // CSS
        wp_register_style('toaster-CSS', 'https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css');
        wp_enqueue_style('toaster-CSS');

        //wp_register_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
        //wp_enqueue_script('select2-js');
        // CSS
        wp_register_style('select2-CSS', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        wp_enqueue_style('select2-CSS');

        wp_enqueue_script( 'select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), '11.1.3', true );
        // wp_register_style('select2-CSS', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        //wp_enqueue_style('select2-CSS');
        wp_register_style('datatable-CSS', 'https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css');
        wp_enqueue_style('datatable-CSS');

        wp_register_style('datatable2-CSS', 'https://cdn.datatables.net/1.13.4/css/dataTables.semanticui.min.css');
        wp_enqueue_style('datatable2-CSS');
//
//        wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '11.1.3', 'all' );

        wp_enqueue_script('moment-js', 'https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js', array(), '2.29.1');
        wp_enqueue_script( 'dtScrapperScript', $this->assets_js . 'scripts.js', array('jquery'),'2.5' ,true );

        if (isset($_GET['page']) && $_GET['page'] == "dt-apps-Scrapper"){
            wp_enqueue_script('chart-js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js', array(), '2.8.0');
            wp_enqueue_script('chart-bundle-js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js', array(), '2.8.0');

            wp_enqueue_script( 'dtScrapperChart', $this->assets_js . 'chart.js?ver=1.0.0', array( 'chart-js' ), '1.0.0', true);
        }
        wp_enqueue_script( 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.js', array( 'jquery' ), '11.1.3', true );
        wp_enqueue_style( 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.css', array(), '11.1.3', 'all' );
        if (isset($_GET['page']) && $_GET['page'] == "scrapper_apps") {
            wp_localize_script('dtScrapperScript', 'appsData', array(
                'ajax_url' => admin_url('admin-ajax.php?action=apps_datatables'),

            ));
            wp_localize_script('dtScrapperScript', 'appsData2', array(
                'post_app_url' => admin_url('admin-ajax.php?action=post_app'),
            ));
            wp_localize_script('dtScrapperScript', 'appsData3', array(
                'process_app_url' => admin_url('admin-ajax.php?action=process_app'),
            ));
            wp_localize_script('dtScrapperScript', 'appsData4', array(
                'disable_app_url' => admin_url('admin-ajax.php?action=disable_app'),
            ));
            wp_localize_script('dtScrapperScript', 'bulk_disable_app', array(
                'bulk_disable_app' => admin_url('admin-ajax.php?action=bulk_disable_app'),
                'bulk_disabled_id' => '#bulk_action',
            ));
            wp_localize_script('dtScrapperScript', 'appsData6', array(
                'post_new_url' => admin_url('admin-ajax.php?action=post_new'),
            ));

        }elseif (isset($_GET['page']) && $_GET['page'] == "scrapper_website") {
            wp_localize_script('dtScrapperScript', 'appsData', array(
                'ajax_url' => admin_url('admin-ajax.php?action=websites_datatables'),
            ));
        }elseif (isset($_GET['page']) && $_GET['page'] == "scrapper_Category") {
            wp_localize_script('dtScrapperScript', 'appsData', array(
                'ajax_url' => admin_url('admin-ajax.php?action=categories_datatables'),
            ));
        }elseif (isset($_GET['page']) && $_GET['page'] == "scrapper_projects") {

            wp_localize_script('dtScrapperScript', 'appsData', array(
                'submit_id' => '#project_scrapper #submit',
                'form_id' => '#project_scrapper',
            ));
        }elseif (isset($_GET['page']) && $_GET['page'] == "related_post") {
            // CSS
            wp_register_style('selectboxit-CSS', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.selectboxit/3.8.0/jquery.selectBoxIt.css');
            wp_enqueue_style('selectboxit-CSS');

            wp_enqueue_script('selectboxit-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.selectboxit/3.8.0/jquery.selectBoxIt.min.js');

            wp_localize_script('dtScrapperScript', 'appsData', array(
                'ajax_url' => admin_url('admin-ajax.php?action=related_post_datatables'),

            ));
            wp_localize_script('dtScrapperScript', 'appsData2', array(
                'ajax_url' => admin_url('admin-ajax.php?action=delete_related_post_datatables'),

            ));
        }elseif (isset($_GET['page']) && $_GET['page'] == "dt-apps-Scrapper") {
            wp_localize_script('dtScrapperChart', 'appsData', array(
                'ajax_url' => admin_url('admin-ajax.php?action=chart_js'),

            ));
        }elseif (isset($_GET['page']) && $_GET['page'] == "KeySelected") {
            wp_localize_script('dtScrapperScript', 'appsData', array(
                'ajax_url' => admin_url('admin-ajax.php?action=key_selected'),

            ));

        }elseif (isset($_GET['page']) && $_GET['page'] == "settings") {
            wp_localize_script('dtScrapperScript', 'appsData', array(
                'ajax_url' => admin_url('admin-ajax.php?action=change_color'),

            ));

        }
    }
    function front_enqueue() {
        // enqueue all our scripts
//        wp_enqueue_style( 'dtScrapperStyle', $this->assets_css . 'style.css?v=1.0.4');
        if (isset($_GET["page"]) && $_GET["page"] = "download" && isset($_GET["app_id"])){

            // wp_deregister_script('jquery');
            // wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.4.min.js', array(), null, true);

//            wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '11.1.3', 'all' );
        }

//        wp_enqueue_style('dashicons');

//        wp_enqueue_style( 'dtScrapperStyleTable', $this->assets_css . 'style-table.css?v=1.0.4' );

    }
}
