<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="">
        <a href="index.php"> 
        <img src="https://viaparqueshopping.com.br/lojas_files/12514.jpg" style="width:100px;">
        </a>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php if ($authenticated) { ?>
                <li class="nav-item">

                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="navbar-text mr-3">Bem-vindo,
                                <?php echo $username; ?>
                            </span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="dashboard.php">Dashboard</a>
                            <a class="dropdown-item" href="checkout.php">Meus Pedidos</a>
                            <a class="dropdown-item " href="logout.php">Sair</a>
                        </div>
                    </div>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="btn btn-outline-primary mr-2" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-success" href="register.php">Registrar</a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <button class="btn btn-link" data-toggle="modal" data-target="#cartModal">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cartItems" class="badge badge-pill badge-primary">
                        <?php
                        if (isset($_SESSION['cart'])) {
                            $cartItemCount = count($_SESSION['cart']);
                            echo $cartItemCount;
                        } else {
                            echo '0';
                        }
                        ?>
                    </span>
                </button>
            </li>
        </ul>
    </div>
</nav>