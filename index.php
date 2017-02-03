<?php
include_once 'class.products.php';
include_once 'class.validator.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Stack</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

</head>
<body>

<div class="container" style="margin-top: 100px;">

    <div class="row">
        <h3>Lägg till ny eller uppdatera produkt</h3>
    </div>



    <form action="" method="post" class="form-inline" style="margin-top: 15px;">

        <label class="sr-only" for="inlineFormInput">Namn</label>
        <input type="text" name="namn" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Namn">

        <label class="sr-only" for="inlineFormInput">Sku</label>
        <input type="text" name="sku" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Sku">

        <label class="sr-only" for="inlineFormInput">Pris</label>
        <input type="text" name="pris" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Pris">

        <button type="submit" name="submit" class="btn btn-primary">Skapa</button>
    </form>
    <?php
    $products = new products();

    //Om get är delete raderas produkten med sku
    if(isset($_GET['delete'])) {

        $skuid = htmlentities($_GET['delete']);

       echo $products->deleteProduct($skuid);
    }

    if(isset($_POST['submit'])) {

        $namn = htmlentities($_POST['namn']);
        $sku = htmlentities($_POST['sku']);
        $pris = htmlentities($_POST['pris']);

        //Kontrollerar först så att fälten är ifyllda innan formaten för varje fält kontrolleras via validator-klassen
        if (!empty($namn) && !empty($pris) && !empty($sku)) {
            if (validator::validateName($namn)) {
                echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"margin-top: 20px;\">" . validator::validateName($namn) . "</div>";
            } elseif (validator::validateSku($sku)) {
                echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"margin-top: 20px;\">" . validator::validateSku($sku) . "</div>";
            }elseif (validator::validatePrice($pris)) {
                echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"margin-top: 20px;\">" . validator::validatePrice($pris) . "</div>";
            } else {
                echo $products->createProduct($namn, $sku, $pris);
            }
        } else {
            echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"margin-top: 20px;\">Alla fält måste vara ifyllda</div>";
        }

    }

    ?>


<table class="table table-hover" style="margin-top: 30px;">
    <thead>
    <tr>
        <th>Namn</th>
        <th>Sku</th>
        <th>Pris</th>
        <th>Uppdatera</th>
        <th>Radera</th>
    </tr>
    </thead>
    <tbody>
    <?php

    //Hämtar produkter
    $result = $products->loadProducts();

    foreach ($result->items as $item) {
        echo "<tr>";
        echo "<td>" . $item->name . "</td>";
        echo "<td>" . $item->sku . "</td>";
        echo "<td>" . $item->price . "</td>";
        echo "<td> <a href='uppdatera.php/?sku=$item->sku' class='btn btn-primary'>Uppdatera</a></td>";
        echo "<td> <a href='index.php?delete=$item->sku' class='btn btn-danger'>Radera</a>";
        echo "</tr>";
    }
?>
    </tbody>
</table>
    </div>

</body>
</html>