<div class="wrap">
	<h1>DT Apps Scrapper Plugin</h1>


    <?php
    if (isset($_SESSION['success_msg']) && $_SESSION['success_msg'] != "") {
        ?>
        <script>
            toastr.success("<?=$_SESSION['success_msg'];?>");

        </script>
        <?php
        unset($_SESSION['success_msg']);

    }
    ?>
    <?php
    if (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] != "") {
        ?>
        <script>
            toastr.error("<?=$_SESSION['error_msg'];?>");

        </script>
        <?php
        unset($_SESSION['error_msg']);

    }
    ?>
	
	<?php
   global $wpdb;
   $table_settings =$this->table_settings;;
   $project_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'project'");
   $use_tag_setting = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'use_tag'");


	?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card"  style="width: 100%;padding: 0px; max-width: 100%; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; border: none">
                <div class="card-header">
                    <h4>
                        Manage Settings
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="post" id="project_scrapper" action="options.php">
                                <input type="submit" id="hidden-submit" style="display: none;">

                                <?php
                                settings_errors();
                                settings_fields('scrapper_options_group');
                                do_settings_sections('dt-apps-Scrapper');
                                submit_button();
                                ?>
                            </form>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
             


</div>
