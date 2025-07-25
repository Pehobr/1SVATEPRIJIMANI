<?php
/**
 * Template Name: Rodičovský zámek
 *
 * Šablona pro samostatnou stránku pro zadání rodičovského klíče.
 *
 * @package YourChildThemeName
 */

// Zpracování odhlášení (odebrání klíče)
if (isset($_GET['akce']) && $_GET['akce'] === 'odebrat_klic') {
    // Smazání cookie nastavením platnosti do minulosti
    setcookie('rodic_overen', '', time() - 3600, "/");
    
    // Přesměrování na čistou URL (bez query parametru), aby se formulář zobrazil správně
    $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: " . $redirect_url);
    exit();
}

// Zjistíme, zda je rodič již ověřen pomocí cookie
$je_prihlasen = isset($_COOKIE['rodic_overen']) && $_COOKIE['rodic_overen'] === 'ano';

// Získání uloženého rodičovského klíče z databáze
$spravny_klic = get_option('rodicovsky_klic');
$chyba_overeni = '';
$uspesne_prihlaseni = false;

// Zpracování formuláře po odeslání (pouze pokud uživatel není již přihlášen)
if (!$je_prihlasen && isset($_POST['rodicovsky_klic_input'])) {
    $zadany_klic = sanitize_text_field($_POST['rodicovsky_klic_input']);
    
    if (!empty($spravny_klic) && $zadany_klic === $spravny_klic) {
        // Klíč je správný, nastavíme cookie s platností 30 dní
        setcookie('rodic_overen', 'ano', time() + (86400 * 30), "/", "", true, true); 
        $uspesne_prihlaseni = true;
        $je_prihlasen = true; // Aktualizujeme stav i pro aktuální načtení stránky

        // Pomocí JavaScriptu stránku znovu načteme, aby se projevilo přihlášení
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = window.location.href;
                }, 1500); // 1.5 sekundy zpoždění pro zobrazení zprávy
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

                    <?php if ($je_prihlasen && !$uspesne_prihlaseni): ?>
                        <div class="alert alert-success text-center" role="alert">
                            <strong>Jste přihlášeni.</strong><br>
                            Rodičovský obsah na všech kartách je nyní odemčen.
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" disabled>Úspěšně přihlášeno</button>
                            <a href="?akce=odebrat_klic" class="btn btn-danger">Odebrat klíč a odhlásit se</a>
                        </div>

                    <?php elseif ($uspesne_prihlaseni): ?>
                        <div class="alert alert-success text-center">
                            <strong>Přihlášení proběhlo úspěšně!</strong><br>
                            Stránka se nyní znovu načte...
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

    </main></div><?php get_footer(); ?>