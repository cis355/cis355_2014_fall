var xmlHttp = createXmlHttpRequestObject();
var message;

function createXmlHttpRequestObject() {
	var xmlHttp;

	if (window.ActiveXObject){
		try{
			xmlHttp = new ActiveXObject("Microsofot.XMLHTTP");
		} catch (e) {
			xmlHttp = false;
		}
	}else{
		try{
			xmlHttp = new XMLHttpRequest();
		} catch (e) {
			xmlHttp = false;
		}
	}

	if (!xmlHttp) {
		alert("Could not create XML Object");
	} else {
		return xmlHttp;
	}
}

function process() {
		food = encodeURIComponent(document.getElementById("userInput").value);
		xmlHttp.open("GET", "foodstore.php?food="+food, true);
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send();

}

function handleServerResponse() {
	if ( xmlHttp.readyState==4 ) {
		if ( xmlHttp.status==200 ) {
		  xmlResponse = xmlHttp.responseXML;
		  var x = xmlResponse.documentElement.childNodes;

      message = "";
      for (i = 0; i < x.length; i++) {
        message += x[i].childNodes[0].nodeValue + "<br>";
      }
      
      document.getElementById("underInput").innerHTML = '<span style="color:blue">' + message + '</span>';
    }
	}
}