<?php
/**
 * Šablona pro zobrazení detailu jedné týdenní karty.
 *
 * @package YourChildThemeName
 */

get_header();

// Zkontrolujeme, zda existuje cookie s ověřením
$je_rodic_overen = isset($_COOKIE['rodic_overen']) && $_COOKIE['rodic_overen'] === 'ano';

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('card-article'); ?>>
                <header class="entry-header text-center mb-4">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <?php
                // Definice obsahu pro děti (použijeme ho na dvou místech)
                ob_start();
                ?>
                <div class="card-section-child pt-4">
                    <div class="vyklad mb-4">
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <?php $zapamatuj_si = get_field('zapamatuj_si'); ?>
                    <?php if ($zapamatuj_si): ?>
                        <div class="zapamatuj-si">
                            <h4 class="section-title-child">Zapamatuj si</h4>
<p class="lead zapamatuj-si-text"><?php echo esc_html($zapamatuj_si); ?></p>                        </div>
                    <?php endif; ?>

                    <?php $modlitba = get_field('modlitba'); ?>
                    <?php if ($modlitba): ?>
                        <div class="modlitba">
                            <h4 class="section-title-child">Modlitba</h4>
                            <div class="entry-content">
                                <?php echo wp_kses_post($modlitba); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="otazky">
                        <h4 class="section-title-child">Otázky</h4>
                        <div class="accordion" id="otazkyAccordion">
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                                <?php
                                    $otazka = get_field($i . '_otazka');
                                    $odpoved = get_field($i . '_odpoved');
                                ?>
                                <?php if ($otazka): // Zobrazit, pokud existuje otázka ?>
                                    <?php if ($odpoved): // Pokud má i odpověď, udělej rozbalovací box ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-<?php echo $i; ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $i; ?>">
                                                    <?php echo esc_html($otazka); ?>
                                                </button>
                                            </h2>
                                            <div id="collapse-<?php echo $i; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $i; ?>" data-bs-parent="#otazkyAccordion">
                                                <div class="accordion-body">
                                                    <?php echo wp_kses_post($odpoved); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: // Pokud odpověď nemá, zobraz jen otázku jako statický box ?>
                                        <div class="accordion-item-static">
                                            <div class="accordion-button-static">
                                                <?php echo esc_html($otazka); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <?php
                $obsah_pro_deti = ob_get_clean();
                ?>

                <?php if ($je_rodic_overen): // === POHLED PRO PŘIHLÁŠENÉ RODIČE === ?>

                    <div class="card-tabs-wrapper">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="deti-tab" data-bs-toggle="tab" data-bs-target="#deti-tab-pane" type="button" role="tab" aria-controls="deti-tab-pane" aria-selected="true">Pro děti</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rodice-tab" data-bs-toggle="tab" data-bs-target="#rodice-tab-pane" type="button" role="tab" aria-controls="rodice-tab-pane" aria-selected="false">Pro rodiče</button>
                            </li>
                        </ul>
                        <div class="tab-content border border-top-0 p-4" id="myTabContent">
                            <div class="tab-pane fade show active" id="deti-tab-pane" role="tabpanel" aria-labelledby="deti-tab" tabindex="0">
                                <?php echo $obsah_pro_deti; ?>
                            </div>
                            <div class="tab-pane fade" id="rodice-tab-pane" role="tabpanel" aria-labelledby="rodice-tab" tabindex="0">
                                <?php $rodicovsky_vyklad = get_field('rodicovsky_vyklad'); ?>
                                <?php if ($rodicovsky_vyklad): ?>
                                    <div class="vyklad-rodice mb-4">
                                        <div class="entry-content-parent">
                                            <?php echo wp_kses_post($rodicovsky_vyklad); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="pdf-download">
    <?php
    $materialy_existuji = false;
    // Nejprve zkontrolujeme, zda je vyplněn alespoň jeden soubor, abychom mohli zobrazit nadpis.
    for ($i = 1; $i <= 5; $i++) {
        if (get_field('pdf_soubor_' . $i)) {
            $materialy_existuji = true;
            break; // Jakmile najdeme první, můžeme smyčku ukončit
        }
    }

    // Pokud existuje alespoň jeden materiál, zobrazíme nadpis a projdeme pole znovu pro vypsání odkazů
    if ($materialy_existuji):
    ?>
        <?php
        // Projdeme všechna pole od 1 do 5
        for ($i = 1; $i <= 5; $i++):
            
            // Získáme data o souboru z pole 'pdf_soubor_1', 'pdf_soubor_2' atd.
            $pdf_soubor = get_field('pdf_soubor_' . $i);
            
            if ($pdf_soubor):
                $url = $pdf_soubor['url'];
                $nazev = $pdf_soubor['title'] ? $pdf_soubor['title'] : $pdf_soubor['filename'];
        ?>
                <a href="<?php echo esc_url($url); ?>" class="btn btn-primary mb-2 d-block" download>
                    <i class="fa fa-download"></i>   <?php echo esc_html($nazev); ?>
                </a>
        <?php 
            endif;
        endfor; // Konec smyčky for
        ?>
    <?php
    endif; // Konec podmínky if($materialy_existuji)
    ?>
</div>
                            </div>
                        </div>
                    </div>

                <?php else: // === POHLED PRO NEPŘIHLÁŠENÉ UŽIVATELE === ?>

                    <div id="myTabContent">
                        <?php echo $obsah_pro_deti; ?>
                    </div>

                <?php endif; ?>

            </article>

        <?php endwhile; // End of the loop. ?>

    </main>
</div>
<?php get_footer(); ?>