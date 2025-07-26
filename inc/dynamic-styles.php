<?php
/**
 * Vkládání dynamických CSS stylů do hlavičky webu.
 */
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Vloží dynamické CSS pro mobilní vzhled do hlavičky webu.
 */
function vlozit_mobilni_custom_css() {
    $options = get_option('mobilni_styly_options');
    if (empty($options)) {
        return;
    }

    $css = '<style type="text/css" id="mobilni-custom-css">';
    $css .= '@media (max-width: 768px) {';

    // Aplikace zaoblení rohů
    if (isset($options['box_border_radius']) && is_numeric($options['box_border_radius'])) {
        $radius_value = esc_attr($options['box_border_radius']);
        $css .= '.single-tydenni_karta .modlitba, .single-tydenni_karta .zapamatuj-si, .single-tydenni_karta .accordion-item { border-radius: ' . $radius_value . 'rem !important; }';
    }

    // Výklad, Modlitba, Zapamatuj si (kód zůstává)
    // ...

    // === NOVÉ: Aplikace stylů pro OTÁZKY ===
    $otazky_selectors = '.single-tydenni_karta .accordion-button, .single-tydenni_karta .accordion-button-static';
    if (!empty($options['otazky_font_size']) && is_numeric($options['otazky_font_size'])) {
        $css .= $otazky_selectors . ' { font-size: ' . esc_attr($options['otazky_font_size']) . 'rem !important; }';
    }
    if (!empty($options['otazky_font_weight'])) {
        $css .= $otazky_selectors . ' { font-weight: ' . esc_attr($options['otazky_font_weight']) . ' !important; }';
    }
    if (!empty($options['otazky_line_height']) && is_numeric($options['otazky_line_height'])) {
        $css .= $otazky_selectors . ' { line-height: ' . esc_attr($options['otazky_line_height']) . ' !important; }';
    }

    // === NOVÉ: Aplikace stylů pro ODPOVĚDI ===
    $odpovedi_selector = '.single-tydenni_karta .accordion-body';
    if (!empty($options['odpovedi_font_size']) && is_numeric($options['odpovedi_font_size'])) {
        $css .= $odpovedi_selector . ' { font-size: ' . esc_attr($options['odpovedi_font_size']) . 'rem !important; }';
    }
    if (!empty($options['odpovedi_font_weight'])) {
        $css .= $odpovedi_selector . ' { font-weight: ' . esc_attr($options['odpovedi_font_weight']) . ' !important; }';
    }
    if (!empty($options['odpovedi_line_height']) && is_numeric($options['odpovedi_line_height'])) {
        $css .= $odpovedi_selector . ' { line-height: ' . esc_attr($options['odpovedi_line_height']) . ' !important; }';
    }
    
    $css .= '}';
    $css .= '</style>';

    echo $css;
}
add_action('wp_head', 'vlozit_mobilni_custom_css');