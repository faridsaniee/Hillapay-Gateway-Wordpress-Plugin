<?php
/*
Plugin Name: Hillapay Payment Gateway for WooCommerce
Version: 1.0.3
Description: Hillapay Payment Gateway Plugin's for WooCommerce
Plugin URI: https://hillapay.ir/
Author: Farid Saniee Pour
Author URI: https://www.linkedin.com/in/faridsaniee/
*/
if(!defined('ABSPATH')) exit;
define('WC_GPPDIR', plugin_dir_path( __FILE__ ));
function Load_hillapay_Gateway(){
	/* Show Debug In Console */
	function plugin_link( $actions, $plugin_file ) 
	{
	   static $plugin;
	   if (!isset($plugin))
	        $plugin = plugin_basename(__FILE__);
	   if ($plugin == $plugin_file) {
	      $settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=checkout&section=hillapay_gateway">' . __('تنظیمات', 'General') . '</a>');
	      //$site_link = array('support' => '<a href="http://thetechterminus.com" target="_blank">Support</a>');
	      $actions = array_merge($settings, $actions);
	      //$actions = array_merge($site_link, $actions);
	   }
	   return $actions;
	}
	function plugin_description_link( $actions, $plugin_file ) 
	{
	   	static $plugin;
	   	if (!isset($plugin)){$plugin = plugin_basename(__FILE__);}
	   	if ($plugin == $plugin_file) {
	 
	 	  	$site_web = array('settings' => '<a href="https://hillapay.ir/?utm_source=plugin&utm_medium=link&utm_campaign=wordpressplugin&utm_id=wordpress_site_pluginpage">' . __('وب سایت هیلاپی', 'General') . '</a>');
	      	$site_panel = array('Site' => '<a href="https://panel.hillapay.ir/merchant?utm_source=plugin&utm_medium=link&utm_campaign=wordpressplugin&utm_id=wordpress_site_pluginpage">' . __('پنل پذیرنده', 'General') . '</a>');	         
	      	$actions = array_merge($site_panel, $actions);
	      	$actions = array_merge($site_web, $actions);
		}
	 	return $actions;
	}
	function WC_GPP_Debug_Log($Debug_Mode='no', $object=null, $label=null ){
		if($Debug_Mode === 'yes'){
			$object = $object; 
			$message = json_encode( $object, JSON_UNESCAPED_UNICODE);
			$label = "Debug".($label ? " ($label): " : ': '); 
			echo "<script>console.log(\"$label\", $message);</script>";

			file_put_contents(WC_GPPDIR.'/log_hillapay.txt', $label."\n".$message."\n\n", FILE_APPEND);
		}
	}
	/* Add Hillapay Gateway Method */
	add_filter('woocommerce_payment_gateways', 'hillapay_Gateway_Add');
	function hillapay_Gateway_Add($methods){
		$methods[] = 'hillapay_gateway';
		return $methods;
	}
	/* Add Iranian Currencies Woocommerce */
	add_filter('woocommerce_currencies', 'add_IR_currency_For_HillaPAY');
	function add_IR_currency_For_HillaPAY($currencies){
		$currencies['IRR'] = __('ریال', 'woocommerce');
		$currencies['IRT'] = __('تومان', 'woocommerce');
		$currencies['IRHR'] = __('هزار ریال', 'woocommerce');
		$currencies['IRHT'] = __('هزار تومان', 'woocommerce');
		return $currencies;
	}
	/* Add Iranian Currencies Symbols Woocommerce */
	add_filter('woocommerce_currency_symbol', 'add_IR_currency_symbol_For_HillaPAY', 10, 2);
	function add_IR_currency_symbol_For_HillaPAY($currency_symbol, $currency){
		switch ($currency) {
			case 'IRR':
				$currency_symbol = 'ریال';
				break;
			case 'IRT':
				$currency_symbol = 'تومان';
				break;
			case 'IRHR':
				$currency_symbol = 'هزار ریال';
				break;
			case 'IRHT':
				$currency_symbol = 'هزار تومان';
				break;
		}
		return $currency_symbol;
	}
	require_once( WC_GPPDIR . 'class-gateway-hillapay.php' );
}
add_action('plugins_loaded', 'Load_hillapay_Gateway', 0);
add_filter( 'plugin_action_links', 'plugin_link', 10, 5 );
add_filter( 'plugin_row_meta', 'plugin_description_link', 10, 2 );