<?php

/*
	Pexels: Free Stock Photos
	https://raajtram.com/plugins/pexels/
	Author: Raaj Trambadia (https://raajtram.com)
*/

/* add the menu */

add_action('admin_menu', 'pexels_fsp_images_add_settings_menu');
function pexels_fsp_images_add_settings_menu() {
    add_options_page(__('Pexels: Free Stock Photos', 'pexels_fsp_images'), __('Pexels Photos', 'pexels_fsp_images'), 'manage_options', 'pexels_fsp_images_settings', 'pexels_fsp_images_settings_page');
    add_action('admin_init', 'register_pexels_fsp_images_options');
}

/* register the options */

function register_pexels_fsp_images_options(){
    register_setting('pexels_fsp_images_options', 'pexels_fsp_images_options', 'pexels_fsp_images_options_validate');
    add_settings_section('pexels_fsp_images_options_section', '', '', 'pexels_fsp_images_settings');
    add_settings_field('attribution-id', __('Attribution', 'pexels_fsp_images'), 'pexels_fsp_images_render_attribution', 'pexels_fsp_images_settings', 'pexels_fsp_images_options_section');
}

/* attribution field */

function pexels_fsp_images_render_attribution(){
    $options = get_option('pexels_fsp_images_options');
    echo '<label><input name="pexels_fsp_images_options[attribution]" value="true" type="checkbox"'.(!$options['attribution'] | $options['attribution']=='true'?' checked="checked"':'').'> '.__('Automatically insert image captions with attribution.', 'pexels_fsp_images').'</label>';
}

/* HTML for the settings page */

function pexels_fsp_images_settings_page() { ?>
    <div class="wrap">
    <h2><?= _e('Pexels: Free Stock Photos Images', 'pexels_fsp_images'); ?></h2>
    <form method="post" action="options.php">
        <?php
            settings_fields('pexels_fsp_images_options');
            do_settings_sections('pexels_fsp_images_settings');
            submit_button();
        ?>
    </form>
    <hr style="margin-bottom:20px">
    <p>
        Photos provided by <a href="https://pexels.com/?utm_source=wordpress-plugin&utm_medium=settings-page" target="_blank" rel="noopener nofollow"><img src="<?= plugin_dir_url(__FILE__).'img/pexels-logo.png' ?>" style="margin:0 3px;position:relative;top:4px" width="80"></a>. Plugin developed and maintained by <a href="https://raajtram.com/?utm_source=pexels-wp-plugin&utm_medium=settings-page">@raajtram</a>.
    </p>
    <p>
        If this plugin helped you, you can show your appreciation by <a href="https://wordpress.org/support/plugin/wp-pexels-free-stock-photos/reviews/#new-post" target="_blank" rel="noopener nofollow">leaving a review</a>.
    </p>
    </div>
<?php }

/* validate settings */

function pexels_fsp_images_options_validate($input){
    global $pexels_fsp_images_gallery_languages;
    $options = get_option('pexels_fsp_images_options');
    if ($input['attribution']) $options['attribution'] = 'true'; else $options['attribution'] = 'false';
    return $options;
}
?>
