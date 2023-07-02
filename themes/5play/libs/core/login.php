<?php
/*-----------------------------------------------------------------------------------*/
/*  EXTHEM.ES
/*  PREMIUM WORDRESS THEMES
/*
/*  STOP DON'T TRY EDIT
/*  IF YOU DON'T KNOW PHP
/*  AS ERRORS IN YOUR THEMES ARE NOT THE RESPONSIBILITY OF THE DEVELOPERS
/*
/*  As Errors In Your Themes
/*  Are Not The Responsibility
/*  Of The DEVELOPERS
/*  @EXTHEM.ES
/*-----------------------------------------------------------------------------------*/ 
// silences is goldens
error_reporting(SALAH);
function subscriber_login_redirect( $redirect_to, $request, $user ) { 
    $URL            = home_url('/');
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'subscriber', $user->roles )) {
             $redirect_to = $URL;
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'subscriber_login_redirect', 10, 3 );


add_action( "admin_enqueue_scripts", "ayecode_enqueue" );
function ayecode_enqueue( $hook ){
	// Load scripts only on the profile page.
	if( $hook === 'profile.php' || $hook === 'user-edit.php' ){
		add_thickbox();
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_media();
	}
}

function ayecode_admin_media_scripts() {
	?>
	<script>
		jQuery(document).ready(function ($) {
			$(document).on('click', '.avatar-image-upload', function (e) {
				e.preventDefault();
				var $button = $(this);
				var file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select or Upload an Custom Avatar',
					library: {
						type: 'image' // mime type
					},
					button: {
						text: 'Select Avatar'
					},
					multiple: false
				});
				file_frame.on('select', function() {
					var attachment = file_frame.state().get('selection').first().toJSON();
					$button.siblings('#ayecode-custom-avatar').val( attachment.sizes.thumbnail.url );
					$button.siblings('.custom-avatar-preview').attr( 'src', attachment.sizes.thumbnail.url );
				});
				file_frame.open();
			});
		});
	</script>
	<?php
}
add_action( 'admin_print_footer_scripts-profile.php', 'ayecode_admin_media_scripts' );
add_action( 'admin_print_footer_scripts-user-edit.php', 'ayecode_admin_media_scripts' );
// 3. Adding the Custom Image section for avatar.
function custom_user_profile_fields( $profileuser ) {
	?>
	<h3><?php _e('Upload your avatar', 'ayecode'); ?></h3>
	<table class="form-table ayecode-avatar-upload-options">
		<tr>
			 
			<td>
				<?php
				// Check whether we saved the custom avatar, else return the default avatar.
				$custom_avatar = get_the_author_meta( 'ayecode-custom-avatar', $profileuser->ID );
				if ( $custom_avatar == '' ){
					$custom_avatar = get_avatar_url( $profileuser->ID );
				}else{
					$custom_avatar = esc_url_raw( $custom_avatar );
				}
				?>
				<img style="width: 96px; height: 96px; display: block; margin-bottom: 15px;" class="custom-avatar-preview" src="<?php echo $custom_avatar; ?>">
				<input type="text" name="ayecode-custom-avatar" id="ayecode-custom-avatar" value="<?php echo esc_attr( esc_url_raw( get_the_author_meta( 'ayecode-custom-avatar', $profileuser->ID ) ) ); ?>" class="regular-text" />
				<input type='button' class="avatar-image-upload button-primary" value="<?php esc_attr_e("Upload Image","ayecode");?>" id="uploadimage"/><br />
				<span class="description">
					<?php _e('Please upload a custom avatar for your profile, to remove the avatar simple delete the URL and click update.', 'ayecode'); ?>
				</span>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'custom_user_profile_fields', 10, 1 );
//add_action( 'edit_user_profile', 'custom_user_profile_fields', 10, 1 );

// 4. Saving the values.
add_action( 'personal_options_update', 'ayecode_save_local_avatar_fields' );
add_action( 'edit_user_profile_update', 'ayecode_save_local_avatar_fields' );
function ayecode_save_local_avatar_fields( $user_id ) {
	if ( current_user_can( 'edit_user', $user_id ) ) {
		if( isset($_POST[ 'ayecode-custom-avatar' ]) ){
			$avatar = esc_url_raw( $_POST[ 'ayecode-custom-avatar' ] );
			update_user_meta( $user_id, 'ayecode-custom-avatar', $avatar );
		}
	}
}

// 5. Set the uploaded image as default gravatar.
add_filter( 'get_avatar_url', 'ayecode_get_avatar_url', 10, 3 );
function ayecode_get_avatar_url( $url, $id_or_email, $args ) {
	$id = '';
	if ( is_numeric( $id_or_email ) ) {
		$id = (int) $id_or_email;
	} elseif ( is_object( $id_or_email ) ) {
		if ( ! empty( $id_or_email->user_id ) ) {
			$id = (int) $id_or_email->user_id;
		}
	} else {
		$user = get_user_by( 'email', $id_or_email );
		$id = !empty( $user ) ?  $user->data->ID : '';
	}
	//Preparing for the launch.
	$custom_url = $id ?  get_user_meta( $id, 'ayecode-custom-avatar', true ) : '';
	
	// If there is no custom avatar set, return the normal one.
	if( $custom_url == '' || !empty($args['force_default'])) {
		return esc_url_raw( get_bloginfo('template_directory').'/assets/img/default-profile.png' ); 
	}else{
		return esc_url_raw($custom_url);
	}
}

 
/*
change author/username base to users/userID
https://wordpress.stackexchange.com/a/13343
*/
function change_author_permalinks() {
	global $wp_rewrite, $opt_themes;
	$link_base_activate        	= $opt_themes['author_link_base_activate'];
	$link_base        			= $opt_themes['author_link_base'];
	// Change the value of the author permalink base to whatever you want here
	if($link_base_activate){
	$wp_rewrite->author_base 	= $link_base;
	$wp_rewrite->flush_rules();
	}
}
global $opt_themes;
$link_base_activate        = $opt_themes['author_link_base_activate'];
if(!$link_base_activate){
add_action('init','change_author_permalinks');
}

function users_query_vars($vars) {
    // add lid to the valid list of variables
    $new_vars		= array('users');
    $vars			= $new_vars + $vars;
    return $vars;
}
add_filter('query_vars', 'users_query_vars');

function user_rewrite_rules( $wp_rewrite ) {
  $newrules 					= array();
  $new_rules['users/(\d*)$'] 	= 'index.php?author=$matches[1]';
  $wp_rewrite->rules 			= $new_rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules','user_rewrite_rules');