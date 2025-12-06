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
    function query($sql){ //DO NOT LET IT USER PAG INPUT HAN PARAM :) only hard coded sql statments.
        try{
        $database = parent::connect();
        $stmt = $database->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return 0;
        }
    }
   }
?>