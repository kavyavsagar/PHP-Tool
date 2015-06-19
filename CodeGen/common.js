function  valid_xmlhttp() {
	var res = 0;
	try {
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP")
		} 
	catch (e) {
		try {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
			}
		catch (E) {
			try {
				xmlhttp=new XMLHttpRequest()
				} 
			catch (ee) {
				res = 1;
				//alert("You must have Microsofts XML parsers available")
				}
		}
	}
	return res;
}


/* The following function creates an XMLHttpRequest object... */
function callRequestObject(PostString,PHPpage,method){
	//alert(PHPpage);
valid_xmlhttp();	
if (window.XMLHttpRequest) { 
		http = new XMLHttpRequest(); 
		
}else if(window.ActiveXObject)
{	http = new ActiveXObject("Microsoft.XMLHTTP"); 
	
}


if(method=="post"){
		
		http.open('POST',PHPpage,false);
		http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		http.setRequestHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
		http.setRequestHeader('Cache-Control', 'public');
		http.send(PostString);
		
}else if(method=="get"){  
		//alert(PHPpage);
		http.open('get',PHPpage,false);
		http.setRequestHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
		http.setRequestHeader('Cache-Control', 'public');
		http.send(null);
	  }	
		return http.responseText;

}
//end Of function 



function GetPostString(frm)
{
form =frm;
str="";
for (i=0;i<form.elements.length;i++){
		name	= form.elements[i].name;
		value	= form.elements[i].value;
		str	+=name+'='+value;
		if (i!=(form.elements.length-1)){
			str	+='&';
		}
}//end of for loop
return str;
}//end of function.

