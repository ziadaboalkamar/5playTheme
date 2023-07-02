<!--<div class="pld-like-wrap  pld-common-wrap"></div>--> 

    <a href="<?php echo esc_attr($href); ?>" class="pld-like-trigger pld-like-dislike-trigger <?php echo ($already_liked == 1) ? 'pld-prevent' : ''; ?> <?php echo ($already_liked_type == 'like') ? 'pld-undo-like-trigger pld-undo-trigger' : ''; ?>" title="<?php global $opt_themes; if($opt_themes['exthemes_Like']) { echo $opt_themes['exthemes_Like']; } ?>" data-post-id="<?php echo intval($post_id); ?>" data-trigger-type="like" data-restriction="<?php echo esc_attr($pld_settings['basic_settings']['like_dislike_resistriction']); ?>" data-already-liked="<?php echo esc_attr($already_liked); ?>">
        <?php
        $template = $pld_settings['design_settings']['template'];
        switch ($template) {
            case 'template-1':
        ?>
        <span class="like-plus">
			<svg width="24" height="24"><use xlink:href="#i__thumbup"/></svg> +
			<span class="ignore-select pld-like-count-wrap pld-count-wrap" <?php global $opt_themes; if($opt_themes['ex_themes_rtl_activate_']) { ?> <?php } ?>>
				<?php 
                global $opt_themes; 
                if($opt_themes['ex_themes_rtl_activate_']) {
                echo RTL_Nums(esc_html($like_count));
                } else {
                echo esc_html($like_count);
                }
                ?>
			</span>
		</span>
            <?php
                break;
            case 'template-2':
            ?>
                <i class="fas fa-heart"></i>
            <?php
                break;
            case 'template-3':
            ?>
                <i class="fas fa-check"></i>
            <?php
                break;
            case 'template-4':
            ?>
                <i class="far fa-smile"></i>
                <?php
                break;
            case 'custom':
                if ($pld_settings['design_settings']['like_icon'] != '') {
                ?>
                    <img src="<?php echo esc_url($pld_settings['design_settings']['like_icon']); ?>" alt="<?php echo esc_attr($like_title); ?>" />
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
        do_action('pld_like_template', $pld_settings);
        ?>
        
		<span class="sr-only">
			<?php 
            global $opt_themes; if($opt_themes['exthemes_Like']) { echo $opt_themes['exthemes_Like']; } 
            ?>
		</span>
    </a>