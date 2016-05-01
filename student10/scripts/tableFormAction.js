// JavaScript Document
/**
  tableFormAction.js
  
  script for implementing database table
*/

/**
   * Purpose: if newValue equals delete display a confirm box and assign values
   *          to hidden input then sumbit form: else use defualt values and submit 
   * Pre:    none
   * Post:   form was submitted 
   *
   * @param   var   newValue identifies the type of action used in database.
   * @param   var   tmpID identifies the $id slected from MySQLi table.
   *
   * note: if newValue equals delete display a confirm box and assign values
   *       to hidden input then sumbit form else use defualt values and submit 
   */
	function submitform(newValue, tmpID)
    {
	 if(newValue == "delete")
	  {
	    if(confirm("Delete record " + tmpID) != true)
		  document.getElementById('submit_choice').value = "view";
		else
		  document.getElementById('submit_choice').value = newValue;
	  }
	  else
	    document.getElementById('submit_choice').value = newValue;
    
	    document.getElementById('ID').value = tmpID;
        document.getElementById('basic1').submit();
	}
	
	function memberOnly()
	{
	  alert("Must be signed in to add a entry.");	
	}
	
	function viewExtra(e)
	{
	  for(; e.nodeName != "TR"; e = e.parentNode);
	  e = e.nextSibling;
	  //$(''.viewRow').css("", "table-row")	
	  $(e).css("display","table-row");
	}
	
	/* submit form if location attributes are selected*/
	function chooseLocation(loc)
	{
	  document.getElementById('loc').value = loc;
      document.getElementById('basic1').submit();
	}
	
	/* submit form if search box is selected*/
	function chooseBySearch()
	{
      document.getElementById('basic1').submit();
	}