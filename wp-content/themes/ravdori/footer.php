<?php
/**
 * Footer
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.3.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php

	get_template_part('views/footer/footer');
	wp_footer();

?>

<?php if ( 'wizard.php' == basename( get_page_template() ) && defined( 'AWSM_EMBED_VERSION' ) ) {

	$ead = Awsm_embed::get_instance();
	$url = plugins_url( 'embed-any-document' );

	?>

	<div id="embed-popup-wrap" style="display: none;">
		<div id="embed-popup">
			<button title="<?php esc_html_e( 'Close', 'embed-any-document' ); ?>" type="button" class="ead-close">×</button>
			<div id="popup-header" class="ead-popup-header">
				<h1><?php esc_html_e( 'Add Document', 'embed-any-document' ); ?></h1>
			</div>
			<div class="ead-section">
				<div id="embed-message" class="awsm-error" style="display:none;"><p></p></div>
				<div class="ead-container">
					<form action="" onSubmit="return false" method="post" enctype="multipart/form-data" id="docuploader">
						<ul class="ead-options">
							<li>
								<a href="#" id="upload-doc"><span><img src="<?php echo $url; ?>/images/icon-upload.png" alt="Upload document"/><?php esc_html_e( 'Upload Document', 'embed-any-document' ); ?></span></a>
							</li>
							<li>
								<a href="#" id="add-ead-document"><span><img src="<?php echo $url; ?>/images/icon-link.png" alt="Add From URL"/><?php esc_html_e( 'Add from URL', 'embed-any-document' ); ?></span></a>
							</li>
							<li><?php $ead->providerlink( 'Drive' ); ?></li>
							<li><?php $ead->providerlink( 'Dropbox' ); ?></li>
							<li><?php $ead->providerlink( 'Box' ); ?></li>
						</ul>
						<div class="ead-url-box addurl-box">
							<label for="awsm-url"><?php esc_html_e( 'Enter document URL', 'embed-any-document' ); ?></label>
							<input name="awsm-url" type="text" class="opt dwl input-group-text" placeholder="Eg: http://www.yoursite.com/file.pdf" id="awsm-url"/>
							<input type="button" value="<?php esc_html_e( 'Add URL', 'embed-any-document' ); ?>" class="ead-btn button-primary input-group-btn" id="ead-add-url"/>
							<div class="clear"></div>
							<a href="#" class="go-back">&larr; <?php esc_html_e( 'back', 'embed-any-document' ); ?></a>
						</div>
					</form>
				</div><!--ead-container-->
				<div class="upload-success">
					<div class="inner">
						<div class="uploaded-doccument">
							<p id="ead-filename"></p>
							<span id="ead-filesize"></span>
						</div>
						<div class="clear"></div>
					</div>
					<div class="advanced-options">
						<h3><?php esc_html_e( 'Advanced Options', 'embed-any-document' ); ?></span></h3>
						<ul class="option-fields">
							<li>
								<div class="f-left"><label><?php esc_html_e( 'Width', 'embed-any-document' ); ?></label>
									<input type="text" name="width" class="embedval input-small" id="ead-width" value="<?php echo esc_attr( get_option( 'ead_width', '100%' ) ); ?>">
								</div>
								<div class="f-left"><label><?php esc_html_e( 'Height', 'embed-any-document' ); ?></label>
									<input type="text" name="height" class="embedval input-small" id="ead-height" value="<?php echo esc_attr( get_option( 'ead_height', '100%' ) ); ?>">
								</div>
								<div class="f-left" id="ead-downloadc">
									<label><?php esc_html_e( 'Show Download Link', 'embed-any-document' ); ?></label>
									<?php
									$downoptions = array(
										'all'    => __( 'For all users', 'embed-any-document' ),
										'logged' => __( 'For Logged-in users', 'embed-any-document' ),
										'none'   => __( 'No Download', 'embed-any-document' ),
									);
									$ead->selectbuilder( 'ead-download', $downoptions, esc_attr( get_option( 'ead_download', 'none' ) ), 'ead-usc' );
									?>
								</div>
								<div class="f-left" id="ead-download-text">
									<label><?php esc_html_e( 'Download Text', 'embed-any-document' ); ?></label>
									<input type="text" name="text" class="embedval" id="ead-text" value="<?php echo esc_attr( get_option( 'ead_text', 'Download' ) ); ?>">
								</div>
								<div class="f-left last" id="new-provider">
									<label><?php esc_html_e( 'Viewer', 'embed-any-document' ); ?></label>
									<?php
									if ( method_exists( 'Awsm_embed', 'get_viewers' ) ) {
										$ead->selectbuilder( 'ead-provider', Awsm_embed::get_viewers(), esc_attr( get_option( 'ead_provider', 'google' ) ), 'ead-usc' );
									}
									?>
								</div>
								<div class="f-left last" id="ead-pseudo" style="display:none">
									<label><?php esc_html_e( 'Viewer', 'embed-any-document' ); ?></label>
									<select name="ead-pseudo" disabled>
										<option value="box"><?php esc_html_e( 'Box', 'embed-any-document' ); ?></option>
										<option value="drive"><?php esc_html_e( 'Drive', 'embed-any-document' ); ?></option>
									</select>
								</div>
								<div class="clear"></div>
								<div class="ead-browser-viewer-note" style="display: none;">
									<?php
										/* translators: %1$s: strong opening tag, %2$s: strong closing tag */
										printf( esc_html__( '%1$s Note:%2$s Browser based PDF embedding feature is not supported by certain browsers and some external servers. Google viewer will be used as a fallback for the unsupported browsers.', 'embed-any-document' ), '<strong>', '</strong>' );
									?>
								</div>
							</li>
							<li class="checkbox" id="eadcachemain">
								<input type="checkbox" id="ead-cache" class="ead-usc" value="on">
								<label for="ead-cache">
									<?php esc_html_e( 'Do not cache this file (Affects performance)', 'embed-any-document' ); ?>
								</label>
							</li>
							<li>
								<label><?php esc_html_e( 'Shortcode Preview', 'embed-any-document' ); ?></label>
								<textarea name="shortcode" style="width:100%" id="shortcode" readonly="readonly"></textarea>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="mceActionPanel ead-action-panel">
				<div style="float: right">
					<input type="button" id="insert-doc" name="insert" data-txt="<?php esc_html_e( 'Insert', 'embed-any-document' ); ?>" data-loading="<?php esc_html_e( 'Loading...', 'embed-any-document' ); ?>" class="ead-btn button button-primary button-medium" value="<?php esc_html_e( 'Insert', 'embed-any-document' ); ?>" disabled/>
				</div>

				<div style="float: left">
					<input type="button" name="cancel" class="ead-btn button cancel-embed button-medium" value="<?php esc_html_e( 'Cancel', 'embed-any-document' ); ?>"/>
				</div>
				<div class="clear"></div>
			</div>

		</div>
	</div>

<?php } ?>

<div id="bthn" lang="en"></div><script>
(function () {
   var script = document.createElement("script");
   script.type = "text/javascript";
   script.src = "https://bringthemhomenow.net/1.0.6/hostages-ticker.js";
   script.setAttribute(
     "integrity",
     "sha384-HUMoFr9Q+E+J/4Miy+33M85Tsz7Ls2OA5PN5YpvwrLsg+c33uz/QN2MU0U36okGG"
   );
   script.setAttribute("crossorigin", "anonymous");
   document.getElementsByTagName("head")[0].appendChild(script);
 })();
</script>

</body>
</html>