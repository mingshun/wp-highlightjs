<?php
/*
Plugin Name: highlight.js for WordPress
Plugin URI: https://github.com/mingshun/wp-highlightjs
Description: highlight.js for WordPress as plugin.
Author: mingshun
Author URI: https://github.com/mingshun
Version: 1.0
*/

require_once('option-page.php');

function hljs_style_scripts() {
  $settings = get_option(HLJS_SETTINGS);
  $enable_on_home = isset($settings['enable_on_home']) ? $settings['enable_on_home'] : false;
  $enable_on_page = isset($settings['enable_on_page']) ? $settings['enable_on_page'] : false;
  $enable_on_post = isset($settings['enable_on_post']) ? $settings['enable_on_post'] : false;
  $style = $settings['style'];
  $stylePath = plugins_url('styles/' . $style . '.css', __FILE__);

  $enable = $enable_on_home && is_home() || $enable_on_page && is_page() || $enable_on_post && is_single();
  if ($enable) {
    wp_enqueue_style('wp-highlightjs-style', plugins_url('wp-lighlightjs.css', __FILE__), array(), '1.0.0', 'all');
    wp_enqueue_style('wp-highlightjs-style-' . $style, $stylePath, array('wp-highlightjs-style'), '8.0', 'all');

    wp_enqueue_script('highlight-pack', plugins_url('highlight.pack.js', __FILE__), array(), '8.0', 'all');
    wp_enqueue_script('wp-highlightjs-script', plugins_url('wp-lighlightjs.js', __FILE__), array('highlight-pack', 'jquery'), '1.0.0', 'all');
  }
}
add_action('wp_enqueue_scripts', 'hljs_style_scripts');
?>
