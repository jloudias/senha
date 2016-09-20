<?php
/* Programa para trocar senha no OpenLDAP
   Autor: Jorge Loureiro Dias - Cel R/1
*/

// DADOS DO FORM
$usuario=$_POST['login'];
$senha_atual=$_POST['senha_atual'];
$senha_nova=$_POST['senha_nova'];

// DADOS DO SERVIDOR LDAP
$pessoas="ou=Pessoas,dc=jld,dc=com";
$servidor="192.168.0.61";
$porta=389;
$base="dc=jld,dc=com";
$administrador="cn=admin,dc=jld,dc=com";
$rdn="uid=".$usuario.",".$pessoas;
$info["userPassword"] = $senha_nova;


$con = @ldap_connect($servidor, $porta)
	or die("Erro na conexao ao servidor {$servidor}");

if ($con) {

    // Versao 3 do LDAP	
    ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);

    // binding to ldap server
    $bind = @ldap_bind($con, $rdn, $senha_atual);

    // verify binding
    if ($bind) {
        echo "LDAP bind successful...";
	// user exists -> change password
	$rs=@ldap_mod_replace($con, $rdn, $info);
   	if ($rs) {
	  $msg="Senha foi atualizada com sucesso!";
	  }
    	else{
	  $msg="Ocorreu um erro ao trocar a senha! Contate o Administrador.";
	  }
	// user or password invalid
    } else {
	  $msg="Usuario inexistente ou senha incorreta! Tente novamente.";
	
    }
	// show message and return to form
        header ("Refresh: 0;url=index.html");
        echo "<script language=Javascript>alert (\"$msg\")</script>";
}
?>

