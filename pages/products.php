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
            <select name="sort_by">
                <option value="price">Price</option>
                <option value="RAM">RAM</option>
                <option value="ROM">ROM</option>
            </select>

            <select name="order">
                <option value="ASC">Low to High</option>
                <option value="DESC">High to Low</option>
            </select>

            <table border="5" cellpadding="20">
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
                        $sort_by = $_POST['sort_by'] ?? 'price';
                        $order = $_POST['order'] ?? 'ASC';
                        $products = new Products();
                        $productsList = $products->getProductsbyCategory($categoryID, $sort_by, $order);        
                        
                        if($productsList && count($productsList) > 0)
                        { 
                            foreach($productsList as $product)
                            { ?>
                                <tr>
                                <td><?= $product['name'] ?></td>
                                <td><?= $product['class_id'] ?></td>
                                <td><?= $product['price'] ?></td>
                                <td><?= $product['stock'] ?></td>
                                <td><?= $product['RAM'] ?></td>
                                <td><?= $product['ROM'] ?></td>
                                </tr>
                            <?php }

                        }
                        else
                        {
                            echo "<tr><td colspan='6'>No products found :(</td></tr>";
                        }

                    }  ?>
                
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