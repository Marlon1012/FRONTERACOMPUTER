<?php
/**
 * Theme functions for FronteraComputer
 */

if (!defined('FRONTERA_VERSION')) {
    define('FRONTERA_VERSION', '1.0.0');
}

add_action('after_setup_theme', function(){
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ]);
    add_theme_support('menus');
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Menú principal', 'fronteracomputer'),
    ]);
});

add_action('wp_enqueue_scripts', function(){
    // Encolar estilos
    wp_enqueue_style('frontera-style', get_template_directory_uri() . '/assets/css/style.css', [], FRONTERA_VERSION);

    // Encolar scripts
    // Usar jQuery de WordPress
    wp_enqueue_script('jquery');

    // Scripts legacy; intentar mantener compatibilidad con jQuery 3 usando noConflict y wrappers
    wp_enqueue_script('frontera-jquery-slide', get_template_directory_uri() . '/assets/js/jquery.slide.js', ['jquery'], FRONTERA_VERSION, true);
    wp_enqueue_script('frontera-jquery-func', get_template_directory_uri() . '/assets/js/jquery-func.js', ['jquery'], FRONTERA_VERSION, true);
});

// Pasar variables PHP->JS si fuese necesario
add_action('wp_enqueue_scripts', function(){
    $vars = [
        'themeUrl' => get_template_directory_uri(),
        'homeUrl'  => home_url('/'),
        'ajaxUrl'  => admin_url('admin-ajax.php'),
    ];
    wp_localize_script('frontera-jquery-func', 'FRONTERA', $vars);
}, 20);

// Helper para imprimir clases en <body>
function frontera_body_classes($classes){
    $classes[] = 'frontera';
    return $classes;
}
add_filter('body_class', 'frontera_body_classes');

// Desregistrar versiones viejas de jQuery si se intentase añadir manualmente
add_action('wp_enqueue_scripts', function(){
    // No se hace nada aquí; recordatorio para evitar incluir jQuery 1.4.1 del proyecto original.
}, 100);
