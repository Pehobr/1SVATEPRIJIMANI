<?php
/**
 * Sekce "FARÁŘ" - sbírka všech nastavení šablony v administraci.
 */
if ( !defined( 'ABSPATH' ) ) exit;

// =============================================================================
// 3. SEKCIE "FARÁŘ" - SBÍRKA VŠECH NASTAVENÍ ŠABLONY
// =============================================================================

/**
 * Registruje hlavní položku menu "FARÁŘ" a všechny její podstránky.
 */
function farar_custom_menu_setup() {
    // Přidání hlavní stránky "FARÁŘ"
    add_menu_page(
        'Farář - Nastavení šablony',
        'FARÁŘ',
        'manage_options',
        'farar-hlavni-menu',
        'render_main_farar_page',
        'dashicons-admin-settings',
        26
    );

    // Přidání podstránky "Rodičovský klíč"
    add_submenu_page(
        'farar-hlavni-menu',
        'Nastavení rodičovského klíče',
        'Rodičovský klíč',
        'manage_options',
        'nastaveni-klice',
        'render_stranka_nastaveni_klice'
    );

    // Přidání podstránky "Vzhled na mobilu"
    add_submenu_page(
        'farar-hlavni-menu',
        'Nastavení vzhledu na mobilu',
        'Vzhled na mobilu',
        'manage_options',
        'nastaveni-mobilniho-vzhledu',
        'render_stranka_nastaveni_mobilu'
    );
}
add_action('admin_menu', 'farar_custom_menu_setup');

/**
 * Registruje všechna pole pro nastavení.
 */
function farar_register_all_settings() {
    // Registrace pro "Rodičovský klíč"
    register_setting( 'nastaveni_klice_group', 'rodicovsky_klic' );
    add_settings_section('hlavni_sekce_klice', 'Globální klíč pro rodiče', null, 'nastaveni-klice');
    add_settings_field('pole_rodicovskeho_klice', 'Rodičovský klíč', 'render_pole_pro_klic', 'nastaveni-klice', 'hlavni_sekce_klice');

    // Registrace pro "Vzhled na mobilu"
    register_setting('mobilni_vzhled_group', 'mobilni_styly_options');
    
    // Obecné nastavení boxů
    add_settings_section('mobilni_sekce_boxy', 'Obecné nastavení boxů', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('box_border_radius', 'Zaoblení rohů boxů (v rem)', 'render_pole_box_border_radius', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_boxy');

    // Výklad
    add_settings_section('mobilni_sekce_vyklad', 'Formátování textu "Výklad"', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('vyklad_font_size', 'Velikost písma (v rem)', 'render_pole_vyklad_font_size', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_vyklad');
    
    // Modlitba
    add_settings_section('mobilni_sekce_modlitba', 'Formátování textu "Modlitba"', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('modlitba_font_size', 'Velikost písma (v rem)', 'render_pole_modlitba_font_size', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_modlitba');
    add_settings_field('modlitba_font_weight', 'Řez písma', 'render_pole_modlitba_font_weight', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_modlitba');
    add_settings_field('modlitba_text_align', 'Zarovnání textu', 'render_pole_modlitba_text_align', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_modlitba');
    
    // Zapamatuj si
    add_settings_section('mobilni_sekce_zapamatuj', 'Formátování textu "Zapamatuj si"', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('zapamatuj_font_size', 'Velikost písma (v rem)', 'render_pole_zapamatuj_font_size', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_zapamatuj');
    add_settings_field('zapamatuj_font_weight', 'Řez písma', 'render_pole_zapamatuj_font_weight', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_zapamatuj');
    add_settings_field('zapamatuj_text_align', 'Zarovnání textu', 'render_pole_zapamatuj_text_align', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_zapamatuj');

    // Otázky
    add_settings_section('mobilni_sekce_otazky', 'Formátování textu "Otázky"', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('otazky_font_size', 'Velikost písma (v rem)', 'render_pole_otazky_font_size', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_otazky');
    add_settings_field('otazky_font_weight', 'Řez písma', 'render_pole_otazky_font_weight', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_otazky');
    add_settings_field('otazky_line_height', 'Výška řádku', 'render_pole_otazky_line_height', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_otazky');

    // Odpovědi
    add_settings_section('mobilni_sekce_odpovedi', 'Formátování textu "Odpovědi"', null, 'nastaveni-mobilniho-vzhledu');
    add_settings_field('odpovedi_font_size', 'Velikost písma (v rem)', 'render_pole_odpovedi_font_size', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_odpovedi');
    add_settings_field('odpovedi_font_weight', 'Řez písma', 'render_pole_odpovedi_font_weight', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_odpovedi');
    add_settings_field('odpovedi_line_height', 'Výška řádku', 'render_pole_odpovedi_line_height', 'nastaveni-mobilniho-vzhledu', 'mobilni_sekce_odpovedi');
}
add_action('admin_init', 'farar_register_all_settings');


// --- Vykreslovací (render) funkce pro stránky a pole ---

function render_main_farar_page() {
    ?>
    <div class="wrap">
        <h1>Farář - Rozcestník nastavení</h1>
        <p>Vítejte v hlavním nastavení Vaší šablony. Zvolte prosím jednu z položek v menu vlevo pro úpravy.</p>
        <p>Tato sekce sdružuje veškerá specifická nastavení pro farnost, jako je "Rodičovský klíč" a "Vzhled na mobilu".</p>
    </div>
    <?php
}

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

function render_pole_pro_klic() {
    $klic = get_option( 'rodicovsky_klic' );
    echo '<input type="text" name="rodicovsky_klic" value="' . esc_attr( $klic ) . '" class="regular-text" />';
    echo '<p class="description">Tento klíč bude použit pro odemčení rodičovské sekce na všech kartách.</p>';
}

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

function get_mobilni_option($field, $default = '') {
    $options = get_option('mobilni_styly_options');
    return isset($options[$field]) ? $options[$field] : $default;
}

// Render funkce pro pole mobilního vzhledu
function render_pole_box_border_radius() {
    $value = get_mobilni_option('box_border_radius', '0.5');
    echo '<input type="number" step="0.1" name="mobilni_styly_options[box_border_radius]" value="' . esc_attr($value) . '" class="regular-text" />';
    echo '<p class="description">Doporučeno: 0.5. Zadejte 0 pro ostré rohy. Ovlivňuje boxy Modlitba, Zapamatuj si a Otázky.</p>';
}

function render_pole_vyklad_font_size() {
    $value = get_mobilni_option('vyklad_font_size', '1.7');
    echo '<input type="number" step="0.1" name="mobilni_styly_options[vyklad_font_size]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.7.</p>';
}

function render_pole_modlitba_font_size() {
    $value = get_mobilni_option('modlitba_font_size', '1.15');
    echo '<input type="number" step="0.05" name="mobilni_styly_options[modlitba_font_size]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.15.</p>';
}

function render_pole_modlitba_font_weight() {
    $value = get_mobilni_option('modlitba_font_weight', 'normal');
    echo '<select name="mobilni_styly_options[modlitba_font_weight]"><option value="normal"' . selected($value, 'normal', false) . '>Obyčejné</option><option value="bold"' . selected($value, 'bold', false) . '>Tučné</option></select>';
}

function render_pole_modlitba_text_align() {
    $value = get_mobilni_option('modlitba_text_align', 'center');
    echo '<select name="mobilni_styly_options[modlitba_text_align]"><option value="center"' . selected($value, 'center', false) . '>Na střed</option><option value="left"' . selected($value, 'left', false) . '>Vlevo</option></select>';
}

function render_pole_zapamatuj_font_size() {
    $value = get_mobilni_option('zapamatuj_font_size', '1.15');
    echo '<input type="number" step="0.05" name="mobilni_styly_options[zapamatuj_font_size]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.15.</p>';
}

function render_pole_zapamatuj_font_weight() {
    $value = get_mobilni_option('zapamatuj_font_weight', '500');
    echo '<select name="mobilni_styly_options[zapamatuj_font_weight]"><option value="normal"' . selected($value, 'normal', false) . '>Obyčejné</option><option value="500"' . selected($value, '500', false) . '>Polotučné (500)</option><option value="bold"' . selected($value, 'bold', false) . '>Tučné (bold)</option></select>';
}

function render_pole_zapamatuj_text_align() {
    $value = get_mobilni_option('zapamatuj_text_align', 'center');
    echo '<select name="mobilni_styly_options[zapamatuj_text_align]"><option value="center"' . selected($value, 'center', false) . '>Na střed</option><option value="left"' . selected($value, 'left', false) . '>Vlevo</option></select>';
}

function render_pole_otazky_font_size() {
    $value = get_mobilni_option('otazky_font_size', '1.1');
    echo '<input type="number" step="0.05" name="mobilni_styly_options[otazky_font_size]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.1.</p>';
}

function render_pole_otazky_font_weight() {
    $value = get_mobilni_option('otazky_font_weight', '600');
    echo '<select name="mobilni_styly_options[otazky_font_weight]"><option value="normal"' . selected($value, 'normal', false) . '>Obyčejné</option><option value="600"' . selected($value, '600', false) . '>Polotučné (600)</option><option value="bold"' . selected($value, 'bold', false) . '>Tučné (bold)</option></select>';
}

function render_pole_otazky_line_height() {
    $value = get_mobilni_option('otazky_line_height', '1.5');
    echo '<input type="number" step="0.1" name="mobilni_styly_options[otazky_line_height]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.5. Jedná se o násobek velikosti písma.</p>';
}

function render_pole_odpovedi_font_size() {
    $value = get_mobilni_option('odpovedi_font_size', '1.1');
    echo '<input type="number" step="0.05" name="mobilni_styly_options[odpovedi_font_size]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.1.</p>';
}

function render_pole_odpovedi_font_weight() {
    $value = get_mobilni_option('odpovedi_font_weight', 'normal');
    echo '<select name="mobilni_styly_options[odpovedi_font_weight]"><option value="normal"' . selected($value, 'normal', false) . '>Obyčejné</option><option value="bold"' . selected($value, 'bold', false) . '>Tučné (bold)</option></select>';
}

function render_pole_odpovedi_line_height() {
    $value = get_mobilni_option('odpovedi_line_height', '1.6');
    echo '<input type="number" step="0.1" name="mobilni_styly_options[odpovedi_line_height]" value="' . esc_attr($value) . '" class="regular-text" /><p class="description">Doporučeno: 1.6.</p>';
}