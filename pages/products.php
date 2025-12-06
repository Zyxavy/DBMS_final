<?php 
    require_once __DIR__ . "/../config/database.php";
    require_once __DIR__ . "/../Classes/ProductClass.php";
    require_once __DIR__ . "/../includes/functions.php";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    This is what u will see when after u login as a customer
    <button>Okay i am customer</button>
    <br>
    <div>
        <form action="products.php" method="post">
        <button type="submit" name="back">button</button>
        <br>
        <div>
            <label for="categories">Choose a category:</label>

            <select id="categories" name="category">
            <option value="1">Mobile-phones</option>
            <option value="2">Laptops</option>
            <option value="3">system-unit</option>
            <option value="4">input-devices</option>
            <option value="5">output-devices</option>
            </select> 

            <button type="submit" name="cat-btn">Search</button>
        </div>

        <div>
            <table border="1" cellpadding="10">
            <tr>
                <th>Product name</th>
                <th>class</th>
                <th>price</th>
                <th>stock</th>
                <th>RAM</th>
                <th>ROM</th>
            </tr>

            <?php 
                if(isset($_POST['cat-btn']))
                {
                    $categoryID = $_POST['category'];
                    $products = new Products();
                    $productsList = $productObj->getProductsbyCategory($categoryID);        
                    
                    if($productsList && count($productsList) > 0)
                    { ?>
                    <tr>
                        <td><?= $products['name '] ?></td>
                        <td><?= $products['class_id '] ?></td>
                        <td><?= $products['price '] ?></td>
                        <td><?= $products['stock'] ?></td>
                        <td><?= $products['RAM'] ?></td>
                        <td><?= $products['ROM'] ?></td>
                    </tr>
            <?php  }
                    else
                    {
                        echo "No products found :(";
                    }
                } >?

            </table>
        </div>




        <hr>
        <button type="submit" name="cart">Cart</button>
    </form>
    </div>
</body>
</html>

<?php



    if(isset($_POST['back']))
    {
        if (!headers_sent()) 
        {
            redirectToPage('../index.php');
        } 
    }
    elseif(isset($_POST['cart']))
    {
        if (!headers_sent()) 
        {
            redirectToPage('cart.php');
        } 
    }


?>