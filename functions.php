<?php /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~ F U N C T I O N S . P H P ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */



/* Constants
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  /**
   * Google Analytics
   * (Universal Analytics only, not Classic Analytics)
   */
  define('GOOGLE_ANALYTICS_ID', ''); // UA-XXXXX-Y 


  /**
   * Google Maps API Key
   */
  define('API_KEY', '');


  /**
   * Google Fonts
   */
  define('GOOGLE_FONTS', 'Archivo+Black|Work+Sans:100,200,300,400,500,600,700,800,900');


  /**
   * Paths
   */
  define('HOME', home_url());
  define('THEME', get_template_directory_uri());
  define('ASSETS', THEME . '/assets');
  define('IMAGES', ASSETS . '/images');
  define('CSS', ASSETS . '/css');
  define('JS', ASSETS . '/js');


  /**
   * Image sizes
   */
  define('SZ_BANNER' , '');
  define('SZ_SLIDER' , '');
  define('SZ_S_THUMB', '');
  define('SZ_G_THUMB', '');


  /**
   * Image placeholder text
   */
  define('PLACEHOLD', 'INMEDIA');


/* Includes
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  require_once locate_template('lib/something.php');


/* Initial setup
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  function inmedia_theme_setup() {

    // Make theme available for translation
    load_theme_textdomain( 'inmedia', get_template_directory() . '/lang' );

    // Enable plugins to manage the document title
    add_theme_support('title-tag');

    // Register wp_nav_menu() menus
    register_nav_menus(array(
      'primary_l' => __( 'Main Menu (Left)', 'inmedia' ),
      'primary_r' => __( 'Main Menu (Right)', 'inmedia' ),
      'footer'  => __( 'Footer Menu', 'inmedia' ),
    ));

    // Add post thumbnails
    add_theme_support('post-thumbnails');

    // Add Custom Image sizes..
    add_image_size( 'small', 400, 300, true );

    // Add post formats
    add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio'));

    // Add HTML5 markup for captions etc..
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

    // Tell the TinyMCE editor to use a custom stylesheet
    add_editor_style(CSS.'/editor.css');

  }
  add_action('after_setup_theme', 'inmedia_theme_setup');


  /**
   * Register sidebars
   */
  function inmedia_widgets_init() {
    register_sidebar(array(
      'name'          => __('Primary', 'inmedia'),
      'id'            => 'sidebar-primary',
      'before_widget' => '<div class="widget %1$s %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3>',
      'after_title'   => '</h3>',
    ));

    register_sidebar(array(
      'name'          => __('Footer', 'inmedia'),
      'id'            => 'sidebar-footer',
      'before_widget' => '<div class="widget %1$s %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3>',
      'after_title'   => '</h3>',
    ));
  }
  add_action('widgets_init', 'inmedia_widgets_init');


  /**
   * Load google fonts in head..
   * uses GOOGLE_FONTS constant above
   */
  function inmedia_load_google_fonts() {

    if( ! defined( 'GOOGLE_FONTS' ) ) return;

    echo '<!-- google fonts -->'."\n";
    echo '<link href="http://fonts.googleapis.com/css?family=' . GOOGLE_FONTS . '" rel="stylesheet" type="text/css" />'."\n";

  }
  add_action( 'wp_head', 'inmedia_load_google_fonts' , 1);


/* Utility functions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  function is_element_empty($element) {

    $element = trim($element);
    return !empty($element);
  }


  // Add page slug to body_class() classes if it doesn't exist..
  function inmedia_body_class($classes) {
    // Add post/page slug
    if (is_single() || is_page() && !is_front_page()) {
      if (!in_array(basename(get_permalink()), $classes)) {
        $classes[] = basename(get_permalink());
      }
    }
    return $classes;
  }
  add_filter('body_class', 'inmedia_body_class');


  /**
   * Clean up the_excerpt()
   */
  function inmedia_excerpt_more($more) {

    return ' &hellip;';
  }
  add_filter('excerpt_more', 'inmedia_excerpt_more');


  /**
   * Set the except length
   */
  function inmedia_excerpt_length($length) {

      return 20;
  }
  add_filter('excerpt_length', 'inmedia_excerpt_length', 999);


  /**
   * Helper function for generating custom excerpts on the fly
   */
  function inmedia_excerptify($text='', $excerpt_length=20, $excerpt_more=null) {

    global $post;
    if ($text != '') {

      $text = strip_shortcodes($text);
      $text = apply_filters('the_content', $text);
      $text = str_replace(']]>', ']]>', $text);
      if ($excerpt_more) {
        
        $excerpt_more = apply_filters('excerpt_more', ' ' . '&hellip;');
      }
      $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
    }
    return apply_filters('the_excerpt', $text);
  }


  /**
   * Manage output of wp_title()
   */
  function inmedia_wp_title($title) {
    if (is_feed()) {
      return $title;
    }

    $title = get_bloginfo('name');

    return $title;
  }
  add_filter('wp_title', 'inmedia_wp_title', 10);


  /**
   * Get post thumbnail alt similarly to get_post_thumbnail_url()
   */
  function get_the_post_thumbnail_alt($post_id=null) {

    if (!$post_id) $post_id = $post->ID;
    $thumbnail_id = get_post_thumbnail_id($post_id);
    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
    return ($alt);
  }

  /**
   * Explode a \n delimited textarea into a list
   */
  function inmedia_list_explode($str, $class=null) {

    $lists = explode("\n", $str);
    $lists_html = '';

    if ($class) {
      $class = ' class="'.trim($class).'"';
    }

    foreach ($lists as $list) {
      
      $lists_html .= "<li{$class}>{$list}</li>";
    }

    return $lists_html;
  }

  /*
   * Echo image placholder
   */
  function placehold($text=PLACEHOLD) {
    echo '<img src="//placehold.it/500x500?text='.$text.'" alt="placeholder">';
  }

  /*
   * Telephone number sanitize
   */
  function tel($tel) {
    return str_replace(' ', '', $tel);
  }


/* ACF
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  /**
   * Set gmaps api key in back end..
   */
  add_filter('acf/settings/google_api_key', function() {
      return API_KEY;
  });


  /**
   * Translate the relationship result
   */
  function inmedia_relationship_result( $title, $post, $field, $post_id ) {

      $editLang = $_COOKIE['qtrans_edit_language'];
      if($editLang) {
          return qtranxf_use($editLang, $title, false, false);
      } else {
          return  qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($title);
      }
  }
  add_filter('acf/fields/relationship/result', 'inmedia_relationship_result', 10, 4);
  add_filter('acf/fields/post_object/result', 'inmedia_relationship_result', 10, 4);
  add_filter('acf/fields/page_link/result', 'inmedia_relationship_result', 10, 4);


  /**
   * Options
   */
  if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
  }


/* Custom
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  //off ya go..>

  add_filter('next_posts_link_attributes', 'posts_link_attributes');
  // add_filter('previous_posts_link_attributes', 'posts_link_attributes');

  function posts_link_attributes() {
      return 'id="moreProperties"';
  }

























/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~ F U N C T I O N S . P H P ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */