<?php
session_start();
include 'config.php';
$authenticated = false;
$username = '';

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $authenticated = true;
    $username = $_SESSION['username'];
}

?>

<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<style>
    .product-image {
        max-width: 100px;
        max-height: 100px;
        border-radius: 5px;
    }

    .mt-4 {
        margin-top: 1.5rem !important;
    }
</style>

<div class="container mt-4">
    <h2 class="mb-4">Lista de Produtos</h2>
    <?php
    $sql = "SELECT id, description, price, image FROM products";
    $result = $pdo->query($sql);

    if ($result->rowCount() > 0) {
        echo '<table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Imagem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>';
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '<td>R$ ' . number_format($row['price'], 2, ',', '.') . '</td>';
            echo '<td>';
            if (!empty($row['image'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" class="product-image" alt="Product Image">';
            } else {
                echo 'Sem imagem';
            }
            echo '</td>';

            echo '<td class="action-links">
                        <a href="dashboard.php?action=edit&id=' . $row['id'] . '" class="btn btn-primary btn-sm">Editar</a>
                        <a href="dashboard.php?action=delete&id=' . $row['id'] . '" class="btn btn-danger btn-sm">Excluir</a>
                      </td>';
            echo '</tr>';
        }
        echo '</tbody>
                </table>';
    } else {
        echo "<p>Não há produtos cadastrados.</p>";
    }
    ?>

    <!-- Seção de Edição de Produto -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $sql = "SELECT id, description, price, image FROM products WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Formulário de edição
        echo '<h2>Editar Produto</h2>';
        echo '<form method="post" action="dashboard.php" enctype="multipart/form-data">';
        echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
        echo '<label>Descrição:</label>';
        echo '<input type="text" name="description" value="' . $product['description'] . '" required><br>';
        echo '<label>Preço:</label>';
        echo '<input type="number" step="0.01" name="price" value="' . $product['price'] . '" required><br>';
        echo '<label>Nova Imagem:</label>';
        echo '<input type="file"class="custom-file-input" name="new_image"><br>';
        echo '<input type="submit" name="submit" value="Atualizar Produto">';
        echo '</form>';
    }
    ?>

    <!-- Seção de Adicionar Produto -->
    <div class="mt-4">
        <h2>Adicionar Produto</h2>
        <form method="post" action="dashboard.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Descrição:</label>
                <input type="text" name="description" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Preço:</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Imagem:</label>
                <input type="file" name="image" class="form-control-file" required>
            </div>
            <input type="submit" name="submit" value="Adicionar Produto" class="btn btn-success">
        </form>
    </div>

    <?php
    // Adicionar Produto
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $description = $_POST['description'];
        $price = floatval($_POST['price']);
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = file_get_contents($_FILES['image']['tmp_name']);

            $sql = "INSERT INTO products (description, price, image) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(1, $description);
            $stmt->bindParam(2, $price);
            $stmt->bindParam(3, $image, PDO::PARAM_LOB);

            if ($stmt->execute()) {
                echo '<script>alert("Produto adicionado com sucesso!"); window.location.href = "dashboard.php";</script>';
                exit();
            } else {
                echo "Erro ao adicionar produto: " . $stmt->errorInfo();
            }
        } else {
            echo "Erro no envio da imagem.";
        }
    }

    // Editar Produto
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $description = $_POST['description'];
        $price = floatval($_POST['price']);
        $product_id = $_POST['product_id'];
        if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
            $new_image = file_get_contents($_FILES['new_image']['tmp_name']);
            $sql = "UPDATE products SET description=?, price=?, image=? WHERE id=?";
            $values = [$description, $price, $new_image, $product_id];
        } else {
            $sql = "UPDATE products SET description=?, price=? WHERE id=?";
            $values = [$description, $price, $product_id];
        }
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($values)) {
            echo '<script>alert("Produto atualizado com sucesso!"); window.location.href = "dashboard.php";</script>';
            exit();
        } else {
            echo "Erro ao atualizar produto: " . $stmt->errorInfo();
        }
    }

    // Excluir Produto
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $sql = "DELETE FROM products WHERE id=?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$product_id])) {
            echo '<script>alert("Produto removido com sucesso!"); window.location.href = "dashboard.php";</script>';
            exit();
        } else {
            echo '<div class="alert alert-danger">Erro ao excluir produto.</div>';
        }
    }
    ?>
</div>
<br>
<?php include 'footer.php'?>