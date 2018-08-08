$(document).ready(function() {
  $('.sup-go').click(function() {
    event.stopPropagation();
    var this_class = $(this).attr('class');
    this_class = this_class.replace('sup-go ','')
    $('.sup-' + this_class).parent().animate({ borderColor: "rgba(0, 0, 0, 0.45) rgba(0, 0, 0, 0.45) rgba(0, 0, 0, 0.7)" }, 'fast');
    var pos = $('.sup-' + this_class).offset().top - $('.navbar').outerHeight() - 12;
    $('html, body').animate({
      scrollTop: pos
    }, 1400);
    return false;
  });

  $('html').click(function() {
    $('.warn').animate({ borderColor: "rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25)" }, 'fast');
  });
});