<?php

namespace SEIC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Github_Updater
 *
 * Handles plugin updates from a private GitHub repository.
 */
class Github_Updater {

	private $file;
	private $plugin_slug;
	private $github_user;
	private $github_repo;
	private $token_constant;

	/**
	 * Github_Updater constructor.
	 *
	 * @param string $file           Path to the main plugin file.
	 * @param string $github_user    GitHub username/organization.
	 * @param string $github_repo    GitHub repository name.
	 * @param string $token_constant Name of the constant holding the GitHub PAT.
	 */
	public function __construct( $file, $github_user, $github_repo, $token_constant = 'SEIC_GITHUB_TOKEN' ) {
		$this->file           = $file;
		$this->plugin_slug    = plugin_basename( $file );
		$this->github_user    = $github_user;
		$this->github_repo    = $github_repo;
		$this->token_constant = $token_constant;

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3 );
		add_filter( 'upgrader_source_selection', array( $this, 'source_selection' ), 10, 4 );
		add_filter( 'http_request_args', array( $this, 'http_request_args' ), 10, 2 );
	}

	/**
	 * Injects the update data into the WordPress update check.
	 */
	public function check_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$release = $this->get_latest_release();
		if ( ! $release ) {
			return $transient;
		}

		$current_version = $transient->checked[ $this->plugin_slug ];
		$new_version     = ltrim( $release->tag_name, 'v' );

		if ( version_compare( $current_version, $new_version, '<' ) ) {
			$package = $this->get_zip_url( $release );

			$obj              = new \stdClass();
			$obj->slug        = dirname( $this->plugin_slug );
			$obj->new_version = $new_version;
			$obj->url         = "https://github.com/{$this->github_user}/{$this->github_repo}";
			$obj->package     = $package;

			$transient->response[ $this->plugin_slug ] = $obj;
		}

		return $transient;
	}

	/**
	 * Provides the "View version details" modal content.
	 */
	public function plugin_popup( $result, $action, $args ) {
		if ( $action !== 'plugin_information' ) {
			return $result;
		}

		if ( ! isset( $args->slug ) || $args->slug !== dirname( $this->plugin_slug ) ) {
			return $result;
		}

		$release = $this->get_latest_release();
		if ( ! $release ) {
			return $result;
		}

		$res              = new \stdClass();
		$res->name        = 'Simple Elementor Image Carousel';
		$res->slug        = $args->slug;
		$res->version     = ltrim( $release->tag_name, 'v' );
		$res->author      = 'Podify Inc.';
		$res->homepage    = "https://github.com/{$this->github_user}/{$this->github_repo}";
		$res->download_link = $this->get_zip_url( $release );
		$res->sections    = array(
			'description' => $release->body,
			'changelog'   => 'Check GitHub releases for changelog.',
		);

		return $res;
	}

	/**
	 * Injects the Authorization header for GitHub API requests.
	 */
	public function http_request_args( $args, $url ) {
		if ( strpos( $url, 'api.github.com' ) !== false || strpos( $url, 'codeload.github.com' ) !== false || strpos( $url, 'objects.githubusercontent.com' ) !== false ) {
			$token = defined( $this->token_constant ) ? constant( $this->token_constant ) : '';
			if ( $token ) {
				$args['headers']['Authorization'] = 'Bearer ' . $token;
				$args['headers']['Accept']        = 'application/vnd.github+json';

				// Critical for private asset downloads
				if ( strpos( $url, '/releases/assets/' ) !== false ) {
					$args['headers']['Accept'] = 'application/octet-stream';
				}
			}
		}
		return $args;
	}

	/**
	 * Fixes the folder name after extraction.
	 */
	public function source_selection( $source, $remote_source, $upgrader, $hook_extra ) {
		$plugin_slug_only = dirname( $this->plugin_slug );
		
		if ( strpos( $source, $plugin_slug_only ) === false ) {
			return $source;
		}

		$corrected_source = trailingslashit( $remote_source ) . $plugin_slug_only . '/';
		if ( rename( $source, $corrected_source ) ) {
			return $corrected_source;
		}
		return $source;
	}

	/**
	 * Fetches the latest release from GitHub API.
	 */
	private function get_latest_release() {
		$url = "https://api.github.com/repos/{$this->github_user}/{$this->github_repo}/releases/latest";
		
		$args = array();
		$token = defined( $this->token_constant ) ? constant( $this->token_constant ) : '';
		if ( $token ) {
			$args['headers'] = array(
				'Authorization' => 'Bearer ' . $token,
				'Accept'        => 'application/vnd.github+json',
			);
		}

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		return json_decode( $body );
	}

	/**
	 * Finds the ZIP asset URL or falls back to zipball_url.
	 */
	private function get_zip_url( $release ) {
		if ( ! empty( $release->assets ) ) {
			foreach ( $release->assets as $asset ) {
				if ( substr( $asset->name, -4 ) === '.zip' ) {
					return $asset->url; // Use API URL for private assets
				}
			}
		}
		return $release->zipball_url;
	}
}
