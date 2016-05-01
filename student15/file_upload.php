<html>
<body>
  <form method="post" enctype="multipart/form-data">
    <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
    <tr>
      <td>please select a file</td>
    </tr>
    <tr>
      <td>
        <input type="hidden" name="MAX_FILE_SIZE" value="16000000">
        <input name="userfile" type="file" id="userfile"> 
      </td>
      <td width="80">
        <input name="upload" type="submit" class="box" id="upload" value=" Upload ">
      </td>
    </tr>
    </table>
  </form>
  <br>
  <a href="http://cis355.com/student15/file_download.php">Download</a><br>
  <a href="http://cis355.com/student06/etc/tables.php">Tables Utility</a>
</body>
</html>

<!-- from: http://codereview.stackexchange.com/questions/27796/php-upload-to-database-->
<?php
  ini_set('file-uploads',true);   // Set php.ini setting to allow file uploads
  error_reporting(e_all);         // Displays errors instead of blank page
  
  // Checks that file was uploaded by checking if submit button (upload) was clicked
  // and the file exists in $_FILES
  if(isset($_POST['upload']) && $_FILES['userfile']['size']>0) {
    $fileName = $_FILES['userfile']['name'];      // The file name on the user machine
    $tmpName  = $_FILES['userfile']['tmp_name'];  // Computer generated name by client
    $fileSize = $_FILES['userfile']['size'];      // The size of the file in bytes
    $fileType = $_FILES['userfile']['type'];      // MIME type of the file (not working)
    
    // IGNORE - Deprecated in PHP 5.0
    $fileType=(get_magic_quotes_gpc()==0 ? mysql_real_escape_string(
    $_FILES['userfile']['type']) : mysql_real_escape_string(
    stripslashes ($_FILES['userfile'])));
    // END IGNORE
    
    $fp      = fopen($tmpName, 'r');            // Opens file that was passed, read-only
    $content = fread($fp, filesize($tmpName));  // Reading whole file into variable
    $content = addslashes($content);            // Add backslashes before special characters (',",\,NULL)
    echo "<br>filename: ".$fileName."<br>";     // Print out file name
    echo "filesize: ".$fileSize."<br>";         // Print out file size
    echo "filetype: ".$fileType."<br>";         // Print out file type
    fclose($fp);                                // Close file using file handle
    
    // IGNORE - Deprecated in PHP 5.0
    if(!get_magic_quotes_gpc()) {
      $fileName = addslashes($fileName);
    }
    // END IGNORE
    
    // Connect to database or print out error
    $con = mysql_connect('localhost', 'student', 'learn') or die(mysql_error());
    // Select and verify lesson01 database, returns true or false
    $db = mysql_select_db('lesson01', $con);
    
    // If lesson01 exists
    if($db) {
      // Build query with variable values
      $query = "INSERT INTO crm_upload (name, size, type, content ) ".
      "VALUES ('$fileName', '$fileSize', '$fileType', '$content')";
      // Execute query
      mysql_query($query) or die('Error, query failed');
      // Close connection
      mysql_close();
      // Echo that file has been uploaded
      echo "<br>File $fileName uploaded<br>";
    }
    else { 
      // Echo 
      echo "file upload failed: ".mysql_error(); 
    }
  } 
?>