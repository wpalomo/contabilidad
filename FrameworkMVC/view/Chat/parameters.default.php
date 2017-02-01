<?php 
	include('header.php');
	
	// RECUPERANDO VALORES
	$inform		= "";
	
	if($_SESSION["oUsuario"]->status<>1){
		RedireccionarHeader("login.php");
	}else{			
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			@$seleccionados=implode("','",$_POST["aopciones"]); settype($seleccionados,'string');
			@$guardar=$_POST["guardar"]; settype($guardar,'integer');
			@$adatos=$_POST["adatos"];
						
			// Guardando cambios
			if($guardar==1) {
				if (!empty($adatos)) {
					foreach($adatos as $key=>$value) {
						$name	= (isset($value['name'])) ? $value['name'] : "";
						$text	= (isset($value['text'])) ? $value['text'] : "";
						$int	= (isset($value['int'])) ? $value['int'] : 0;
						setDefault($name,$text,$int);
					}
				}
			}
		}
	}

	function setDefault($name,$text,$int){
		$sql	= sprintf("UPDATE `parameters` SET `int`=%s, `text`='%s', `timestamp`=NOW() WHERE `name`='%s';",$int,$text,$name);	
		$result	= pg_query($sql,HELP_DESK_LINK);
	}
	
	function setInputText($id,$key,$value,$readonly) {
		$readonly = $readonly==1 ? "" : "readonly=\"readonly\"" ;
		$value	 = sprintf("<input id=\"textfield\" name=\"adatos[%s][%s]\" %s type=\"text\" value=\"%s\" style=\"%s\">",$id,$key,$readonly,$value,"width:100%");
		return $value;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>HelpDesk - Set Users</title>

<?php include("links.php"); ?>

<script type="text/javascript">
function eliminar(){
	var answer = confirm("�Esta seguro de eliminar los registros marcados?");
	if (answer){
		document.forms[0].eliminar.value=1;
		document.forms[0].submit();		
	} else {
		alert("No se eliminaran los registros")
	}
}

function guardar(){
	var answer = confirm("�Esta seguro de guardar las modificaciones?");
	if (answer){
		document.forms[0].guardar.value=1;
		document.forms[0].submit();		
	} else {
		alert("No se han guardado las modificaciones")
	}
}
</script>

</head>
<body>
<form action="parameters.default.php" method="post" enctype="multipart/form-data" name="frmWrite" target="_self" id="frmWrite">
  <table width="100%" border="0" cellpadding="1" cellspacing="0" id="contenido">
    <tr>
      <td valign="middle" class="title"><b><a href="profile.php" target="_self"><img src="<?php print($_SESSION["oUsuario"]->avatarp); ?>" alt="Avatar" width="32" height="32" border="0" align="absmiddle">&#8250;&#8250;&nbsp;<?php print($_SESSION["oUsuario"]->name); ?></a>&nbsp;
         <a href='home.php'>&#8250;&#8250;&nbsp;Home</a>
      &#8250;&#8250;&nbsp;<a href="parameters.php">Parametros</a></b></td>
    </tr>
    <tr>
      <td align="left" valign="top"><div class="marco">

          <table width="100%" cellpadding="2" cellspacing="2">
            
            
            <tr>
              <td class="TituloGrupo">Propiedades generales de usuarios</td>
              </tr>
            <tr>
              <td class="Titulo">&nbsp;</td>
            </tr>
            <tr>
              <td class="marco">
              <div class="panelScroll">
              <table width="100%" border="0" cellspacing="2" cellpadding="0">
<?php
//CONSULTANDO LISTA
$sql="SELECT * FROM `parameters` WHERE `name` IN ('DefaultEncode','DefaultIconRoom','DefaultIdAvatar','DefaultIdRoom','DefaultRoomName','LogsClearOut','LogsClearPrv','UserLocationSend','UsersAllAvatar','UsersEmoticons','UsersEmoticonsSeparator','UsersIp','UsersTimeOut'); ";
$result=pg_query($sql,HELP_DESK_LINK);
$registro=0;

if($result) {
	while($row = pg_fetch_array($result)) 
	{
		printf("<tr>");
		printf("<td colspan=\"2\" class=\"Titulo\" style=\"text-align:left;\">%s</td>",$row["description"]);
		printf("</tr>");		
		printf("<tr>");
		printf("<td width=\"20\">Name</td><td>%s</td>",setInputText($registro,"name",$row["name"],0));
		printf("</tr>");
		printf("<tr>");
		printf("<td width=\"20\">Int</td><td>%s</td>",setInputText($registro,"int",$row["int"],1));
		printf("</tr>");		
		printf("<tr>");
		printf("<td width=\"20\">Text</td><td>%s</td>",setInputText($registro,"text",$row["text"],1));
		printf("</tr>");
		printf("<tr>");
		printf("<td class=\"Titulo\">&nbsp;</td><td>&nbsp;</td>");
		printf("</tr>");
		$registro+=1;
	}
}

pg_free_result($result);
?>                  
</table>       
              </div></td>
            </tr>
            <tr>
              <td class="TituloGrupo"><span style="color:#FF0000;"><?php print($inform); ?>&nbsp;
                <input name="eliminar" type="hidden" id="eliminar" value="0">
                <input name="guardar" type="hidden" id="guardar" value="0">
              </span></td>
            </tr>
            <tr>
              <td align="right"><table border="0" cellspacing="2" cellpadding="0">
                <tr>
                  <td width="32" height="32"><a href="javascript:guardar();"><img src="images/32x32/system/save.png" alt="Guardar" width="32" height="32" border="0" align="absmiddle"></a></td>
                  <td width="32" height="32"><a href="parameters.default.php"><img src="images/32x32/system/update.png" alt="Actualizar" width="32" height="32" border="0" align="absmiddle"></a></td>
                  <td width="32" height="32">&nbsp;</td>
                  <td width="32" height="32">&nbsp;</td>
                  <td width="32" height="32">&nbsp;</td>
                  <td width="32" height="32"><a href="home.php"><img src="images/32x32/system/home.png" alt="Guardar" width="32" height="32" border="0" align="absmiddle"></a></td>
                </tr>
            </table></td>
              </tr>
          </table>
        </div>
</td>
    </tr>
  </table>
</form>
</body>
</html>