var callingURL = document.URL; 
var cgiString = callingURL.substring(callingURL.indexOf('?')+1,callingURL.length); 
var DELIMETER = '&'; 
var errorExist = 0 ;
var redirectTo = "";

if (cgiString.indexOf('#')!=-1){ 
    cgiString=cgiString.slice(0,cgiString.indexOf('#')); 
} 
var arrayParams=cgiString.split(DELIMETER); 
for (var i=0;i<arrayParams.length;i++){ 
	varName = arrayParams[i].substring(0,arrayParams[i].indexOf('=')) ;
	varValue = arrayParams[i].substring(arrayParams[i].indexOf('=')+1,arrayParams[i].length) ;
	if( errorExist == 0 && varName == "ValidationError" && varValue == 1 ) {
		errorExist = 1 ;
	}
	if( varName != "" && varName != "ValidationError" && varName != "wrtMsg" ) {
		if( varName.substr(0,4) == "err_" ) {
			var objID = varName ;
			document.getElementById( objID ).innerHTML = unescape(varValue) ;
		} else {
			var objID = varName ;
			document.getElementById( objID ).value = unescape( varValue.replace(/\+/g," ") ) ;
		}
	}
	if( varName == "redirect" ) { 
		redirectTo = varValue ;
	}
	if( varName == "wrtMsg" && varValue == 1) { 
		document.getElementById( "mensaje_error" ).innerHTML = "";
		if( redirectTo != "" )  {
			window.setTimeout("window.location.href ='"+unescape(redirectTo)+"'",0);
		}
	}
} 
