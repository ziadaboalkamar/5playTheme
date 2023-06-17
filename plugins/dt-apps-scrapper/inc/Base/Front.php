<?php
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Base;
require_once plugin_dir_path(__FILE__) . '/BaseController.php';


class Front extends BaseController  {

    function application_info($post_id) {

        global $wpdb;

        $table_dt_meta = $this->table_dt_meta;
        $table_meta_app_post =$this->table_app_post;
        $table_settings =$this->table_settings;
        $table_color= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'table_color'");
        $hover_line= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'hover_line'");
        $dw_button= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'dw_button'");
        $content_replace= $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'content_replace'");
        $content = '';
        if ($table_color){
            delete_option( 'dt_table_primary_color');

            add_option( 'dt_table_primary_color', $table_color->value );
        }else{
            add_option( 'dt_table_primary_color', '#2F0F5D' );
        }
        if ($hover_line){
            delete_option( 'dt_hover_line_primary_color');

            add_option( 'dt_hover_line_primary_color', $hover_line->value );
        }else{
            add_option( 'dt_hover_line_primary_color', '#2F0F5D' );
        }
        if ($dw_button){
            delete_option( 'dt_dw_button_primary_color');

            add_option( 'dt_dw_button_primary_color', $dw_button->value );
        }else{
            add_option( 'dt_dw_button_primary_color', '#0EA293' );
        }

        $posts= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_meta_app_post} WHERE post_id = %d", $post_id ) );
        if ($posts){
            foreach ($posts as $post){

                $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE status = 1 AND app_id = %d", $post->app_id ) );
                $app_name = "";
                $operating_system = "";
                $software_company = "";
                $downloads = "";
                $last_update = "";
                $votes = "";
                $rating = "";
                $software_size = "";
                $version = "";
                $download_info="";
                foreach ($results as $row) {


                    if(($row->key == 'app_name') ) {
                        $app_name = '<div class="dt-lg-3 table_dt_list"><div class="row"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("Name",'dt-apps-scrapper').'</span><span class="value"> '.$row->value.'</span></div></div></div>';

                    }
                    if(($row->key == 'version')) {
                        $version = ' <div class="dt-lg-3  table_dt_list"><div class="dt-row-scrapper"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("Version",'dt-apps-scrapper').'</span><span class="value">'.$row->value.'</span></div></div></div>';

                    }
                    if(($row->key == 'size')) {
                        $software_size = $row->value;
                        $software_size = ' <div class="dt-lg-3  table_dt_list"><div class="dt-row-scrapper"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("Size",'dt-apps-scrapper').'</span><span class="value"> '.$row->value.'</span></div></div></div>';
                    }
                    if(($row->key == 'rating') ) {
                        $rating = '<div class="dt-lg-3  table_dt_list"><div class="dt-row-scrapper"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("Rating",'dt-apps-scrapper').'</span><span class="value"> '.$row->value.'</span></div></div></div>';
                    }
                    if( ($row->key == 'votes') ) {
                        $votes = '<div class="dt-lg-3  table_dt_list"><div class="dt-row-scrapper"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("Votes",'dt-apps-scrapper').'</span><span class="value"> '.$row->value.'</span></div></div></div>';
                    }
                    if(($row->key == 'last_update')) {
                        $last_update = ' <div class="dt-lg-3  table_dt_list"><div class="dt-row-scrapper"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("last_update",'dt-apps-scrapper').'</span><span class="value">'. $row->value .'</span></div></div></div>';
                    }
                    if( ($row->key == 'downloads') ) {
                        $downloads = $row->value;
                        $downloads = ' <div class="dt-lg-3 table_dt_list"><div class="dt-row-scrapper"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("Download Count",'dt-apps-scrapper').'</span><span class="value">'. $row->value .'</span></div></div></div>';
                    }
                    if( ($row->key == 'author') ) {
                        $author = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE status = 1 AND `key` = 'author_link' AND app_id = %d", $post->app_id ) );
                        if ($author){
                            $author_link = $author->value;
                        }else{
                            $author_link = "#";
                        }
                        $software_company = $row->value;
                        $software_company  = ' <div class="dt-lg-3 table_dt_list"><div class="dt-row-scrapper"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("Author Name",'dt-apps-scrapper').'</span><span><a href="'.$author_link.'" target="_blank" class="value" >' . $software_company . '</a></span></div></div></div>';

                    }
                    if($row->key == 'os' ) {
                        $operating_system =$row->value ;
                        $operating_system = '<div class="dt-lg-3  table_dt_list"><div class="dt-row-scrapper"><div class="dt-lg-12"><span class="dt_title">'.esc_html__("Operating System",'dt-apps-scrapper').'</span><span class="value">'.$operating_system.'</span></div></div></div>';
                    }

                    if($row->key == 'link' ||  $row->key == 'file') {
                        $download_info =$row->value ;
                        $download_info = '<p><a href="' . esc_url( home_url( '/?page=download&id=' . $post_id.'&app_id='. $post->app_id) ) . '" class="dlink btn btn-three" target="_blank"  rel="noopener noreferrer nofollow">'.esc_html__("Download Link",'dt-apps-scrapper').'</a></p>';

                    }
                }
                $app_info_table = '';
                if($app_name || $version || $software_size || $last_update || $rating || $votes || $downloads || $software_company){
                    $app_info_table = '
 <style>
:root {
  --dt_table_primary_color: '.get_option( 'dt_table_primary_color' ).' ;
//    --dt_hover_line_primary_color: '.get_option( 'dt_hover_line_primary_color' ).' ;
      --dt_dw_button_primary_color: '.get_option( 'dt_dw_button_primary_color' ).' ;
}
</style>                                                                                 
                 <div class="card p-2" style="width: 100%;">
  <div class="card-body">
               <div class="dt_container table_dt ">
                 <div class="dt-row-scrapper application-info" dir="rtl">


                 '.
                        $app_name.
                        $version.
                        $software_size .
                        $last_update.
                        $rating.
                        $votes.
                        $downloads.
                        $software_company

                        .'

  </div>
</div>
              </div>
            </div>'.$download_info;

                }

                $content = $content.$app_info_table;

            }
            return $content;


        }


    }


function download_info($post_id,$app_id) {
    global $wpdb;
    $table_dt_meta =  $this->table_dt_meta;
    $table_settings = $this->table_settings;
    $table_color= $wpdb->get_row("SELECT * FROM $table_settings WHERE `key` = 'table_color'");
    $hover_line= $wpdb->get_row("SELECT * FROM $table_settings WHERE `key` = 'hover_line'");
    $dw_button= $wpdb->get_row("SELECT * FROM $table_settings WHERE `key` = 'dw_button'");
    $api_url = $wpdb->get_row("SELECT * FROM {$table_settings} WHERE `key` = 'api_url'");

    if ($table_color){
        delete_option( 'dt_table_primary_color');

        add_option( 'dt_table_primary_color', $table_color->value );
    }else{
        add_option( 'dt_table_primary_color', '#2F0F5D' );
    }
    if ($hover_line){
        delete_option( 'dt_hover_line_primary_color');

        add_option( 'dt_hover_line_primary_color', $hover_line->value );
    }else{
        add_option( 'dt_hover_line_primary_color', '#2F0F5D' );
    }
    if ($dw_button){
        delete_option( 'dt_dw_button_primary_color');

        add_option( 'dt_dw_button_primary_color', $dw_button->value );
    }else{
        add_option( 'dt_dw_button_primary_color', '#0EA293' );
    }
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_dt_meta} WHERE status = 1 AND app_id = %d", $app_id ) );
        $link = "";
if ($results){

        foreach ($results as $row) {
           if(($row->key == 'link' ||  $row->key == 'file') ) {
               if ($row->key == 'link'){
                   $link_file =$row->value;

               }else{
                   $link_file =$api_url->value."/".$row->value;
               }
           $svg = ' <style>
                        :root {
                          --dt_table_primary_color: '.get_option( 'dt_table_primary_color' ).' ;
                              --dt_dw_button_primary_color: '.get_option( 'dt_dw_button_primary_color' ).' ;
                        }
                        </style>
                        <span style="margin-left: 10px"><svg width="1em" height="1em" viewBox="0 0 24 24">
                                           <path d="M12 1.993C6.486 1.994 2 6.48 2 11.994c0 5.513 4.486 9.999 10 10c5.514 0 10-4.486 10-10s-4.485-10-10-10.001zm0 18.001c-4.411-.001-8-3.59-8-8c0-4.411 3.589-8 8-8.001c4.411.001 8 3.59 8 8.001s-3.589 8-8 8z" fill="currentColor"></path>
                                           <path d="M13 8h-2v4H7.991l4.005 4.005L16 12h-3z" fill="currentColor"></path>
                                           </svg></span>';
               $link ='<a target="blank"  class="android_button btn btn-primary btn btn-three" href="'.$link_file.'"> '.$svg.esc_html__("Download Link",'dt-apps-scrapper').' </a>';
           }
        }
}
    echo $link;

}}