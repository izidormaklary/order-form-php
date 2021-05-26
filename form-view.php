<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <title>Order food & drinks</title>
</head>
<body>
<div class="container">
    <h1>Order food in restaurant "the Personal Ham Processors"</h1>
    <nav>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="?food=1">Order food</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?food=0">Order drinks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?food=2">Order both</a>
            </li>
        </ul>
    </nav>

    <?php showError(); ?>

    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">E-mail:</label>
                <input type="text" id="email" name="email" class="form-control" value="<?php global $email;if (isset($_SESSION['person']['email'])){echo $_SESSION['person']['email'];} elseif (!valid()) {echo $email;} ?>" />
            </div>
            <div></div>
        </div>

        <fieldset>
            <legend>Address</legend>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="street">Street:</label>
                    <input type="text" name="street" id="street" class="form-control" value="<?php global $street; if (isset($_SESSION['person']['street'])){echo $_SESSION['person']['street'];} elseif (!valid()) {echo $street;} ?>" required>
                </div>
                <div class=" form-group col-md-6">
                    <label for="streetnumber">Street number:</label>
                    <input type="text" id="streetnumber" name="streetnumber" class="form-control"
                           value="<?php global $streetnumber;  if (isset($_SESSION['person']['streetnumber'])){echo $_SESSION['person']['streetnumber'];} elseif (!valid()) {echo $streetnumber;} ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" class="form-control"
                           value="<?php global $city;  if (isset($_SESSION['person']['city'])){echo $_SESSION['person']['city'];} elseif (!valid()) {echo $city;} ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="zipcode">Zipcode</label>
                    <input type="text" id="zipcode" name="zipcode"
                           value="<?php global $zipcode; if (isset($_SESSION['person']['zipcode'])){echo $_SESSION['person']['zipcode'];} elseif (!valid()) {echo $zipcode;} ?>"
                           class="form-control required">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Products</legend>
            <?php
            foreach ( $products[$index] as $i => $product): ?>
                <label>
                    <input type="number" min="0" max="5" value="0" class="form-control"
                           name="products[<?php echo $index ?>][<?php echo $i ?>]"/>
                    <?php echo $product->getName() ?> - &euro; <?php echo number_format($product->getPrice(), 2) ?></label><br/>
            <?php endforeach; ?>
        </fieldset>

        <label>
            <input type="checkbox" name="express_delivery" value="5"/>
            Express delivery (+ 5 EUR)
        </label>

        <button type="submit" class="btn btn-primary">Order!</button>
    </form>

    <footer>You already ordered <strong>&euro; <?php echo $_COOKIE['totalValue'] ?></strong> in food and drinks.</footer>
</div>

<style>
    footer {
        text-align: center;
    }
</style>
</body>
</html>
