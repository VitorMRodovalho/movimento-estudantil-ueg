<?php

if(!empty($_GET['abrir'])) {
  $abrir = $_GET['abrir'];

  $caminho["inicial"] = array("pagina" => "inicio.htm", "titulo" => "Apresentação");
  $caminho["programacao"] = array("pagina" => "programacao.htm", "titulo" => "Programação");
  $caminho["palestras"] = array("pagina" => "palestras.htm", "titulo" => "Palestras");
  $caminho["minicursos"] = array("pagina" => "minicursos.htm", "titulo" => "Minicursos");
  $caminho["salas"] = array("pagina" => "salas.htm", "titulo" => "Salas - Minicursos - Palestras");
  $caminho["mapa"] = array("pagina" => "mapa_evento.htm", "titulo" => "Mapa do Evento");
  $caminho["patrocinio"] = array("pagina" => "patrocinadores.htm", "titulo" => "Patrocinadores");
  $caminho["downloads"] = array("pagina" => "downloads.htm", "titulo" => "Downloads");
  $caminho["fotos"] = array("pagina" => "fotos.htm", "titulo" => "Fotos do Evento");
  $caminho["fotosp1"] = array("pagina" => "fotos_p_241005.htm", "titulo" => "Fotos do 1° Dia");
  $caminho["fotosp2"] = array("pagina" => "fotos_p_251005.htm", "titulo" => "Fotos do 2° Dia");
  $caminho["fotosp3"] = array("pagina" => "fotos_p_261005.htm", "titulo" => "Fotos do 3° Dia");
  $caminho["fotosp4"] = array("pagina" => "fotos_p_271005.htm", "titulo" => "Fotos do 4° Dia");
  $caminho["fotosf1"] = array("pagina" => "fotos_f1.htm", "titulo" => "Festa no Café Cancum");
  $caminho["fotosf2"] = array("pagina" => "fotos_f2.htm", "titulo" => "Festa na Its");
  $caminho["fotosf3"] = array("pagina" => "fotos_f3.htm", "titulo" => "Festa de Encerramento");
  $caminho["fotoso"] = array("pagina" => "fotos_o.htm", "titulo" => "Outras Fotos");

  @$pagina = $caminho[$abrir]["pagina"];
  @$titulo = "III Semana da Engenharia Civil - ";
  @$titulo .= $caminho[$abrir]["titulo"];
}
else  {
   $pagina = "inicio.htm";
   $titulo = "III Semana da Engenharia Civil";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="description" content="Site da III Semana de Engenharia UEG, contendo diversas informações">
<meta name="keywords" content="III Semana de Engenharia, UEG, Anápolis, UnuCET, 24 à 27 de outubro de 2005">
<base target="_self">
<title><?php echo $titulo;?></title>
<link href="estilocss/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
window.defaultStatus="III Semana de Engenharia Civil"
//-->
function mail()  {
 <!--
   window.open("email.php","email","toolbar=0,location=0,scrolling=0,status=0,menubar=0,scrollbars=0,resizable=0,center=0,top=0,width=450,height=400");
 //-->
 }
</script>
</head>
<body leftmargin="0" topmargin="0" ondragstart="return false" oncontextmenu="return false" onselectstart="return false">
<center>
<table width="776" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td class="fundo">
      <?php include "topo.htm";?>
    </td>
  </tr>
</table>
  <table width="780" border="0" cellpadding="0" cellspacing="0" class="fundo">
    <tr> 
    <td> 
      <?php include "data.php";?>
    </td>
    <td>
      <div align="right">www.engenhariacivil.ueg.br/semana</div>
	</td>
  </tr>
</table>
  <table width="780" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="124" height="244" rowspan="2" valign="top"> <table width="121" border="0" cellpadding="2" cellspacing="1">
          <tr> 
            <td width="115" height="10"></td>
          </tr>
          <tr> 
            <td class="aba"> <div align="center">Menu</div></td>
          </tr>
          <tr> 
            <td class="menu"><a href="?abrir=inicial">Apresenta&ccedil;&atilde;o</a></td>
          </tr>
          <tr> 
            <td class="menu"><a href="?abrir=programacao" >Programa&ccedil;&atilde;o</a></td>
          </tr>
          <tr> 
            <td class="menu"><a href="?abrir=palestras">Palestras</a></td>
          </tr>
          <tr> 
            <td class="menu"> <a href="?abrir=minicursos">Minicursos</a></td>
          </tr>
		  <tr> 
            <td class="menu"><a href="?abrir=salas">Salas</a></td>
          </tr>
          <tr> 
            <td class="menu"><a href="?abrir=mapa">Mapa do evento</a></td>
          </tr>
          <tr> 
            <td class="menu"><a href="?abrir=patrocinio">Patrocinadores</a></td>
          </tr>
          <tr> 
            <td class="menu"><a href="?abrir=downloads">Downloads</a></td>
          </tr>
          <tr> 
            <td class="menu"><a href="?abrir=fotos">Fotos do Evento</a></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td class="aba"><div align="center">Not&iacute;cias</div></td>
          </tr>
          <tr> 
            <td height="105" class="noticias"> 
              <?php include "noticias.htm";?>
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td class="aba">Enviar mensagem</td>
          </tr>
          <tr> 
            <td class="noticias"><div align="center"><img src="imagens/email_engcivil.gif" width="40" height="40"></div>
              <a href="javascript:mail();" >::Clique aqui</a></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td bgcolor="#3399FF" class="texto"><div align="center"><font color="#FFFFFF" ><strong>Fale 
                conosco</strong></font></div></td>
          </tr>
          <tr> 
            <td bgcolor="#D2E9FF"> <div align="center"> 
                <p class="texto"><font color="#000000"><strong>Coordena&ccedil;&atilde;o 
                  de Engenharia Civil</strong></font></p>
                <p class="texto"> <font color="#000000"><strong>Endere&ccedil;o:</strong><br>
                  UnUCET - Campus Henrique Santillo <br>
                  BR 153, Km 98 An&aacute;polis - GO<br>
                  CEP: 75001-970</font></p>
                <p class="texto"><font color="#000000"><strong>Telefone:</strong><br>
                  (62) 8439-1091<br>
                  (Vitor)</font><font color="#000000"><br>
                  (62) 8407-9792<br>
                  (Marcos Henrique)<br>
                  <br>
                  <strong>e-mail:</strong><br>
                  semanadeengenharia @ueg.br</font></p>
              </div></td>
          </tr>
          <tr> 
            <td bgcolor="#3399FF" height="5"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table></td>
      <td width="10" rowspan="2">&nbsp;</td>
      <td width="620" height="70" align="center" valign="middle"> 
        <div align="center">
          <iframe src="banner/banner.php" marginwidth="0" marginheight="0"  hspace="0" space="0"  frameborder="0" scrolling="no"  width="236" height="62"></iframe>
        </div></td>
      <td width="20" rowspan="2" background="imagens/fundo1.gif">&nbsp;</td>
    </tr>
    <tr>
      <td height="718" valign="top"> 
        <?php @include $pagina;?>
      </td>
    </tr>
  </table>
  <table width="780" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td colspan="2" class="fundo" ><div align="center">Universidade Estadual 
          de Goi&aacute;s - Coordena&ccedil;&atilde;o de Engenharia Civil - Comiss&atilde;o 
          da III Semana de Engenharia Civil 2005</div></td>
    </tr>
    <tr> 
      <td width="746">&nbsp;</td>
      <td width="20" background="imagens/fundo1.gif">&nbsp;</td>
    </tr>
  </table>
  <table width="780" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="740" height="20">&nbsp;</td>
      <td height="40" colspan="2" rowspan="2" background="imagens/fundo3.gif">&nbsp;</td>
    </tr>
    <tr> 
      <td height="20" background="imagens/fundo2.gif">&nbsp;</td>
    </tr>
  </table>
</center>
</body>
</html>
