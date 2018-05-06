'use strict';

jQuery(document).ready(function ($) {

  var myArray = [];
  var allElements = $('.flex-grid-item');
  for (var i = 0, n = allElements.length; i < n; i++) {
    if (allElements[i].getAttribute('style') !== null) {
      myArray.push(allElements[i]);
    }
  }

  var speedInfo = document.getElementById('speedInfo');

  if (myArray.length) {
    myArray.forEach(function (item) {
      $(item).click(function () {
        var title = $(this).data('title');
        var id = $(this).data('id');
        var link = $(this).data('link');
        var speed = $(this).data('speed');
        var fade = $(this).data('fade');
        var turn = $(this).data('turn');
        var glide = $(this).data('glide');
        var picLink = $(this).data('pic');
        var backGround = $(this).data('bg');
        var textColor = $(this).data('text');
        var manage = $(this).data('manage');

        if (typeof title !== 'undefined') {
          var a = $(this).children()[0];
          if (manage) {
            if ($('button.admin-option')[0].style.display === '' || $('button.admin-option')[0].style.display === 'none') {
              $('#speedInfo').html('').show().append('\n  <div class="si-innerWrap" style="background:' + backGround + '; color:' + textColor + '"><div class="si-title"><h3 style="color:' + textColor + '" >' + title + '</h3></div>\n <a id="close-me" >×</a>\n <img src="' + picLink + '"></img>\n <div class="disc-data"><h4 style="color:' + textColor + '">Flight Information</h4><p style="color:' + textColor + '" ><span class="si-data-title">Speed:</span> ' + speed + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Glide:</span> ' + glide + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Turn:</span> ' + turn + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Fade:</span> ' + fade + '</p>\n <a href="' + link + '" class="vp-btn">View Products</a>\n </div>\n' +
                  '<div class="stability-grade-container">\n                  <ul>\n                  <li class="stab--a">a</li>\n                  <li class="stab--b">b</li>\n                <li class="stab--c">c</li>\n                  <li class="stab--d">d</li>\n                  <li class="stab--e">e</li>\n                  <li class="stab--f">f</li>\n                  <li class="stab--g">g</li>\n                  <li class="stab--h">h</li>\n                  <li class="stab--i">i</li>\n                  <li class="stab--j">j</li>\n                  <li class="stab--k">k</li>\n                  <li class="stab--l">l</li>\n                  <li class="stab--m">m</li>\n                  <li class="stab--n">n</li>\n                  <li class="stab--o">o</li>\n                  <li class="stab--p">p</li>\n                  <li class="stab--q">q</li>\n                  </ul>\n                </div>\n                <div class="speed-grade-container">\n                  <ul>\n                  <li class="speed3">\u2191</li>\n                  <li class="speed4">\u2191</li>\n                  <li class="speed5">\u2191</li>\n                  <li class="speed6">\u2191</li>\n                  <li class="speed7">\u2191</li>\n                  <li class="speed8">\u2191</li>\n                  <li class="speed9">\u2191</li>\n                  <li class="speed10">\u2191</li>\n                  <li class="speed11">\u2191</li>\n                  <li class="speed12">\u2191</li>\n                  <li class="speed13">\u2191</li>\n                  <li class="speed14">\u2191</li>\n                  </ul>\n                </div></div>\n              ');
            } else {
              $('#speedInfo').html('').show().append('\n <div class="si-innerWrap" style="background:' + backGround + '; color:' + textColor + '"><div class="si-title"><h3 style="color:' + textColor + '">' + title + '</h3>\n <img class="disc-edit delete-img" src="http://testsite2.marshallstreetdiscgolf.com/wp-content/plugins/MSDGFlightGuide/assets/img/trashbin-icon.png" alt="Delete Disc Entry" title="Delete Disc Entry" width="18" height="22"></div>\n <a id="close-me">×</a>\n <img src="' + picLink + '"></img>\n <div class="disc-data"><h4 style="color:' + textColor + '">Flight Information</h4><p style="color:' + textColor + '"><span class="si-data-title">Speed:</span> ' + speed + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Glide:</span> ' + glide + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Turn:</span> ' + turn + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Fade:</span> ' + fade + '</p>\n <a href="' + link + '" class="vp-btn">View Products</a></div>\n  <form id="toggleMe" method="post">\n<input type="hidden" name="disc_id" value="' + id + '">\n<button type="submit" name="remove_disc">-</button>\n</form>\n' +
                  '  <div class="stability-grade-container">\n                  <ul>\n                  <li class="stab--a">a</li>\n                  <li class="stab--b">b</li>\n                  <li class="stab--c">c</li>\n                  <li class="stab--d">d</li>\n                  <li class="stab--e">e</li>\n                  <li class="stab--f">f</li>\n                  <li class="stab--g">g</li>\n                  <li class="stab--h">h</li>\n                  <li class="stab--i">i</li>\n                  <li class="stab--j">j</li>\n                  <li class="stab--k">k</li>\n                  <li class="stab--l">l</li>\n                  <li class="stab--m">m</li>\n                  <li class="stab--n">n</li>\n                  <li class="stab--o">o</li>\n                  <li class="stab--p">p</li>\n                  <li class="stab--q">q</li>\n                  </ul>\n                </div>\n                <div class="speed-grade-container">\n                  <ul>\n                  <li class="speed3">\u2191</li>\n                  <li class="speed4">\u2191</li>\n                  <li class="speed5">\u2191</li>\n                  <li class="speed6">\u2191</li>\n                  <li class="speed7">\u2191</li>\n                  <li class="speed8">\u2191</li>\n                  <li class="speed9">\u2191</li>\n                  <li class="speed10">\u2191</li>\n                  <li class="speed11">\u2191</li>\n                  <li class="speed12">\u2191</li>\n                  <li class="speed13">\u2191</li>\n                  <li class="speed14">\u2191</li>\n                  </ul>\n               </div></div>\n              ');
            }
          } else {
            $('#speedInfo').html('').show().append('\n <div class="si-innerWrap" style="background:' + backGround + '; color:' + textColor + '"><div class="si-title"><h3 style="color:' + textColor + '">' + title + '</h3></div>\n <a id="close-me">×</a>\n <img src="' + picLink + '"></img>\n <div class="disc-data"><h4 style="color:' + textColor + '">Flight Information</h4><p style="color:' + textColor + '"><span class="si-data-title">Speed:</span> ' + speed + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Glide:</span> ' + glide + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Turn:</span> ' + turn + '</p>\n <p style="color:' + textColor + '"><span class="si-data-title">Fade:</span> ' + fade + '</p>\n <a href="' + link + '" class="vp-btn">View Products</a></div>\n' +
                '<div class="stability-grade-container">\n      <ul>\n    <li class="stab--a">a</li>\n                  <li class="stab--b">b</li>\n                  <li class="stab--c">c</li>\n                  <li class="stab--d">d</li>\n                  <li class="stab--e">e</li>\n                  <li class="stab--f">f</li>\n                  <li class="stab--g">g</li>\n                  <li class="stab--h">h</li>\n                  <li class="stab--i">i</li>\n                  <li class="stab--j">j</li>\n                  <li class="stab--k">k</li>\n                  <li class="stab--l">l</li>\n                  <li class="stab--m">m</li>\n                  <li class="stab--n">n</li>\n                  <li class="stab--o">o</li>\n                  <li class="stab--p">p</li>\n                  <li class="stab--q">q</li>\n                  </ul>\n                </div>\n                <div class="speed-grade-container">\n                  <ul>\n                  <li class="speed3">\u2191</li>\n                  <li class="speed4">\u2191</li>\n                  <li class="speed5">\u2191</li>\n                  <li class="speed6">\u2191</li>\n                  <li class="speed7">\u2191</li>\n                  <li class="speed8">\u2191</li>\n                  <li class="speed9">\u2191</li>\n                  <li class="speed10">\u2191</li>\n                  <li class="speed11">\u2191</li>\n                  <li class="speed12">\u2191</li>\n                  <li class="speed13">\u2191</li>\n                  <li class="speed14">\u2191</li>\n                  </ul>\n                </div></div>\n              ');
          }
        }
        
      });
    });
  }

  $(document).on('click', '#close-me', function () {
    document.getElementsByClassName('speedInfo')[0].style.display = 'none';
  });

  function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function (m, r, g, b) {
      return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
      r: parseInt(result[1], 16),
      g: parseInt(result[2], 16),
      b: parseInt(result[3], 16)
    } : null;
  }

  var disableProducts = document.getElementsByClassName("disable-products");
  var disablePutters = document.getElementsByClassName("putter-child");

  if (disableProducts.length > 0) {
    for (var i = 0; i < disableProducts.length; i++) {
      disableProducts[i].onclick = function () {

        var background = this.dataset.background;
        var textColor = this.dataset.color;
        var disableDivs = document.querySelectorAll('.flex-grid-item > .disc-item[style]');
        var showDivs = document.querySelectorAll('div.hideIt');

        var putters = document.querySelectorAll('.putter-child');
        var rgbBackground = 'rgb(' + hexToRgb(background).r + ', ' + hexToRgb(background).g + ', ' + hexToRgb(background).b + ')';
        var rgbTextColor = 'rgb(' + hexToRgb(textColor).r + ', ' + hexToRgb(textColor).g + ', ' + hexToRgb(textColor).b + ')';

        if (disableDivs.length > 0) {
          if (! this.classList.contains('disabled-manuf-x')) {
            for (var j = 0; j < disableDivs.length; j++) {
              if (disableDivs[j].style.backgroundColor === rgbBackground && disableDivs[j].firstChild.style.color === rgbTextColor) {
                disableDivs[j].classList.add('hideIt');
              }
            }

            if (disablePutters.length > 0) {
              for (var p = 0; p < disablePutters.length; p++) {
                if (disablePutters[p].hasAttribute('style')) {
                  if (disablePutters[p].style.backgroundColor === rgbBackground) {
                    if (disablePutters[p].firstChild.firstChild.style.color === rgbTextColor) {
                      disablePutters[p].classList.add('hideIt');
                    }
                  }
                }
              }
            }
          }
        }

        if (showDivs.length > 0) {
          if (this.classList.contains('disabled-manuf-x')) {

            for (var v=0; v<showDivs.length; v++) {
              if (showDivs[v].dataset.text === textColor && showDivs[v].style.backgroundColor === rgbBackground) {
                showDivs[v].classList.remove('hideIt');
              }
            }

            if (putters.length > 0) {
              for (var p=0; p < putters.length; p++) {
                if (putters[p].hasAttribute("style")) {
                  if (putters[p].style.backgroundColor === rgbBackground && putters[p].firstChild.firstChild.style.color === rgbTextColor) {
                    putters[p].classList.remove('hideIt');
                  }
                }
              }
            }
          }
        }

      }
    }
  }

  $(".manufacturer-item .hide-manuf").click(function () {
    $(this).closest('.manufacturer-item').toggleClass('disabled-manuf');
    $(this).closest('.hide-manuf').toggleClass('disabled-manuf-x');
  });
});
