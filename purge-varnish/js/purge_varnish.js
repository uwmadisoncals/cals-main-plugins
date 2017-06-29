jQuery( document ).ready(function() {
  if(jQuery('#post_expiration').length) {
    jQuery('#post_expiration').css('display', 'block');
  }
  
  jQuery(".ck_custom_url").change(function() {
    if(this.checked) {
       jQuery("div.custom_url").removeClass('hide_custom_url');
       jQuery("div.custom_url").addClass('show_custom_url');
    }
    else {
        jQuery("div.custom_url").removeClass('show_custom_url');
        jQuery("div.custom_url").addClass('hide_custom_url');
    }
});

  
});

function open_menu(evt, container_id) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the link that opened the tab
  document.getElementById(container_id).style.display = "block";
  evt.currentTarget.className += " active";
}