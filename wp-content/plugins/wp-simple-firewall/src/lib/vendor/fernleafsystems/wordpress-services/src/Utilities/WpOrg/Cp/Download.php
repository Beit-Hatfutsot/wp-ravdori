<?php

namespace FernleafSystems\Wordpress\Services\Utilities\WpOrg\Cp;

use FernleafSystems\Wordpress\Services\Services;
use FernleafSystems\Wordpress\Services\Utilities\HttpUtil;

class Download {

	/**
	 * @param string $version
	 * @return string
	 * @throws \Exception
	 */
	public function version( $version ) {
		$tmpFile = null;

		try {
			$url = $this->getZipDownloadUrl( $version );
			if ( !empty( $version ) ) {
				$tmpFile = ( new HttpUtil() )
					->downloadUrl( $url );
			}
		}
		catch ( \Exception $e ) {
		}
		return $tmpFile;
	}

	/**
	 * @param $version
	 * @return string|null
	 */
	private function getZipDownloadUrl( $version ) {
		$url = null;
		$versions = @json_decode( Services::HttpRequest()->getContent( Repo::GetUrlForVersions() ), true );

		if ( is_array( $versions ) ) {
			foreach ( $versions as $version ) {
				if ( $version[ 'tag_name' ] == $version ) {
					$url = $version[ 'zipball_url' ];
					break;
				}
			}
		}

		return $url;
	}
}