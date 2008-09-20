$(document).ready(function() {
  var cloneAndPlace = function(element, dom_class, offset) {
    e_offset = element.offset();
    clone = element.clone().addClass(dom_class).width(element.width()).height(element.height()).css('top', e_offset['top']).css('left', e_offset['left']);
    element.after(clone);
    c_offset = clone.offset();
    clone.css('left', 2 * e_offset['left'] - c_offset['left'] + offset).css('top', 2 * e_offset['top'] - c_offset['top'] + offset);
  };

  var makeShadow = function(element) {
    element = $(element);
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
});
