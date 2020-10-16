jQuery(function() {
    jQuery(document).ready(function() {
        jQuery("#newlink").click(function() {
            var id = jQuery(this).attr("class");
           // alert(id);

            jQuery.ajax({
            type: "POST",
            url: Drupal.url('urlcounter/'),
            data: {
                'id': id
              }
          });
        //return false;

        });
    });
});
