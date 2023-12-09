<?php
session_start();
include 'config.php';

$authenticated = false;
$username = '';

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $authenticated = true;
    $username = $_SESSION['username'];
}

$sql = "SELECT id, description, price, image FROM products";
$result = $pdo->query($sql);
$products = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<div class="modal right fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Carrinho de Compras</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cartContent">
                <?php
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        echo "Product ID: $product_id - Quantity: $quantity <br>";
                    }
                } else {
                    echo "Carrinho vazio";
                }
                ?>
            </div>
            <div class="modal-footer">
                <a href="checkout.php" class="btn btn-primary">Finalizar Compra</a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <h1>Produtos</h1>
    <div class="row">
        <?php foreach ($products as $key => $product) { ?>
            <div class="col-sm-4">
                <div class="card">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" class="card-img-top"
                        alt="<?php echo $product['description']; ?>">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo $product['description']; ?>
                        </h5>
                        <p class="card-text">Preço: R$
                            <?php echo number_format($product['price'], 2, ',', '.'); ?>
                        </p>
                        <form class="add-to-cart-form" action="add_to_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <div class="form-group">
                                <label for="quantity">Quantidade:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
                            </div>
                            <button type="submit" class="btn btn-primary add-to-cart-btn">Adicionar ao Carrinho</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<br>
<script>
    $(document).ready(function () {
        const cartModal = $('#cartModal');
        const cartIcon = $('#cartIcon');

        function loadCartContent() {
            $.ajax({
                url: 'add_to_cart.php',
                type: 'GET',
                success: function (response) {
                    const cartData = JSON.parse(response);
                    const totalQuantity = cartData.totalQuantity;
                    cartIcon.text(totalQuantity);
                    let cartContent = '';
                    $.each(cartData.cartDetails, function (productId, quantity) {
                        cartContent += `Product ID: ${productId} - Quantity: ${quantity} <br>`;
                    });
                    $('#cartContent').html(cartContent);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Evento de submit do formulário "Adicionar ao Carrinho"
        $('.add-to-cart-form').submit(function (event) {
            event.preventDefault();

            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function (response) {
                    alert('Produto adicionado ao carrinho');
                    loadCartContent();
                    cartModal.modal('show');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });

        cartModal.on('show.bs.modal', function () {
            loadCartContent();
        });

        loadCartContent();
    });
</script>

<?php include 'footer.php'; ?>