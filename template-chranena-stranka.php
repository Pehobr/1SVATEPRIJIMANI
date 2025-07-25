<?php
/**
 * Template Name: Chráněná stránka pro rodiče
 *
 * Šablona pro stránku, která je přístupná pouze po zadání rodičovského klíče.
 * Ověření probíhá na základě cookie 'rodic_overen'.
 *
 * @package YourChildThemeName
 */

// --- START OCHRANY OBSAHU ---

// Zkontrolujeme, zda je rodič ověřen pomocí cookie.
$je_prihlasen = isset($_COOKIE['rodic_overen']) && $_COOKIE['rodic_overen'] === 'ano';

// Pokud rodič NENÍ přihlášen, přesměrujeme ho na stránku s rodičovským zámkem.
if (!$je_prihlasen) {
    // URL adresa stránky pro zadání klíče. 
    // Ujistěte se, že 'rodicovsky-zamek' odpovídá URL vaší přihlašovací stránky.
    $url_rodicovskeho_zamku = home_url('/rodicovsky-zamek/'); 
    wp_redirect($url_rodicovskeho_zamku);
    exit; // Důležité: Ukončíme vykonávání skriptu po přesměrování.
}

// --- KONEC OCHRANY OBSAHU ---


// Pokud se kód dostal až sem, znamená to, že je rodič přihlášen.
// Následuje standardní kód pro zobrazení obsahu stránky.

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">

        <?php
        while (have_posts()) :
            the_post();

            // Zobrazíme titulek a obsah stránky zadaný ve WordPress editoru
            the_title('<h1 class="entry-title text-center mb-4">', '</h1>');
            the_content();

        endwhile; // Konec smyčky.
        ?>

    </main></div><?php
get_footer();