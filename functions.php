<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// =============================================================================
// 1. NAČTENÍ STYLŮ, SKRIPTŮ A PŘÍMÉ VLOŽENÍ CSS
// =============================================================================

// Nejprve klasické načtení stylů a skriptů
function minimalistblogger_child_scripts() {
    // Načtení stylů z rodičovské šablony
    wp_enqueue_style( 'minimalistblogger-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Načtení Bootstrap CSS
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', array(), '5.2.3' );
    
    // Načtení hlavního stylu dceřiné šablony
    wp_enqueue_style( 'minimalistblogger-child-style', get_stylesheet_uri(), array( 'minimalistblogger-parent-style', 'bootstrap-css' ) );
    
    // Načtení Bootstrap JS
    wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.2.3', true );
}
add_action( 'wp_enqueue_scripts', 'minimalistblogger_child_scripts' );


// Nová funkce pro přímé vložení CSS do hlavičky
function vlozit_vlastni_css_pro_kartu() {
    // Tuto akci provedeme POUZE na stránce s detailem týdenní karty
    if ( is_singular( 'tydenni_karta' ) ) {
        echo '
<style type="text/css">
    /* === ZDE JSOU NAŠE FINÁLNÍ A OPRAVENÉ STYLY === */

    .card-article {
        background-color: #d4af37 !important;
        padding: 2rem !important;
        border-radius: 15px !important;
    }

    .nav-tabs {
        border-bottom: none !important;
        position: relative;
        top: 1px;
    }

    .nav-tabs .nav-link {
        border: 1px solid #dee2e6 !important;
        border-bottom: none !important;
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
        background-color: #f8f9fa;
        color: #212529 !important;
        font-weight: normal !important;
    }

    #myTabContent {
        background-color: #ffffff !important;
        border: 1px solid #dee2e6 !important;
        padding: 1.5rem !important;
        border-radius: 15px !important;
    }

    .nav-tabs .nav-link.active {
        background-color: #ffffff !important;
        border-color: #dee2e6 #dee2e6 #ffffff !important;
        font-weight: bold !important;
    }

    .accordion-item {
        border-color: #e5cf87 !important;
        border-radius: 10px !important;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .accordion-button {
        color: #5C4033 !important;
        background-color: #e5cf87 !important;
    }

    .accordion-button:not(.collapsed) {
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(92, 64, 51, 0.25) !important;
        border-color: #e5cf87 !important;
    }

    /* === ZDE JE OPRAVA === */
    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\' fill=\'%235C4033\'%3e%3cpath fill-rule=\'evenodd\' d=\'M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z\'/%3e%3c/svg%3e") !important;
    }
</style>
        ';
    }
}
// Ujistěte se, že tato část na konci souboru zůstala
add_action('wp_head', 'vlozit_vlastni_css_pro_kartu', 999);


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