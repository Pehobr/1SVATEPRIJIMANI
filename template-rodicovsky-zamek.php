<?php
/**
 * Template Name: Rodičovský zámek
 *
 * Šablona pro samostatnou stránku pro zadání rodičovského klíče.
 *
 * @package YourChildThemeName
 */

// Získání uloženého rodičovského klíče z databáze
$spravny_klic = get_option('rodicovsky_klic');
$chyba_overeni = '';
$uspesne_prihlaseni = false;

// Zpracování formuláře po odeslání klíče
if (isset($_POST['rodicovsky_klic_input'])) {
    $zadany_klic = sanitize_text_field($_POST['rodicovsky_klic_input']);
    
    if (!empty($spravny_klic) && $zadany_klic === $spravny_klic) {
        // Klíč je správný, nastavíme cookie
        setcookie('rodic_overen', 'ano', time() + (86400 * 30), "/", "", true, true); 
        $uspesne_prihlaseni = true;

        // Přesměrujeme uživatele na hlavní stránku po úspěšném přihlášení
        // Použijeme JavaScript pro přesměrování, aby se stihla nastavit cookie
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "' . esc_url(home_url('/')) . '";
                }, 2000); // 2 sekundy zpoždění
              </script>';

    } else {
        // Klíč je nesprávný
        $chyba_overeni = "Zadaný klíč není správný. Zkuste to prosím znovu.";
    }
}

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-form-wrapper p-4 p-md-5 border rounded shadow-sm">

                    <header class="entry-header text-center mb-4">
                        <h1 class="entry-title">Vstup pro rodiče</h1>
                    </header>

                    <?php if ($uspesne_prihlaseni): ?>
                        <div class="alert alert-success text-center">
                            <strong>Přihlášení proběhlo úspěšně!</strong><br>
                            Za chvíli budete přesměrováni na hlavní stránku.
                        </div>
                    <?php else: ?>
                        
                        <p class="text-center">Pro odemčení rodičovského obsahu na všech kartách zadejte prosím Váš klíč.</p>

                        <?php if (!empty($chyba_overeni)): ?>
                            <div class="alert alert-danger"><?php echo esc_html($chyba_overeni); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="rodicovsky_klic_input" class="form-label">Rodičovský klíč</label>
                                <input type="password" name="rodicovsky_klic_input" id="rodicovsky_klic_input" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Odemknout obsah</button>
                            </div>
                        </form>

                    <?php endif; ?>

                </div>
            </div>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
