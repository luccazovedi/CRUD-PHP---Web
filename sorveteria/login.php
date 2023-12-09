<?php
session_start();
require_once 'config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit();
    } else {
        $message = 'Nome de usuário ou senha incorretos!';
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
    <h2>Login</h2>
    <?php if ($message !== ''): ?>
        <div class="alert alert-danger" role="alert">
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
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="register.php" class="btn btn-secondary">Ainda não é cadastrado? Cadastre-se aqui</a>
        <hr><a href="index.php" class="btn btn-link">Voltar ao início</a>
    </form>
</div>
<?php include 'footer.php'; ?>