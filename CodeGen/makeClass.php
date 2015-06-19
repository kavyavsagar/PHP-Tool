<?PHP
include("classes/fastTemplate.cls.php");	//Include the Fast template Class
include("common.php");
$tpl = new  FastTemplate("templates");		
$tpl->define(array("main"=>"class.tmpl.html"));	

$tpl->define_dynamic("declarevariables","main");
$tpl->define_dynamic("assignvariables","main");
$tpl->define_dynamic("postvarsvariables","main");

$tpl->define_dynamic("getvarsvariables","main");
$tpl->define_dynamic("parseinsertfeilds","main");
$tpl->define_dynamic("parsefeilds","main");

$tpl->define_dynamic("parseupdatefeilds","main");
$tpl->define_dynamic("parseinsertfeilds","main");
$tpl->define_dynamic("validatefeilds","main");

$tpl->define_dynamic("retrieverowfeilds","main");
$tpl->define_dynamic("retrievearraynumfeilds","main");
$tpl->define_dynamic("retrievearraystringfeilds","main");

$tpl->define_dynamic("retrievearrayparsing","main");



mysql_select_db($_REQUEST["dbname"]);
$table = $moduleName;
$arrFields = array();
$arrValidate = explode(",",$validateFeilds);

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
	
	$arrCount = count($arrFields);
	$count    = 1;
	
	foreach($arrFields as $row){
	
		$tpl->assign("TPL_DECLAREVARIABLE",$row["name"]);
		$tpl->parse("DECVAR",".declarevariables");
		
		$tpl->assign("TPL_ASSIGNVARIABLE",$row["name"]);
		$tpl->parse("ASSVAR",".assignvariables");
		
		$tpl->assign("TPL_POSTVARS",$row["name"]);
		$tpl->parse("POSTVAR",".postvarsvariables");
		
		$tpl->assign("TPL_GETVARS",$row["name"]);
		$tpl->parse("GETVAR",".getvarsvariables");
		
		if($primaryKey != $row["name"]){
			
			$tpl->assign("TPL_FEILDS",$row["name"]);
			if($count <> $arrCount) $tpl->assign("TPL_SEPERATOR",",");
			else $tpl->assign("TPL_SEPERATOR","");
			$tpl->parse("INSVAR",".parseinsertfeilds");
			
		
			$tpl->assign("TPL_FEILDS",$row["name"]);
			if($count <> $arrCount) $tpl->assign("TPL_SEPERATOR",",");
			else $tpl->assign("TPL_SEPERATOR","");
			$tpl->parse("PAITSVAR",".parsefeilds");
			
			$tpl->assign("TPL_FEILDS",$row["name"]);
			if($count <> $arrCount) $tpl->assign("TPL_SEPERATOR",",");
			else $tpl->assign("TPL_SEPERATOR","");
			$tpl->parse("UPDATEVAR",".parseupdatefeilds");
			
			if(in_array($row["name"],$arrValidate)){
				$tpl->assign("TPL_FEILDS",$row["name"]);
				$tpl->assign("TPL_FIELD_NAME",innerCapsToReadableText(str_replace("Id","Name",$row["name"])));
				if($count <> $arrCount) $tpl->assign("TPL_SEPERATOR",",");
				else $tpl->assign("TPL_SEPERATOR","");
				$tpl->parse("VALVAR",".validatefeilds");
			}	
			
		}
		
		
		
		$tpl->assign("TPL_FEILDS",$row["name"]);
		$tpl->parse("RETROWVAR",".retrieverowfeilds");
		
		if($row["numeric"] == 1){
			$tpl->assign("TPL_RETNUMFEILDS",$row["name"]);
			$tpl->parse("AAADD",".retrievearraynumfeilds");
			
		}else{
			
			$tpl->assign("TPL_RETSTRINGFEILDS",$row["name"]);
			$tpl->parse("MMPPP",".retrievearraystringfeilds");
			
		}
		$tpl->parse("ARPAR",".retrievearrayparsing");
		
		
		$count++;
	}	
	//echo $_REQUEST["projectPrefix"];
	$tpl->assign("TPL_IDS",$ids); 
	$tpl->assign("TPL_PRIMARYKEY",$primaryKey); 
	$tpl->assign("TPL_MODULE",$_REQUEST["moduleName"]); 
	$tpl->assign("TPL_PREFIX",$_REQUEST["projectPrefix"]);  
	$tpl->assign("TPL_PPREFIX",substr($_REQUEST["projectPrefix"],3,(strlen($_REQUEST["projectPrefix"])-3)));  
	$tpl->assign("TPL_TABLENAME",$_REQUEST["tablename"]);    

$tpl->parse("MAIN","main");
include("header.php");
$contents	= $tpl->fetch();
$fp = fopen("../classes/".$_REQUEST["classSaveName"],"w");
fwrite($fp,trim($contents));
fclose($fp);
highlight_string($contents,false);
function innerCapsToReadableText($text) {
	 
	 
	  $text = ereg_replace("([A-Z]) ", "\\1",ucwords(strtolower(ereg_replace("[A-Z]"," \\0",$text))));
	  return ereg_replace("([A-Za-z])([0-9])", "\\1 \\2", $text);
}
?>
