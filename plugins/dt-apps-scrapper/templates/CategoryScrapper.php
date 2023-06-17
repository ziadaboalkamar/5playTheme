<div class="wrap">
    <br>
    <div class="tab-content">
		<div id="tab-1" class="tab-pane active form">
            <h2 class="tab-pane active">DT Apps Scrapper Category </h2><br>
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
            <table id="categoryTable" class="ui celled table" style="width:100%">
                <thead>
                    <tr>
                        
                        <th>id</th>
                        <th>Name</th>
                        <th>status</th>
                      
                    </tr>

                </thead>

            </table>
		</div>
	</div>
</div>