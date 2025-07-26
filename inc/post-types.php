<?php
/**
 * Registrace vlastního typu příspěvku 'tydenni_karta'.
 */
if ( !defined( 'ABSPATH' ) ) exit;

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