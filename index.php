<?php
/**
 * Hlavní soubor šablony pro výpis příspěvků (týdenních karet).
 *
 * @package minimalistblogger
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">

        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header>
                <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <h3 class="text-center mb-5">Týdenní karty pro přípravu na první svaté přijímání</h3>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
            <?php
            // Nový loop pro zobrazení vlastního typu příspěvku 'tydenni_karta'
            $args = array(
                'post_type' => 'tydenni_karta',
                'posts_per_page' => -1, // Zobrazit všechny
                'orderby' => 'date',
                'order' => 'ASC' // Nebo 'DESC' pro opačné řazení
            );
            $karty_query = new WP_Query( $args );

            if ( $karty_query->have_posts() ) :
                while ( $karty_query->have_posts() ) : $karty_query->the_post(); ?>

                    <div class="col">
                        <div class="card h-100 shadow-sm text-center">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark d-flex flex-column h-100">
                                
                                <div class="card-body d-flex align-items-center justify-content-center flex-grow-1 py-3">
                                    <h5 class="card-title mb-0"><?php the_title(); ?></h5>
                                </div>
                                
                                <div class="card-footer bg-white border-top-0 p-3 d-none d-md-block">
                                    <span class="btn btn-primary w-100">Přejít na kartu</span>
                                </div>
                            </a>
                        </div>
                    </div>

                <?php endwhile;
                wp_reset_postdata(); // Obnovení původních dat postů
            else :
                get_template_part( 'template-parts/content', 'none' );
            endif;
            ?>
        </div></main></div><?php
get_footer();