(function($) {
	
	$.fn.slide = function(settings){
		var _link = $(this);
		var _parent = _link.parent();
		var _slide_selector = settings.slide_selector;
		var _auto = settings.auto || false;
		var _time = settings.time || 5000;
		
		var _index = 0;
		var _max_index = $(this).length;
		var _interval;
		var $panels = $(_slide_selector);
		
		_link.click(function(){
			_index = _link.parent().find('a').index(this);
			
			if( _auto ) {
				_clear_interval();
				_set_interval();
			}
			
			_set_index();
	
			return false;
		});

		// Accesibilidad: navegación por teclado en tabs
		_link.bind('keydown', function(e){
			var key = e.which || e.keyCode;
			if (key === 13 || key === 32) { // Enter o Espacio activa
				e.preventDefault();
				$(this).click();
				return;
			}
			if (key === 37 || key === 39 || key === 36 || key === 35) { // Flechas, Home, End
				e.preventDefault();
				if (key === 37) { // Left
					_index = (_index - 1 + _max_index) % _max_index;
				}
				if (key === 39) { // Right
					_index = (_index + 1) % _max_index;
				}
				if (key === 36) { // Home
					_index = 0;
				}
				if (key === 35) { // End
					_index = _max_index - 1;
				}
				_set_index();
				_link.eq(_index).focus();
			}
		});
		
		function _set_index(){
			// Estado visual y ARIA de los tabs
			_link.removeClass('active').attr({'aria-selected':'false', 'tabindex':'-1'});
			_link.eq(_index).addClass('active').attr({'aria-selected':'true', 'tabindex':'0'});
			
			// Sincronizar paneles: ocultar/mostrar y atributo hidden
			$panels.hide().attr('hidden', 'hidden');
			$panels.eq(_index).removeAttr('hidden').fadeIn();
		}
		
		function _set_interval() {
			_interval = window.setInterval(_next, _time);
		}
		
		function _clear_interval(){
			window.clearInterval(_interval);
		}
		
		function _next(){
			_index++;
			if( _index == _max_index ) {
				_index = 0;
			}
			
			_set_index();
		}
		
		_set_index();
		
		if( _auto ) {
			_set_interval();
		}
	}
	
})(jQuery);