var xmlHttp = createXmlHttpRequestObject();

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
		//alert(food);
		xmlHttp.open("GET", "kndeary.php?food="+food, true);
		xmlHttp.onreadystatechange = handleServerResponse;
		xmlHttp.send();

}

function handleServerResponse () {

	if ( xmlHttp.readyState==4 )
		if ( xmlHttp.status==200) {
		xmlResponse = xmlHttp.responseXML;
		xmlDocumentElement = xmlResponse.documentElement;
		message = xmlDocumentElement.firstChild.textContent;
		document.getElementById("underInput").innerHTML = '<span style="color:blue">' + message + '</span>';
	}
}

// source: http://tzachsolomon.blogspot.com/2013/02/ajax-first-example-based-on-new-boston.html