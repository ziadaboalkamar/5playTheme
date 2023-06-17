<?php
// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) { ?>
    <p class="nocomments">This post is password protected. Enter the password to view comments.</p>
    <?php
    return;
}


/**
 * Modify the "must_log_in" string of the comment form.
 *
 * @see http://wordpress.stackexchange.com/a/170492/26350
 */
add_filter( 'comment_form_defaults', function( $fields ) {
    $fields['must_log_in'] = sprintf( 
        __( 'You must <a href="%s">Register</a> or <a href="%s">Login</a> to post a comment.'),
        wp_registration_url(),
        wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )   
    );
    return $fields;
}); 

function next_comments_links( $label = '', $max_page = 0 ) { 
if(get_next_comments_links()){
echo get_next_comments_links( $label, $max_page ); } else { ?>
<span class="page_prev" title="back">
<span><svg width="24" height="24"><use xlink:href="#i__arrowright"></use></svg><span class="sr-only">back</span></span>
</span>
<?php }}

function previous_comments_links( $label = '', $max_page = 0 ) {
if(get_previous_comments_links()){
echo get_previous_comments_links( $label, $max_page ); } else { ?>
<span class="page_prev" title="back">
<span><svg width="24" height="24"><use xlink:href="#i__arrowleft"></use></svg><span class="sr-only">back</span></span>
</span>
<?php }}

function get_next_comments_links( $label = '', $max_page = 0 ) {
	global $wp_query;
	if ( ! is_singular() ) {
		return;
	}
	$page = get_query_var( 'cpage' );
	if ( ! $page ) {
		$page = 1;
	}
	$next_page = (int) $page + 1;
	if ( empty( $max_page ) ) {
		$max_page = $wp_query->max_num_comment_pages;
	}
	if ( empty( $max_page ) ) {
		$max_page = get_comment_pages_count();
	}
	if ( $next_page > $max_page ) {
		return;
	}
	if ( empty( $label ) ) {
		$label = __( '<svg width="24" height="24"><use xlink:href="#i__arrowright"></use></svg><span class="sr-only">next</span ' );
	} 
	$attr = apply_filters( 'next_comments_link_attributes', '' );
	return sprintf(
		'<span class="page_next" title="next"><a href="%1$s" %2$s>%3$s</a></span>',
		esc_url( get_comments_pagenum_link( $next_page, $max_page ) ),
		$attr,
		preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label )
	);
}
function get_previous_comments_links( $label = '' ) {
	if ( ! is_singular() ) {
		return;
	}
	$page = get_query_var( 'cpage' );
	if ( (int) $page <= 1 ) {
		return;
	}
	$previous_page = (int) $page - 1;
	if ( empty( $label ) ) {
		$label = __( '<svg width="24" height="24"><use xlink:href="#i__arrowleft"></use></svg><span class="sr-only">back</span>' );
	} 
	$attr = apply_filters( 'previous_comments_link_attributes', '' );
	return sprintf(
		'<span class="page_prev" title="back"><a href="%1$s" %2$s>%3$s</a></span>',
		esc_url( get_comments_pagenum_link( $previous_page ) ),
		$attr,
		preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label )
	);
}


?>
 

<?php

function play5__comments($comment, $args, $depth) {
// ~~~~~~~~~~~~~~~~~~~~~ EX_THEMES ~~~~~~~~~~~~~~~~~~~~~~~~ \\  
$GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment212 ';
    } else {
        $tag = 'li';
        $add_below = 'div-comment121 ';
    }
global $comment, $current_user;  
$user_id   = get_comment(get_comment_ID())->user_id;
// ~~~~~~~~~~~~~~~~~~~~~ EX_THEMES ~~~~~~~~~~~~~~~~~~~~~~~~ \\  
if( $comment->comment_parent ) { ?>
<ol class="comments-tree-list">
<li id="comments-tree-item-<?php comment_ID() ?>" class="comments-tree-item">
<div id="comment-id-<?php comment_ID() ?>">
<div class="comment <?php if ($user_id) { $user_info = get_userdata($user_id ); ?>pos-comm<?php } else { ?><?php } ?> guest-view-com">
<div class="comment-head">
<?php
	$user = wp_get_current_user(); 
	if ( $user ) : ?>
	<div class="avatar-status"><i class="avatar fit-cover"><img src="<?php echo get_avatar_url( $comment->comment_author_email, 25 ); ?>" alt="Guest Dark" loading="lazy"></i></div>
	<?php endif; ?>

	
<span class="name truncate"><a ><?php echo strip_tags(get_comment_author()) ?></a></span>
<div class="comment-meta">
<span class="group-label <?php if ($user_id) { $user_info = get_userdata($user_id ); ?>g-adm<?php } else { ?>g-guest<?php } ?>"><?php if ($user_id) { $user_info = get_userdata($user_id ); echo implode(' ', $user_info->roles); } else { ?>Guests<?php  } ?></span>
<time class="date c-muted" datetime="<?php printf(  __( '%1$sT%2$s', '5play' ), get_comment_date(), get_comment_time() ); ?>" style="color: #8a949d !important;"><?php printf(  __( '%1$s', '5play' ), get_comment_date(), get_comment_time() ); ?> <?php edit_comment_link(__('(Edit)'),'  ','') ?></time>
</div>
</div>
<div class="comment-text">
<div id="comm-id-<?php comment_ID() ?>"><?php comment_text(); ?></div>
</div>
<div class="comment-foot">
<ul class="comment-tools">
<li class="com__reply"> <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'] ))); ?>  </li> 
</ul>
   
</div>
</div>
</div> 
<?php } else { ?>
<li id="comments-tree-item-<?php comment_ID() ?>" class="comments-tree-item">
<div id="comment-id-<?php comment_ID() ?>">
<div class="comment <?php if ($user_id) { $user_info = get_userdata($user_id ); ?>pos-comm<?php } else { ?><?php } ?> guest-view-com">
<div class="comment-head">
<?php
$user = wp_get_current_user(); 
if ( $user ) : ?>
	<div class="avatar-status"><i class="avatar fit-cover"><img src="<?php echo get_avatar_url( $comment->comment_author_email, 25 ); ?>" alt="Guest Dark" loading="lazy"></i></div>
<?php endif; ?>

	
<span class="name truncate"><a ><?php echo strip_tags(get_comment_author()) ?></a></span>
<div class="comment-meta">
<span class="group-label <?php if ($user_id) { $user_info = get_userdata($user_id ); ?>g-adm<?php } else { ?>g-guest<?php } ?>"><?php if ($user_id) { $user_info = get_userdata($user_id ); echo implode(' ', $user_info->roles); } else { ?>Guests<?php  } ?></span>
<time class="date c-muted" datetime="<?php printf(  __( '%1$sT%2$s', '5play' ), get_comment_date(), get_comment_time() ); ?>" style="color: #8a949d !important;"><?php printf(  __( '%1$s', '5play' ), get_comment_date(), get_comment_time() ); ?> <?php edit_comment_link(__('(Edit)'),'  ','') ?></time>
</div>
</div>
<div class="comment-text">
<div id="comm-id-<?php comment_ID() ?>"><?php comment_text(); ?></div>
</div>
<div class="comment-foot">
<ul class="comment-tools">
 <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'] ))); ?>
</ul>
 
</div>
</div>
</div> 
<?php }
}

?>
 

<div class="block b-comments">
		 
		<div class="b-head">
        <h3 class="section-title"><i class="s-purple c-icon"><svg width="24" height="24"><use xlink:href="#i__coms"></use></svg></i><?php if(get_comments_number( )) { ?><?php comments_number('0', '1', '%'); ?><?php global $opt_themes; if($opt_themes['exthemes_comment_Comments']) { ?> <?php echo $opt_themes['exthemes_comment_Comments']; ?><?php } ?><?php } else { ?>No <?php global $opt_themes; if($opt_themes['exthemes_comment_Comments']) { ?> <?php echo $opt_themes['exthemes_comment_Comments']; ?><?php } ?><?php } ?> </h3>
        <a href="#addcom-block" class="btn s-green btn-all anchor"><span><?php global $opt_themes; if($opt_themes['exthemes_comment_Comment_on']) { ?><?php echo $opt_themes['exthemes_comment_Comment_on']; ?><?php } ?></span><svg width="24" height="24"><use xlink:href="#i__keyright"></use></svg></a>
		</div>
	 
		<div class="b-cont">
		<div id="dle-ajax-comments"></div>
		<div id="comment"></div>
		<ol class="comments-tree-list">
		<?php
			wp_list_comments( array(
				'short_ping'	=> true, 
				'callback'		=> 'play5__comments'
			) );
		?>
		
		</ol> 
		<?php if( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
		<div class="dle-comments-navigation">
		<div class="navigation"> 
		 
		<?php
		echo previous_comments_links();
		$pages = paginate_comments_links(['echo' => false, 'type' => 'array', 'prev_text' => '&laquo;', 'next_text' => '&raquo;']);

		if( is_array( $pages ) ) {
        $output = '';
        foreach ($pages as $page) {
		$page = $page;
		if (strpos($page, ' current') !== false) 
		$page = str_replace([' current', ''], ['', '<span>'], $page);
		$output .= $page;
        }
		 
        ?>
		<div class="pages">
        <nav class="pages-list">         
		<?php echo $output; ?>         
        </nav>
		</div>
		<?php } 
		echo next_comments_links();
		?>
		
		</div>
		</div>
		<?php } ?>
		</div>
</div>


<?php if ( comments_open()) { ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" name="dle-comments-form" id="dle-comments-form"> 

<div class="anchor-line"><span id="addcom-block"></span></div>
<div id="addcomment" class="block b-add-comments ignore-select">
<div class="b-head">
<h3 class="section-title"><i class="s-purple c-icon"><svg width="24" height="24"><use xlink:href="#i__addcom"></use></svg></i><?php global $opt_themes; if($opt_themes['exthemes_comment_Comments']) { ?> <?php echo $opt_themes['exthemes_comment_Comments']; ?><?php } ?></h3>
</div>

<div class="b-cont">
<?php 
add_filter( 'comment_form_defaults', 'my_comment_form_defaults' );
/**
 * Customize the text prior to the comment form fields.
 * @param  array $defaults
 * @return $defaults
 */
function my_comment_form_defaults( $defaults ) {

    $defaults['comment_notes_before'] = '<div class="form-group" id="comment-editor"><label class="c-muted" for="comments">'. __( 'Yours email address will not be published. Required fields are marked *', THEMES_NAMES ).'</label></div>';

    return $defaults;

}
add_filter( 'comment_form_default_fields', 'wc_comment_form_change_cookies' );
function wc_comment_form_change_cookies( $fields ) {
	$commenter = wp_get_current_commenter();

	$consent   = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

	$fields['cookies'] = '<div class="form-group" id="comment-editor">' . '<label class="c-muted" for="comments"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />&nbsp;&nbsp;'.__('Save my name, email, and website in this browser for the next time I comment.', THEMES_NAMES).'</label></div>';
	return $fields;
}
$comment_args = array(
	'title_reply'		=> '',
    'fields'			=> apply_filters('comment_form_default_fields', array(
	'author'			=> '<div class="form-combo">'.($req ? ' ' : '').'<div class="form-group"><input  placeholder=" Your Name " class="form-control" id="author" name="author" type="text" value="'. esc_attr($commenter['comment_author']).'"  rows="5"  /></div>',
	'email'				=> ($req ? ' ' : '').'<div class="form-group"><input placeholder=" Your Email " class="form-control" id="email" name="email" type="text" value="'.esc_attr($commenter['comment_author_email']).'"  rows="5"  /></div></div>',
	'url'				=> '')),
    'comment_field'		=> '<!--<label for="comment">'.__('Let us know what you have to say:') . '</label>-->'.'<div class="form-group" id="comment-editor"><div class="bb-editor"><textarea placeholder="Your Comment" name="comment" id="comment" class="apkmody materialize-textarea has-very-light-gray-background-color comment-form-input no-border" rows="5"></textarea></div></div>',
	'submit_button'		=> '<div class="form-submit"><button name="submit" type="submit" id="submit" class="btn btn-block s-green"  >Send</button></div>',
	//'submit_field'         => ' ',
    'comment_notes_after' => ' ',
); 
comment_form($comment_args); 
//comment_form(); 
 
?>
</div>

</div>
</form>
<?php } 

