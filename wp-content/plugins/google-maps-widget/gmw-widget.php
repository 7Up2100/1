<?php
/*
 * Maps Widget for Google Maps
 * Widget definition, admin GUI and widget rendering functions
 * (c) Web factory Ltd, 2012 - 2023
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}


// main widget class, extends WP widget interface/class
if (!class_exists('GoogleMapsWidget')) {
class GoogleMapsWidget extends WP_Widget {
  static $widgets = array();
  static $defaults = array('title' => 'Map',
                           'address' => 'New York, USA',
                           'thumb_pin_color' => '#ff0000',
                           'thumb_pin_type' => 'predefined',
                           'thumb_pin_size' => 'default',
                           'thumb_pin_label' => 'A',
                           'thumb_pin_img' => '',
                           'thumb_width' => '250',
                           'thumb_height' => '250',
                           'thumb_type' => 'roadmap',
                           'thumb_zoom' => '13',
                           'thumb_header' => '',
                           'thumb_footer' => '',
                           'thumb_color_scheme' => 'new',
                           'thumb_link_type' => 'lightbox',
                           'thumb_link' => '',
                           'thumb_format' => 'png',
                           'thumb_lang' => 'en',
                           'thumb_powered_by' => '0',
                           'thumb_hide_title' => '0',
                           'lightbox_width' => '550',
                           'lightbox_height' => '550',
                           'lightbox_fullscreen' => '0',
                           'lightbox_mode' => 'place',
                           'lightbox_map_type' => 'roadmap',
                           'lightbox_clustering' => '0',
                           'lightbox_filtering' => '0',
                           'lightbox_zoom' => '14',
                           'lightbox_feature' => array('overlay_close'),
                           'lightbox_skin' => 'light',
                           'lightbox_lang' => 'en',
                           'lightbox_header' => '',
                           'lightbox_footer' => '');


  // constructor - define the widget
  function __construct() {
    $title = __('Maps Widget for Google Maps', 'google-maps-widget');

    $widget_ops = array('classname' => 'google-maps-widget', 'description' => __('Displays a map image thumbnail with a larger map available in a lightbox.', 'google-maps-widget'), 'customize_selective_refresh' => true);
    $control_ops = array('width' => 450, 'height' => 350);
    parent::__construct('GoogleMapsWidget', $title, $widget_ops, $control_ops);

    self::$defaults['title'] = __('Map', 'google-maps-widget');
    self::$defaults['address'] = __('New York, USA', 'google-maps-widget');
  } // GoogleMapsWidget


  // widget edit form HTML
  function form($instance) {
    $options = GMW::get_options();
    $instance = $this->upgrade_wiget_instance($instance);
    extract($instance, EXTR_SKIP);

    $thumb_map_types = array(array('val' => 'hybrid', 'label' => __('Hybrid', 'google-maps-widget')),
                             array('val' => 'roadmap', 'label' => __('Road (default)', 'google-maps-widget')),
                             array('val' => 'satellite', 'label' => __('Satellite', 'google-maps-widget')),
                             array('val' => 'terrain', 'label' => __('Terrain', 'google-maps-widget')),
                             array('val' => '-1', 'label' => __('Custom Static Image (save money on API calls)', 'google-maps-widget')));

    $lightbox_map_types = array(array('val' => 'roadmap', 'label' => __('Road (default)', 'google-maps-widget')),
                                array('val' => 'satellite', 'label' => __('Satellite', 'google-maps-widget')));

    $lightbox_modes = array(array('val' => 'place', 'label' => __('Place (default)', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Directions', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Search', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Street View', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('View (clean map, no markers)', 'google-maps-widget')));

    $thumb_pin_sizes = array(array('val' => 'tiny', 'label' => __('Tiny', 'google-maps-widget')),
                       array('val' => 'small', 'label' => __('Small', 'google-maps-widget')),
                       array('val' => 'mid', 'label' => __('Medium', 'google-maps-widget')),
                       array('val' => 'default', 'label' => __('Large (default)', 'google-maps-widget')));

    $thumb_pin_colors = array(array('val' => '#000000', 'label' => __('Black', 'google-maps-widget')),
                              array('val' => '#0000ff', 'label' => __('Blue', 'google-maps-widget')),
                              array('val' => '#a52a2a', 'label' => __('Brown', 'google-maps-widget')),
                              array('val' => '#808080', 'label' => __('Gray', 'google-maps-widget')),
                              array('val' => '#00ff00', 'label' => __('Green', 'google-maps-widget')),
                              array('val' => '#ffa500', 'label' => __('Orange', 'google-maps-widget')),
                              array('val' => '#800080', 'label' => __('Purple', 'google-maps-widget')),
                              array('val' => '#ff0000', 'label' => __('Red (default)', 'google-maps-widget')),
                              array('val' => '#ffffff', 'label' => __('White', 'google-maps-widget')),
                              array('val' => '#ffff00', 'label' => __('Yellow', 'google-maps-widget')),
                              array('val' => '-1', 'label' => __('PRO offers unlimited color choices', 'google-maps-widget')));

    $pin_labels = array(array('val' => 'A', 'label' => __('A (default)', 'google-maps-widget')),
                        array('val' => '-1', 'label' => __('Choose a custom label by going PRO', 'google-maps-widget')));

    $zoom_levels_thumb = array(array('val' => '0', 'label' => __('0 - entire world', 'google-maps-widget')));
    for ($tmp = 1; $tmp <= 21; $tmp++) {
      if ($tmp == 13) {
        $zoom_levels_thumb[] = array('val' => $tmp, 'label' => $tmp . ' (default)');
      } else {
        $zoom_levels_thumb[] = array('val' => $tmp, 'label' => $tmp);
      }
    }
    $zoom_levels_lightbox = $zoom_levels_thumb;

    $lightbox_sizes = array(array('val' => '0', 'label' => __('Custom size (default)', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Fullscreen', 'google-maps-widget')));

    $lightbox_skins = array(array('val' => 'dark', 'label' => __('Dark', 'google-maps-widget')),
                            array('val' => 'light', 'label' => __('Light (default)', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Blue', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Cartoonish', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Dark rim', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Fancy', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Gears', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Gray squared', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Minimalistic', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Minimalistic #2', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Painting', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Polaroid', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Rounded', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Rounded white', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Shadow', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Simple', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Squared black', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Squared white', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Tablet', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Vintage', 'google-maps-widget')),
                            array('val' => '-1', 'label' => __('Wood', 'google-maps-widget')));

    $thumb_pin_types = array(array('val' => 'predefined', 'label' => __('Predefined by Google (default)', 'google-maps-widget')),
                             array('val' => 'custom', 'label' => __('Custom image', 'google-maps-widget')),
                             array('val' => '-1', 'label' => __('GMW pins library (1500+ pins)', 'google-maps-widget')));

    $thumb_link_types = array(array('val' => 'lightbox', 'label' => __('Interactive map in lightbox (default)', 'google-maps-widget')),
                              array('val' => '-1', 'label' => __('Replace thumb map with an interactive map', 'google-maps-widget')),
                              array('val' => '-1', 'label' => __('Interactive map in a new window', 'google-maps-widget')),
                              array('val' => '-1', 'label' => __('Skip thumbnail map, immediately show interactive one', 'google-maps-widget')),
                              array('val' => 'custom', 'label' => __('Custom URL', 'google-maps-widget')),
                              array('val' => '-1', 'label' => __('Custom URL in a new window', 'google-maps-widget')),
                              array('val' => 'nolink', 'label' => __('Disable link', 'google-maps-widget')));

    $thumb_color_schemes = array(array('val' => 'default', 'label' => __('Default', 'google-maps-widget')),
                                 array('val' => 'new', 'label' => __('Refreshed by Google', 'google-maps-widget')),
                                 array('val' => '-1', 'label' => __('Apple', 'google-maps-widget')),
                                 array('val' => 'blue', 'label' => __('Blue', 'google-maps-widget')),
                                 array('val' => 'bright', 'label' => __('Bright', 'google-maps-widget')),
                                 array('val' => 'gowalla', 'label' => __('Gowalla', 'google-maps-widget')),
                                 array('val' => 'gray', 'label' => __('Gray', 'google-maps-widget')),
                                 array('val' => 'gray2', 'label' => __('Gray #2', 'google-maps-widget')),
                                 array('val' => '-1', 'label' => __('Light', 'google-maps-widget')),
                                 array('val' => 'mapbox', 'label' => __('Mapbox', 'google-maps-widget')),
                                 array('val' => 'midnight', 'label' => __('Midnight', 'google-maps-widget')),
                                 array('val' => 'neon', 'label' => __('Neon', 'google-maps-widget')),
                                 array('val' => '-1', 'label' => __('Pale', 'google-maps-widget')),
                                 array('val' => 'paper', 'label' => __('Paper', 'google-maps-widget')),
                                 array('val' => 'ultra_light', 'label' => __('Ultra Light', 'google-maps-widget')),
                                 array('val' => '-1', 'label' => __('Custom scheme to match your site', 'google-maps-widget')));

    $thumb_formats = array(array('val' => 'png', 'label' => __('PNG 8-bit (default)', 'google-maps-widget')),
                           array('val' => '-1', 'label' => __('Add 4 more image formats', 'google-maps-widget')));

    $thumb_langs = array(array('val' => 'en', 'label' => 'English'),
                         array('val' => '-1', 'label' => __('Add auto-detection and 50 more languages', 'google-maps-widget')));

    $thumb_powered_bys = array(array('val' => '0', 'label' => __('No, I do not want to do that', 'google-maps-widget')),
                         array('val' => '1', 'label' => __('Please, let us show a tiny link below the map so that others can learn about GMW too', 'google-maps-widget')));

    $hide_titles = array(array('val' => '0', 'label' => __('No, show the widget title (default)', 'google-maps-widget')),
                         array('val' => '1', 'label' => __('Yes, hide the widget title', 'google-maps-widget')));

    $lightbox_langs = array(array('val' => 'en', 'label' => 'English'),
                            array('val' => '-1', 'label' => __('Add auto-detection and 50 more languages', 'google-maps-widget')));

    $lightbox_features = array(array('val' => 'title', 'label' => __('Show map title', 'google-maps-widget')),
                               array('val' => 'overlay_close', 'label' => __('Close on overlay click', 'google-maps-widget')),
                               array('val' => '-1', 'disabled' => true, 'label' => __('Show close button (upgrade to PRO)', 'google-maps-widget')),
                               array('val' => '-1', 'disabled' => true, 'label' => __('Close on Esc key (upgrade to PRO)', 'google-maps-widget')));

    $lightbox_units = array(array('val' => 'auto', 'label' => __('Detect automatically', 'google-maps-widget')),
                            array('val' => 'imperial', 'label' => __('Imperial', 'google-maps-widget')),
                            array('val' => 'metric', 'label' => __('Metric', 'google-maps-widget')));

    $lightbox_clustering_options = array(array('val' => '0', 'label' => __('Disabled (default)', 'google-maps-widget')),
                                         array('val' => '-1', 'label' => __('Small cluster radius', 'google-maps-widget')),
                                         array('val' => '-1', 'label' => __('Medium cluster radius', 'google-maps-widget')),
                                         array('val' => '-1', 'label' => __('Large cluster radius', 'google-maps-widget')));

    $lightbox_filtering_options = array(array('val' => '0', 'label' => __('Disabled (default)', 'google-maps-widget')),
                                        array('val' => '-1', 'label' => __('Enabled', 'google-maps-widget')));

    // force update if activated
    if (GMW::is_activated()) {
      echo '<p>Maps Widget for Google Maps <b class="gmw-pro-red">PRO</b> has been activated!';
      echo '<ol class="normal">
      <li><a href="https://gmapswidget.com/pro-download/" target="_blank">Download</a> the PRO version ZIP file</li>
      <li>Go to <a href="' . esc_url(admin_url('plugin-install.php')) . '">Plugins - Add New</a> and install the PRO version</li>
      <li>When prompted, overwrite the free version with the PRO one</li>
      <li>Create some maps ;)</li>
    </ol>';
      echo '<style type="text/css"> #widget-' . esc_attr($this->id) . '-savewidget { display: none; } </style>';
      echo '<input id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" type="hidden" value="' . esc_attr($title) . '">';

      return true;
    } // is_activated

    // upgrade ad
    if (!GMW::is_activated()) {
      echo '<p class="gmw-upgrade-notice-widget">Grab our <a class="open_promo_dialog" href="#">limited offer</a>: a <b>one time payment</b>, lifetime license for <b>only $39</b>! No recurring charges! <a class="open_promo_dialog" href="#">Upgrade now</a> to <span class="gmw-pro-red">PRO</span> &amp; get more than 50 extra features.</p>';
    }

    // api key warning
    if (!GMW::get_api_key('test')) {
      echo '<p class="gmw-api-key-error"><b>Important!</b> ';
      echo 'Please go to <a href="' . esc_url(admin_url('options-general.php?page=gmw_options')) . '" title="Maps Widget for Google Maps settings">settings</a> &amp; follow simple instructions to get a <b>free Google Maps API key</b>. It only takes a minute. Without a key your <b>maps will NOT work</b>. This rule is enforced by Google. Sorry for the inconvenience.</p>';
    }

    // widget options markup
    // title & address
    echo '<p><label for="' . esc_attr($this->get_field_id('title')) . '">' . esc_html__('Title', 'google-maps-widget') . ':</label>';
    echo '<input data-tooltip="Widget title styled as defined in the active theme. HTML tags and shortcodes are not supported. Title is optional." class="widefat" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" placeholder="' . esc_html__('Optional Map title', 'google-maps-widget') . '" type="text" value="' . esc_attr($title) . '">';
    echo '</p>';
    echo '<label for="' . esc_attr($this->get_field_id('address')) . '">' . esc_html__('Address', 'google-maps-widget') . ':</label>';
    echo '<div class="input-address-group">';
    echo '<input name="' . esc_attr($this->get_field_name('address')) . '" type="text" value="' . esc_attr($address) . '" required="required" class="widefat" id="' . esc_attr($this->get_field_id('address')) . '" placeholder="' . esc_html__('Address / location to show', 'google-maps-widget') . '" data-tooltip="' . esc_html('Address or location shown on both maps. Coordinates can be used as well. Write them in a numerical fashion, not in degrees, ie: 40.70823, -74.01052.') . '">';
    echo '<a data-target="address" href="#" class="button-secondary gmw-pick-address"><span class="dashicons dashicons-location"></span></a>';
    echo '</div>';
    // end - title & address

    // tabs
    echo '<div class="gmw-tabs" id="tab-' . esc_attr($this->id) . '"><ul>';
    echo '<li><a href="#gmw-thumb">' . esc_html__('Thumbnail Map', 'google-maps-widget') . '</a></li>';
    echo '<li><a href="#gmw-lightbox">' . esc_html__('Interactive Map', 'google-maps-widget') . '</a></li>';
    echo '<li><a href="#gmw-pins">' . esc_html__('Pins', 'google-maps-widget') . '</a></li>';
    echo '<li><a href="#gmw-shortcode">' . esc_html__('Shortcode', 'google-maps-widget') . '</a></li>';
    echo '<li><a href="#gmw-info">' . esc_html__('Info &amp; Support', 'google-maps-widget') . '</a></li>';
    echo '</ul>';

    // thumb tab
    echo '<div id="gmw-thumb">';
    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_width')) . '">' . esc_html__('Map Size', 'google-maps-widget') . ':</label>';
    echo '<input data-title="Map Width" data-tooltip="Map width in pixels; from 50 to 640. The size limit is imposed by Google. Image may be resized by the theme if the sidebar is narrower." min="50" max="640" step="1" class="small-text" id="' . esc_attr($this->get_field_id('thumb_width')) . '" name="' . esc_attr($this->get_field_name('thumb_width')) . '" type="number" value="' . esc_attr($thumb_width) . '" required="required"> x ';
    echo '<input data-title="Map Height" data-tooltip="Map height in pixels; from 50 to 640. The size limit is imposed by Google." min="50" max="640" step="1" class="small-text" id="' . esc_attr($this->get_field_id('thumb_height')) . '" name="' . esc_attr($this->get_field_name('thumb_height')) . '" type="number" value="' . esc_attr($thumb_height) . '" required="required">';
    echo ' px</p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_type')) . '">' . esc_html__('Map Type', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Controls the map layers shown. Roadmap is the most popular, hybrid combines road and satellite while terrain shows physical relief map image, displaying terrain and vegetation.<br>Custom Static Image displays an image of your choosing in order to <b>save money</b> on Google Maps API calls. When clicked it opens the lightbox map." id="' . esc_attr($this->get_field_id('thumb_type')) . '" name="' . esc_attr($this->get_field_name('thumb_type')) . '">';
    GMW::create_select_options($thumb_map_types, $thumb_type);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_color_scheme')) . '">' . esc_html__('Color Scheme', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Changes the overall appearance of the map. Please note that most visitors are acustomed to the Refreshed color scheme." class="gmw_thumb_color_scheme" id="' . esc_attr($this->get_field_id('thumb_color_scheme')) . '" name="' . esc_attr($this->get_field_name('thumb_color_scheme')) . '">';
    GMW::create_select_options($thumb_color_schemes, $thumb_color_scheme);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_zoom')) . '">' . esc_html__('Zoom Level', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Zoom varies from the lowest level, in which the entire world can be seen, to highest, which shows streets and individual buildings. Building outlines, where available, appear on the map around zoom level 17. This value differs from area to area." class="gmw_thumb_zoom" id="' . esc_attr($this->get_field_id('thumb_zoom')) . '" name="' . esc_attr($this->get_field_name('thumb_zoom')) . '">';
    GMW::create_select_options($zoom_levels_thumb, $thumb_zoom);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_pin_type')) . '">' . esc_html__('Pin Type', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Predefined pin can be adjusted in terms of color, size and one letter label.
          Custom pin can be any custom image stored on a publically available server (HTTPS is not supported)." class="gmw_thumb_pin_type" id="' . esc_attr($this->get_field_id('thumb_pin_type')) . '" name="' . esc_attr($this->get_field_name('thumb_pin_type')) . '">';
    GMW::create_select_options($thumb_pin_types, $thumb_pin_type);
    echo '</select></p>';

    echo '<p class="gmw_thumb_pin_type_predefined"><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_pin_color')) . '">' . esc_html__('Pin Color', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Choose one of the predefined pin colors, or upgrade to <b class=\'gmw-pro-red\'>PRO</b> to have an unlimited choice of colors." id="' . esc_attr($this->get_field_id('thumb_pin_color')) . '" name="' . esc_attr($this->get_field_name('thumb_pin_color')) . '">';
    GMW::create_select_options($thumb_pin_colors, $thumb_pin_color);
    echo '</select>';
    echo '</p>';

    echo '<p class="gmw_thumb_pin_type_predefined"><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_pin_size')) . '">' . esc_html__('Pin Size', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="All sizes besides the large one are quite small." id="' . esc_attr($this->get_field_id('thumb_pin_size')) . '" name="' . esc_attr($this->get_field_name('thumb_pin_size')) . '">';
    GMW::create_select_options($thumb_pin_sizes, $thumb_pin_size);
    echo '</select></p>';

    echo '<p class="gmw_thumb_pin_type_predefined"><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_pin_label')) . '">' . esc_html__('Pin Label', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Due to pin\'s size, only single-letter labels are available." id="' . esc_attr($this->get_field_id('thumb_pin_label')) . '" name="' . esc_attr($this->get_field_name('thumb_pin_label')) . '">';
    GMW::create_select_options($pin_labels, $thumb_pin_label);
    echo '</select></p>';

    echo '<p class="gmw_thumb_pin_type_custom"><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_pin_img')) . '">' . esc_html__('Pin Image URL', 'google-maps-widget') . ':</label>';
    echo '<input data-tooltip="Enter the full URL to the image, starting with http://. Image has to be publicly accessible and with size up to 64x64px. Https and localhosts are *not* supported." placeholder="http://" type="url" class="regular-text" id="' . esc_attr($this->get_field_id('thumb_pin_img')) . '" name="' . esc_attr($this->get_field_name('thumb_pin_img')) . '" value="' . esc_attr($thumb_pin_img) . '">';
    echo '</p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_link_type')) . '">' . esc_html__('Link To', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Choose what happens when the map is clicked. Clicks are tracked in Google Analytics if that option is enabled in settings (PRO only). Please configure interactive map\'s settings in its tab." class="gmw_thumb_link_type" id="' . esc_attr($this->get_field_id('thumb_link_type')) . '" name="' . esc_attr($this->get_field_name('thumb_link_type')) . '">';
    GMW::create_select_options($thumb_link_types, $thumb_link_type);
    echo '</select></p>';

    echo '<p class="gmw_thumb_link_section"><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_link')) . '">' . esc_html__('Custom URL', 'google-maps-widget') . ':</label>';
    echo '<input data-tooltip="Make sure the URL starts with http:// if it leads to a different site." placeholder="http://" class="regular-text" id="' . esc_attr($this->get_field_id('thumb_link')) . '" name="' . esc_attr($this->get_field_name('thumb_link')) . '" type="url" value="' . esc_attr($thumb_link) . '">';
    echo '</p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_format')) . '">' . esc_html__('Image Format', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Jpg and jpg-baseline typically provide the smallest image size, though they do so through _lossy_ compression which may degrade the image. Gif, png8 and png32 provide lossless compression." id="' . esc_attr($this->get_field_id('thumb_format')) . '" name="' . esc_attr($this->get_field_name('thumb_format')) . '">';
    GMW::create_select_options($thumb_formats, $thumb_format);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_lang')) . '">' . esc_html__('Map Language', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Not all map labels and texts have translations. Everything is controlled by Google at their discretion. If you choose the auto-detect mode language will be detected from the users browser settings." id="' . esc_attr($this->get_field_id('thumb_lang')) . '" name="' . esc_attr($this->get_field_name('thumb_lang')) . '">';
    GMW::create_select_options($thumb_langs, $thumb_lang);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_hide_title')) . '">' . esc_html__('Hide Widget Title', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="You can define a title for easier widgets management in admin but hide it with this option when the widget is displayed on site." id="' . esc_attr($this->get_field_id('thumb_hide_title')) . '" name="' . esc_attr($this->get_field_name('thumb_hide_title')) . '">';
    GMW::create_select_options($hide_titles, $thumb_hide_title);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('thumb_powered_by')) . '">' . esc_html__('Show Appreciation', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Please help others learn about GMW by placing a tiny link below the map. Thank you very much!" id="' . esc_attr($this->get_field_id('thumb_powered_by')) . '" name="' . esc_attr($this->get_field_name('thumb_powered_by')) . '">';
    GMW::create_select_options($thumb_powered_bys, $thumb_powered_by);
    echo '</select></p>';

    echo '<p><label for="' . esc_attr($this->get_field_id('thumb_header')) . '">' . esc_html__('Text Above Map', 'google-maps-widget') . ':</label>';
    echo '<textarea placeholder="Any text; HTML and shortcodes are fully supported." data-tooltip="Text that appears above the map. HTML tags and shortcodes are fully supported.
          If you choose to have the thumb replaced by an interactive map (PRO only) this text will be replaced by the interactive header text.
          Use the _{address}_ variable to display the map\'s address." class="widefat" rows="1" cols="20" id="' . esc_attr($this->get_field_id('thumb_header')) . '" name="' . esc_attr($this->get_field_name('thumb_header')) . '">'. esc_textarea($thumb_header) . '</textarea></p>';
    echo '<p><label for="' . esc_attr($this->get_field_id('thumb_footer')) . '">' . esc_html__('Text Below Map', 'google-maps-widget') . ':</label>';
    echo '<textarea placeholder="Any text; HTML and shortcodes are fully supported." data-tooltip="Text that appears below the map. HTML tags and shortcodes are fully supported.
          If you choose to have the thumb replaced by an interactive map (PRO only) this text will be replaced by the interactive footer text.
          Use the _{address}_ variable to display the map\'s address." class="widefat" rows="1" cols="20" id="' . esc_attr($this->get_field_id('thumb_footer')) . '" name="' . esc_attr($this->get_field_name('thumb_footer')) . '">'. esc_textarea($thumb_footer) . '</textarea></p>';
    echo '</div>';
    // end - thumbnail tab

    // lightbox tab
    echo '<div id="gmw-lightbox">';
    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_fullscreen')) . '">' . esc_html__('Lightbox Size', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Choose from a custom size or the fullscreen, border-to-border option for the lightbox map." class="gmw_lightbox_fullscreen" id="' . esc_attr($this->get_field_id('lightbox_fullscreen')) . '" name="' . esc_attr($this->get_field_name('lightbox_fullscreen')) . '">';
    GMW::create_select_options($lightbox_sizes, $lightbox_fullscreen);
    echo '</select>';
    echo '<span class="gmw_lightbox_fullscreen_custom_section"><span class="gmw-label label-holder">&nbsp;</span>';
    echo '<input data-title="Map Width" data-tooltip="Interactive map width in pixels; from 50 to 2000. If needed, map will be resized to accomodate for smaller screens." class="small-text fullscreen_fix" min="50" max="2000" step="1" id="' . esc_attr($this->get_field_id('lightbox_width')) . '" type="number" name="' . esc_attr($this->get_field_name('lightbox_width')) . '" value="' . esc_attr($lightbox_width) . '" required="required"> x ';
    echo '<input data-title="Map Height" data-tooltip="Interactive map height in pixels; from 50 to 2000. If needed, map will be resized to accomodate for smaller screens." class="small-text" id="' . esc_attr($this->get_field_id('lightbox_height')) . '" name="' . esc_attr($this->get_field_name('lightbox_height')) . '" type="number" step="1" min="50" max="2000" value="' . esc_attr($lightbox_height) . '" required="required"> px</span></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_mode')) . '">' . esc_html__('Map Mode', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Place mode displays a map pin at a defined place or address.
          Directions mode displays the path between a start address defined below, and destination defined in the map\'s address.
          Search mode displays results for a search across the area around the map\'s address.
          View mode returns a map with no markers or directions; it produces a very clean map.
          Street View provides panoramic views on the designated location. Please note that it\'s not available on all locations." class="gmw_lightbox_mode" id="' . esc_attr($this->get_field_id('lightbox_mode')) . '" name="' . esc_attr($this->get_field_name('lightbox_mode')) . '">';
    GMW::create_select_options($lightbox_modes, $lightbox_mode);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_map_type')) . '">' . esc_html__('Map Type', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Controls the map layers shown." id="' . esc_attr($this->get_field_id('lightbox_map_type')) . '" name="' . esc_attr($this->get_field_name('lightbox_map_type')) . '">';
    GMW::create_select_options($lightbox_map_types, $lightbox_map_type);
    echo '</select></p>';

    echo '<p class="gmw_multiple_pins_feature"><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_clustering')) . '">' . esc_html__('Pins Clustering', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Clustering enables pins grouping depending on their distances on the current view. It declutters the map when multiple pins are displayed. See it on <a href=\'https://www.gmapswidget.com/#examples\' target=\'_blank\'>Starbucks locations example</a>." id="' . esc_attr($this->get_field_id('lightbox_clustering')) . '" name="' . esc_attr($this->get_field_name('lightbox_clustering')) . '">';
    GMW::create_select_options($lightbox_clustering_options, $lightbox_clustering);
    echo '</select></p>';

    echo '<p class="gmw_multiple_pins_feature"><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_filtering')) . '">' . esc_html__('Pins Filtering', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Filtering gives users the ability to show/hide pins based on group name(s) set for each pin. Additional GUI is displayed in the upper right corner of the map. See it in action on <a href=\'https://www.gmapswidget.com/#examples\' target=\'_blank\'>Restaurants in Manhattan</a>." id="' . esc_attr($this->get_field_id('lightbox_filtering')) . '" name="' . esc_attr($this->get_field_name('lightbox_filtering')) . '">';
    GMW::create_select_options($lightbox_filtering_options, $lightbox_filtering);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_zoom')) . '">' . esc_html__('Zoom Level', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Zoom varies from the lowest level, in which the entire world can be seen, to highest, which shows streets and individual buildings. Building outlines, where available, appear on the map around zoom level 17. This value differs from area to area." id="' . esc_attr($this->get_field_id('lightbox_zoom')) . '" name="' . esc_attr($this->get_field_name('lightbox_zoom')) . '">';
    GMW::create_select_options($zoom_levels_lightbox, $lightbox_zoom);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_skin')) . '">' . esc_html__('Lightbox Skin', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Controls the overall appearance of the lightbox, not the map itself. Adjust according to your site\'s design." class="gmw_lightbox_skin" id="' . esc_attr($this->get_field_id('lightbox_skin')) . '" name="' . esc_attr($this->get_field_name('lightbox_skin')) . '">';
    GMW::create_select_options($lightbox_skins, $lightbox_skin);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_feature')) . '">' . esc_html__('Lightbox Features', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Title is taken from the widget title field. Not all skins have a title, and the ones that do have it in different places, so please test your maps.
          Other 3 options control the way users close the lightbox. Enable at least one of them." class="gmw-select2" data-placeholder="' . esc_html__('Click to choose features', 'google-maps-widget') . '" multiple="multiple" id="' . esc_attr($this->get_field_id('lightbox_feature')) . '" name="' . esc_attr($this->get_field_name('lightbox_feature')) . '[]">';
    GMW::create_select_options($lightbox_features, $lightbox_feature);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . esc_attr($this->get_field_id('lightbox_lang')) . '">' . esc_html__('Map Language', 'google-maps-widget') . ':</label>';
    echo '<select data-tooltip="Not all map labels and texts have translations. Everything is controlled by Google at their discretion. If you choose the auto-detect mode language will be detected from the users browser settings." id="' . esc_attr($this->get_field_id('lightbox_lang')) . '" name="' . esc_attr($this->get_field_name('lightbox_lang')) . '">';
    GMW::create_select_options($lightbox_langs, $lightbox_lang);
    echo '</select></p>';

    echo '<p><label for="' . esc_attr($this->get_field_id('lightbox_header')) . '">' . esc_html__('Text Above Map', 'google-maps-widget') . ':</label>';
    echo '<textarea placeholder="Any text; HTML and shortcodes are fully supported." data-tooltip="Text that appears above the interactive map. HTML tags and shortcodes are fully supported.
         Use the _{address}_ variable to display the map\'s address." class="widefat" rows="1" cols="20" id="' . esc_attr($this->get_field_id('lightbox_header')) . '" name="' . esc_attr($this->get_field_name('lightbox_header')) . '">'. esc_textarea($lightbox_header) . '</textarea></p>';

    echo '<p><label for="' . esc_attr($this->get_field_id('lightbox_footer')) . '">' . esc_html__('Text Below Map', 'google-maps-widget') . ':</label>';
    echo '<textarea placeholder="Any text; HTML and shortcodes are fully supported." data-tooltip="Text that appears below the interactive map. HTML tags and shortcodes are fully supported.
         Use the _{address}_ variable to display the map\'s address." class="widefat" rows="1" cols="20" id="' . esc_attr($this->get_field_id('lightbox_footer')) . '" name="' . esc_attr($this->get_field_name('lightbox_footer')) . '">'. esc_textarea($lightbox_footer) . '</textarea></p>';

    echo '</div>';
    // end - lightbox tab

    // pins tab
    echo '<div id="gmw-pins">';
    echo '<p>Multiple pins support is a <span class="gmw-pro-red">PRO</span> feature. It includes everything you need to display as many pins as needed on one map;</p>';

    echo '<ul class="normal">';
    echo '<li>per-pin control for all settings</li>';
    echo '<li>library of 1500+ pins &amp; support for custom pins</li>';
    echo '<li>pins clustering for decluttering maps</li>';
    echo '<li>pins grouping with front-end filtering control</li>';
    echo '<li>unlimited pins import</li>';
    echo '<li>numerous "on pin click" actions including custom HTML info boxes</li>';
    echo '</ul>';

    echo '<p>If one location per map is all you need, please enter the address in the address field above.</p>';
    echo '<p><br><a class="button open_promo_dialog" href="#">Activate <span class="gmw-pro-red">PRO</span> features NOW</a></p>';
    echo '</div>';
    // end - pins tab

    // shortcode tab
    echo '<div id="gmw-shortcode">';
    echo '<p>Shortcode support is a <span class="gmw-pro-red">PRO</span> feature. It enables you to place maps anywhere in content; including pages, posts and custom post types. Besides the shortcode feature you will get 50 extra options and features.</p><p><br><a class="button open_promo_dialog" href="#">Activate <span class="gmw-pro-red">PRO</span> features NOW</a></p>';
    echo '</div>';
    // end - shortcode tab

    // info tab
    echo '<div id="gmw-info">';
    echo '<h4>' . esc_html__('Support', 'google-maps-widget') . '</h4>';
    echo '<p>If you have any problems, questions or would like a new feature added post it on the <a href="https://wordpress.org/support/plugin/google-maps-widget" target="_blank">official support forum</a>. It\'s the only place to get support. Since it\'s free and community powered please be patient.<br>';
    echo 'If you <a href="#" class="open_promo_dialog">upgrade</a> to <span class="gmw-pro-red">PRO</span> you will get instant access to premium, prioritised support via email.</p>';

    echo '<h4>' . esc_html__('Activate <span class="gmw-pro-red">PRO</span> features', 'google-maps-widget') . '</h4>';
    echo '<p><span class="gmw-pro-red">PRO</span> features give you access to priority support and more than 50 extra options &amp; features including shortcode support, additional map types, more map skins and a host of other additional features.';
    echo ' <a class="open_promo_dialog" href="#">Activate PRO features NOW</a>';
    echo '</p>';
    echo '<h4>' . esc_html__('Rate the plugin &amp; spread the word', 'google-maps-widget') . '</h4>';
    echo '<p>It won\'t take you more than a minute, but it will help us immensely. So please - <a href="https://wordpress.org/support/view/plugin-reviews/google-maps-widget" target="_blank">rate the plugin</a>. Or spread the word by <a href="https://twitter.com/intent/tweet?via=WebFactoryLtd&amp;text=' . esc_attr(urlencode('I\'m using the #free Maps Widget for Google Maps for #wordpress. You can grab it too at http://goo.gl/2qcbbf')) . '" target="_blank">tweeting about it</a>. Thank you!</p>';
    echo '</div>';
    // end - info tab
    echo '</div><p></p>'; // tabs

    GMW::wp_kses_wf('<p class="widget_footer_info">' . __('Upgrade to Maps Widget for Google Maps <span class="gmw-pro-red">PRO</span> to get more than 50 extra options &amp; features available immeditely. <a class="open_promo_dialog" href="#">Upgrade now</a>.', 'google-maps-widget') . '</p>');
  } // form


  // update/save widget options
  function update($new_instance, $old_instance) {
    $instance = array();
    if (!is_array($old_instance)) {
      $old_instance = array();
    }

    $instance['title'] = sanitize_text_field($new_instance['title']);
    $instance['address'] = sanitize_text_field(trim($new_instance['address']));

    $instance['thumb_pin_type'] = sanitize_text_field($new_instance['thumb_pin_type']);
    $instance['thumb_pin_color'] = GMW::sanitize_hex_color(sanitize_text_field(@$new_instance['thumb_pin_color']));
    $instance['thumb_pin_size'] = sanitize_text_field($new_instance['thumb_pin_size']);
    $instance['thumb_pin_label'] = sanitize_text_field($new_instance['thumb_pin_label']);
    $instance['thumb_pin_img'] = sanitize_text_field(trim($new_instance['thumb_pin_img']));
    $instance['thumb_width'] = min(640, max(50, (int) $new_instance['thumb_width']));
    $instance['thumb_height'] = min(640, max(50, (int) $new_instance['thumb_height']));
    $instance['thumb_zoom'] = sanitize_text_field($new_instance['thumb_zoom']);
    $instance['thumb_type'] = sanitize_text_field(@$new_instance['thumb_type']);
    $instance['thumb_link_type'] = sanitize_text_field($new_instance['thumb_link_type']);
    $instance['thumb_link'] = sanitize_text_field(trim($new_instance['thumb_link']));
    $instance['thumb_header'] = wp_kses_post(trim($new_instance['thumb_header']));
    $instance['thumb_footer'] = wp_kses_post(trim($new_instance['thumb_footer']));
    $instance['thumb_color_scheme'] = sanitize_text_field($new_instance['thumb_color_scheme']);
    $instance['thumb_format'] = sanitize_text_field($new_instance['thumb_format']);
    $instance['thumb_lang'] = sanitize_text_field($new_instance['thumb_lang']);
    $instance['thumb_powered_by'] = sanitize_text_field($new_instance['thumb_powered_by']);
    $instance['thumb_hide_title'] = sanitize_text_field($new_instance['thumb_hide_title']);

    $instance['lightbox_fullscreen'] = (int) $new_instance['lightbox_fullscreen'];
    $instance['lightbox_width'] = min(2000, max(50, (int) $new_instance['lightbox_width']));
    $instance['lightbox_height'] = min(2000, max(50, (int) $new_instance['lightbox_height']));
    $instance['lightbox_mode'] = sanitize_text_field($new_instance['lightbox_mode']);
    $instance['lightbox_map_type'] = sanitize_text_field($new_instance['lightbox_map_type']);
    $instance['lightbox_zoom'] = sanitize_text_field($new_instance['lightbox_zoom']);
    $instance['lightbox_feature'] = (array) $new_instance['lightbox_feature'];
    $instance['lightbox_header'] = wp_kses_post(trim($new_instance['lightbox_header']));
    $instance['lightbox_footer'] = wp_kses_post(trim($new_instance['lightbox_footer']));
    $instance['lightbox_skin'] = sanitize_text_field($new_instance['lightbox_skin']);
    $instance['lightbox_lang'] = sanitize_text_field($new_instance['lightbox_lang']);

    $instance['core_ver'] = GMW::$version;

    return $instance;
  } // update


  // output widget
  function widget($widget, $instance) {
    $options = GMW::get_options();

    // no user map key -> disable map
    if ( !GMW::get_api_key('test') ) {
      if ( is_user_logged_in() && current_user_can('administrator') ) {
        GMW::wp_kses_wf( $widget['before_widget'] . '<div style="border: 1px solid black; padding: 20px;">Open <a href="' . admin_url('options-general.php?page=gmw_options') . '">Maps Widget for Google Maps settings</a> to configure the Google Maps API key. The map can\'t work without it. This is a Google\'s rule that all sites must follow.</div>' . $widget['after_widget']);
      } else {
        GMW::wp_kses_wf( $widget['before_widget'] . '<div style="border: 1px solid black; padding: 20px;">Open Maps Widget for Google Maps settings to configure the Google Maps API key. The map can\'t work without it. This is a Google\'s rule that all sites must follow.</div>' . $widget['after_widget']);
      }

      return;
    }


    $out = $widget_content = $style = '';
    $map_params = array();
    $instance = $this->upgrade_wiget_instance($instance);
    $map_styles = array(
    'ultra_light' => 'style=feature:water|element:geometry|color:0xe9e9e9|lightness:17|&style=feature:landscape|element:geometry|color:0xf5f5f5|lightness:20|&style=feature:road.highway|element:geometry.fill|color:0xffffff|lightness:17|&style=feature:road.highway|element:geometry.stroke|color:0xffffff|lightness:29|weight:0.2|&style=feature:road.arterial|element:geometry|color:0xffffff|lightness:18|&style=feature:road.local|element:geometry|color:0xffffff|lightness:16|&style=feature:poi|element:geometry|color:0xf5f5f5|lightness:21|&style=feature:poi.park|element:geometry|color:0xdedede|lightness:21|&style=feature:all|element:labels.text.stroke|visibility:on|color:0xffffff|lightness:16|&style=feature:all|element:labels.text.fill|saturation:36|color:0x333333|lightness:40|&style=feature:all|element:labels.icon|visibility:off|&style=feature:transit|element:geometry|color:0xf2f2f2|lightness:19|&style=feature:administrative|element:geometry.fill|color:0xfefefe|lightness:20|&style=feature:administrative|element:geometry.stroke|color:0xfefefe|lightness:17|weight:1.2|',
    'gowalla' => 'style=feature:road|element:labels|visibility:simplified|lightness:20|&style=feature:administrative.land_parcel|element:all|visibility:off|&style=feature:landscape.man_made|element:all|visibility:off|&style=feature:transit|element:all|visibility:off|&style=feature:road.local|element:labels|visibility:simplified|&style=feature:road.local|element:geometry|visibility:simplified|&style=feature:road.highway|element:labels|visibility:simplified|&style=feature:poi|element:labels|visibility:off|&style=feature:road.arterial|element:labels|visibility:off|&style=feature:water|element:all|hue:0xa1cdfc|saturation:30|lightness:49|&style=feature:road.highway|element:geometry|hue:0xf49935|&style=feature:road.arterial|element:geometry|hue:0xfad959|',
    'mapbox' => 'style=feature:water|element:all|saturation:43|lightness:-11|hue:0x0088ff|&style=feature:road|element:geometry.fill|hue:0xff0000|saturation:-100|lightness:99|&style=feature:road|element:geometry.stroke|color:0x808080|lightness:54|&style=feature:landscape.man_made|element:geometry.fill|color:0xece2d9|&style=feature:poi.park|element:geometry.fill|color:0xccdca1|&style=feature:road|element:labels.text.fill|color:0x767676|&style=feature:road|element:labels.text.stroke|color:0xffffff|&style=feature:poi|element:all|visibility:off|&style=feature:landscape.natural|element:geometry.fill|visibility:on|color:0xb8cb93|&style=feature:poi.park|element:all|visibility:on|&style=feature:poi.sports_complex|element:all|visibility:on|&style=feature:poi.medical|element:all|visibility:on|&style=feature:poi.business|element:all|visibility:simplified|',
    'bright' => 'style=feature:water|element:all|color:0x19a0d8|&style=feature:administrative|element:labels.text.stroke|color:0xffffff|weight:6|&style=feature:administrative|element:labels.text.fill|color:0xe85113|&style=feature:road.highway|element:geometry.stroke|color:0xefe9e4|lightness:-40|&style=feature:road.arterial|element:geometry.stroke|color:0xefe9e4|lightness:-20|&style=feature:road|element:labels.text.stroke|lightness:100|&style=feature:road|element:labels.text.fill|lightness:-100|&style=&style=feature:landscape|element:labels|visibility:off|&style=feature:landscape|element:all|lightness:20|color:0xefe9e4|&style=feature:landscape.man_made|element:all|visibility:off|&style=feature:water|element:labels.text.stroke|lightness:100|&style=feature:water|element:labels.text.fill|lightness:-100|&style=feature:poi|element:labels.text.fill|hue:0x11ff00|&style=feature:poi|element:labels.text.stroke|lightness:100|&style=feature:poi|element:labels.icon|hue:0x4cff00|saturation:58|&style=feature:poi|element:geometry|visibility:on|color:0xf0e4d3|&style=feature:road.highway|element:geometry.fill|color:0xefe9e4|lightness:-25|&style=feature:road.arterial|element:geometry.fill|color:0xefe9e4|lightness:-10|&style=feature:poi|element:labels|visibility:simplified|',
    'midnight' => 'style=feature:water|element:all|color:0x021019|&style=feature:landscape|element:all|color:0x08304b|&style=feature:poi|element:geometry|color:0x0c4152|lightness:5|&style=feature:road.highway|element:geometry.fill|color:0x000000|&style=feature:road.highway|element:geometry.stroke|color:0x0b434f|lightness:25|&style=feature:road.arterial|element:geometry.fill|color:0x000000|&style=feature:road.arterial|element:geometry.stroke|color:0x0b3d51|lightness:16|&style=feature:road.local|element:geometry|color:0x000000|&style=feature:all|element:labels.text.fill|color:0xffffff|&style=feature:all|element:labels.text.stroke|color:0x000000|lightness:13|&style=feature:transit|element:all|color:0x146474|&style=feature:administrative|element:geometry.fill|color:0x000000|&style=feature:administrative|element:geometry.stroke|color:0x144b53|lightness:14|weight:1.4|',
    'neon' => 'style=saturation:100|gamma:0.6',
    'gray' => 'style=feature:landscape|element:all|saturation:-100|lightness:65|visibility:on|&style=feature:poi|element:all|saturation:-100|lightness:51|visibility:simplified|&style=feature:road.highway|element:all|saturation:-100|visibility:simplified|&style=feature:road.arterial|element:all|saturation:-100|lightness:30|visibility:on|&style=feature:road.local|element:all|saturation:-100|lightness:40|visibility:on|&style=feature:transit|element:all|saturation:-100|visibility:simplified|&style=feature:administrative.province|element:all|visibility:off|&style=feature:water|element:labels|visibility:on|lightness:-25|saturation:-100|&style=feature:water|element:geometry|hue:0xffff00|lightness:-25|saturation:-97|',
    'blue' => 'style=feature:water|element:all|color:0x46bcec|visibility:on|&style=feature:landscape|element:all|color:0xf2f2f2|&style=feature:road|element:all|saturation:-100|lightness:45|&style=feature:road.highway|element:all|visibility:simplified|&style=feature:road.arterial|element:labels.icon|visibility:off|&style=feature:administrative|element:labels.text.fill|color:0x444444|&style=feature:transit|element:all|visibility:off|&style=feature:poi|element:all|visibility:off|',
    'gray2' => 'style=feature:all|element:all|saturation:-100|gamma:0.5|',
    'paper' => 'style=feature:landscape|element:all|hue:0xF1FF00|saturation:-27.4|lightness:9.4|gamma:1|&style=feature:road.highway|element:all|hue:0x0099FF|saturation:-20|lightness:36.4|gamma:1|&style=feature:road.arterial|element:all|hue:0x00FF4F|saturation:0|lightness:0|gamma:1|&style=feature:road.local|element:all|hue:0xFFB300|saturation:-38|lightness:11.2|gamma:1|&style=feature:water|element:all|hue:0x00B6FF|saturation:4.2|lightness:-63.4|gamma:1|&style=feature:poi|element:all|hue:0x9FFF00|saturation:0|lightness:0|gamma:1|');

    $map_src = '//maps.googleapis.com/maps/api/staticmap';

    // make sure all params are defined
    $instance = wp_parse_args((array) $instance, self::$defaults);
    $instance['id'] = $widget['widget_id'];

    // build thumbnail map parameters
    if (GMW::get_api_key('static')) {
      $map_params['key'] = GMW::get_api_key('static');
    }
    $map_params['scale'] = 1;
    $map_params['format'] = $instance['thumb_format'];
    $map_params['zoom'] = $instance['thumb_zoom'];
    $map_params['size'] = $instance['thumb_width'] . 'x' . $instance['thumb_height'];
    if ($instance['thumb_lang'] != 'auto') {
      $map_params['language'] = $instance['thumb_lang'];
    }
    $map_params['maptype'] = $instance['thumb_type'];
    if ($instance['thumb_pin_type'] == 'custom') {
      $map_params['markers'] = 'icon:' . $instance['thumb_pin_img'];
    } elseif ($instance['thumb_pin_type'] == 'custom-library') {
      $map_params['markers'] = 'icon:' . plugins_url('/images/pins/' . $instance['thumb_pin_img_library'], __FILE__);
    } else {
      $map_params['markers'] = 'size:' . $instance['thumb_pin_size'] . '|color:' . str_replace('#', '0x', $instance['thumb_pin_color']) . '|label:' . $instance['thumb_pin_label'];
    }
    $map_params['markers'] .= '|' . $instance['address'];
    $map_params['center'] = $instance['address'];
    if ($instance['thumb_color_scheme'] == 'new') {
      $map_params['visual_refresh'] = 'true';
    } elseif ($instance['thumb_color_scheme'] != 'default') {
      $map_params['visual_refresh'] = 'false';
      $style = '&amp;' . str_replace('&', '&amp;', $map_styles[$instance['thumb_color_scheme']]);
    }

    // start building widget markup
    $out .= $widget['before_widget'];

    // add widget title; respect sidebar markup
    $title = empty($instance['title'])? '' : apply_filters('widget_title', $instance['title']);
    if (!empty($title) && empty($instance['thumb_hide_title'])) {
      $out .= $widget['before_title'] . $title . $widget['after_title'];
    }

    // if not empty, add header text
    if (!empty($instance['thumb_header'])) {
      $tmp = str_ireplace(array('{address}'), array($instance['address']), $instance['thumb_header']);
      $widget_content .= wpautop(do_shortcode($tmp));
    }
    $widget_content .= '<p>';

    if ($instance['thumb_link_type'] == 'lightbox') {
      self::$widgets[$widget['widget_id']] = $instance;
      $map_alt = __('Click to open a larger map', 'google-maps-widget');
      $widget_content .= '<a data-gmw-id="' . $widget['widget_id'] . '" class="gmw-thumbnail-map gmw-lightbox-enabled" href="#gmw-dialog-' . $widget['widget_id'] . '" title="' . __('Click to open a larger map', 'google-maps-widget') . '">';
    } elseif ($instance['thumb_link_type'] == 'replace') {
      self::$widgets[$widget['widget_id']] = $instance;
      $map_alt = __('Click to open the interactive map', 'google-maps-widget');
      $widget_content .= '<a data-gmw-id="' . $widget['widget_id'] . '" class="gmw-thumbnail-map gmw-replace-enabled" href="#gmw-dialog-' . $widget['widget_id'] . '" title="' . __('Click to open an interactive map', 'google-maps-widget') . '">';
    } elseif ($instance['thumb_link_type'] == 'custom') {
      $map_alt = esc_attr($instance['address']);
      $widget_content .= '<a class="gmw-thumbnail-map" title="' . esc_attr($instance['address']) . '" href="' . $instance['thumb_link'] . '">';
    } elseif ($instance['thumb_link_type'] == 'custom_blank') {
      $map_alt = esc_attr($instance['address']);
      $widget_content .= '<a class="gmw-thumbnail-map" title="' . esc_attr($instance['address']) . '" target="_blank" href="' . $instance['thumb_link'] . '">';
    } elseif ($instance['thumb_link_type'] == 'map_blank') {
      $map_alt = __('Click to open the interactive map in a new window', 'google-maps-widget');
      $map_url = GMW::build_lightbox_url($instance);
      $widget_content .= '<a class="gmw-thumbnail-map" title="' . esc_attr($instance['address']) . '" target="_blank" href="' . $map_url . '">';
    } elseif ($instance['thumb_link_type'] == 'nolink') {
      $map_alt = esc_attr($instance['address']);
    }

    // build map image source
    $map_src .= '?' . http_build_query($map_params, "", '&amp;') . $style;
    $map_src = apply_filters('gmw_thumb_map_src', $map_src, $instance);
    $widget_content .= '<img width="' . $instance['thumb_width'] . '" height="' . $instance['thumb_height'] . '" alt="' . $map_alt . '" title="' . $map_alt . '" src="' . $map_src . '">';

    if ($instance['thumb_link_type'] == 'lightbox' ||
        $instance['thumb_link_type'] == 'replace' ||
        $instance['thumb_link_type'] == 'custom' ||
        $instance['thumb_link_type'] == 'custom_blank' ||
        $instance['thumb_link_type'] == 'map_blank') {
      $widget_content .= '</a>';
    }
    $widget_content .= '</p>';

    // if not empty, add footer text
    if (!empty($instance['thumb_footer'])) {
      $tmp = str_ireplace(array('{address}'), array($instance['address']), $instance['thumb_footer']);
      $widget_content .= wpautop(do_shortcode($tmp));
    }

    if (!empty($instance['thumb_powered_by'])) {
      $widget_content .= '<small>We use the fastests <a href="https://wordpress.org/plugins/google-maps-widget/" target="_blank">free Google Maps plugin for WordPress</a></small>';
    }

    $out .= apply_filters('gmw_widget_content', $widget_content, $instance);
    $out .= $widget['after_widget'];

    GMW::wp_kses_wf($out);
  } // widget


  // compatibility fixes for widgets prior to v3.0
  function upgrade_wiget_instance($instance) {
    $instance = wp_parse_args((array) $instance, self::$defaults);

    if (isset($instance['core_ver']) && version_compare($instance['core_ver'], '3.0',  '>=')) {
      return $instance;
    }

    // pin color is now in hex
    if ($instance['thumb_pin_color'][0] != '#') {
      $instance['thumb_pin_color'] = GMW::convert_color($instance['thumb_pin_color']);
    } elseif (empty($instance['thumb_pin_color'])) {
      $instance['thumb_pin_color'] = '#ff0000';
    }

    // if we had title before, we need it now too
    if (!empty($instance['lightbox_title']) && !in_array('title', $instance['lightbox_feature'])) {
      $instance['lightbox_feature'][] = 'title';
    }

    // map type values changed
    if (isset($instance['lightbox_map_type']) && $instance['lightbox_map_type'] == 'k') {
      $instance['lightbox_map_type'] = 'satellite';
    } elseif ($instance['lightbox_map_type'] != 'satellite') {
      $instance['lightbox_map_type'] = 'roadmap';
    }

    return $instance;
  } // upgrade_widget_instance

} // class GoogleMapsWidget
}
