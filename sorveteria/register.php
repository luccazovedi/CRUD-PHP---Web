<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$username, $password]);
        $message = 'Usuário registrado com sucesso! Siga para o Login.';
    } catch (PDOException $e) {
        $message = 'Erro ao registrar o usuário: ' . $e->getMessage();
    }
}
?>
<?php include 'header.php'; ?>

<style>
    body {
        margin: 100px auto;
        width: 50%;
    }
</style>

<div class="container">
    <h2>Registro</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label for="username">Nome de Usuário:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Nome de usuário"
                required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="login.php" class="btn btn-secondary">Já possui cadastro? Faça o Login.</a>
        <hr><a href="index.php" class="btn btn-link">Voltar ao início</a>
    </form>
</div>
<?php include 'footer.php'; ?>