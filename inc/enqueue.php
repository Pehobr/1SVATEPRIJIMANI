<?php
/**
 * Načítání stylů a skriptů.
 */
if ( !defined( 'ABSPATH' ) ) exit;

// =============================================================================
// 1. NAČTENÍ STYLŮ A SKRIPTŮ
// =============================================================================
function minimalistblogger_child_scripts() {
    // Načtení stylů z rodičovské šablony
    wp_enqueue_style( 'minimalistblogger-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Načtení Bootstrap CSS z CDN
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', array(), '5.2.3' );
    
    // Načtení hlavního stylu dceřiné šablony
    wp_enqueue_style( 'minimalistblogger-child-style', get_stylesheet_uri(), array( 'minimalistblogger-parent-style' ) );
    
    // Načtení VŠECH VLASTNÍCH stylů s automatickou verzí
    $custom_css_path = get_stylesheet_directory() . '/css/custom-styles.css';
    $custom_css_ver = file_exists( $custom_css_path ) ? filemtime( $custom_css_path ) : '1.0';
    wp_enqueue_style(
        'pehobr-custom-styles',
        get_stylesheet_directory_uri() . '/css/custom-styles.css',
        array( 'minimalistblogger-child-style' ),
        $custom_css_ver
    );

    // Načtení responzivních stylů pro mobilní zařízení
    $mobile_css_path = get_stylesheet_directory() . '/css/tydenni-karta-mobile.css';
    if ( file_exists( $mobile_css_path ) ) {
        $mobile_css_ver = filemtime( $mobile_css_path );
        wp_enqueue_style(
            'pehobr-mobile-styles',
            get_stylesheet_directory_uri() . '/css/tydenni-karta-mobile.css',
            array( 'pehobr-custom-styles' ),
            $mobile_css_ver,
            '(max-width: 768px)'
        );
    }

    // Načtení Bootstrap JS
    wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.2.3', true );
}
add_action( 'wp_enqueue_scripts', 'minimalistblogger_child_scripts', 99 );