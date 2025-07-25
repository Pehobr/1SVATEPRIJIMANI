<?php
/**
 * The front page template file for displaying weekly cards.
 *
 * @package YourChildThemeName
 */

get_header(); // Načte hlavičku (s navigací, CSS, JS)
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5"> <h1 class="text-center mb-4">Týdenní karty pro přípravu na první svaté přijímání</h1>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"> <?php
            // Nastavení argumentů pro dotaz na vlastní typ příspěvku 'tydenni_karta'
            $args = array(
                'post_type'      => 'tydenni_karta', // Náš vlastní typ příspěvku
                'posts_per_page' => -1,              // Zobrazit všechny karty (-1)
                'order'          => 'DESC',          // Nejnovější nahoře
                'orderby'        => 'date',          // Řadit podle data vytvoření
            );

            $tydenni_karty_query = new WP_Query( $args );

            // Začátek smyčky WordPressu
            if ( $tydenni_karty_query->have_posts() ) :
                while ( $tydenni_karty_query->have_posts() ) :
                    $tydenni_karty_query->the_post();
                    ?>

                    <div class="col">
                        <div class="card h-100 shadow-sm"> <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php the_title(); ?></h5>
                                </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-block">Přejít na lekci</a>
                            </div>
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

        </div></main></div><?php
get_footer(); // Načte patičku
?>