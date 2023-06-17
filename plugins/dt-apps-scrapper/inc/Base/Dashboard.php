<?php
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Base;
require_once plugin_dir_path(__FILE__) . '/BaseController.php';

class  Dashboard extends BaseController {
public function chartjs(){
    global $wpdb;
    // $table_event = $wpdb->prefix . 'dt_event';
   $table_event = $this->table_event;
    if (!isset($_GET["period"])){
      $data = $wpdb->get_results("
      SELECT created_at AS date, updated_app
      FROM $table_event
      WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
      GROUP BY created_at
      ORDER BY created_at ASC
    ");
    }else{
      $period = $_GET["period"];
      if ($period == "daily"){
          $data = $wpdb->get_results("
          SELECT created_at AS date, updated_app
          FROM $table_event
          WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
          GROUP BY created_at
          ORDER BY created_at ASC
        ");
      }elseif($period == "weekly"){
          $data = $wpdb->get_results("
          SELECT created_at AS date, updated_app
          FROM $table_event
          WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          GROUP BY created_at
          ORDER BY created_at ASC
        ");
      }elseif($period == "monthly"){
          $data = $wpdb->get_results("
          SELECT created_at AS date, updated_app
          FROM $table_event
          WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
          GROUP BY created_at
          ORDER BY created_at ASC
        ");
      }
    }

    $labels = array();
    $app_counts = array();

    foreach ($data as $row) {
        $labels[] = $row->date;
        $app_counts[] = $row->updated_app;
    }

    $data = array(
        'labels' => $labels,
        'app_counts' => $app_counts
    );
    wp_send_json($data);
}
}