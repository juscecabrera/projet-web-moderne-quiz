<?php
declare(strict_types=1);

require __DIR__ . '/../src/session.php';
require __DIR__ . '/../src/layout.php';
quiz_require_user();

quiz_layout_start('Accueil', 'accueil', true);
?>
<section class="hero">
    <div class="hero__content">
        <p class="hero__eyebrow">Prêt à relever le défi ?</p>
        <h1>Bonjour, <?= quiz_escape($_SESSION['pseudo']) ?> 👋</h1>
        <p class="hero__text">
            Testez vos connaissances, gagnez des points et grimpez dans le classement.
            Chaque partie vous rapproche du sommet.
        </p>
        <div class="hero__actions">
            <a class="btn btn-light btn-lg" href="themes.php">
                Jouer maintenant
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                </svg>
            </a>
        </div>
    </div>
    <div class="hero__art" aria-hidden="true">?</div>
</section>

<section class="action-grid" aria-label="Mon espace">
    <a class="action-card" href="performances.php">
        <span class="action-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 20V10"/><path d="M10 20V4"/><path d="M16 20v-7"/><path d="M22 20H2"/>
            </svg>
        </span>
        <h2>Mes performances</h2>
        <p>Retrouvez l’historique et les scores de vos parties.</p>
        <span class="action-card__cta">Voir mes statistiques →</span>
    </a>

    <a class="action-card action-card--accent" href="classement.php">
        <span class="action-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 21h8"/><path d="M12 17v4"/>
                <path d="M7 4h10v4a5 5 0 0 1-10 0V4Z"/>
                <path d="M7 6H4v2a3 3 0 0 0 3 3"/><path d="M17 6h3v2a3 3 0 0 1-3 3"/>
            </svg>
        </span>
        <h2>Classement</h2>
        <p>Comparez vos résultats avec les autres joueurs.</p>
        <span class="action-card__cta">Voir le classement →</span>
    </a>

    <a class="action-card action-card--success" href="profil.php">
        <span class="action-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>
            </svg>
        </span>
        <h2>Mon profil</h2>
        <p>Personnalisez le pseudo affiché dans le classement.</p>
        <span class="action-card__cta">Modifier mon profil →</span>
    </a>
</section>
<?php
quiz_layout_end();
