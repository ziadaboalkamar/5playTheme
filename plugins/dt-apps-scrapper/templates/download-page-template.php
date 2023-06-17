<?php

use Inc\Base\BaseController;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Template Name: Download page
*/

if ( !isset($_GET['id']) || empty($_GET['id']) ) {
    wp_redirect( home_url() );
    exit;
}

$post_id = intval($_GET['id']);
$app_id = intval($_GET['app_id']);
if($post_id != 0) {
    if ( FALSE === get_post_status( $post_id ) ) {
        wp_redirect( home_url() );
        exit;
    }
}
wp_reset_postdata();



$class = '';
if( function_exists('fw_ext_page_builder_is_builder_post') && !fw_ext_page_builder_is_builder_post( get_queried_object_id() ) ) {
    $class = ' page-default-content';
}



?>
<!DOCTYPE html>
<html>
<head>
    <title>My Template Page</title>
    <?php wp_head(); ?>
    <style>

        .dt-lg-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
            display: block;
            width: 100%;
            padding-top: 27px;
            padding-bottom: 3px;
        }

        .post-container{
            width: 215px;
            height: 158px;
        }
        .app-down .app-info {
            line-height: 38px;
        }
        .app-down .app-info .name {
            float: right;
            margin-left: 8px;
            font-size: 16px;
            color: #1e39f2;
        }
        .dwn-excerpt {
            padding: 15px 10px;
            border: 1px solid #edf2f4;
            margin: 0px 0 15px;
            border-radius: 4px;
            line-height: 2em;
        }
        .dwn-excerpt .read-more {
            color: #1e39f2;
        }
        .show-link #more-links {
            margin-top: 25px;
            padding: 10px;
            border: 1px solid #edf2f4;
            border-radius: 4px;
        }
        .down-page #more-links .dlink {
            margin-bottom: 10px;
            background-color: #1c3b5e;
            display: -ms-flexbox;
            display: flex;
        }
        #more-links .dlink {
            width: auto;
        }
        .col-link .dlink, .col-links .dlink {
            height: 45px;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0;
            opacity: .9;
            transition: all .2s ease;
        }
        #counter {
            float: right;
            position: relative;
            height: 80px;
            width: 80px;
            margin-left: 15px;
            margin-bottom: 15px;
        }
        #counter .load img {
            /* opacity: .1; */
            border-radius: 50%;
        }
        .read_paragraph{
            display: inline-block;
            position: relative;
            top: -42px;
            right: 569px;
        }
        .ab-center {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            margin: auto;
            text-align: center;
        }
        #counter .load {
            position: absolute;
            height: 80px;
            width: 80px;
            border-radius: 50%;
            border: 6px solid #f2f2f2;
        }
        div[data-progress] {
            box-sizing: border-box;
            position: relative;
            height: 60px;
            width: 60px;
            background: beige;
            border-radius: 50%;
            box-shadow: inset 0px 0px 0px 7px red;
            transition: all 1s;
            overflow: hidden;
            margin: auto;
            top:-20px;

        }
        .counter {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0%;
            left: 0%;
            text-align: center;
            line-height: 61px;
            border-radius: 50%;
            background: transparent;
            z-index: 4;
        }
        /* div > div {
          position: absolute;
          height: 50%;
          width: 50%;
          background: inherit;
          border-radius: 0%;
        } */
        .quad1,
        .quad2 {
            left: 50%;
            transform-origin: left bottom;
        }
        #more-links, #more-links-2{
            display:none;
        }
        .quad3,
        .quad4 {
            left: 0%;
            transform-origin: right top;
        }
        .quad1,
        .quad4 {
            top: 0%;
        }
        .quad2,
        .quad3 {
            top: 50%;
        }
        .quad1,
        .quad3 {
            transform: skew(0deg); /* invisible at -90deg */
        }
        .quad2,
        .quad4 {
            transform: skewY(0deg); /* invisible at 90deg */
        }
        /* Just for demo */
        /* body {
          background: linear-gradient(90deg, crimson, indianred, purple);
        } */
        /* div[data-progress] {
            margin-right: 286px;
            top: -67px;
        } */

        a.red_class {
            padding: 9px 9px;
            border-radius: 10px;
            background-color: #ed5369 !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom:  -7px !important;
            margin-top: 11px !important;
            border: none;
        }

        a.yellow_class {
            padding: 10px 20px;
            border-radius: 10px;
            background-color: #f1c40f !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom: -7px !important;
            margin-top: 11px !important;
            border: none;
        }


        a.blue_class  {
            padding: 9px 9px;
            border-radius: 10px;
            background-color: #0056a7 !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom:  -7px !important;
            margin-top: 11px !important;
            border: none;
        }

        a.Purple_class{
            padding: 9px 9px;
            border-radius: 10px;
            background-color: #9638a9 !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom: -7px !important;
            margin-top: 10px !important;
            border: none;
        }

        a.gray_class {
            padding: 9px 9px;
            border-radius: 10px;
            background-color: #777 !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom: -7px !important;
            margin-top: 10px !important;
            border: none;
        }

        a.green_class {
            padding: 9px 9px;
            border-radius: 10px;
            background-color: #14a24e !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom: -7px !important;
            margin-top: 11px !important;
            border: none;
        }
        a.Black_class {
            padding: 9px 9px;
            border-radius: 10px;
            background-color: #242526d9 !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom: -7px !important;
            margin-top: 10px !important;
            border: none;
        }

        a.pink_class{
            padding: 9px 9px;
            border-radius: 10px;
            background-color: #e2306cdb !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom: -7px !important;
            margin-top: 10px !important;
            border: none;
        }
        a.red_class:hover,.blue_class:hover,.yellow_class:hover,.gray_class:hover,.pink_class:hover,.Black_class:hover,.Purple_class:hover,.green_class:hover{
            box-shadow: 1px 1px 12px 0px #ccc !important;
            opacity: 1;
            transition: all 0.2s ease;
        }


        a.android_button.btn.btn-primary {
            padding: 10px 9px;
            border-radius: 10px;
            background: var(--dt_dw_button_primary_color) !important;
            color: white !important;
            font-weight: bold;
            width: 172px;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 158px;
            margin-bottom: 25px !important;
            margin-top: 5px !important;
            border: none;
            font-size: 15px;
        }
        a.apple_button.btn.btn-primary {
            padding: 6px !important;
            border-radius: 10px;
            background: #777 !important;
            color: #fff !important;
            font-weight: 700;
            width: 131px !important;
            display: flex;
            align-items: center;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 134px !important;
            margin-bottom: 2px !important;
            margin-top: 11px !important;
            border: none;
        }
        a.computer_button.btn.btn-primary {
            padding: 1px 9px !important;
            margin-top: 19px !important;
            background: #2196f3;
            width: 131px !important;
            display: flex;
            color: #fff;
            align-items: center;
            margin-top: 30px !important;
            margin: auto !important;
            text-align: center;
            justify-content: center;
            border-radius: 4px;
            text-decoration: none;
            min-width: 134px !important;
            line-height: .8;
            font-weight: 700;
        }
        svg.out-svg {
            width: 20px;
            margin-left: 6px;
        }

        svg.andrd-svg {
            width: 20px;
            margin-left: 6px;
        }

        svg.app-svg {
            width: 20px;
            margin-left: 6px;
        }

        .post-related {
            display: flex;
            padding-top: 39px;
            padding-bottom: 191px;
        }
        .post-categories {
            display: none;
        }
        h2.post-single-title {
            margin-right: -73px;
        }
        h3.h4 {
            font-size: 12px;
        }
        .page-content{
            background-color: #eeeeee3d;
        }
        dt-row-scrapper{
            direction: ltr;
        }
        .rtl.dt-row-scrapper{
            direction: rtl;
        }

    </style>
</head>
<body>
<?php
if( wp_is_block_theme() ) {
    block_template_part('header');
}else{
    get_header();
}
?>
<div id="content-wrapper">
    <div id="content" class="page-content">

        <div class="page-content">
            <?php
            global $wpdb;
            $base = new BaseController();
            $table_app_info=$base->table_app_info;
            $app_data = $wpdb->get_row("SELECT * FROM {$table_app_info} WHERE id = {$app_id}");
            $logo = $base->assets_image."android.png";
            $logo_app ="";
            $software_name = "";
            $software_license="";
            $Front = new Inc\Base\Front();
            $link =  get_permalink($post_id);
            $software_license = "[ ".get_key($app_id,"version", )." ]";
            if(get_key($app_id,"logo", ) ){
                $logo = get_key($app_id,"logo", );
            }
            if(get_key($app_id,"app_name") ){
                $software_name = get_key($app_id,"app_name");
            }else{
               $software_name = $app_data->name;
            }

            ?>
            <div>
          <div class="dt_container pb-5">
              <div class="dt-row-scrapper">
                  <div class="dt-lg-12">
                      <div class="card mt-4 mb-4" style="width: 100%; height: 100%">
                          <div class="card-body">
                              <h2 class="card-title mt-3">
                                  <?php if( !is_front_page() && get_the_title() ) : ?>
                                     <?php esc_html_e("Download Page",'dt-apps-scrapper');?>
                                          <?php echo " ".$software_name ; ?>

                                  <?php endif; ?>
                              </h2>
                              <div class="dt-row-scrapper">
                                  <div class="dt-lg-12">
                                      <div class="app-down">
                                          <div class="dt-row-scrapper">
                                              <div class="dt-lg-12 mb-4">
                                                  <div class="dt-row-scrapper">
                                                      <div class="dt-lg-8">
                                                          <div class="dt-row-scrapper">
                                                              <div class="dt-col-auto">
                                                                  <div id="counter" class="loaded">
                                                                      <div class="load">
                                                                          <img src="<?php echo $logo; ?>" alt="<?php echo $software_name; ?>" width="80px" height="80px">
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              <div class="dt-col-auto">
                                                                  <div class="app-info">
                                                                      <h3>
                                                                          <span class="name"><?php echo $software_name; ?></span> <span class="license free"><?php echo $software_license; ?></span>
                                                                      </h3>
                                                                      <p class="excerpt" id="status"><?php esc_html_e("The file will start downloading in seconds!",'dt-apps-scrapper');?></p>
                                                                      <div id="add_after_me"></div>
                                                                      <div data-progress="0" id="progress_bar">
                                                                          <div class="quad1"></div>
                                                                          <div class="quad2"></div>
                                                                          <div class="quad3"></div>
                                                                          <div class="quad4"></div>
                                                                          <div class='counter'>5</div>
                                                                      </div>
                                                                      <div id="more-links">

                                                                        <span class="bicon">
                                                                            <svg width="1em" height="1em" viewBox="0 0 24 24">
                                                                                <path d="M12 1.993C6.486 1.994 2 6.48 2 11.994c0 5.513 4.486 9.999 10 10c5.514 0 10-4.486 10-10s-4.485-10-10-10.001zm0 18.001c-4.411-.001-8-3.59-8-8c0-4.411 3.589-8 8-8.001c4.411.001 8 3.59 8 8.001s-3.589 8-8 8z" fill="currentColor"></path>
                                                                                <path d="M13 8h-2v4H7.991l4.005 4.005L16 12h-3z" fill="currentColor"></path>
                                                                            </svg>
                                                                        </span>
                                                                          <span><?php esc_html_e("The file is ready for download",'dt-apps-scrapper');?></span>
                                                                          <?php
                                                                          $Front->download_info($post_id,$app_id);
                                                                          ?>
                                                                      </div>
                                                                  </div>

                                                              </div>
                                                          </div>

                                                      </div>
                                                      <div class="dt-lg-6"></div>
                                                  </div>
                                              </div>

                                              <div class="dt-lg-6">
                                                  <div class="dwn-excerpt"><?php echo wp_first_paragraph_excerpt($post_id); ?></div>
                                              </div>
                                              <div class="dt-lg-6">
                                              </div>
                                              <div class="dt-lg-8 mt-3">
                                                  <?php $files=\Inc\Base\Apps::check_if_has_old_files($_GET["app_id"]);
                                                 if (isset($files["files"]) && count($files["files"]) > 0){
                                                  ?>
                                                  <div class="card not_hover" style="overflow-x:auto;width: 100%;padding: 0px; max-width: 100%; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; border: none">
                                                      <div class="card-body">
                                                          <h5 class="card-title"> <?php esc_html_e("Other Versions",'dt-apps-scrapper'); ?></h5>
                                                          <table class=" table table-striped old_version_table mt-3" cellspacing="0">
                                                              <thead>
                                                              <tr>
                                                                 <th scope="col"><?php esc_html_e("Name",'dt-apps-scrapper');?></th>
                                                                  <th scope="col"><?php esc_html_e("Size",'dt-apps-scrapper');?></th>
                                                                  <th scope="col"><?php esc_html_e("Version",'dt-apps-scrapper');?></th>
                                                                  <th scope="col"><?php esc_html_e("Download Link",'dt-apps-scrapper');?></th>
                                                              </tr>
                                                              </thead>
                                                              <tbody>
                                                              <?php
                                                              foreach ($files["files"] as $file){
                                                                  if ($file["version"] != null){
                                                                      $version = $file["version"];
                                                                  }else{
                                                                      $version ="";
                                                                  }
                                                                  $link = $files["api_url"].'/'.$file["file"];
                                                                  $download_info = '<p><a href="' .$link. '" class="dlink btn btn-three" target="_blank"  rel="noopener noreferrer nofollow"> '.esc_html__("Download Link",'dt-apps-scrapper').'</a></p>';

                                                                  echo "<tr><td>".$file["file_name"]."</td><td>".$file["size"]."</td><td>".$version."</td><td>".$download_info."</td></tr>";
                                                                  ?>

                                                              <?php   }
                                                              }   ?>
                                                              </tbody>
                                                          </table>
                                                      </div>
                                                  </div>



                                              </div>

                                          </div>

                                       </div>

                                  </div>
                              </div>

                          </div>
                      </div>
                  </div>
              </div>
          </div>

            </div>

            <?php /* Clear unclosed floats */ ?>
            <div class="sh-clear"></div>
        </div>


        <?php if( false && isset( $position ) && $position == 'bottom' ) : ?>
            <div class="post-related-arrows">
                <div class="post-slide-arrows sh-carousel-buttons-styling"></div>
            </div>
        <?php endif;?>

    </div>
</div>

<?php
if( wp_is_block_theme() ) {
    block_template_part('footer');
}else{
    get_footer();
}

?>
<?php wp_footer(); ?>




<script>
    window.onload = function() {
        var progressbar = document.querySelector('div[data-progress]'),
            quad1 = document.querySelector('.quad1'),
            quad2 = document.querySelector('.quad2'),
            quad3 = document.querySelector('.quad3'),
            quad4 = document.querySelector('.quad4'),
            counter = document.querySelector('.counter');

        var progInc = setInterval(incrementProg, 1000); // call function every second

        function incrementProg() {
            progress = progressbar.getAttribute('data-progress'); //get current value
            progress++; // increment the progress bar value by 1 with every iteration
            progressbar.setAttribute('data-progress', progress); //set value to attribute
            counter.textContent = 5 - parseInt(progress, 10); // set countdown timer's value
            setPie(progress); // call the paint progress bar function based on progress value
            if (progress == 5) {
                clearInterval(progInc); // clear timer when countdown is complete
                document.getElementById("add_after_me").insertAdjacentHTML("afterend",
                    '<a href="" target="_blank" rel="nofollow noopener noreferrer"></a>');
                document.getElementById("status").remove();
                document.getElementById("progress_bar").remove();
                document.getElementById("more-links").style.display = 'block';


            }
        }

        function setPie(progress) {
            /* If progress is less than 25, modify skew angle the first quadrant */
            if (progress <= 1.5) {
                quad1.setAttribute('style', 'transform: skew(' + progress * (-90 / 25) + 'deg)');
            }

            /* Between 25-50, hide 1st quadrant + modify skew angle of 2nd quadrant */
            else if (progress > 1.25 && progress <= 2.5) {
                quad1.setAttribute('style', 'transform: skew(-90deg)'); // hides 1st completely
                quad2.setAttribute('style', 'transform: skewY(' + (progress - 1.25) * (90 / 25) + 'deg)');
                progressbar.setAttribute('style', 'box-shadow: inset 0px 0px 0px 7px orange');
            }

            /* Between 50-75, hide first 2 quadrants + modify skew angle of 3rd quadrant */
            else if (progress > 2.5 && progress <= 3.75) {
                quad1.setAttribute('style', 'transform: skew(-90deg)'); // hides 1st completely
                quad2.setAttribute('style', 'transform: skewY(90deg)'); // hides 2nd completely
                quad3.setAttribute('style', 'transform: skew(' + (progress - 3.75) * (-90 / 25) + 'deg)');
                progressbar.setAttribute('style', 'box-shadow: inset 0px 0px 0px 7px yellow');
            }

            /* Similar to above for value between 75-100 */
            else if (progress > 3.75 && progress <= 5) {
                quad1.setAttribute('style', 'transform: skew(-90deg)'); // hides 1st completely
                quad2.setAttribute('style', 'transform: skewY(90deg)'); // hides 2nd completely
                quad3.setAttribute('style', 'transform: skew(-90deg)'); // hides 3rd completely
                quad4.setAttribute('style', 'transform: skewY(' + (progress - 30) * (90 / 25) + 'deg)');
                progressbar.setAttribute('style', 'box-shadow: inset 0px 0px 0px 7px green');
            }
        }
    }
</script>
<script>
    // Select all the 'a' elements inside the 'ul' with class 'dropdown-menu-lang'
    var links = document.querySelectorAll('ul.dropdown-menu-lang a');
    var id = <?php echo $post_id ?>;
    var app_id = <?php echo $app_id ?>;
    // Loop through all the links and modify their 'href' attribute
    for (var i = 0; i < links.length; i++) {
        var href = links[i].getAttribute('href'); // Get the current href value
        var newHref = href + '?page=download&id='+id+'&app_id='+app_id; // Add the variable to the href
        links[i].setAttribute('href', newHref); // Set the new href value
    }

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
</body>
</html>




