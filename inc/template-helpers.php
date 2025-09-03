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

/**
 * Nahradí text "Rodičovský klíč" za ikonu a přesune položku doprava, pokud je rodič přihlášen.
 */
function upravit_polozku_menu_pro_rodice($items) {
    // Zkontrolujeme, zda je rodič přihlášen
    if (isset($_COOKIE['rodic_overen']) && $_COOKIE['rodic_overen'] === 'ano') {
        foreach ($items as $item) {
            // Najdeme položku, která odkazuje na stránku "Rodičovský zámek"
            if ($item->object === 'page' && get_page_template_slug($item->object_id) === 'template-rodicovsky-zamek.php') {
                
                // Nahradíme název položky SVG ikonou klíče
                $item->title = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>';
                
                // Přidáme vlastní CSS třídu pro stylování
                $item->classes[] = 'menu-item-ikona-klice';
                break; 
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'upravit_polozku_menu_pro_rodice', 20, 1);