<?php
$code_value = (isset($_GET['code'])) ? $_GET['code'] : '';
?>
<p>Nouveau mot de passe pour <?=$_SESSION['recup_mail'] ?></p>
<form method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">Nouveau mot de passe </label>
        <input type="password" class="form-control" name="change_mdp">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Confirmation mot de passe</label>
        <input type="password" class="form-control" name="change_mdpc">
    </div>
    <input type="hidden" name="code_value" value="<?php echo htmlspecialchars($code_value) ?>">
    <button type="submit" class="btn btn-primary" name="change_submit">Valider</button>
</form>