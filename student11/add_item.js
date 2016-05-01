$(document).ready(function(){
    $('form').on('submit',function(event){
      event.preventDefault();
    $.ajax({
      url: "backEnd.php",
      type: 'GET',
      contentType: 'application/json',
      dataType: 'json',
      data: {
        "jewelry_name": $("#jewelryName").val(),
        "quality":$("#quality").val(),
        "price":$("#price").val(),
        "discounts":$("#discounts").val(),
        "user_id":$("#user_id").val(),
        "location_id":$("#location").val(),
            },
      success: function(response){
        if($("#myTable>tbody>tr").length==0){
          $("#myTable>tbody,#myTable2>tbody").append(response);
          $(".no-data").remove();

        }else{
           $("#myTable>tbody>tr:last-child,#myTable2>tbody>tr:last-child").after(response);
        }
        parent.jQuery.colorbox.close();
      },
      complete: function(){
        $(".main_table_body").find(".update_item").remove();
        $(".update_table_body").find(".delete_item").remove();
        $("#myTable,#myTable2").trigger('update');
      },
      error: function(error){
        console.log("this value is " + error.message);
      }
    });//ajax
  });//event handler
});
