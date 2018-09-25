<?php

class WP_Auth0_Admin_Basic extends WP_Auth0_Admin_Generic {

	/**
	 *
	 * @deprecated 3.6.0 - Use $this->_description instead
	 */
	const BASIC_DESCRIPTION = '';

	protected $_description;

	protected $actions_middlewares = array(
		'basic_validation',
	);

<<<<<<< HEAD
	public function init() {

		/* ------------------------- BASIC ------------------------- */
		add_action( 'wp_ajax_auth0_delete_cache_transient', array( $this, 'auth0_delete_cache_transient' ) );

		$this->init_option_section( '', 'basic', array(

				array( 'id' => 'wpa0_domain', 'name' => 'Domain', 'function' => 'render_domain' ),
				array( 'id' => 'wpa0_client_id', 'name' => 'Client ID', 'function' => 'render_client_id' ),
				array( 'id' => 'wpa0_client_secret', 'name' => 'Client Secret', 'function' => 'render_client_secret' ),
				array( 'id' => 'wpa0_client_secret_b64_encoded', 'name' => 'Client Secret Base64 Encoded', 'function' => 'render_client_secret_b64_encoded' ),
				array( 'id' => 'wpa0_client_signing_algorithm', 'name' => 'Client Signing Algorithm', 'function' => 'render_client_signing_algorithm' ),
				array( 'id' => 'wpa0_cache_expiration', 'name' => 'Cache Time (minutes)', 'function' => 'render_cache_expiration' ),
				array( 'id' => 'wpa0_auth0_app_token', 'name' => 'API token', 'function' => 'render_auth0_app_token' ), //we are not going to show the token
				array( 'id' => 'wpa0_login_enabled', 'name' => 'WordPress login enabled', 'function' => 'render_allow_wordpress_login' ),
				array( 'id' => 'wpa0_allow_signup', 'name' => 'Allow signup', 'function' => 'render_allow_signup' ),

			) );
	}


	public function render_client_id() {
		$v = $this->options->get( 'client_id' );
?>
      <input type="text" name="<?php echo $this->options->get_options_name(); ?>[client_id]" id="wpa0_client_id" value="<?php echo esc_attr( $v ); ?>"/>
      <div class="subelement">
        <span class="description"><?php echo __( 'Application ID, copy from your application\'s settings in the', WPA0_LANG ); ?> <a href="https://manage.auth0.com/#/applications" target="_blank">Auth0 dashboard</a>.</span>
      </div>
    <?php
	}

	public function render_auth0_app_token() {

		$scopes = WP_Auth0_Api_Client::GetConsentScopestoShow();
		$v = $this->options->get( 'auth0_app_token' );

?>
      <input type="text" name="<?php echo $this->options->get_options_name(); ?>[auth0_app_token]" id="wpa0_auth0_app_token" autocomplete="off" <?php if ( !empty( $v ) ) {?>placeholder="Not visible"<?php } ?> />
      <div class="subelement">
        <span class="description">
          <?php echo __( 'The token should be generated via the ', WPA0_LANG ); ?>
          <a href="https://auth0.com/docs/api/v2" target="_blank"><?php echo __( 'token generator', WPA0_LANG ); ?></a>
          <?php echo __( ' with the following scopes:', WPA0_LANG ); ?>
          <i>
          <?php $a = 0; foreach ( $scopes as $resource => $actions ) { $a++;?>
            <b><?php echo $resource ?></b> (<?php echo $actions ?>)<?php
			if ( $a < count( $scopes ) - 1 ) {
				echo ", ";
			} else if ( $a === count( $scopes ) - 1 ) {
					echo " and ";
				}
?>
          <?php } ?>.
          </i>
        </span>
      </div>
    <?php
	}

	public function render_client_secret() {
		$v = $this->options->get( 'client_secret' );
	?>
      <input type="text" autocomplete="off" name="<?php echo $this->options->get_options_name(); ?>[client_secret]" id="wpa0_client_secret"  <?php if ( !empty( $v ) ) {?>placeholder="Not visible"<?php } ?> />
      <div class="subelement">
        <span class="description"><?php echo __( 'Application secret, copy from your application\'s settings in the', WPA0_LANG ); ?> <a href="https://manage.auth0.com/#/applications" target="_blank">Auth0 dashboard</a>.</span>
      </div>
  <?php
	}

	public function render_client_secret_b64_encoded() {
		$v = absint( $this->options->get( 'client_secret_b64_encoded' ) );

		echo $this->render_a0_switch( "wpa_client_secret_b64_encoded", "client_secret_b64_encoded", 1, 1 == $v );
	?>
				<div class="subelement">
					<span class="description"><?php echo __( 'Enable if your client secret is base64 enabled.  If you are not sure, check your clients page in Auth0.  Displayed below the client secret on that page is the text "The Client Secret is not base64 encoded.
	" when this is not encoded.', WPA0_LANG ); ?></span>
				</div>
			<?php
	}

  public function render_client_signing_algorithm(){
		$v = $this->options->get( 'client_signing_algorithm' );
	?>

    <select id="wpa0_client_signing_algorithm" name="<?php echo $this->options->get_options_name() ?>[client_signing_algorithm]">
    	<option value="HS256" <?php echo ($v === "HS256" ? 'selected' : '') ?>>HS256</option>
    	<option value="RS256" <?php echo ($v === "RS256" ? 'selected' : '') ?>>RS256</option>
    </select>
    <div class="subelement">
			<span class="description"><?php echo __( 'If you use the default client secret to sign tokens, select HS256. See your clients page in Auth0. Advanced > OAuth > JsonWebToken Signature Algorithm', WPA0_LANG ); ?></span>
		</div>
  <?php  
 	}

 public function render_cache_expiration() {
 		$v = $this->options->get( 'cache_expiration' );
 	?>
 	   <script>
	    function DeleteCacheTransient(event) {
	      event.preventDefault();

	      var data = {
	        'action': 'auth0_delete_cache_transient',
	      };

	      jQuery('#auth0_delete_cache_transient').attr('disabled', 'true');

	      jQuery.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', data, function(response) {

	        jQuery('#auth0_delete_cache_transient').val('Done!').attr('disabled', 'true');

	      }, 'json');

	    }
    </script>

     <input type="number" name="<?php echo $this->options->get_options_name(); ?>[cache_expiration]" id="wpa0_cache_expiration" value="<?php echo esc_attr( $v ); ?>" />
     
     <input type="button" onclick="DeleteCacheTransient(event);" name="auth0_delete_cache_transient" id="auth0_delete_cache_transient" value="Delete Cache" class="button button-secondary" />

  		<div class="subelement">
				<span class="description"><?php echo __( 'JWKS cache expiration in minutes (0 = no caching)', WPA0_LANG ); ?></span>
			</div>
	<?php
 	}

	public function render_domain() {
		$v = $this->options->get( 'domain' );
	?>
      <input type="text" name="<?php echo $this->options->get_options_name(); ?>[domain]" id="wpa0_domain" value="<?php echo esc_attr( $v ); ?>" />
      <div class="subelement">
        <span class="description"><?php echo __( 'Your Auth0 domain, you can see it in the', WPA0_LANG ); ?> <a href="https://manage.auth0.com/#/applications" target="_blank">Auth0 dashboard</a><?php echo __( '. Example: foo.auth0.com', WPA0_LANG ); ?></span>
      </div>
  <?php
=======
	/**
	 * WP_Auth0_Admin_Basic constructor.
	 *
	 * @param WP_Auth0_Options_Generic $options
	 */
	public function __construct( WP_Auth0_Options_Generic $options ) {
		parent::__construct( $options );
		$this->_description = __( 'Basic settings related to the Auth0 integration.', 'wp-auth0' );
>>>>>>> upstream/master
	}

	/**
	 * All settings in the Basic tab
	 *
	 * @see \WP_Auth0_Admin::init_admin
	 * @see \WP_Auth0_Admin_Generic::init_option_section
	 */
	public function init() {
		add_action( 'wp_ajax_auth0_delete_cache_transient', array( $this, 'auth0_delete_cache_transient' ) );

		$options = array(
			array(
				'name'     => __( 'Domain', 'wp-auth0' ),
				'opt'      => 'domain',
				'id'       => 'wpa0_domain',
				'function' => 'render_domain',
			),
			array(
				'name'     => __( 'Custom Domain', 'wp-auth0' ),
				'opt'      => 'custom_domain',
				'id'       => 'wpa0_custom_domain',
				'function' => 'render_custom_domain',
			),
			array(
				'name'     => __( 'Client ID', 'wp-auth0' ),
				'opt'      => 'client_id',
				'id'       => 'wpa0_client_id',
				'function' => 'render_client_id',
			),
			array(
				'name'     => __( 'Client Secret', 'wp-auth0' ),
				'opt'      => 'client_secret',
				'id'       => 'wpa0_client_secret',
				'function' => 'render_client_secret',
			),
			array(
				'name'     => __( 'Client Secret Base64 Encoded', 'wp-auth0' ),
				'opt'      => 'client_secret_b64_encoded',
				'id'       => 'wpa0_client_secret_b64_encoded',
				'function' => 'render_client_secret_b64_encoded',
			),
			array(
				'name'     => __( 'JWT Signature Algorithm', 'wp-auth0' ),
				'opt'      => 'client_signing_algorithm',
				'id'       => 'wpa0_client_signing_algorithm',
				'function' => 'render_client_signing_algorithm',
			),
			array(
				'name'     => __( 'Cache Time (in minutes)', 'wp-auth0' ),
				'opt'      => 'cache_expiration',
				'id'       => 'wpa0_cache_expiration',
				'function' => 'render_cache_expiration',
			),
			array(
				'name'     => __( 'API Token', 'wp-auth0' ),
				'opt'      => 'auth0_app_token',
				'id'       => 'wpa0_auth0_app_token',
				'function' => 'render_auth0_app_token',
			),
			array(
				'name'     => __( 'WordPress Login Enabled', 'wp-auth0' ),
				'opt'      => 'wordpress_login_enabled',
				'id'       => 'wpa0_login_enabled',
				'function' => 'render_allow_wordpress_login',
			),
			array(
				'name'     => __( 'Allow Signups', 'wp-auth0' ),
				'id'       => 'wpa0_allow_signup',
				'function' => 'render_allow_signup',
			),
		);
		$this->init_option_section( '', 'basic', $options );
	}

	/**
	 * Render form field and description for the `domain` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_domain( $args = array() ) {
		$this->render_text_field( $args['label_for'], $args['opt_name'], 'text', 'your-tenant.auth0.com' );
		$this->render_field_description(
			__( 'Auth0 Domain, found in your Application settings in the ', 'wp-auth0' ) .
			$this->get_dashboard_link( 'applications' )
		);
	}

	/**
	 * Render form field and description for the `custom_domain` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_custom_domain( $args = array() ) {
		$this->render_text_field( $args['label_for'], $args['opt_name'], 'text', 'login.yourdomain.com' );
		$this->render_field_description(
			__( 'Custom login domain. ', 'wp-auth0' ) .
			$this->get_docs_link( 'custom-domains', __( 'More information here', 'wp-auth0' ) )
		);
	}

	/**
	 * Render form field and description for the `client_id` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_client_id( $args = array() ) {
		$this->render_text_field( $args['label_for'], $args['opt_name'] );
		$this->render_field_description(
			__( 'Client ID, found in your Application settings in the ', 'wp-auth0' ) .
			$this->get_dashboard_link( 'applications' )
		);
	}

	/**
	 * Render form field and description for the `client_secret` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_client_secret( $args = array() ) {
		$this->render_text_field( $args['label_for'], $args['opt_name'], 'password' );
		$this->render_field_description(
			__( 'Client Secret, found in your Application settings in the ', 'wp-auth0' ) .
			$this->get_dashboard_link( 'applications' )
		);
	}

	/**
	 * Render form field and description for the `client_secret_b64_encoded` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_client_secret_b64_encoded( $args = array() ) {
		$this->render_switch( $args['label_for'], $args['opt_name'] );
		$this->render_field_description(
			__( 'Enable this if your Client Secret is base64 encoded. ', 'wp-auth0' ) .
			__( 'This information is found below the Client Secret field in the ', 'wp-auth0' ) .
			$this->get_dashboard_link( 'applications' )
		);
	}

	/**
	 * Render form field and description for the `client_signing_algorithm` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_client_signing_algorithm( $args = array() ) {
		$curr_value = $this->options->get( $args['opt_name'] ) ?: WP_Auth0_Api_Client::DEFAULT_CLIENT_ALG;
		$this->render_radio_buttons(
			array( 'HS256', 'RS256' ),
			$args['label_for'],
			$args['opt_name'],
			$curr_value
		);
		$this->render_field_description(
			__( 'This value can be found the Application settings in the ' ) .
			$this->get_dashboard_link( 'applications' ) .
			__( ' under Show Advanced Settings > OAuth > "JsonWebToken Signature Algorithm"', 'wp-auth0' )
		);
	}

	/**
	 * Render form field and description for the `cache_expiration` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_cache_expiration( $args = array() ) {
		$this->render_text_field( $args['label_for'], $args['opt_name'], 'number' );
		printf(
			' <input type="button" id="auth0_delete_cache_transient" value="%s" class="button button-secondary">',
			__( 'Delete Cache', 'wp-auth0' )
		);
		$this->render_field_description( __( 'JWKS cache expiration in minutes (use 0 for no caching)', 'wp-auth0' ) );
		if ( $domain = $this->options->get( 'domain' ) ) {
			$this->render_field_description(
				sprintf(
					'<a href="https://%s/.well-known/jwks.json" target="_blank">%s</a>',
					$domain,
					__( 'View your JWKS here', 'wp-auth0' )
				)
			);
		}
	}

	/**
	 * Render form field and description for the `auth0_app_token` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_auth0_app_token( $args = array() ) {
		$this->render_text_field( $args['label_for'], $args['opt_name'], 'password' );
		$this->render_field_description(
			__( 'This token should include the following scopes: ', 'wp-auth0' ) .
			'<br><br><code>' . implode( '</code> <code>', WP_Auth0_Api_Client::ConsentRequiredScopes() ) .
			'</code><br><br>' . $this->get_docs_link(
				'api/management/v2/tokens#get-a-token-manually',
				__( 'More information on manually generating tokens', 'wp-auth0' )
			)
		);
	}

	/**
	 * Render form field and description for the `wordpress_login_enabled` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @param array $args - callback args passed in from add_settings_field().
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_allow_wordpress_login( $args = array() ) {
		$this->render_switch( $args['label_for'], $args['opt_name'] );
		$this->render_field_description(
			__( 'Turn on to enable a link on wp-login.php pointing to the core login form. ', 'wp-auth0' ) .
			__( 'Logins and signups using the WordPress form will NOT be pushed to Auth0. ', 'wp-auth0' ) .
			__( 'This is typically only used while testing the plugin initially', 'wp-auth0' )
		);
	}

	/**
	 * Render description for the `wpa0_allow_signup` option.
	 * IMPORTANT: Internal callback use only, do not call this function directly!
	 *
	 * @see WP_Auth0_Admin_Generic::init_option_section()
	 * @see add_settings_field()
	 */
	public function render_allow_signup() {
		if ( is_multisite() ) {
			$settings_text = __(
				'"Allow new registrations" in the Network Admin > Settings > Network Settings',
				'wp-auth0'
			);
		} else {
			$settings_text = __( '"Anyone can register" in the WordPress General Settings', 'wp-auth0' );
		}
		$allow_signup = $this->options->is_wp_registration_enabled();
		$this->render_field_description(
			__( 'Signups are currently ', 'wp-auth0' ) . '<b>' .
			( $allow_signup ? __( 'enabled', 'wp-auth0' ) : __( 'disabled', 'wp-auth0' ) ) .
			'</b>' . __( ' by this setting ' ) . $settings_text
		);
	}

	public function auth0_delete_cache_transient() {
		check_ajax_referer( 'auth0_delete_cache_transient' );
		delete_transient( WPA0_JWKS_CACHE_TRANSIENT_NAME );
		exit;
	}
	public function auth0_delete_cache_transient() {
		if ( ! is_admin() ) return;

		WP_Auth0_ErrorManager::insert_auth0_error( 'WP_Auth0_Admin_Basic::delete_cache_transient', 'deleting cache transient' );

		delete_transient('WP_Auth0_JWKS_cache');

	}
	public function basic_validation( $old_options, $input ) {

		if ( wp_cache_get( 'doing_db_update', WPA0_CACHE_GROUP ) ) {
			return $input;
		}

		$input['client_id']        = sanitize_text_field( $input['client_id'] );
		$input['cache_expiration'] = absint( $input['cache_expiration'] );

		$input['wordpress_login_enabled'] = ( isset( $input['wordpress_login_enabled'] )
			? $input['wordpress_login_enabled']
			: 0 );

<<<<<<< HEAD
		$input['client_id'] = sanitize_text_field( $input['client_id'] );
		$input['cache_expiration'] = absint( $input['cache_expiration'] );
		$input['wordpress_login_enabled'] = ( isset( $input['wordpress_login_enabled'] ) ? $input['wordpress_login_enabled'] : 0 );
=======
>>>>>>> upstream/master
		$input['allow_signup'] = ( isset( $input['allow_signup'] ) ? $input['allow_signup'] : 0 );

		// Only replace the secret or token if a new value was set. If not, we will keep the last one entered.
		$input['client_secret'] = ( ! empty( $input['client_secret'] )
			? $input['client_secret']
			: $old_options['client_secret'] );

		$input['client_secret_b64_encoded'] = ( isset( $input['client_secret_b64_encoded'] )
			? $input['client_secret_b64_encoded'] == 1
			: false );

		$input['auth0_app_token'] = ( ! empty( $input['auth0_app_token'] )
			? $input['auth0_app_token']
			: $old_options['auth0_app_token'] );

		// If we have an app token, get and store the audience
		if ( ! empty( $input['auth0_app_token'] ) ) {
			$db_manager = new WP_Auth0_DBManager( WP_Auth0_Options::Instance() );

			if ( get_option( 'wp_auth0_client_grant_failed' ) ) {
				$db_manager->install_db( 16, $input['auth0_app_token'] );
			}

			if ( get_option( 'wp_auth0_grant_types_failed' ) ) {
				$db_manager->install_db( 17, $input['auth0_app_token'] );
			}
		}

		if ( empty( $input['domain'] ) ) {
			$this->add_validation_error( __( 'You need to specify a domain', 'wp-auth0' ) );
		}

		if ( empty( $input['client_id'] ) ) {
			$this->add_validation_error( __( 'You need to specify a client id', 'wp-auth0' ) );
		}

		if ( empty( $input['client_secret'] ) && empty( $old_options['client_secret'] ) ) {
			$this->add_validation_error( __( 'You need to specify a client secret', 'wp-auth0' ) );
		}

<<<<<<< HEAD
		if ( empty( $input['cache_expiration'] ) && empty( $old_options['cache_expiration'] ) ) {
			$error = __( 'You need to specify a number for cache expiration', WPA0_LANG );
			$this->add_validation_error( $error );
			$completeBasicData = false;
		}

=======
>>>>>>> upstream/master
		return $input;
	}

	/**
	 *
	 * @deprecated 3.6.0 - Should not be called directly, handled within WP_Auth0_Admin_Basic::render_allow_signup()
	 */
	public function render_allow_signup_regular_multisite() {
		// phpcs:ignore
		trigger_error( sprintf( __( 'Method %s is deprecated.', 'wp-auth0' ), __METHOD__ ), E_USER_DEPRECATED );
	}

<<<<<<< HEAD
}
=======
	/**
	 *
	 * @deprecated 3.6.0 - Should not be called directly, handled within WP_Auth0_Admin_Basic::render_allow_signup()
	 */
	public function render_allow_signup_regular() {
		// phpcs:ignore
		trigger_error( sprintf( __( 'Method %s is deprecated.', 'wp-auth0' ), __METHOD__ ), E_USER_DEPRECATED );
	}

	/**
	 *
	 * @deprecated 3.6.0 - Handled by WP_Auth0_Admin_Generic::render_description()
	 */
	public function render_basic_description() {
		// phpcs:ignore
		trigger_error( sprintf( __( 'Method %s is deprecated.', 'wp-auth0' ), __METHOD__ ), E_USER_DEPRECATED );
		printf( '<p class="a0-step-text">%s</p>', $this->_description );
	}
}
>>>>>>> upstream/master
