<?php
/**
 * Template Name: Chráněná stránka pro rodiče
 *
 * Šablona pro stránku, která je přístupná pouze po zadání rodičovského klíče
 * a zobrazuje obsah ve více záložkách.
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

// === Načítání obsahu pro všechny záložky ===

// NOVĚ: Načtení obsahu pro "Úvodní dopis"
$uvodni_dopis_page = get_page_by_path('uvodni-dopis');
$uvodni_dopis_content = '';
if ($uvodni_dopis_page) {
    $uvodni_dopis_content = apply_filters('the_content', $uvodni_dopis_page->post_content);
}

// Načtení obsahu pro "Aktuality"
$aktuality_page = get_page_by_path('aktuality');
$aktuality_content = '';
if ($aktuality_page) {
    $aktuality_content = apply_filters('the_content', $aktuality_page->post_content);
}

// Načtení obsahu pro "Setkání s rodiči"
$setkani_page = get_page_by_path('setkani-s-rodici');
$setkani_content = '';
if ($setkani_page) {
    $setkani_content = apply_filters('the_content', $setkani_page->post_content);
}

// Načtení obsahu pro "Kontakty"
$kontakty_page = get_page_by_path('kontakty');
$kontakty_content = '';
if ($kontakty_page) {
    $kontakty_content = apply_filters('the_content', $kontakty_page->post_content);
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">

        <?php
        // Smyčka pro získání titulku hlavní stránky (/info-pro-rodice/)
        while (have_posts()) :
            the_post();

            // Zobrazíme hlavní titulek stránky
            the_title('<h1 class="entry-title text-center mb-4">', '</h1>');
            ?>

            <div class="card-tabs-wrapper">
                <ul class="nav nav-tabs" id="rodiceTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="uvodni-dopis-tab" data-bs-toggle="tab" data-bs-target="#uvodni-dopis-tab-pane" type="button" role="tab" aria-controls="uvodni-dopis-tab-pane" aria-selected="true">Úvodní dopis</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="aktuality-tab" data-bs-toggle="tab" data-bs-target="#aktuality-tab-pane" type="button" role="tab" aria-controls="aktuality-tab-pane" aria-selected="false">Aktuality</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-controls="info-tab-pane" aria-selected="false">Základní informace</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="setkani-tab" data-bs-toggle="tab" data-bs-target="#setkani-tab-pane" type="button" role="tab" aria-controls="setkani-tab-pane" aria-selected="false">Setkání s rodiči</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="kontakty-tab" data-bs-toggle="tab" data-bs-target="#kontakty-tab-pane" type="button" role="tab" aria-controls="kontakty-tab-pane" aria-selected="false">Kontakty</button>
                    </li>
                </ul>

                <div class="tab-content border border-top-0 p-4" id="rodiceTabContent">
                    <div class="tab-pane fade show active" id="uvodni-dopis-tab-pane" role="tabpanel" aria-labelledby="uvodni-dopis-tab" tabindex="0">
                        <?php 
                        if (!empty($uvodni_dopis_content)) {
                            echo $uvodni_dopis_content;
                        } else {
                            echo '<p>Obsah pro úvodní dopis nebyl nalezen. Ujistěte se, že existuje stránka s URL adresou /uvodni-dopis/.</p>';
                        }
                        ?>
                    </div>
                    <div class="tab-pane fade" id="aktuality-tab-pane" role="tabpanel" aria-labelledby="aktuality-tab" tabindex="0">
                        <?php 
                        if (!empty($aktuality_content)) {
                            echo $aktuality_content;
                        } else {
                            echo '<p>Obsah pro aktuality nebyl nalezen. Ujistěte se, že existuje stránka s URL adresou /aktuality/.</p>';
                        }
                        ?>
                    </div>
                    <div class="tab-pane fade" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                        <?php the_content(); // Zobrazí obsah ze stránky /info-pro-rodice/ ?>
                    </div>
                    <div class="tab-pane fade" id="setkani-tab-pane" role="tabpanel" aria-labelledby="setkani-tab" tabindex="0">
                        <?php 
                        if (!empty($setkani_content)) {
                            echo $setkani_content;
                        } else {
                            echo '<p>Obsah pro setkání s rodiči nebyl nalezen. Ujistěte se, že existuje stránka s URL adresou /setkani-s-rodici/.</p>';
                        }
                        ?>
                    </div>
                    <div class="tab-pane fade" id="kontakty-tab-pane" role="tabpanel" aria-labelledby="kontakty-tab" tabindex="0">
                        <?php 
                        if (!empty($kontakty_content)) {
                            echo $kontakty_content;
                        } else {
                            echo '<p>Obsah pro kontakty nebyl nalezen. Ujistěte se, že existuje stránka s URL adresou /kontakty/.</p>';
                        }
                        ?>
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