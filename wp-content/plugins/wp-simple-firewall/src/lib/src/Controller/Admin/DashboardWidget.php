<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Controller\Admin;

use FernleafSystems\Utilities\Logic\ExecOnce;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\PluginControllerConsumer;

class DashboardWidget {

	use PluginControllerConsumer;
	use ExecOnce;

	protected function canRun() :bool {
		$con = $this->getCon();
		return $con->isValidAdminArea() &&
			   apply_filters( 'shield/show_dashboard_widget', $con->cfg->properties[ 'show_dashboard_widget' ] ?? true );
	}

	protected function run() {
		add_action( 'wp_dashboard_setup', function () {
			$this->createWidget();
		} );
	}

	private function createWidget() {
		$con = $this->getCon();
		wp_add_dashboard_widget(
			$con->prefix( 'dashboard_widget' ),
			apply_filters( $con->prefix( 'dashboard_widget_title' ), $con->getHumanName() ),
			function () {
				do_action( $this->getCon()->prefix( 'dashboard_widget_content' ) );
			}
		);
	}
}
