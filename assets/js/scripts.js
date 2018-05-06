"use strict";

jQuery(document).ready(function ($) {
  var modal = document.getElementById('myModal');
  var btn = document.getElementsByClassName("myBtn");
  var span = document.getElementsByClassName("close")[0];
  var textColor = document.getElementById("text-color");
  var backgroundColor = document.getElementById("bg-color");

  if (! localStorage.getItem('enableAdmin')) {
    localStorage.setItem('enableAdmin', 0);
  }

  if (textColor) {
    textColor.setAttribute('value', '000000');
  }

  if (backgroundColor) {
    backgroundColor.setAttribute('value', 'f4f4f4')
  }

  if (localStorage.getItem('enableAdmin') == 1) {
    $('.toggle-admin-options').addClass('toggle-admin-options-selected');
    $('.admin-option').show();
    $(".popup").hide();
  }

  for (var i = 0; i < btn.length; i++) {
    btn[i].onclick = function () {
      modal.style.display = "flex";
      var x = $(this).data('x');
      var y = $(this).data('y');
      var brands = $(this).data('brands');
      var colors = $(this).data('colors');
      var textcolors = $(this).data('texts');
      var myOptions = '';

      for (var i = 0; i < brands.length; i++) {
        myOptions += '<option value="' + brands[i].replace('-', " ") + '" style="background-color: ' + colors[i] + '; color: ' + textcolors[i] + ';">' + brands[i].replace('-', " ") + '</option>';
      }

      $('#add-product').html('').append("\n <form method=\"post\">\n <input type=\"hidden\" name=\"x\" value=" + x + ">\n <input type=\"hidden\" name=\"y\" value=" + y + ">\n<h3> Insert New Disc</h3><div class='add-disc-top'> <input class='prod-name-title' type=\"text\" name=\"disc\" placeholder=\"Product name\" required>\n <div class='choose-brand'><select id=\"mySelect\" name=\"brand\"><option value=\"\">Select manufacturer</option>" + myOptions + "</select></div></div>\n <h4 class='flight-info-title'>Flight Information</h4><div class='flight-info-values'><input type=\"text\" name=\"speed\" placeholder=\"Speed value\" required>\n <input type=\"text\" name=\"glide\" placeholder=\"Glide value\" required>\n <input type=\"text\" name=\"turn\" placeholder=\"Turn value\" required>\n <input type=\"text\" name=\"fade\" placeholder=\"Fade value\" required></div>\n <h4 class='flight-info-title'>Graph & Product links</h4><div class='link-values'> <input type=\"text\" name=\"pic_link\" placeholder=\"Insert link to graph image\">\n <input type=\"text\" name=\"link\" placeholder=\"Insert product link\" required>\n</div>\n   <button class='add-new-disc-btn' type=\"submit\" name=\"disc-color\">Submit Disc Entry</button>\n <input type=\"hidden\" name=\"x\" value=" + x + ">\n </form>\n      ");
    };
  }

  span.onclick = function () {
    modal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  };

  /* Enable Admin Options Button */
  $(document).on('click', '.toggle-admin-options', function () {
    if (localStorage.getItem('enableAdmin') == 0) {
      localStorage.removeItem('enableAdmin');
      localStorage.setItem('enableAdmin', 1);
      $(this).toggleClass('toggle-admin-options-selected');
      $(".admin-option").toggle();
      $(".popup").hide();
    } else {
      localStorage.removeItem('enableAdmin');
      localStorage.setItem('enableAdmin', 0);
      $(this).toggleClass('toggle-admin-options-selected');
      $('.admin-option').toggle();
      $('.popup').hide();
    }

  });

  $(document).on('click', '.close-popup-btn', function () {
    $(".popup").hide();
  });

  /* Show Editing Options */

  $(this).on('click', ".show-form", function (e) {
    e.stopPropagation();
    var drop = $(this).siblings('form');

    if (drop.is(":hidden")) {
      $('.title_active').removeClass("title_active").siblings('form').hide();
      drop.show().css('display', 'flex');
      $(this).addClass("title_active");
      $(this).parent().addClass("ms_active");
    } else {
      drop.hide();
      $(this).removeClass("title_active");
    }
  });

    $(".add-new-putter").click(function () {
    $(this).closest('.add-putter-form').toggle();
  });

$( ".add-new-putter" ).click(function() {
    $( ".add-putter-form" ).toggleClass('show-flex');
});
$( ".close-add-putter-form-btn" ).click(function() {
    $( ".add-putter-form" ).toggleClass('show-flex');
});

 $(this).on('click', ".delete-img", function (e) {
    $( "#toggleMe" ).toggle();
});

});