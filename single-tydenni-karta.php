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

                <!-- =========================================================== -->
                <!-- 1. ČÁST PRO DĚTI (VŽDY VEŘEJNÁ)                           -->
                <!-- =========================================================== -->
                <div class="card-section card-section-child mb-5 p-4 border rounded shadow-sm">
                    <h2 class="section-title">Pro děti</h2>
                    
                    <!-- Výklad -->
                    <div class="vyklad mb-4">
                        <h3>Výklad</h3>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Zapamatuj si -->
                    <?php $zapamatuj_si = get_field('zapamatuj_si'); ?>
                    <?php if ($zapamatuj_si): ?>
                        <div class="zapamatuj-si mb-4 p-3 bg-light rounded">
                            <h3>Zapamatuj si</h3>
                            <p class="lead"><?php echo esc_html($zapamatuj_si); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Otázky a odpovědi -->
                    <div class="otazky">
                        <h3>Otázky k zamyšlení</h3>
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


                <!-- =========================================================== -->
                <!-- 2. ČÁST PRO RODIČE (ZOBRAZÍ SE PODLE PODMÍNKY)             -->
                <!-- =========================================================== -->
                <div class="card-section card-section-parent p-4 border rounded shadow-sm bg-light">
                    
                    <?php if ($je_rodic_overen): ?>
                        <!-- OBSAH PRO OVĚŘENÉ RODIČE -->
                        <h2 class="section-title">Pro rodiče</h2>

                        <!-- Výklad pro rodiče -->
                        <?php $rodicovsky_vyklad = get_field('rodicovsky_vyklad'); ?>
                        <?php if ($rodicovsky_vyklad): ?>
                            <div class="vyklad-rodice mb-4">
                                <h3>Podrobnější výklad</h3>
                                <div class="entry-content-parent">
                                    <?php echo wp_kses_post($rodicovsky_vyklad); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- PDF ke stažení -->
                        <?php $pdf_soubor = get_field('pdf_ke_stazeni'); ?>
                        <?php if ($pdf_soubor): ?>
                            <div class="pdf-download">
                                <h3>Materiály ke stažení</h3>
                                <a href="<?php echo esc_url($pdf_soubor['url']); ?>" class="btn btn-primary" download>
                                    Stáhnout kartu v PDF
                                </a>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- === ZMĚNĚNÁ ČÁST === -->
                        <!-- ODKAZ NA SAMOSTATNOU PŘIHLAŠOVACÍ STRÁNKU -->
                        <div class="text-center">
                            <h2 class="section-title">Vstup pro rodiče</h2>
                            <p>Tato sekce obsahuje doplňující materiály pro rodiče.</p>
                            <a href="<?php echo esc_url(home_url('/rodicovsky-zamek/')); ?>" class="btn btn-primary">Přejít na přihlášení</a>
                        </div>
                        <!-- === KONEC ZMĚNĚNÉ ČÁSTI === -->
                    <?php endif; ?>

                </div>

            </article>

        <?php endwhile; // End of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<style>
    /* Doplňkové styly pro lepší vzhled detailu karty */
    .card-article .section-title {
        color: #5C4033;
        font-weight: bold;
        border-bottom: 2px solid #e5cf87;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e5cf87;
        color: #5C4033;
    }
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(92, 64, 51, 0.25);
    }
</style>

<?php get_footer(); ?>
