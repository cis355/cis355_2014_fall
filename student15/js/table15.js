jQuery.validator.addMethod(
  "money",
  function(value, element) {
    var isValidMoney = /^\d{0,4}(\.\d{0,2})?$/.test(value);
    return this.optional(element) || isValidMoney;
  },
  "Price format: 9999.99"
);

jQuery.validator.addMethod(
  "positiveInteger",
  function(value, element) {
    var isValidID = /^\d+$/.test(value);
    return this.optional(element) || isValidID;
  },
  "ID must be a positive integer."
);

$(document).ready(function() {
  $('#camera_entry').validate({
    rules: {
      user_id: {
        required: true,
        positiveInteger: true
      },
      location_id: {
        required: true,
        positiveInteger: true
      },
      item_name: {
        required: true
      },
      make: {
        required: true
      },
      model: {
        required: true
      },
      price: {
        required: true,
        money: true
      }
    }, //end rules
    messages: {
      user_id: {
        required: "Please enter a user ID."
      },
      location_id: {
        required: "Please select a location."
      },
      item_name: {
        required: "Please enter an item name."
      },
      make: {
        required: "Please enter the make of the camera."
      },
      model: {
        required: "Please enter the model number."
      },
      price: {
        required: "Please enter the item price."
      }
    }, // end messages
    submitHandler: function(form) {
      form.submit();
    }, // end submitHandler
    errorPlacement: function(error, element) {
      if ( element.is(":radio") || element.is(":checkbox")) {
        error.appendTo( element.parent());
      }
      else {
        error.insertAfter(element);
      }
    }, // end errorPlacement
    highlight: function (element) {
      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    }, // end highlight
    success: function (element) {
      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
    } // end success
  }); // end validate
}); // end ready