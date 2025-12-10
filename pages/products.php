<?php
    session_start();
    require_once __DIR__ . "/../config/database.php";
    require_once __DIR__ . "/../Classes/ProductClass.php";
    require_once __DIR__ . "/../includes/functions.php";

    if(!isset($_SESSION['user_id']))
    {
        redirectToPage("../index.php");
        session_destroy();
        exit();
    }

    if(isset($_POST['log-out']))
    {
        if (!headers_sent()) 
        {
            session_destroy();
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <h2>This is what u will see when after u login as a customer</h2>

    <?php
        if(isset($_SESSION['success_message'])) 
        {
            echo "<div style='color: green;'>" . $_SESSION['success_message'] . "</div>";
            unset($_SESSION['success_message']);
        }
        if(isset($_SESSION['error_message'])) 
        {
            echo "<div style='color: red;'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']);
        }
    ?>
    
    <div>
        <form action="products.php" method="post">
            <button type="submit" name="log-out">Log Out</button>
        </form>
        <br>
        
        <form action="products.php" method="post">
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
            </div>
        </form>

        <div>
            <table border="5" cellpadding="20">
                <tr>
                    <th>Product name</th>
                    <th>class</th>
                    <th>price</th>
                    <th>stock</th>
                    <th>RAM</th>
                    <th>ROM</th>
                    <th>Action</th>
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
                                    <td><?= $product['class_name'] ?></td>
                                    <td><?= $product['price'] ?></td>
                                    <td><?= $product['stock'] ?></td>
                                    <td><?= $product['RAM'] ?></td>
                                    <td><?= $product['ROM'] ?></td>

                                    <td>
                                        <form action="addToCart.php" method="post">
                                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                            <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                                            <button type="submit" name="add_to_cart">Add to Cart</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } 
                        }
                        else
                        {
                            echo "<tr><td colspan='7'>No products found :(</td></tr>";
                        }
                    }  
                ?>
            </table>
        </div>

        <hr>
        
        <form action="products.php" method="post">
            <button type="submit" name="cart">Cart</button>
        </form>
    </div>
</body>
</html>