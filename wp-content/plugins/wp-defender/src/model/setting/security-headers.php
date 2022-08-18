<?php

namespace WP_Defender\Model\Setting;

use Calotes\Model\Setting;
use WP_Defender\Component\Security_Headers\Sh_Content_Type_Options;
use WP_Defender\Component\Security_Headers\Sh_Feature_Policy;
use WP_Defender\Component\Security_Headers\Sh_Referrer_Policy;
use WP_Defender\Component\Security_Headers\Sh_Strict_Transport;
use WP_Defender\Component\Security_Headers\Sh_X_Frame;
use WP_Defender\Component\Security_Headers\Sh_XSS_Protection;

class Security_Headers extends Setting {
	/**
	 * Option name
	 * @var string
	 */
	public $table = 'wd_security_headers_settings';

	/**
	 * @var bool
	 * @defender_property
	 */
	public $sh_xframe = false;
	/**
	 * @var string
	 * @defender_property
	 */
	public $sh_xframe_mode = 'sameorigin';
	/**
	 * @var bool
	 * @defender_property
	 */
	public $sh_xss_protection = false;
	/**
	 * @var string
	 * @defender_property
	 */
	public $sh_xss_protection_mode = 'sanitize';
	/**
	 * @var bool
	 * @defender_property
	 */
	public $sh_content_type_options = false;
	/**
	 * @var string
	 * @defender_property
	 */
	public $sh_content_type_options_mode = 'nosniff';
	/**
	 * @var bool
	 * @defender_property
	 */
	public $sh_strict_transport = false;
	/**
	 * @var int
	 * @defender_property
	 */
	public $hsts_preload = 0;
	/**
	 * @var int
	 * @defender_property
	 */
	public $include_subdomain = 0;
	/**
	 * @var string
	 * @defender_property
	 */
	public $hsts_cache_duration = '30 days';
	/**
	 * @var bool
	 * @defender_property
	 */
	public $sh_referrer_policy = false;
	/**
	 * @var string
	 * @defender_property
	 */
	public $sh_referrer_policy_mode = 'origin-when-cross-origin';
	/**
	 * @var bool
	 * @defender_property
	 */
	public $sh_feature_policy = false;
	/**
	 * @var string
	 * @defender_property
	 */
	public $sh_feature_policy_mode = 'self';
	/**
	 * @var string
	 * @defender_property
	 */
	public $sh_feature_policy_urls = '';
	/**
	 * Contains all the data generated by rules
	 * @var array
	 */
	public $data = array();

	/**
	 * Define labels for settings key
	 *
	 * @param  string|null $key
	 *
	 * @return string|array|null
	 */
	public function labels( $key = null ) {
		$labels = array(
			'sh_xframe'                    => __( 'Enable X-Frame-Options', 'wpdef' ),
			'sh_xframe_mode'               => __( 'X-Frame-Options mode', 'wpdef' ),
			'sh_xss_protection'            => __( 'Enable X-XSS-Protection', 'wpdef' ),
			'sh_xss_protection_mode'       => __( 'X-XSS-Protection mode', 'wpdef' ),
			'sh_content_type_options'      => __( 'Enable X-Content-Type-Options', 'wpdef' ),
			'sh_content_type_options_mode' => __( 'X-Content-Type-Options mode', 'wpdef' ),
			'sh_strict_transport'          => __( 'Enable Strict Transport', 'wpdef' ),
			'hsts_preload'                 => __( 'HSTS Preload', 'wpdef' ),
			'include_subdomain'            => __( 'Include Subdomains', 'wpdef' ),
			'hsts_cache_duration'          => __( 'Browser caching', 'wpdef' ),
			'sh_referrer_policy'           => __( 'Enable Referrer Policy', 'wpdef' ),
			'sh_referrer_policy_mode'      => __( 'Referrer Information', 'wpdef' ),
			'sh_feature_policy'            => __( 'Enable Permissions-Policy', 'wpdef' ),
			'sh_feature_policy_mode'       => __( 'Permissions-Policy mode', 'wpdef' ),
			'sh_feature_policy_urls'       => __( 'Specific Origins', 'wpdef' ),
		);

		if ( ! is_null( $key ) ) {
			return $labels[ $key ] ?? null;
		}

		return $labels;
	}

	/**
	 * Get headers
	 *
	 * @return array
	 */
	public function get_headers() {
		return array(
			Sh_X_Frame::$rule_slug                 => new Sh_X_Frame(),
			Sh_XSS_Protection::$rule_slug          => new Sh_XSS_Protection(),
			Sh_Content_Type_Options::$rule_slug    => new Sh_Content_Type_Options(),
			Sh_Strict_Transport::$rule_slug        => new Sh_Strict_Transport(),
			Sh_Referrer_Policy::$rule_slug         => new Sh_Referrer_Policy(),
			Sh_Feature_Policy::$rule_slug          => new Sh_Feature_Policy(),
		);
	}

	/**
	 * Filter the security headers and return data as array
	 *
	 * @param bool $sort
	 *
	 * @return array
	 */
	public function get_headers_as_array( $sort = false ) {
		$headers = $this->get_headers();
		$data    = array();
		foreach ( $headers as $header ) {
			$data[ $header::$rule_slug ] = array(
				'slug'  => $header::$rule_slug,
				'title' => $header->get_title(),
				'diff'  => $header->get_misc_data(),
			);
		}

		if ( $sort ) {
			ksort( $data );
		}

		return $data;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get_data_values( $key ) {
		if ( is_array( $this->data ) && isset( $this->data[ $key ] ) ) {
			return $this->data[ $key ];
		}

		return null;
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function set_data_values( $key, $value ) {
		if ( null === $value ) {
			unset( $this->data[ $key ] );
		} elseif ( is_array( $this->data ) ) {
			$this->data[ $key ] = $value;
		}
		$this->save();
	}

	public function after_validate() {
		if ( true === $this->sh_xframe
			&& ( empty( $this->sh_xframe_mode )
			|| ! in_array( $this->sh_xframe_mode, array( 'sameorigin', 'deny' ), true ) )
		) {
			$this->errors[] = __( 'X-Frame-Options mode is invalid', 'wpdef' );

			return false;
		}

		if ( true === $this->sh_xss_protection
			&& ( empty( $this->sh_xss_protection_mode )
				|| ! in_array( $this->sh_xss_protection_mode, array( 'sanitize', 'block', 'none' ), true ) )
		) {
			$this->errors[] = __( 'X-XSS-Protection mode is invalid', 'wpdef' );

			return false;
		}

		if ( true === $this->sh_referrer_policy
			&& ( empty( $this->sh_referrer_policy_mode )
				|| ! in_array(
					$this->sh_referrer_policy_mode,
					array(
						'no-referrer',
						'no-referrer-when-downgrade',
						'origin',
						'origin-when-cross-origin',
						'same-origin',
						'strict-origin',
						'strict-origin-when-cross-origin',
						'unsafe-url',
					),
					true
				)
			)
		) {
			$this->errors[] = __( 'Referrer Policy mode is invalid', 'wpdef' );

			return false;
		}
	}

	/**
	 * Refresh headers
	 *
	 * @return array
	 */
	public function refresh_headers() {
		$defined_headers = $this->get_headers();
		$enabled         = array();
		foreach ( $defined_headers as $header ) {
			if ( true === $header->check() ) {
				$enabled[] = array(
					'title' => $header->get_title(),
				);
			}
		}
		return $enabled;
	}

	/**
	 * Get active, inactive or both types of headers
	 * @param string $type can be active|inactive|both
	 *
	 * @return array
	 */
	public function get_headers_by_type( $type = 'both' ) {
		$headers = array(
			'active'   => array(),
			'inactive' => array(),
		);
		if ( ! in_array( $type, ['active', 'inactive', 'both'], true ) ) {

			return $headers;
		}
		$url = network_admin_url( 'admin.php?page=wdf-advanced-tools&view=security-headers#' );
		foreach ( $this->get_headers() as $header ) {
			$key = true === $header->check() ? 'active' : 'inactive';
			$headers[ $key ][] = array(
				'title' => $header->get_title(),
				'url'   => $url . $header::$rule_slug
			);
		}

		return 'both' === $type ? $headers : $headers[ $type ];
	}

	/**
	 * @return bool
	 */
	public function is_any_activated() {
		if (
			true === $this->sh_xframe
			|| true === $this->sh_xss_protection
			|| true === $this->sh_content_type_options
			|| true === $this->sh_feature_policy
			|| true === $this->sh_strict_transport
			|| true === $this->sh_referrer_policy
		) {
			return true;
		}

		return false;
	}

	/**
	 * Get enabled headers
	 * @param int $total
	 *
	 * @return array
	 */
	public function get_enabled_headers( $total = 3 ) {
		$defined_headers = $this->get_headers();
		$enabled         = array();
		foreach ( $defined_headers as $header ) {
			if ( true === $header->check() ) {
				$enabled[ $header::$rule_slug ] = array(
					'title' => $header->get_title(),
				);
			}
		}

		return array_slice( $enabled, 0, $total );
	}
}