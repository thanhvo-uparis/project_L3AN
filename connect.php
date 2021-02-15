<?php
	$nom_control = $_POST['nom_control'];
	/*$deadline = $_POST['deadline'];
    $realise_par = $_POST['realise_par'];
	$niv_risque = $_POST['niv_risque'];
	$design = $_POST['design'];
    $efficacite = $_POST['efficacite'];
*/
	// Database connection
	$conn = new mysqli('localhost','root','','BDD_Projet-L3AN1');
	if($conn->connect_error){
		echo "$conn->connect_error";
		die("Connection Failed : ". $conn->connect_error);
	} else {
        $stmt = $conn->prepare("insert into tableau_doc(nom_control)values(?)");
		$stmt->bind_param("s", $nom_control /*$deadline, $realise_par, $niv_risque, $design, $efficacite*/);
		$execval = $stmt->execute();
		echo $execval;
		echo "Uploading réussis";
		$stmt->close();
		$conn->close();
	}
    var_dump($_POST);
?>