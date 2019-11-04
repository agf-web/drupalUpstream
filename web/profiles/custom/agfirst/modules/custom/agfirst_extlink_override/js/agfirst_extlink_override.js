(function ($, Drupal, drupalSettings) {
  Drupal.extlink.popupClickHandler = function (e) {
    // Stop the click event
    e.preventDefault();
    e.stopPropagation();

    var target = $(e.currentTarget);
    var newWin = target.attr('target') == '_blank' ? true : false;
    var url = target.attr('href');
    $( "#agfirst-link-dialog-block" ).dialog({
      resizable: false,
      height:350,
      width:450,
      modal: true,
      title: "Notice:",
      buttons: {
        "Okay": function() {
          if(newWin) {
            $( this ).dialog( "close" );
            open(url);
          } else {
            window.location.href = url;
          }
        },
        "Cancel": function() {
          $( this ).dialog( "close" );
        }
      }
    });
    return false;
  };
})(jQuery, Drupal, drupalSettings);
