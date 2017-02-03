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
        <h3>Uppdatera produkt</h3>
    </div>

    <?php
    $products = new products();

    //Kontrollerar så att GET finns, annars skickas användaren till index.php
    if(isset($_GET['sku']))
    {
        $skuid = htmlentities($_GET['sku']);
        $result = $products->loadProductBySku($skuid);
    } else {
        header('Location: index.php'); exit();
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
                echo $products->updateProduct($namn, $sku, $pris);
                $result = $products->loadProductBySku($skuid);
            }
        } else {
            echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"margin-top: 20px;\">Alla fält måste vara ifyllda</div>";
        }

    }

    ?>

    <form action="" method="post" class="form-inline" style="margin-top: 15px;">

        <label class="sr-only" for="inlineFormInput">Namn</label>
        <input type="text" name="namn" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" value="<?php echo $result->name; ?>" >

        <label class="sr-only" for="inlineFormInput">Pris</label>
        <input type="text" name="pris" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" value="<?php echo $result->price; ?>">

        <input type="hidden" name="sku" value="<?php echo $result->sku; ?>">

        <button type="submit" name="submit" class="btn btn-primary">Uppdatera produkt</button>
    </form>




    <div class="row" style="margin-top: 25px;">
        <a href="/index.php" class="btn btn-primary">< Tillbaka</a>
    </div>


</div>

</body>
</html>