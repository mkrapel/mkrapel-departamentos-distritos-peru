<?php
/*
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 *
 * Plugin Name: Departamentos y Distritos de Perú para WooCommerce
 * Plugin URI: https://marketingrapel.cl/
 * Description: Plugin con los Departamentos y Distritos de Perú actualizado al 2020, permitiendo usar los Distritos para establecer las Zonas de Despacho en la sección de Envíos de WooCommerce. Retira campos de Código Postal y Línea 2 de la Dirección en el CheckOut, junto con nueva distribución visible.
 * Version: 3.2
 * Requires at least: 5.0
 * Requires PHP: 5.6
 * Author: Marketing Rapel
 * Author URI: https://marketingrapel.cl
 * License: GPLv3
 * Text Domain: mkrapel-departamentos-distritos-peru
 * Domain Path: /languages
 * Tested up to: 5.4
 * WC requires at least: 4.0.0
 * WC tested up to: 4.3.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('plugins_loaded','mkrapel_pe_departamentos_distritos_peru_init',1);
add_filter('woocommerce_checkout_fields', 'mkrapel_pe_nombre_campos');
add_filter('woocommerce_checkout_fields', 'mkrapel_pe_campos_quitados');
add_filter('woocommerce_checkout_fields', 'mkrapel_pe_campos_class');
add_filter('woocommerce_checkout_fields', 'mkrapel_pe_campos_orden');


if ( ! function_exists( 'mkrapel_pe_smp_notices' ) ) {
	function mkrapel_pe_smp_notices($classes, $notice){
		?>
		<div class="<?php echo $classes; ?>">
			<p><?php echo $notice; ?></p>
		</div>
		<?php
	}
}
if ( ! function_exists( 'mkrapel_pe_departamentos_distritos_peru_init' ) ) {
	function mkrapel_pe_departamentos_distritos_peru_init(){
		load_plugin_textdomain('mkrapel-departamentos-distritos-peru',
			FALSE, dirname(plugin_basename(__FILE__)) . '/languages');

		/* Check if WooCommerce is active */
		if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

			require_once ('includes/states-places.php');
			require_once ('includes/filter-by-cities.php');

			global $pagenow;
			$GLOBALS['wc_states_places'] = new MkRapel_Departamentos_Distritos_PE(__FILE__);

			add_filter( 'woocommerce_shipping_methods', 'mkrapel_pe_add_filters_by_cities_method' );
			add_action( 'woocommerce_shipping_init', 'mkrapel_pe_filters_by_cities_method' );
			
			if ( ! function_exists( 'mkrapel_pe_add_filters_by_cities_method' ) ) {
				function mkrapel_pe_add_filters_by_cities_method( $methods ) {
					$methods['mkrapel_pe_filters_by_cities_shipping_method'] = 'MkRapel_PE_Filters_By_Cities_Method';
					return $methods;
				}
			}
			if ( is_admin() && 'plugins.php' == $pagenow && !defined( 'DOING_AJAX' ) ) {
				add_action('admin_notices', function() use($subs) {
					mkrapel_pe_smp_notices('notice notice-info is-dismissible', $subs);
				});
			}
		}
	}
}
if ( ! function_exists( 'mkrapel_pe_nombre_campos' ) ) {
	function mkrapel_pe_nombre_campos( $fields ) {
		$fields['billing']['billing_first_name']['placeholder'] = 'Su Nombre';
		$fields['billing']['billing_last_name']['placeholder'] = 'Sus Apellidos';
		$fields['billing']['billing_address_1']['placeholder'] = 'Nombre de la Calle, Número, Depto, Local, Oficina';
		$fields['billing']['billing_company']['placeholder'] = 'Digite su DNI';
		$fields['billing']['billing_country']['placeholder'] = 'Seleccione País';
		$fields['billing']['billing_state']['placeholder'] = 'Seleccione Departamento';
		$fields['billing']['billing_city']['placeholder'] = 'Seleccione Distrito';
		$fields['billing']['billing_email']['placeholder'] = 'Su Email';
		$fields['billing']['billing_phone']['placeholder'] = 'Su Celular o Teléfono';

		$fields['billing']['billing_address_1']['label'] = 'Dirección';
		$fields['billing']['billing_company']['label'] = 'DNI';
		$fields['billing']['billing_country']['label'] = 'País';
		$fields['billing']['billing_state']['label'] = 'Departamento';
		$fields['billing']['billing_city']['label'] = 'Distrito';


		$fields['shipping']['shipping_first_name']['placeholder'] = 'Su Nombre';
		$fields['shipping']['shipping_last_name']['placeholder'] = 'Sus Apellidos';
		$fields['shipping']['shipping_address_1']['placeholder'] = 'Nombre de la Calle, Número, Depto, Local, Oficina';
		$fields['shipping']['shipping_company']['placeholder'] = 'Digite su DNI';
		$fields['shipping']['shipping_country']['placeholder'] = 'Seleccione País';
		$fields['shipping']['shipping_state']['placeholder'] = 'Seleccione Departamento';
		$fields['shipping']['shipping_city']['placeholder'] = 'Seleccione Distrito';
		$fields['shipping']['shipping_email']['placeholder'] = 'Su Email';
		$fields['shipping']['shipping_phone']['placeholder'] = 'Su Celular o Teléfono';

		$fields['shipping']['shipping_address_1']['label'] = 'Dirección';
		$fields['shipping']['shipping_company']['label'] = 'DNI';
		$fields['shipping']['shipping_country']['label'] = 'País';
		$fields['shipping']['shipping_state']['label'] = 'Departamento';
		$fields['shipping']['shipping_city']['label'] = 'Distrito';

		return $fields;
	}
}
if ( ! function_exists( 'mkrapel_pe_campos_quitados' ) ) {
	function mkrapel_pe_campos_quitados( $fields ) {
		unset($fields['billing']['billing_address_2']);
		unset($fields['billing']['billing_postcode']);

		unset($fields['shipping']['shipping_address_2']);
		unset($fields['shipping']['shipping_postcode']);

		return $fields;
	}
}
if ( ! function_exists( 'mkrapel_pe_campos_class' ) ) {
	function mkrapel_pe_campos_class($fields){
		$fields['billing']['billing_first_name']['class'][0] = 'form-row-first';
		$fields['billing']['billing_last_name']['class'][0] = 'form-row-last';
		$fields['billing']['billing_company']['class'][0] = 'form-row-first';
		$fields['billing']['billing_country']['class'][0] = 'form-row-last';
		$fields['billing']['billing_address_1']['class'][0] = 'form-row-wide';
		$fields['billing']['billing_state']['class'][0] = 'form-row-first';
		$fields['billing']['billing_city']['class'][0] = 'form-row-last';
		$fields['billing']['billing_phone']['class'][0] = 'form-row-first';
		$fields['billing']['billing_email']['class'][0] = 'form-row-last';

		$fields['shipping']['shipping_first_name']['class'][0] = 'form-row-first';
		$fields['shipping']['shipping_last_name']['class'][0] = 'form-row-last';
		$fields['shipping']['shipping_company']['class'][0] = 'form-row-first';
		$fields['shipping']['shipping_country']['class'][0] = 'form-row-last';
		$fields['shipping']['shipping_address_1']['class'][0] = 'form-row-wide';
		$fields['shipping']['shipping_state']['class'][0] = 'form-row-first';
		$fields['shipping']['shipping_city']['class'][0] = 'form-row-last';
		$fields['shipping']['shipping_phone']['class'][0] = 'form-row-first';
		$fields['shipping']['shipping_email']['class'][0] = 'form-row-last';

		return $fields;
	}
}
if ( ! function_exists( 'mkrapel_pe_campos_orden' ) ) {
	function mkrapel_pe_campos_orden($fields){
		$fields['billing']['billing_first_name']['priority'] = 10;
		$fields['billing']['billing_last_name']['priority'] = 20;
		$fields['billing']['billing_company']['priority'] = 30;
		$fields['billing']['billing_country']['priority'] = 40;
		$fields['billing']['billing_address_1']['priority'] = 50;
		$fields['billing']['billing_state']['priority'] = 60;
		$fields['billing']['billing_city']['priority'] = 70;
		$fields['billing']['billing_phone']['priority'] = 80;
		$fields['billing']['billing_email']['priority'] = 90;

		$fields['shipping']['shipping_first_name']['priority'] = 10;
		$fields['shipping']['shipping_last_name']['priority'] = 20;
		$fields['shipping']['shipping_company']['priority'] = 30;
		$fields['shipping']['shipping_country']['priority'] = 40;
		$fields['shipping']['shipping_address_1']['priority'] = 50;
		$fields['shipping']['shipping_state']['priority'] = 60;
		$fields['shipping']['shipping_city']['priority'] = 70;
		$fields['shipping']['shipping_phone']['priority'] = 80;
		$fields['shipping']['shipping_email']['priority'] = 90;

		return $fields;
	}
}
?>