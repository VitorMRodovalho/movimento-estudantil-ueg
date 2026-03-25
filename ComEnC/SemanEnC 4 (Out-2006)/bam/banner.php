<?php
  if(!isset($_COOKIE['banner']))
    {
     $ultimo = 0;
    }
  else
    {
      $ultimo = $_COOKIE['banner'];
    }
  $imagem = array(1 => "belgo", "crea", "funcer", "impercia", "lafix", "cafecancun", "realmix", "schneider", "sika", "its", "panapolis", "cimpor", "sinduscon", "tsunami");
  $aleatorio = array_rand($imagem,2);
  $i = 0;
  if($aleatorio[0] == $ultimo)
    {$i = 1;}
  $gerador = $imagem[$aleatorio[$i]].".gif";
  setcookie("banner","$aleatorio[$i]");
?>
<html>
<head>
<meta http-equiv="refresh" content="10">
<link href="estilocss/estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body leftmargin="0" topmargin="0">
<img src="<?php echo $gerador;?>" border="1" width="234" height="60"> 
</body>
</html>
