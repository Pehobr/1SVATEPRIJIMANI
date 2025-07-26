<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Cesta ke složce s našimi logickými částmi
$includes_path = get_stylesheet_directory() . '/inc/';

// Načtení jednotlivých souborů
require_once $includes_path . 'enqueue.php';          // 1. Načítání stylů a skriptů
require_once $includes_path . 'post-types.php';       // 2. Registrace CPT 'tydenni_karta'
require_once $includes_path . 'admin-settings.php';   // 3. Sekce "FARÁŘ" - nastavení v administraci
require_once $includes_path . 'dynamic-styles.php';   // 4. Vkládání dynamického CSS pro mobilní vzhled
require_once $includes_path . 'template-helpers.php'; // 5. Filtry a pomocné funkce

function pehobr_enqueue_custom_scripts() {
    // Načte náš JavaScript pro mobilní menu.
    // Třetí parametr 'array()' znamená, že náš skript na ničem nezávisí.
    // '1.0.1' je nová verze, aby se obešla cache.
    // 'true' znamená, že se skript načte v patičce stránky.
    wp_enqueue_script( 
        'pehobr-mobile-menu-js', 
        get_stylesheet_directory_uri() . '/js/mobile-menu.js', 
        array(), 
        '1.0.1', 
        true 
    );
}
// Přidá naši funkci do správné WordPress akce.
add_action( 'wp_enqueue_scripts', 'pehobr_enqueue_custom_scripts' );

?>

