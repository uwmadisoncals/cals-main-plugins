<div class="ai1wm-modal-sites">
	<?php if ( count( $networks ) > 1 ) : ?>
		<p>
			<label for="ai1wmme-new-network-id">
				<?php _e( 'Network', AI1WMME_PLUGIN_NAME ); ?>
				<br />
				<select id="ai1wmme-new-network-id" name="options[network]">
					<?php foreach ( $networks as $network ) : ?>
						<option value="<?php echo $network['id']; ?>"><?php echo $network['domain'] . untrailingslashit( $network['path'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</p>
	<?php else : ?>
		<?php if ( ( $network = get_current_site() ) ) : ?>
			<input type="hidden" name="options[network]" value="<?php echo $network->id; ?>" />
		<?php endif; ?>
	<?php endif; ?>
	<p>
		<label for="ai1wmme-old-subsite-url">
			<?php _e( 'Old subsite URL', AI1WMME_PLUGIN_NAME ); ?>
			<br />
			<input type="text" id="ai1wmme-old-subsite-url" value="<?php echo $sites[ $subsite ]['Old']['HomeURL']; ?>" disabled="disabled" />
		</label>
	</p>
	<p>
		<label for="ai1wmme-new-subsite-url">
			<?php _e( 'New subsite URL', AI1WMME_PLUGIN_NAME ); ?>
			<br />
			<input type="text" id="ai1wmme-new-subsite-url" name="options[sites][<?php echo $subsite; ?>]" value="<?php echo $sites[ $subsite ]['New']['HomeURL']; ?>" />
		</label>
	</p>
	<input type="hidden" name="options[subsite]" value="<?php echo $subsite; ?>" />
</div>
