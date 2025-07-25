<?php
/**
 * Template Name: Jednoduchá stránka (bez postranního panelu)
 *
 * Šablona pro jednoduchou stránku, která přebírá vzhled z ostatních vlastních šablon,
 * jako je 'Chráněná stránka', ale bez jakýchkoliv speciálních funkcí.
 * Zobrazuje obsah na celou šířku v kontejneru.
 *
 * @package YourChildThemeName
 */

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