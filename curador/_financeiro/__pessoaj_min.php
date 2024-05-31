<?
require_once("includes/conexao.php");
if (pode_algum("r", $_SESSION["permissao"])) {
?>

    <div>
        <label for="apelido_fantasia">Nome Fantasia:</label>
        <input title="Nome Fantasia" class="required" name="apelido_fantasia" value="<?= $rs->apelido_fantasia; ?>" id="apelido_fantasia" />
    </div>

<? } ?>