<div class="ai1wm-field-set">
	<ul class="ai1wmme-sites-list">
		<li>
			<label for="ai1wmme-network">
				<input type="checkbox" name="options[network]" value="1" id="ai1wmme-network" class="ai1wmme-network" checked="checked" />
				<?php _e( 'Network (all sites)', AI1WMME_PLUGIN_NAME ); ?>
			</label>
		</li>
		<?php foreach ( ai1wmme_sites() as $site ) :  ?>
			<li>
				<label for="ai1wmme-site-<?php echo $site['BlogID']; ?>">
					<input type="checkbox" name="options[sites][]" value="<?php echo $site['BlogID']; ?>" id="ai1wmme-site-<?php echo $site['BlogID']; ?>" class="ai1wmme-sites" />
					<?php echo $site['Domain'] . untrailingslashit( $site['Path'] ); ?>
				</label>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
