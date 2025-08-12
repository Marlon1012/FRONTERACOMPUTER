$(document).ready(function(){
	// Comportamiento legacy de inputs con .blink
	$('.blink')
		.focus(function(){
			if( $(this).attr('value') == $(this).attr('title') ) {
				$(this).attr({ 'value': '' });
			}
		})
		.blur(function(){
			if( $(this).attr('value') == '' || typeof($(this).attr('value')) == 'undefined') {
				$(this).attr({ 'value': $(this).attr('title') })
			}
		});

	// Slider legacy (solo si existe en DOM)
	if ($.fn.jcarousel && $('#slider-holder ul').length) {
		$('#slider-holder ul').jcarousel({
			scroll: 1,
			wrap: 'both',
			initCallback: _init_carousel,
			buttonNextHTML: null,
			buttonPrevHTML: null
		});
	}

	// Tabs
	$('.tabs a').slide({ 'slide_selector' : '.tab-content' });

	// Envío por WhatsApp desde el formulario de servicios (compat jQuery 1.4)
	$('#ws-submit').bind('click', function(){
		var tel = '18495061763'; // Número en formato internacional E.164
		var nombre = $.trim($('#sv-nombre').val()||'');
		var telefono = $.trim($('#sv-telefono').val()||'');
		var email = $.trim($('#sv-email').val()||'');
		var servicio = $('#sv-servicio').val();
		var descripcion = $.trim($('#sv-descripcion').val()||'');
		var direccion = $.trim($('#sv-direccion').val()||'');

		if(!nombre || !telefono){
			alert('Por favor completa al menos Nombre y Teléfono.');
			return;
		}

		var msg = 'Hola, quiero solicitar un servicio:%0A' +
			'- Nombre: ' + encodeURIComponent(nombre) + '%0A' +
			'- Teléfono: ' + encodeURIComponent(telefono) + '%0A' +
			(email ? ('- Email: ' + encodeURIComponent(email) + '%0A') : '') +
			'- Servicio: ' + encodeURIComponent(servicio) + '%0A' +
			(descripcion ? ('- Descripción: ' + encodeURIComponent(descripcion) + '%0A') : '') +
			(direccion ? ('- Dirección: ' + encodeURIComponent(direccion) + '%0A') : '');

		var url = 'https://wa.me/' + tel + '?text=' + msg;
		window.open(url, '_blank');
	});

	// ==============================
	// Carrito y modal de producto
	// ==============================
	var CART_KEY = 'item';

	function parseJSON(raw){
		var obj = null;
		if(!raw) return null;
		try {
			if (window.JSON && window.JSON.parse) { obj = window.JSON.parse(raw); }
			else { obj = eval('(' + raw + ')'); }
		} catch(e) { obj = null; }
		return obj;
	}
	function loadCart(){
		var raw = null;
		try { raw = window.localStorage ? localStorage.getItem(CART_KEY) : null; } catch(e) {}
		var cart = parseJSON(raw);
		if(!cart || !cart.items){ cart = { items: {}, totalCount: 0, totalAmount: 0 }; }
		calcTotals(cart);
		return cart;
	}
	function saveCart(cart){
		try { if(window.localStorage){ localStorage.setItem(CART_KEY, JSON.stringify(cart)); } } catch(e){}
	}
	function calcTotals(cart){
		var count=0, total=0;
		for(var id in cart.items){ if(!cart.items.hasOwnProperty(id)) continue; var it = cart.items[id]; count += it.qty; total += (it.price||0) * it.qty; }
		cart.totalCount = count; cart.totalAmount = total;
	}
	function formatUSD(n){ n = n || 0; return '$' + n.toFixed(2) + ' USD'; }
	function updateCartUI(cart){ $('.cart-count').text(cart.totalCount); $('.options .cart strong').text(formatUSD(cart.totalAmount)); }

	var cart = loadCart();
	updateCartUI(cart);

	// Click en productos para abrir modal
	$('.items li').each(function(){
		var $li = $(this);
		$li.css('cursor','pointer');
		$li.find('a').click(function(e){ e.preventDefault(); });
		$li.bind('click', function(){
			var product = extractProductFromLi($li);
			openProductModal(product);
		});
	});

	function extractProductFromLi($li){
		var img = $li.find('.image img');
		var imgSrc = img.attr('src') || '';
		var name = $.trim($li.find('p').not('.price').find('span').first().text());
		var priceText = $.trim($li.find('p.price strong').text());
		var price = parseFloat(priceText.replace(/[^0-9\.]/g, '')) || 0;
		var specs = [];
		$li.find('p').not('.price').each(function(){
			var html = $(this).html() || '';
			var parts = html.split(/<br\s*\/?\s*>/i);
			for(var i=0;i<parts.length;i++){
				var part = $.trim(parts[i]);
				if(!part) continue;
				var tmp = $('<div>').html(part);
				var text = $.trim(tmp.text());
				var idx = text.indexOf(':');
				if(idx>0){
					var label = $.trim(text.substring(0, idx));
					var value = $.trim(text.substring(idx+1));
					specs.push({label: label, value: value});
					if(label.toLowerCase() === 'producto'){ name = value || name; }
				}
			}
		});
		var id = (imgSrc + '|' + name).toLowerCase();
		var galAttr = $.trim($li.attr('data-gallery')||'');
		var gallery = [];
		if(galAttr){
			var parts = galAttr.split(/\s*,\s*/);
			for(var g=0; g<parts.length; g++){ if(parts[g]) gallery.push(parts[g]); }
		}
		if(gallery.length === 0){ gallery = [imgSrc]; }
		var desc = $.trim($li.attr('data-desc')||'');
		if(!desc){ desc = buildProductDescription(name, specs); }
		return { id: id, name: name, image: imgSrc, price: price, specs: specs, gallery: gallery, description: desc };
	}

	function buildProductDescription(name, specs){
		var map = {};
		for(var i=0;i<specs.length;i++){ map[specs[i].label.toLowerCase()] = specs[i].value; }
		var proc = map['procesador'] || map['cpu'] || '';
		var ram = map['memoria ram'] || map['ram'] || '';
		var storage = map['almacenamiento'] || map['disco'] || '';
		var lines = [];
		lines.push(name + ': rendimiento confiable para trabajo, estudio y entretenimiento.');
		if(proc){ lines.push('Procesador ' + proc + ' para ejecutar múltiples tareas con fluidez.'); }
		if(ram){ lines.push(ram + ' de RAM para navegación con varias pestañas y apps abiertas.'); }
		if(storage){ lines.push(storage + ' para arranques rápidos y espacio suficiente para tus archivos.'); }
		lines.push('Incluye garantía y soporte. Ideal para uso diario y productividad.');
		return lines.join(' ');
	}

	var $modal = $('#product-modal');
	var $pmImage = $('#pm-image');
	var $pmName = $('#pm-name');
	var $pmSpecs = $('#pm-specs');
	var $pmPrice = $('#pm-price');
	var $pmThumbs = $('#pm-thumbs');
	var $pmDesc = $('#pm-desc');

	// Scroll lock helpers
	function lockScroll(){
		var body = document.body;
		if((' ' + body.className + ' ').indexOf(' scroll-lock ') !== -1) return;
		var y = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
		body.setAttribute('data-scroll-y', y);
		var sw = window.innerWidth - document.documentElement.clientWidth;
		if(sw > 0){ body.style.paddingRight = sw + 'px'; }
		body.style.top = (-y) + 'px';
		body.style.position = 'fixed';
		body.style.width = '100%';
		body.style.overflow = 'hidden';
		if(body.className.indexOf('scroll-lock') === -1){ body.className += (body.className ? ' ' : '') + 'scroll-lock'; }
	}
	function unlockScroll(){
		var body = document.body;
		var y = parseInt(body.getAttribute('data-scroll-y') || '0', 10) || 0;
		body.className = body.className.replace(/(^|\s)scroll-lock(\s|$)/, ' ').replace(/\s+/g,' ').replace(/^\s|\s$/g,'');
		body.style.top = '';
		body.style.paddingRight = '';
		body.style.position = '';
		body.style.width = '';
		body.style.overflow = '';
		body.removeAttribute('data-scroll-y');
		window.scrollTo(0, y);
	}

	function openProductModal(product){
		// Imagen principal y nombre
		var mainUrl = (product.gallery && product.gallery.length) ? product.gallery[0] : product.image;
		$pmImage.attr({ src: mainUrl, alt: product.name });
		$pmName.text(product.name);
		$pmPrice.text(formatUSD(product.price));

		// Especificaciones
		var $ul = $('<ul class="pm-spec-list"></ul>');
		for(var i=0;i<product.specs.length;i++){
			var s = product.specs[i];
			var $li = $('<li></li>');
			$('<strong></strong>').text(s.label + ': ').appendTo($li);
			$li.append(document.createTextNode(s.value));
			$ul.append($li);
		}
		$pmSpecs.empty().append($ul);

		// Descripción detallada
		$pmDesc.empty().append($('<p></p>').text(product.description||''));

		// Thumbs de galería
		$pmThumbs.empty();
		for(var t=0; product.gallery && t<product.gallery.length; t++){
			(function(url, index){
				var $btn = $('<button type="button" class="pm-thumb" aria-label="Ver imagen"></button>');
				var $img = $('<img />').attr('src', url).attr('alt','');
				$btn.append($img);
				if(index===0){ $btn.addClass('active'); }
				$btn.bind('click', function(){
					$pmThumbs.find('.pm-thumb').removeClass('active');
					$btn.addClass('active');
					$pmImage.attr('src', url);
				});
				$pmThumbs.append($btn);
			})(product.gallery[t], t);
		}

		$('#pm-qty').val('1');
		$modal.data('product', product);
		lockScroll();
		$modal.removeAttr('hidden').show();
		$modal.find('.modal-body').css({ 'maxHeight': 'calc(100vh - 80px)', 'overflow': 'auto', '-webkit-overflow-scrolling': 'touch' });
		$('.modal-close').focus();
	}
	function closeProductModal(){ $modal.hide().attr('hidden','hidden'); unlockScroll(); }
	$('.modal-close').bind('click', function(){ closeProductModal(); });
	$modal.bind('click', function(e){ if($(e.target).is('#product-modal')) closeProductModal(); });
	$(document).bind('keydown', function(e){ if(e.keyCode === 27 && !$modal.attr('hidden')) closeProductModal(); });

	$('#pm-add').bind('click', function(){
		var qty = parseInt($('#pm-qty').val(), 10) || 1;
		if(qty < 1) qty = 1;
		var product = $modal.data('product');
		if(!product) return;
		if(!cart.items[product.id]){ cart.items[product.id] = { name: product.name, price: product.price, qty: 0, image: product.image }; }
		cart.items[product.id].qty += qty;
		calcTotals(cart);
		saveCart(cart);
		updateCartUI(cart);
		closeProductModal();
		alert('Producto agregado al carrito.');
	});

	// Modal de carrito
	var $cModal = $('#cart-modal');
	var $cItems = $('#cart-items');
	var $cEmpty = $('#cart-empty');
	var $cTotal = $('#cart-total');

	function openCartModal(){
		renderCart();
		lockScroll();
		$cModal.removeAttr('hidden').show();
		$cModal.find('.modal-body').css({ 'maxHeight': 'calc(100vh - 80px)', 'overflow': 'auto', '-webkit-overflow-scrolling': 'touch' });
		$cModal.find('.modal-close').focus();
	}
	function closeCartModal(){ $cModal.hide().attr('hidden','hidden'); unlockScroll(); }

	$('.cart-ico').bind('click', function(e){ e.preventDefault(); openCartModal(); });
	$('#cart-details').bind('click', function(e){ e.preventDefault(); openCartModal(); });
	$('#cart-checkout').bind('click', function(e){ e.preventDefault(); openCartModal(); });
	$('#cart-checkout-btn').bind('click', function(){
		var tel = '18495061763';
		if(cart.totalCount <= 0){ alert('Tu carrito está vacío.'); return; }
		var msg = buildCartMessage(cart);
		var url = 'https://wa.me/' + tel + '?text=' + msg;
		window.open(url, '_blank');
	});

	$('#cart-clear').bind('click', function(){
		cart = { items:{}, totalCount:0, totalAmount:0 };
		saveCart(cart);
		updateCartUI(cart);
		renderCart();
	});

	$cModal.find('.modal-close').bind('click', function(){ closeCartModal(); });
	$cModal.bind('click', function(e){ if($(e.target).is('#cart-modal')) closeCartModal(); });

	// Advanced Search modal open/close with scroll lock
	var $asModal = $('#advsearch-modal');
	$('#advanced-search-link').bind('click', function(e){ e.preventDefault(); openAdvSearch(); });
	function openAdvSearch(){ lockScroll(); $asModal.removeAttr('hidden').show(); $asModal.find('.modal-body').css({ 'maxHeight': 'calc(100vh - 80px)', 'overflow': 'auto', '-webkit-overflow-scrolling': 'touch' }); $asModal.find('.modal-close').focus(); }
	function closeAdvSearch(){ $asModal.hide().attr('hidden','hidden'); unlockScroll(); }
	$asModal.find('.modal-close').bind('click', function(){ closeAdvSearch(); });
	$asModal.bind('click', function(e){ if($(e.target).is('#advsearch-modal')) closeAdvSearch(); });

	function buildCartMessage(cart){
		var lines = [];
		lines.push('Pedido desde FRONTERACOMPUTER');
		for(var id in cart.items){
			if(!cart.items.hasOwnProperty(id)) continue;
			var it = cart.items[id];
			lines.push('- ' + it.name + ' x' + it.qty + ' = ' + formatUSD((it.price||0)*it.qty));
		}
		lines.push('Total: ' + formatUSD(cart.totalAmount));
		var text = lines.join('%0A');
		return text;
	}

	function renderCart(){
		if(cart.totalCount <= 0){
			$cEmpty.show();
			$('.cart-table, .cart-summary').hide();
			$cItems.empty();
			$cTotal.text(formatUSD(0));
			return;
		}
		$cEmpty.hide();
		$('.cart-table, .cart-summary').show();
		var html = '';
		for(var id in cart.items){
			if(!cart.items.hasOwnProperty(id)) continue;
			var it = cart.items[id];
			var subtotal = (it.price||0) * it.qty;
			html += '<tr data-id="' + escapeHtml(id) + '">';
			html += '<td class="ci-product"><img src="' + escapeAttr(it.image||'') + '" alt="" /><span>' + escapeHtml(it.name||'') + '</span></td>';
			html += '<td>' + formatUSD(it.price||0) + '</td>';
			html += '<td><input type="number" class="ci-qty" min="1" value="' + it.qty + '" /></td>';
			html += '<td class="ci-subtotal">' + formatUSD(subtotal) + '</td>';
			html += '<td><button type="button" class="ci-remove">Eliminar</button></td>';
			html += '</tr>';
		}
		$cItems.html(html);
		$cTotal.text(formatUSD(cart.totalAmount));

		$cItems.find('.ci-qty').each(function(){
			var $input = $(this);
			$input.bind('change', function(){
				var $tr = $input.closest('tr');
				var id = $tr.attr('data-id');
				var qty = parseInt($input.val(), 10) || 1;
				if(qty < 1) qty = 1;
				if(cart.items[id]){
					cart.items[id].qty = qty;
					calcTotals(cart);
					saveCart(cart);
					updateCartUI(cart);
					renderCart();
				}
			});
		});
		$cItems.find('.ci-remove').each(function(){
			var $btn = $(this);
			$btn.bind('click', function(){
				var $tr = $btn.closest('tr');
				var id = $tr.attr('data-id');
				if(cart.items[id]){ delete cart.items[id]; }
				calcTotals(cart);
				saveCart(cart);
				updateCartUI(cart);
				renderCart();
			});
		});
	}

	function escapeHtml(str){
		str = String(str||'');
		return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\"/g,'&quot;').replace(/'/g,'&#39;');
	}
	function escapeAttr(str){ return escapeHtml(str); }

	});
function _init_carousel(carousel) {
	$('#slider-nav .next').bind('click', function() {
		carousel.next();
		return false;
	});
	
	$('#slider-nav .prev').bind('click', function() {
		carousel.prev();
		return false;
	});
};