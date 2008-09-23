$(document).ready(function() {
  var place = function(element, target, offset) {
    element.width(target.width()).height(target.height()).css('top', 0).css('left', 0);
    target.after(element);
    t_offset = target.offset();
    e_offset = element.offset();
    element.css('left', t_offset['left'] - e_offset['left'] + offset).css('top', t_offset['top'] - e_offset['top'] + offset);
  };

  var cloneAndPlace = function(element, dom_class, offset) {
    clone = element.clone().addClass(dom_class);
    place(clone, element, offset);
  };

  var makeShadow = function(element) {
    element = $(element);
    if (element.is('img')) {
      place(element.clone().addClass('overShadow'), element, 0);
      element.css('visibility', 'hidden');
      place($(document.createElement('div')).addClass('blockShadow'), element, 3);
      return;
    }
    cloneAndPlace(element, 'shadow', 1);
    cloneAndPlace(element, 'overShadow', 0);
    element.css('visibility', 'hidden');
  };

  var makeShadows = function(selector) {
    $(selector).each(function() { makeShadow(this); });
  };

  makeShadows('h1');
  makeShadows('h2');
  makeShadows('ul');
  makeShadows('p');
  makeShadows('img');
});
