<?php
/*
Plugin Name: Awesome-WordPress-ScrollBar
Plugin URI: http://www.hf-it.org/
Description: This plugin will  create awesome custom ScroolBar in your wordpress site.You can change any settings of this ScrollBar from Option Panel. For more,Please read the readme.txt file.
Author: HelpFull(HF) Institute
Author URI: http://www.hf-it.org/
Version: 1.1.0
*/

    /* Adding Latest jQuery from Wordpress */
if (!function_exists('hf_scroolbar_plugin_wp')) {
    function hf_scroolbar_plugin_wp() {
        wp_enqueue_script('jquery');
    }
    add_action('init', 'hf_scroolbar_plugin_wp');
}
    /*Custom-set-up*/
    define('HF_SCROLLBAR', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
if (!function_exists('hf_scroolbar_css_and_js')) {
function hf_scroolbar_css_and_js() {
   wp_enqueue_script('hf-nicescroll',HF_SCROLLBAR.'js/jquery.nicescroll.min.js',array('jquery'),'3.6.0',false);

   wp_enqueue_style('hf-mains',HF_SCROLLBAR.'css/hf-main.css',array(),'1.0','all');
}
add_action('wp_enqueue_scripts','hf_scroolbar_css_and_js');
}

if (!function_exists('hf_scrollbar_add_menu_options')) {
   function hf_scrollbar_add_menu_options() {  
        add_options_page( 'HF-Scroll-Settings', 'Awesome Scroll Settings', 'manage_options','scrollbar_settings', 'hf_scroll_options_panel');
   
    }  
    add_action('admin_menu', 'hf_scrollbar_add_menu_options');
}

if (!function_exists('hf_scroll_color_picker')) {
function hf_scroll_color_picker( $hook ) {
    if( is_admin() ) {
        // Add the color picker css file      
        wp_enqueue_style( 'wp-color-picker' );
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( '/js/color-pickr.js', __FILE__ ), 
                          array( 'wp-color-picker' ), false, true );   
        wp_enqueue_script('hf_scrollbeefup',HF_SCROLLBAR.'js/jquery.beefup.min.js',array('jquery'),
            '1.0.1',false);                              
        wp_enqueue_script('hf_scrolladmin',HF_SCROLLBAR.'js/hfscroll_admin.js', array('jquery'),
            '1.0',false);  
        wp_enqueue_style('hf_scroll-admin',HF_SCROLLBAR.'css/hf-scroll-admin.css'); 
}}
add_action( 'admin_enqueue_scripts', 'hf_scroll_color_picker' );
}

if ( is_admin() ) : // Load only if It is Admmin
if (!function_exists('hf_scrollbar_register_settings')) {
function hf_scrollbar_register_settings() {
    // Register settings and call sanitation functions 
    register_setting( 'hf_scroll_sf_options', 'hf_scroll_options','hf_scroll_validate_options' );
}
add_action( 'admin_init', 'hf_scrollbar_register_settings' );
}

    // Default options values
    $hf_scroll_g_options = array(
        'hfcursor_style' => "#000",
        'cursor_color' => "#000",
        'background_color' => "#f22",
        'cursor_width' => "10px",
        'cursorborder_radius' => "0px",
        'cursor_border_w' => "0px solid #fff",
        'cursor_border_s' => "solid",
        'cursor_border_c' => "#fff",
        'mousescroll_speed' => "60",
        'cursor_hide_options' => false,
    );
  // Auto hide  options
    $cursor_hide_options = array(
       'auto_hide_on' => array(
            'value' =>  'true', 
            'label' => 'Active Auto Hide',    
        ),
        'auto_hide_off' => array(
            'value' =>  'false', 
            'label' => 'Deactive Auto Hide',    
        ) );

  // Different Style
    $hfcursor_style = array(
        'hf_style_default' => array(
            'value' =>  'hf_scrollstyle_def', 
            'label' => 'Default Style',    
        ),
       'hf_style_one' => array(
            'value' =>  'hf_style_1', 
            'label' => 'Circle Style',    
        ),
        'hf_style_two' => array(
            'value' =>  'hf_scrollstyle_2', 
            'label' => 'Flat Style',    
        ));

        
if (!function_exists('hf_scroll_options_panel')) {
function hf_scroll_options_panel() {
    global $hf_scroll_g_options,$hfcursor_style,$cursor_hide_options,$scrollBar_borser_st ;

    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false; // Getting custom data from database ?>

    <div class="wrap">

        <h2>Awesome WP ScrollBar Settings </h2><hr>
    <?php if ( false !== $_REQUEST['updated'] ) : ?>
    <div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
    <?php endif; // When click done, it will show the notification ?>

    <form method="post" action="options.php">
        <?php $settings = get_option( 'hf_scroll_options', $hf_scroll_g_options ); ?>

        <?php settings_fields( 'hf_scroll_sf_options' );
        /* This function outputs some hidden fields required by the form,
        including a nonce, a unique number used to ensure the form has been submitted from the admin page
        and not somewhere else,it is most important for database security */ ?>
        
<article class="beefup">
    <h2 class="beefup-head">ScrollBar Settings</h2>
    <div class="beefup-body">
    <div class="hf_saved">
        <p class="submit"><input type="submit" class="button-primary" value="Done" /></p> 
    </div>
        <div id="pro" class="wp-preloader-single-option">
			<?php foreach( $hfcursor_style as $activate ) : ?>
           <div class="single_option" <?php checked( $settings['hfcursor_style'], $activate['value'] ); ?>>
            
			    <input type="radio" id="<?php echo $activate['value']; ?>" name="hf_scroll_options[hfcursor_style]" value="<?php esc_attr_e( $activate['value'] ); ?>" <?php checked( $settings['hfcursor_style'], $activate['value'] ); ?> />
            
			<label class="<?php  echo $activate['value']; ?>" for="<?php echo $activate['value']; ?>"><span id="<?php echo $activate['value']; ?>" class="label_img"></span> <p><?php echo $activate['label']; ?></p></label>
			</div>
			<?php endforeach; ?>		
		</div>
    <hr>
<table class="form-table">
<!--Auto hide here will Next-->
    <tr valign="top">
        <th scope="row"><label for="cursor_color">ScrollBar Color</label></th>
        <td>
            <input id="cursor_color" type="text" name="hf_scroll_options[cursor_color]" value="<?php echo stripslashes($settings['cursor_color']); ?>" class="color-field" /><p class="mydescription">Select ScrollBar Color here. You can also add html HEX color code here.Example: {  #000  }. For more color: <a href="http://www.w3schools.com/tags/ref_colorpicker.asp" target="_blank">click here</a></p> 
        </td>
    </tr>
    <tr valign="top">
        <th scope="row"><label for="background_color">Background Color</label></th>
        <td>
            <input id="background_color" type="text" name="hf_scroll_options[background_color]" value="<?php echo stripslashes($settings['background_color']); ?>" class="color-field" /><p class="mydescription">Select ScrollBar Background Color here. You can also add html HEX color code here.Example: {  #f22  }. For more color: <a href="http://www.w3schools.com/tags/ref_colorpicker.asp" target="_blank">click here</a></p> 
        </td>
    </tr>  

</table> 
    </div>
    		
</article>

<p class="submit"><input type="submit" class="button-primary" value="Done" /></p> 
    </form>
    </div>

<?php
}}
// validate data options

if (!function_exists('hf_scroll_validate_options')) {
    function hf_scroll_validate_options( $input ) {
        global $hf_scroll_g_options,$hfcursor_style,$cursor_hide_options,$scrollBar_borser_st ;

        $settings = get_option( 'hf_scroll_options', $hf_scroll_g_options );

        // We strip all tags from the text field, to avoid vulnerablilties like XSS
        $input['cursor_color'] = wp_filter_post_kses( $input['cursor_color'] );
        $input['background_color'] = wp_filter_post_kses( $input['background_color'] );
           
        $prev = $settings['layout_only'];
        if ( !array_key_exists( $input['layout_only'], $hfcursor_style ) )
        if ( !array_key_exists( $input['layout_only'], $cursor_hide_options ) )
        $input['cursor_hide_options'] = wp_filter_post_kses( $input['cursor_hide_options'] );            
		$input['layout_only'] = $prev;	
	
	return $input;
    }}
    endif; 


if (!function_exists('hf_scroolbar_plugin_active')) {
    function hf_scroolbar_plugin_active() {
       global $hf_scroll_g_options; $hf_scroll_n_dynamic_settings = get_option( 'hf_scroll_options', $hf_scroll_g_options );    
if ( $hf_scroll_n_dynamic_settings['hfcursor_style'] == 'hf_style_1' ) : 
?> 
     
      <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $("html").niceScroll({
                    cursorcolor : "<?php echo $hf_scroll_n_dynamic_settings['cursor_color']; ?>",
                    cursorborder : "5px solid #9b59b6",
                    background  : "<?php echo $hf_scroll_n_dynamic_settings['background_color']; ?>",
                    scrollspeed  : "60",
                    cursorminheight  : "50px",
                    cursorwidth  : "20px",
                    cursorborderradius  : "50px",
                    touchbehavior  : true,
                    bouncescroll  : true,
                    cursorfixedheight  : true,
                    horizrailenabled  : false,
                    rabcursorenabled  : false,
                    grabcursorenabled  : false,    
                    autohidemode : false,  
                });                
            });
        </script> 
<?php
      elseif( $hf_scroll_n_dynamic_settings['hfcursor_style'] == 'hf_scrollstyle_2') : 		
?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $("html").niceScroll({
                    cursorcolor : "<?php echo $hf_scroll_n_dynamic_settings['cursor_color']; ?> ",
                    cursorwidth  : "12px",
                    background   : "<?php echo $hf_scroll_n_dynamic_settings['background_color']; ?>",
                    cursorborder : "5px solid #2980b9",
                    cursorborderradius:"0px",
                    scrollspeed : "60",
                    touchbehavior  : true,
                    bouncescroll  : true,
                    horizrailenabled  : false,
                    grabcursorenabled  : false, 
                    autohidemode   : false,

                });                
            });
        </script>
<?php
        else : 	
?>
      <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $("html").niceScroll({
                    cursorcolor : "#f1c40f",
                    cursorwidth  : "20px",
                    cursorborder : "5px solid #8e44ad",
                    cursorborderradius  : "50px",
                    scrollspeed    : "60",
                    touchbehavior  : true,
                    bouncescroll  : true,
                    cursorfixedheight  : true,
                    cursorminheight  : "10px",
                    horizrailenabled  : false,
                    background  : "#95a5a6",
                    rabcursorenabled  : false,
                    grabcursorenabled  : false,    
                    autohidemode : false,
                });                
            });
        </script> 
<?php
endif;          
    }
    add_action('wp_head', 'hf_scroolbar_plugin_active');
}

    










