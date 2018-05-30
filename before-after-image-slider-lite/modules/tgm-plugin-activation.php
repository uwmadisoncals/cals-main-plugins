<?php

if (!defined('ABSPATH')) {
    die("Don't touch this.");
}

add_action('tgmpa_register', 'moewe_studio_register_required_plugins');

if (!function_exists('moewe_studio_register_required_plugins')) {
    function moewe_studio_register_required_plugins() {
        $plugins = array(
            array(
                'name'     => 'FavPress',
                'slug'     => 'favpress',
                'source'   => 'https://apps.moewe.io/favpress/stable/favpress.zip',
                'required' => false
            )
        );

        $config = array(
            'id'           => 'before-after-image-slider-lite-tgm',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',
            'menu'         => 'tgmpa-install-plugins',
            'parent_slug'  => 'themes.php',
            'capability'   => 'edit_theme_options',
            'has_notices'  => true,
            'dismissable'  => true,
            'dismiss_msg'  => '',
            'is_automatic' => true,
            'message'      => ''
        );
        tgmpa($plugins, $config);
    }
}