<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (!class_exists('RDPlugin')) :

  /**
   * A base singleton class for plugins to inherit
   * 
   * @package RDPlugin
   * @copyright (c) 2014, Chris Cox
   * @author Chris Cox <chris@renaissance-design.net>
   * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
   * @version 0.3
   * 
   * This class takes care of all the standard things a plugin might be called to do, 
   * for example registering activation and deactivation hooks and maintaining singleton uniqueness. 
   * This allows individual plugins to contain little to no duplication of boilerplate code.
   */
  class RDPlugin {

    /**
     *
     * @var array $_instances Instances of RDPlugin objects
     */
    protected static $_instances = null;

    /**
     *
     * @var string $plugin_file The main file of the plugin 
     */
    public static $plugin_file = null;

    /**
     *
     * @var string $plugin_url
     */
    public static $plugin_url = null;

    /**
     *
     * @var string the filesystem path of the plugin
     */
    public static $plugin_path = null;

    /**
     *
     * @var string The name of the plugin
     */
    public $plugin_name = null;

    /**
     *
     * @var string The slug of the plugin
     */
    public $slug = null;

    /**
     *
     * @var array The default data
     */
    public $defaults = array();

    /**
     *
     * @var array Any plugins which this plugin relies upon to run
     */
    protected $dependencies = null;

    /**
     *
     * @var string The URL of a third-party API to update from
     */
    protected $plugin_store = null;

    /**
     *
     * @var string the licence key 
     */
    public $licence_key = null;

    /**
     *
     * @var string the licence status 
     */
    public $licence_status = null;

    /**
     *
     * @var array Common frontend/admin includes
     */
    protected $common_includes = null;

    /**
     *
     * @var array Includes for the frontend
     */
    protected $frontend_includes = null;

    /**
     *
     * @var array Admin-only includes
     */
    protected $admin_includes = null;

    /**
     *
     * @var object An instance of RDPlugin_Admin
     */
    protected $admin = null;

    /**
     *
     * @var object An instance of RDPlugin_Error
     */
    protected $error = null;
    
    /**
     *
     * @var object An instance of RDPlugin_Options
     */
    // public $options = null;
    
    /**
     *
     * @var array an array of templates used by the plugin
     */
    public $templates = null;

    /**
     *
     * @var string The version of the plugin
     */
    public $version = null;
    
    /**
     *
     * @var string The version of the plugin's database schema 
     */
    public $dbversion = null;
    

    
    
    /**
     * Enforce singleton
     * 
     * This method can be inherited by child objects
     * 
     * @return object $_instance
     */
    public static function get_instance() {
      $c = get_called_class();
      if (!isset(self::$_instances[$c])) {
        $args = func_get_args();
        $reflection_object = new ReflectionClass($c);
        self::$_instances[$c] = $reflection_object->newInstanceArgs($args);
      }
      return self::$_instances[$c];
    }

    /**
     * Exception handling for bad OO
     * 
     * @throws Exception
     */
    public function __clone() {
      throw new Exception('You cannot clone a singleton.');
    }

    /**
     * Constructor
     * 
     * Set up all required hooks
     * 
     * @access public
     * @return object instajet
     */
    public function __construct($plugin_name) {
      $c = get_class($this);
      if (isset(self::$_instances[$c])) {
        throw new Exception('You cannot create more than one copy of a singleton.');
      }
      else {
        self::$_instances[$c] = $this;
      }
      $this->slug = trim(strtolower($plugin_name));
      $this->plugin_file = $this->get_plugin_file();
      $this->plugin_url = $this->get_plugin_url();
      $this->plugin_path = $this->get_plugin_path();
      $this->licence_key = $this->get_licence_key();
      $this->licence_status = $this->get_licence_status();
      if (is_admin()) {
        require_once($this->plugin_path . 'lib/rdplugin/rdplugin-admin.php');
        $this->admin = new RDPlugin_Admin($this);
      }
      require_once($this->plugin_path . 'lib/rdplugin/rdplugin-meta.php');
      // require_once($this->plugin_path . 'lib/rdplugin/rdplugin-options.php');
      // $this->options = new RDPlugin_Options($this->slug);
      
      $this->rdincludes();

      $this->resolve_dependencies($this->dependencies);

      $this->add_hooks();
    }

    /**
     * Add hooks and filters
     */
    protected function add_hooks() {
      add_action('plugins_loaded', array($this, 'plugins_loaded'));
      add_action('init', array($this, 'init'));
      add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'), 25);
      add_action('admin_init', array($this, 'admin_init'));
      register_activation_hook(__FILE__, array($this, 'activate'));
      register_deactivation_hook(__FILE__, array($this, 'deactivate'));
      register_uninstall_hook(__FILE__, array('RDPlugin', 'uninstall'));
      // add_filter('template_include', array($this, 'template_loader'));
    }

    /**
     * Activation hook
     * 
     * Performs actions on plugin activation
     */
    public static function activate() {
      update_option($this->slug . '_options', $this->defaults);
      flush_rewrite_rules();
    }

    /**
     * Deactivation hook
     * 
     * Performs actions on plugin deactivation
     */
    public static function deactivate() {   
      flush_rewrite_rules();
    }

    /**
     * Installation events go here
     */
    public static function install() {
      
    }

    /**
     * Uninstall hook
     * 
     * Performs actions on plugin uninstall
     */
    public static function uninstall() {
      delete_option($this->slug . '_options');
      delete_option($this->slug . '_license_key');
      delete_option($this->slug . '_license_status');
      flush_rewrite_rules();
    }

    /**
     * Updates plugin from 3rd-party API after licence check
     */
    public function update() {
	  return;
      //include_once($this->plugin_path . 'lib/rdplugin/rdplugin-updater.php');
      $plugin_data = get_plugin_data($this->plugin_file, false);
      $edd_updater = new EDD_SL_Plugin_Updater($this->plugin_store, $this->plugin_file, array(
          'version' => $plugin_data['Version'],
          'license' => $this->licence_key,
          'item_name' => $plugin_data['Name'],
          'author' => $plugin_data['Author'],
          'url' => home_url()
          )
      );
    }
    
    public function update_db() {
      
    }

    /**
     * Hook to plugins_loaded
     */
    public function plugins_loaded() {
      load_plugin_textdomain($this->slug, false, $this->plugin_path . 'lang');
      $this->update_db();
    }

    /**
     * Hook to init
     */
    public function init() {
      
    }

    /**
     *  Hook to admin_init
     */
    public function admin_init() {
      $this->update();
    }

    /**
     * Include all necessary libraries and classes
     */
    protected function rdincludes() {
      $this->do_includes($this->common_includes);
      if (is_admin()) {
        $this->do_includes($this->admin_includes);
      }
      else {
        $this->do_includes($this->frontend_includes);
      }
    }

    /**
     * Does the heavy lifting of including classes and libraries
     * 
     * @param array $includes An array of key/value pairs containing the relative path to the file and boolean for whether to require
     */
    protected function do_includes($includes) {
      if (is_array($includes)) {
        foreach ($includes as $key => $value) {
          if ($value == true) {
            require_once($this->plugin_path . 'lib/' . $key);
          }
          else {
            include_once($this->plugin_path . 'lib/' . $key);
          }
        }
      }
      else {
        return false;
      }
    }

    /**
     * Resolve any plugin dependencies
     * 
     * Installs any dependencies from the $deps array
     * 
     * @param array/null $deps
     */
    public function resolve_dependencies($deps) {

      if (is_array($deps)) {
        include_once($this->plugin_path . '/lib/rdplugin/rdplugin-dependencies.php');
        $config = array(
            'default_path' => '', // Default absolute path to pre-packaged plugins.
            'menu' => 'tgmpa-install-plugins', // Menu slug.
            'has_notices' => true, // Show admin notices or not.
            'dismissable' => true, // If false, a user cannot dismiss the nag message.
            'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false, // Automatically activate plugins after installation or not.
            'message' => '', // Message to output right before the plugins table.
            'strings' => array(
                'page_title' => __('Install Required Plugins', 'tgmpa'),
                'menu_title' => __('Install Plugins', 'tgmpa'),
                'installing' => __('Installing Plugin: %s', 'tgmpa'), // %s = plugin name.
                'oops' => __('Something went wrong with the plugin API.', 'tgmpa'),
                'notice_can_install_required' => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.'), // %1$s = plugin name(s).
                'notice_can_install_recommended' => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.'), // %1$s = plugin name(s).
                'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.'), // %1$s = plugin name(s).
                'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.'), // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.'), // %1$s = plugin name(s).
                'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.'), // %1$s = plugin name(s).
                'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.'), // %1$s = plugin name(s).
                'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.'), // %1$s = plugin name(s).
                'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins'),
                'activate_link' => _n_noop('Begin activating plugin', 'Begin activating plugins'),
                'return' => __('Return to Required Plugins Installer', 'tgmpa'),
                'plugin_activated' => __('Plugin activated successfully.', 'tgmpa'),
                'complete' => __('All plugins installed and activated successfully. %s', 'tgmpa'), // %s = dashboard link.
                'nag_type' => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            )
        );
        tgmpa($deps, $config);
      }
    }

    /**
     * Get the plugin dir.
     * 
     * @return string
     */
    public function get_plugin_url() {
      return trailingslashit(plugin_dir_url(__DIR__));
    }

    /**
     * Get the plugin path.
     * 
     * @return string
     */
    public function get_plugin_path() {
      return plugin_dir_path(__DIR__);
    }

    /**
     * Get the main plugin file
     * 
     * @return string
     */
    public function get_plugin_file() {
      return $this->get_plugin_path() . $this->slug . '.php';
    }

    /**
     * Get the template path.
     * 
     * @return string
     */
    public function get_template_path() {
      return apply_filters($this->plugin_dir, 'templates/');
    }

    /**
     * Get Ajax URL.
     * 
     * @return string
     */
    public function ajax_url() {
      return admin_url('admin-ajax.php', 'relative');
    }

    /**
     * Get the licence key
     * @return type
     */
    protected function get_licence_key() {
      return trim(get_option($this->slug . '_licence_key'));
    }

    /**
     * Get the licence status
     * @return type
     */
    protected function get_licence_status() {
      return trim(get_option($this->slug . '_licence_status'));
    }

  }

  

endif;