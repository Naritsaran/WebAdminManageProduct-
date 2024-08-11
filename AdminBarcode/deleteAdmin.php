<?php
    include "connection.php";
     $id = $_GET['id'];
    

    $stmt = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $result = $stmt->get_result();
   
    if($stmt->execute()){
        header("Location: admin.php?msg=Record deleted sucessfully");
    }else{
        echo 'Failed: '. mysqli_error($conn);
    }
    
    $stmt->close(); 
?>