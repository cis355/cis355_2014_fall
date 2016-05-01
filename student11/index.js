//this is a table object that basically manipulates the dom to keep the multiple tables
//update 
var table_object = {
      init: function(){
          $(".main_table_body").on('click','.delete_item',function(event){table_object.delete_item(event)});
          $(".update_table_body").on('click','.update_item',function(event){table_object.update_item(event)});
          table_object.add_item_colorbox();
      },
      delete_item: function(event){
            event.preventDefault();
           //making sure the user wants to delete a table
            if(confirm("Sure you want to delete record?") == false){
              return; 
            } 
            //this ensures that the button that was clicked maintains context 
            var $this= $(event.target);
            $.ajax({
                url:'index.php',
                type:"POST",
                datatype: 'json',
                data: {
                  "delete": "remove",
                  "jew_id": $this.parent().siblings("#id").val()
                },
                success: function(response){
                   $this.parent().parent().hide();
                   //getting the new updated table so 
                   //it can be replace the table on pane 2
                   $new_table = $(".main_table_body").html();
                   $(".update_table_body").empty().html($new_table);
                },
                complete: function(){
                  $(".update_table_body").find(".delete_item").replaceWith("<a class='update_item btn btn-primary disable'href = '#' role='button'>Update Item</a>");
                  $("#myTable,#myTable2").trigger('update');
                },
            });
      },
      update_item: function(event){
          event.preventDefault();
          //sets the context of the update button
          var $this = $(event.target);
          table_object.update_item_colorbox($this);
      },
      add_item_colorbox: function(){
        $(".add_item").colorbox({
            onLoad:function(){
                $('#cboxClose').remove();    
            },
        });//event handler
      },
      pre_populate_form_from_rows_data:function ($update_button_context){
          //grabbing the rows data 
          var $this = $update_button_context;
          var $id = $this.parent().siblings("#id").val();
          var $jewleryName = $this.parent().siblings(".jewlery_type").text();
          var $qual = $this.parent().siblings(".quality").text();
          var $pri = $this.parent().siblings(".price_paid").text();
          var $disco = $this.parent().siblings(".discount").text();
          var $date_reported = $this.parent().attr("value");
          var $user_id = $this.parent().siblings('.user_id').text();
          var $location_id = $this.parent().siblings('.location_id').text();
          $("#jewName").val($jewleryName);
          $("#qual").val($qual);
          $("#pri").val($pri);
          $("#discnt").val($disco);
          $("#location_id").val($location_id);
          $("#purchased_by").val($user_id);
          $('#date_reported').attr('value',$date_reported);

          table_object.update_form_event_handler($update_button_context);
       
      },
      update_item_colorbox: function($update_button_context){
          $(".update_item").colorbox({
            onLoad:function(){
              $('#cboxClose').remove();
            },
            onOpen: function(){
               table_object.pre_populate_form_from_rows_data($update_button_context);      
            },
            inline:true,
            href: ".form-group"
        });
      },//todo need to finish the update 
      update_form_event_handler: function($update_button_context){
          $(".form-group").off('click','#submit_button').on('click','#submit_button',function(event){
          event.preventDefault();
          //this refers to the row in which the update button was clicked
          //because that row brought us to this form 
              console.log("here");
          var $id = $update_button_context.parent().siblings("#id").val();
          $.ajax({
            url:'index.php',
            type:"GET",
            context:".update_item",
            datatype: 'json',
            data: {
              "update": "change",
              "jew_id": $id,
              "jewelry_name": $("#jewName").val(),
              "quality":$("#qual").val(),
              "price":$("#pri").val(),
              "discounts":$("#discnt").val(),
              "location_id":$("#location_id").val(),
              "user_id":$("#purchased_by").attr("value"),
              "date_reported":$("#date_reported").attr("value")
            },
            success: function(response){
              $update_button_context.parent().parent().replaceWith(response);
              //getting the new updated table so 
              // it can be replace the table on pane 1
              $new_table = $(".update_table_body").html();
              $(".main_table_body").empty().html($new_table);
              parent.jQuery.colorbox.close();
            },
            complete: function(){
              $(".main_table_body").find(".update_item").replaceWith("<a class='delete_item btn btn-primary disable'href = '#' role='button'>Remove Item</a>");
              $("#myTable, #myTable2").trigger('update');
            },
            error: function(error){
              console.log("this value is " + error.message);
            }
          });
         }); 
      }
   };


//when the document loads
$(document).ready(function(){
  //initializing the table object 
  table_object.init();
 $(".bio").colorbox({
     onLoad:function(){
        $('#cboxClose').remove();    
     },
  });//event handler
  //extending the table sorter object with a bootstrap them, this code is from a theme from a the 
  //tablesorter widget web page
  $.extend($.tablesorter.themes.bootstrap, {
    // these classes are added to the table. To see other table classes available,
    // look here: http://twitter.github.com/bootstrap/base-css.html#tables
    table      : 'table table-bordered',
    caption    : 'caption',
    header     : 'bootstrap-header', // give the header a gradient background
    footerRow  : '',
    footerCells: '',
    icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
    sortNone   : 'bootstrap-icon-unsorted',
    sortAsc    : 'icon-chevron-up glyphicon glyphicon-chevron-up',     // includes classes for Bootstrap v2 & v3
    sortDesc   : 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
    active     : '', // applied when column is sorted
    hover      : '', // use custom css here - bootstrap class may not override it
    filterRow  : '', // filter row class
    even       : '', // odd row zebra striping
    odd        : ''  // even row zebra striping
  });

  //initiates the table sorter, most of this code is from the table sorter 
  //website, it has been altered slightly
  $('.tablesorter').tablesorter(
  {
    // this will apply the bootstrap theme if "uitheme" widget is included
    // the widgetOptions.uitheme is no longer required to be set
    theme : "dropbox",

    widthFixed: true,

    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

    // widget code contained in the jquery.tablesorter.widgets.js file
    // use the zebra stripe widget if you plan on hiding any rows (filter widget)
    widgets : [ "uitheme", "zebra" ],

    widgetOptions : {
      // using the default zebra striping class name, so it actually isn't included in the theme variable above
      // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
      zebra : ["even","filter" ,"odd"],

      // // // reset filters button
      filter_reset : ".reset",

      // set the uitheme widget to use the bootstrap theme class names
      // this is no longer required, if theme is set
      uitheme : "dropbox"

    }
  });//if there are multiple pages then uncomment this
});//document.ready

