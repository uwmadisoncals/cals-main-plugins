<?php
/**
 * Provide a documentation area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    ipanorama
 * @subpackage ipanorama/admin/partials
 */
?>

<?php 
$path = plugin_dir_url( dirname(__FILE__) ) . 'doc';
?>

<div class="avrm-doc-wrap">
	<div class="avrm-doc-text-center">
		<div class="avrm-doc-navbar">
			<a href="#about">About</a>
			<a href="#usage">Usage</a>
			<a href="#source-and-credits">Credits</a>
		</div>
	</div>
	
	<div class="avrm-doc-promo avrm-doc-quote">
		<p>Thank you for using our software. If you have any questions that are beyond the scope of this help file, you've found a bug, need new feature, or you just want me to show your website using this plugin, please feel free to email on <a href="mailto:avirtum@gmail.com">avirtum@gmail.com</a> or via <a href="http://codecanyon.net/user/avirtum" target="_blank">contact form</a> on codecanyon.</p>
		<p><b>Max Lawrence</b><br>CEO & Co-Founder, <a href="http://avirtum.com">Avirtum</a></p>
	</div>
	
	<div id="about" class="avrm-doc-section">
		<div class="avrm-doc-section-title">
			<h2>About the plugin</h2>
		</div>
		<div class="avrm-doc-section-data">
		<p><b>iPanorama 360</b> is a lightweight and rich-feature panorama viewer for the web. It has virtual tours which allowed the user to navigate from one scene to another. Also, it supports hotspots for providing information about any part of the scene, uses it's own tooltip system, so you can enrich it with text, images, video and other online media. Use this plugin to create interactive tours, maps and presentations.</p>
			<p class="avrm-doc-text-center">
				<img class="avrm-doc-img" src="<?php echo $path; ?>/screenshot-01.jpg" alt="" height="200px">
				<img class="avrm-doc-img" src="<?php echo $path; ?>/screenshot-02.jpg" alt="" height="200px">
				<img class="avrm-doc-img" src="<?php echo $path; ?>/screenshot-03.jpg" alt="" height="200px">
			</p>
		</div>
	</div>
	
	
	<div id="usage" class="avrm-doc-section">
		<div class="avrm-doc-section-title">
			<h2>Usage</h2>
		</div>
		<div class="avrm-doc-section-data">
			<p>This section will cover just the WordPress approach.</p>
			
			<h3>Builder</h3>
			<p>You should use the builder to create the iPanorama item. Go to admin menu <code>iPanorama -> Add New</code> and you will see an editing web tool that allows you to quickly and easily make a config for the new item. What you see is what you get! This builder is intuitive and a pleasure to use. It allows you to save custom configs and use it in the future via shortcodes.</p>
			
			<h3>Shortcode</h3>
			<p>Use the <mark>ipanorama</mark> shortcode to insert the iPanorama item into the Page or Post content. Look at its structure that is composed as follows:</p>
			
<pre class="prettyprint">
[ipanorama 
	id = "5"
	slug = "mypanorama"
	class = "myclass"
	width = "100%"
	height = "240px"
]
</pre>
			<h3>Parameters</h3>
			<dl class="avrm-doc-dl-parameters avrm-doc-clearfix">
				<dt><code>id</code></dt>
				<dd><p>This is the only <b>required parameter</b> and it gets automatically populated with the ID of the iPanorama item. If left blank this instance of the iPanorama will show an error message.</p></dd>
				<dt><code>slug</code></dt>
				<dd><p>Instead of the <code>id</code> parameter, you can provide a short name to the iPanorama item through this parameter.</p></dd>
				<dt><code>class</code></dt>
				<dd><p>The class parameter specifies the additinal CSS class of a view.</p></dd>
				<dt><code>width</code></dt>
				<dd><p>The width parameter specifies the width of a view. If the width parameter is not set, the view takes config from the iPanorama item.</p><p>The value can be any valid CSS property <code>(auto|value[px,cm,%,etc]|initial|inherit)</code></p></dd>
				<dt><code>height</code></dt>
				<dd><p>The height parameter specifies the height of a view. If the height parameter is not set, the view takes config from the iPanorama item.</p><p>The value can be any valid CSS property <code>(auto|value[px,cm,%,etc]|initial|inherit)</code></p></dd>
			</dl>
		</div>
	</div>
	
	<div id="source-and-credits" class="avrm-doc-section">
		<div class="avrm-doc-section-title">
			<h2>Sources and Credits</h2>
		</div>
		<div class="avrm-doc-section-data">
			<p>I've used the following libraries:</p>
			<ul>
				<li>jQuery - <a href="http://www.jquery.com">http://www.jquery.com</a></li>
				<li>Three.js - <a href="https://github.com/mrdoob/three.js">https://github.com/mrdoob/three.js</a></li>
				<li>Effect.Less - <a href="https://github.com/MaxLawrence/Effect.less">https://github.com/MaxLawrence/Effect.less</a></li>
				<li>Angular - <a href="https://github.com/angular/angular.js">https://github.com/angular/angular.js</a></li>
			</ul>
		</div>
	</div>
</div>

