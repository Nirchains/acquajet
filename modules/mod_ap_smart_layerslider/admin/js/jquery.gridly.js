
(function() {
  "use strict";
  var $, Draggable, Gridly,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __slice = [].slice;

  $ = jQuery;
  Draggable = (function() {
    function Draggable($container, selector, callbacks) {
      this.click = __bind(this.click, this);
      this.moved = __bind(this.moved, this);
      this.ended = __bind(this.ended, this);
      this.began = __bind(this.began, this);
      this.coordinate = __bind(this.coordinate, this);
      this.off = __bind(this.off, this);
      this.on = __bind(this.on, this);
      this.toggle = __bind(this.toggle, this);
      this.bind = __bind(this.bind, this);
      this.$container = $container;
      this.selector = selector;
      this.callbacks = callbacks;
      this.toggle();
    }

    Draggable.prototype.bind = function(method) {
      if (method == null) {
        method = 'on';
      }
      $(document)[method]('mousemove touchmove', this.moved);
      return $(document)[method]('mouseup touchend touchcancel', this.ended);
    };

    Draggable.prototype.toggle = function(method) {
      if (method == null) {
        method = 'on';
      }
      this.$container[method]('mousedown touchstart', this.selector, this.began);
      return this.$container[method]('click', this.selector, this.click);
    };

    Draggable.prototype.on = function() {
      return this.toggle('on');
    };

    Draggable.prototype.off = function() {
      return this.toggle('off');
    };

    Draggable.prototype.coordinate = function(event) {
      switch (event.type) {
        case 'touchstart':
        case 'touchmove':
        case 'touchend':
        case 'touchcancel':
          return event.originalEvent.touches[0];
        default:
          return event;
      }
    };

    Draggable.prototype.began = function(event) {
      var _ref;
      if (this.$target) {
        return;
      }
      event.preventDefault();
      event.stopPropagation();
      this.bind('on');
      this.$target = $(event.target).closest(this.$container.find(this.selector));
      this.$target.addClass('dragging');
      this.origin = {
        x: this.coordinate(event).pageX - this.$target.position().left,
        y: this.coordinate(event).pageY - this.$target.position().top
      };
      return (_ref = this.callbacks) != null ? typeof _ref.began === "function" ? _ref.began(event) : void 0 : void 0;
    };

    Draggable.prototype.ended = function(event) {
      var _ref;
      if (this.$target == null) {
        return;
      }
      event.preventDefault();
      event.stopPropagation();
      this.bind('off');
      this.$target.removeClass('dragging');
      delete this.$target;
      delete this.origin;
      return (_ref = this.callbacks) != null ? typeof _ref.ended === "function" ? _ref.ended(event) : void 0 : void 0;
    };

    Draggable.prototype.moved = function(event) {
      var _ref;
      if (this.$target == null) {
        return;
      }
      event.preventDefault();
      event.stopPropagation();
      this.$target.css({
        left: this.coordinate(event).pageX - this.origin.x,
        top: this.coordinate(event).pageY - this.origin.y
      });
      this.dragged = this.$target;
      return (_ref = this.callbacks) != null ? typeof _ref.moved === "function" ? _ref.moved(event) : void 0 : void 0;
    };

    Draggable.prototype.click = function(event) {
      if (!this.dragged) {
        return;
      }
      event.preventDefault();
      event.stopPropagation();
      return delete this.dragged;
    };

    return Draggable;

  })();

  Gridly = (function(win) {
    Gridly.settings = {
      base: 60,
      gutter: 20,
	  columns:8,
      draggable: {
        zIndex: 800,
        selector: '> *'
      },
	  responsive: false
    };
	
    Gridly.gridly = function($el, options) {
      var data;
      if (options == null) {
        options = {};
      }
      data = $el.data('_gridly');
      if (!data) {
        data = new Gridly($el, options);
        $el.data('_gridly', data);
      }
      return data;
    };

    function Gridly($el, settings) {
      if (settings == null) {
        settings = {};
      }	  
      this.optimize = __bind(this.optimize, this);
      this.layout = __bind(this.layout, this);
      this.structure = __bind(this.structure, this);
      this.position = __bind(this.position, this);
      this.size = __bind(this.size, this);
      this.draggingMoved = __bind(this.draggingMoved, this);
      this.draggingEnded = __bind(this.draggingEnded, this);
      this.draggingBegan = __bind(this.draggingBegan, this);
      this.$sorted = __bind(this.$sorted, this);
      this.draggable = __bind(this.draggable, this);
      this.compare = __bind(this.compare, this);
      this.$ = __bind(this.$, this);
      this.reordinalize = __bind(this.reordinalize, this);
      this.ordinalize = __bind(this.ordinalize, this);
      this.$el = $el;
      this.settings = $.extend({}, Gridly.settings, settings);
	  this.config = {};
	  if(this.settings.responsive == true || this.settings.columns == undefined || !$.isNumeric(this.settings.columns)){		  
		  this.config.columns = Math.floor($el.width() / (this.settings.gutter + this.settings.base));
		  
		  $(win).resize(function(){
			var data = $el.data('_gridly');
			if(!!data){
				data.config.columns = Math.floor($el.width() / (data.settings.gutter + data.settings.base));
				setTimeout(data.layout, 0);
			}
		  });
	  } else {
		this.config.columns = this.settings.columns;
	  }
	  	  
      this.ordinalize(this.$('> *'));
      if (this.settings.draggable !== false) {
        this.draggable();
      }
      return this;
    }

    Gridly.prototype.ordinalize = function($elements) {
      var $element, i, _i, _ref, _results;
      _results = [];
      for (i = _i = 0, _ref = $elements.length; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
        $element = $($elements[i]);
		$element.attr("data-position",i);
        _results.push($element.data('position', i));
      }
      return _results;
    };

    Gridly.prototype.reordinalize = function($element, position) {
      $element.attr("data-position",position);
	  return $element.data('position', position);
    };

    Gridly.prototype.$ = function(selector) {
      return this.$el.find(selector);
    };

    Gridly.prototype.compare = function(d, s) {
      if (d.y > s.y + s.h) {
        return +1;
      }
      if (s.y > d.y + d.h) {
        return -1;
      }
      if ((d.x + (d.w / 2)) > (s.x + (s.w / 2))) {
        return +1;
      }
      if ((s.x + (s.w / 2)) > (d.x + (d.w / 2))) {
        return -1;
      }
      return 0;
    };

    Gridly.prototype.draggable = function(method) {
      if (this._draggable == null) {
        this._draggable = new Draggable(this.$el, this.settings.draggable.selector, {
          began: this.draggingBegan,
          ended: this.draggingEnded,
          moved: this.draggingMoved
        });
      }
      if (method != null) {
        return this._draggable[method]();
      }
    };

    Gridly.prototype.$sorted = function($elements) {
      return ($elements || this.$('> *')).sort(function(a, b) {
        var $a, $b, aPosition, aPositionInt, bPosition, bPositionInt;
        $a = $(a);
        $b = $(b);
        aPosition = $a.data('position');
        bPosition = $b.data('position');
        aPositionInt = parseInt(aPosition);
        bPositionInt = parseInt(bPosition);
        if ((aPosition != null) && (bPosition == null)) {
          return -1;
        }
        if ((bPosition != null) && (aPosition == null)) {
          return +1;
        }
        if (!aPosition && !bPosition && $a.index() < $b.index()) {
          return -1;
        }
        if (!bPosition && !aPosition && $b.index() < $a.index()) {
          return +1;
        }
        if (aPositionInt < bPositionInt) {
          return -1;
        }
        if (bPositionInt < aPositionInt) {
          return +1;
        }
        return 0;
      });
    };

    Gridly.prototype.draggingBegan = function(event) {
      var $elements, _ref, _ref1;
      $elements = this.$sorted();
      this.ordinalize($elements);
      setTimeout(this.layout, 0);
      return (_ref = this.settings) != null ? (_ref1 = _ref.callbacks) != null ? typeof _ref1.reordering === "function" ? _ref1.reordering($elements) : void 0 : void 0 : void 0;
    };

    Gridly.prototype.draggingEnded = function(event) {
      var $elements, _ref, _ref1;
      $elements = this.$sorted();
      this.ordinalize($elements);
      setTimeout(this.layout, 0);
      return (_ref = this.settings) != null ? (_ref1 = _ref.callbacks) != null ? typeof _ref1.reordered === "function" ? _ref1.reordered($elements) : void 0 : void 0 : void 0;
    };

    Gridly.prototype.draggingMoved = function(event) {
      var $dragging, $elements, element, i, index, original, positions, _i, _j, _len, _ref, _ref1, _ref2;
      $dragging = $(event.target).closest(this.$(this.settings.draggable.selector));
      $elements = this.$sorted(this.$(this.settings.draggable.selector));
      positions = this.structure($elements).positions;
      original = index = $dragging.data('position');
      _ref = positions.filter(function(position) {
        return position.$element.is($dragging);
      });
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        element = _ref[_i];
        element.x = $dragging.position().left;
        element.y = $dragging.position().top;
        element.w = $dragging.data('width') || $dragging.width();
        element.h = $dragging.data('height') || $dragging.height();
      }
      positions.sort(this.compare);
      $elements = positions.map(function(position) {
        return position.$element;
      });
      $elements = (((_ref1 = this.settings.callbacks) != null ? _ref1.optimize : void 0) || this.optimize)($elements);
      for (i = _j = 0, _ref2 = $elements.length; 0 <= _ref2 ? _j < _ref2 : _j > _ref2; i = 0 <= _ref2 ? ++_j : --_j) {
        this.reordinalize($($elements[i]), i);
      }
      return this.layout();
    };

    Gridly.prototype.size = function($element) {
      return (($element.data('width') || $element.width()) + this.settings.gutter) / (this.settings.base + this.settings.gutter);
    };

    Gridly.prototype.position = function($element, columns) {
      var column, height, i, max, size, _i, _j, _ref, _ref1;
      size = this.size($element);
      height = Infinity;
      column = 0;
      for (i = _i = 0, _ref = columns.length - size; 0 <= _ref ? _i < _ref : _i > _ref; i = 0 <= _ref ? ++_i : --_i) {
        max = Math.max.apply(Math, columns.slice(i, i + size));
        if (max < height) {
          height = max;
          column = i;
        }
      }
      for (i = _j = column, _ref1 = column + size; column <= _ref1 ? _j < _ref1 : _j > _ref1; i = column <= _ref1 ? ++_j : --_j) {
        columns[i] = height + ($element.data('height') || $element.height()) + this.settings.gutter;
      }
      return {
        x: column * (this.settings.base + this.settings.gutter),
        y: height
      };
    };

    Gridly.prototype.structure = function($elements) {
      var $element, columns, i, index, position, positions, _i, _ref;
      if ($elements == null) {
        $elements = this.$sorted();
      }
      positions = [];
      columns = (function() {
        var _i, _ref, _results;
        _results = [];
        for (i = _i = 0, _ref = this.config.columns; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
          _results.push(0);
        }
        return _results;
      }).call(this);
      for (index = _i = 0, _ref = $elements.length; 0 <= _ref ? _i < _ref : _i > _ref; index = 0 <= _ref ? ++_i : --_i) {
        $element = $($elements[index]);
        position = this.position($element, columns);
        positions.push({
          x: position.x,
          y: position.y,
          w: $element.data('width') || $element.width(),
          h: $element.data('height') || $element.height(),
          $element: $element
        });
      }
      return {
        height: Math.max.apply(Math, columns),
        positions: positions
      };
    };

    Gridly.prototype.layout = function() {
      var $element, $elements, index, position, structure, _i, _ref, _ref1;
      $elements = (((_ref = this.settings.callbacks) != null ? _ref.optimize : void 0) || this.optimize)(this.$sorted());
      structure = this.structure($elements);
      for (index = _i = 0, _ref1 = $elements.length; 0 <= _ref1 ? _i < _ref1 : _i > _ref1; index = 0 <= _ref1 ? ++_i : --_i) {
        $element = $($elements[index]);
        position = structure.positions[index];
        if ($element.is('.dragging')) {
          continue;
        }
		$element.attr("data-position",index);
        $element.css({
          position: 'absolute',
          left: position.x,
          top: position.y
        });
      }
      return this.$el.css({
        height: structure.height
      });
    };

    Gridly.prototype.optimize = function(originals) {
      var columns, index, results, _i, _ref;
      results = [];
      columns = 0;
      while (originals.length > 0) {
        if (columns === this.config.columns) {
          columns = 0;
        }
        index = 0;
        for (index = _i = 0, _ref = originals.length; 0 <= _ref ? _i < _ref : _i > _ref; index = 0 <= _ref ? ++_i : --_i) {
          if (columns + this.size($(originals[index])) <= this.config.columns) {
            break;
          }
        }
        if (index === originals.length) {
          index = 0;
          columns = 0;
        }
        columns += this.size($(originals[index]));
        results.push(originals.splice(index, 1)[0]);
      }
      return results;
    };

    return Gridly;

  })(window);

  $.fn.extend({
    gridly: function() {
      var option, parameters;
      option = arguments[0], parameters = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if (option == null) {
        option = {};
      }
      return this.each(function() {
        var $this, action, options;
        $this = $(this);
        options = $.extend({}, $.fn.gridly.defaults, typeof option === "object" && option);
        action = typeof option === "string" ? option : option.action;
        if (action == null) {
          action = "layout";
        }
        return Gridly.gridly($this, options)[action](parameters);
      });
    }
  });

}).call(this);
