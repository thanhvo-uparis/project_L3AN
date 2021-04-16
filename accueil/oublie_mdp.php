<?php
session_start(); // pour utiliser $_SESSION
$bdd = new PDO("mysql:host=localhost;dbname=bdd_projet-l3an1", "root", "");
define('HOME_URL', 'http://localhost/homepage/');
// lấy giá trị section trên URL
$section = (isset($_GET['section'])) ? htmlspecialchars($_GET['section']) : "";
if (isset($_POST['recup_submit'])) {
    if (!empty($_POST['recup_mail'])) {
        $recup_mail = htmlspecialchars($_POST['recup_mail']);
        if (filter_var($recup_mail, FILTER_VALIDATE_EMAIL)) {
            // lấy thông tin chi tiết user cuả email vừa nhập
            $mailexist = $bdd->prepare('SELECT email FROM utilisateur WHERE email=?');
            $mailexist->execute(array(
                $recup_mail
            ));
            $mailexist_count = $mailexist->rowCount(); // tổng số user trả về
            if ($mailexist_count === 1) // kiểm tra nếu tồn tài user
            {
                $_SESSION['recup_mail'] = $recup_mail; // gán biến $recup_mail vào biến session
                //fonction: créer automatique un code aléatoires
                $chars = '0123456789';
                $recup_code = ''; // gán code ban đầu là rỗng
                for ($i = 0; $i < 8; $i++) {
                    $recup_code .= $chars[rand(0, strlen($chars) - 1)];
                }

                $recup_update = $bdd->prepare('UPDATE utilisateur SET code = ? WHERE email = ?'); //si oui: faire mise à jour le code
                $recup_update->execute(array(
                    $recup_code,
                    $recup_mail
                ));


                //PARTIE 2: Envoie d'un code par mail
                //Redirection vers la page d'entrée du code $GET['section']='code' ??
                //Check code, si le code exact, redirection vers la page de changement de mdp change_mdp.php. $_GET['section']='mdpform'
                //Si les deux mots de passe correspondent -> hashage en sha1() et enregistrement dans la BDD
                //chú ý: href='...c_mdp.php?section=code&code=".$recup_code." ' nhờ đoạn code này mà sau khi click vào đường dẫn trong mail -> chuyển trực tiếp tới page change_mdp.php với email= email do người dùng nhập vào.
                $subject = 'Khôi phục mật khẩu';
                $message = 'Click vào <a href="' . HOME_URL . 'change_mdp.php?section=code&code=' . $recup_code . '" >đây</a> Đây là mã để đặt lại mật khẩu: ' . $recup_code;
                $header = 'MIME-Version: 1.0\r\n';
                $header .= 'From: "nopreply@mazars.fr"<support@mazars.fr>' . "\n";
                $header .= 'Content-type: text/html; cahset="uft-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';

                $mail = mail($recup_mail, $subject, $message, $header);
                header('Location: ' . HOME_URL . 'change_mdp.php?section=code'); // chuyển tới trang nhập code
            } else {
                $error = "Địa chỉ email này chưa được đăng ký";
            }
        } else {
            $error = "Địa chỉ email không hợp lệ";
        }
    } else {
        $error = "Hãy điền địa chỉ email của bạn";
    }
}

//PARTIE 3: Vérifier le code dans le champ.
// Et vérifier 2 nouveaux mots de passe saisis par l'utilisateur.
if (isset($_POST['verif_submit'])) {
    if (!empty($_POST['verif_code'])) { //si le champ n'est pas vide.
        $verif_code = htmlspecialchars($_POST['verif_code']); //
        $verif_req = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ? AND code = ?');
        $verif_req->execute(array(
            $_SESSION['recup_mail'],
            $verif_code
        ));
        $verif_req = $verif_req->rowCount();
        if ($verif_req == 1) {
            header('Location: ' . HOME_URL . 'change_mdp.php?section=change_password&code=' . $verif_code); // chuyển tới trang đổi mật khẩu
        } else {
            $error = "Code invalide";
        }
    } else {
        $error = "Veuillez saisir votre code de confirmation";
    }
}

// click submit trong form thay đổi mật khẩu
if (isset($_POST['change_submit'])) {
    // kiểm tra giá trị code có được truyền hay ko, nếu ko chuyển tới form nhập code
    if (!empty($_POST['code_value'])) {
        $verif_req = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ? AND code = ?');
        $verif_req->execute(array(
            $_SESSION['recup_mail'],
            $_POST['code_value']
        ));
        $verif_req = $verif_req->rowCount();
        if ($verif_req == 1) {
            if (isset($_POST['change_mdp'], $_POST['change_mdpc'])) {
                $verif_confirme = $bdd->prepare('SELECT email FROM utilisateur WHERE email = ?');
                $verif_confirme->execute(array(
                    $_SESSION['recup_mail']
                ));
                $verif_confirme = $verif_confirme->fetch();
                $verif_confirme = $verif_confirme['confirme'];
                if ($verif_confirme == 1) {
                    $mdp = md5($_POST['change_mdp']);
                    $mdpc = md5($_POST['change_mdpc']);
                    if (!empty($mdp) and !empty($mdpc)) {
                        if ($mdp == $mdpc) {
                            $mdp = sha1($mdp);
                            $ins_mdp = $bdd->prepare('UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?');
                            $ins_mdp->execute(array(
                                $mdp,
                                $_SESSION['recup_mail']
                            ));
                            header('Location:http://localhost/homepage');
                        } else {
                            $error = "Votre mot de passe ne correspond pas ";
                        }
                    } else {
                        $error = "Veuillez remplir tous les champs ";
                    }
                }
            }
        } else {
            header('Location: ' . HOME_URL . 'change_mdp.php?section=code'); // chuyển tới trang nhập code
        }
    } else {
        header('Location: ' . HOME_URL . 'change_mdp.php?section=code'); // chuyển tới trang nhập code
    }
}

//PARTIE 4:
?>
