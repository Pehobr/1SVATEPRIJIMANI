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

    // === NOVÉ: Aplikace zaoblení rohů ===
    if (isset($options['box_border_radius']) && is_numeric($options['box_border_radius'])) {
        $radius_value = esc_attr($options['box_border_radius']);
        $css .= '.single-tydenni_karta .modlitba, .single-tydenni_karta .zapamatuj-si, .single-tydenni_karta .accordion-item { border-radius: ' . $radius_value . 'rem !important; }';
    }

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