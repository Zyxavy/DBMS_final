<!-- For the CRUD operations para tat products, orders, etc.-->
<?php
   
   class manage extends Database{
    
    
    function add_product($product_name, $category_id, $class_id,$price,$stock,$ROM,$RAM){
        try{
            $sql = "insert into products(name,category_id,
            class_id,price,stock,ROM,RAM) values( :name, :category_id, :class_id,
            :price, :stock, :ROM, :RAM);";
            
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindValue( ":name",$product_name,PDO::PARAM_STR);
            $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
            $stmt->bindValue(":class_id", $class_id, PDO::PARAM_INT);
            
            //using param str in price kay auto na gin convert it mysql to decimal
            $stmt->bindValue(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
            $stmt->bindParam(":ROM", $ROM, PDO::PARAM_INT);
            $stmt->bindParam(":RAM", $RAM, PDO::PARAM_INT);

            return $stmt->execute();
        }catch(PDOException $e){
            return false;
        }
        
        
    }
    function query($sql, $fetch){ //DO NOT LET IT USER PAG INPUT HAN PARAM :) only hard coded sql statments.
        try{
        $database = parent::connect();
        $stmt = $database->prepare($sql);
        $stmt->execute();
        if($fetch){return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        }catch(PDOException $e){
            return 0;
        }
    }

    function update_product($product_id,$product_name, $category_id, $class_id,$price,$stock,$ROM,$RAM){
        try{
            $sql = "UPDATE products SET name= :name, category_id= :category_id,
            class_id= :class_id, price= :price, stock= :stock, ROM= :ROM, RAM= :RAM WHERE product_id = :product_id;";
            
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindValue(":product_id",$product_id,PDO::PARAM_INT);
            $stmt->bindValue( ":name",$product_name,PDO::PARAM_STR);
            $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
            $stmt->bindValue(":class_id", $class_id, PDO::PARAM_INT);
            
            //using param str in price kay auto na gin convert it mysql to decimal
            $stmt->bindValue(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
            $stmt->bindParam(":ROM", $ROM, PDO::PARAM_INT);
            $stmt->bindParam(":RAM", $RAM, PDO::PARAM_INT);

            return $stmt->execute();
        }catch(PDOException $e){
            return false;
        }
   }
   function delete_product($product_id){
        try{
            $sql = "DELETE FROM products WHERE product_id = :product_id;";
            
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindValue(":product_id",$product_id,PDO::PARAM_INT);
            return $stmt->execute();
        }catch(PDOException $e){
            return false;
        }
   }

   function change_status($status){

   }

   function get_all_orders() {
    try {
        $sql = 
            "SELECT o.order_id, o.user_id, u.username, o.address_id, a.city,
                a.province,o.order_date,o.total_amount, o.order_status, COUNT(oi.order_item_id) AS total_items
            FROM orders o
            INNER JOIN users u ON o.user_id = u.user_id
            INNER JOIN address a ON o.address_id = a.address_id
            LEFT JOIN order_items oi ON o.order_id = oi.order_id
            GROUP BY o.order_id, u.username, a.city, a.province";
        return $this->query($sql, true);
    }catch (PDOException $e) {
        return [];
    }
    }
}
?>