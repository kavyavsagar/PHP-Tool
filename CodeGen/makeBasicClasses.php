<?php
include("common.php");
$prefix = substr($_REQUEST["projectPrefix"],3,(strlen($_REQUEST["projectPrefix"])-3));

makeBasicClasses("Connect",$prefix);//connect
makeBasicClasses("Base",$prefix);//base
makeBasicClasses("Paginate",$prefix);//paginate

makeIncludeFiles("CommonBody",$prefix);//CommonBody
makeIncludeFiles("CommonPopupBody",$prefix);//CommonPopupBody



echo "Completed  Connect,Base and Paginate Classes";
function makeBasicClasses($class,$prefix){
	$classTemplate  = $class . ".tmpl.html";
	$contents		= file_get_contents("./templates/" .$classTemplate);
	$contents		= str_replace("TPL_SPPREF",$prefix,$contents);
	$fp = fopen("../classes/$prefix".$class . ".cls.php","w");
	fwrite($fp,trim($contents));
	fclose($fp);
}
function makeIncludeFiles($file,$prefix){

	$template  = $class . ".inc.html";
	$contents		= file_get_contents("./templates/" .$template);
	$contents		= str_replace("TPL_SPPREF",$prefix,$contents);
	$contents		= str_replace("TPL_PPREF",$prefix,strtoupper($contents));
	$fp 			= fopen("../classes/$prefix".$file . ".inc.php","w");
	fwrite($fp,trim($contents));
	fclose($fp);
}
?>