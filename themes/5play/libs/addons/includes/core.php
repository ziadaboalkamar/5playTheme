<?php
/*-----------------------------------------------------------------------------------*/
/*  EXTHEM.ES
/*  PREMIUM WORDRESS THEMES
/*
/*  STOP DON'T TRY EDIT
/*  IF YOU DON'T KNOW PHP
/*  AS ERRORS IN YOUR THEMES ARE NOT THE RESPONSIBILITY OF THE DEVELOPERS
/*
/*
/*  @EXTHEM.ES
/*  Follow Social Media Exthem.es
/*  Youtube : https://www.youtube.com/channel/UCpcZNXk6ySLtwRSBN6fVyLA
/*  Facebook : https://www.facebook.com/groups/exthem.es
/*  Twitter : https://twitter.com/ExThemes
/*  Instagram : https://www.instagram.com/exthemescom/
/*	More Premium Themes Visit Now On https://exthem.es/
/*
/*-----------------------------------------------------------------------------------*/
// Add the Meta Boxes
function add_post_metaboxes() {
    global $post;
    $article_content				= get_post_meta( $post->ID, 'wp_article_content',  true );
    add_meta_box( 'wp_apk_details', 'App Details', 'ex_themes_apk_details_', 'post', 'normal' );
    add_meta_box( 'repeatable-fields', 'Download Link Box ', 'download_link_box', 'post', 'normal' );
    if ($article_content) {
        add_meta_box( 'wp_desc', 'Entry Content', 'apk_entry_content', 'post', 'normal', 'high');
    }
    add_meta_box('versions', __( 'Version', THEMES_NAMES ), 'callback_versions', 'post', 'normal');
}
add_action( 'add_meta_boxes', 'add_post_metaboxes', 0 );

add_action("manage_posts_custom_column",  "wpwm_custom_columns");
add_filter("manage_edit-post_columns", "wpwm_edit_columns");
function wpwm_edit_columns($columns){
    $columns = array_merge(array("poster" => "Poster"), $columns);
    return $columns;
}
function wpwm_custom_columns($column){
    global $post;
    switch ($column) {
        case "poster":
            echo get_the_post_thumbnail( $post->ID, array(100, 100) );
            break;
    }
}

function callback_versions($post) {
    global $opt_themes, $wpdb, $post, $wp_query;
    $search						= get_post_meta( $post->ID, 'wp_title_GP', true );
    $search						= preg_replace('/[^A-Za-z0-9\-]/', ' ', $search);
    $wp_GP_ID					= get_post_meta( $post->ID, 'wp_GP_ID', true );
    $version_gp					= get_post_meta( $post->ID, 'wp_version_GP', true );
    $version_sc					= get_post_meta( get_the_ID(), 'wp_version', true );
//if ( $version_gp === FALSE or $version_gp == '' ) $version_gp = $version_sc;
//    $appname_on					= $opt_themes['ex_themes_title_appname'];
    if (isset($opt_themes['ex_themes_title_appname'])) {
        $appname_on = $opt_themes['ex_themes_title_appname'];
    }
    $title						= get_post_meta( $post->ID, 'wp_title_GP', true );
    $title_alt					= get_the_title();
    if($wp_GP_ID){

        ?>

        <table class="table_s" style="width:100%;">
            <thead>
            <tr>
                <th style="width:50px;" id="poster">Poster</th>
                <th>Version From Playstore</th>
                <th>Title Playstore</th>
                <th>Update Date Post</th>
                <th>Edit Post</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $arg_version = array(
                'post_type'			=> 'post',
                'posts_per_page'	=> -1,
                'meta_key'			=> 'wp_GP_ID',
                'meta_value'		=> $wp_GP_ID,
                'orderby'			=> $version_gp,
                'order'				=> 'DESC',
            );
            $post_version = new WP_Query($arg_version);
            while ( $post_version->have_posts() ) :
                $post_version->the_post();

                $image_id_alt					= get_post_thumbnail_id($post->ID);
                $image_idx						= get_post_thumbnail_id();
                $fullx							= 'post_thumb_version';
                $image_urlx						= wp_get_attachment_image_src($image_idx, $fullx, true);
                $imagex							= $image_urlx[0];
                $version_gp			    		= get_post_meta( $post->ID, 'wp_version_GP', true );
                $version_sc		    			= get_post_meta( get_the_ID(), 'wp_version', true );
                $versionX1 = '';
                if ( $versionX1 === FALSE or $versionX1 == '' ) $versionX1 = $version_sc;

                $mods							= get_post_meta( get_the_ID(), 'wp_mods', true );
                $updates						= get_the_modified_time('F j, Y');
                $updates_app					= get_post_meta( $post->ID, 'wp_updates_GP', true );
                $title_gp						= get_post_meta( $post->ID, 'wp_title_GP', true );
                $sizes							= get_post_meta( $post->ID, 'wp_sizes', true );
                $sizes_alt						= get_post_meta( $post->ID, 'wp_sizes_GP', true );
                if ( $sizes === FALSE or $sizes == '' ) $sizes = $sizes_alt;
                $appname_on				    	= $opt_themes['title_app_name_active_'];
                $title					    	= get_post_meta( $post->ID, 'wp_title_GP', true );
                $title_alt				    	= get_the_title();
                $poster_gp						= get_post_meta( $post->ID, 'wp_poster_GP', true );

                ?>
                <tr>
                    <td id="poster">
                        <img src="<?php if($poster_gp){ echo $poster_gp; } else { echo $imagex; } ?>" width="50px" height="50px" />
                    </td>
                    <td>
                        v.<?php echo $version_gp; ?>
                    </td>
                    <td><?php echo $title_gp; ?></td>
                    <td><?php echo $updates; ?></td>
                    <td>
                        <a href="<?php echo admin_url( 'post.php?action=edit&post='.$post->ID ) ?>">Edit</a>
                    </td>
                </tr>
            <?php
            endwhile;
            wp_reset_query();
            ?>
            </tbody>
        </table>

        <style>.table_s {border-collapse: collapse;}.table_s td, .table_s th {border: 1px solid #ddd;padding: 10px;text-align: left;}.table_s th {border: 0;background: #F2F2F2;}@media (max-width: 400px)  {.table_s #poster {display:none}}</style>

        <?php
    }
}

function apk_entry_content() {
    global $post;
    $article_content				= get_post_meta( $post->ID, 'wp_article_content',  true );
    ?>
    <center>
        <p><strong style="text-transform:capitalize;color:black">Here for Default Contents from <u style="text-transform:uppercase!important;color:#2271b1">Sources</u><br> Please copy this if you want use this contents </strong></p>
    </center>
    <?php wp_editor(($article_content), 'wp_article_content',
        array(
            'textarea_name' => 'wp_article_content',
            'media_buttons' => false,
            'textarea_rows' => 5,
            'tabindex' => 3,
            'tinymce' => array(
                'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp',
            ),
        ));

}

function ex_themes_apk_details_() {
    global $post;
    $sources					= get_post_meta( $post->ID, 'wp_source_url', true );
    $sources_dt					= get_post_meta( $post->ID, 'link' );
    if ($sources_dt && $sources_dt != ""){
        $sources=$sources_dt;
    }

    if(!$sources) $sources		= ' ';
    $plugin_url					= WP_PLUGIN_URL . '/'. str_replace( basename( __FILE__ ), "", plugin_basename(__FILE__) );
    $download					= get_post_meta( $post->ID, 'wp_downloadlink', true );
    $download2					= get_post_meta( $post->ID, 'wp_downloadlink2', true );
    $download3					= get_post_meta( $post->ID, 'wp_downloadlink3', true );
    if(!$download) $download	= ' ';
    $namedownloadlink			= get_post_meta( $post->ID, 'wp_namedownloadlink', true );
    $namedownloadlink2			= get_post_meta( $post->ID, 'wp_namedownloadlink2', true );
    if ( $namedownloadlink === FALSE or $namedownloadlink == '' ) $namedownloadlink = $namedownloadlink2;
    $judul						= get_post_meta( $post->ID, 'wp_title_GP',  true );
    $title_dt                   = get_key_option($post->ID,"app_name");
    if ($title_dt && $title_dt!=""){
        $judul = $title_dt;
    }
    if(!$judul) $judul			= ' ';
    $titleID					= get_post_meta( $post->ID, 'wp_GP_ID', true );
    if(!$titleID) $titleID		= ' ';
    $package_dt                 = get_package($post->ID);
    if ($package_dt && $package_dt!= ""){
        $titleID = $package_dt;
    }
    $titleID2					= get_post_meta( $post->ID, 'wp_GP_ID', true );
    if(!$titleID2) $titleID2	= ' ';
    $developer					= get_post_meta( $post->ID, 'wp_developers_GP', true );
    $developerX					= get_post_meta( $post->ID, 'wp_developers2_GP', true );
    $developer_dt               = get_key_option($post->ID,"author");
    if ($developer_dt && $developer_dt != ""){
        $developer = $developer_dt;
    }
    if ( $developer === FALSE or $developer == '' ) $developer = $developerX;


    $version_web				= get_post_meta( $post->ID, 'wp_version', true );
    $version					= get_post_meta( $post->ID, 'wp_version_GP', true );
    $version_dt               = get_key_option($post->ID,"version");
    if ($version_dt && $version_dt != ""){
        $version_web = $version_dt;
        $version =  $version_dt;
    }
    if ( $version_web === FALSE or $version_web == '' ) $version_web = $version;

    $installs					= get_post_meta( $post->ID, 'wp_installs_GP', true );
    $installsX					= get_post_meta( $post->ID, 'wp_installsapgk', true );
    if ( $installs === FALSE or $installs == '' ) $installs = $installsX;
    $requires					= get_post_meta( $post->ID, 'wp_requires_GP', true );
    $requiresX					= '4.4 and up';
    if ( $requires === FALSE or $requires == '' ) $requires = $requiresX;
    $updates					= get_post_meta( $post->ID, 'wp_updates_GP', true );
    $updatesX					= get_post_meta( $post->ID, 'wp_updateapgk', true );
    $update_date_dt               = get_key_option($post->ID,"last_update");
    if ($update_date_dt && $update_date_dt != ""){
        $updates = $update_date_dt;
    }
    if ( $updates === FALSE or $updates == '' ) $updates = $updatesX;
    $contentrated				= get_post_meta( $post->ID, 'wp_contentrated_GP', true );
    $contentratedX				= get_post_meta( $post->ID, 'wp_contentratingapgk', true );
    if ( $contentrated === FALSE or $contentrated == '' ) $contentrated = $contentratedX;
    $rated						= get_post_meta( $post->ID, 'wp_rated_GP', true );
    $ratedX						= get_post_meta( $post->ID, 'wp_ratedapgk', true );
    if ( $rated === FALSE or $rated == '' ) $rated = $ratedX;
    $ratings					= get_post_meta( $post->ID, 'wp_ratings_GP', true );
    $ratingsX					= get_post_meta( $post->ID, 'wp_ratingsapgk', true );
    if ( $ratings === FALSE or $ratings == '' ) $ratings = $ratingsX;
    $persenapgk					= get_post_meta( $post->ID, 'wp_persen_GP', true );
    $persenapgkX				= mt_rand(990,1925);
    if ( $persenapgk === FALSE or $persenapgk == '' ) $persenapgk = $persenapgkX;
    $whatnews					= get_post_meta( $post->ID, 'wp_whatnews_GP', true );
    if(!$whatnews) $whatnews	= ' ';
    $youtube					= get_post_meta( $post->ID, 'wp_youtube_GP', true );
    $youtubeX					= get_post_meta( $post->ID, 'wp_youtube_GP', true );
    if ( $youtube === FALSE or $youtube == '' ) $youtube = $youtubeX;
    $sizes						= get_post_meta( $post->ID, 'wp_sizes', true );
    $sizesX						= get_post_meta( $post->ID, 'wp_sizes_GP', true );
    $size_dt               = get_key_option($post->ID,"size");
    if ($size_dt && $size_dt != ""){
        $sizes = $size_dt;
    }
    if ( $sizes === FALSE or $sizes == '' ) $sizes = $sizesX;
    $desck						= get_post_meta( $post->ID, 'wp_desck_GP', true );
    $desckX						= get_post_meta( $post->ID, 'wp_desck_GP', true );
    if ( $desck === FALSE or $desck == '' ) $desck = $desckX;
    $modfeatures				= get_post_meta( $post->ID, 'wp_mods', true );
    $postergp					= get_post_meta( $post->ID, 'wp_poster_GP', true );
    $developer_dt               = get_key_option($post->ID,"author");
    if ($developer_dt && $developer_dt != ""){
        $postergp = $developer_dt;
    }
    $gambarX21					= get_post_meta( $post->ID, 'wp_images_GP', true );
    $gambarX212					= get_post_meta( $post->ID, 'wp_images_GP1', true );
    if ( $gambarX21 === FALSE or $gambarX21 == '' ) $gambarX21 = $gambarX212;
    $modfeatures				= get_post_meta( $post->ID, 'wp_mods', true );
    $modfeatures2				= get_post_meta( $post->ID, 'wp_mods2', true );
    $newupdates					= get_post_meta( $post->ID, 'wp_newupdates', true );
    $wp_category_app			= get_post_meta( $post->ID, 'wp_category_app', true );
    $wp_mods_post				= get_post_meta( $post->ID, 'wp_mods_post', true );
    $wp_mods_post2				= get_post_meta( $post->ID, 'wp_mods_post2', true );
    $wp_mods_post3				= get_post_meta( $post->ID, 'wp_mods_post3', true );
    $wp_title_wp_mods			= get_post_meta( $post->ID, 'wp_title_wp_mods', true );
    $wp_title_wp_mods_2			= get_post_meta( $post->ID, 'wp_title_wp_mods_2', true );
    $wp_title_wp_mods_3			= get_post_meta( $post->ID, 'wp_title_wp_mods_3', true );
    $downloadapkxapkpremiers	= get_post_meta( $post->ID, 'wp_downloadapkxapkpremier', true );
    $downloadapkxapkg			= get_post_meta( $post->ID, 'wp_downloadapkxapkg', true );
    if ( $downloadapkxapkpremiers === FALSE or $downloadapkxapkpremiers == '' ) $downloadapkxapkpremiers = $downloadapkxapkg;
    get_template_part( '/libs/addons/assets/css/custom.tooltips' );
    ?>


    <table class="responsive-table">
        <caption>Add Your Details App Informations</caption>

        <tbody>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">Stats <strong>New</strong> or <strong>Updates</strong></b>
                <a class="toggle" href="#news_updates">?</a>
                <p class="toggle-content" id="news_updates" style="display:none;">
                    Example :  <br>
                    type <strong>New</strong> for New post
                    <br>
                    type <strong>Updates</strong> for update post.
                    <br>
                    Or Anything You Wants
                    <br>
                    leave empty if you dont want
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_newupdates" value="<?= $newupdates ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize" >Playstore ID</b>
                <a class="toggle" href="#Playstore">?</a>
                <p class="toggle-content" id="Playstore" style="display:none;">
                    Example :  <br>
                    <strong>com.roblox.client</strong>
                    <br>If You Make It Empty.
                    <br>App Detail Info <strong>Not Showing</strong>
                    <br>All Version <strong>not Showing</strong>
                </p>
            </th>
            <td data-title="Value"><input style="width:98%;" type="text" name="wp_GP_ID" value="<?= $titleID ?>" /></td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">App Name</b>
                <a class="toggle" href="#AppName">?</a>
                <p class="toggle-content" id="AppName" style="display:none;">
                    Example :  <br>
                    <strong>Roblox</strong>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_title_GP" value="<?= $judul ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">apps Google Play</b>
                <a class="toggle" href="#AppsGooglePlay">?</a>
                <p class="toggle-content" id="AppsGooglePlay" style="display:none;">
                    Example :  <br>
                    <strong>https://play.google.com/store/apps/details?id=com.roblox.client</strong>
                    <br>
                    or copy this :   <br>
                    <strong>https://play.google.com/store/apps/details?id=<?= $titleID ?>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_GP_ID2" value="https://play.google.com/store/apps/details?id=<?= $titleID ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">apps Poster</b>
                <a class="toggle" href="#AppsPoster">?</a>
                <p class="toggle-content" id="AppsPoster" style="display:none;">
                    Example :  <br>
                    <strong>https://play-lh.googleusercontent.com/xxxxxx</strong>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_poster_GP" value="<?= $postergp ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">Apps Version From Playstore</b>
                <a class="toggle" href="#AppsVersionPlaystore">?</a>
                <p class="toggle-content" id="AppsVersionPlaystore" style="display:none;">
                    Example :  <br>
                    <strong>2.522.280</strong>
                    <br>Dont Make It <strong>Empty</strong>
                    <br>if Empty All Version <strong>not Showing</strong>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_version_GP" value="<?= $version ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">apps version from Sources</b>
                <a class="toggle" href="#AppsVersionFromSources">?</a>
                <p class="toggle-content" id="AppsVersionFromSources" style="display:none;">
                    Example :  <br>
                    <strong>2.522.280</strong>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_version" value="<?= $version_web ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">OS Required</b>
                <a class="toggle" href="#OSRequired">?</a>
                <p class="toggle-content" id="OSRequired" style="display:none;">
                    Example :  <br>
                    <strong style='color: red;'>4.5</strong>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_requires_GP" value="<?= $requires ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">apps updates</b>
                <a class="toggle" href="#Appsupdates">?</a>
                <p class="toggle-content" id="Appsupdates" style="display:none;">
                    Example :  <br>
                    <strong>April 14, 2022</strong>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_updates_GP" value="<?= $updates ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">apps Size </b>
                <a class="toggle" href="#appsSize">?</a>
                <p class="toggle-content" id="appsSize" style="display:none;">
                    Example :  <br>
                    <strong>250 mb</strong> or <strong>1 gb</strong>
                    <br>
                    Not Format Like This <strong>250</strong>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_sizes" value="<?= $sizes ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <b style="text-transform:capitalize">apps youtube id</b>
                <a class="toggle" href="#appsyoutubeid">?</a>
                <p class="toggle-content" id="appsyoutubeid" style="display:none;">
                    Example :  <br>
                    <strong>T_rkoL9vt3g</strong>
                    <br>
                    Not Format Like This <strong>https://youtube.com/watch?v=03DXtNlUGGg</strong>
                </p>
            </th>
            <td data-title="Value">
                <input style="width:98%"  type="text" name="wp_youtube_GP" value="<?= $youtube ?>" />
            </td>
        </tr>



        </tbody>
    </table>

    <div id='metabox_mdr'>
        <noscript>
	<span style="display:none">



    <b style="text-transform:lowercase"><strong style="text-transform:lowercase;color: blue;">New</strong> or <strong style="text-transform:lowercase;color: blue;">Updates</strong> Or Anything You Wants</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content"> Example : type <strong style='color: red;'>New</strong> for New post or type <strong style='color: red;'>Updates</strong> for update post. leave empty if you dont want
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_newupdates" value="<?= $newupdates ?>" /></p>

    <b style="text-transform:capitalize">Playstore ID</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	 Example : <strong style='color: red;'>com.roblox.client</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_GP_ID" value="<?= $titleID ?>" /></p>

    <b style="text-transform:capitalize">App Name </b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>Roblox</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_title_GP" value="<?= $judul ?>" /></p>

    <b style="text-transform:capitalize;display:none">App Category </b>
    <p style="display:none"><input style="width:98%"  type="text" name="wp_category_app" value="<?= $wp_category_app ?>" /></p>
    <b style="text-transform:capitalize">apps Google Play</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>https://play.google.com/store/apps/details?id=com.roblox.client</strong>
	</span>
	</span>
    <p>or copy this : <strong style='color: #2271b1;'>https://play.google.com/store/apps/details?id=<?= $titleID ?></strong>
    <p><input style="width:98%"  type="text" name="wp_GP_ID2" value="https://play.google.com/store/apps/details?id=<?= $titleID ?>" /></p>

    <b style="text-transform:capitalize">apps Poster</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>https://play-lh.googleusercontent.com/xxxxxx</strong>
	<br>
	Or You Can Upload On Featured Image Box
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_poster_GP" value="<?= $postergp ?>" /></p>

    <b style="text-transform:capitalize">apps version playstore</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>2.522.280</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_version_GP" value="<?= $version ?>" /></p>


    <b style="text-transform:capitalize">apps version mods</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>2.5</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_version" value="<?= $version_web ?>" /></p>

    <b style="text-transform:capitalize;display:none">apps developers</b>
    <p  style="display:none" ><input style="width:98%"  type="text" name="wp_developers_GP" value="<?= $developer ?>" /></p>
    <b style="text-transform:capitalize;display:none">apps installs</b>
    <p style="display:none"><input style="width:98%"  type="text" name="wp_installs_GP" value="<?= $installs ?>" /></p>
    <b style="text-transform:capitalize">OS Required</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>5.0</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_requires_GP" value="<?= $requires ?>" /></p>

    <b style="text-transform:capitalize">apps updates</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>April 14, 2022</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_updates_GP" value="<?= $updates ?>" /></p>

    <b style="text-transform:capitalize;">apps rated</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>4.5</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_rated_GP" value="<?= $rated ?>" /></p>

	<b style="text-transform:capitalize;">apps ratings</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: red;'>28.536.990</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_ratings_GP" value="<?= $ratings ?>" /></p>
    <b style="text-transform:capitalize;display:none">apps persen</b>
    <p style="display:none"><input style="width:98%"  type="text" name="wp_persen_GP" value="<?= $persenapgk ?>" /></p>
    <b style="text-transform:capitalize;display:none">apps content rated</b>
    <p style="display:none"><input style="width:98%"  type="text" name="wp_contentrated_GP" value="<?= $contentrated ?>" /></p>
    <b style="text-transform:capitalize">apps youtube id</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: green;'>T_rkoL9vt3g</strong>
	<br>
	Not Format Like This <strong style='color: red;'>https://youtube.com/watch?v=03DXtNlUGGg</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_youtube_GP" value="<?= $youtube ?>" /></p>

    <b style="text-transform:capitalize">apps Size</b>
	<span class="tooltip">
	<span class="tooltip-point"><i class="mce-ico mce-i-wp_help">&nbsp;</i></span>
	<span class="tooltip-content">
	Example : <strong style='color: green;'>250 mb</strong> or <strong style='color: green;'>1 gb</strong>
	<br>
	Not Format Like This <strong style='color: red;'>250</strong>
	</span>
	</span>
    <p><input style="width:98%"  type="text" name="wp_sizes" value="<?= $sizes ?>" /></p>
    <b style="text-transform:capitalize;display:none">Name download link</b>
    <p style="display:none"><input style="width:98%" type="text" name="wp_namedownloadlink" value="<?= $namedownloadlink ?>" /></p>
    <b style="text-transform:capitalize;display:none">download link</b>
    <p style="display:none"><input style="width:98%" type="text" name="wp_downloadlink" value="<?= $download ?>" /></p>
    <b style="text-transform:capitalize;display:none">sources</b>
    <p style="display:none"><input style="width:98%"  type="text" name="wp_source_url" value="<?= $sources ?>" /></p>

	</span>
        </noscript>


        <b style="text-transform:capitalize">App Desc</b>
        <p><?php wp_editor(  ($desck), 'wp_desck_GP', array('textarea_name' => 'wp_desck_GP', 'textarea_rows' => 5)); ?></p>
        <b style="text-transform:capitalize;"> App what news </b>
        <p><?php wp_editor(  ($whatnews), 'wp_whatnews_GP', array('textarea_name' => 'wp_whatnews_GP', 'textarea_rows' => 5)); ?></p>
        <b style="text-transform:capitalize">mod features 1</b>
        <p><?php wp_editor(  ($modfeatures), 'wp_mods', array('textarea_name' => 'wp_mods', 'textarea_rows' => 5)); ?></p>
        <b style="text-transform:capitalize">mod features 2</b>
        <p><?php wp_editor(  ($modfeatures2), 'wp_mods2', array('textarea_name' => 'wp_mods2', 'textarea_rows' => 5)); ?></p>
    </div>
    <hr>



    <script>
        var show=function(t){t.style.display="block"},hide=function(t){t.style.display="none"},toggle=function(t){"block"!==window.getComputedStyle(t).display?show(t):hide(t)};document.addEventListener("click",function(t){if(t.target.classList.contains("toggle")){t.preventDefault();var e=document.querySelector(t.target.hash);e&&toggle(e)}},!1);
    </script>
<?php }
function callback_download($post){
    $downloadlinks						= get_post_meta($post->ID, 'wp_downloadlink2', true);
    $sizexx								= get_post_meta($post->ID, 'wp_sizes2', true);
    $namedownloadlinks					= get_post_meta($post->ID, 'wp_namedownloadlink2', true);
    $namedownloadlinks					= preg_replace('/\s++/', ' ', $namedownloadlinks);
    $downloadlinks						= !empty($downloadlinks) ? $downloadlinks : array();
    $c									= 3;
    $input_upload						= '';
    wp_nonce_field( 'repeatable_meta_box_downloadlinks', 'repeatable_meta_box_downloadlinks' );
    ?>
    <script>jq1 = jQuery.noConflict();
        jq1(function($) {
            var count = <?php echo $c; ?>;
            $(document).on('click', '.remove-row', function(){
                $(this).parents('p').remove();
                count--;
            });
            $(".addImg").on('click', function(){
                $(".ElementImagenes").append('<p><input type="text" name="downloadlinks['+count+']" value="" class="regular-text upload"><?php echo @$input_upload; ?><a href="javascript:void(0)" class="button remove-row">Remove</a></p>');
                count++;
            });
        });
        jpp2 = jQuery.noConflict();
        jpp2(function($) {
            $('.upload_image_button').on( 'click', function() {
                formfield = $(this).prev('input');
                tb_show('', 'media-upload.php?type=file&amp;TB_iframe=true');
                var oldFunc = window.send_to_editor;
                window.send_to_editor = function(html) {
                    if($(html).attr('src')) {
                        imgurl = $(html).attr('src');
                    } else if ($(html).attr('href')) {
                        imgurl = $(html).attr('href');
                    }
                    formfield.val(imgurl);
                    tb_remove();
                    window.send_to_editor = oldFunc;
                };
                return false;
            });
        });
    </script>
    <?php if ($downloadlinks) { ?>
        <div class="ElementImagenes">
            <div class="download"></div>
            <table id="downloadlinks" width="100%" class="content-table">
                <thead>
                <tr>

                    <th width="30%">Url Names <br>APK / ZIP / OBB</th>
                    <th width="70%">Url Links <br>APK / ZIP / OBB</th>
                </tr>
                </thead>
                <tbody>
                <center>
                    <p><strong style="text-transform:uppercase!important;color:#2271b1">Here for Default Download Links from Sources, you cant add or remove this. <br>but you can copy this link for you insert to download link page</strong></p>
                </center>
                <?php
                $i = 0;
                if(count($downloadlinks)>5){
                    foreach($downloadlinks as $elemento) { ?>
                        <tr>
                            <td><input type="text" name="namedownloadlinks[<?php echo $i; ?>]" value="<?php echo (!empty($namedownloadlinks[$i])) ? $namedownloadlinks[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                            <td><input type="text" name="downloadlinks[<?php echo $i; ?>]" value="<?php echo (!empty($downloadlinks[$i])) ? $downloadlinks[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                        </tr>
                        </tr>
                        <?php $i++; } ?>
                <?php } else {
                    for($i=0;$i<3;$i++): ?>
                        <tr>
                            <td><input type="text" name="namedownloadlinks[<?php echo $i; ?>]" value="<?php echo (!empty($namedownloadlinks[$i])) ? $namedownloadlinks[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                            <td><input type="text" name="downloadlinks[<?php echo $i; ?>]" value="<?php echo (!empty($downloadlinks[$i])) ? $downloadlinks[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                        </tr>
                        </tr>
                    <?php endfor; ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php }
}
function download_link_box() {
    global $post, $wpdb, $gets_data;

    $name_links_dl				= get_post_meta( $post->ID, 'name_links_dl', true );

    $downloadlink_happymood		= get_post_meta( $post->ID, 'wp_downloadlink', true );
    $namedownloadlink_happymood	= get_post_meta( $post->ID, 'wp_namedownloadlink', true );

    $downloadlink_ori			= get_post_meta( $post->ID, 'downloadlink_ori', true );
    $downloadlink_ori_1			= get_post_meta( $post->ID, 'downloadlink_ori_1', true );
    $downloadlink_ori_2			= get_post_meta( $post->ID, 'downloadlink_ori_2', true );
    $name_downloadlinks_ori		= get_post_meta( $post->ID, 'name_downloadlinks_ori', true );
    $name_downloadlinks_ori_1	= get_post_meta( $post->ID, 'name_downloadlinks_ori_1', true );
    $name_downloadlinks_ori_2	= get_post_meta( $post->ID, 'name_downloadlinks_ori_2', true );
    $size_downloadlinks_orig	= get_post_meta( $post->ID, 'size_downloadlinks_orig', true );
    $size_downloadlinks_orig_1	= get_post_meta( $post->ID, 'size_downloadlinks_orig_1', true );
    $size_downloadlinks_orig_2	= get_post_meta( $post->ID, 'size_downloadlinks_orig_2', true );


    $downloadlink_gma			= get_post_meta( $post->ID, 'downloadlink_gma', true );
    $downloadlink_gma_1			= get_post_meta( $post->ID, 'downloadlink_gma_1', true );
    $downloadlink_gma_2			= get_post_meta( $post->ID, 'downloadlink_gma_2', true );
    $downloadlink_gma_3			= get_post_meta( $post->ID, 'downloadlink_gma_3', true );
    $downloadlink_gma_4			= get_post_meta( $post->ID, 'downloadlink_gma_4', true );
    $downloadlink_gma_5			= get_post_meta( $post->ID, 'downloadlink_gma_5', true );


    $namedownloadlink_gma		= get_post_meta( $post->ID, 'name_downloadlinks_gma', true );
    $namedownloadlink_gma		= preg_replace('/\s+\s+/', ' ', $namedownloadlink_gma);
    $namedownloadlink_gma_1		= get_post_meta( $post->ID, 'name_downloadlinks_gma_1', true );
    $namedownloadlink_gma_1		= preg_replace('/\s+\s+/', ' ', $namedownloadlink_gma_1);
    $namedownloadlink_gma_2 	= get_post_meta( $post->ID, 'name_downloadlinks_gma_2', true );
    $namedownloadlink_gma_2		= preg_replace('/\s+\s+/', ' ', $namedownloadlink_gma_2);
    $namedownloadlink_gma_3 	= get_post_meta( $post->ID, 'name_downloadlinks_gma_3', true );
    $namedownloadlink_gma_3		= preg_replace('/\s+\s+/', ' ', $namedownloadlink_gma_3);
    $namedownloadlink_gma_4		= get_post_meta( $post->ID, 'name_downloadlinks_gma_4', true );
    $namedownloadlink_gma_4		= preg_replace('/\s+\s+/', ' ', $namedownloadlink_gma_4);
    $namedownloadlink_gma_5		= get_post_meta( $post->ID, 'name_downloadlinks_gma_5', true );
    $namedownloadlink_gma_5		= preg_replace('/\s+\s+/', ' ', $namedownloadlink_gma_5);


    $sizedownloadlink_gma		= get_post_meta( $post->ID, 'size_downloadlinks_gma', true );
    $sizedownloadlink_gma_1		= get_post_meta( $post->ID, 'size_downloadlinks_gma_1', true );
    $sizedownloadlink_gma_2		= get_post_meta( $post->ID, 'size_downloadlinks_gma_2', true );
    $sizedownloadlink_gma_3		= get_post_meta( $post->ID, 'size_downloadlinks_gma_3', true );
    $sizedownloadlink_gma_4		= get_post_meta( $post->ID, 'size_downloadlinks_gma_4', true );
    $sizedownloadlink_gma_5		= get_post_meta( $post->ID, 'size_downloadlinks_gma_5', true );


    $downloadapkpremiers		= get_post_meta( $post->ID, 'wp_downloadapkxapkpremier', true );
    $downloadapkxapkg			= get_post_meta( $post->ID, 'wp_downloadapkxapkg', true );
    if ( $downloadapkpremiers === FALSE or $downloadapkpremiers == '' ) $downloadapkpremiers = $downloadapkxapkg;
    $c							= 4;

    $repeatable_fields			= get_post_meta($post->ID, 'repeatable_fields', true);
    $downloadlinks				= get_post_meta($post->ID, 'wp_downloadlink2', true);
    $downloadlinksZ1			= get_post_meta( $post->ID, 'wp_downloadlink', true );
    if ( $downloadlinks === FALSE or $downloadlinks == '' ) $downloadlinks = $downloadlinksZ1;
    $downloadlinks				= !empty($downloadlinks) ? $downloadlinks : array();


    $link_download_apksupport	= get_post_meta( $post->ID, 'link_download_apksupport', true );
    $name_download_apksupport	= get_post_meta( $post->ID, 'name_download_apksupport', true );
    $size_download_apksupport	= get_post_meta( $post->ID, 'size_download_apksupport', true );
    $type_download_apksupport	= get_post_meta( $post->ID, 'type_download_apksupport', true );

    $downloadlinks				= get_post_meta($post->ID, 'wp_downloadlink2', true);
    $sizexx						= get_post_meta($post->ID, 'wp_sizedownloadlink2', true);
    $sizexx_alt					= get_post_meta($post->ID, 'wp_sizes2', true);
    if ( $sizexx === FALSE or $sizexx == '' ) $sizexx = $sizexx_alt;
    $namedownloadlinks			= get_post_meta($post->ID, 'wp_namedownloadlink2', true);
    $namedownloadlinks			= preg_replace('/\s++/', ' ', $namedownloadlinks);
    $downloadlinks				= !empty($downloadlinks) ? $downloadlinks : array();
    $typexx						= get_post_meta($post->ID, 'wp_typedownloadlink2', true);

    wp_nonce_field( 'repeatable_meta_box_nonce', 'repeatable_meta_box_nonce' );
    get_template_part( '/libs/addons/assets/css/custom.table' );
    ?>

    <?php
    global $post, $opt_themes;
    $no_jquery			= $opt_themes['no_jquery_post'];
    if ($no_jquery) {
        ?>
        <script>
            var $exhemes_devs = jQuery.noConflict();
        </script>
    <?php } ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){$('.metabox_submit').click(function(e){e.preventDefault();$('#publish').click();});$('#add-row').on('click',function(){var row=$('.empty-row.screen-reader-text').clone(true);row.removeClass('empty-row screen-reader-text');row.insertBefore('#repeatable-fieldset-one tbody>tr:last');return false;});$('.remove-row').on('click',function(){$(this).parents('tr').remove();return false;});$('#repeatable-fieldset-one tbody').sortable({opacity:0.6,revert:true,cursor:'move',handle:'.sort'});});
    </script>

    <style>
        .content-table{border-collapse:collapse;margin:25px 0;font-size:0.9em;width:100%;border-radius:5px 5px 0 0;overflow:hidden;box-shadow:0 0 20px rgba(0,0,0,0.15)}.content-table thead tr{background-color:#2271b1;color:#ffffff;text-align:left;font-weight:bold;text-align:center}.content-table th,.content-table td{padding:12px 10px}.content-table tbody tr{border-bottom:1px solid #dddddd}.content-table tbody tr:nth-of-type(even){background-color:#f3f3f3}.content-table tbody tr:last-of-type{border-bottom:2px solid #2271b1}.content-table tbody tr.active-row{font-weight:bold;color:#2271b1}@media only screen and (max-width:800px){#no-table table,#no-table thead,#no-table tbody,#no-table th,#no-table td,#no-table tr{display:block}#no-table thead tr{position:absolute;top:-9999px;left:-9999px}#no-table tr{border:1px solid #ccc}#no-table td{border:none;border-bottom:1px solid #eee;position:relative;padding-left:50%;white-space:normal;text-align:left}#no-table td:before{position:absolute;top:25px;left:10px;width:25%;padding-right:10px;white-space:nowrap;text-align:left;font-weight:bold}#no-table td:before{content:attr(data-title)}input.meta_image_url{width:95%!important}.field_right{float:left;margin-top:20px}}
    </style>

    <?php if ($downloadlink_happymood) { ?>
        <div id="no-table">
            <table class="content-table">
                <center>
                    <p><strong style="text-transform:capitalize;color:black">Here for Default Download Links from Sources <u style="text-transform:uppercase!important;color:#2271b1">Happymood</u><br> Please copy this link to download box<br> if you not copy the link.. download link use this for default link....</strong></p>
                </center>
                <thead>
                <tr>
                    <th width="35%">Name</th>
                    <th width="65%">Link</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($downloadlink_happymood) { ?>
                    <tr>
                        <td data-title="Name" name="name_download_happymood"><input type="text" class="widefat " name="name_download_happymood" value="<?php echo $namedownloadlink_happymood; ?>" /></td>
                        <td data-title="Link" name="link_download_happymood"><input style="width:100%" type="text" name="link_download_happymood" value="<?php echo $downloadlink_happymood; ?>" /> </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>
        </div>
    <?php } ?>
    <?php if ($link_download_apksupport) { ?>
        <div id="no-table">
            <table class="content-table">
                <center>
                    <p><strong style="text-transform:capitalize;color:black">Here for Default Download Links from Sources <u style="text-transform:uppercase!important;color:#2271b1">Google Play Store</u><br> Please copy this link to download box<br> if you not copy the link.. download link use this for default link....</strong></p>
                </center>
                <thead>
                <tr>
                    <th width="0%" style='display:none'>Type</th>
                    <th width="5%" class="more_info">Size <div class="popup">APK / ZIP / OBB</div></th>
                    <th width="30%" class="more_info">Name <div class="popup">APK / ZIP / OBB</div></th>
                    <th width="65%" class="more_info">Link <div class="popup">APK / ZIP / OBB</div></th>
                </tr>
                </thead>
                <tbody>

                <?php if ($link_download_apksupport) { ?>
                    <tr>
                        <td name="type_download_apksupport" style='display:none' ><input type="text" class="widefat " name="type_download_apksupport" value="<?php echo $type_download_apksupport; ?>" /></td>

                        <td name="size_download_apksupport"><input type="text" class="widefat " name="size_download_apksupport" value="<?php echo $size_download_apksupport; ?>" /></td>

                        <td name="name_download_apksupport"><input type="text" class="widefat " name="name_download_apksupport" value="<?php echo $name_download_apksupport; ?>" /></td>

                        <td name="link_download_apksupport"><input style="width:100%" type="text" name="link_download_apksupport" value="<?php echo $link_download_apksupport; ?>" /> </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    <?php } ?>

    <?php if ($downloadlink_ori) { ?>
        <div id="no-table">
            <table class="content-table">
                <center>
                    <p><strong style="text-transform:capitalize;color:black">Here for Default Download Links from Sources <u style="text-transform:uppercase!important;color:#2271b1">Google Play Store</u><br> Please copy this link to download box<br> if you not copy the link.. download link not showing...</strong></p>
                </center>
                <thead>
                <tr>
                    <th width="5%" class="more_info">Size <div class="popup">APK / ZIP / OBB</div></th>
                    <th width="30%" class="more_info">Name <div class="popup">APK / ZIP / OBB</div></th>
                    <th width="65%" class="more_info">Link <div class="popup">APK / ZIP / OBB</div></th>
                </tr>
                </thead>
                <tbody>

                <?php if ($downloadlink_ori) { ?>
                    <tr>
                        <td name="size_downloadlinks_orig"><?php echo $size_downloadlinks_orig; ?></td>
                        <td name="name_downloadlinks_ori"><?php echo $name_downloadlinks_ori; ?></td>
                        <td name="downloadlink_ori"><input style="width:100%" type="text" name="downloadlink_ori" value="<?php echo $downloadlink_ori; ?>" /> </td>
                    </tr>
                <?php } ?>
                <?php if ($downloadlink_ori_1) { ?>
                    <tr>
                        <td name="size_downloadlinks_orig_1"><?php echo $size_downloadlinks_orig_1; ?></td>
                        <td name="name_downloadlinks_ori_1"><?php echo $name_downloadlinks_ori_1; ?></td>
                        <td name="downloadlink_ori_1"><input style="width:100%" type="text" name="downloadlink_ori_1" value="<?php echo $downloadlink_ori_1; ?>" /> </td>
                    </tr>
                <?php } ?>
                <?php if ($downloadlink_ori_2) { ?>
                    <tr>
                        <td name="size_downloadlinks_orig_2"><?php echo $size_downloadlinks_orig_2; ?></td>
                        <td name="name_downloadlinks_ori_2"><?php echo $name_downloadlinks_ori_2; ?></td>
                        <td name="downloadlink_ori_2"><input style="width:100%" type="text" name="downloadlink_ori_2" value="<?php echo $downloadlink_ori_2; ?>" /> </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <?php if ($downloadlink_gma) { ?>
        <div id="no-table">
            <table class="content-table">
                <center>
                    <p><strong style="text-transform:capitalize;color:black">Here for Default Download Links from <u style="text-transform:uppercase!important;color:#2271b1">Sources </u><br> Please copy this link to download box<br> if you not copy the link.. download link from <u style="text-transform:uppercase!important;color:#2271b1">Sources</u> not showing...</strong></p>
                </center>
                <thead>
                <tr>
                    <?php if ($sizedownloadlink_gma) { ?><th width="15%">Size</th> <?php } ?>
                    <th width="30%">Name</th>
                    <th width="65%">Link</th>
                </tr>
                </thead>
                <tbody>

                <?php if ($downloadlink_gma) { ?>
                    <tr>
                        <?php if ($sizedownloadlink_gma) { ?><td data-title="Size" name="size_downloadlinks_gma"><input title="<?php echo $sizedownloadlink_gma; ?>" type="text" class="widefat " name="size_downloadlinks_gma" value="<?php echo $sizedownloadlink_gma; ?>" /></td><?php } ?>
                        <td data-title="Name" name="name_downloadlinks_gma"><input title="<?php echo $namedownloadlink_gma; ?>" type="text" class="widefat " name="name_downloadlinks_gma" value="<?php echo $namedownloadlink_gma; ?>" /></td>
                        <td data-title="Link" name="downloadlink_gma"><input style="width:100%" type="text" name="downloadlink_gma" value="<?php echo $downloadlink_gma; ?>" /></td>
                    </tr>
                <?php } if ($downloadlink_gma_1) { ?>
                    <tr>
                        <?php if ($sizedownloadlink_gma_1) { ?><td data-title="Size" name="size_downloadlinks_gma_1"><input type="text" class="widefat " name="size_downloadlinks_gma_1" value="<?php echo $sizedownloadlink_gma_1; ?>" title="<?php echo $sizedownloadlink_gma_1; ?>"  /></td> <?php } ?>
                        <td data-title="Name" name="name_downloadlinks_gma_1"><input type="text" class="widefat " name="name_downloadlinks_gma_1" value="<?php echo $namedownloadlink_gma_1; ?>" title="<?php echo $namedownloadlink_gma_1; ?>"  /></td>
                        <td data-title="Link" name="downloadlink_gma_1"><input style="width:100%" type="text" name="downloadlink_gma_1" value="<?php echo $downloadlink_gma_1; ?>" /> </td>
                    </tr>
                <?php } if ($downloadlink_gma_2) { ?>
                    <tr>
                        <?php if ($sizedownloadlink_gma_2) { ?><td data-title="Size" name="size_downloadlinks_gma_2"><input type="text" class="widefat " name="size_downloadlinks_gma_2" value="<?php echo $sizedownloadlink_gma_2; ?>" title="<?php echo $sizedownloadlink_gma_2; ?>"  /></td> <?php } ?>
                        <td data-title="Name" name="name_downloadlinks_gma_2"><input type="text" class="widefat " name="name_downloadlinks_gma_2" value="<?php echo $namedownloadlink_gma_2; ?>" title="<?php echo $namedownloadlink_gma_2; ?>"  /></td>
                        <td data-title="Link" name="downloadlink_gma_2"><input style="width:100%" type="text" name="downloadlink_gma_2" value="<?php echo $downloadlink_gma_2; ?>" /> </td>
                    </tr>
                <?php } if ($downloadlink_gma_3) { ?>
                    <tr>
                        <?php if ($sizedownloadlink_gma_3) { ?><td data-title="Size" name="size_downloadlinks_gma_3"><input type="text" class="widefat " name="size_downloadlinks_gma_3" value="<?php echo $sizedownloadlink_gma_3; ?>" title="<?php echo $sizedownloadlink_gma_3; ?>"  /></td> <?php } ?>
                        <td data-title="Name" name="name_downloadlinks_gma_3"><input type="text" class="widefat " name="name_downloadlinks_gma_3" value="<?php echo $namedownloadlink_gma_3; ?>" title="<?php echo $namedownloadlink_gma_3; ?>"  /></td>
                        <td data-title="Link" name="downloadlink_gma_3"><input style="width:100%" type="text" name="downloadlink_gma_3" value="<?php echo $downloadlink_gma_3; ?>" /> </td>
                    </tr>
                <?php } if ($downloadlink_gma_4) { ?>
                    <tr>
                        <?php if ($sizedownloadlink_gma_4) { ?><td data-title="Size" name="size_downloadlinks_gma_4"><input type="text" class="widefat " name="size_downloadlinks_gma_4" value="<?php echo $sizedownloadlink_gma_4; ?>" title="<?php echo $sizedownloadlink_gma_4; ?>"  /></td> <?php } ?>
                        <td data-title="Name" name="name_downloadlinks_gma_4"><input type="text" class="widefat " name="name_downloadlinks_gma_4" value="<?php echo $namedownloadlink_gma_4; ?>" title="<?php echo $namedownloadlink_gma_4; ?>"  /></td>
                        <td data-title="Link" name="downloadlink_gma_4"><input style="width:100%" type="text" name="downloadlink_gma_4" value="<?php echo $downloadlink_gma_4; ?>" /> </td>
                    </tr>
                <?php } if ($downloadlink_gma_5) { ?>
                    <tr>
                        <?php if ($sizedownloadlink_gma_5) { ?><td data-title="Size" name="size_downloadlinks_gma_5"><input type="text" class="widefat " name="size_downloadlinks_gma_5" value="<?php echo $sizedownloadlink_gma_5; ?>" title="<?php echo $sizedownloadlink_gma_5; ?>"  /></td> <?php } ?>
                        <td data-title="Name" name="name_downloadlinks_gma_5"><input type="text" class="widefat " name="name_downloadlinks_gma_5" value="<?php echo $namedownloadlink_gma_5; ?>" title="<?php echo $namedownloadlink_gma_5; ?>"  /></td>
                        <td data-title="Link" name="downloadlink_gma_5"><input style="width:100%" type="text" name="downloadlink_gma_5" value="<?php echo $downloadlink_gma_5; ?>" /> </td>
                    </tr>
                <?php } if ($downloadapkxapkpremiers) { ?>
                    <tr class="active-row">
                        <td name="size_apkxapkpremiers">n/a</td>
                        <td name="name_apkxapkpremiers">Google Apis</td>
                        <td name="wp_downloadapkxapkpremier"><input style="width:100%" type="text" name="wp_downloadapkxapkpremier" value="<?= $downloadapkxapkpremiers ?>" /></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>

    <?php } if ($downloadlinks) { ?>
        <script>
            jq1=jQuery.noConflict();jq1(function($){var count=<?php echo $c;?>;$(document).on('click','.remove-row',function(){$(this).parents('p').remove();count--;});$(".addImg").on('click',function(){$(".ElementImagenes").append('<p><input type="text" name="downloadlinks['+count+']" value="" class="regular-text upload"><?php echo @$input_upload; ?><a href="javascript:void(0)" class="button remove-row">Remove</a></p>');count++;});});jpp2=jQuery.noConflict();jpp2(function($){$('.upload_image_button').on('click',function(){formfield=$(this).prev('input');tb_show('','media-upload.php?type=file&amp;TB_iframe=true');var oldFunc=window.send_to_editor;window.send_to_editor=function(html){if($(html).attr('src')){imgurl=$(html).attr('src');}else if($(html).attr('href')){imgurl=$(html).attr('href');}formfield.val(imgurl);tb_remove();window.send_to_editor=oldFunc;};return false;});});
        </script>
        <div class="ElementImagenes">
            <div class="download"></div>
            <div id="no-table">
                <table id="downloadlinks" width="100%"  class="content-table">
                    <thead>
                    <tr>
                        <?php if ($typexx) { ?>
                            <th width="15%" class="more_info">Tipes <div class="popup">APK / ZIP / OBB</div></th>
                        <?php } if ($sizexx) { ?>
                            <th width="15%" class="more_info">Sizes <div class="popup">APK / ZIP / OBB</div></th>
                        <?php } ?>
                        <th width="30%" class="more_info">Url Names <div class="popup">APK / ZIP / OBB</div></th>
                        <th width="40%" class="more_info">Url Links <div class="popup">APK / ZIP / OBB</div></th>
                    </tr>
                    </thead>
                    <tbody>
                    <center><p><strong style="text-transform:uppercase!important;color:#2271b1">Here for Default Download Links from Sources, you can't add or remove this. <br>but you can copy this link for you insert to download link page</strong></p></center>
                    <?php
                    $i		= 0;
                    if($downloadlinks) {
                        foreach($downloadlinks as $elemento) { ?>
                            <tr>
                                <?php if ($typexx) { ?>
                                    <td data-title="Tipes" ><input type="text" name="tipes[<?php echo $i; ?>]" value="<?php echo (!empty($typexx[$i])) ? $typexx[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" /><?php echo $input_upload; ?></td>
                                <?php } if ($sizexx) { ?>
                                    <td data-title="Sizes"><input type="text" name="sizes[<?php echo $i; ?>]" value="<?php echo (!empty($sizexx[$i])) ? $sizexx[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                                <?php } ?>
                                <td data-title="Url Names"><input type="text" name="namedownloadlinks[<?php echo $i; ?>]" value="<?php echo (!empty($namedownloadlinks[$i])) ? $namedownloadlinks[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                                <td data-title="Url Links"><input type="text" name="downloadlinks[<?php echo $i; ?>]" value="<?php echo (!empty($downloadlinks[$i])) ? $downloadlinks[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                            </tr>
                            <?php $i++; } } else {
                        for($i=0;$i<3;$i++): ?>
                            <tr>
                                <?php if ($typexx) { ?>
                                    <td><input type="text" name="tipes[<?php echo $i; ?>]" value="<?php echo (!empty($typexx[$i])) ? $typexx[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" /><?php echo $input_upload; ?></td>
                                <?php } if ($sizexx) { ?>
                                    <td><input type="text" name="sizes[<?php echo $i; ?>]" value="<?php echo (!empty($sizexx[$i])) ? $sizexx[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                                <?php } ?>
                                <td><input type="text" name="namedownloadlinks[<?php echo $i; ?>]" value="<?php echo (!empty($namedownloadlinks[$i])) ? $namedownloadlinks[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                                <td><input type="text" name="downloadlinks[<?php echo $i; ?>]" value="<?php echo (!empty($downloadlinks[$i])) ? $downloadlinks[$i] : ''; ?>" id="imagenes<?php echo $i; ?>"  class="widefat" ><?php echo $input_upload; ?></td>
                            </tr>
                        <?php endfor; ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    <?php } ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.metabox_submit').click(function(e) {
                e.preventDefault();
                $('#publish').click();
            });
            $('#add-row').on('click', function() {
                var row = $('.empty-row.screen-reader-text').clone(true);
                row.removeClass('empty-row screen-reader-text');
                row.insertBefore('#repeatable-fieldset-one tbody>tr:last');
                return false;
            });
            $('.remove-row').on('click', function() {
                $(this).parents('tr').remove();
                return false;
            });
            $('#repeatable-fieldset-one tbody').sortable({
                opacity: 0.6,
                revert: true,
                cursor: 'move',
                handle: '.sort'
            });
        });
    </script>

    <center>
        <p><strong style="text-transform:uppercase!important;color:#2271b1">Here for Your can Insert Link Download<br>you can add, edit or remove your link</strong></p>
    </center>

    <div id="no-table">
        <table id="repeatable-fieldset-one" width="100%" class="content-table">
            <thead>
            <tr>
                <th width="15%" class="more_info">Sizes <div class="popup">APK / ZIP / OBB</div></th>
                <th width="30%" class="more_info">Url Names <div class="popup">APK / ZIP / OBB</div></th>
                <th width="40%" class="more_info">Url Links <div class="popup">APK / ZIP / OBB</div></th>
                <th width="5%">Remove </th>
            </tr>
            </thead>
            <tbody id="sortable">
            <?php
            if ( $repeatable_fields ) :
                foreach ( $repeatable_fields as $field ) {
                    ?>
                    <tr>
                        <td data-title="Sizes"><input type="text" class="widefat exthemes_dlbutton_item_size" name="sizes1[]" value="<?php if($field['sizes1'] != '') echo esc_attr( $field['sizes1'] ); ?>" /></td>
                        <td data-title="Url Names"><input type="text" class="widefat exthemes_dlbutton_item_title" name="name[]" value="<?php if($field['name'] != '') echo esc_attr( $field['name'] ); ?>" /></td>
                        <td data-title="Url Links"><input type="text" class="widefat exthemes_dlbutton_item_url" name="url[]" value="<?php if ($field['url'] != '') echo esc_attr( $field['url'] ); else echo 'http://'; ?>" /></td>
                        <td data-title="Remove"><a class="button remove-row" href="#">Remove</a></td>
                    </tr>
                <?php }
            else :
                // show a blank one
                ?>
                <tr>

                    <td data-title="Sizes"><input type="text" class="widefat exthemes_dlbutton_item_size" name="sizes1[]"  /></td>
                    <td data-title="Url Names"><input type="text" class="widefat exthemes_dlbutton_item_title" name="name[]"  /></td>
                    <td data-title="Url Links"><input type="text" class="widefat exthemes_dlbutton_item_url" name="url[]" /></td>
                    <td data-title="Remove"><a class="button remove-row" href="#">Remove</a></td>
                </tr>
            <?php endif; ?>
            <!-- empty hidden one for jQuery -->
            <tr class="empty-row screen-reader-text">
                <td data-title="Sizes"><input type="text" class="widefat exthemes_dlbutton_item_size" name="sizes1[]" /></td>
                <td data-title="Url Names"><input type="text" class="widefat exthemes_dlbutton_item_title" name="name[]" /></td>
                <td data-title="Url Links"><input type="text" class="widefat exthemes_dlbutton_item_url" name="url[]"  /></td>
                <td data-title="Remove"><a class="button remove-row" href="#">Remove</a></td>
            </tr>
            </tbody>
        </table>
        <p><a id="add-row" class="button" href="#">Add New Url Link</a>
            <input type="submit" class="button metabox_submit" value="Save" />
        </p>
    </div>

    <style>
        .popup {display:none}
    </style>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.js"></script>

    <script>
        $("#sortable").sortable({stop:function(){$("#sortable").find("input").bind('mousedown.ui-disableSelection selectstart.ui-disableSelection',function(e){e.stopImmediatePropagation();});}}).disableSelection();$("#sortable").find("input").bind('mousedown.ui-disableSelection selectstart.ui-disableSelection',function(e){e.stopImmediatePropagation();});
    </script>

<?php }
add_action('save_post', 'repeatable_meta_box_save');
function repeatable_meta_box_save($post_id) {
    if ( ! isset( $_POST['repeatable_meta_box_nonce'] ) ||
        ! wp_verify_nonce( $_POST['repeatable_meta_box_nonce'], 'repeatable_meta_box_nonce' ) )
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;
    $old							= get_post_meta($post_id, 'repeatable_fields', true);
    $new							= array();
    $tipes							= $_POST['tipes'];
    $sizes1							= $_POST['sizes1'];
    $names							= $_POST['name'];
    $urls							= $_POST['url'];
    $count							= count( $names );
    for ( $i = 0; $i < $count; $i++ ) {
        if ( $names[$i] != '' ) :
            $new[$i]['name'] = stripslashes( strip_tags( $names[$i] ) );
            if ( $tipes[$i] != '' )
                $new[$i]['tipes'] = stripslashes( strip_tags( $tipes[$i] ) );
            if ( $sizes1[$i] != '' )
                $new[$i]['sizes1'] = stripslashes( strip_tags( $sizes1[$i] ) );
            if ( $urls[$i] == 'https://' )
                $new[$i]['url'] = '';
            else
                $new[$i]['url'] = stripslashes( $urls[$i] ); // and however you want to sanitize
        endif;
    }
    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'repeatable_fields', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'repeatable_fields', $old );
}
#################################### <a class="button remove-row" href="#">Remove</a>
function callback_imagenes($post){
    $gambarX21						= get_post_meta( $post->ID, 'wp_images_GP', true );
    $gambarX212						= get_post_meta( $post->ID, 'wp_images_GP1', true );
    //$datos_imagenes = ''.$gambarX21.''.$gambarX212.'';
    //$datos_imagenes = get_post_meta($post->ID, 'wp_images_GP', true);
    $datos_imagenes					= $gambarX21;
    if ( $datos_imagenes === FALSE or $datos_imagenes == '' ) $datos_imagenes = $gambarX212;


    $datos_imagenes					= !empty($datos_imagenes) ? $datos_imagenes : array();
    $i								= 2;
    $input_upload					= '<input class="gambarbaru upload_image_button button" type="button" value="Upload">';
    ?>
    <script>jq1 = jQuery.noConflict();
        jq1(function($) {
            var count = <?php echo $i; ?>;
            $(document).on('click', '.hapusgambar', function(){
                $(this).parents('p').remove();
                count--;
            });
            $(".tambahgambar").on('click', function(){
                $(".kolomgambar").append('<p><input type="text" name="datos_imagenes['+count+']" value="" class="regular-text upload"><?php echo @$input_upload; ?><a href="javascript:void(0)" class="button hapusgambar">Remove</a></p>');
                count++;
            });
        });
        jpp2 = jQuery.noConflict();
        jpp2(function($) {
            $('.gambarbaru').on( 'click', function() {
                formfield = $(this).prev('input');
                tb_show('', 'media-upload.php?type=file&amp;TB_iframe=true');
                var oldFunc = window.send_to_editor;
                window.send_to_editor = function(html) {
                    if($(html).attr('src')) {
                        imgurl = $(html).attr('src');
                    } else if ($(html).attr('href')) {
                        imgurl = $(html).attr('href');
                    }
                    formfield.val(imgurl);
                    tb_remove();
                    window.send_to_editor = oldFunc;
                };
                return false;
            });
        });
    </script>
    <div class="kolomgambar"  >
        <div class="download"></div>
        <center>
            <p><strong style="text-transform:uppercase!important;color:#2271b1">Here for Default Screenshoots Poster from Googleplay, you cant add or remove this</strong></p>
        </center>
        <table style="width:100%;">
            <thead>
            <tr style="text-align:left;">
                <th style="width:100%;">Link Source Images Poster from Googleplay</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $limiteds = 4;
            $i = 0;
            foreach ($datos_imagenes as $elemento) {
                $i++;
                if ( $i <= $limiteds ) { ?>
                    <tr>
                        <td><input type="text" name="datos_imagenes[<?php echo $i; ?>]" value="<?php echo (!empty($datos_imagenes[$i])) ? $datos_imagenes[$i] : ''; ?>" id="imagenes<?php echo $i; ?>" class="regular-text upload"><?php echo $input_upload; ?><a href="#" class="button hapusgambar">Remove</a></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td><input type="text" name="datos_imagenes[<?php echo $i; ?>]" value="<?php echo (!empty($datos_imagenes[$i])) ? $datos_imagenes[$i] : ''; ?>" id="imagenes<?php echo $i; ?>" class="regular-text upload"><?php echo $input_upload; ?><a href="#" class="button hapusgambar">Remove</a></td>
                    </tr>
                <?php } } ?>


            </tbody>
        </table>
    </div>
    <p class="tambahgambar button" ><b>+ Add Images</b></p>
<?php }
add_action( 'save_post', 'cd_quote_meta_save' );
function cd_quote_meta_save( $id ) {
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if(!wp_verify_nonce( @$_POST['dynamicMeta_noncename'], plugin_basename( __FILE__ ))){
        return;
    }
    if( !current_user_can( 'edit_post', $id ) ) return;
    $allowed = array(
        'p'	=> array()
    );
    if($_POST['datos_informacion'])
        update_post_meta($id, "datos_informacion", $_POST['datos_informacion']);
    if($_POST['datos_video'])
        update_post_meta($id, "datos_video", $_POST['datos_video']);
    if($_POST['datos_imagenes'])
        update_post_meta($id, "datos_imagenes", $_POST['datos_imagenes']);
    if($_POST['datos_download'])
        update_post_meta($id, "datos_download", $_POST['datos_download']);
    if(isset($_POST['custom_boxes']))
        update_post_meta($id, "custom_boxes", $_POST['custom_boxes']);
    if( isset($_POST['new_rating_users'])  || isset($_POST['new_rating_average']) ) {
        update_post_meta($id, "new_rating_users", $_POST['new_rating_users']);
        update_post_meta($id, "new_rating_average", $_POST['new_rating_average']);
        update_post_meta($id, "new_rating_count", ceil($_POST['new_rating_users'] * $_POST['new_rating_average']));
    }
}

/* When the post is saved, saves our custom data */
function wpwm_save_postdata( $post_id ) {
    // First we need to check if the current user is authorised to do this action.
    if ( 'page' == @$_REQUEST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return;
    }
    $wpwm_meta = array('wp_version_GP', 'wp_version', 'link_download_apksupport','name_download_apksupport','size_download_apksupport','type_download_apksupport','wp_download_original_ps', 'wp_downloadapkxapkpremier_link', 'wp_category_app', 'wp_downloadapkxapkg','wp_downloadapkxapkpremier','wp_title_wp_mods_3', 'wp_title_wp_mods_2', 'wp_title_wp_mods', 'wp_mods2', 'wp_newupdates', 'wp_mods2', 'wp_namedownloadlink', 'wp_size_GP', 'wp_GP_ID', 'wp_sizes', 'wp_source_url', 'wp_downloadlink', 'wp_title', 'wp_GP_ID2', 'wp_title_id', 'wp_title_id2', 'wp_developers', 'wp_developers2', 'wp_contentrated', 'wp_installs','wp_requires', 'wp_updates', 'wp_ratings', 'wp_rated', 'wp_whatnews', 'wp_gambar1', 'wp_gambar2', 'wp_gambar3', 'wp_gambar4', 'wp_gambar5', 'wp_gambar6', 'wp_youtube', 'wp_desck', 'wp_desckapgk', 'wp_desckapgk', 'wp_contentratingapgk', 'wp_updateapgk', 'wp_requiresapgk', 'wp_installsapgk', 'wp_developersapgk', 'wp_versionapgk', 'wp_judulapgk', 'wp_persenapgk', 'wp_modfeatures', 'wp_modfeatures3','wp_images_GP',  'wp_mods', 'wp_desck_GP',  'wp_youtube_GP', 'wp_whatnews_GP', 'wp_persen_GP', 'wp_ratings_GP', 'wp_rated_GP', 'wp_contentrated_GP', 'wp_updates_GP', 'wp_requires_GP', 'wp_installs_GP', 'wp_version_GP', 'wp_developers2_GP', 'wp_developers_GP', 'wp_title_GP', 'wp_GP_ID', 'wp_poster_GP',  );
    //if saving in a custom table, get post_ID
    $post_ID = @$_POST['post_ID'];
    foreach ($wpwm_meta as $meta_key) {

        if(isset($_POST[$meta_key])){
            if ($meta_key == "wp_version_GP" || $meta_key == "wp_version"){
                $has_key = get_key_option($post_ID,"version");
                if ($has_key){
                    update_key_option($post_ID, "version", $_POST[$meta_key]);

                }else{
                    update_post_meta($post_ID, $meta_key, $_POST[$meta_key]);}
            }elseif ($meta_key == "wp_sizes" || $meta_key == "wp_size_GP"){
                $has_key = get_key_option($post_ID,"size");
                if ($has_key){
                    update_key_option($post_ID, "size", $_POST[$meta_key]);

                }else{
                    update_post_meta($post_ID, $meta_key, $_POST[$meta_key]);}
            }elseif ($meta_key == "wp_title_GP" || $meta_key == "wp_title"){
                $has_key = get_key_option($post_ID,"app_name");
                if ($has_key){
                    update_key_option($post_ID, "app_name", $_POST[$meta_key]);

                }else{
                    update_post_meta($post_ID, $meta_key, $_POST[$meta_key]);}
            }elseif ($meta_key == "wp_updates_GP" || $meta_key == "wp_updates"){
                $has_key = get_key_option($post_ID,"last_update");
                if ($has_key){
                    update_key_option($post_ID, "last_update", $_POST[$meta_key]);

                }else{
                    update_post_meta($post_ID, $meta_key, $_POST[$meta_key]);}
            }elseif ($meta_key == "wp_poster_GP"){
                $has_key = get_key_option($post_ID,"author");
                if ($has_key){
                    update_key_option($post_ID, "author", $_POST[$meta_key]);

                }else{
                    update_post_meta($post_ID, $meta_key, $_POST[$meta_key]);}
            }else{
                update_post_meta($post_ID, $meta_key, $_POST[$meta_key]);}
        }
    }
}
add_action( 'save_post', 'wpwm_save_postdata' );