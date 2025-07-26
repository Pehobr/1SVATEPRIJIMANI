<?php
// Exit if accessed directly
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

// =============================================================================
// 5. NASTAVENÍ VZHLEDU PRO MOBILNÍ ZAŘÍZENÍ
// =============================================================================

// Přidání nové stránky do menu "Vzhled" v administraci
function pridat_stranku_nastaveni_mobilu() {
    add_theme_page(
        'Vzhled na mobilu',          // Název stránky
        'Vzhled na mobilu',          // Text v menu
        'manage_options',            // Oprávnění pro přístup
        'nastaveni-mobilniho-vzhledu', // Slug stránky
        'render_stranka_nastaveni_mobilu' // Funkce pro vykreslení obsahu
    );
}
add_action('admin_menu', 'pridat_stranku_nastaveni_mobilu');

// Vykreslení obsahu stránky s nastavením
function render_stranka_nastaveni_mobilu() {
    ?>
    <div class="wrap">
        <h1>Nastavení vzhledu týdenních karet na mobilu</h1>
        <p>Zde můžete upravit velikost a zarovnání textů pro lepší čitelnost na zařízeních s šířkou do 768px.</p>
        <form method="post" action="options.php">
            <?php
            settings_fields('mobilni_vzhled_group');
            do_settings_sections('nastaveni-mobilniho-vzhledu');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrace nastavení, sekcí a polí
function registrovat_mobilni_nastaveni() {
    register_setting('mobilni_vzhled_group', 'mobilni_styly_options');

    // Sekce pro "Výklad"
    add_settings_section('mobilni_sekce_vyklad', 'Formátování textu "Výklad"', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('vyklad_font_size', 'Velikost písma (v rem)', 'render_pole_vyklad_font_size', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_vyklad');

    // Sekce pro "Modlitba"
    add_settings_section('mobilni_sekce_modlitba', 'Formátování textu "Modlitba"', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('modlitba_font_size', 'Velikost písma (v rem)', 'render_pole_modlitba_font_size', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_modlitba');
    add_settings_field('modlitba_font_weight', 'Řez písma', 'render_pole_modlitba_font_weight', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_modlitba');
    add_settings_field('modlitba_text_align', 'Zarovnání textu', 'render_pole_modlitba_text_align', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_modlitba');

    // Sekce pro "Zapamatuj si"
    add_settings_section('mobilni_sekce_zapamatuj', 'Formátování textu "Zapamatuj si"', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('zapamatuj_font_size', 'Velikost písma (v rem)', 'render_pole_zapamatuj_font_size', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_zapamatuj');
    add_settings_field('zapamatuj_font_weight', 'Řez písma', 'render_pole_zapamatuj_font_weight', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_zapamatuj');
    add_settings_field('zapamatuj_text_align', 'Zarovnání textu', 'render_pole_zapamatuj_text_align', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_zapamatuj');
}
add_action('admin_init', 'registrovat_mobilni_nastaveni');

// --- Renderovací funkce pro jednotlivá pole ---

function get_mobilni_option($field, $default = '') {
    $options = get_option('mobilni_styly_options');
    return isset($options[$field]) ? $options[$field] : $default;
}

function render_pole_vyklad_font_size() {
    $value = get_mobilni_option('vyklad_font_size', '1.7');
    echo '<input type="number" step="0.1" name="mobilni_styly_options[vyklad_font_size]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.7. Jednotka "rem" se přizpůsobuje výchozímu nastavení prohlížeče.</p>';
}

function render_pole_modlitba_font_size() {
    $value = get_mobilni_option('modlitba_font_size', '1.15');
    echo '<input type="number" step="0.05" name="mobilni_styly_options[modlitba_font_size]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.15.</p>';
}

function render_pole_modlitba_font_weight() {
    $value = get_mobilni_option('modlitba_font_weight', 'normal');
    echo '<select name="mobilni_styly_options[modlitba_font_weight]">
              <option value="normal"' . selected($value, 'normal', false) . '>Obyčejné</option>
              <option value="bold"' . selected($value, 'bold', false) . '>Tučné</option>
          </select>';
}

function render_pole_modlitba_text_align() {
    $value = get_mobilni_option('modlitba_text_align', 'center');
    echo '<select name="mobilni_styly_options[modlitba_text_align]">
              <option value="center"' . selected($value, 'center', false) . '>Na střed</option>
              <option value="left"' . selected($value, 'left', false) . '>Vlevo</option>
          </select>';
}

function render_pole_zapamatuj_font_size() {
    $value = get_mobilni_option('zapamatuj_font_size', '1.15');
    echo '<input type="number" step="0.05" name="mobilni_styly_options[zapamatuj_font_size]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.15.</p>';
}

function render_pole_zapamatuj_font_weight() {
    $value = get_mobilni_option('zapamatuj_font_weight', '500');
    echo '<select name="mobilni_styly_options[zapamatuj_font_weight]">
              <option value="normal"' . selected($value, 'normal', false) . '>Obyčejné</option>
              <option value="500"' . selected($value, '500', false) . '>Polotučné (500)</option>
              <option value="bold"' . selected($value, 'bold', false) . '>Tučné (bold)</option>
          </select>';
}

function render_pole_zapamatuj_text_align() {
    $value = get_mobilni_option('zapamatuj_text_align', 'center');
    echo '<select name="mobilni_styly_options[zapamatuj_text_align]">
              <option value="center"' . selected($value, 'center', false) . '>Na střed</option>
              <option value="left"' . selected($value, 'left', false) . '>Vlevo</option>
          </select>';
}


// Funkce pro generování a vložení dynamických CSS stylů do hlavičky
function vlozit_mobilni_custom_css() {
    $options = get_option('mobilni_styly_options');
    if (empty($options)) {
        return;
    }

    $css = '<style type="text/css" id="mobilni-custom-css">';
    $css .= '@media (max-width: 768px) {';

    // Výklad
    if (!empty($options['vyklad_font_size'])) {
        $css .= '.single-tydenni_karta .vyklad .entry-content { font-size: ' . esc_attr($options['vyklad_font_size']) . 'rem !important; }';
    }

    // Modlitba
    if (!empty($options['modlitba_font_size'])) {
        $css .= '.single-tydenni_karta .modlitba .entry-content { font-size: ' . esc_attr($options['modlitba_font_size']) . 'rem !important; }';
    }
    if (!empty($options['modlitba_font_weight'])) {
        $css .= '.single-tydenni_karta .modlitba .entry-content { font-weight: ' . esc_attr($options['modlitba_font_weight']) . ' !important; }';
    }
    if (!empty($options['modlitba_text_align'])) {
        $css .= '.single-tydenni_karta .modlitba .entry-content { text-align: ' . esc_attr($options['modlitba_text_align']) . ' !important; }';
    }

    // Zapamatuj si
    if (!empty($options['zapamatuj_font_size'])) {
        $css .= '.single-tydenni_karta .zapamatuj-si .zapamatuj-si-text { font-size: ' . esc_attr($options['zapamatuj_font_size']) . 'rem !important; }';
    }
    if (!empty($options['zapamatuj_font_weight'])) {
        $css .= '.single-tydenni_karta .zapamatuj-si .zapamatuj-si-text { font-weight: ' . esc_attr($options['zapamatuj_font_weight']) . ' !important; }';
    }
    if (!empty($options['zapamatuj_text_align'])) {
        $css .= '.single-tydenni_karta .zapamatuj-si .zapamatuj-si-text { text-align: ' . esc_attr($options['zapamatuj_text_align']) . ' !important; }';
    }
    
    $css .= '}';
    $css .= '</style>';

    echo $css;
}
add_action('wp_head', 'vlozit_mobilni_custom_css');