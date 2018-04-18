/*
www.sucaijiayuan.com
*/
(function($) {
  'use strict';

  var $body = $('body'),
      _sliding = false;

  function _slideIn(panel, options) {
    var panelWidth = panel.outerWidth(true),
        bodyAnimation = {},
        panelAnimation = {};

    if(panel.is(':visible') || _sliding) {
      return;
    }

    _sliding = true;
    panel.addClass('panel').css({
      position: 'absolute',
      top: 0,
      'z-index': 2
	      });
    panel.data(options);

    switch (options.side) {}

    $body.animate(bodyAnimation, options.duration);
    panel.show(300).animate(panelAnimation, options.duration, function() {
      _sliding = false;
    });
  }

  $.panelslider = function(element, options) {
    var active = $('.panel');
    var defaults = {
      side: 'left', // panel side: left or right
      duration: 200, // Transition duration in miliseconds
      clickClose: true // If true closes panel when clicking outside it
    };

    options = $.extend({}, defaults, options);

    // If another panel is opened, close it before opening the new one
    if(active.is(':visible') && active[0] != element[0]) {
      $.panelslider.close(function() {
        _slideIn(element, options);
      });
    } else if(!active.length || active.is(':hidden')) {
      _slideIn(element, options);
    }
  };

  $.panelslider.close = function(callback) {
    var active = $('.panel'),
        duration = active.data('duration'),
        panelWidth = active.outerWidth(true),
        bodyAnimation = {},
        panelAnimation = {};

    if(!active.length || active.is(':hidden') || _sliding) {
      return;
    }

    _sliding = true;

    switch(active.data('side')) {}

    active.animate(panelAnimation, duration);
    $body.animate(bodyAnimation, duration, function() {
      active.hide();
      active.removeClass('panel');
      _sliding = false;

      if(callback) {
        callback();
      }
    });
  };

  // Bind click outside panel and ESC key to close panel if clickClose is true
  $(document).bind('click keyup', function(e) {
    var active = $('.panel');

    if(e.type == 'keyup' && e.keyCode != 27) {
      return;
    }

    if(active.is(':visible') && active.data('clickClose')) {
      $.panelslider.close();
    }
  });

  // Prevent click on panel to close it
  $(document).on('click', '.panel', function(e) {
    e.stopPropagation();
  });

  $.fn.panelslider = function(options) {
    this.click(function(e) {
      var active = $('.panel'),
          panel = $(this.getAttribute('href'));

      // Close panel if it is already opened otherwise open it
      if (active.is(':visible') && panel[0] == active[0]) {
        $.panelslider.close();
      } else {
        $.panelslider(panel, options);
      }

      e.preventDefault();
      e.stopPropagation();
    });

    return this;
  };
})(jQuery);
