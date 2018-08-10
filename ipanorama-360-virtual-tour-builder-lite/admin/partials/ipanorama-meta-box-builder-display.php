<?php
/**
 * This file is used to markup the meta box aspects of the plugin.
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

<!-- ipnrm-ui-app -->
<div id="ipnrm-ui-app" class="ipnrm-ui-app-item" x-ng-controller="ngiPanoramaAppController">
	<input type="hidden" id="ipnrm-ui-meta-ui-cfg" name="ipnrm-meta-ui-cfg" value="<?php echo get_post_meta( get_the_ID(), 'ipnrm-meta-ui-cfg', true ); ?>">
	<input type="hidden" id="ipnrm-ui-meta-ui-global-cfg" name="ipnrm-meta-ui-global-cfg" value="<?php echo stripslashes(get_option('ipnrm-meta-ui-global-cfg')); ?>">
	<input type="hidden" id="ipnrm-ui-meta-global-cfg" name="ipnrm-meta-global-cfg" value="">
	<input type="hidden" id="ipnrm-ui-meta-panorama-cfg" name="ipnrm-meta-panorama-cfg" value="">
	<input type="hidden" id="ipnrm-ui-meta-panorama-theme" name="ipnrm-meta-panorama-theme" value="">
	<input type="hidden" id="ipnrm-ui-meta-total-scenes" name="ipnrm-meta-total-scenes" value="">
	
	<div id="ipnrm-ui-loading" class="ipnrm-ui-loading-main">
		<div class="ipnrm-ui-loading-progress"></div>
	</div>
	<div id="ipnrm-ui-workspace" class="ipnrm-ui-clearfix" x-workspace x-init="appData.fn.workspace.init">
		<div id="ipnrm-ui-screen">
			<div id="ipnrm-ui-canvas" x-ng-class="{'ipnrm-ui-target-tool': appData.targetTool}"></div>
			<div class="ipnrm-ui-value-info" x-ng-if="appData.scene.selected">
				<div class="ipnrm-ui-value-yaw"><?php echo __('yaw', 'ipanorama'); ?>: {{appData.scene.selected.yaw}}</div>
				<div class="ipnrm-ui-value-pitch"><?php echo __('pitch', 'ipanorama'); ?>: {{appData.scene.selected.pitch}}</div>
				<div class="ipnrm-ui-value-zoom"><?php echo __('zoom', 'ipanorama'); ?>: {{appData.scene.selected.zoom}}</div>
			</div>
		</div>
		<div id="ipnrm-ui-tabs" class="ipnrm-ui-clearfix">
			<div class="ipnrm-ui-commands ipnrm-ui-clearfix">
				<div class="ipnrm-ui-cmd-preview" x-ng-click="appData.fn.preview(appData);"><i class="fa fa-television fa-fw"></i><span><?php echo __('Preview', 'ipanorama'); ?></span></div>
				<!--<div class="ipnrm-ui-cmd-preview" x-ng-click="appData.fn.getCode(appData, appData.config, false);">Get Code</div>-->
				<div class="ipnrm-ui-cmd-load" x-ng-click="appData.fn.storage.loadFromFile(appData);" title="Load settings from a local config file (json format)"><i class="fa fa-upload fa-fw"></i><span><?php echo __('Load From File', 'ipanorama'); ?></span></div>
				<div class="ipnrm-ui-cmd-save" x-ng-click="appData.fn.storage.saveToFile(appData);" title="Save settings to a local config file (json format)"><i class="fa fa-floppy-o fa-fw"></i><span><?php echo __('Save To File', 'ipanorama'); ?></span></div>
			</div>
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.general.isActive}" x-tab-panel-item x-id="general" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-cog"></i><?php echo __('General', 'ipanorama'); ?></div>
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.scenes.isActive}" x-tab-panel-item x-id="scenes" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-picture-o"></i><?php echo __('Scenes', 'ipanorama'); ?><div class="ipnrm-ui-label">{{appData.config.scenes.length}}</div></div>
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.hotspots.isActive, 'ipnrm-ui-hide': !appData.scene.selected}" x-tab-panel-item x-id="hotspots" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-dot-circle-o"></i><?php echo __('Hotspots', 'ipanorama'); ?><div class="ipnrm-ui-label">{{appData.scene.selected.config.hotspots.length}}</div></div>
			<input id="ipnrm-ui-load-from-file" type="file" style="display:none;" />
		</div>
		<div id="ipnrm-ui-tab-data">
			<!-- general section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.general.isActive}">
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
									<option value="fixed">Fixed Size</option>
								</select>
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.panoramaSize}">
									<div class="ipnrm-ui-label">
										<span>{{ appData.configGlobal.panoramaSize == 'none' ? 'default' : appData.configGlobal.panoramaSize }}</span>
										<span>{{ appData.configGlobal.panoramaSize == 'fixed' ? '[' + appData.configGlobal.panoramaWidth + 'x' + appData.configGlobal.panoramaHeight + ']' : '' }}</span>
									</div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.panoramaSize" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							<div x-ng-if="!(appData.config.panoramaSize=='none')"> 
								<div class="ipnrm-ui-control">
									<input class="ipnrm-ui-number" x-ng-model="appData.config.panoramaWidth">
									<div class="ipnrm-ui-label"><?php echo __('Width', 'ipanorama'); ?> (auto|value[px,cm,%,etc]|initial|inherit)</div>
									
									<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.panoramaWidth}">
										<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.panoramaWidth }}</span></div>
										<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.panoramaWidth" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
									</div>
								</div>
								<div class="ipnrm-ui-control">
									<input class="ipnrm-ui-number" x-ng-model="appData.config.panoramaHeight">
									<div class="ipnrm-ui-label"><?php echo __('Height', 'ipanorama'); ?> (auto|value[px,cm,%,etc]|initial|inherit)</div>
									
									<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.panoramaHeight}">
										<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.panoramaHeight }}</span></div>
										<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.panoramaHeight" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
									</div>
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
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.theme}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.theme.replace('ipnrm-theme-','') }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.theme" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.imagePreview}">
									<div class="ipnrm-ui-label">
										<span>{{ appData.configGlobal.imagePreview.isActive ? 'ON' : 'OFF' }}</span>
										<span>{{ appData.configGlobal.imagePreview.url ? appData.configGlobal.imagePreview.url : 'none' }}</span>
									</div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.imagePreview" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.autoLoad}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.autoLoad ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.autoLoad" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.autoRotate"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('If ON the panorama will automatically rotate when loaded.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Auto rotate', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.autoRotate}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.autoRotate ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.autoRotate" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.autoRotate">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.autoRotateSpeed">
								<div class="ipnrm-ui-label"><?php echo __('Speed', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.autoRotateSpeed}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.autoRotateSpeed }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.autoRotateSpeed" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.autoRotate">
								<input class="ipnrm-ui-text" type="number" min="0" x-ng-model="appData.config.autoRotateInactivityDelay">
								<div class="ipnrm-ui-label"><?php echo __('Inactivity delay (ms)', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.autoRotateInactivityDelay}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.autoRotateInactivityDelay }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.autoRotateInactivityDelay" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelPreventDefault"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable prevention of the default behavior on the mouseWheel event.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Mouse wheel, prevent the default behavior', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelPreventDefault}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelPreventDefault ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelPreventDefault" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelRotate"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the panorama rotate using the mouse scroll wheel.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Mouse wheel rotate', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelRotate}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelRotate ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelRotate" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.mouseWheelRotate">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.mouseWheelRotateCoef">
								<div class="ipnrm-ui-label"><?php echo __('Rotate coefficient', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelRotateCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelRotateCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelRotateCoef" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mouseWheelZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the panorama zoom using the mouse scroll wheel.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Mouse wheel zoom', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelZoom}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelZoom ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelZoom" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.mouseWheelZoom">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.mouseWheelZoomCoef">
								<div class="ipnrm-ui-label"><?php echo __('Zoom coefficient', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mouseWheelZoomCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mouseWheelZoomCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mouseWheelZoomCoef" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.hoverGrab"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the "grab/move" mode on the mouse hover event.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Hover grab', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.hoverGrab}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.hoverGrab ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.hoverGrab" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.hoverGrab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.hoverGrabYawCoef">
								<div class="ipnrm-ui-label"><?php echo __('Yaw coefficient', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.hoverGrabYawCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.hoverGrabYawCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.hoverGrabYawCoef" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.hoverGrab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.hoverGrabPitchCoef">
								<div class="ipnrm-ui-label"><?php echo __('Pitch coefficient', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.hoverGrabPitchCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.hoverGrabPitchCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.hoverGrabPitchCoef" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.grab"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the "grab/move" mode on the mouse click and move events.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Grab (click and drag)', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.grab}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.grab ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.grab" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.grab">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.grabCoef">
								<div class="ipnrm-ui-label"><?php echo __('Grab coefficient', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.grabCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.grabCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.grabCoef" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.pinchZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the zoom by pinch gesture.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Pinch zoom', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.pinchZoom}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.pinchZoom ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.pinchZoom" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.pinchZoom">
								<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.config.pinchZoomCoef">
								<div class="ipnrm-ui-label"><?php echo __('Pinch zoom coefficient', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.pinchZoomCoef}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.pinchZoomCoef }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.pinchZoomCoef" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.keyboardNav"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable navigation via keyboard.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Keyboard navigation', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.keyboardNav}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.keyboardNav ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.keyboardNav" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.keyboardZoom"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable zoom via keyboard.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Keyboard zoom', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.keyboardZoom}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.keyboardZoom ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.keyboardZoom" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<input class="ipnrm-ui-text" name="ipnrm-input-scene-fade-duration" type="number" min="0" x-ng-model="appData.config.sceneFadeDuration">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specifies the fade duration in milliseconds, when transitioning between scenes.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Scene fade duration (ms)', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneFadeDuration}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneFadeDuration }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneFadeDuration" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.sceneBackgroundLoad"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable loading scene images in background.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Scene images background load', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneBackgroundLoad}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneBackgroundLoad ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneBackgroundLoad" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.mobile"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the animation in the mobile browsers.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Mobile animation', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.mobile}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.mobile ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.mobile" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showControlsOnHover}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showControlsOnHover ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showControlsOnHover" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneThumbsCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the thumbnails slider control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Thumbnails slider control', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showSceneThumbsCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showSceneThumbsCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showSceneThumbsCtrl" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div class="ipnrm-ui-radio">
									<input class="ipnrm-ui-radio" type="radio" name="ipnrm-input-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="true">
									<div class="ipnrm-ui-label"><?php echo __('Vertical type', 'ipanorama'); ?></div>
									<input class="ipnrm-ui-radio" type="radio" name="ipnrm-input-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="false">
									<div class="ipnrm-ui-label"><?php echo __('Horizontal type', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneThumbsVertical}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneThumbsVertical ? 'vertical' : 'horizontal' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneThumbsVertical" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.sceneThumbsStatic"></div>
								<div class="ipnrm-ui-label"><?php echo __('Thumbnails are static', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneThumbsStatic}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneThumbsStatic ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneThumbsStatic" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneMenuCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the toggle thumbnails slider control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Toggle thumbnails slider control', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showSceneMenuCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showSceneMenuCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showSceneMenuCtrl" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showSceneNextPrevCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the scene next/prev control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Scene next/prev control', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showSceneNextPrevCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showSceneNextPrevCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showSceneNextPrevCtrl" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.showSceneNextPrevCtrl">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.sceneNextPrevLoop"></div>
								<div class="ipnrm-ui-label"><?php echo __('Scene next/prev loop', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.sceneNextPrevLoop}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.sceneNextPrevLoop ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.sceneNextPrevLoop" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showZoomCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showZoomCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showZoomCtrl" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showFullscreenCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the fullscreen control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Fullscreen control', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showFullscreenCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showFullscreenCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showFullscreenCtrl" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.showAutoRotateCtrl"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the auto rotate control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('AutoRotate control', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.showAutoRotateCtrl}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.showAutoRotateCtrl ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.showAutoRotateCtrl" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.compass"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the compass control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Compass', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.compass}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.compass ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.compass" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.config.title"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Show/hide the title control.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('Title', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.title}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.title ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.title" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popover}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.popover ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popover" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set a custom popover HTML template.<br><br>Note:<br>We recommend do not change this parameter without having some knowledge.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-label"><?php echo __('HTML template', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverTemplate}">
									<div class="ipnrm-ui-label"><span>...</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverTemplate" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
								
								<textarea class="ipnrm-ui-textarea" cols="40" rows="5" x-ng-model="appData.config.popoverTemplate"></textarea>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set default placement property of popovers', 'ipanorama'); ?></div></div>
								<select class="ipnrm-ui-select" x-ng-model="appData.config.popoverPlacement">
									<option value="top">top</option>
									<option value="bottom">bottom</option>
									<option value="left">left</option>
									<option value="right">right</option>
									<option value="top-left">top-left</option>
									<option value="top-right">top-right</option>
									<option value="bottom-left">bottom-left</option>
									<option value="bottom-right">bottom-right</option>
								</select>
								<div class="ipnrm-ui-label"><?php echo __('Popover placement', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverPlacement}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.popoverPlacement }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverPlacement" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.hotSpotBelowPopover"></div>
								<div class="ipnrm-ui-label"><?php echo __('Popover is under the hotspot', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.hotSpotBelowPopover}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.hotSpotBelowPopover ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.hotSpotBelowPopover" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-label"><?php echo __('Popover show trigger', 'ipanorama'); ?></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specify how a popover will be triggered.', 'ipanorama'); ?></div></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverShowTrigger}">
									<div class="ipnrm-ui-label">
										<span>
											{{ appData.configGlobal.popoverShowTriggerHover ? '<?php echo __('Hover', 'ipanorama'); ?>' : '' }}
											{{ appData.configGlobal.popoverShowTriggerClick ? '<?php echo __('Click', 'ipanorama'); ?>' : '' }}
										</span>
									</div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverShowTrigger" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
								
								<br>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverShowTriggerHover"></div>
								<div class="ipnrm-ui-label"><?php echo __('Hover', 'ipanorama'); ?></div>
								
								<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.config.popoverShowTriggerClick"></div>
								<div class="ipnrm-ui-label"><?php echo __('Click', 'ipanorama'); ?></div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-label"><?php echo __('Popover hide trigger', 'ipanorama'); ?></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specify how a popover will be hidden.', 'ipanorama'); ?></div></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverHideTrigger}">
									<div class="ipnrm-ui-label">
										<span>
											{{ appData.configGlobal.popoverHideTriggerLeave ? '<?php echo __('Leave', 'ipanorama'); ?>' : '' }}
											{{ appData.configGlobal.popoverHideTriggerClick ? '<?php echo __('Click', 'ipanorama'); ?>' : '' }}
											{{ appData.configGlobal.popoverHideTriggerGrab ? '<?php echo __('Grab', 'ipanorama'); ?>' : '' }}
											{{ appData.configGlobal.popoverHideTriggerManual ? '<?php echo __('Manual', 'ipanorama'); ?>' : '' }}
										</span>
									</div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverHideTrigger" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
								
								<br>
								
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverShowClass}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.popoverShowClass ? appData.configGlobal.popoverShowClass : 'none' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverShowClass" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specify a CSS animation class for the popover hide', 'ipanorama'); ?></div></div>
								<button class="ipnrm-ui-button" type="button" x-ng-click="appData.fn.selectPopoverHideClass(appData)">GET</button>
								<input class="ipnrm-ui-text" type="text" x-ng-model="appData.config.popoverHideClass" x-ng-model-options="{updateOn: 'change blur'}">
								<div class="ipnrm-ui-label"><?php echo __('Popover Hide CSS3 Class', 'ipanorama'); ?></div>
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.popoverHideClass}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.popoverHideClass ? appData.configGlobal.popoverHideClass : 'none' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.popoverHideClass" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.customCSS}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.customCSS ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.customCSS" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.customCSS">
								<textarea class="ipnrm-ui-textarea" cols="40" rows="20" placeholder="Custom CSS Content" x-ng-model="appData.config.customCSSContent"></textarea>
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
								
								<div class="ipnrm-ui-global-cfg" x-ng-class="{'ipnrm-ui-active': appData.config.global.customJS}">
									<div class="ipnrm-ui-label"><span>{{ appData.configGlobal.customJS ? 'ON' : 'OFF' }}</span></div>
									<div x-checkbox class="ipnrm-ui-standard ipnrm-ui-orange" x-ng-model="appData.config.global.customJS" title="<?php echo __('Check to use global settings', 'ipanorama'); ?>"></div>
								</div>
							</div>
							
							<div class="ipnrm-ui-control" x-ng-if="appData.config.customJS">
								<textarea class="ipnrm-ui-textarea" cols="40" rows="20" placeholder="Custom JS code" x-ng-model="appData.config.customJSContent"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /end general section -->
			
			<!-- scene section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.scenes.isActive}">
				<div class="ipnrm-ui-item-list-wrap">
					<div class="ipnrm-ui-item-commands">
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.add(appData)"><i class="fa fa-fw fa-plus-square"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.copySelected(appData)"><i class="fa fa-fw fa-clone"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.upSelected(appData)"><i class="fa fa-fw fa-arrow-up"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.downSelected(appData)"><i class="fa fa-fw fa-arrow-down"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.removeSelected(appData)"><i class="fa fa-fw fa-trash"></i></div>
					</div>
					<ul class="ipnrm-ui-item-list">
						<li class="ipnrm-ui-item" x-ng-repeat="scene in appData.config.scenes track by scene.id" x-ng-class="{'ipnrm-ui-active': scene.isSelected}" x-ng-click="appData.fn.scenes.select(appData, scene)">
							<span class="ipnrm-ui-icon"><i class="fa fa-cube"></i></span>
							<span class="ipnrm-ui-name">{{scene.id}}<span x-ng-if="scene.config.title"> | <i>{{scene.config.title}}</i></sapn></span>
							<span class="ipnrm-ui-visible" x-ng-click="scene.isVisible=!scene.isVisible" x-ng-class="{'ipnrm-ui-off': !scene.isVisible}"></span>
						</li>
					</ul>
				</div>
				<div class="ipnrm-ui-config">
					<div x-ng-class="{'ipnrm-ui-hide': !appData.scene.selected}">
						<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.sceneSettings}">
							<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.sceneSettings = !appData.config.foldedSections.sceneSettings;">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Use this options to set common scene settings.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-title"><?php echo __('Scene Settings', 'ipanorama'); ?></div>
								<div class="ipnrm-ui-state"></div>
							</div>
							<div class="ipnrm-ui-block-data">
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set title for selected scene.', 'ipanorama'); ?></div></div>
									<input class="ipnrm-ui-text ipnrm-ui-long" type="text" placeholder="<?php echo __('Scene title', 'ipanorama'); ?>" x-ng-model="appData.scene.selected.config.title">
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.scene.selected.config.titleHtml"></div>
									<div class="ipnrm-ui-label"><?php echo __('The title has HTML markup', 'ipanorama'); ?></div>
								</div>
								
								<!--
									<div class="ipnrm-ui-label">Selector</div>
									<div class="ipnrm-ui-control">
										<input type="text" class="ipnrm-ui-long" x-ng-model="appData.scene.selected.config.titleSelector" placeholder="it allows you to set an element's HTML content for the title">
									</div>
								-->
								
								<div class="ipnrm-ui-control" x-ng-if="appData.config.popover">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Select a type of selected scene and set images.', 'ipanorama'); ?></div></div>
									<select class="ipnrm-ui-select" x-ng-model="appData.scene.selected.config.type">
										<option value="sphere"><?php echo __('sphere', 'ipanorama'); ?></option>
										<option value="cylinder"><?php echo __('cylinder', 'ipanorama'); ?></option>
										<option value="cube"><?php echo __('cube', 'ipanorama'); ?></option>
									</select>
									<div class="ipnrm-ui-label"><?php echo __('Scene type', 'ipanorama'); ?></div><br>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.scene.selected.config.type == 'cube'">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Select count of textures for a cube scene.', 'ipanorama'); ?></div></div>
									<select class="ipnrm-ui-select" x-ng-model="appData.scene.selected.config.cubeTextureCount">
										<option value="single"><?php echo __('1 Texture', 'ipanorama'); ?></option>
										<option value="six"><?php echo __('6 Textures', 'ipanorama'); ?></option>
									</select>
									<div class="ipnrm-ui-label"><?php echo __('Texture count', 'ipanorama'); ?></div><br>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageFront.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageFront);">
										<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageFront) + ')'}"></div>
										<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageFront);$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageFront.url=null;$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-label"><?php echo __('Front', 'ipanorama'); ?></div>
									</div>
									
									<span x-ng-class="{'ipnrm-ui-image-hide': appData.scene.selected.config.type != 'cube' || appData.scene.selected.config.cubeTextureCount != 'six'}">
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageBack.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageBack);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageBack) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageBack);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageBack.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label"><?php echo __('Back', 'ipanorama'); ?></div>
										</div>
										
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageLeft.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageLeft);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageLeft) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageLeft);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageLeft.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label"><?php echo __('Left', 'ipanorama'); ?></div>
										</div>
										
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageRight.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageRight);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageRight) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageRight);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageRight.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label"><?php echo __('Right', 'ipanorama'); ?></div>
										</div>
										
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageTop.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageTop);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageTop) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageTop);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageTop.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label"><?php echo __('Top', 'ipanorama'); ?></div>
										</div>
										
										<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.imageBottom.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.imageBottom);">
											<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.imageBottom) + ')'}"></div>
											<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.imageBottom);$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.imageBottom.url=null;$event.stopPropagation();"></div>
											<div class="ipnrm-ui-image-label"><?php echo __('Bottom', 'ipanorama'); ?></div>
										</div>
									</span>
								</div>
								
								<div class="ipnrm-ui-accordion" x-ng-if="appData.scene.selected.config.type == 'sphere'">
									<div class="ipnrm-ui-accordion-toggle"><?php echo __('Advanced Options', 'ipanorama'); ?></div>
									<div class="ipnrm-ui-accordion-data">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set the sphere mesh quality.<br><br>Note:<br>This is a number of horizontal and vertical segments.', 'ipanorama'); ?></div></div>
											
											<div class="ipnrm-ui-inline-group">
												<div class="ipnrm-ui-label"><?php echo __('Width segments', 'ipanorama'); ?></div>
												<div class="ipnrm-ui-control">
													<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.scene.selected.config.sphereWidthSegments" x-ng-change="appData.hotspot.dirty=true">
												</div>
											</div>
											<div class="ipnrm-ui-inline-group">
												<div class="ipnrm-ui-label"><?php echo __('Height segments', 'ipanorama'); ?></div>
												<div class="ipnrm-ui-control">
													<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.scene.selected.config.sphereHeightSegments" x-ng-change="appData.hotspot.dirty=true">
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.scene.selected.config.thumb"></div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set a thumbnail image for selected scene.', 'ipanorama'); ?></div></div>
									<div class="ipnrm-ui-label"><?php echo __('Scene thumb image', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.scene.selected.config.thumb">
									<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.scene.selected.config.thumbImage.url}" x-ng-click="appData.fn.selectImage(appData, appData.scene.selected.config.thumbImage);">
										<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.scene.selected.config.thumbImage) + ')'}"></div>
										<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.scene.selected.config.thumbImage);$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-clear" x-ng-click="appData.scene.selected.config.thumbImage.url=null;$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-label"><?php echo __('Image', 'ipanorama'); ?></div>
									</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.scene.selected.config.saveCamera"></div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Save or use original scene camera lookat vector.', 'ipanorama'); ?></div></div>
									<div class="ipnrm-ui-label"><?php echo __('Save camera lookat vector', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.scene.selected.config.pitchLimits"></div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the pitch limits.', 'ipanorama'); ?></div></div>
									<div class="ipnrm-ui-label"><?php echo __('Pitch limits', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.scene.selected.config.pitchLimits">
									<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.scene.selected.config.pitchLimitUp">
									<div class="ipnrm-ui-label"><?php echo __('Up limit (degree)', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.scene.selected.config.pitchLimits">
									<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.scene.selected.config.pitchLimitDown">
									<div class="ipnrm-ui-label"><?php echo __('Down limit (degree)', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.scene.selected.config.yawLimits"></div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable the yaw limits.', 'ipanorama'); ?></div></div>
									<div class="ipnrm-ui-label"><?php echo __('Yaw limits', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.scene.selected.config.yawLimits">
									<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.scene.selected.config.yawLimitLeft">
									<div class="ipnrm-ui-label"><?php echo __('Left limit (degree)', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.scene.selected.config.yawLimits">
									<input class="ipnrm-ui-text" type="number" step="any" x-ng-model="appData.scene.selected.config.yawLimitRight">
									<div class="ipnrm-ui-label"><?php echo __('Right limit (degree)', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set the panorama\'s starting yaw and pitch position in degrees, and the zoom parameter.', 'ipanorama'); ?></div></div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><?php echo __('Yaw', 'ipanorama'); ?></div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.scene.selected.config.yaw">
											<div class="ipnrm-ui-indicator"><div class="ipnrm-ui-indicator-btn" x-ng-click="appData.scene.selected.config.yaw=appData.scene.selected.yaw"></div>{{appData.scene.selected.yaw}}</div>
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><?php echo __('Pitch', 'ipanorama'); ?></div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.scene.selected.config.pitch">
											<div class="ipnrm-ui-indicator"><div class="ipnrm-ui-indicator-btn" x-ng-click="appData.scene.selected.config.pitch=appData.scene.selected.pitch"></div>{{appData.scene.selected.pitch}}</div>
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><?php echo __('Zoom', 'ipanorama'); ?></div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.scene.selected.config.zoom">
											<div class="ipnrm-ui-indicator"><div class="ipnrm-ui-indicator-btn" x-ng-click="appData.scene.selected.config.zoom=appData.scene.selected.zoom"></div>{{appData.scene.selected.zoom}}</div>
										</div>
									</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set the north offset for compass direction, in degrees.<br><br>Note:<br>It only has an effect if compass is set to ON.', 'ipanorama'); ?></div></div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><?php echo __('Compass Offset', 'ipanorama'); ?></div><br>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.scene.selected.config.compassNorthOffset">
											<div class="ipnrm-ui-indicator"><div class="ipnrm-ui-indicator-btn" x-ng-click="appData.scene.selected.config.compassNorthOffset=appData.scene.selected.yaw"></div>{{appData.scene.selected.yaw}}</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /end scene section -->
			
			<!-- hotspots section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.config.tabPanel.hotspots.isActive}">
				<div class="ipnrm-ui-item-list-wrap">
					<div class="ipnrm-ui-item-commands">
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.add(appData)"><i class="fa fa-fw fa-plus-square"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.copySelected(appData)"><i class="fa fa-fw fa-clone"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.upSelected(appData)"><i class="fa fa-fw fa-arrow-up"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.downSelected(appData)"><i class="fa fa-fw fa-arrow-down"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.removeSelected(appData)"><i class="fa fa-fw fa-trash"></i></div>
					</div>
					<ul class="ipnrm-ui-item-list">
						<li class="ipnrm-ui-item" x-ng-repeat="hotspot in appData.scene.selected.config.hotspots track by hotspot.id" x-ng-class="{'ipnrm-ui-active': hotspot.isSelected}" x-ng-click="appData.fn.hotspots.select(appData, hotspot)">
							<span class="ipnrm-ui-icon"><i class="fa fa-thumb-tack"></i></span>
							<span class="ipnrm-ui-name">{{hotspot.id}}<span x-ng-if="hotspot.config.title"> | <i>{{hotspot.config.title}}</i></sapn></span>
							<span class="ipnrm-ui-visible" x-ng-click="hotspot.isVisible=!hotspot.isVisible;appData.hotspot.dirty=true" x-ng-class="{'ipnrm-ui-off': !hotspot.isVisible}"></span>
						</li>
					</ul>
				</div>
				<div class="ipnrm-ui-config">
					<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.hotspotTargetTool}">
						<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.hotspotTargetTool = !appData.config.foldedSections.hotspotTargetTool;">
							<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Use target tool to quick create a hotspot and it\'s location on the panorama.', 'ipanorama'); ?></div></div>
							<div class="ipnrm-ui-title"><?php echo __('Target Tool', 'ipanorama'); ?></div>
							<div class="ipnrm-ui-state"></div>
						</div>
						<div class="ipnrm-ui-block-data">
							<div class="ipnrm-ui-control">
								<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.targetTool"></div>
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable target tool.<br><br>Note:<br>When the target tool is ON click on the panorama together with Ctrl Key (for Mac with Meta Key).', 'ipanorama'); ?></div></div>
							</div>
						</div>
					</div>
					<div x-ng-class="{'ipnrm-ui-hide': !appData.hotspot.selected}">
						<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.hotspotLocation}">
							<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.hotspotLocation = !appData.config.foldedSections.hotspotLocation;">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set a hotspot location.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-title"><?php echo __('Hotspot Location', 'ipanorama'); ?></div>
								<div class="ipnrm-ui-state"></div>
							</div>
							<div class="ipnrm-ui-block-data">
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set the hotspot\'s starting yaw and pitch position in degrees.<br><br>Note:<br>If you want to change the location of the selected hotspot, just click on the panorama together with Ctrl Key (for Mac with Meta Key).', 'ipanorama'); ?></div></div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><?php echo __('Yaw', 'ipanorama'); ?></div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.hotspot.selected.config.yaw" x-ng-change="appData.hotspot.dirty=true">
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><?php echo __('Pitch', 'ipanorama'); ?></div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" step="any" x-ng-model="appData.hotspot.selected.config.pitch" x-ng-change="appData.hotspot.dirty=true">
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.hotspotSettings}">
							<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.hotspotSettings = !appData.config.foldedSections.hotspotSettings;">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set common hotspot settings.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-title"><?php echo __('Hotspot Settings', 'ipanorama'); ?></div>
								<div class="ipnrm-ui-state"></div>
							</div>
							<div class="ipnrm-ui-block-data">
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set title for selected hotspot.', 'ipanorama'); ?></div></div>
									<input class="ipnrm-ui-text ipnrm-ui-long" type="text" placeholder="<?php echo __('Hotspot title', 'ipanorama'); ?>" x-ng-model="appData.hotspot.selected.config.title" x-ng-change="appData.hotspot.dirty=true">
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Specify the ID of the scene to link to.', 'ipanorama'); ?></div></div>
									<select class="ipnrm-ui-select" x-ng-model="appData.hotspot.selected.config.sceneId">
										<option value="none"><?php echo __('none', 'ipanorama'); ?></option>
										<option x-ng-repeat="scene in appData.config.scenes track by scene.id" value="{{appData.fn.getSceneKeyById(scene.id)}}">{{scene.id}} {{appData.fn.trunc(scene.config.title, 25)}}</option>
									</select>
									<div class="ipnrm-ui-label"><?php echo __('Go to the scene', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set a hotspot image, otherwise the plugin uses a theme icon.', 'ipanorama'); ?></div></div>
									<div class="ipnrm-ui-image" x-ng-class="{'ipnrm-ui-active': appData.hotspot.selected.config.image.url}" x-ng-click="appData.fn.selectImage(appData, appData.hotspot.selected.config.image);">
										<div class="ipnrm-ui-image-data" x-ng-style="{'background-image': 'url(' + appData.fn.getImageUrl(appData, appData.hotspot.selected.config.image) + ')'}"></div>
										<div class="ipnrm-ui-image-edit" x-ng-click="appData.fn.setImageUrlConfirm(appData, appData.hotspot.selected.config.image);$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-clear" x-ng-click="appData.hotspot.selected.config.image.url=null;$event.stopPropagation();"></div>
										<div class="ipnrm-ui-image-label"><?php echo __('Image', 'ipanorama'); ?></div>
									</div>
								</div>
								
								<div x-ng-if="(appData.hotspot.selected.config.image.url ? true : false)">
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><?php echo __('Image Custom Width (px)', 'ipanorama'); ?></div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.hotspot.selected.config.image.width">
										</div>
									</div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><?php echo __('Image Custom Height (px)', 'ipanorama'); ?></div>
										<div class="ipnrm-ui-control">
											<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.hotspot.selected.config.image.height">
										</div>
									</div>
								</div>
								
								<div class="ipnrm-ui-control">
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('This lets you connect a hotspot to an internet address.', 'ipanorama'); ?></div></div>
									<input class="ipnrm-ui-text ipnrm-ui-long" type="text" placeholder="<?php echo __('Url', 'ipanorama'); ?>" x-ng-model="appData.hotspot.selected.config.link">
								</div>
								
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.hotspot.selected.config.linkNewWindow"></div>
									<div class="ipnrm-ui-label ipnrm-ui-thin"><?php echo __('Open url in new window', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-accordion">
									<div class="ipnrm-ui-accordion-toggle"><?php echo __('Advanced Options', 'ipanorama'); ?></div>
									<div class="ipnrm-ui-accordion-data">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable hotspot custom style.<br><br>Note:<br>You can define your own style for hotspot with images, icons, text and etc..', 'ipanorama'); ?></div></div>
											<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.hotspot.selected.config.custom"></div>
											<div class="ipnrm-ui-label"><?php echo __('Custom style', 'ipanorama'); ?></div>
										</div>
										
										<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.custom">
											<input class="ipnrm-ui-text ipnrm-ui-long" type="text" placeholder="<?php echo __('Hotspot Class Name', 'ipanorama'); ?>" x-ng-model="appData.hotspot.selected.config.customClassName">
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set custom classes for a hotspot element.', 'ipanorama'); ?></div></div>
										</div>
										
										<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.custom">
											<textarea class="ipnrm-ui-textarea" cols="40" rows="5" placeholder="<?php echo __('Hotspot HTML Content', 'ipanorama'); ?>" x-ng-model="appData.hotspot.selected.config.customContent"></textarea>
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set content for a hotspot element, if you want to make it complex.', 'ipanorama'); ?></div></div>
										</div>
										
										<div class="ipnrm-ui-control">
											<textarea class="ipnrm-ui-textarea" cols="40" rows="5" placeholder="<?php echo __('Hotspot user data, can be any valid string', 'ipanorama'); ?>" x-ng-model="appData.hotspot.selected.config.userData"></textarea>
											<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set user data for a hotspot element, if you want to use it in your scripts.', 'ipanorama'); ?></div></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="ipnrm-ui-block" x-ng-class="{'ipnrm-ui-folded': appData.config.foldedSections.hotspotPopover}">
							<div class="ipnrm-ui-block-header" x-ng-click="appData.config.foldedSections.hotspotPopover = !appData.config.foldedSections.hotspotPopover;">
								<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set common hotspot popover settings.', 'ipanorama'); ?></div></div>
								<div class="ipnrm-ui-title"><?php echo __('Popover Settings', 'ipanorama'); ?></div>
								<div class="ipnrm-ui-state"></div>
							</div>
							<div class="ipnrm-ui-block-data">
								<div class="ipnrm-ui-control">
									<div x-checkbox class="ipnrm-ui-toggle" x-ng-model="appData.hotspot.selected.config.popover"></div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Enable/disable hotspot popover window.', 'ipanorama'); ?></div></div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div class="ipnrm-ui-label"><?php echo __('Popover Content', 'ipanorama'); ?></div>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set content for a popover window.', 'ipanorama'); ?></div></div>
									<textarea class="ipnrm-ui-textarea" cols="40" rows="5" x-ng-model="appData.hotspot.selected.config.popoverContent"></textarea>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.hotspot.selected.config.popoverHtml"></div>
									<div class="ipnrm-ui-label ipnrm-ui-thin"><?php echo __('The popover content has HTML markup', 'ipanorama'); ?></div>
								</div>
								
								<!--
								<div class="ipnrm-ui-label">Selector</div>
								<div class="ipnrm-ui-control">
									<input type="text" class="ipnrm-ui-long" x-ng-model="appData.hotspot.selected.config.popoverSelector" placeholder="it allows you to set an element's HTML content for the popover">
								</div>
								-->
								
								<!--
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.hotspot.selected.config.popoverLazyload"></div>
									<div class="ipnrm-ui-label ipnrm-ui-thin">Lazyload</div>
								</div>
								-->
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div x-checkbox class="ipnrm-ui-standard" x-ng-model="appData.hotspot.selected.config.popoverShow"></div>
									<div class="ipnrm-ui-label ipnrm-ui-thin"><?php echo __('Show on load', 'ipanorama'); ?></div>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div class="ipnrm-ui-label"><?php echo __('Popover Placement', 'ipanorama'); ?></div><br>
									<div class="ipnrm-ui-helper"><div class="ipnrm-ui-tooltip"><?php echo __('Set a placement property for a popover window.', 'ipanorama'); ?></div></div>
									<select class="ipnrm-ui-select" x-ng-model="appData.hotspot.selected.config.popoverPlacement">
										<option value="default"><?php echo __('default', 'ipanorama'); ?></option>
										<option value="top"><?php echo __('top', 'ipanorama'); ?></option>
										<option value="bottom"><?php echo __('bottom', 'ipanorama'); ?></option>
										<option value="left"><?php echo __('left', 'ipanorama'); ?></option>
										<option value="right"><?php echo __('right', 'ipanorama'); ?></option>
										<option value="top-left"><?php echo __('top-left', 'ipanorama'); ?></option>
										<option value="top-right"><?php echo __('top-right', 'ipanorama'); ?></option>
										<option value="bottom-left"><?php echo __('bottom-left', 'ipanorama'); ?></option>
										<option value="bottom-right"><?php echo __('bottom-right', 'ipanorama'); ?></option>
									</select>
								</div>
								
								<div class="ipnrm-ui-control" x-ng-if="appData.hotspot.selected.config.popover">
									<div class="ipnrm-ui-label"><?php echo __('Popover Custom Width (px)', 'ipanorama'); ?></div><br>
									<input class="ipnrm-ui-number" type="number" min="0" x-ng-model="appData.hotspot.selected.config.popoverWidth">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /end hotspots section -->
		</div>
	</div>
	<div class="ipnrm-ui-modals">
	</div>
	<div id="ipnrm-ui-preview-wrap" x-ng-class="{'ipnrm-ui-active': appData.preview}">
		<button type="button" id="ipnrm-ui-preview-close" aria-label="Close" x-ng-click="appData.fn.previewClose(appData);"><span aria-hidden="true">&times;</span></button>
		<div id="ipnrm-ui-preview-inner">
			<style x-ng-if="appData.config.customCSS">
				{{appData.config.customCSSContent}}
			</style>
			<div id="ipnrm-ui-preview-canvas">
			</div>
		</div>
	</div>
</div>
<!-- /end ipnrm-ui-app -->