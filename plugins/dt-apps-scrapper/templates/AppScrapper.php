
<div class="wrap">

    <!-- <h1>AppScrapper </h1> -->
    <?php settings_errors();?>
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
    <div class="wrap">
        <div class="loader">
            <svg role="img" aria-label="Mouth and eyes come from 9:00 and rotate clockwise into position, right eye blinks, then all parts rotate and merge into 3:00" class="smiley" viewBox="0 0 128 128" width="128px" height="128px">
                <defs>
                    <clipPath id="smiley-eyes">
                        <circle class="smiley__eye1" cx="64" cy="64" r="8" transform="rotate(-40,64,64) translate(0,-56)" />
                        <circle class="smiley__eye2" cx="64" cy="64" r="8" transform="rotate(40,64,64) translate(0,-56)" />
                    </clipPath>
                    <linearGradient id="smiley-grad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#000" />
                        <stop offset="100%" stop-color="#fff" />
                    </linearGradient>
                    <mask id="smiley-mask">
                        <rect x="0" y="0" width="128" height="128" fill="url(#smiley-grad)" />
                    </mask>
                </defs>
                <g stroke-linecap="round" stroke-width="12" stroke-dasharray="175.93 351.86">
                    <g>
                        <rect fill="hsl(193,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
                        <g fill="none" stroke="hsl(193,90%,50%)">
                            <circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
                            <circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
                        </g>
                    </g>
                    <g mask="url(#smiley-mask)">
                        <rect fill="hsl(223,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
                        <g fill="none" stroke="hsl(223,90%,50%)">
                            <circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
                            <circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
                        </g>
                    </g>
                </g>
            </svg>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="width: 100%;padding: 0px; max-width: 100%; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; border: none">
                    <div class="card-header">
                        <h4>
                            DT Apps Scrapper / Applications
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row f-right">
                                    <div class="col-auto">
                                        <button type="button"  id="bulk_action" class="btn btn-outline-danger mb-4">Bulk Disabled</button>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" onclick="process_app()" id="process_data" class="btn btn-outline-success">Fetch Data</button>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" id="create_Post" class="btn btn-outline-success">Add All</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <table id="appsTable" class="ui celled table" style="max-width: 100%">
                                    <thead>
                                    <tr>
                                        <th><div class="animated-checkbox">
                                                <label class="m-0">
                                                    <input type="checkbox" id="record__select-all">
                                                    <span class="label-text"></span>
                                                </label>
                                            </div></th>
                                        <th>id</th>
                                        <th>name</th>
                                        <th>package Name</th>
                                        <th>api_app_id</th>
                                        <th>posts</th>
                                        <th>status</th>
                                        <th>action</th>

                                    </tr>

                                    </thead>
                                </table>
                                <!--                             --><?php
                                //                             if (isset($_GET['process']) && $_GET["process"] && $_GET["process"]==true){
                                //                                 ?>
                                <input type="hidden" name="record_ids" id="record-ids" value="[]">



                                <!--                             --><?php //} ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>



    </div>

