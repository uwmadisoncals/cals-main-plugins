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
<script>
	var _iPanoramaAppData = window.iPanoramaAppData || {};
	if(_iPanoramaAppData) {
		_iPanoramaAppData.path = '<?php echo plugin_dir_url( dirname(dirname(__FILE__)) ); ?>';
		_iPanoramaAppData.ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
		_iPanoramaAppData.uploadUrl = '<?php $upload_dir = wp_upload_dir(); echo $upload_dir['baseurl']; ?>';
	}
</script>

<div class="ipnrm-ui-app-settings-quote">
	<p><?php echo __('This is global settings. These options are shared among all items if you turn it ON for an item, see an item right side checkbox for each parameter.', 'ipanorama'); ?></p>
</div>

<!-- ipnrm-ui-app -->
<div id="ipnrm-ui-app" class="ipnrm-ui-app-settings" x-ng-controller="ngiPanoramaAppController">
	<input type="hidden" id="ipnrm-ui-meta-ui-global-cfg" name="ipnrm-meta-ui-global-cfg" value="<?php echo stripslashes(get_option('ipnrm-meta-ui-global-cfg')); ?>">
	
	<div id="ipnrm-ui-loading" class="ipnrm-ui-loading-main">
		<div class="ipnrm-ui-loading-progress"></div>
	</div>
	<div id="ipnrm-ui-settings" class="ipnrm-ui-clearfix" x-workspace x-init="appData.fn.workspaceSettings.init">
		<div id="ipnrm-ui-tabs" class="ipnrm-ui-clearfix">
			<div class="ipnrm-ui-commands ipnrm-ui-clearfix">
				<div class="ipnrm-ui-cmd-save" x-ng-click="appData.fn.workspaceSettings.updateSettings(appData);"><i class="fa fa-floppy-o fa-fw"></i><span><?php echo __('Update Settings', 'ipanorama'); ?></span></div>
			</div>
			<div class="ipnrm-ui-tab ipnrm-ui-active"><?php echo __('General', 'ipanorama'); ?></div>
		</div>
		<div id="ipnrm-ui-tab-data">
			<!-- general section -->
			<div class="ipnrm-ui-section ipnrm-ui-active" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.general.isActive}">
				<div class="ipnrm-ui-config">
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.size}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.size = !appData.config.foldedSections.size;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set panorama custom width and height.<br><br>Note:<br>Also you can set size via shortcode.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Size', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<select class="ipnrm-ui-select" x-ng-model="appData.config.panoramaSize">
									<option value="none"><?php echo __('Default', 'ipanorama'); ?></option>
									<option value="fixed"><?php echo __('Fixed Size', 'ipanorama'); ?></option>
								</select>
							</div>
							<div x-ng-if="!(appData.config.panoramaSize=='none')"> 
								<div class="ipnrm-ui-control">
									<input class="ipnrm-ui-number" x-ng-model="appData.config.panoramaWidth">
									<div class="ipnrm-ui-label"><?php echo __('Width', 'ipanorama'); ?> (auto|value[px,cm,%,etc]|initial|inherit)</div>
								</div>
								<div class="ipnrm-ui-control">
									<input class="ipnrm-ui-number" x-ng-model="appData.config.panoramaHeight">
									<div class="ipnrm-ui-label"><?php echo __('Height', 'ipanorama'); ?> (auto|value[px,cm,%,etc]|initial|inherit)</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.theme}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.theme = !appData.config.foldedSections.theme;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Choose a theme from the list.<br><br>Note:<br>You can create your own theme too and put it into the plugin folder for later use.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Theme', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<select class="ipnrm-ui-select" x-ng-model="appData.config.theme">
									<option value="ipnrm-theme-default"><?php echo __('Default', 'ipanorama'); ?></option>
									<?php 
										$plugin_path = plugin_dir_path( dirname(dirname(__FILE__)) );
										$path = $plugin_path . 'lib/ipanorama.theme.*.css';
										$files = glob( $path );
										foreach($files as $file) {
											$file = strtolower(basename($file));
											$matches = array();
											
											if(preg_match('/^ipanorama.theme.(.*).css?/', $file, $matches)) {
												$theme = $matches[1];
												if($theme !== 'default' ) {
													echo '<option value="ipnrm-theme-' . $theme . '">' . $theme . '</option>';
												}
											}
										}
									?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.imagePreview}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.imagePreview = !appData.config.foldedSections.imagePreview;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set a preview image that will display before the panorama load.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Image Preview', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.imagePreview.isActive"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable image preview.', 'ipanorama'); ?></div></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.imagePreview.isActive">
								<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.config.imagePreview.url}" x-ng-click="appData.fn.selectImage(appData, appData.config.imagePreview);">
									<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.config.imagePreview) + ')'}"></div>
									<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.config.imagePreview);$event.stopPropagation();"></div>
									<div class="ipnrm-ui-image-clear" x-ng-click="appData.config.imagePreview.url=null;$event.stopPropagation();"></div>
									<div class="ipnrm-ui-image-label"><?php echo __('Image', 'ipanorama'); ?></div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.actions}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.actions = !appData.config.foldedSections.actions;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Use this options to enable or disable different UI actions.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Actions', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.autoLoad"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('If ON the panorama will automatically load.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Auto load', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.autoRotate"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('If ON the panorama will automatically rotate when loaded.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Auto rotate', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.autoRotate">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.autoRotateSpeed">
								<div class="ipnrm-ui-label"><?php echo __('Speed', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.autoRotate">
								<input class="ipnrm-ui-text" type="number" min="0" x-ng-model="appData.config.autoRotateInactivityDelay">
								<div class="ipnrm-ui-label"><?php echo __('Inactivity delay (ms)', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelPreventDefault"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable prevention of the default behavior on the mouseWheel event.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Mouse wheel, prevent the default behavior', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelRotate"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the panorama rotate using the mouse scroll wheel.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Mouse wheel rotate', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.mouseWheelRotate">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.mouseWheelRotateCoef">
								<div class="ipnrm-ui-label"><?php echo __('Rotate coefficient', 'ipanorama'); ?></div>
							</div>
							
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the panorama zoom using the mouse scroll wheel.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Mouse wheel zoom', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.mouseWheelZoom">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.mouseWheelZoomCoef">
								<div class="ipnrm-ui-label"><?php echo __('Zoom coefficient', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.hoverGrab"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the "grab/move" mode on the mouse hover event.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Hover grab', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.hoverGrab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.hoverGrabYawCoef">
								<div class="ipnrm-ui-label"><?php echo __('Yaw coefficient', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.hoverGrab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.hoverGrabPitchCoef">
								<div class="ipnrm-ui-label"><?php echo __('Pitch coefficient', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.grab"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the "grab/move" mode on the mouse click and move events.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Grab (click and drag)', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.grab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.grabCoef">
								<div class="ipnrm-ui-label"><?php echo __('Grab coefficient', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.pinchZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the zoom by pinch gesture.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Pinch zoom', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.pinchZoom">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.pinchZoomCoef">
								<div class="ipnrm-ui-label"><?php echo __('Pinch zoom coefficient', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.keyboardNav"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable navigation via keyboard.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Keyboard navigation', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.keyboardZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable zoom via keyboard.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Keyboard zoom', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<input class="ipnrm-ui-text" name="ipnrm-input-scene-fade-duration" type="number" min="0" x-ng-model="appData.config.sceneFadeDuration">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specifies the fade duration in milliseconds, when transitioning between scenes.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Scene fade duration (ms)', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.sceneBackgroundLoad"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable loading scene images in background.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Scene images background load', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mobile"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the animation in the mobile browsers.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Mobile animation', 'ipanorama'); ?></div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.controls}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.controls = !appData.config.foldedSections.controls;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Use this options to show or hide controls.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Controls', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showControlsOnHover"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show controls on mouse hover event.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Show controls on hover', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneThumbsCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the thumbnails slider control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Thumbnails slider control', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div class="ipnrm-ui-radio">
									<input class="ipnrm-ui-radio" type="radio" name="ipnrm-input-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="true">
									<div class="ipnrm-ui-label"><?php echo __('Vertical type', 'ipanorama'); ?></div>
									<input class="ipnrm-ui-radio" type="radio" name="ipnrm-input-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="false">
									<div class="ipnrm-ui-label"><?php echo __('Horizontal type', 'ipanorama'); ?></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.sceneThumbsStatic"></div>
								<div class="ipnrm-ui-label"><?php echo __('Thumbnails are static', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneMenuCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the toggle thumbnails slider control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Toggle thumbnails slider control', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneNextPrevCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the scene next/prev control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Scene next/prev control', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.showSceneNextPrevCtrl">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.sceneNextPrevLoop"></div>
								<div class="ipnrm-ui-label"><?php echo __('Scene next/prev loop', 'ipanorama'); ?></div>
							</div>
							
							<!--
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showShareCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip">Show/hide the share control.</div></div>
								<div class="ipnrm-ui-label">Share control</div>
							</div>
							-->
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showZoomCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the zoom control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Zoom control', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showFullscreenCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the fullscreen control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Fullscreen control', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showAutoRotateCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the auto rotate control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('AutoRotate control', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.compass"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the compass control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Compass', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.title"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the title control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Title', 'ipanorama'); ?></div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.popover}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.popover = !appData.config.foldedSections.popover;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Use this options to set common popover settings.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Popover Settings', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.popover"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable popovers.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Show Popovers', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set a custom popover HTML template.<br><br>Note:<br>We recommend do not change this parameter without having some knowledge.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('HTML template', 'ipanorama'); ?></div>
								<textarea class="ipnrm-ui-textarea" cols="40" rows="5" x-ng-model="appData.config.popoverTemplate"></textarea>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set default placement property of popovers', 'ipanorama'); ?></div></div>
								<select class="ipnrm-ui-select" x-ng-model="appData.config.popoverPlacement">
									<option value="top"><?php echo __('top', 'ipanorama'); ?></option>
									<option value="bottom"><?php echo __('bottom', 'ipanorama'); ?></option>
									<option value="left"><?php echo __('left', 'ipanorama'); ?></option>
									<option value="right"><?php echo __('right', 'ipanorama'); ?></option>
									<option value="top-left"><?php echo __('top-left', 'ipanorama'); ?></option>
									<option value="top-right"><?php echo __('top-right', 'ipanorama'); ?></option>
									<option value="bottom-left"><?php echo __('bottom-left', 'ipanorama'); ?></option>
									<option value="bottom-right"><?php echo __('bottom-right', 'ipanorama'); ?></option>
								</select>
								<div class="ipnrm-ui-label"><?php echo __('Popover placement', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.hotSpotBelowPopover"></div>
								<div class="ipnrm-ui-label"><?php echo __('Popover is under the hotspot', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-label"><?php echo __('Popover show trigger', 'ipanorama'); ?></div><br>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specify how a popover will be triggered.', 'ipanorama'); ?></div></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverShowTriggerHover"></div>
								<div class="ipnrm-ui-label"><?php echo __('Hover', 'ipanorama'); ?></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverShowTriggerClick"></div>
								<div class="ipnrm-ui-label"><?php echo __('Click', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-label"><?php echo __('Popover hide trigger', 'ipanorama'); ?></div><br>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specify how a popover will be hidden.', 'ipanorama'); ?></div></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverHideTriggerLeave"></div>
								<div class="ipnrm-ui-label"><?php echo __('Leave', 'ipanorama'); ?></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverHideTriggerClick"></div>
								<div class="ipnrm-ui-label"><?php echo __('Click', 'ipanorama'); ?></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverHideTriggerGrab"></div>
								<div class="ipnrm-ui-label"><?php echo __('Grab', 'ipanorama'); ?></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverHideTriggerManual"></div>
								<div class="ipnrm-ui-label"><?php echo __('Manual', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specify a CSS animation class for the popover show', 'ipanorama'); ?></div></div>
								<button class="ipnrm-ui-button" type="button" x-ng-click="appData.fn.selectPopoverShowClass(appData)">GET</button>
								<input class="ipnrm-ui-text" type="text" x-ng-model="appData.config.popoverShowClass" x-ng-model-options="{updateOn: 'change blur'}">
								<div class="ipnrm-ui-label"><?php echo __('Popover Show CSS3 Class', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specify a CSS animation class for the popover hide', 'ipanorama'); ?></div></div>
								<button class="ipnrm-ui-button" type="button" x-ng-click="appData.fn.selectPopoverHideClass(appData)">GET</button>
								<input class="ipnrm-ui-text" type="text" x-ng-model="appData.config.popoverHideClass" x-ng-model-options="{updateOn: 'change blur'}">
								<div class="ipnrm-ui-label"><?php echo __('Popover Hide CSS3 Class', 'ipanorama'); ?></div>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.customCSS}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.customCSS = !appData.config.foldedSections.customCSS;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enter any custom css you want to apply on this virtual tour.<br><br>Note:<br>Please do not use <b>&lt;style&gt;...&lt;/style&gt;</b> tag with Custom CSS.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Custom CSS', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.customCSS"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable custom css rules.', 'ipanorama'); ?></div></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.customCSS">
								<textarea class="ipnrm-ui-textarea" cols="40" rows="20" placeholder="<?php echo __('Custom CSS Content', 'ipanorama'); ?>" x-ng-model="appData.config.customCSSContent"></textarea>
							</div>
						</div>
					</div>
					
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.customJS}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.customJS = !appData.config.foldedSections.customJS;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enter any custom javascript code you want to execute after the ipanorama load.<br><br>Note:<br>Please do not use <b>&lt;script&gt;...&lt;/script&gt;</b> tag with Custom JavaScript.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Custom JavaScript', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.customJS"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable a custom js source code. The code is hooked to the onload event.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Handle \'onLoad\' event', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.customJS">
								<textarea class="ipnrm-ui-textarea" cols="40" rows="20" placeholder="<?php echo __('Custom JS code', 'ipanorama'); ?>" x-ng-model="appData.config.customJSContent"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
	<div class="ipnrm-ui-modals">
	</div>
</div>
<!-- /end ipnrm-ui-app -->