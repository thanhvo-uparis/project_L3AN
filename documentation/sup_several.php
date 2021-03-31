<?php 
   $connect=  mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");
    
    if(!$connect){
        die("connection failed !".mysqli_connect_error());
    }

    $query = mysqli_query($connect, "select * from controle");

    if(isset($_POST['delete'])){
        $cases = $_POST['ids'];
        foreach($cases as $id){
            mysqli_query($connect, "delete from controle where id =".$id);
        }
        header("location : document.php");
    }
    mysqli_close($connect);
?>
