<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <a class="skip-link" href="#content"><?php _e('Saltar al contenido', 'fronteracomputer'); ?></a>
  <div id="top">
    <div class="shell">
      <div id="header" role="banner">
        <h1 id="logo"><a href="<?php echo esc_url(home_url('/')); ?>">FRONTERACOMPUTER</a></h1>
        <button id="nav-toggle" class="nav-toggle" aria-expanded="false" aria-controls="navigation">&#9776;</button>
        <nav id="navigation" aria-label="<?php esc_attr_e('Navegación principal', 'fronteracomputer'); ?>">
          <?php
            wp_nav_menu([
              'theme_location' => 'primary',
              'container' => false,
              'menu_class' => '',
              'items_wrap' => '<ul>%3$s</ul>',
              'fallback_cb' => false,
            ]);
          ?>
        </nav>
      </div>
      <section class="hero" aria-label="<?php esc_attr_e('Promoción destacada', 'fronteracomputer'); ?>">
        <div class="hero-inner">
          <h2><?php _e('Tu tecnología al mejor precio', 'fronteracomputer'); ?></h2>
          <p><?php _e('Envíos a todo el país. Garantía y soporte.', 'fronteracomputer'); ?></p>
          <a class="btn-cta" href="#ofertas" aria-label="<?php esc_attr_e('Ver productos en oferta', 'fronteracomputer'); ?>"><?php _e('Ver ofertas', 'fronteracomputer'); ?></a>
        </div>
      </section>
    </div>
  </div>
  <div id="main" role="main">
    <div class="shell">
      <div class="options">
        <div class="search">
          <form action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search" aria-label="<?php esc_attr_e('Buscar en la tienda', 'fronteracomputer'); ?>">
            <span class="field">
              <input type="search" name="s" placeholder="<?php esc_attr_e('Buscar productos', 'fronteracomputer'); ?>" aria-label="<?php esc_attr_e('Buscar', 'fronteracomputer'); ?>" />
            </span>
            <input type="submit" class="search-submit" value="<?php esc_attr_e('Buscar', 'fronteracomputer'); ?>" />
          </form>
        </div>
        <span class="left"><a href="#" id="advanced-search-link"><?php _e('Búsqueda Avanzada', 'fronteracomputer'); ?></a></span>
        <div class="right">
          <span class="cart">
            <a href="#" class="cart-ico" aria-label="<?php esc_attr_e('Carrito de compras', 'fronteracomputer'); ?>">&nbsp;</a>
            <span class="cart-count" aria-label="<?php esc_attr_e('Artículos en el carrito', 'fronteracomputer'); ?>">0</span>
            <strong>$0.00</strong>
          </span>
          <span class="left more-links">
            <a href="#" id="cart-checkout"><?php _e('Pagar', 'fronteracomputer'); ?></a>
            <a href="#" id="cart-details"><?php _e('Detalles', 'fronteracomputer'); ?></a>
          </span>
        </div>
      </div>
