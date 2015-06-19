<?php
$arrSearchFeilds = explode(",",$_REQUEST["searchFeilds"]);
$arrAddFeilds	 = explode(",",$_REQUEST["addFeilds"]);
?>

<table width="100%"  border="0" cellspacing="2" cellpadding="4">
 <tr bgcolor="#CCCCCC"><td colspan="3"><strong>Add/Edit Template Creation</strong></td>
 </tr>
<?
if($_REQUEST["addFeilds"]<>""){
	foreach($arrAddFeilds as $val){
	?>
		
	  <tr>
		<td width="13%">&nbsp;</td>
		<td width="29%">&nbsp;<?=innerCapsToReadableText($val)?></td>
		<td width="58%">
		
		<select name="option_<?=$val?>" size="1">
		  <option value="select">select</option>
		  <option value="checkbox">Check Box</option>
		  <option value="radio">Radio Button</option>
		  <option value="text">Text Feild</option>
		  <option value="textarea">Text Area</option>
		   <option value="file">file</option>
			<option value="password">password</option>
		 	<option value="hidden">hidden</option> 
		
		</select>
		</td>
	  </tr>
	 
	
	
	<?
	}// ."<BR>";
}
?>
 <tr bgcolor="#CCCCCC"><td colspan="3"><strong>Search Template Creation</strong></td>
 </tr>
<?
if($_REQUEST["searchFeilds"]<>""){
	foreach($arrSearchFeilds as $val){
	?>
		
	  <tr>
		<td width="13%">&nbsp;</td>
		<td width="29%">&nbsp;<?=innerCapsToReadableText($val)?></td>
		<td width="58%">
		
		<select name="searchOption_<?=$val?>" size="1">
		  <option value="select">select</option>
		  <option value="checkbox">Check Box</option>
		  <option value="radio">Radio Button</option>
		  <option value="text">Text Feild</option>
		  <option value="textarea">Text Area</option>
		   <option value="file">file</option>
			<option value="password">password</option>
		  	<option value="hidden">hidden</option> 
		
		</select>
		</td>
	  </tr>
	 
	
	
	<?
	}// ."<BR>";
}	
?>
</table>
<?
function innerCapsToReadableText($text) {
	  $text = ereg_replace("([A-Z]) ", "\\1",ucwords(strtolower(ereg_replace("[A-Z]"," \\0",$text))));
	  return ereg_replace("([A-Za-z])([0-9])", "\\1 \\2", $text);
}
?>