<?php
/**
 * Template Name: Náhodná otázka
 *
 * Šablona pro stránku, která zobrazuje náhodné otázky a odpovědi
 * z týdenních karet bez opakování.
 *
 * @package YourChildThemeName
 */

get_header();

// 1. Načteme všechny týdenní karty
$vsechny_karty = get_posts([
    'post_type' => 'tydenni_karta',
    'posts_per_page' => -1, // -1 znamená načíst všechny
    'post_status' => 'publish',
]);

// 2. Projdeme karty a sesbíráme všechny otázky, které mají odpověď
$otazky_s_odpovedi = [];
if ($vsechny_karty) {
    foreach ($vsechny_karty as $karta) {
        for ($i = 1; $i <= 3; $i++) {
            $otazka = get_field($i . '_otazka', $karta->ID);
            $odpoved = get_field($i . '_odpoved', $karta->ID);

            // Přidáme jen ty, které mají otázku i odpověď
            if (!empty($otazka) && !empty($odpoved)) {
                $otazky_s_odpovedi[] = [
                    'otazka' => $otazka,
                    'odpoved' => $odpoved,
                ];
            }
        }
    }
}

// 3. Zamícháme otázky, aby byly pokaždé v jiném pořadí
if (!empty($otazky_s_odpovedi)) {
    shuffle($otazky_s_odpovedi);
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container my-5">

        <header class="entry-header text-center mb-4">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>

        <div class="random-question-container text-center">
            <?php if (!empty($otazky_s_odpovedi)): ?>
                
                <div id="question-display-area" class="mb-4">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button id="random-question-button" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRandom" aria-expanded="false" aria-controls="collapseRandom">
                                <?php echo esc_html($otazky_s_odpovedi[0]['otazka']); // Zobrazíme první otázku ze zamíchaného pole ?>
                            </button>
                        </h2>
                        <div id="collapseRandom" class="accordion-collapse collapse" data-bs-parent="#question-display-area">
                            <div class="accordion-body" id="random-answer-body">
                                <?php echo wp_kses_post($otazky_s_odpovedi[0]['odpoved']); // Zobrazíme její odpověď ?>
                            </div>
                        </div>
                    </div>
                </div>

                <button id="new-question-btn" class="btn btn-primary btn-lg">Načíst další otázku</button>

                <div id="end-message-container" style="display: none;">
                    <h3>Výborně!</h3>
                    <p class="lead">Prošel jsi všechny otázky. Můžeš si je zkusit znovu v jiném pořadí.</p>
                </div>

                <button id="start-over-btn" class="btn btn-success btn-lg" style="display: none;">Začít znovu</button>

            <?php else: ?>
                <p>Zatím nebyly nalezeny žádné otázky s odpověďmi.</p>
            <?php endif; ?>
        </div>

    </main>
</div>

<?php // 4. JavaScript, který se postará o dynamickou změnu otázek ?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elementy
    const newQuestionBtn = document.getElementById('new-question-btn');
    const questionContainer = document.getElementById('question-display-area');
    const endMessageContainer = document.getElementById('end-message-container');
    const startOverBtn = document.getElementById('start-over-btn');

    // Bezpečnostní kontrola
    if (!newQuestionBtn || !questionContainer || !endMessageContainer || !startOverBtn) return;

    // Data z PHP
    const allQuestions = <?php echo json_encode($otazky_s_odpovedi); ?>;
    let currentQuestionIndex = 0; // Začínáme od první otázky v poli

    // Elementy pro otázku a odpověď
    const questionButton = document.getElementById('random-question-button');
    const answerBody = document.getElementById('random-answer-body');
    const collapseElement = new bootstrap.Collapse(document.getElementById('collapseRandom'), {
        toggle: false
    });

    // Funkce pro zobrazení otázky
    function displayQuestion(index) {
        if (index >= allQuestions.length) return;

        const questionData = allQuestions[index];
        questionButton.innerHTML = questionData.otazka;
        answerBody.innerHTML = questionData.odpoved;
        
        // Před zobrazením nové otázky vždy sbalíme odpověď
        collapseElement.hide();
    }

    // Kliknutí na "Načíst další otázku"
    newQuestionBtn.addEventListener('click', function() {
        currentQuestionIndex++; // Posun na další otázku

        if (currentQuestionIndex >= allQuestions.length) {
            // Všechny otázky byly zobrazeny
            questionContainer.style.display = 'none';
            newQuestionBtn.style.display = 'none';
            endMessageContainer.style.display = 'block';
            startOverBtn.style.display = 'inline-block';
        } else {
            // Zobrazíme další otázku s krátkou prodlevou pro plynulý přechod
            setTimeout(() => {
                displayQuestion(currentQuestionIndex);
            }, 250);
        }
    });

    // Kliknutí na "Začít znovu"
    startOverBtn.addEventListener('click', function() {
        window.location.reload(); // Obnoví stránku a spustí cyklus znovu
    });

    // Pokud nejsou žádné otázky, skryjeme tlačítko hned
    if (allQuestions.length === 0) {
        newQuestionBtn.style.display = 'none';
    }
});
</script>
<?php
get_footer();