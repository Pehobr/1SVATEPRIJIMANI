<?php
/**
 * Filtry a pomocné funkce pro šablony.
 */
if ( !defined( 'ABSPATH' ) ) exit;

// =============================================================================
// 4. FILTRY A POMOCNÉ FUNKCE
// =============================================================================
function vynutit_sablonu_pro_kartu( $template ) {
    if ( is_singular( 'tydenni_karta' ) ) {
        $new_template = get_stylesheet_directory() . '/single-tydenni-karta.php';
        if ( file_exists( $new_template ) ) {
            return $new_template;
        }
    }
    return $template;
}
add_filter( 'single_template', 'vynutit_sablonu_pro_kartu' );

function skryt_polozku_menu_pro_rodice($items) {
    // Zjistíme, jestli je rodič přihlášený pomocí cookie
    $je_prihlasen = isset($_COOKIE['rodic_overen']) && $_COOKIE['rodic_overen'] === 'ano';

    // Pokud je rodič přihlášen, nic neměníme a vrátíme původní položky
    if ($je_prihlasen) {
        return $items;
    }

    // Pokud rodič NENÍ přihlášen, projdeme položky a skryjeme ty chráněné
    $items_to_keep = array();
    foreach ($items as $item) {
        // Získáme ID stránky, na kterou položka odkazuje
        $page_id = get_post_meta($item->ID, '_menu_item_object_id', true);

        // Získáme název souboru šablony pro danou stránku
        $template = get_page_template_slug($page_id);

        // Položku zachováme pouze tehdy, pokud NEPOUŽÍVÁ naši chráněnou šablonu
        if ($template !== 'template-chranena-stranka.php') {
            $items_to_keep[] = $item;
        }
    }
    
    // Vrátíme pole s ponechanými položkami
    return $items_to_keep;
}
add_filter('wp_nav_menu_objects', 'skryt_polozku_menu_pro_rodice', 10, 1);