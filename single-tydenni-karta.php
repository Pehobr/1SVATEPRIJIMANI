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
                        <h4 class="section-title-child">Výklad</h4>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <?php $zapamatuj_si = get_field('zapamatuj_si'); ?>
                    <?php if ($zapamatuj_si): ?>
                        <div class="zapamatuj-si mb-4 p-3 bg-light rounded">
                            <h4 class="section-title-child">Zapamatuj si</h4>
                            <p class="lead"><?php echo esc_html($zapamatuj_si); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="otazky">
                        <h4 class="section-title-child">Otázky k zamyšlení</h4>
                        <div class="accordion" id="otazkyAccordion">
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                                <?php
                                    $otazka = get_field($i . '_otazka');
                                    $odpoved = get_field($i . '_odpoved');
                                ?>
                                <?php if ($otazka && $odpoved): ?>
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
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <?php
                $obsah_pro_deti = ob_get_clean();
                ?>

                <?php if ($je_rodic_overen): ?>
                    
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
                                        <h3 class="section-title-parent">Podrobnější výklad</h3>
                                        <div class="entry-content-parent">
                                            <?php echo wp_kses_post($rodicovsky_vyklad); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php $pdf_soubor = get_field('pdf_ke_stazeni'); ?>
                                <?php if ($pdf_soubor): ?>
                                    <div class="pdf-download">
                                        <h3 class="section-title-parent">Materiály ke stažení</h3>
                                        <a href="<?php echo esc_url($pdf_soubor['url']); ?>" class="btn btn-primary" download>
                                            <i class="fas fa-download me-2"></i>Stáhnout kartu v PDF
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php else: ?>

                    <div class="card-section p-4 border rounded shadow-sm">
                        <?php echo $obsah_pro_deti; ?>
                    </div>

                    <div class="card-section card-section-parent p-4 border rounded shadow-sm bg-light mt-5">
                        <div class="text-center">
                            <h2 class="section-title">Vstup pro rodiče</h2>
                            <p>Tato sekce obsahuje doplňující materiály pro rodiče.</p>
                            <a href="<?php echo esc_url(home_url('/rodicovsky-zamek/')); ?>" class="btn btn-primary">Přejít na přihlášení</a>
                        </div>
                    </div>

                <?php endif; ?>

            </article>

        <?php endwhile; // End of the loop. ?>

    </main></div><?php get_footer(); ?>