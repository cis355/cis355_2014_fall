var connection = new XMLHttpRequest();

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
	var food = encodeURIComponent(document.getElementById("input").value);
	
	connection.open("GET", "foodstore.php?food="+food, true);
	connection.onreadystatechange = handleServerResponse;
	//connection.setRequestHeader("Content-Type", "text/xml");
	connection.send();
	
	
	/*var doc = connection.resposeXML;
	var foodItems = doc.childNodes[0];
	
	var item = foodItems.children[0];
	
	var itemCategorys = item.getElementsByTagName("item");
	
	document.getElementById("output").innerHTML = itemCategorys[0].textContent.toString();*/
}

function handleServerResponse () {

	if ( connection.readyState==4 )
	{
		if ( connection.status==200) 
		{
			xmlResponse = connection.responseXML;
			xmlDocumentElement = xmlResponse.documentElement;
			
			message = xmlDocumentElement.firstChild.textContent;
			document.getElementById("output").innerHTML = '<span style="color:blue">' + message + '</span>';
			
			var item2 = xmlDocumentElement.getElementsByTagName('item')[0];
			document.getElementById("options").innerHTML = '<span style="color:blue">' + item2.textContent + '</span>';
		}
	}
}