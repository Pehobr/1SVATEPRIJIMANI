<?php
/**
 * The front page template file for displaying weekly cards.
 *
 * @package YourChildThemeName
 */

get_header(); // Načte hlavičku (s navigací, CSS, JS)
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">
        <h3 class="text-center mb-5">Týdenní karty pro přípravu na první svaté přijímání</h3>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
            <?php
            // Nastavení argumentů pro dotaz na vlastní typ příspěvku 'tydenni_karta'
            $args = array(
                'post_type'      => 'tydenni_karta', // Náš vlastní typ příspěvku
                'posts_per_page' => -1,              // Zobrazit všechny karty
                'order'          => 'ASC',           // Řadit vzestupně (1. hodina, 2. hodina...)
                'orderby'        => 'title',         // Řadit podle názvu
            );

            $tydenni_karty_query = new WP_Query( $args );

            // Začátek smyčky WordPressu
            if ( $tydenni_karty_query->have_posts() ) :
                while ( $tydenni_karty_query->have_posts() ) :
                    $tydenni_karty_query->the_post();
                    ?>

                    <div class="col">
                        <div class="card h-100 shadow-sm text-center">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark d-flex flex-column h-100">
                                
                                <!-- === ZMĚNĚNÁ ČÁST === -->
                                <!-- Odstraněna část s obrázkem. Karta nyní obsahuje jen název a tlačítko. -->
                                <div class="card-body d-flex align-items-center justify-content-center flex-grow-1 py-3">
                                    <h5 class="card-title mb-0"><?php the_title(); ?></h5>
                                </div>
                                <!-- === KONEC ZMĚNĚNÉ ČÁSTI === -->

                                <div class="card-footer bg-white border-top-0 p-3">
                                    <span class="btn btn-primary w-100">Přejít na kartu</span>
                                </div>
                            </a>
                        </div>
                    </div>

                <?php
                endwhile;
                wp_reset_postdata(); // Resetuje data dotazu
            else :
                // Pokud nejsou nalezeny žádné karty
                echo '<div class="col-12"><p class="text-center">Zatím nebyly přidány žádné týdenní karty.</p></div>';
            endif;
            ?>

        </div><!-- .row -->
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); // Načte patičku
?>
