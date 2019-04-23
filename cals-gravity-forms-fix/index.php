<?php
/**
 * Plugin Name: Gravity Forms Logged In Fix
 * Plugin URI: http://cals.wisc.edu
 * Description: CSS Override to show Gravity Forms Submit button when logged in.
 * Version: 1.0
 * Author: Al Nemec
 * Author URI: http://cals.wisc.edu
 */




add_action('wp_footer', 'gravity_forms_submit_reveal');
function gravity_forms_submit_reveal() { ?>
    <style>
        body.admin-bar input[type="submit"] {
            position: relative !important;
            left: 0em;
            opacity: 1;
        }
    </style>

<?php }




