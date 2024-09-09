(function (Drupal, $, once) {
  'use strict';
  Drupal.behaviors.alertOnClick = {
    attach: function (context) {
      // Use 'once' to ensure the behavior only applies once per element.
      once('alertOnClick', '#add-to-cart', context).forEach(function (element) {
        // Use jQuery to add a click event handler to the button.
        $(element).click(function () {
          $('#message').toggle();
        }); 
      });
    }
  }
})(Drupal, jQuery, once);
