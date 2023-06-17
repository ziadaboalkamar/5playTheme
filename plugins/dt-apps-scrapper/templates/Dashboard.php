<div class="wrap dashboard">
	<h1>Scrapper plugin </h1>
	<?php settings_errors(); ?>
   
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container">
    <div class="row">
  <?php  global $wpdb;
  require_once  WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/BaseController.php';

  use Inc\Base\BaseController;
  $base= new BaseController();
        $table_app_info =$base->table_app_info;
        $app_count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_app_info}");
?>
    <div class="col-md-4 col-xl-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block">
                <h6 class="m-b-20">Apps</h6>
                <h2 class="text-right"><i class="fa fa-android f-left"></i><span><?php echo $app_count; ?></span></h2>
            </div>
        </div>
    </div>
   <?php
    global $wpdb;
    $table_app_post =$base->table_app_post;
   
    $app_post_count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_app_post} ");
    ?>
       <div class="col-md-4 col-xl-3">
        <div class="card bg-c-green order-card">
            <div class="card-block">
                <h6 class="m-b-20">Related Posts </h6>
                <h2 class="text-right"><i class="fa fa-circle-nodes f-left"></i><span><?php echo $app_post_count; ?></span></h2>
            </div>
        </div>
    </div>
    <?php
    global $wpdb;
    $table_event= $base->table_event;
    $app_ids = array();
    $app_post_count = $wpdb->get_row( "SELECT * FROM {$table_event} ORDER BY id DESC LIMIT 1" );
    $updated_app_ids = $wpdb->get_results("
    SELECT *
    FROM (
      SELECT DATE(created_at) AS event_date, updated_app_id , updated_app
      FROM {$table_event}
      WHERE updated_app_id IS NOT NULL
        AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        AND  updated_app != 0
    ) AS daily_events
    JOIN (
      SELECT DATE(created_at) AS event_date, updated_app_id, id,updated_app
      FROM {$table_event}
      WHERE updated_app_id IS NOT NULL
      AND  updated_app != 0

    ) AS event 
    ON DATE(event.event_date) = daily_events.event_date
      AND CONCAT(',', event.updated_app_id, ',') LIKE CONCAT('%,', daily_events.updated_app_id, ',%');
    

");

    foreach($updated_app_ids as $date){
        if (isset($app_ids[$date->event_date])){
            if($date->updated_app_id && $date->updated_app_id != null ){
                foreach (json_decode($date->updated_app_id) as $id){
                    if (!in_array($id,$app_ids[$date->event_date])){
                        array_push($app_ids[$date->event_date],$id);
                    }
                }
            }
        
        }else{
            $app_ids[$date->event_date] = json_decode($date->updated_app_id);

        }

    }

    $formatted_date = "new";
    $updated_app = "new";
    if ($app_post_count){
        $date = new DateTime($app_post_count->created_at);
        $formatted_date = $date->format('Y:d:H H:i:s');
        $updated_app = $app_post_count->updated_app;
    }
    ?>
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Last Cron Job</h6>
                    <h2 class="text-right "><i class="fa fa-calendar"></i><span class="date"><?php echo $formatted_date?></span></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Last Apps Updated</h6>
                    <h2 class="text-right"><i class="fa fa-refresh f-left"></i><span><?php echo $updated_app ?></span></h2>
                </div>
            </div>
        </div>
	</div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card" style="width: 100%; max-width: 100%" >
                <div class="card-body">
                    <label for="chart-select">Choose chart period:</label>
                    <select id="chart-select">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <canvas id="my-chart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card" style="width: 100%; max-width: 100%" >
                <div class="card-body">
                    <h5 class="card-title">Updated Apps Last 7 Days</h5>
                    <?php if (isset($app_ids) && count($app_ids) > 0) { ?>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <?php

                            foreach ($app_ids as $key =>$result) {
                                if($result != null){
                                    echo '<th> '.$key.'</th>';

                                }
                            }
                            ?>


                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <?php
                        if (isset($app_ids)){
                            foreach ($app_ids as $key =>$result) {
                                if($result != null){
                                    $app_count = count($result);
                                    echo '<td><a href="'.esc_html( get_admin_url( get_current_blog_id(), "admin.php?page=dt-apps-Scrapper&action=view_apps&date={$key}" ) ).'">'.$app_count.' Apps </a></td>';
                                    // Do something with $event_date and $app_count, such as displaying them in a table
                               
                                }

                            }
                        }else{
                            echo "<tr><td>No result</td></tr>";
                        }

                        ?>

                        </tr>

                        </tbody>
                    </table>
                    <?php }else{ ?>

                    <h5 class="text-center">
                        Don't Have Event's
                    </h5>
                     <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>
</div>



