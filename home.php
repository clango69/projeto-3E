<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduConnect - Conectando Saberes</title>
    <link rel="preconnect" href="https://googleapis.com"><link rel="preconnect" href="https://gstatic.com" crossorigin><link href="https://googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com"></script>
    <link rel="stylesheet" href="estilo.css">
    <style>
        @media (max-width: 600px) {
            .header-content { flex-direction: column; text-align: center; }
            .action-nav { flex-wrap: wrap; justify-content: center; margin-top: 10px; }
            .nav-item { padding: 8px 12px; font-size: 13px; }
            .dashboard-preview { flex-direction: column; gap: 15px; text-align: center; }
            .hero-block h2 { font-size: 32px; }
        }
    </style>
</head>
<body class="dark-portal">
    <div class="glow-bg"><div class="ball cyan"></div><div class="ball purple"></div></div>
    <header class="glass-header">
        <div class="container header-content">
            <a href="home.php" class="brand-logo">Edu<span>.</span>connect</a>
            <nav class="action-nav">
                <a href="home.php" class="nav-item active"><i class="ph ph-squares-four"></i> Início</a>
                <a href="alunos.php" class="nav-item"><i class="ph ph-user-list"></i> Alunos</a>
                <a href="mentorias.php" class="nav-item"><i class="ph ph-lightning"></i> Mentorias</a>
                <?php if (isset($_SESSION['logado'])): ?><a href="logout.php" class="nav-item" style="color: #ef4444;"><i class="ph ph-sign-out"></i> Sair</a><?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container portal-layout">
        <section class="hero-block">
            <div class="status-tag"><span class="pulse-dot"></span> Teste Operacional MVP</div>
            <h2>Conectando quem quer <span>aprender</span> com quem quer <span>ensinar</span>.</h2>
            <p>Plataforma inteligente de reforço escolar focada em transformar o desempenho de estudantes da rede pública através do voluntariado.</p>
            <div class="cta-group"><a href="alunos.php" class="btn-glow">Entrar no Painel <i class="ph ph-arrow-up-right"></i></a></div>
            <div class="dashboard-preview">
                <div class="stat-box"><span class="num">100%</span><span class="label">Gratuito</span></div>
                <div class="stat-box"><span class="num">Rede</span><span class="label">Pública</span></div>
                <div class="stat-box"><span class="num">Escopo</span><span class="label">MVP 1.0</span></div>
            </div>
        </section>
        <section class="tracks-block">
            <h3 class="section-title">Frentes de Aprendizado</h3>
            <div class="track-list">
                <div class="track-card">
                    <div class="track-icon math"><i class="ph ph-function"></i></div>
                    <div class="track-info"><h4>Lógica & Exatas</h4><p>Desmistificando equações, frações e geometria básica sem decoreba.</p></div>
                </div>
                <div class="track-card">
                    <div class="track-icon lang"><i class="ph ph-textbox"></i></div>
                    <div class="track-info"><h4>Escrita & Crítica</h4><p>Estruturação de redações nota mil e técnicas avançadas de interpretação.</p></div>
                </div>
                <div class="track-card">
                    <div class="track-icon science"><i class="ph ph-atom"></i></div>
                    <div class="track-info"><h4>Ciências da Natureza</h4><p>O universo da física, química e biologia aplicados de forma visual.</p></div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
