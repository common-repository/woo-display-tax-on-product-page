<?php
 /*

 * Plugin Name: WooCommerce display tax on product page 

 * Version: 1.0

 * Plugin URI: http://www.mlfactory.at/display-tax-on-product-page

 * Description: This small plugin displays the tax rate on the product page

 * Author: Michael Leithold

 * Author URI: http://www.mlfactory.at

 * Requires at least: 4.0

 * Tested up to: 4.0

 * License: GPLv2 or later

 * Text Domain: woo-dtopp

 *

*/
 

 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
defined( 'ABSPATH' ) or exit;
// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}


//Frontend part
class woo_dtopp {
	
	 public static function init() {
        add_action( 'woocommerce_single_product_summary', __CLASS__ . '::woocommerce_template_display_tax', 15 );

	 }
	 


public static function woocommerce_template_display_tax() {
    global $product;
    $tax_rates = WC_Tax::get_rates( $product->get_tax_class() );
    if (!empty($tax_rates)) {
        $tax_rate = reset($tax_rates);
		$text_before = get_option('wc_settings_wootopp_text_before');
		$text_after = get_option('wc_settings_wootopp_text_after');
		$font_size = get_option('wc_settings_wootopp_text_font_size');
		echo sprintf(__('<div style="font-size:'.$font_size.'">'.$text_before.' %.2f %% '.$text_after.'</div>', 'Text for tax rate. %.2f = tax rate', 'wptheme.foundation'), $tax_rate['rate']);
		
    }
}
}

woo_dtopp::init();



//Backend part
class woo_dtopp_settings_tab {
    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_demo', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_demo', __CLASS__ . '::update_settings' );
    }
    
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_demo'] = __( 'Tax on product page', 'woo-dtopp-settings-tag' );
        return $settings_tabs;
    }
    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }
    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }
    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {
		

        $settings = array(
            'section_title' => array(
                'name'     => __( 'Display tax rate on product page', 'woo-dtopp-settings-tag' ),
                'type'     => 'title',
                'desc'     => 'Setting for displaying tax on produt page. <br />',
                'id'       => 'wc_settings_wootopp_text_before1'
            ),
            'title' => array(
                'name' => __( 'Title', 'woo-dtopp-settings-tag' ),
                'type' => 'text',
				'default' => 'Inclusive',
                'desc' => __( 'Text before x%', 'woo-dtopp-settings-tag' ),
                'id'   => 'wc_settings_wootopp_text_before'
            ),
            'description' => array(
                'name' => __( 'Description', 'woo-dtopp-settings-tag' ),
                'type' => 'text',
				'default' => 'tax',
                'desc' => __( 'Text after x%', 'woo-dtopp-settings-tag' ),
                'id'   => 'wc_settings_wootopp_text_after'
            ),
			'font_size' => array(
                'name' => __( 'Font Size', 'woo-dtopp-settings-tag' ),
                'type' => 'text',
				'default' => '14px',
                'desc' => __( 'Font size of the text on the product page under the product price.', 'woo-dtopp-settings-tag' ),
                'id'   => 'wc_settings_wootopp_text_font_size'
            ),
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_demo_section_end'
            )
        );
        return apply_filters( 'wc_settings_tab_demo_settings', $settings );
    }
}
woo_dtopp_settings_tab::init();