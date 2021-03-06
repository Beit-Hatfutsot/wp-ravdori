<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Controller\Ajax;

use FernleafSystems\Utilities\Logic\ExecOnce;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\PluginControllerConsumer;
use FernleafSystems\Wordpress\Services\Services;

class Init {

	use ExecOnce;
	use PluginControllerConsumer;

	protected function canRun() :bool {
		return Services::WpGeneral()->isAjax();
	}

	protected function run() {
		add_action( 'wp_ajax_'.$this->getCon()->prefix(), function () {
			$this->ajaxAction();
		} );
		add_action( 'wp_ajax_nopriv_'.$this->getCon()->prefix(), function () {
			$this->ajaxAction();
		} );
	}

	private function ajaxAction() {
		$nonceAction = Services::Request()->request( 'exec' );
		check_ajax_referer( $nonceAction, 'exec_nonce' );

		ob_start();
		$response = apply_filters(
			$this->getCon()->prefix( Services::WpUsers()->isUserLoggedIn() ? 'ajaxAuthAction' : 'ajaxNonAuthAction' ),
			[], $nonceAction
		);
		$noise = ob_get_clean();

		if ( is_array( $response ) && isset( $response[ 'success' ] ) ) {
			$success = $response[ 'success' ];
		}
		else {
			$success = false;
			$response = [];
		}

		( new Response() )->issue(
			[
				'success' => $success,
				'data'    => $response,
				'noise'   => $noise
			],
			false
		);
	}
}