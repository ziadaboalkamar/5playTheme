<!--<div class="pld-dislike-wrap  pld-common-wrap"></div>-->

    <a href="<?php echo esc_attr($href); ?>" class="pld-dislike-trigger pld-like-dislike-trigger <?php echo ($already_liked == 1) ? 'pld-prevent' : ''; ?> <?php echo ($already_liked_type == 'dislike') ? 'pld-undo-dislike-trigger pld-undo-trigger' : ''; ?>" title="<?php global $opt_themes; if($opt_themes['exthemes_Dislike']) { echo $opt_themes['exthemes_Dislike']; } ?>" data-post-id="<?php echo intval($post_id); ?>" data-trigger-type="dislike" data-restriction="<?php echo esc_attr($pld_settings['basic_settings']['like_dislike_resistriction']); ?>" data-already-liked="<?php echo esc_attr($already_liked); ?>">
        <?php
        $template = esc_attr($pld_settings['design_settings']['template']);
        switch ($template) {
            case 'template-1':
        ?>
         <span class="like-minus">
			<svg width="24" height="24"><use xlink:href="#i__thumbdown"/></svg> -
			<span  class="ignore-select pld-like-count-wrap pld-count-wrap" <?php global $opt_themes; if($opt_themes['ex_themes_rtl_activate_']) { ?> <?php } ?>>
				<?php 
                global $opt_themes; 
                if($opt_themes['ex_themes_rtl_activate_']) {
                echo RTL_Nums(esc_html($dislike_count));
                } else {
                echo esc_html($dislike_count);
                } 
                ?>
			</span>
		</span>
            <?php
                break;
            case 'template-2':
            ?>
                <i class="fa fa-heartbeat"></i>
            <?php
                break;
            case 'template-3':
            ?>
                <i class="fas fa-times"></i>
            <?php
                break;
            case 'template-4':
            ?>
                <i class="far fa-frown"></i>
                <?php
                break;
            case 'custom':
                if ($pld_settings['design_settings']['dislike_icon'] != '') {
                ?>
                    <img src="<?php echo esc_url($pld_settings['design_settings']['dislike_icon']); ?>" alt="<?php echo esc_attr($dislike_title); ?>" />
        <?php
                }
                break;
        }
        /**
         * Fires when template is being loaded
         *
         * @param array $pld_settings
         *
         * @since 1.0.0
         */
        do_action('pld_dislike_template', $pld_settings);
        ?>
        <span class="sr-only">
            <?php global $opt_themes; if($opt_themes['exthemes_Dislike']) { echo $opt_themes['exthemes_Dislike']; } ?>
        </span>
    </a>
  