<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Cesta ke složce s našimi logickými částmi
$includes_path = get_stylesheet_directory() . '/inc/';

// Načtení jednotlivých souborů
require_once $includes_path . 'enqueue.php';          // 1. Načítání stylů a skriptů
require_once $includes_path . 'post-types.php';       // 2. Registrace CPT 'tydenni_karta'
require_once $includes_path . 'admin-settings.php';   // 3. Sekce "FARÁŘ" - nastavení v administraci
require_once $includes_path . 'dynamic-styles.php';   // 4. Vkládání dynamického CSS pro mobilní vzhled
require_once $includes_path . 'template-helpers.php'; // 5. Filtry a pomocné funkce

?>