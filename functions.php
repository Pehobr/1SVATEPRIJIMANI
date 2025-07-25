<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue scripts and styles.
 *
 * This function properly enqueues parent and child theme stylesheets,
 * as well as the Bootstrap CSS and JS required for the card layout.
 */
function minimalistblogger_child_scripts() {
    // Enqueue parent theme's stylesheet.
    wp_enqueue_style( 'minimalistblogger-parent-style', get_template_directory_uri() . '/style.css' );

    // Enqueue Bootstrap CSS for the card layout.
    // Using a specific version for stability.
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', array(), '5.2.3' );

    // Enqueue child theme's stylesheet.
    // It's good practice to enqueue it after the parent and framework styles.
    // This ensures that your custom styles can override the parent and Bootstrap styles.
    wp_enqueue_style( 'minimalistblogger-child-style', get_stylesheet_uri(), array( 'minimalistblogger-parent-style', 'bootstrap-css' ) );
    
    // Enqueue Bootstrap JS bundle (includes Popper.js).
    // The `true` at the end loads the script in the footer for better performance.
    wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.2.3', true );
}
add_action( 'wp_enqueue_scripts', 'minimalistblogger_child_scripts' );

?>
