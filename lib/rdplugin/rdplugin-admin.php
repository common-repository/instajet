<?php
if (!class_exists('RDPlugin_Admin')):

  /**
   * Main admin class for the plugin
   */
  class RDPlugin_Admin {

    /**
     *
     * @var object A plugin object
     */
    protected $plugin;

    /**
     * 
     * @var array A collection of admin pages
     */
    protected $sections = array();

    /**
     *
     * @var array An array of notices to output 
     */
    protected $notices = array();
    
    /**
     *
     * @var string A hook automatically added by WP when the plugin's admin is loaded
     */
    public $hook;
    
    /**
     * @var array An array of contextual help tabs, indexed by screen
     */
    public $help = array();

    /**
     * Constructor
     */
    public function __construct($plugin) {
      $this->plugin = $plugin;
      
      $this->add_hooks();
    }

    /**
     * Add hooks and filters
     */
    protected function add_hooks() {
      add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets'));
      add_action('admin_menu', array($this, 'admin_menu'));
      add_action('admin_enqueue_scripts', array($this, 'admin_scripts'), 25);
      add_action('customize_register', array($this, 'customize_register'));
      add_action('admin_init', array($this, 'admin_init'));
      add_action('admin_notices', array($this, 'admin_notices'), 10);
    }

    /**
     * Adds widgets to the WP Dashboard
     */
    public function add_dashboard_widgets() {
      
    }

    /**
     * Stub to admin menu
     */
    public function admin_menu() {
      
    }

    /**
     * Hook to admin init 
     */
    public function admin_init() {
      $this->options_init();
      $this->register_license();
      $this->activate_license();
    }

    /**
     * Register the license settings field
     */
    public function register_license() {
      register_setting('instajet_license_key', 'instajet_license_key', array($this, 'sanitize_license'));
    }

    /**
     * Sanitizes the license
     * 
     * @param string $new
     * @return string
     */
    public function sanitize_license($new) {
      $new = trim($new);
      $old = get_option('instajet_license_key');
      if ($old && $old != $new) {
        delete_option('instajet_license_status');
      }
      return $new;
    }

    /**
     * license check and activation
     * 
     * @return boolean false on error
     */
    public function activate_license() {
		
		$license = trim($_POST['instajet_license_key']);
		$old = get_option("instajet_license_key");
				
		if(!isset($_POST['instajet_license_key']) || $license == $old) return false;
		
		update_option("instajet_license_key", $license);
		
		if(empty($license)) return false;
				
		$fields = array(
			'method' => 'activate_license'
		);
		
		$response = ij_get_api_json($fields);
		$rspns = json_decode($response);
		
		update_option('instajet_license_status', $rspns->license);
		/*
		if (isset($_POST['instajet_license_key'])) {

			$license = trim($_POST['instajet_license_key']);
			$old = get_option("instajet_license_key");
			
			if($license == $old) return false;

			$api_params = array(
				'edd_action' => 'activate_license',
				'license' => $license,
				'item_name' => urlencode($this->plugin->plugin_name),
				'url' => home_url()
			);
	
			$response = wp_remote_post(ij_api_url(), array(
				'timeout' => 15,
				'sslverify' => false,
				'body' => $api_params
			));
			
			if (is_wp_error($response)) {
			  $this->notices[] = array(
				  'class' => 'error',
				  'message' => 'Failed to activate license',
				  'capability' => 'manage_options'
			  );
			  return false;
			}
			
			$rspns = wp_remote_retrieve_body($response);			
			$rspns = json_decode($rspns);
			
			if (!$rspns->success) {
			  $this->notices[] = array(
				  'class' => 'error',
				  'message' => 'Failed to activate license',
				  'capability' => 'manage_options'
			  );
			  return false;
			} else {
			  update_option("instajet_license_key", $license);
			  update_option('instajet_license_status', $rspns->license);
			  $this->notices[] = array(
				  'class' => 'success',
				  'message' => 'License activated',
				  'capability' => 'manage_options'
			  );
			}
		}
		*/
    }

    /**
     * Output a settings page for the plugin
     */
    public function settings_page() {
      if (isset($_GET['settings-updated'])) {
        $this->notices[] = array(
            'class' => 'updated',
            'message' => $this->plugin->plugin_name . ' options updated',
            'capability' => 'manage_options'
        );
      }
      $active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general_settings';
      ?>
      <div class="wrap">
        <div id="icon-tools" class="icon32"></div><h2><?php _e($this->plugin->plugin_name . ' Settings', 'instajet'); ?></h2>

        <!--<h2 class="nav-tab-wrapper">
          <?php foreach ($this->sections as $section => $value) : ?>
            <a href="?page=<?php echo $this->plugin->slug; ?>_options&tab=<?php echo $value['slug']; ?>" class="nav-tab <?php echo ($active_tab == $value['slug']) ? 'nav-tab-active' : ''; ?>"><?php echo $value['title_i18n']; ?></a>
          <?php endforeach; ?>  
        </h2>-->
        <form method="post" action="options.php">
          <?php settings_fields('instajet_options'); ?>

          <?php do_settings_sections('instajet_options'); ?>

          <?php submit_button('Save Changes'); ?>
        </form>
      </div>
      <?php
    }

    /**
     * Register settings sections
     */
    public function options_init() {

      register_setting('instajet_options', 'instajet_options', array($this, 'validate_options'));
	  
	  $options = get_option('instajet_options');
	  if(empty($options['instajet_dashboard'])) $options['instajet_dashboard'] = 1;
	  
      $active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general_settings';

      foreach ($this->sections as $section => $value) {
        if ($value['slug'] == $active_tab) {
          add_settings_section(
                  'section-' . $value['slug'], $value['title_i18n'], array($this, 'section_description'), 'instajet_options'
          );

          foreach ($value['fields'] as $field) {
            add_settings_field(
                    $field['slug'], $field['label_i18n'], array($this, 'settings_field_callback'), 'instajet_options', 'section-' . $value['slug'], $field
            );
          }
        }
      }
    }

    /**
     * Output the description for each settings field
     */
    function section_description() {
		
		$statuses = array(
			"site_inactive" => "This domain is not authorised to use this license key.",
			"inactive" => "This license has not yet been activated or has been deactivated.",
			"invalid" => "This license is invalid or has expired.",
			"disabled" => "This license has been disabled.",
		);
				
		$active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general_settings';
		foreach ($this->sections as $section => $value) {
		  if ($value['slug'] == $active_tab) :
			?>
			<p><?php echo $value['description_i18n']; ?></p>
			<?php
		  endif;
		}
		
		$fields = array(
			'method' => 'get_usage'
		);
		
		$return = (array) ij_get_api_json($fields);
				
		if($return['message']=="200 OK"){
			echo "<p><strong class='".($return['data'][0] < $return['data'][1] ? "positive" : "negative")."'>Used ".$return['data'][0]." of ".$return['data'][1]." API calls this month</strong>. You are on the <strong>".$return['data'][2]."</strong> plan.</p>";
			if(isset($statuses[$return['data'][3]])) echo "<p><strong>".$statuses[$return['data'][3]]."</strong></p>";
			echo "<p><a target='_blank' href='http://instajet.co.uk/'>Instajet Website</a> | <a target='_blank' href='http://instajet.co.uk/contact-us/'>Contact Instajet</a>.</p>";
		} else {
			echo "<p>Could not fetch usage statistics.</p>";	
		}
		
    }

    /**
     * Dispatches the correct type of field
     */
    public function settings_field_callback($field) {
      $options = get_option('instajet_options');
	  $options['instajet_license_key'] = get_option('instajet_license_key');
      switch ($field['type']) {
        case 'text':
          $this->text_field($field, $options);
          break;
        case 'license':
          echo $this->license_field($field, $options);
          break;
		case 'select':
          echo $this->select_field($field, $options);
          break;
		case 'checkbox':
          echo $this->check_box($field, $options);
          break;
      }
    }

    /**
     * Outputs the markup for a text field
     * 
     * @param array $field
     * @param array $options
     */
    public function text_field($field, $options) {
      $value = (isset($options[$field['slug']])) ? $options[$field['slug']] : $field['default'];
      $output = '<input id="' . $field['slug'] . '" name="' . 'instajet_options[' . $field["slug"] . ']" size="40" type="text" value="' . $value . '" />';
      if (isset($field['description_118n']) && strlen($field['description_i18n']) > 0) {
        $output .= '<p class="description">' . $field['description_i18n'] . '</p>';
      }
      echo $output;
    }
	
	/**
     * Outputs the markup for a checkbox
     * 
     * @param array $field
     * @param array $options
     */
    public function check_box($field, $options) {
      $value = (isset($options[$field['slug']])) ? $options[$field['slug']] : $field['default'];
      $output = '<input id="' . $field['slug'] . '" name="' . 'instajet_options[' . $field["slug"] . ']" ' . ($value==1 ? 'checked' : '') . ' type="checkbox" value="1" />';
      if (isset($field['description']) && strlen($field['description']) > 0) {
        $output .= '<p class="description">' . $field['description'] . '</p>';
      }
      echo $output;
    }
	
	/**
     * Outputs the markup for a select field
     * 
     * @param array $field
     * @param array $options
     */
    public function select_field($field, $options) {
      $value = (isset($options[$field['slug']])) ? $options[$field['slug']] : '';
      $output = '<select id="' . $field['slug'] . '" name="' . 'instajet_options[' . $field["slug"] . ']">';
	  foreach($field['options'] as $option => $v){
	  	$output .= '<option value="'.$v.'"';
		if($v==$value) $output .= ' selected';
		$output .= '>'.$option.'</option>';
	  }
	  $output .= '</select>';
      if (!empty($field['description'])) {
        $output .= '<p class="description">' . $field['description'] . '</p>';
      }
      echo $output;
    }

    /**
     * Outputs a license field
     * 
     * @param array $field
     * @param array $options
     */
    public function license_field($field, $options) {
      $value = (isset($options[$field['slug']])) ? $options[$field['slug']] : '';
      $output = '<input id="' . 'instajet_license_key" name="' . 'instajet_license_key" size="40" type="text" value="' . $value . '" />';
      if ('instajet_license_status' !== false && 'instajet_license_status' == 'valid') :
        $output .= '<span style="color:green;">' . _e('active') . '</span>';
      else :
        wp_nonce_field('instajet_license_nonce', 'instajet_license_nonce');
      endif;
      //$output .= '<input type="submit" class="button-secondary" name="' . 'instajet_license_activate" value="' . __('Activate', 'rdplugin') . '"/>';
      if (isset($field['slug']) && strlen($field['slug']) > 0) {
        $output .= '<p class="description">' . $field['description'] . '</p>';
      }
      return $output;
    }
    
    /**
     * Outputs any contextual help tabs for the plugin
     */
    public function contextual_help() {
      $screen = get_current_screen();
      if(isset($this->help[$this->hook]) && $screen->id == $this->hook) {
        $screen->add_help_tab($this->help[$this->hook]);
      }
    }

    /**
     * Output any CSS and JS for the admin
     */
    public function admin_scripts() {
      
    }

    /**
     * Loops through admin notices, displaying those appropriate for the level of the current user
     */
    public function admin_notices() {
      foreach ($this->notices as $notice) {
        if (current_user_can($notice['capability'])) :
          ?>
          <div class="<?php echo $notice['class']; ?>">
            <p><?php echo $notice['message']; ?></p>
          </div>
          <?php
        endif;
      }
    }

    /**
     * Hook to the Customize screen
     * 
     * @param object $wp_customize
     */
    public function customize_register($wp_customize) {
      
    }

    /**
     * Stub for validation of options
     * 
     * @param array $options
     * @return array
     */
    public function validate_options($options) {

      return $options;
    }

  }



endif;