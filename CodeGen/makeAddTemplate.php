<?PHP
include("classes/fastTemplate.cls.php");	//Include the Fast template Class
include("common.php");
$tpl = new  FastTemplate("templates");		
$tpl->define(array("main"=>"add.tmpl.html"));	
$tpl->define(array("phppage"=>"add.php.html"));	
$tpl->define_dynamic("javascriptvalidation","main");
$tpl->define_dynamic("inputfeilds","main");

///////////////////////////////////////////////////////////////////////////////////////////////////

$textString 		= '<input name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" type="text" id="TPL_PREFIXTPL_MODULE_TPL_FEILDS" maxlength="TPL_MAXLENGTH"   size="40" value="<?=stripslashes($TPL_PREFIXTPL_MODULE->TPL_FEILDS)?>">';

$textareaString		= '<textarea name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" id="TPL_PREFIXTPL_MODULE_TPL_FEILDS"><?=stripslashes($TPL_PREFIXTPL_MODULE->TPL_FEILDS)?></textarea>';

$checkboxString		= '<input type="checkbox" name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" value="checkbox" />';

$radioString		= '<input name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" type="radio" value="radiobutton" />';

$selectString		= '<select name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" id="TPL_PREFIXTPL_MODULE_TPL_FEILDS"> 
					   <?=TPL_FEILDSList?>
						</select>';
$fileString			= '<input type="file" name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" />';
$passwordString		= '<input type="password" name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" />';

////////////////////////////////////////////////////////////////////////////////////////////////////

$tpl->define_dynamic("postvarsvariables","main");

mysql_select_db($_REQUEST["dbname"]);
$table = $moduleName;
$arrFields = array();

$query = "SELECT * FROM " . $_REQUEST["tablename"];

	$result = mysql_query($query);
	$a=0;
	$fieldArray = array();
	while ($a < mysql_num_fields($result)) {
   
	   $meta = mysql_fetch_field($result, $a);
	   if (!$meta) {
		  //$classCode .=  "No information available<br />\n";
	   }
	   //echo $meta->max_length . "<BR>";
			if($meta->primary_key == 1){ $primaryKey = $meta->name;$ids = $meta->name . "s" ;} 
			$arrFields = array (
									"blob"=>     $meta->blob,
									"max_length"=>   $meta->max_length,
									"multiple_key"=> $meta->multiple_key,
									"name"=>         $meta->name,
									"not_null"=>     $meta->not_null,
									"numeric"=>      $meta->numeric,
									"primary_key"=>  $meta->primary_key,
									"table"=>        $meta->table,
									"type"=>         $meta->type,
									"unique_key"=>   $meta->unique_key,
									"unsigned"=>     $meta->unsigned,
									"zerofill"=>     $meta->zerofill,
							);
							
		
   	$a++;
	}
	print_r($arrFields);
	$arrValidate  	= $_REQUEST["validateFeildsList"];
	$arrAdd 		= $_REQUEST["addFeildslist"];
	
	foreach($arrValidate as $row){
		
		
		$tpl->assign("TPL_FEILDS",$row);
		$tpl->assign("TPL_FIELD_NAME",innerCapsToReadableText(str_replace("Id","Name",$row)));//
		$tpl->parse("DECVAR",".javascriptvalidation");
		$count++;
	}	
	//print_r($_REQUEST);
	foreach($arrAdd as $row){
		$option = "option_$row";
		$optionString = $$option ."String" ;
		//echo "HERE" . $$option . "<BR>";
		$fieldInput = str_replace("TPL_FEILDS",$row,$$optionString);
		$fieldInput = str_replace("TPL_MAXLENGTH",$arrFields["max_length"],$fieldInput);
		$tpl->assign("TPL_FEILDS",$row);
		$tpl->assign("TPL_FIELD_INPUT",$fieldInput);
		$tpl->assign("TPL_FIELD_NAME",innerCapsToReadableText($row));
		
		
		$tpl->parse("AASZ",".inputfeilds");
		$count++;
	}	
	
	$prprefix = str_replace("cls","",$_REQUEST["projectPrefix"]);
	$tpl->assign("TPL_IDS",$ids); 
	$tpl->assign("TPL_PRIMARYKEY",$primaryKey); 
	$tpl->assign("TPL_MODULE",$_REQUEST["moduleName"]); 
	$tpl->assign("TPL_PREFIX",$_REQUEST["projectPrefix"]);  
	$tpl->assign("TPL_TABLENAME",$_REQUEST["tableName"]);  
	$tpl->assign("TPL_PRPREFIX",$prprefix); 

$tpl->parse("MAIN","main");
include("header.php");
$contents	= $tpl->fetch();
$fp = fopen("../templates/".$_REQUEST["addTemplateName"],"w");
fwrite($fp,$contents);
fclose($fp);
highlight_string($contents,false);
$tpl->parse("MAIN","phppage");
$contents	= $tpl->fetch();
$phppagename = str_replace(".tmpl.",".",$_REQUEST["addTemplateName"]);

$fp = fopen("../" . $phppagename ,"w");
fwrite($fp,trim($contents));
fclose($fp);

function innerCapsToReadableText($text) {
	 
	 
	  $text = ereg_replace("([A-Z]) ", "\\1",ucwords(strtolower(ereg_replace("[A-Z]"," \\0",$text))));
	  return ereg_replace("([A-Za-z])([0-9])", "\\1 \\2", $text);
}
?>
