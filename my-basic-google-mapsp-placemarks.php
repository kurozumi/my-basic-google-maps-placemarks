<?php
/**
 * Plugin Name: MY Basic Google Maps Placemarks
 * Version: 0.1-alpha
 * Description: PLUGIN DESCRIPTION HERE
 * Author: kurozumi
 * Author URI: http://a-zumi.net
 * Plugin URI: PLUGIN SITE HERE
 * Text Domain: my-basic-google-maps-placemarks
 * Domain Path: /languages
 * @package My-basic-google-maps-placemarks
 */

add_action('plugins_loaded', function() {

    if( !class_exists( 'BasicGoogleMapsPlacemarks' ) )
        return;

    class MY_BasicGoogleMapsPlacemarks extends BasicGoogleMapsPlacemarks
    {
    	public function register()
    	{
			add_action("admin_menu", function(){
				remove_submenu_page("options-general.php", self::PREFIX . 'settings');				
			});
    	}
    	
        public function mapShortcode( $attributes )
        {
            if( !wp_script_is( 'googleMapsAPI', 'queue' ) || !wp_script_is( 'bgmp', 'queue' ) || !wp_style_is( self::PREFIX .'style', 'queue' ) )
            {
                $error = sprintf(
                    __( '<p class="error">%s error: JavaScript and/or CSS files aren\'t loaded. If you\'re using do_shortcode() you need to add a filter to your theme first. See <a href="%s">the FAQ</a> for details.</p>', 'basic-google-maps-placemarks' ),
                    BGMP_NAME,
                    'http://wordpress.org/extend/plugins/basic-google-maps-placemarks/faq/'
                );

                // @todo maybe change this to use views/message.php

                return $error;
            }

            if( isset( $attributes[ 'categories' ] ) )
                $attributes[ 'categories' ]	= apply_filters( self::PREFIX . 'mapShortcodeCategories', $attributes[ 'categories' ] );		// @todo - deprecated b/c 1.9 output bgmpdata in post; can now just set args in do_shortcode() . also  not consistent w/ shortcode naming scheme and have filter for all arguments now. need a way to notify people


            $attributes = apply_filters( self::PREFIX . 'map-shortcode-arguments', $attributes );					// @todo - deprecated b/c 1.9 output bgmpdata in post...
            $attributes = $this->cleanMapShortcodeArguments( $attributes );

            ob_start();
            do_action( BasicGoogleMapsPlacemarks::PREFIX . 'meta-address-before' );
            // viewの読み込み先を変更
            require_once( dirname( __FILE__ ) . '/views/shortcode-bgmp-map.php' );
            do_action( BasicGoogleMapsPlacemarks::PREFIX . 'shortcode-bgmp-map-after' );
            $output = ob_get_clean();

            return $output;
        }
    }
    $my_bgmp = new MY_BasicGoogleMapsPlacemarks;
    $my_bgmp->register();
}, 9999);
