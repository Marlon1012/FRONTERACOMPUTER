      <div id="footer" role="contentinfo">
        <div class="left">
          <a href="#"><?php _e('Inicio', 'fronteracomputer'); ?></a>
          <span>|</span>
          <a href="#"><?php _e('Soporte', 'fronteracomputer'); ?></a>
          <span>|</span>
          <a href="#"><?php _e('Mi Cuenta', 'fronteracomputer'); ?></a>
          <span>|</span>
          <a href="#"><?php _e('Tienda', 'fronteracomputer'); ?></a>
          <span>|</span>
          <a href="#contacto"><?php _e('Contacto', 'fronteracomputer'); ?></a>
        </div>
        <div class="right">&copy; FRONTERACOMPUTER. <?php _e('Diseñado por Ing. Marlon Batista', 'fronteracomputer'); ?></div>
      </div>
    </div>
  </div>

  <div id="cart-modal" class="modal" role="dialog" aria-modal="true" hidden>
    <div class="modal-dialog">
      <button type="button" class="modal-close" aria-label="<?php esc_attr_e('Cerrar', 'fronteracomputer'); ?>">×</button>
      <div class="modal-body cart-body">
        <h3 style="margin-bottom:8px; color:#333; text-transform:none;"><?php _e('Tu carrito', 'fronteracomputer'); ?></h3>
        <div id="cart-empty" class="cart-empty"><?php _e('Tu carrito está vacío.', 'fronteracomputer'); ?></div>
        <table class="cart-table" aria-describedby="cart-summary">
          <thead>
            <tr>
              <th><?php _e('Producto', 'fronteracomputer'); ?></th>
              <th><?php _e('Precio', 'fronteracomputer'); ?></th>
              <th><?php _e('Cant.', 'fronteracomputer'); ?></th>
              <th><?php _e('Subtotal', 'fronteracomputer'); ?></th>
              <th></th>
            </tr>
          </thead>
          <tbody id="cart-items"></tbody>
        </table>
        <div class="cart-summary" id="cart-summary">
          <div><?php _e('Total:', 'fronteracomputer'); ?> <strong id="cart-total"></strong></div>
          <div class="cart-actions">
            <button type="button" id="cart-clear" class="btn secondary"><?php _e('Vaciar', 'fronteracomputer'); ?></button>
            <button type="button" id="cart-checkout-btn" class="btn"><?php _e('Pagar', 'fronteracomputer'); ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="product-modal" class="modal" role="dialog" aria-modal="true" hidden>
    <div class="modal-dialog">
      <button type="button" class="modal-close" aria-label="<?php esc_attr_e('Cerrar', 'fronteracomputer'); ?>">×</button>
      <div class="modal-body">
        <div class="pm-media">
          <img id="pm-image" alt="" />
          <div class="pm-thumbs" id="pm-thumbs"></div>
        </div>
        <div class="pm-info">
          <h3 id="pm-name"></h3>
          <div id="pm-desc" class="pm-desc"></div>
          <div id="pm-specs"></div>
          <div class="pm-price"><?php _e('Precio:', 'fronteracomputer'); ?> <strong id="pm-price"></strong></div>
          <div class="pm-actions">
            <label for="pm-qty"><?php _e('Cantidad', 'fronteracomputer'); ?></label>
            <input type="number" id="pm-qty" min="1" value="1" />
            <button type="button" id="pm-add" class="btn"><?php _e('Agregar al carrito', 'fronteracomputer'); ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="advsearch-modal" class="modal" role="dialog" aria-modal="true" hidden>
    <div class="modal-dialog">
      <button type="button" class="modal-close" aria-label="<?php esc_attr_e('Cerrar', 'fronteracomputer'); ?>">×</button>
      <div class="modal-body cart-body">
        <h3 style="margin-bottom:8px; color:#333; text-transform:none;"><?php _e('Búsqueda avanzada', 'fronteracomputer'); ?></h3>
        <form id="advsearch-form" action="#" method="get">
          <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <label style="flex:1 1 220px;">Texto
              <input type="text" id="as-text" placeholder="<?php esc_attr_e('Nombre, especificación...', 'fronteracomputer'); ?>" />
            </label>
            <label style="flex:0 0 140px;">Precio mín.
              <input type="number" id="as-min" min="0" step="1" />
            </label>
            <label style="flex:0 0 140px;">Precio máx.
              <input type="number" id="as-max" min="0" step="1" />
            </label>
            <label style="flex:0 0 180px;">Categoría
              <select id="as-category">
                <option value=""><?php _e('Todas', 'fronteracomputer'); ?></option>
                <option value="Laptops">Laptops</option>
                <option value="Teclados">Teclados</option>
                <option value="Accesorios">Accesorios</option>
              </select>
            </label>
            <label style="flex:1 1 220px;">Marca (opcional)
              <input type="text" id="as-brand" placeholder="<?php esc_attr_e('HP, Dell, Lenovo...', 'fronteracomputer'); ?>" />
            </label>
          </div>
          <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px;">
            <button type="button" id="as-clear" class="btn secondary"><?php _e('Limpiar', 'fronteracomputer'); ?></button>
            <button type="button" id="as-apply" class="btn"><?php _e('Aplicar filtros', 'fronteracomputer'); ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <a id="whatsapp-float" class="whatsapp" href="https://wa.me/18495061763?text=Chatea%20en%20WhatsApp%20con%20FRONTERACOMPUTER" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('Chatea en WhatsApp con FRONTERACOMPUTER', 'fronteracomputer'); ?>" title="<?php esc_attr_e('Chatea en WhatsApp con FRONTERACOMPUTER', 'fronteracomputer'); ?>">
    <span class="icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11.5 11.5 0 006.1 18.9L2 22l3.2-4.2A11.5 11.5 0 1020.5 3.5zm-8.7 14.9a9 9 0 01-4.6-1.3l-.3-.2-2.7.7.7-2.6-.2-.3a9 9 0 111-1.2l.3.2a8.6 8.6 0 003.9 1.1h.3c.4 0 .7 0 1-.1.3-.1.6-.1.9-.2.3-.1.6-.2.8-.3l.2-.1c.1-.1.3-.1.4-.2l.1-.1c.2-.1.3-.2.5-.3l.1-.1-.1-.2-.1-.1c-.1-.2-.3-.3-.4-.5l-.1-.1-.2-.3-.2-.3-.2-.4-.1-.2-.2-.4v-.2s0-.1-.1-.1l-.1-.1c-.1-.2-.3-.2-.4-.3h-.1l-.3-.1h-.1c-.1 0-.2 0-.3.1h-.1l-.2.1h-.1c-.2.1-.4.2-.6.4l-.1.1-.1.1-.2.3-.1.1c-.1.2-.1.4-.1.6 0 .3.1.6.2.9.1.3.3.6.4.9.2.3.5.5.8.8.3.2.6.4.9.5.3.1.6.2.9.2.3 0 .6-.1.9-.2.3-.1.6-.2.9-.4.3-.2.6-.4.8-.6.3-.2.5-.4.7-.7.2-.3.3-.6.4-.9.1-.3.1-.6.1-.9 0-.4 0-.7-.1-1-.1-.3-.2-.6-.3-.9a6.7 6.7 0 00-.5-.9 4.9 4.9 0 00-.7-.8 4.8 4.8 0 00-.8-.6 6.3 6.3 0 00-1-.5c-.3-.1-.6-.2-.9-.3-.3-.1-.6-.1-.9-.1z"></path></svg>
    </span>
  </a>

  <?php wp_footer(); ?>
  <script>
    (function(){
      var btn = document.getElementById('nav-toggle');
      var nav = document.getElementById('navigation');
      if(btn && nav){
        btn.addEventListener('click', function(){
          var isOpen = /(^|\s)open(\s|$)/.test(nav.className);
          if(isOpen){
            nav.className = nav.className.replace(/(^|\s)open(\s|$)/, ' ').trim();
            btn.setAttribute('aria-expanded', 'false');
          } else {
            nav.className += (nav.className ? ' ' : '') + 'open';
            btn.setAttribute('aria-expanded', 'true');
          }
        }, false);
      }
    })();
  </script>
</body>
</html>
