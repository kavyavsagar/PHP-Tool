<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>:: Class Builder ::</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
}
-->
</style>
</head>
<style>
td,body,select{
	font-family:"Trebuchet MS";
	font-size:8pt;
	
}
input,select{
	font-family:"Trebuchet MS";
	
	border:1pt solid #CCCCCC;
	font-size:8pt;
	
}
.headerTd{
	color:#FFFFFF;
	text-decoration:underline;
	font-family:"Trebuchet MS";
	font-size:14pt;
}
hr{
	color:#33CC99;
}
.disableObject{
	display:none;
}
.enableObject{
	display:'';
}
.topClass{
	position:absolute;
	top:600px;
	left:50Px;
	visibility:visible;
}

</style>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
include("common.php");
if($_REQUEST["dbname"] <>"")
$tableoptions = retrieveTableOptions($_REQUEST["dbname"],$_REQUEST["tablename"]);
if($_REQUEST["tablename"] <>""){
	$fieldoptions =  retrieveFeildOptions($_REQUEST["tablename"]);
}
if($_REQUEST["tableAlias"] <>"") $table	 = $_REQUEST["tableAlias"];
else $table = str_replace($projectPrefic,"",$_REQUEST["tablename"]);
$projectPrefic	= $_REQUEST["projectorefix"];

$arrValidateFeilds = 	explode(",",$_REQUEST["validateFeilds"]);
if($_REQUEST["preferredclassname"]=="")
	$preferredclassname = $projectPrefic. $table .".cls.php";
else
	$preferredclassname = $_REQUEST["preferredclassname"];

?>
<script src="common.js"></script>
<script>
function populateTables(){
	document.location.href="index.php?dbname=" + document.form1.dbname.value;
}
function showTableName(val){
	document.form1.tableAlias.value = val; 
}
function showFeildNames(){
	document.location.href="index.php?dbname=" + document.form1.dbname.value + "&tablename=" +  document.form1.tablename.value;
}
feildArray = new Array();
var arrIndex =0;   
function updateValidateFeilds() {   
	
	fieldInteger=document.form1.fieldnames.selectedIndex;
	flag = 0;
  	fieldString=document.form1.fieldnames.options[fieldInteger].value;
	for(i=0;i<feildArray.length;i++){
		
		if(feildArray[i]== fieldString){
			flag = 1;
		}
	}
	if(flag == 0){
		
		feildArray[arrIndex]	 = fieldString;
		arrIndex = arrIndex + 1;
		
		if(confirm("Sure to add '"+fieldString +"' for validation")){
			if(document.form1.validateFeilds.value == "")
				 document.form1.validateFeilds.value=  fieldString  ;
			else
				document.form1.validateFeilds.value= document.form1.validateFeilds.value + "," + fieldString  ;
		}
	}else{
		alert( "'" + fieldString + "' Already Added for Validation!");
	}			
}
function submitForm(val){

	frm  = document.form1;
	
	if(val == "Download") frm.action = "download.php";
	else 	frm.action = "";
	if(val == "Generate Class")  alert("The class has been created in the folder 'classes'");
	if(val == "Choose Template Options"){
		frm.validateFeilds.value =getSelectedItem(frm , "validateFeildsList[]");
		frm.searchFeilds.value =getSelectedItem(frm , "searchFeildsList[]");
		frm.addFeilds.value = getSelectedItem(frm, "addFeildslist[]");
		//alert(frm.validateFeilds.value);
		PostString 									= "validateFeilds=" + frm.validateFeilds.value;
		PostString 									+= "&searchFeilds=" + frm.searchFeilds.value;
		PostString 									+= "&addFeilds=" + frm.addFeilds.value;
		PHPpage										= "templateOptions.php";
		response  									= callRequestObject(PostString,PHPpage,"post");
		
		document.getElementById("templateOption").innerHTML 					= response;
	}
	else if(val == "Create Template And Class"){
	
		
		frm.action = "makeFiles.php";
		frm.target = "";
	}
	
	else{
		frm 						= document.form1;
			
		moduleName  				= frm.moduleName.value; 
		if(moduleName == ""){
			alert("Please Enter Module Details");
			frm.moduleName.focus();
			return false;
		}
		alert(val);
		if(val == "Make Class"){
					frm.validateFeilds.value =getSelectedItem(frm , "validateFeildsList[]");
					frm.searchFeilds.value =getSelectedItem(frm , "searchFeildsList[]");
					frm.addFeilds.value = getSelectedItem(frm, "addFeildslist[]");
				frm.target = "_blank";
				frm.action = "makeClass.php";
				frm.submit();
				
				
		}
		if(val == "Make Add Template"){
			
				frm.target = "_blank";
				frm.action = "makeAddTemplate.php";
				frm.submit();
			
		}
		if(val == "Make List Template"){
				
				frm.target = "_blank";
				frm.action = "makeListTemplate.php";
				frm.submit();
			
		}
		 if(val == "Make Basic Classses"){
			
			frm.target = "_blank";
			frm.action = "makeBasicClasses.php";
			frm.submit();
			
		}
	}	
	return false;
}	
function getSelectedItem(formName,objList) {

	var selectedValue = "";
	
	
	for(var i=0;i<formName.elements[objList].length;i++)
	{
	  	if (formName.elements[objList][i].selected)
		{
	 		 if (selectedValue != "")
			   {
				selectedValue += ",";	
			   }
			selectedValue	+= formName.elements[objList][i].value + "";
			

		} 	
	}	
	if (selectedValue == "") {
		
	   selectedValue = formName.elements[objList].value;
	}
	
	return selectedValue;
	
} 
function populateOtherFeilds(){
	frm 						= document.form1;

	moduleName  				= frm.moduleName.value; 
	projectPrefix  				= frm.projectPrefix.value; 
	frm.paginateClass.value		= projectPrefix  + "Paginate";	
	
	frm.arrayName.value			= "arrSearch" + moduleName;	
	frm.searchClassName.value	= projectPrefix  + "Search" + moduleName;
	frm.className.value			= projectPrefix+  moduleName;	
	frm.formName.value			= "frm" + moduleName + "List";
	frm.formNameSearch.value	= "frm" + moduleName + "Search";
	frm.formNameAdd.value		= "frm" + moduleName;
	prprojectPrefix 			= projectPrefix.replace("cls","");
	frm.classSaveName.value		= prprojectPrefix  + moduleName + ".cls.php";	
	frm.addTemplateName.value		= prprojectPrefix  + moduleName + ".tmpl.php";	
	frm.listTemplateName.value		= prprojectPrefix  + moduleName + "List.tmpl.php";
	
}
function closeLayer(){
	document.getElementById("showFrame").width = 0;
	document.getElementById("showFrame").height = 0;
	document.getElementById("showLayer").className = "Layer1";
	document.getElementById("resTitle").innerHTML = "";
	document.getElementById("closeOpt").innerHTML = "";
	
}
</script>
  <form  name="form1" method="post" action="" >
  <input type="hidden"  value="" name="validateFeilds"/ >
  <input type="hidden"  value="" name="searchFeilds"/ >
  <input type="hidden"  value="" name="addFeilds"/ >
 
<table width="100%" border="0" cellspacing="0" cellpadding="3">

  <tr>
    <td height="25" colspan="4" align="left" bgcolor="#9999cc" style="border-bottom:1pt solid #000000" class="headerTd"><b><img src="images/php.gif" width="120" height="67" />&nbsp;CODE ENGINE  </b></td>
    </tr>
  <tr>
    <td height="10" colspan="4" bgcolor="#EEEEEE" >Create two folders with write permission on the root folder 1.classes 2.templates ; you can change the db connection on common.php page </td>
    </tr>
   <tr>
     <td height="10" colspan="4" align="left" valign="top" bgcolor="#CCCCCC"><b>Choose DB,Table and necessary Fields </b></td>
    </tr>
   <tr>
    <td width="25%" height="10" align="left" valign="top"><b>DB Name </b></td>
    <td width="25%" height="10" align="left">
      <select name="dbname" onchange="populateTables();"><?=retrieveDbOptions($_REQUEST["dbname"]);?></select></td>
    <td width="17%" align="left">&nbsp;</td>
    <td width="33%" align="left">&nbsp;</td>
   </tr>
  <tr>
    <td height="10" align="left" valign="top"><b>Table Name </b></td>
    <td height="10" align="left">
      <label>
      <select name="tablename" onchange="showFeildNames()">
	  	<option ''>Choose table</option>
	  	<?=$tableoptions?>
	  	</select>
      </label></td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" align="left" valign="top"><strong>Validate Feilds</strong></td>
    <td height="10" align="left"><select name="validateFeildsList[]" size="5" multiple="multiple"  id="validateFeildsList">
	<?=$fieldoptions?>
    </select></td>
    <td height="10" align="left" valign="top"><strong>Search Feilds </strong></td>
    <td height="10" align="left"><select name="searchFeildsList[]" size="5" multiple="multiple" id="searchFeildsList" >
        <?=$fieldoptions?>
    </select></td>
  </tr>
  
  <tr>
    <td height="10" align="left" valign="top"><strong>Add Feilds</strong></td>
    <td height="10" align="left"><select name="addFeildslist[]" size="5" multiple="multiple" id="addFeildslist" >
      <?=$fieldoptions?>
        </select></td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <!--
  <tr>
    <td height="10" align="left" valign="top"><b>Preffered Class Name </b></td>
    <td height="10" align="left"><input name="preferredclassname" type="text" size="50"  value="<?=$preferredclassname?>"/>
      &nbsp;(<b>This optiion is used to generate the class file</b>) </td>
  </tr>
  -->
   <tr>
     <td height="10" align="left">&nbsp;</td>
     <td height="10" align="left">&nbsp;</td>
     <td height="10" align="left">&nbsp;</td>
     <td height="10" align="left">&nbsp;</td>
   </tr>
   <tr>
    <td height="10" colspan="4" align="left"><input type="button" name="Submit4" value="Choose Template Options"  onclick=" return submitForm('Choose Template Options');"/>
&nbsp;[<a href="#templateOption"  onclick="templateOption.innerHTML='';">Hide</a>]</td>
    </tr>
  <tr>
    <td height="10" colspan="4" align="left" id="templateOption">&nbsp;</td>
    </tr>
  <tr>
 <td height="10" colspan="4" align="left" valign="top" bgcolor="#CCCCCC"><b>Module Details : </b></td>
    </tr>
  <tr>
  <td height="10" align="left" bgcolor="#EEEEEE"><b>Module Name </b></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="moduleName" type="text"  onKeyUp="populateOtherFeilds()"  value="<?=$_REQUEST["moduleName"]?>" size="50"/></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><b>ProjectPrefix</b></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="projectPrefix" type="text" onkeyup="populateOtherFeilds()"  value="<?=$_REQUEST["projectPrefix"]?>" size="50"/></td>
  </tr>
  
  <tr>
    <td height="10" align="left" bgcolor="#EEEEEE"><b>Paginate Class  </b></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="paginateClass" type="text"  value="<?=$_REQUEST["paginateClass"]?>" size="50"/></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><b>Array Name </b></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="arrayName" type="text"  value="<?=$_REQUEST["arrayName"]?>" size="50"/></td>
  </tr>
  
  <tr>
    <td height="10" align="left" bgcolor="#EEEEEE"><b>Search Class Name </b></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="searchClassName" type="text"  value="<?=$_REQUEST["searchClassName"]?>" size="50"/></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><b>ClassName</b></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="className" type="text"  value="<?=$_REQUEST["className"]?>" size="50"/></td>
  </tr>
 
  <tr>
    <td height="10" align="left" bgcolor="#EEEEEE"><b>Form Name List </b></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="formName" type="text"  value="<?=$_REQUEST["formName"]?>" size="50"/></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><b>Form Name Search</b> </td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="formNameSearch" type="text"  value="<?=$_REQUEST["formNameSearch"]?>" size="50"/></td>
  </tr>
  
  <tr>
    <td height="10" align="left" bgcolor="#EEEEEE"><b>Form NameAdd</b></td>
    <td height="10" align="left" bgcolor="#EEEEEE"><input name="formNameAdd" type="text"  value="<?=$_REQUEST["formNameAdd"]?>" size="50"/></td>
    <td height="10" align="left" bgcolor="#EEEEEE">&nbsp;</td>
    <td height="10" align="left" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  
 
  <tr>
    <td height="10" align="left">&nbsp;</td>
    <td height="10" align="left">&nbsp;</td>
    <td height="10" align="left">&nbsp;</td>
    <td height="10" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" align="left"><b>Class Name </b></td>
    <td height="10" align="left"><input name="classSaveName" type="text" value="<?=$_REQUEST["classSaveName"]?>" size="40" />
  &nbsp;</td>
    <td height="10" align="left"><input type="submit" name="Submit23" value="Make Class"  onclick="return submitForm('Make Class')"/></td>
    <td height="10" align="left"><input type="submit" name="Submit23" value="Create Connect,Base and Paginate Classes "  onclick="return submitForm('Make Basic Classses')"/></td>
  </tr>
  <tr>
    <td height="10" align="left"><b>Add Template </b></td>
    <td height="10" align="left"><input name="addTemplateName" type="text" value="<?=$_REQUEST["addTemplateName"]?>" size="40">  &nbsp;</td>
    <td height="10" align="left"><input type="submit" name="Submit22" value="Make Add Template"  onclick="return  submitForm('Make Add Template')"/></td>
    <td height="10" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" align="left"><b>List Template</b> </td>
    <td height="10" align="left"><input name="listTemplateName" type="text" value="<?=$_REQUEST["listTemplateName"]?>" size="40" />
  &nbsp;</td>
    <td align="left"><input type="submit" name="Submit222" value="Make List Template"  onclick="return  submitForm('Make List Template')"/></td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" align="left">&nbsp;</td>
    <td height="10" align="left"><label></label></td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" align="left">&nbsp;</td>
    <td height="10" align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="20" colspan="4"  align="center" >&nbsp;</td>
  </tr>
</table>
<?
//echo $_REQUEST["Submit"];
	

if($_REQUEST["Submit"] == "Generate Class"){
if($preferredclassname !="") $genClassPath = "classes/$preferredclassname";
	$fp = fopen($genClassPath,"w");
	fwrite($fp,$_REQUEST["holdtext"]);
	fclose($fp);
echo "class created $genClassPath ";	
}
//include_once 'init.inc.php';
if($_REQUEST["Submit"] == "Make Class"){
	$classPrefix 	= "cls";
	$projectPrefic	= $_REQUEST["projectorefix"];
	$classCode = "";
	
	mysql_select_db($_REQUEST["dbname"]);
	$query = "SELECT * FROM " . $_REQUEST["tablename"];
	if(trim($_REQUEST["tableAlias"])=="")
		$table = ucwords(str_replace($projectPrefic,"",$_REQUEST["tablename"]));
	else
		$table	 = $_REQUEST["tableAlias"];
	$className =$classPrefix  .  $projectPrefic. $table;
	$baseClass = $classPrefix  .  $projectPrefic . "Base";
	$prefix	   =  $classPrefix  .  $projectPrefic;
	//$classCode .=  $className . "\r\n";
	$result = mysql_query($query);
	$a=0;
	
	$fieldArray = array();
	while ($a < mysql_num_fields($result)) {
   
   $meta = mysql_fetch_field($result, $a);
   if (!$meta) {
      //$classCode .=  "No information available<br />\n";
   }
  	 array_push($fieldArray,$meta->name);
	
   $a++;
}
	//STARTING CLASS......................
	$classCode .=  "class $className extends $baseClass  { \r\n";
	foreach($fieldArray as $row){
	$classCode .=  "\t var $" . $row . ";\r\n";
	}
	$classCode .=  "\n\r\t var $" . $fieldArray[0] . "s;\r\n";
	//CONSTRUCTOR ......................
	$classCode .=  "function $className($"."connect, $"."includePath) {\r\n";
		$classCode .=  "\t $"."this->".$prefix."Base($" ."connect, $"."includePath, \"$className\");\r\n \r\n	";
	foreach($fieldArray as $row){
	$classCode .=  "\t $" ."this->". $row . "=\"\";\r\n";
	}	
	$classCode .=  "}\r\n";
	//POSTVARS ......................
	$classCode .=  "function setPostVars() {\r\n";
		$classCode .=  "\t parent::setPostVars();\r\n \r\n	";
	foreach($fieldArray as $row){
		$classCode .=  "\t if (isset($"."_POST['$className"."_". "$row']))			$"."this->$row				= trim($"."_POST['$className"."_". "$row']);\r\n"; 
	}
	$classCode .=  "}\r\n";
	//GETVARS ......................
	$classCode .=  "function setGetVars() {\r\n";
		$classCode .=  "\tparent::setGetVars();\r\n \r\n	";
	foreach($fieldArray as $row){
		$classCode .=  "\t if (isset($"."_GET['$className"."_". "$row']))			$"."this->$row			= trim($"."_GET['$className"."_". "$row']);\r\n"; 
	}
	$classCode .=  "}\r\n";
	//SAVE ......................
	$classCode .=  "//SAVE ......................\r\n";
	$classCode .= "function save"."$table($". $fieldArray[0] ."){ \r\n"; 
		$classCode .=  "\t if (!$"."this->validate"."$table()) return 0;\r\n";
		$classCode .= "\t if (trim($". $fieldArray[0] .") ==  \"\") {\r\n";
		$classCode .=  "//INSERT ......................\r\n";
		$classCode .= "\t\t$"."query	= \" INSERT INTO  ".$_REQUEST["tablename"] ."  
							
							(
							";
		$count = 0;
		foreach($fieldArray as $row){	
			if($count>0){
				if($count>1) $classCode .= ",\n\r\t\t\t\t\t";
				$classCode .= " \t\t $row ";
			}
			$count++;
		}
		
		$count = 0;	
		$classCode .=" \n\r\t\t)\r\n";
		$count = 0;
		$classCode .="\t\tVALUES\n\r\t\t(\n\r";
		foreach($fieldArray as $row){	
			if($count>0){
				if($count>1) $classCode .= ",\n\r";
				$classCode .= "\t\t\t\t\t \\\"$" ."this->$row\\\"";
			}
			$count++;
		}
		
		$count = 0;	
		$classCode .=" \n\r\t\t)\";\r\n";	
		$classCode .="\n\r\t\t$"."dbQry	= new dbQuery($"."query, $"."this->connect->connId);
			$"."this->" . $fieldArray[0] . "		= $"."dbQry->lastInsertId();
			$"."_SESSION[\"".strtoupper($projectPrefic)  ."MESSAGE\"]	= \"$table added successfully\";\n\r";		
		
		$classCode .=  "\t}else{\r\n";
		
		//UPDATE
		$classCode .=  "//UPDATE ......................\r\n";
		$classCode .= "\t\t$"."query	= \" UPDATE
							\t ".$_REQUEST["tablename"] ."  \n\r
							SET\n\r
							";
		
		foreach($fieldArray as $row){	
			if($count>0){
				if($count>1) $classCode .= ",\n\r\t\t\t";
				$classCode .= " \t\t $row = " . " \\\"$" ."this->$row\\\""; 
			}
			$count++;
		}	
		
		$classCode .= "\n\r\t\t\t WHERE ". $fieldArray[0] ."=" . " \\\"$" ."this->". $fieldArray[0] ."\\\""; 
		$classCode .=  " \";\n\r";
		$classCode .="\n\r\t\t$"."dbQry	= new dbQuery($"."query, $"."this->connect->connId);
		$"."this->" . $fieldArray[0]  ."	= $". $fieldArray[0] .";
		$"."_SESSION[\"".strtoupper($projectPrefic)  ."MESSAGE\"]	= \"$table added successfully\";\n\r";	
			$classCode .=  " \r\n\t}\r\n";
	$classCode .=  "}\r\n";
	//Validate ......................
	//if(count($arrValidateFeilds)>0){
		
		$classCode .=  "//Validate ......................\r\n";
		$classCode .= "function validate"."$table(){ \r\n"; 
		$classCode .= "\t\t$"."_SESSION[\"".strtoupper($projectPrefic)  ."MESSAGE\"]	=\"\";\r\n";
		foreach($fieldArray as $row){	
			
			if(in_array($row,$arrValidateFeilds)){
				$classCode .= "\t\tif (trim($"."this->".$row.") == \"\") {\n\r"; 
				$classCode .= "\t\t\t$"."_SESSION[\"".strtoupper($projectPrefic)  ."MESSAGE\"]	.= \"$row cannot be null\";\n\r";	
				$classCode .=  "\t\t}\r\n";
			}
			
		}	
		$classCode .= "\t\tif (strlen($"."_SESSION[\"".strtoupper($projectPrefic)  ."MESSAGE\"]) > 0) {\n\r";
		$classCode .= "\t\t\treturn 0;\n\r";
		$classCode .=  "\t\t}else{\r\n";
			$classCode .= "\t\t\treturn 1;\n\r";
		$classCode .=  "\t\t}\r\n";
		$classCode .=  "}\r\n";
	//}
	
	//RETRIEVE
		$classCode .=  "//RETRIEVE ......................\r\n";
	$classCode .= "function retrieve"."$table($". $fieldArray[0] ."){ \r\n"; 
	$classCode .= "\t if (trim($". $fieldArray[0] .") ==  \"\") return 0;\r\n";
	$classCode .= "\t\t $"."query	= \" SELECT 
						* 
					FROM 
						" . $_REQUEST["tablename"] . "
					WHERE 
						$"."this->".$fieldArray[0]."		= $". $fieldArray[0] ." \";\n\r";	
	$classCode .=  "\t\t$". "dbQry	= new dbQuery($"."query, $"."this->connect->connId);	
		$"."this->retrieve".$table."Row($"."dbQry);
		return $"."dbQry->numRows();\n\r";
	$classCode .=  "}\r\n";
	//Retrieve Row
	$classCode .=  "//Retrieve Row ......................\r\n";
	$classCode .= "function retrieve"."$table"."Row($". "dbQry){ \r\n"; 
	$classCode .= "\t\tif(!$"."dbQry->numRows()) return 0;\r\n";
		$classCode .= "\t\t$"."row\t= $"."dbQry->getArray();\r\n";
		foreach($fieldArray as $row){	
			$classCode .= " \t\t $"."$row = " . " $" ."this->$row;\n\r"; 
			
			
		}	
			$classCode .= "\t\treturn 1;\n\r";
	$classCode .=  "}\r\n";
	//Retrieve Array
	$classCode .=  "//Retrieve Array ......................\r\n";
	$classCode .= "function retrieve"."$table"."Array(){ \r\n"; 
	$classCode .= "$"."query	= \" SELECT	
						*  
					FROM	
						".$_REQUEST["tablename"] ." WHERE 1=1 \";\r\n";
	foreach($fieldArray as $row){	
			$classCode .= "\t\t if (trim($"."this->". $row .") ==  \"\") \n\r";
			$classCode .= "\t\t\t$"."query .=\"$"."$row = \\\"$"."this->". $row ."\\\" \"; \n\r";
			
		}	
	$classCode .=  "\t\t\$"."query	.= \" ORDER BY $"."this->sortColumn $"."this->sortDirection\";\n\r";	
	$classCode .=  "\t\t\$"."this->".$prefix."Paginate = new ".$prefix."Paginate($"."this->connect->connId, $"."query, $"."this->pageSize, $"."this->rangeVal, $"."this->pageIndex, $"."this->includePath, 0);\n\r\n\r";
	$classCode .=  "\t\treturn $"."this->retrieve". $table. "RowArray($"."this->".$prefix."Paginate);\n\r";
	$classCode .=  "}\r\n";
	//retrieve array row
	$classCode .=  "//retrieve array row ......................\r\n";
	$classCode .= "function retrieve"."$table"."RowArray($"."dbQry){ \r\n"; 
	$classCode .= "\t\t$"."arr". $table."	= array();\r\n";
	$classCode .= "\t\twhile($"."row		= $"."dbQry->getArray()) {\r\n";
	$classCode .= "\t\t\t$"."arr". $table."[$"."row[\"". $fieldArray[0]. "\"]]	= array(\n\r";
	
	$count =0;
	foreach($fieldArray as $row){	
		if($count<>0)$classCode .=",";
		$classCode .= "\n\r\t\t\t\t\t\t\t\t\t\t\"" .$row ."\"			=> $"."row[\"" .$row ."\"]";
		$count++;
	}
	$classCode .= "\n\r\t\t\t\t\t\t\t\t\t\t);\n\r";
	
	$classCode .=  "\t\t}\r\n";
	$classCode .= "\t\t return $"."arr".$table.";\n\r";
	$classCode .=  "}\r\n";
	//delete
	$classCode .=  "//delete ......................\r\n";
	$classCode .= "function delete"."$table($". $fieldArray[0] ."s){ \r\n"; 
	$classCode .= "\t\t$"."query	= \" DELETE FROM 
						".$_REQUEST["tablename"]."
					WHERE 
						".$fieldArray[0] ." IN ($".$fieldArray[0] ."s)\";\r\n";
	$classCode .= "\t\t$"."dbQry	= new dbQuery($"."query, $"."this->connect->connId);\r\n";		
	$classCode .= "\t\t$"."_SESSION[\"".strtoupper($projectPrefic)  ."MESSAGE\"]	.= \"".$table."(s) deleted successfully\";\r\n";
	$classCode .= "\t\treturn 1;\r\n";
	$classCode .=  "}\r\n";
	//post user
	$classCode .=  "//post user ......................\r\n";
	$classCode .=  "function post" .$table . "() {	\r\n";
	$classCode .=  "\t\t$"."this->setPostVars();\r\n";
	$classCode .=  "\t\t$"."this->setGetVars();\r\n";
	$classCode .=  "\t\tif ($"."this->action == \"SAVE\") {\r\n";
	$classCode .=  "\t\t\tif ($"."this->save".$table."($"."this->".$fieldArray[0] .")) {\r\n";
	$classCode .=  "\t\t\t\t\header(\"location:\".$"."this->includePath.stripslashes($"."this->returnUrl));\r\n";
	$classCode .=  "\t\t\t\t\exit();\r\n";
	$classCode .=  "\t\t\t}\r\n";
	$classCode .=  "\t\t}\r\n";
	$classCode .=  "\t\telse if ($"."this->action == \"DELETE\") {\r\n";
	$classCode .=  "\t\t\t$"."this->delete".$table."($"."this->".$fieldArray[0] ."s);\r\n";
	$classCode .=  "\t\t\theader(\"location:\".$"."this->includePath.stripslashes($"."this->returnUrl));\r\n";
	$classCode .=  "\t\t\texit();\r\n";
	$classCode .=  "\t\t}\r\n";
	$classCode .=  "\t\t return $"."this->retrieve".$table."($"."this->".$fieldArray[0] .");\r\n";
	$classCode .=  "}\r\n";
	$classCode .=  "}\r\n";
	echo "<BR> <b> $className class: </b>";
	echo "<hr>";
	?>
	<SPAN ID="copytext" >
	<?
	$classCode = "<?php \n\r" . $classCode . "\n\r?>";
	$str = highlight_string($classCode,true); 
	echo $str; 
	?>
	</SPAN>
	
	<?php
	
}
function retrieveDbOptions($db){
	global $con;
	$db_list = mysql_list_dbs($con);
	$i = 0;
	$cnt = mysql_num_rows($db_list);
	$options = "";
	while ($i < $cnt) {
	   $dbname = mysql_db_name($db_list, $i);
	   $selected = 0;
		if($db == $dbname) $selected= "selected";
		$options .= "<option value='$dbname' $selected>$dbname</option>";
		$i++;
	}
	return $options;
}
function retrieveTableOptions($db,$tbl){
	
	$result = mysql_list_tables("$db");
	$options  = "";
	for ($i = 0; $i < mysql_num_rows($result); $i++) {
		$table =  mysql_tablename($result, $i);
		$selected = 0;
		if($tbl == $table) $selected= "selected";
		$options .= "<option value='$table' $selected>$table</option>";
	}
	return $options;

}
function retrieveFeildOptions($tablename){
	
	$result = mysql_query("select * from $tablename");
	
	$options  = "";
	$a = 0;
	while ($a < mysql_num_fields($result)) {
    	$meta = mysql_fetch_field($result, $a);
		if (!$meta) {
		 }
		$selected = 0;
		if($fld == $meta->name) $selected= "selected";
		$options .= "<option value='$meta->name' $selected>$meta->name</option>";
		 $a++;
	}
	return $options;

}

?>




<TEXTAREA ID="holdtext"  name="holdtext" style="display:none;">
<?php echo trim($classCode); ?>
</TEXTAREA>
  </form>
  <TEXTAREA ID="holddata" style="display:none;">
</TEXTAREA>
</body>
</html>
<SCRIPT LANGUAGE="JavaScript">

function ClipBoard()
{
holddata.innerText = copytext.innerText;
Copied = holddata.createTextRange();
Copied.execCommand("Copy");
alert("Paste your code to an editor");
}

</SCRIPT>
