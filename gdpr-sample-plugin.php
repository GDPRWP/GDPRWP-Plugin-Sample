<?php
/**
 * GDPR interface
 *
 * @package GDPR Sample Plugin
 * @author  GDPRWP
 *
 * Plugin Name:       GDPR Sample Plugin
 * Plugin URI:        https://github.com/GDPRWP/GDPRWP-Plugin-Sample
 * Description:       This is an example of how a plugin can inform the GDPRWP-Plugin, of the userdata they have created given an email.
 * Version:           1.0
 * Author:            Peytz & Co (Kåre Mulvad Steffensen, Jesper V Nielsen & others)
 * Author URI:        http://peytz.dk/
 * GitHub Plugin URI: https://github.com/GDPRWP/GDPRWP-Plugin-Sample
 */

class GdprSamplePlugin {

	public function __construct() {
		// Create the hooks to run your functions.
		add_action( 'gdpr_init', [ $this, 'set_gdpr_data' ] );
	}

	/**
	 * Inform GDPR, about the data you have created, on behalf on the the user. The object will have helper functions to inform the gdpr of the data, such as "set_field( $args );
	 *
	 * @param object $gdpr
	 * @return void
	 */
	public function set_gdpr_data( $gdpr ) {

		$user_email = $gdpr->get_email();

		//Collect all personal data your plugin has collected on the user
		//get user object
		$user = get_user_by( 'email', $user_email );

		//get meta data, using the $user object
		// $my_custom_data = get_user_meta( $user->ID, 'my_custom_data', true );

		$gdpr->set_key( 'gdpr_sample_plugin' );
		$gdpr->set_plugin_name( 'GGDPR Sample Plugin' );

		// @TODO make a set_purpose / set_description generel for the plugin. -> set_policy();

		$gdpr->set_field(
			[
				'label' => 'Name',
				'value' => 'John Doe',
			]
		);
		$gdpr->set_field(
			[
				'label'          => 'Adress',
				'value'          => 'street, city, zipcode, country ect.',
				'purpose'        => 'lorem ipsum doret sit amor', // a description of the purpose of keeping this data.
				'table_name'     => 'user', // Work in progress. - Should admin be informed of where in the database the data is located?
				'table_key'      => 'c_name',
				'expires'        => 'timestamp', //Work in progress. - Should the admin be informed of when the data is set to expire?
				'latest_updated' => 'timestamp',
				'sensitive'      => true, //Or false. is this data sensitive? Work in progress - Should this be on a scale of 1-5, or low, medium, hig?
			]
		);
		$gdpr->set_field(
			[
				'label' => 'IP Adress',
				'value' => '192.168.x.x',
			]
		);

		$gdpr->set_anonymize_cb( [ $this, 'anonymize_userdata' ] );
		$gdpr->set_anonymize_cb( [ $this, 'test_cb' ] );

	}

	/**
	 * Anonymize the user data, given the gdpr object, and then inform GDPR of witch user data has been anonymized.
	 *
	 * @param object $gdpr
	 * @return void
	 */
	public function anonymize_userdata( $gdpr ) {

		$user_email = $gdpr->get_email();

		$user = get_user_by( 'email', $user_email );

		// Now do the magic of anonymize your data.
		// For now, tell $gdpr what data you has anonymized, so we can tell the Admin, whitch data is anonymized.
		// It is then the Admins responsibillity to let the user know that he now is "forgotten" in the system.

		$gdpr->set_field(
			[
				'label' => 'anon',
				'value' => 'anon Doe',
			]
		);

	}

	public function test_cb( $gdpr ) {
		$gdpr->set_field(
			[
				'label' => 'second',
				'value' => 'brave walls',
			]
		);
	}

}

$gdpr_sample_plugin = new GdprSamplePlugin();
