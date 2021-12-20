<?php
/**
 * Embed Any Document
 *
 * @author     Htmline
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.3.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * BH_eod_scripts_n_styles
 *
 * @param   N/A
 * @return  N/A
 */
function BH_eod_scripts_n_styles() {

	BH_eod_register_styles();
	BH_eod_register_scripts();

}
add_action( 'wp', 'BH_eod_scripts_n_styles' );

/**
 * BH_eod_register_styles
 *
 * @param   N/A
 * @return  N/A
 */
function BH_eod_register_styles() {

	if ( 'wizard.php' == basename( get_page_template() ) && defined( 'AWSM_EMBED_VERSION' ) ) {

		$ead = plugins_url( 'embed-any-document' );
		wp_enqueue_style( 'ead_media_button', $ead . '/css/embed.min.css', false, VERSION, 'all' );

	}

}

/**
 * BH_eod_register_scripts
 *
 * @param   N/A
 * @return  N/A
 */
function BH_eod_register_scripts() {

	if ( 'wizard.php' == basename( get_page_template() ) && defined( 'AWSM_EMBED_VERSION' ) ) {

		$ead = plugins_url( 'embed-any-document' );

		wp_enqueue_script( 'ead_media_button', $ead . '/js/embed.min.js', array( 'jquery' ), VERSION, true );

		wp_localize_script(
			'ead_media_button',
			'emebeder',
			array(
				'viewers'       => array_keys( Awsm_embed::get_viewers() ),
				'height'        => get_option( 'ead_height', '100%' ),
				'width'         => get_option( 'ead_width', '100%' ),
				'download'      => get_option( 'ead_download', 'none' ),
				'text'          => get_option( 'ead_text', __( 'Download', 'embed-any-document' ) ),
				'provider'      => get_option( 'ead_provider', 'google' ),
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'site_url'      => site_url( '/' ),
				'validtypes'    => Awsm_embed::get_instance()->validembedtypes(),
				'msextension'   => Awsm_embed::get_instance()->validextensions( 'ms' ),
				'drextension'   => Awsm_embed::get_instance()->validextensions( 'all' ),
				'nocontent'     => __( 'Nothing to insert', 'embed-any-document' ),
				'invalidurl'    => __( 'Invalid URL', 'embed-any-document' ),
				'addurl'        => __( 'Add URL', 'embed-any-document' ),
				'verify'        => __( 'Verifying...', 'embed-any-document' ),
				'from_url'      => __( 'From URL', 'embed-any-document' ),
				'select_button' => __( 'Select', 'embed-any-document' ),
			)
		);

	}

}