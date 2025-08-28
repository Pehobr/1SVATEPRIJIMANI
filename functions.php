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

function pehobr_enqueue_custom_scripts() {
    // Načte náš JavaScript pro mobilní menu.
    // Třetí parametr 'array()' znamená, že náš skript na ničem nezávisí.
    // '1.0.1' je nová verze, aby se obešla cache.
    // 'true' znamená, že se skript načte v patičce stránky.
    wp_enqueue_script( 
        'pehobr-mobile-menu-js', 
        get_stylesheet_directory_uri() . '/js/mobile-menu.js', 
        array(), 
        '1.0.1', 
        true 
    );
}
// Přidá naši funkci do správné WordPress akce.
add_action( 'wp_enqueue_scripts', 'pehobr_enqueue_custom_scripts' );

// =============================================================================
// VLOŽENÍ SMART SUPP CHATU POUZE PRO PŘIHLÁŠENÉ RODIČE
// =============================================================================
function vlozit_smartsupp_pro_rodice() {
    // Zkontrolujeme, zda existuje cookie s ověřením rodiče.
    if ( isset($_COOKIE['rodic_overen']) && $_COOKIE['rodic_overen'] === 'ano' ) {
        // Pokud je rodič ověřen, vložíme skript pro chat.
?>
    <script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = 'e157a5c21de86d5ed76d2a06e67b64324bcd3081';
    window.smartsupp||(function(d) {
      var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
      s=d.getElementsByTagName('script')[0];c=d.createElement('script');
      c.type='text/javascript';c.charset='utf-8';c.async=true;
      c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
    </script>
    <noscript> Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript>
<?php
    }
}
// Přidáme naši funkci do patičky webu. Zkontroluje se na každé stránce.
add_action('wp_footer', 'vlozit_smartsupp_pro_rodice');

?>