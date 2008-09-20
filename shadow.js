$(document).ready(function() {
  var makeShadow = function(element) {
    element = $(element);
    offset = element.offset();
    shadow = element.clone().addClass('shadow').width(element.width()).height(element.height()).css('top', offset['top']+1).css('left', offset['left']+1);
    over = element.clone().addClass('overShadow').width(element.width()).height(element.height()).css('top', offset['top']).css('left', offset['left']);
    element.before(over).after(shadow).css('visibility', 'hidden'); 
  };

  var makeShadows = function(selector) {
    $(selector).each(function() { makeShadow(this); });
  };

  makeShadows('h1');
  makeShadows('h2');
  makeShadows('ul');
});
