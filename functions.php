<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// =============================================================================
// 1. NAČTENÍ STYLŮ A SKRIPTŮ
// =============================================================================
function minimalistblogger_child_scripts() {
    // Načtení stylů z rodičovské šablony
    wp_enqueue_style( 'minimalistblogger-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Načtení Bootstrap CSS
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', array(), '5.2.3' );
    
    // Načtení hlavního stylu dceřiné šablony
    wp_enqueue_style( 'minimalistblogger-child-style', get_stylesheet_uri(), array( 'minimalistblogger-parent-style', 'bootstrap-css' ) );
    
    // === CHYTRÉ NAČÍTÁNÍ VLASTNÍHO CSS S AUTOMATICKOU VERZÍ ===
    $custom_css_path = get_stylesheet_directory() . '/css/custom-styles.css';
    $custom_css_ver = file_exists( $custom_css_path ) ? filemtime( $custom_css_path ) : '1.0';

    wp_enqueue_style(
        'pehobr-custom-styles', // Unikátní název
        get_stylesheet_directory_uri() . '/css/custom-styles.css', // Cesta k souboru
        array( 'minimalistblogger-child-style' ), // Načte se až po ostatních
        $custom_css_ver // Automaticky se měnící verze souboru
    );

    // Načtení Bootstrap JS
    wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.2.3', true );
}
add_action( 'wp_enqueue_scripts', 'minimalistblogger_child_scripts', 99 );


// =============================================================================
// 2. REGISTRACE VLASTNÍHO TYPU PŘÍSPĚVKU 'TYDENNI_KARTA'
// =============================================================================
function registrovat_tydenni_karty() {
    $labels = array(
        'name'               => 'Týdenní karty',
        'singular_name'      => 'Týdenní karta',
        'menu_name'          => 'Týdenní karty',
        'name_admin_bar'     => 'Týdenní karta',
        'add_new'            => 'Přidat novou',
        'add_new_item'       => 'Přidat novou kartu',
        'new_item'           => 'Nová karta',
        'edit_item'          => 'Upravit kartu',
        'view_item'          => 'Zobrazit kartu',
        'all_items'          => 'Všechny karty',
        'search_items'       => 'Hledat karty',
        'not_found'          => 'Žádné karty nenalezeny.',
        'not_found_in_trash' => 'Žádné karty v koši.',
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'karta' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-id-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
    );
    register_post_type( 'tydenni_karta', $args );
}
add_action( 'init', 'registrovat_tydenni_karty' );


// =============================================================================
// 3. SYSTÉM RODIČOVSKÉHO KLÍČE
// =============================================================================
function pridat_stranku_nastaveni_klice() {
    add_menu_page('Rodičovský klíč', 'Rodičovský klíč', 'manage_options', 'nastaveni-klice', 'render_stranka_nastaveni_klice', 'dashicons-lock', 20);
}
add_action( 'admin_menu', 'pridat_stranku_nastaveni_klice' );

function render_stranka_nastaveni_klice() {
    ?>
    <div class="wrap">
        <h1>Nastavení rodičovského klíče</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'nastaveni_klice_group' );
            do_settings_sections( 'nastaveni-klice' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function registrovat_nastaveni_klice() {
    register_setting( 'nastaveni_klice_group', 'rodicovsky_klic' );
    add_settings_section('hlavni_sekce_klice', 'Globální klíč pro rodiče', null, 'nastaveni-klice');
    add_settings_field('pole_rodicovskeho_klice', 'Rodičovský klíč', 'render_pole_pro_klic', 'nastaveni-klice', 'hlavni_sekce_klice');
}
add_action( 'admin_init', 'registrovat_nastaveni_klice' );

function render_pole_pro_klic() {
    $klic = get_option( 'rodicovsky_klic' );
    echo '<input type="text" name="rodicovsky_klic" value="' . esc_attr( $klic ) . '" class="regular-text" />';
    echo '<p class="description">Tento klíč bude použit pro odemčení rodičovské sekce na všech kartách.</p>';
}


// =============================================================================
// 4. VYNUCENÍ SPRÁVNÉ ŠABLONY PRO TÝDENNÍ KARTY
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

/**
 * Skryje položky menu, které vedou na stránky s chráněnou šablonou,
 * pokud uživatel není ověřen rodičovským klíčem.
 *
 * @param array $items Pole objektů položek menu.
 * @return array Upravené pole objektů položek menu.
 */
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

// Přidáme naši funkci jako filtr pro navigační menu
add_filter('wp_nav_menu_objects', 'skryt_polozku_menu_pro_rodice', 10, 1);