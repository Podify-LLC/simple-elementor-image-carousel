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
	public function __construct( $file, $github_user, $github_repo, $token_constant = 'PODIFY_GITHUB_TOKEN' ) {
		$this->file           = $file;
		$this->plugin_slug    = plugin_basename( $file );
		$this->github_user    = $github_user;
		$this->github_repo    = $github_repo;
		$this->token_constant = $token_constant;

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3 );
		add_filter( 'upgrader_source_selection', array( $this, 'source_selection' ), 10, 4 );
		add_filter( 'http_request_args', array( $this, 'http_request_args' ), 10, 2 );
		add_action( 'wp_ajax_seic_get_logo_url', array( $this, 'ajax_get_logo_url' ) );
	}

	/**
	 * AJAX handler to get the logo URL from GitHub assets.
	 */
	public function ajax_get_logo_url() {
		check_ajax_referer( 'seic_admin_nonce' );
		
		$release = $this->get_latest_release();
		if ( $release ) {
			$logo_url = $this->get_asset_url( $release, 'logo.png' );
			$logo_cropped_url = $this->get_asset_url( $release, 'logo_cropped.png' );
			
			wp_send_json_success( array( 
				'logo_url' => $logo_url ? $logo_url : '',
				'logo_cropped_url' => $logo_cropped_url ? $logo_cropped_url : $logo_url
			) );
		}
		
		wp_send_json_error();
	}

	/**
	 * Injects the update data into the WordPress update check.
	 */
	public function check_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$release = $this->get_latest_release();
		if ( ! $release || empty( $release->tag_name ) ) {
			return $transient;
		}

		$current_version = $transient->checked[ $this->plugin_slug ];
		$new_version     = ltrim( $release->tag_name, 'v' );

		// Check for specific version file if provided as an asset
		$version_asset = $this->get_asset_url( $release, 'version.txt' );
		if ( $version_asset ) {
			$version_response = wp_remote_get( $version_asset, array(
				'headers' => array(
					'Authorization' => 'Bearer ' . (defined($this->token_constant) ? constant($this->token_constant) : ''),
					'Accept'        => 'application/octet-stream'
				)
			) );
			if ( ! is_wp_error( $version_response ) && wp_remote_retrieve_response_code( $version_response ) === 200 ) {
				$remote_version = trim( wp_remote_retrieve_body( $version_response ) );
				if ( ! empty( $remote_version ) ) {
					$new_version = ltrim( $remote_version, 'v' );
				}
			}
		}

		if ( version_compare( $current_version, $new_version, '<' ) ) {
			$package = $this->get_zip_url( $release );

			// If no manual ZIP asset is found, we don't fall back to zipball for private repos
			// to avoid the "commit hash" folder name issue and authentication failures.
			if ( ! $package ) {
				return $transient;
			}

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

		$new_version = ltrim( $release->tag_name, 'v' );
		$version_asset = $this->get_asset_url( $release, 'version.txt' );
		if ( $version_asset ) {
			$version_response = wp_remote_get( $version_asset, array(
				'headers' => array(
					'Authorization' => 'Bearer ' . (defined($this->token_constant) ? constant($this->token_constant) : ''),
					'Accept'        => 'application/octet-stream'
				)
			) );
			if ( ! is_wp_error( $version_response ) && wp_remote_retrieve_response_code( $version_response ) === 200 ) {
				$remote_version = trim( wp_remote_retrieve_body( $version_response ) );
				if ( ! empty( $remote_version ) ) {
					$new_version = ltrim( $remote_version, 'v' );
				}
			}
		}

		$res              = new \stdClass();
		$res->name        = 'Simple Elementor Image Carousel';
		$res->slug        = $args->slug;
		$res->version     = $new_version;
		$res->author      = 'Podify Inc.';
		$res->homepage    = "https://github.com/{$this->github_user}/{$this->github_repo}";
		$res->download_link = $this->get_zip_url( $release );
		
		// Add plugin icon and banner from GitHub assets
		$logo_url = $this->get_asset_url( $release, 'logo.png' );
		if ( $logo_url ) {
			$res->icons = array(
				'1x' => $logo_url,
				'2x' => $logo_url,
			);
			$res->banners = array(
				'low'  => $logo_url,
				'high' => $logo_url,
			);
		}

		$res->sections    = array(
			'description' => ! empty( $release->body ) ? $release->body : 'No description provided.',
			'changelog'   => 'Check GitHub releases for changelog.',
		);

		return $res;
	}

	/**
	 * Injects the Authorization header for GitHub API requests.
	 */
	public function http_request_args( $args, $url ) {
		// Only intercept requests to GitHub API or asset domains
		if ( strpos( $url, 'api.github.com' ) !== false || strpos( $url, 'codeload.github.com' ) !== false || strpos( $url, 'objects.githubusercontent.com' ) !== false ) {
			$token = defined( $this->token_constant ) ? constant( $this->token_constant ) : '';
			
			// Always provide a User-Agent for GitHub API compliance
			$args['headers']['User-Agent'] = 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_home_url();

			if ( $token ) {
				$args['headers']['Authorization'] = 'Bearer ' . $token;
				
				// Critical: GitHub API requires specific Accept header for discovery
				if ( strpos( $url, 'api.github.com' ) !== false && strpos( $url, '/releases/assets/' ) === false ) {
					$args['headers']['Accept'] = 'application/vnd.github+json';
				}

				// Critical: Release assets REQUIRE application/octet-stream for download
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
		$transient_key = 'seic_github_release_' . $this->github_repo;
		
		// When manually checking via AJAX, we want to bypass the cache
		$is_ajax_check = ( defined( 'DOING_AJAX' ) && isset( $_POST['action'] ) && $_POST['action'] === 'seic_check_updates' );
		
		if ( ! $is_ajax_check ) {
			$cached = get_site_transient( $transient_key );
			if ( $cached !== false ) {
				return $cached;
			}
		}
		
		$url = "https://api.github.com/repos/{$this->github_user}/{$this->github_repo}/releases/latest";
		
		$args = array(
			'headers' => array(
				'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_home_url()
			)
		);
		$token = defined( $this->token_constant ) ? constant( $this->token_constant ) : '';
		if ( $token ) {
			$args['headers']['Authorization'] = 'Bearer ' . $token;
			$args['headers']['Accept']        = 'application/vnd.github+json';
		}

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$release = json_decode( $body );
		
		if ( $release && ! isset( $release->message ) ) {
			set_site_transient( $transient_key, $release, HOUR_IN_SECONDS );
			return $release;
		}
		
		return false;
	}

	/**
	 * Finds the ZIP asset URL or returns false if none found.
	 */
	private function get_zip_url( $release ) {
		if ( ! empty( $release->assets ) && is_array( $release->assets ) ) {
			foreach ( $release->assets as $asset ) {
				if ( isset( $asset->name ) && preg_match( '/\.zip$/i', $asset->name ) ) {
					// We use the API URL, not the browser download URL
					return $asset->url;
				}
			}
		}
		return false;
	}

	/**
	 * Finds a specific asset by name.
	 */
	private function get_asset_url( $release, $name ) {
		if ( ! empty( $release->assets ) && is_array( $release->assets ) ) {
			foreach ( $release->assets as $asset ) {
				if ( isset( $asset->name ) && $asset->name === $name ) {
					return $asset->url;
				}
			}
		}
		return false;
	}
}
