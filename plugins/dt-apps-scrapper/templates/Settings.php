<div class="wrap">
    <br>

    <?php settings_errors(); ?>

    <?php
    if (isset($_SESSION['success_msg']) && $_SESSION['success_msg'] != "") {
        ?>
        <script>

            toastr.success("<?=$_SESSION['success_msg'];?>");

        </script>
        <?php
        // unset($_SESSION['success_msg']);
//        unset($_SESSION['success_msg']);

    }
    ?>
    <?php
    if (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] != "") {
        ?>
        <script>

            toastr.error("<?=$_SESSION['error_msg'];?>");
        </script>

        <?php
        unset($_SESSION['success_msg']);

        unset($_SESSION['error_msg']);

    }
    require_once  WP_PLUGIN_DIR  . '/dt-apps-scrapper/inc/Base/BaseController.php';

    use Inc\Base\BaseController;
    global $wpdb;
    $table_settings = $this->table_settings;;
    $row_api_connected = $wpdb->get_row("SELECT * FROM $table_settings WHERE `key` = 'api_connected'");
    $schedule = wp_get_schedule( 'my_cron_hook' );
    // Check if your cron job is scheduled
    if ( $schedule ) {
        // Display the schedule and next run time
        $cron_status =  'Your cron job is scheduled to run every ' . $schedule ;
        $next_cron_job = 'The next run time is: ' . date( 'Y-m-d H:i:s', wp_next_scheduled( 'my_cron_hook' ) ) ;
    } else {
        // Display a message if your cron job is not scheduled
        $cron_status = 'Your cron job is not scheduled.';
        $next_cron_job = 'Your cron job is not scheduled.';
    }
    if($row_api_connected != null) {
        if($row_api_connected->value == 0) {
            ?>

            <div class="tab-content">
                <div id="tab-1" class="tab-pane active form">
                    <h2 class="tab-pane active" >DT Apps Scrapper Settings </h2>
                    <p>Connect to Api to get Apps Data.</p>
                    <form id="loginform"  action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                        </div><br>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div><br>
                        <div class="form-group">
                            <label for="url">api url</label>
                            <input type="url" class="form-control" id="url" name="url" placeholder="url">
                        </div><br>
                        <button type="submit" name="SettingSubmit" class="btn btn-primary">Connect/Login</button>
                    </form>
                    <p class="status"></p>
                </div>
            </div>
            <?php
        } else {

            global $wpdb;
            $base= new BaseController();
            $table_settings=$base->table_settings;
            $results = $wpdb->get_results("SELECT * FROM $table_settings");
            $use_tag_setting = $wpdb->get_row("SELECT * FROM $table_settings WHERE `key` = 'use_tag'");
            $content_place_setting = $wpdb->get_row("SELECT * FROM $table_settings WHERE `key` = 'content_place'");
            $array_content_place = json_decode($content_place_setting->value);
            $redirect_url_setting = $wpdb->get_row("SELECT * FROM $table_settings WHERE `key` = 'redirect_url'");

            ?>
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <div>
                    You Are Connected With Api Now !
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="width: 100%;padding: 0px; max-width: 100%; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; border: none">
                        <div class="card-header">
                            <h4>
                                DT Apps Scrapper Settings
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h4 class="card-subtitle mb-2 text-body-secondary"> Profile</h4>
                                    <?php foreach ($results as $row) { ?>
                                        <?php if ($row->key == 'name') { ?>
                                            <p><strong>Name:</strong> <?php echo $row->value; ?></p>
                                        <?php } else if ($row->key == 'api_email') { ?>
                                            <p><strong>Email:</strong> <?php echo $row->value; ?></p>
                                        <?php } else if ($row->key == 'type') { ?>
                                            <p><strong>Type:</strong> <?php echo $row->value; ?></p>
                                        <?php } else if ($row->key == 'created_at') { ?>
                                            <p><strong>created_at:</strong> <?php echo $row->value; ?></p>
                                        <?php } else if ($row->key == 'last_connected') { ?>
                                            <p><strong>last_connected:</strong> <?php echo $row->value; ?></p>

                                        <?php } else if ($row->key == 'project_name') { ?>
                                            <p><strong>Project:</strong> <?php echo $row->value; ?></p>
                                        <?php } else if ($row->key == 'tag_name') { ?>
                                            <p><strong>Tag:</strong> <?php if ($use_tag_setting->value == 0){ echo "Don't use tag";}else{ echo "$row->value ";} ?></p>
                                        <?php } ?>
                                    <?php } ?>
                                    <p><strong>CronJob:</strong> <?php echo $cron_status?></p>
                                    <p><strong>Next Run CronJob:</strong> <?php echo $next_cron_job?></p>

                                </div>
                                <div class="col-lg-6">
                                    <form id="loginform"  action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>" class="text-right" style="text-align: right" >
                                        <input type="hidden" value="0" name="api_connected">
                                        <button  type="submit" style="padding: 10px 50px;" name="SubmitDisabled" class="btn btn-danger">Disconnect</button>
                                    </form >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card" style="width: 100%;padding: 0px; max-width: 100%; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; border: none">
                        <div class="card-header">
                            <h4>
                                DT Apps Scrapper Settings
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label for="exampleColorInput" class="form-label">Table Color</label>
                                    <input type="color" class="form-control form-control-color" id="exampleColorInput" name="table_color" value="<?php color_saved("table"); ?>" title="Choose your color">
                                </div>
                                <div class="col-lg-2">
                                    <label for="exampleColorInput" class="form-label">Download Button Color</label>
                                    <input type="color" class="form-control form-control-color" id="DownloadColorInput" name="download_button_color" value="<?php color_saved("dw_button"); ?>" title="Choose your color">
                                </div>
                                <div class="col-lg-2">
                                    <label for="exampleColorInput" class="form-label">Hover Line Color</label>
                                    <input type="color" class="form-control form-control-color" id="hoverColorInput" name="hover_color" value="<?php color_saved("hover_line"); ?>" title="Choose your card color">
                                </div>
                                <div class="col-lg-3">
                                    <label for="exampleColorInput"  class="form-label">Place Of Content</label><br>
                                    <select class="select2" name="content_place[]" multiple id="content_place">
                                        <option <?php if (in_array("in_top",$array_content_place)){ echo "selected" ;} ?> value="in_top">insert table of top content</option>
                                        <option <?php if (in_array("inside_content",$array_content_place)){ echo "selected" ;} ?>  value="inside_content">insert table in side the content content</option>
                                        <option <?php if (in_array("in_bottom",$array_content_place)){ echo "selected" ;} ?>  value="in_bottom">insert table of bottom of the  content</option>

                                    </select>
                                </div>
                                <div class="col-lg-3">
                                        <label for="exampleColorInput"  class="form-label">SubDomain</label><br>
                                    <input  class="form-control" type="text" placeholder="https://www.example.com/" name="redirect_url" id="redirect_url" value="<?php if ($redirect_url_setting) echo $redirect_url_setting->value;?>">
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" onclick="change_color()"  class="btn btn-primary mt-3">Save Settings</button>

                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>



            <?php
        }
    }
    ?><script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('#content_place').select2();
        });
    </script>
</div>
