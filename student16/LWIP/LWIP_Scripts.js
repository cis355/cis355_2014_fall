/**
 * Purpose: This file will;
 *          perform the client side redirections, ajax and other javascript function
 *
 * Author:  Kevin Stachowski
 * Date:    10/02/2014
 * Update:  10/11/2014
 * Notes:   This file is included in the LWIP_Functions.php header function
 * 
 */

  /**
 * Purpose: This will navigate to the showtables.phph page
 * Pre:     none
 * Post:    page has been navigated
 */
function goToShowTables2(dataArray)
{
    var url = "http://www.cis355.com/student16/LWIP/LWIP_ShowTables.php?id=" + dataArray[0] + "&email=" + dataArray[1];
    window.location = url;
}

  /**
 * Purpose: This will navigate to the showtables.phph page
 * Pre:     none
 * Post:    page has been navigated
 */
function goToShowTables()
{
    var url = "http://www.cis355.com/student16/LWIP/LWIP_ShowTables.php";
    window.location = url;
}

 /**
 * Purpose: This will prompt the user to confirm a delete
 * Pre:     none
 * Post:    redirect or continue to delete
 */
function okPopUp()
{
	/* display ok/cancel */
	var r = confirm("Are you sure you want to delete? This cannot be undone!");
	/* if cancel is clicked stop and redirect to ShowTables */
	if (r != true) {
		goToShowTables();
	} 
}

  /**
 * Purpose: This will navigate to the login.php page
 * Pre:     none
 * Post:    page has been navigated
 */
function goToLogin()
{
	window.location = "http://www.cis355.com/student16/LWIP/LWIP_Login.php";
}

  /**
 * Purpose: This will navigate to the login.php page
 * Pre:     none
 * Post:    page has been navigated
 */
function goToNewLogin()
{
	window.location = "http://www.cis355.com/student14/login.php";
}

  /**
 * Purpose: This will navigate to the login.php page
 * Pre:     none
 * Post:    page has been navigated
 */
function logout()
{
	window.location = "http://www.cis355.com/student14/logout.php";
}

  /**
 * Purpose: This will do an ajax call to check if a users login is valid.
 * Pre:     credentails have been entered
 * Post:    success or fail message is returned.
 */
function checkLogin() 
{
  var email = document.getElementById("email").value;
  var password = document.getElementById("password").value;
  
  //if either of the credentials are emtpy do nothing.
  if (email.length === 0 || password.length === 0) 
  {
    alert("Email or password has been left blank!");
    return;
  }
  
  var xmlhttp = new XMLHttpRequest();
  
  xmlhttp.onreadystatechange=function() 
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      if(xmlhttp.responseText.indexOf("success") == 0)
      {
          var data = xmlhttp.responseText.replace("success:","");
          var dataArray = data.split(",");
          goToShowTables2(dataArray);
      }
      else
          alert("serviceResponce: "+xmlhttp.responseText);
    }
  }
  xmlhttp.open("GET","phpWebservice.php?service=login&email="+email+"&password="+password,true);
  xmlhttp.send();
}

  /**
 * Purpose: This will do an ajax call to check if a users login is valid.
 * Pre:     credentails have been entered
 * Post:    success or fail message is returned.
 */
function createLogin() 
{
  var email = document.getElementById("emailC").value;
  var password = document.getElementById("passwordC").value;
  var location = document.getElementById("locationC").value;
  
  //if either of the credentials are emtpy do nothing.
  if (email.length === 0 || password.length === 0) 
  {
    //document.getElementById("errorMsg").innerHTML="Email or password has been left blank!";
    alert("Email or password has been left blank!");
    return;
  }
  
  var xmlhttp = new XMLHttpRequest();
  
  xmlhttp.onreadystatechange=function() 
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      if(xmlhttp.responseText.indexOf("success") == 0)
      {
          var data = xmlhttp.responseText.replace("success:","");
          var dataArray = data.split(",");
          goToShowTables2(dataArray);
      }
      else
          alert("serviceResponce: "+xmlhttp.responseText);
    }
  }
  xmlhttp.open("GET","phpWebservice.php?service=create&email="+email+"&password="+password+"&location="+location,true);
  xmlhttp.send();
}

  /**
 * Purpose: This will delay a function callback for a number of milliseconds.
 * Pre:     none
 * Post:    function has a delayed start
 */
function sleep(millis, callback) 
{
    setTimeout(function()
    { callback(); }
    , millis);
}

 /**
 * Purpose: This will disable all inputs on the page
 * Pre:     none
 * Post:    all input elements have been disabled.
 */
function disableInput()
{
	var inputs = document.getElementsByTagName("INPUT");
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].disabled = true;
		inputs[i].style.backgroundColor = '#D8D8D8';
	}
	
	var inputs = document.getElementsByTagName("SELECT");
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].disabled = true;
		inputs[i].style.backgroundColor = '#D8D8D8';
	}
	
	document.getElementById("dashboard").disabled = false;
}