<?php
/**
 * This file is used to markup the meta box aspects of the plugin.
 *
 * @since      1.0.0
 * @package    ipanorama
 * @subpackage ipanorama/admin/partials
 */
?>

<?php 
	$post = get_post();
	$id = $post->ID;
	$slug = $post->post_name;
?>

<p><?php echo __('To use this iPanorama item in your posts or pages use the following shortcode:', 'ipanorama'); ?></p>
<p><code>[ipanorama id="<?php echo $id; ?>"]</code><?php ($slug ? 'or' : '') ?></p>
<?php if ( $slug ) { ?>
<p><code>[ipanorama slug="<?php echo $slug; ?>"]</code></p>
<?php } ?>