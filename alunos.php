<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}
include 'conexao.php';
$msg = '';
$tipo_msg = '';

if (isset($_POST['salvar_aluno'])) {
    $id = $_POST['id'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $serie = trim($_POST['serie']);
    $data_nasc = $_POST['data_nascimento'];

    if (!empty($nome) && !empty($email) && !empty($serie) && !empty($data_nasc)) {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE alunos SET nome = ?, email = ?, serie = ?, data_nascimento = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $serie, $data_nasc, $id]);
                $msg = "Dados atualizados!";
                $tipo_msg = "sucesso";
            } else {
                $stmt = $pdo->prepare("INSERT INTO alunos (nome, email, serie, data_nascimento) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nome, $email, $serie, $data_nasc]);
                $msg = "Aluno matriculado!";
                $tipo_msg = "sucesso";
            }
        } catch (Exception $e) {
            $msg = "Erro: E-mail em uso.";
            $tipo_msg = "erro";
        }
    }
}

if (isset($_GET['excluir'])) {
    $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = ?");
    $stmt->execute([$_GET['excluir']]);
    header("Location: alunos.php");
    exit;
}

$alunos = $pdo->query("SELECT * FROM alunos ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$aluno_edicao = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $aluno_edicao = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>EduConnect - Alunos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body class="dark-portal">
    <header class="glass-header">
        <div class="container header-content">
            <a href="home.php" class="brand-logo">Edu<span>.</span>connect</a>
            <nav class="action-nav">
                <a href="home.php" class="nav-item"><i class="ph ph-squares-four"></i> Início</a>
                <a href="alunos.php" class="nav-item active"><i class="ph ph-user-list"></i> Alunos</a>
                <a href="mentorias.php" class="nav-item"><i class="ph ph-lightning"></i> Mentorias</a>
            </nav>
        </div>
    </header>
    <div class="container" style="padding-top: 40px;">
        <?php if ($msg): ?><div class="alerta alerta-<?= $tipo_msg ?>"><?= $msg ?></div><?php endif; ?>
        
        <div class="track-card" style="margin-bottom: 30px; display:block;">
            <h3 style="margin-bottom: 20px; color: #fff;"><?= $aluno_edicao ? 'Editar Aluno' : 'Cadastrar Aluno' ?></h3>
            <form method="POST" action="alunos.php">
                <input type="hidden" name="id" value="<?= $aluno_edicao['id'] ?? '' ?>">
                <div class="grupo-form"><label>Nome Completo</label><input type="text" name="nome" value="<?= $aluno_edicao['nome'] ?? '' ?>" required></div>
                <div class="grupo-form"><label>E-mail</label><input type="email" name="email" value="<?= $aluno_edicao['email'] ?? '' ?>" required></div>
                <div class="grupo-form">
                    <label>Série / Ano Escolar</label>
                    <select name="serie" required>
                        <option value="">Selecione...</option>
                        <option value="6º Ano" <?= (isset($aluno_edicao['serie']) && $aluno_edicao['serie'] == '6º Ano') ? 'selected' : '' ?>>6º Ano</option>
                        <option value="7º Ano" <?= (isset($aluno_edicao['serie']) && $aluno_edicao['serie'] == '7º Ano') ? 'selected' : '' ?>>7º Ano</option>
                        <option value="8º Ano" <?= (isset($aluno_edicao['serie']) && $aluno_edicao['serie'] == '8º Ano') ? 'selected' : '' ?>>8º Ano</option>
                        <option value="9º Ano" <?= (isset($aluno_edicao['serie']) && $aluno_edicao['serie'] == '9º Ano') ? 'selected' : '' ?>>9º Ano</option>
                        <option value="1º Ano EM" <?= (isset($aluno_edicao['serie']) && $aluno_edicao['serie'] == '1º Ano EM') ? 'selected' : '' ?>>1º Ano EM</option>
                        <option value="2º Ano EM" <?= (isset($aluno_edicao['serie']) && $aluno_edicao['serie'] == '2º Ano EM') ? 'selected' : '' ?>>2º Ano EM</option>
                        <option value="3º Ano EM" <?= (isset($aluno_edicao['serie']) && $aluno_edicao['serie'] == '3º Ano EM') ? 'selected' : '' ?>>3º Ano EM</option>
                    </select>
                </div>
                <div class="grupo-form"><label>Data de Nascimento</label><input type="date" name="data_nascimento" value="<?= $aluno_edicao['data_nascimento'] ?? '' ?>" required></div>
                <button type="submit" name="salvar_aluno" class="btn-glow">Salvar Registro</button>
            </form>
        </div>

        <div class="track-card" style="display:block;">
            <h3 style="margin-bottom: 20px; color: #fff;">Estudantes</h3>
            <table class="tabela-custom">
                <thead><tr><th>Nome</th><th>E-mail</th><th>Série</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php foreach ($alunos as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nome']) ?></td>
                        <td><?= htmlspecialchars($r['email']) ?></td>
                        <td><?= htmlspecialchars($r['serie']) ?></td>
                        <td>
                            <a href="alunos.php?editar=<?= $r['id'] ?>" style="color:#06b6d4; margin-right:15px;">Editar</a>
                            <a href="alunos.php?excluir=<?= $r['id'] ?>" style="color:#ef4444;" onclick="return confirm('Excluir?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
