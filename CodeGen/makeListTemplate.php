<?PHP
include("classes/fastTemplate.cls.php");	//Include the Fast template Class
include("common.php");
$tpl = new  FastTemplate("templates");		
$tpl->define(array("main"=>"list.tmpl.html"));	
$tpl->define(array("phppage"=>"list.php.html"));	
$tpl->define_dynamic("sumbitsearchactionvalueassigning","main");
$tpl->define_dynamic("sumbitpageaction","main");
$tpl->define_dynamic("sumbitsearchaction","main");
$tpl->define_dynamic("clearsearch","main");
$tpl->define_dynamic("inputfields","main");
$tpl->define_dynamic("extrafields","phppage");

$tpl->define_dynamic("fieldnameparsing","main");
$tpl->define_dynamic("fieldvalueparsing","main");
///////////////////////////////////////////////////////////////////////////////////////////////////

$textString 		= '<input name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" type="text" id="TPL_PREFIXTPL_MODULE_TPL_FEILDS" maxlength="70"   size="40" value="<?=stripslashes($TPL_PREFIXTPL_MODULE->TPL_FEILDS)?>">';

$textareaString		= '<textarea name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" id="TPL_PREFIXTPL_MODULE_TPL_FEILDS"><?=stripslashes($TPL_PREFIXTPL_MODULE->TPL_FEILDS)?></textarea>';

$checkboxString		= '<input type="checkbox" name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" value="checkbox" />';

$radioString		= '<input name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" type="radio" value="radiobutton" />';

$selectString		= '<select name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" id="TPL_PREFIXTPL_MODULE_TPL_FEILDS"> 
					   <?=TPL_FIELD_NAMEList?>
						</select>';
$fileString			= '<input type="file" name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" />';
$passwordString		= '<input type="password" name="TPL_PREFIXTPL_MODULE_TPL_FEILDS" />';

////////////////////////////////////////////////////////////////////////////////////////////////////

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
			$arrFields[] = array (
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
	//prefix parsing 
	$prefix = substr($_REQUEST["projectPrefix"],3,(strlen($_REQUEST["projectPrefix"])-3));
	$tpl->assign("TPL_PPREF",strtoupper($prefix));
	$tpl->assign("TPL_SPPREF",strtolower($prefix));
	
	
	$arrSearch  	= $_REQUEST["searchFeildsList"];
	$count =0;	
	foreach($arrSearch as $row){
		
		$tpl->assign("TPL_FEILDS",$row);
		$tpl->assign("TPL_FIELD_NAME",innerCapsToReadableText($row));
		$tpl->parse("AT1",".sumbitsearchactionvalueassigning");
		
		$tpl->assign("TPL_FEILDS",$row);
		if($count ==0) $tpl->assign("TPL_SEPERATOR","?");
		else $tpl->assign("TPL_SEPERATOR","&");
		$tpl->assign("TPL_FIELD_NAME",innerCapsToReadableText($row));
		$tpl->parse("AT2",".sumbitpageaction");
		
		$tpl->assign("TPL_FEILDS",$row);
		$tpl->assign("TPL_FIELD_NAME",innerCapsToReadableText($row));
		if($count ==0) $tpl->assign("TPL_SEPERATOR","?");
		else $tpl->assign("TPL_SEPERATOR","&");
		$tpl->parse("AT3",".sumbitsearchaction");
		
		$tpl->assign("TPL_FEILDS",$row);
		$tpl->assign("TPL_FIELD_NAME",innerCapsToReadableText($row));
		$tpl->parse("AT4",".clearsearch");
		//print_r($_REQUEST);
		$option = "searchOption_$row";
		$optionString = $$option ."String" ;
		//echo $$option;
		$fieldInput = str_replace("TPL_FEILDS",$row,$$optionString);
			$fieldInput = str_replace("TPL_FIELD_NAME","$" .str_replace("Id","Name",$row),$fieldInput);
		$tpl->assign("TPL_FIELD_INPUT",$fieldInput);
		$tpl->assign("TPL_FEILDS",$row);
		$tpl->assign("TPL_FIELD_NAME",innerCapsToReadableText(str_replace("Id","Name",$row)));
		$tpl->parse("AT5",".inputfields");
		
		$tpl->assign("TPL_FIELDS",$row);
		$tpl->parse("ATSS5",".extrafields");
		
		
		
		
		$count++;
	}	
	foreach($arrFields as $row){
	
		if($row["name"] <>$primaryKey){
			//echo "<BR>" . $row["name"]  .  "=>" . innerCapsToReadableText($row["name"])  ;
			$tpl->assign("TPL_FEILDNAME",innerCapsToReadableText(str_replace("Id","Name",$row["name"])));
			$tpl->assign("TPL_FIELDS",str_replace("Id","Name",$row["name"]));
			$tpl->assign("TPL_FEILDS",str_replace("Id","Name",$row["name"]));
		
			$tpl->parse("DE1",".fieldnameparsing");
			$tpl->parse("DE2",".fieldvalueparsing");
		}	
	}
	
	
	$tpl->assign("TPL_IDS",$ids); 
	$tpl->assign("TPL_PRIMARYKEY",$primaryKey); 
	$tpl->assign("TPL_MODULE",$_REQUEST["moduleName"]); 
	$tpl->assign("TPL_PREFIX",$_REQUEST["projectPrefix"]);  
	$tpl->assign("TPL_TABLENAME",$_REQUEST["tableName"]); 
	$tpl->assign("TPL_FRMLIST",$_REQUEST["formName"]);  
	$tpl->assign("TPL_FRMSEARCH",$_REQUEST["formNameSearch"]);
	$tpl->assign("TPL_COLSPAN",count($arrFields)+1);        
	

$tpl->parse("MAIN","main");
include("header.php");
$contents	= $tpl->fetch();
$fp = fopen("../templates/".$_REQUEST["listTemplateName"],"w");
fwrite($fp,trim($contents));
fclose($fp);
highlight_string($contents,false);

$tpl->parse("MAIN","phppage");
$contents	= $tpl->fetch();
$phppagename = str_replace(".tmpl.",".",$_REQUEST["listTemplateName"]);

$fp = fopen("../" . $phppagename ,"w");
fwrite($fp,trim($contents));
fclose($fp);

function innerCapsToReadableText($text) {
	 
	 
	  $text = ereg_replace("([A-Z]) ", "\\1",ucwords(strtolower(ereg_replace("[A-Z]"," \\0",$text))));
	  return ereg_replace("([A-Za-z])([0-9])", "\\1 \\2", $text);
}
?>
