<?php
/**
 * Template Name: Formulář pro kontakty rodičů
 *
 * Šablona pro stránku se sběrem kontaktů a jejich odesláním do Firebase.
 *
 * @package YourChildThemeName
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">

        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <?php
                while (have_posts()) :
                    the_post();
                    // Zobrazíme titulek a obsah stránky zadaný ve WordPress editoru
                    the_title('<h1 class="entry-title text-center mb-4">', '</h1>');
                    the_content(); // Můžete sem napsat instrukce pro rodiče
                endwhile;
                ?>
                
                <div class="card p-4 shadow-sm">
                    <form id="kontaktniFormular">
                        <div class="mb-3">
                            <label for="jmenoDitete" class="form-label">Celé jméno dítěte</label>
                            <input type="text" class="form-control" id="jmenoDitete" required>
                        </div>
                        <div class="mb-3">
                            <label for="rodic" class="form-label">Jsem</label>
                            <select class="form-select" id="rodic" required>
                                <option value="" selected disabled>-- Vyberte --</option>
                                <option value="matka">Matka</option>
                                <option value="otec">Otec</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mobil" class="form-label">Mobilní číslo</label>
                            <input type="tel" class="form-control" id="mobil" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Odeslat kontakt</button>
                        </div>
                    </form>
                    <div id="form-message" class="mt-3"></div>
                </div>

            </div>
        </div>

    </main>
</div>

<script type="module">
  // Import funkcí z Firebase SDK
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
  import { getFirestore, collection, addDoc } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore.js";

  // ==========================================================================
  // ZDE VLOŽTE VAŠI KONFIGURACI Z PROJEKTU "setkani-s-rodici"
  // ==========================================================================
  const firebaseConfig = {
    // Vložte sem zkopírovaný kód...
  };
  // ==========================================================================

  // Inicializace Firebase
  const app = initializeApp(firebaseConfig);
  const db = getFirestore(app);

  // Zpracování formuláře
  const form = document.getElementById('kontaktniFormular');
  const messageDiv = document.getElementById('form-message');

  form.addEventListener('submit', async (e) => {
    e.preventDefault(); // Zabráníme klasickému odeslání formuláře

    // Získání hodnot z polí
    const jmenoDitete = document.getElementById('jmenoDitete').value;
    const rodic = document.getElementById('rodic').value;
    const mobil = document.getElementById('mobil').value;
    const email = document.getElementById('email').value;

    // Zobrazíme zprávu o odesílání
    messageDiv.innerHTML = '<div class="alert alert-info">Odesílám data...</div>';
    
    try {
      // Odeslání dat do Firestore do kolekce "kontakty"
      const docRef = await addDoc(collection(db, "kontakty"), {
        jmenoDitete: jmenoDitete,
        rodic: rodic,
        mobil: mobil,
        email: email,
        casOdeslani: new Date() // Přidáme i časovou značku
      });
      
      // Zobrazení úspěšné zprávy
      messageDiv.innerHTML = `<div class="alert alert-success">Děkujeme! Váš kontakt byl úspěšně uložen.</div>`;
      form.reset(); // Vyčistíme formulář

    } catch (error) {
      // Zobrazení chybové zprávy
      console.error("Chyba při zápisu do databáze: ", error);
      messageDiv.innerHTML = `<div class="alert alert-danger">Při odesílání došlo k chybě. Zkuste to prosím znovu.</div>`;
    }
  });
</script>

<?php
get_footer();
?>