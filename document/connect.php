<?php
	$nom_control = $_POST['Nom_du_controle'];
	$deadline = $_POST['Deadline'];
    $realise_par = $_POST['Realise_par'];
    $statut = $_POST['Statut'];
	$niv_risque = $_POST['Niveau_de_risque'];
	$design = $_POST['Design'];
    $efficacite = $_POST['Efficacite'];

	// Database connection
 /*	$conn = new mysqli('localhost','root','','bdd_projet-l3an1');
	if($conn->connect_error){
		echo "$conn->connect_error";
		die("Connection Failed : ". $conn->connect_error);
	} else {
        $stmt = $conn->prepare("insert into tableau_doc(Nom_du_controle, Deadline, Realise_par, Statut, Niveau_de_risque, Design, Efficacite)values(?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssss", $nom_control, $deadline, $realise_par, $statut,  $niv_risque, $design, $efficacite);
		$stmt->execute();
		echo "Uploading réussis <br>";
		$stmt->close();
		$conn->close();
        var_dump($_POST);
    }
  */
     $connect = mysqli_connect("localhost", "root", "", "bdd_projet-l3an1");
       if ($connect ->connect_error){
       	 die ("pas de connexion: " . connect ->connect_error);
       	 exit();
       }

    $sql = "insert into tableau_doc(Nom_du_controle, Deadline, Realise_par, Statut, Niveau_de_risque, Design, Efficacite) values ('$nom_control', '$deadline', '$realise_par', '$statut',  '$niv_risque', '$design', '$efficacite')";
    if($connect ->query($sql) === TRUE) {
    	echo "réussi à l'ajoute à BDD";
    } else {
    	echo "Error: "  .$sql . "<br>" . $connect -> error;
    }

    $connect -> close();


?>