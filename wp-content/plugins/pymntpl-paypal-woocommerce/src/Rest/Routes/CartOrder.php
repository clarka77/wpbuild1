<?php

namespace PaymentPlugins\WooCommerce\PPCP\Rest\Routes;

use PaymentPlugins\PayPalSDK\Order;
use PaymentPlugins\PayPalSDK\OrderApplicationContext;
use PaymentPlugins\WooCommerce\PPCP\Admin\Settings\AdvancedSettings;
use PaymentPlugins\WooCommerce\PPCP\Constants;

/**
 * Route that is called when the PayPal integration requests an order ID.
 */
class CartOrder extends AbstractCart {

	private $settings;

	public function __construct( AdvancedSettings $settings, ...$args ) {
		parent::__construct( ...$args );
		$this->settings = $settings;
	}

	public function get_path() {
		return 'cart/order';
	}

	public function get_routes() {
		return [
			[
				'methods'  => \WP_REST_Server::CREATABLE,
				'callback' => [ $this, 'handle_request' ],
				'args'     => [
					'payment_method' => [
						'required' => true
					]
				]
			]
		];
	}

	public function handle_post_request( \WP_REST_Request $request ) {
		wc_maybe_define_constant( 'WOOCOMMERCE_CHECKOUT', true );
		if ( $this->is_checkout_initiated( $request ) ) {
			// only update the customer data if this is the checkout page since the product and cart page
			// don't have any input fields that should update the customer
			$this->update_customer_data( WC()->customer, $request );

			if ( $this->settings->is_shipping_address_disabled() ) {
				add_action( 'wc_ppcp_get_order_from_cart', function ( Order $order ) {
					// If the application context is allowing the address to be changed, override that.
					if ( $order->getApplicationContext()->getShippingPreference() === OrderApplicationContext::GET_FROM_FILE ) {
						$order->getApplicationContext()->setShippingPreference( OrderApplicationContext::SET_PROVIDED_ADDRESS );
					}
				} );
			}
		}
		$this->populate_post_data( $request );
		$this->calculate_totals();
		$order = $this->get_order_from_cart( $request );
		try {
			if ( $this->is_checkout_initiated( $request ) ) {
				if ( $this->is_checkout_validation_enabled( $request ) ) {
					$this->validate_checkout_fields( WC()->customer, $request );
				}
				/**
				 * 3rd party code can use this action to perform custom validations.
				 *
				 * @since 1.0.31
				 */
				do_action( 'wc_ppcp_validate_checkout_fields', $request );
			}

			$result = $this->client->orders->create( $order );
			if ( is_wp_error( $result ) ) {
				/**
				 * @var \WP_Error $result
				 */
				if ( $result->get_error_code() === 'MISSING_SHIPPING_ADDRESS' ) {
					throw new \Exception( __( 'Please enter a valid shipping address.', 'pymntpl-paypal-woocommerce' ) );
				}
				throw new \Exception( $result->get_error_message() );
			}
			$this->cache->set( Constants::CAN_UPDATE_ORDER_DATA, true );
			$this->cache->set( Constants::PAYPAL_ORDER_ID, $result->id );

			return $result->id;
		} catch ( \Exception $e ) {
			$this->logger->error( sprintf( 'Error creating PayPal order. Msg:%s Params: %s', $e->getMessage(), print_r( $order->toArray(), true ) ) );
			throw new \Exception( $e->getMessage(), 400 );
		}
	}

	/**
	 * @param \WC_Customer     $customer
	 * @param \WP_REST_Request $request
	 */
	private function update_customer_data( $customer, $request ) {
		$customer->set_billing_email( isset( $request['billing_email'] ) ? $request['billing_email'] : '' );

		$fields         = [ 'first_name', 'last_name', 'country', 'state', 'postcode', 'city', 'address_1', 'address_2', 'phone' ];
		$billing_prefix = apply_filters( 'wc_ppcp_cart_order_billing_prefix', 'billing', $request );
		$props          = [];
		foreach ( $fields as $field ) {
			$key                     = "{$billing_prefix}_{$field}";
			$props["billing_$field"] = isset( $request[ $key ] ) ? wc_clean( wp_unslash( $request[ $key ] ) ) : '';
		}
		if ( wc_ship_to_billing_address_only() ) {
			$customer->set_props( $props );
		} else {
			$shipping_prefix = apply_filters( 'wc_ppcp_cart_order_shipping_prefix', isset( $request['ship_to_different_address'] ) ? 'shipping' : 'billing', $request );
			foreach ( $fields as $field ) {
				$key                      = "{$shipping_prefix}_{$field}";
				$props["shipping_$field"] = isset( $request[ $key ] ) ? wc_clean( wp_unslash( $request[ $key ] ) ) : '';
			}
			$customer->set_props( $props );
		}
	}

	/**
	 * @param \WC_Customer $customer
	 *
	 * @return void
	 */
	private function validate_checkout_fields( $customer, $request ) {
		$checkout = WC()->checkout();
		$fields   = $checkout ? $checkout->get_checkout_fields() : [];
		if ( WC()->cart && ! WC()->cart->needs_shipping() ) {
			unset( $fields['shipping'] );
		}
		/**
		 * @since 1.0.37
		 */
		$fields = apply_filters( 'wc_ppcp_checkout_validation_fields', $fields, $request );

		foreach ( $fields as $fieldset_key => $fieldset ) {
			if ( $fieldset_key === 'account' ) {
				if ( is_user_logged_in() || ( ! $checkout->is_registration_required() && empty( $request['createaccount'] ) ) ) {
					continue;
				}
			}
			foreach ( $fieldset as $key => $field ) {
				/**
				 * Use wc_string_to_bool in case some 3rd party plugins change 'required' to a string like 'true'.
				 */
				if ( isset( $field['required'] ) && wc_string_to_bool( $field['required'] ) ) {
					$field_label = $field['label'] ?? $key;
					switch ( $fieldset_key ) {
						case 'billing':
						case 'shipping':
							$method = "get_{$key}";
							if ( method_exists( $customer, $method ) ) {
								$value = $customer->{$method}();
							} else {
								$value = $request[ $key ] ?? '';
							}
							break;
						default:
							$value = $request[ $key ] ?? '';
							break;
					}
					if ( ! \is_string( $value ) || ! strlen( $value ) ) {
						/**
						 * Use substr to determine the if the key type is for billing or shipping. Some 3rd party
						 * plugins manipulate the field set value so better to rely on the $key
						 */
						if ( substr( $key, 0, 7 ) === 'billing' ) {
							$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label );
						} elseif ( substr( $key, 0, 8 ) === 'shipping' ) {
							$field_label = sprintf( _x( 'Shipping %s', 'checkout-validation', 'woocommerce' ), $field_label );
						}
						throw new \Exception( esc_html( apply_filters( 'wc_ppcp_checkout_field_validation_label',
								sprintf( __( '%s is a required field.', 'woocommerce' ), $field_label ),
								$field,
								$key,
								$fieldset_key
							)
						) );
					}
				}
			}
		}
	}

	/**
	 * @param $request
	 *
	 * @since 1.0.28
	 * @return bool
	 */
	private function is_checkout_initiated( $request ) {
		return isset( $request['ppcp_payment_type'] ) && $request['ppcp_payment_type'] === 'checkout';
	}

	/**
	 * @since 1.0.28
	 * @return bool
	 */
	private function is_checkout_validation_enabled( $request ) {
		return apply_filters( 'wc_ppcp_is_checkout_validation_enabled', \wc_string_to_bool( $this->settings->get_option( 'validate_checkout', 'no' ) ), $request );
	}

}