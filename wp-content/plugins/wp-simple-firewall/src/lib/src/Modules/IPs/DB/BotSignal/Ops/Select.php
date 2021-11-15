<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\IPs\DB\BotSignal\Ops;

use FernleafSystems\Wordpress\Plugin\Core\Databases\Base;
use FernleafSystems\Wordpress\Plugin\Shield\Databases\Base\Traits\Select_IPTable;

class Select extends Base\Select {

	use Common;
}