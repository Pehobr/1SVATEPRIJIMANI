<?php
/**
 * Template Name: Chráněná stránka pro rodiče
 *
 * Šablona pro stránku, která je přístupná pouze po zadání rodičovského klíče
 * a zobrazuje obsah ve dvou záložkách.
 *
 * @package YourChildThemeName
 */

// --- START OCHRANY OBSAHU ---

// Zkontrolujeme, zda je rodič ověřen pomocí cookie.
$je_prihlasen = isset($_COOKIE['rodic_overen']) && $_COOKIE['rodic_overen'] === 'ano';

// Pokud rodič NENÍ přihlášen, přesměrujeme ho na stránku s rodičovským zámkem.
if (!$je_prihlasen) {
    $url_rodicovskeho_zamku = home_url('/rodicovsky-zamek/'); 
    wp_redirect($url_rodicovskeho_zamku);
    exit; // Ukončíme vykonávání skriptu.
}

// --- KONEC OCHRANY OBSAHU ---

get_header();

// Načtení obsahu pro první záložku ze stránky "Aktuality"
$aktuality_page = get_page_by_path('aktuality');
$aktuality_content = '';
if ($aktuality_page) {
    // Aplikujeme filtr 'the_content', aby se správně zpracovaly shortcody a formátování
    $aktuality_content = apply_filters('the_content', $aktuality_page->post_content);
}

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">

        <?php
        // Smyčka pro získání titulku a obsahu hlavní stránky (/info-pro-rodice/)
        while (have_posts()) :
            the_post();

            // Zobrazíme hlavní titulek stránky
            the_title('<h1 class="entry-title text-center mb-4">', '</h1>');
            ?>

            <div class="card-tabs-wrapper">
                <ul class="nav nav-tabs" id="rodiceTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="aktuality-tab" data-bs-toggle="tab" data-bs-target="#aktuality-tab-pane" type="button" role="tab" aria-controls="aktuality-tab-pane" aria-selected="true">Aktuality</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-controls="info-tab-pane" aria-selected="false">Základní informace</button>
                    </li>
                </ul>

                <div class="tab-content border border-top-0 p-4" id="rodiceTabContent">
                    <div class="tab-pane fade show active" id="aktuality-tab-pane" role="tabpanel" aria-labelledby="aktuality-tab" tabindex="0">
                        <?php 
                        if (!empty($aktuality_content)) {
                            echo $aktuality_content; // Zobrazí obsah ze stránky /aktuality/
                        } else {
                            echo '<p>Obsah pro aktuality nebyl nalezen. Ujistěte se, že existuje stránka s URL adresou /aktuality/.</p>';
                        }
                        ?>
                    </div>
                    <div class="tab-pane fade" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                        <?php the_content(); // Zobrazí obsah ze stránky /info-pro-rodice/ ?>
                    </div>
                </div>
            </div>

        <?php
        endwhile; // Konec smyčky.
        ?>

    </main>
</div>

<?php
get_footer();
?>