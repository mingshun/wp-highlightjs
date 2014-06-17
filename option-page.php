<?php
/**
 * Plugin options page.
 *
 * @since 1.0
 */

require_once('style-list.php');

define('HLJS_OPTION_SLUG', 'wp-highlightjs');
define('HLJS_SETTINGS', 'wp-highlightjs-settings');

/**
 * Create option menu.
 *
 * @since 1.0
 */
function hljs_create_option_menu() {
  $page_hook_suffix = add_submenu_page(
    'options-general.php',
    'wp-highlightjs 设置',
    'wp-highlightjs 设置',
    'administrator',
    HLJS_OPTION_SLUG,
    'hljs_settings_page'
  );

  add_action('load-' . $page_hook_suffix, 'hljs_admin_styles');
  add_action('admin_print_scripts-' . $page_hook_suffix, 'hljs_admin_scripts');

  add_action('admin_init', 'hljs_register_settings');
}
add_action('admin_menu', 'hljs_create_option_menu');

/**
 * Register plugin settings.
 *
 * @since 1.0
 */
function hljs_register_settings() {
  register_setting(HLJS_SETTINGS, HLJS_SETTINGS);

  if (!get_option(HLJS_SETTINGS)) {
    $settings = hljs_get_default_settings();
    add_option(HLJS_SETTINGS, $settings);
  }
}

/**
 * Return default plugin settings.
 *
 * @since 1.0
 */
function hljs_get_default_settings() {
  $settings = array(
    'style'           => 'default',
    'enable_on_home'  => false,
    'enable_on_page'  => true,
    'enable_on_post'  => true
  );
  return $settings;
}

/**
 * Load stylesheets of setting page.
 *
 * @since 1.0
 */
function hljs_admin_styles() {
  $settings = get_option(HLJS_SETTINGS);
  $current_style = $settings['style'] ? $settings['style'] : 'default';
  global $wp_styles;

  $parent_handle = 'wp-highlightjs-style';
  wp_register_style($parent_handle, plugins_url('wp-lighlightjs.css', __FILE__), array(), '1.0.0', 'all');
  wp_enqueue_style($parent_handle);

  $hljs_style_list = hljs_get_style_list();
  foreach ($hljs_style_list as $key => $value) {
    $url = plugins_url('styles/' . $value . '.css', __FILE__);
    $handle = $parent_handle . '-' . $value;
    wp_enqueue_style($handle, $url, array($parent_handle), '8.0', 'all');
    $wp_styles->add_data($handle, 'title', $value);
    if ($value != $current_style) {
      $wp_styles->add_data($handle, 'alt', true);
    }
  }
  wp_enqueue_style('wp-highlightjs-admin-style', plugins_url('wp-lighlightjs-admin.css', __FILE__), array(), '1.0.0', 'all');
}

/**
 * Load javascripts of setting page.
 *
 * @since 1.0
 */
function hljs_admin_scripts() {
  wp_register_script('highlight-pack', plugins_url('highlight.pack.js', __FILE__), array(), '8.0', 'all');
  wp_enqueue_script('highlight-pack');

  wp_enqueue_script('wp-highlightjs-script', plugins_url('wp-lighlightjs.js', __FILE__), array('highlight-pack'), '1.0.0', 'all');
  wp_enqueue_script('wp-highlightjs-admin-script', plugins_url('wp-lighlightjs-admin.js', __FILE__), array(), '1.0.0', 'all');
}

function hljs_settings_page() {
  $settings = get_option(HLJS_SETTINGS);
  $enable_on_home = isset($settings['enable_on_home']) ? $settings['enable_on_home'] : false;
  $enable_on_page = isset($settings['enable_on_page']) ? $settings['enable_on_page'] : false;
  $enable_on_post = isset($settings['enable_on_post']) ? $settings['enable_on_post'] : false;
?>
<a name="top"></a>
<div class="wrap">
<h2>wp-highlightjs 设置</h2>
<form method="post" action="options.php">
  <?php settings_fields(HLJS_SETTINGS); ?>
  <h3>在下列位置启用 highlight.js</h3>
  <fieldset>
    <label class="hljs_enable_item <?php echo $enable_on_home ? 'selected' : ''; ?>">
      <input name="<?php echo HLJS_SETTINGS; ?>[enable_on_home]" type="checkbox" value="<?php echo $enable_on_home ? 'true' : 'false'; ?>" <?php echo $enable_on_home ? 'checked' : ''; ?>>
      <span>主页</span>
    </label>
    <label class="hljs_enable_item <?php echo $enable_on_page ? 'selected' : ''; ?>">
      <input name="<?php echo HLJS_SETTINGS; ?>[enable_on_page]" type="checkbox" value="<?php echo $enable_on_page ? 'true' : 'false'; ?>" <?php echo $enable_on_page ? 'checked' : ''; ?>>
      <span>页面</span>
    </label>
    <label class="hljs_enable_item <?php echo $enable_on_post ? 'selected' : ''; ?>">
      <input name="<?php echo HLJS_SETTINGS; ?>[enable_on_post]" type="checkbox" value="<?php echo $enable_on_post ? 'true' : 'false'; ?>" <?php echo $enable_on_post ? 'checked' : ''; ?>>
      <span>文章</span>
    </label>
  </fieldset>
  <h3>代码高亮显示配色方案</h3>
  <fieldset id="hljs-style-picker">
<?php
    $current_style = $settings['style'] ? $settings['style'] : 'default';
    $hljs_style_list = hljs_get_style_list();
    foreach ($hljs_style_list as $key => $value):
      $labelClass = 'hljs-style-item ' . ($current_style == $value ? 'selected' : '');
      $inputName = HLJS_SETTINGS . '[style]';
      $inputId = "hljs_style_$value";
      $inputChecked = $current_style == $value ? 'checked="checked"' : '';
?>
    <label title="<?php echo $value; ?>" class="<?php echo $labelClass; ?>">
      <input name="<?php echo $inputName; ?>" id="<?php echo $inputId; ?>" type="radio" value="<?php echo $value; ?>" class="tog" <?php echo $inputChecked ?>/>
      <span><?php echo $key; ?></span>
    </label>
<?php
    endforeach;
?>
  </fieldset>
  <h3>样式预览</h3>
  <pre>
<code class="lineNumbers">@requires_authorization
def somefunc(param1='', param2=0):
    r'''A docstring'''
    if param1 &gt; param2: # interesting
        print 'Gre\'ater'
    return (param2 - param1 + 1 + 0b10l) or None

class SomeClass:
    pass

&gt;&gt;&gt; message = '''interpreter
... prompt'''</code>
  </pre>
  <p class="submit">
    <input type="submit" name="submit" id="submit" class="button button-primary" value="保存更改" />
  </p>
</form>
</div>
<?php
}
?>