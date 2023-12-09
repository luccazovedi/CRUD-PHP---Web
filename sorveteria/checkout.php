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

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Resumo do Pedido</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        $total = 0;
                        foreach ($_SESSION['cart'] as $product_id => $quantity) {
                            $sql = "SELECT id, description, price FROM products WHERE id=?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$product_id]);
                            $product = $stmt->fetch(PDO::FETCH_ASSOC);

                            echo '<tr>';
                            echo '<td>' . $product['id'] . '</td>';
                            echo '<td>' . $product['description'] . '</td>';
                            echo '<td>R$ ' . number_format($product['price'], 2, ',', '.') . '</td>';
                            echo '<td>' . $quantity . '</td>';

                            $subtotal = $product['price'] * $quantity;
                            echo '<td>R$ ' . number_format($subtotal, 2, ',', '.') . '</td>';
                            echo '</tr>';

                            $total += $subtotal;
                        }
                        echo '<tr>';
                        echo '<td colspan="4" class="text-right"><strong>Total:</strong></td>';
                        echo '<td><strong>R$ ' . number_format($total, 2, ',', '.') . '</strong></td>';
                        echo '</tr>';
                    } else {
                        echo '<tr><td colspan="5">Carrinho vazio</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <h2>Detalhes do Cartão de Crédito</h2>
            <form>
                <div class="form-group">
                    <label for="cardNumber">Número do Cartão</label>
                    <input type="text" class="form-control" id="cardNumber" required>
                </div>
                <div class="form-group">
                    <label for="expiryDate">Data de Expiração</label>
                    <input type="text" class="form-control" id="expiryDate" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" class="form-control" id="cvv" required>
                </div>
                <button type="submit" class="btn btn-primary">Finalizar Pagamento</button>
                <a href="index.php"><button type="button" class="btn btn-link">Voltar</button></a>
            </form>
        </div>
    </div>
</div>
<br>
<?php include 'footer.php'?>