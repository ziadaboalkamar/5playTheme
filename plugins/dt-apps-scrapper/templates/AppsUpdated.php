<div class="wrap">
    <br>
    <div class="tab-content">
		<div id="tab-1" class="tab-pane active form">
            <h2 class="tab-pane active" >DT Apps Scrapper Category </h2><br>
            <?php settings_errors(); ?>
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
            <table class="ui celled table" style="width:100%">
                <thead>
                   <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th>Package Name</th>
                        <th>status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $apps = \Inc\Base\Apps::AppsUpdated($_GET["date"]);
                    $app_ids = array_map('intval', $apps);
                    $all_apps = \Inc\Base\Apps::get_apps($app_ids);
                       foreach ($all_apps as $apps){
                            echo '<tr><td>'.$apps->id.'</td> <td>'.$apps->name.'</td> <td>'.$apps->package_name.'</td> <td>'.$apps->status.'</td><td><a href="'.esc_html( get_admin_url( get_current_blog_id(), "admin.php?page=dt-apps-Scrapper&action=view_history&id={$apps->id}&date={$_GET["date"]}" ) ).'" type="button" class="btn btn-primary btn-sm">History</a></td>  </tr>' ;
                       }
                    ?>
                </tbody>
            </table>
		</div>
	</div>
</div>