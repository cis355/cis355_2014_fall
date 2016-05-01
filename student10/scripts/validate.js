// JavaScript Document

// validate.js

/**
   * Purpose: assign values to hidden input fields and submit form
   * Pre:    none
   * Post:   form was submitted 
   *
   * @param   var   newValue identifies the type of action used in database.
   */
  function submitNewRecord(newValue)
  {	
    cmd = "";
	choice = false;
  
    switch(newValue)
	{
	  case 0: // view data base
	    cmd = "view"; 
		choice = true;  
		break;
	  case 1:
		cmd = "add"; 
		choice = (validateForm()) ? true : false;
		break;
	  case 2:
		cmd = "update"; 
		choice = (validateForm()) ? true : false;
		break;    
	}
	   
	if(choice)
	{
	  document.getElementById('submit_choice').value = cmd;
      document.getElementById('itemFrm').submit();	 
	}
	else
	  $('.requiredFields').css("display", "block");
  }
  
  function resetForm() 
  {
    tags = document.getElementsByTagName('input');
	for(i = 0; i < tags.length; i++) 
	  if(tags[i].type)
	    if (tags[i].name != 'name')	
	      tags[i].value = '';
  }
  
  function testLength(field)
  {
    if (field.value == "" )
	{	
      field.style.backgroundColor = "yellow";
	  return false;
    }
	else
	{
      field.style.backgroundColor = "white";
	  return true;
    }
  }
  
  function testPattern(regex, field)
  {
    var x = field.value
	if(!x.match(regex))
	{
      field.style.backgroundColor = "yellow";
	  field.style.color = "red";
	  return false;
    } 
	else 
	{
      field.style.backgroundColor = "white";
	  field.style.color = "black";
	  return true;
    }
  }
  
  function validateForm()
  {
     var valid = true;
		
	 if (false == testLength(document.forms["itemFrm"]["brand"]))
	   valid = false;
	   
	 if (false == testLength(document.forms["itemFrm"]["model"]))
	   valid = false;

	 if (false == testLength(document.forms["itemFrm"]["year"]))
	   valid = false;
	 else
	 {
	   var testdept = testPattern(/^[0-9]+$/, document.forms["itemFrm"]["year"]);
       if (testdept == false) valid = false;
	 }

	 if (false == testLength(document.forms["itemFrm"]["cost"]))
	   valid = false;
	 else 
	 {
	   var testdept = testPattern(/^[0-9]+$/, document.forms["itemFrm"]["cost"]);
       if (testdept == false) valid = false;
	 }
	 
     return valid;
  }