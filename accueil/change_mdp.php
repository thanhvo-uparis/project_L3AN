<?php
require_once ('oublie_mdp.php');
require_once ('views/menu.php');
?>

<h4 class="title-element">Récupération de la mot de passe</h4>
<?php
switch ($section) {
    case 'code':
        require_once ('views/form/code.php');
        break;
    case 'change_password':
        require_once ('views/form/change_password.php');
        break;
    default:
        break;
}
echo (isset($error)) ? '<span style="color:red">' . $error . '</span>' : "";
?>