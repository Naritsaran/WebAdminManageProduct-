<?php
    include "connection.php";
    $id = $_GET['product_id'];

    //ลบรูป
    $imgPath = $_GET['imgPart'];

    if(file_exists($imgPath)) {
        unlink($imgPath);
    }

    $stmt = $conn->prepare("DELETE FROM `products` WHERE product_id = ?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $result = $stmt->get_result();


   
    if($stmt->execute()){
        header("Location: productUser.php?msg=Record deleted sucessfully");
        //echo "sucessfully";
    }else{
        echo 'Failed: '. mysqli_error($conn);
    }
    
    $stmt->close();
?>