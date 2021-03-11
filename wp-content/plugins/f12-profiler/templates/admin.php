<div class="f12-profiler">
	<h2>F12-Profiler</h2>

	<form action="" method="post" name="f12_profiler_options">
		<table>
			<tr>
				<td>
					<label for="active">
						<?php _e( 'Activate?', 'f12_profiler' ); ?>
					</label>
				</td>
				<td>
					<input type="checkbox" name="active" value="1" id="active" <?php if ( $atts['active'] == 1 ) {
						echo 'checked="checked"';
					} ?>/>
					<span>
						<?php _e( 'Enable / Disable the F12-Profiler plugin', 'f12_profiler' ); ?>
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<label for="page_metrics">
						<?php _e( 'Show Page Resource Metrics?', 'f12_profiler' ); ?>
					</label>
				</td>
				<td>
					<input type="checkbox" name="page_metrics" value="1"
						   id="page_metrics" <?php if ( $atts['page_metrics'] == 1 ) {
						echo 'checked="checked"';
					} ?>/>
					<span>
						<?php _e( 'Enable / Disable Page Resource Metrics', 'f12_profiler' ); ?>
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<label for="hardware_metrics">
						<?php _e( 'Show Hardware Metrics?', 'f12_profiler' ); ?>
					</label>
				</td>
				<td>
					<input type="checkbox" name="hardware_metrics" value="1"
						   id="hardware_metrics" <?php if ( $atts['hardware_metrics'] == 1 ) {
						echo 'checked="checked"';
					} ?>/>
					<span>
						<?php _e( 'Enable / Disable Hardware Metrics', 'f12_profiler' ); ?>
					</span>
					<p>
						<?php _e( 'Important: This functions are in beta and can affect the performance for users logged in within the backend of your system.', 'f12_profiler' ); ?>
					</p>
				</td>
			</tr>
		</table>

		<input type="submit" name="f12_profiler_save" class="button" value="<?php _e( 'Save', 'f12_profiler' ); ?>"/>
	</form>

	<?php if ( isset( $atts['hardware'] ) ) {
		echo $atts['hardware'];
	} ?>
</div>