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
            <?php
            $apps = \Inc\Base\Apps::get_apps_history($_GET["id"],$_GET["date"]);


            foreach ($apps as $keys => $history){
                $index = intval($keys);
                ?>

                <div class="card" id="card_<?php echo $index ?>" style="width: 100%;padding: 0px; max-width: 100%; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; border: none">
                    <div class="card-body">

                        <h5 class="card-title">Date : <?php echo $history["datetime"] ?> </h5>
                        <table class="ui celled table" style="width:100%">
                            <thead>
                            <tr>

                                <th>Key</th>
                                <th>New value</th>
                                <th>Old value</th>

                            </tr>

                            </thead>
                            <tbody>
                            <?php
                            foreach ($history["rows"] as $row) {
                                $data = $row;
                                if (is_object($data)) {
                                    // Convert the object to a JSON string
                                    $data = json_encode($data);
                                }

                                // Decode the JSON string into an associative array
                                $data = json_decode($data, true);
                                $old_date = "";
                                if(isset($apps[$index-1]["datetime"]) && $apps[$index-1]["datetime"] != null ){
                                    $old_date = $apps[$index-1]["datetime"];
                                    $dif_key = \Inc\Base\Apps::check_if_key_change($history["datetime"],$old_date,$data["key"]);

                                    if ($dif_key && count($dif_key) > 0){
                                        echo "<tr><td>".$dif_key["key"]."</td><td>".$dif_key["new_value"]."</td><td>".$dif_key["old_value"]."</td></tr>";

                                    }else{
                                        echo "<style> #card_".$index."{display: none}</style>";
                                    }
                                }else{
                                    echo "<tr><td>".$data["key"]."</td><td>".$data["value"]."</td><td>No data</td></tr>";
                                }
                            }
                            ?>
                             </tbody>
                        </table>

                    </div>
                </div>
            <?php } ?>
		</div>
	</div>
</div>