<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
/*
Plugin Name: DisplayCPGThumbnails
Plugin URI: none
Description: a simple plugin DisplayCPGThumbniails to display some thumbs from CPG Gallery in a Wordpress Widget
Author: macmiller
Version: 1.0
Author URI: none
*/
/**
 * DisplayCPGThumbnails Class
 */


include_once('DisplayCPGThumbnailsGuts.php');

class DisplayCPGThumbnails extends WP_Widget {

    /** constructor */
    function DisplayCPGThumbnails() {
        $widget_ops = array('classname' => 'displaycpgthumbnails', 'description' => 'This is a widget which displays thumbnail photos from a Coppermine gallery.  It displays either latest pics (last uploaded) or random with a number of other screens, such as max number of images per user or album.  Images are displayed in a table with user defined caption options.');
        $control_ops = array('width' => 300, 'id_base' => 'displaycpgthumbnails');
//      $control_ops = array('width' => 300);
        parent::WP_Widget('displaycpgthumbnails', 'Display CPG Thumbnails', $widget_ops, $control_ops);
// open text file for logging
//      $path = $_SERVER['PHP_SELF'];
//      if (strpos($path, "wp-admin")===FALSE) {
//         $logfile = fopen("./zzCPGlog.txt", "a+");
//      } else {
//         $logfile = fopen("../zzCPGlog.txt", "a+");
//      }
        date_default_timezone_set('Asia/Bangkok');
        $current_time = time();
        $fmt_current_time = date('Y-m-d H:i:s',$current_time);
//      fwrite($logfile, "----OPEN-----" . $fmt_current_time);
//      fwrite($logfile, "\n");
//      fclose($logfile);

    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
//      $path = $_SERVER['PHP_SELF'];
//      if (strpos($path, "wp-admin")===FALSE) {
//         $logfile = fopen("./zzCPGlog.txt", "a+");
//      } else {
//         $logfile = fopen("../zzCPGlog.txt", "a+");
//      }
//      fwrite($logfile, "--func widget--");
//      fwrite($logfile, "\n");
//      foreach ($args as $key=>$value) {
//         fwrite($logfile, $key . "=>" . $value . "\n");
//      }
//      fwrite($logfile, "explode instance" . "\n");
//      foreach ($instance as $key=>$value) {
//         fwrite($logfile, $key . "=>" . $value . "\n");
//      }
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $caption_str = $instance['caption_str'];
        $caption_pre = $instance['caption_pre'];
        $caption_len = $instance['caption_len'];
        $caption_con = $instance['caption_con'];
        $nbr_images = $instance['nbr_images'];
        $max_per_album = $instance['max_per_album'];
        $max_per_user = $instance['max_per_user'];
        $thumb_size_width = $instance['thumb_size_width'];
        $thumb_size_height = $instance['thumb_size_height'];
        $nbr_tbl_cols = $instance['nbr_tbl_cols'];
        $CPG_config_location = $instance['CPG_config_location'];
        $radioUPDorRND = $instance['radioUPDorRND'];
//      fwrite($logfile, "title->" . $title . "\n");
//      fwrite($logfile, "run parameters->" . $caption_str . "\n");
//      fwrite($logfile, "CPG_config_location->" . $CPG_config_location . "\n");
//      fwrite($logfile, "radioUPDorRND->" . $radioUPDorRND . "\n");
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
              <?php
              $cpgR = array();
//            fwrite($logfile, "T1119" . "\n");
              $cpgR = get_echo_cpg_info($caption_str,$caption_pre,$caption_len,$caption_con,$nbr_images,$max_per_album,$max_per_user,$thumb_size_width,$thumb_size_height,$nbr_tbl_cols,$CPG_config_location,$radioUPDorRND);
//            fwrite($logfile, "T1419" . "\n");
              if ($cpgR['ind'] === FALSE) {
                 echo $cpgR['msg'];
              }
//            if ($cpgR['ind'] === FALSE) {
//               fwrite($logfile, "cpgR[ind]->FALSE" . "\n");
//            } else {
//               fwrite($logfile, "cpgR[ind]->" . $cpgR['ind'] . "\n");
//            }
//            fwrite($logfile, "cpgR[msg]->" . $cpgR['msg'] . "\n");
              ?>
              <?php echo $after_widget; ?>
        <?php
//      fclose($logfile);
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
//      $path = $_SERVER['PHP_SELF'];
//      if (strpos($path, "wp-admin")===FALSE) {
//         $logfile = fopen("./zzCPGlog.txt", "a+");
//      } else {
//         $logfile = fopen("../zzCPGlog.txt", "a+");
//      }
//      fwrite($logfile, "--func update--" . "\n");
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['caption_str'] = strip_tags($new_instance['caption_str']);
	$instance['caption_pre'] = strip_tags($new_instance['caption_pre']);
	$instance['caption_len'] = strip_tags($new_instance['caption_len']);
	$instance['caption_con'] = strip_tags($new_instance['caption_con']);
	$instance['nbr_images'] = strip_tags($new_instance['nbr_images']);
	$instance['max_per_album'] = strip_tags($new_instance['max_per_album']);
	$instance['max_per_user'] = strip_tags($new_instance['max_per_user']);
	$instance['thumb_size_width'] = strip_tags($new_instance['thumb_size_width']);
	$instance['thumb_size_height'] = strip_tags($new_instance['thumb_size_height']);
	$instance['nbr_tbl_cols'] = strip_tags($new_instance['nbr_tbl_cols']);
	$instance['CPG_config_location'] = strip_tags($new_instance['CPG_config_location']);
	$instance['radioUPDorRND'] = strip_tags($new_instance['radioUPDorRND']);
        return $instance;
//      fclose($logfile);
    }

    /** @see WP_Widget::form */
    function form($instance) {
//      $path = $_SERVER['PHP_SELF'];
//      if (strpos($path, "wp-admin")===FALSE) {
//         $logfile = fopen("./zzCPGlog.txt", "a+");
//      } else {
//         $logfile = fopen("../zzCPGlog.txt", "a+");
//      }
//      fwrite($logfile, "--func formA--" . "\n");
//      foreach ($instance as $key => $value) {
//         fwrite($logfile, "instance['$key']" . '=>' . $value . "\n");
//      }
        

	$defaults = array( 'title' => 'Gallery Images', 
                           'CPG_config_location' => "{$_SERVER['DOCUMENT_ROOT']}" . '/mygallery/include/config.inc.php',
                           'radioUPDorRND' => 'UPD',
                           'nbr_images' => 4,
                           'max_per_album' => -1,
                           'max_per_user' => -1,
                           'thumb_size_width' => -1,
                           'thumb_size_height' => -1,
                           'nbr_tbl_cols' => 2,
                           'caption_str' => 'USR/DTE/TIT/CAP',
                           'caption_pre' => 'by / on / / /',
                           'caption_len' => ' /10/15/40',
                           'caption_con' => '//.../...');
	$instance = wp_parse_args( (array) $instance, $defaults );
//      fwrite($logfile, "T1133a" . "\n");
//      foreach ($instance as $key => $value) {
//         fwrite($logfile, "instance['$key']" . '=>' . $value . "\n");
//      }

        $title = esc_attr($instance['title']);
        $caption_str = esc_attr($instance['caption_str']);
        $caption_pre = esc_attr($instance['caption_pre']);
        $caption_len = esc_attr($instance['caption_len']);
        $caption_con = esc_attr($instance['caption_con']);
        $nbr_images = esc_attr($instance['nbr_images']);
        $max_per_album = esc_attr($instance['max_per_album']);
        $max_per_user = esc_attr($instance['max_per_user']);
        $thumb_size_width = esc_attr($instance['thumb_size_width']);
        $thumb_size_height = esc_attr($instance['thumb_size_height']);
        $nbr_tbl_cols = esc_attr($instance['nbr_tbl_cols']);
        $CPG_config_location = esc_attr($instance['CPG_config_location']);
        $radioUPDorRND = esc_attr($instance['radioUPDorRND']);
//      if (trim($nbr_images=="")) {$nbr_images=4;}
//      if (trim($max_per_album=="")) {$max_per_album = -1;}
//      if (trim($max_per_user=="")) {$max_per_user=-1;}
//      if (trim($thumb_size_width=="")) {$thumb_size_width=-1;}
//      if (trim($thumb_size_height=="")) {$thumb_size_height=-1;}
//      if (trim($CPG_config_location=="")) {$CPG_config_location='../mygallery/include/config.inc.php';}
        // variables:
        //   title = the title for the widget as displayed on the blog
        //   CPG_config_location = location for the CPG config file
        //   radioUPDorRND = indicates if data is returned for last updates or in random sequence
        //   nbr_images = indicates the total number of images to be returned
        //   max_per_album = max number per album
        //   max_per_user = max number per user
        //   thumb_size_width = width of return thumb or 00 for unchanged
        //   thumb_size_height = height of return thumb or 00 for unchanged
        //   

        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('CPG_config_location'); ?>"><?php _e('CPG Config Location:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('CPG_config_location'); ?>" name="<?php echo $this->get_field_name('CPG_config_location'); ?>" type="text" value="<?php echo $CPG_config_location; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('radioUPDorRND'); ?>"><?php _e('Random or Update:' );?></label>
          <?php
//        fwrite($logfile, "beforeIFstatementradio->" . $radioUPDorRND. "\n");
          $fldid = $this->get_field_id('radioUPDorRND');
          $flname = $this->get_field_name('radioUPDorRND');
          $line1UPD = "RND<input class=\"checkbox\" id=\"$fldid\" name=\"$flname\" type=\"radio\" size = 2 value=\"RND\" />";
          $line2UPD = "UPD<input class=\"checkbox\" id=\"$fldid\" name=\"$flname\" type=\"radio\" size = 2 value=\"UPD\" checked />";
          $line1RND = "RND<input class=\"checkbox\" id=\"$fldid\" name=\"$flname\" type=\"radio\" size = 2 value=\"RND\" checked />";
          $line2RND = "UPD<input class=\"checkbox\" id=\"$fldid\" name=\"$flname\" type=\"radio\" size = 2 value=\"UPD\" />";
//        fwrite($logfile, "line1UPD->" . $line1UPD . "\n");
//        fwrite($logfile, "fldid->" . $fldid . "\n");
          if($radioUPDorRND=='UPD') {
              echo $line1UPD;
              echo $line2UPD, "<br>";
          } else {
              echo $line1RND;
              echo $line2RND, "<br>";
          }
          ?>
        </p>
        <p style="width:48%;float:left;">
          <label for="<?php echo $this->get_field_id('nbr_images'); ?>"><?php _e('Number Images:' );?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('nbr_images'); ?>" name="<?php echo $this->get_field_name('nbr_images'); ?>" type="number" size = 2 value="<?php echo $nbr_images; ?>" />
       </p>
        <p style="width:48%;float:right;">
          <label for="<?php echo $this->get_field_id('max_per_album'); ?>"><?php _e('Max Per Album:' );?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('max_per_album'); ?>" name="<?php echo $this->get_field_name('max_per_album'); ?>" type="number" value="<?php echo $max_per_album; ?>" />
       </p>
        <p style="width:48%;float:left;">
          <label for="<?php echo $this->get_field_id('max_per_user'); ?>"><?php _e('Max Per User:' );?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('max_per_user'); ?>" name="<?php echo $this->get_field_name('max_per_user'); ?>" type="number" size = 2 value="<?php echo $max_per_user; ?>" />
       </p>
        <p style="width:48%;float:right;">
          <label for="<?php echo $this->get_field_id('thumb_size_width'); ?>"><?php _e('Thumb Width:' );?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('thumb_size_width'); ?>" name="<?php echo $this->get_field_name('thumb_size_width'); ?>" type="number" value="<?php echo $thumb_size_width; ?>" />
       </p>
        <p style="width:48%;float:left;">
          <label for="<?php echo $this->get_field_id('thumb_size_height'); ?>"><?php _e('Thumb Height:' );?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('thumb_size_height'); ?>" name="<?php echo $this->get_field_name('thumb_size_height'); ?>" type="number" size = 2 value="<?php echo $thumb_size_height; ?>" />
       </p>
        <p style="width:48%;float:right;">
          <label for="<?php echo $this->get_field_id('nbr_tbl_cols'); ?>"><?php _e('NBR of TBL Cols:' );?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('nbr_tbl_cols'); ?>" name="<?php echo $this->get_field_name('nbr_tbl_cols'); ?>" type="number" value="<?php echo $nbr_tbl_cols; ?>" />
       </p>
         <p style="clear:both;">
          <label for="<?php echo $this->get_field_id('caption_str'); ?>"><?php _e('Caption Options:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('caption_str'); ?>" name="<?php echo $this->get_field_name('caption_str'); ?>" type="text" value="<?php echo $caption_str; ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('caption_pre'); ?>"><?php _e('Caption Prefixes:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('caption_pre'); ?>" name="<?php echo $this->get_field_name('caption_pre'); ?>" type="text" value="<?php echo $caption_pre; ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('caption_len'); ?>"><?php _e('Caption Lengths:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('caption_len'); ?>" name="<?php echo $this->get_field_name('caption_len'); ?>" type="text" value="<?php echo $caption_len; ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('caption_con'); ?>"><?php _e('Caption Cont. Text:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('caption_con'); ?>" name="<?php echo $this->get_field_name('caption_con'); ?>" type="text" value="<?php echo $caption_con; ?>" />
        </p>
        <?php

        echo "<br>";
        echo "<br>";
        echo "<label for=\"Path_Info\">Document Root Path info to aid in setting config path(info only)</label>";
        echo "<p class=\"widefat\">";
        echo $_SERVER['DOCUMENT_ROOT'];
        echo "</p>";

        echo "<br>";
        echo "<br>";
        echo "<label for=\"Param_Validation\">Parameter Validation Return Area (Don't Enter)</label>";
        $indText = "Entered Values Are OK";
        // ck the entered $CPG_config_location from the admin panel we must append one ../ to get us out of the admin.  
        if ( (trim($nbr_images) == "") || (!is_numeric(trim($nbr_images))) || ((int)$nbr_images > 100) || ((int)$nbr_images < 1)){
           $indText = "Number of Images must be specified and must be a number between 1 and 100 inclusive";
        }
        if ( (trim($max_per_album) == "") || (!is_numeric(trim($max_per_album))) || ((int)$max_per_album > 5) || ((int)$max_per_album == 0)){
           $indText = "Max per album must be specified but is disregarded if set to -1.  Numeric integer between 1 and 5 inclusive";
        }
        if ( (trim($max_per_user) == "") || (!is_numeric(trim($max_per_user))) || ((int)$max_per_user > 5) || ((int)$max_per_user == 0)){
           $indText = "Max per user must be specified but is disregarded if set to -1.  Numeric integer between 1 and 5 inclusive";
        }
        if ( (trim($thumb_size_width) == "") || (!is_numeric(trim($thumb_size_width))) || ((int)$thumb_size_width > 100) || ( ((int)$thumb_size_width <50) && ((int)$thumb_size_width != -1)) ){
           $indText = "Thumb size width must be entered and a numeric value.  It is disregarded (not used) if -1, otherwise must be between 50 and 100 inclusive";
        }
        if ( (trim($thumb_size_height) == "") || (!is_numeric(trim($thumb_size_height))) || ((int)$thumb_size_height > 100) || ( ((int)$thumb_size_height <50) && ((int)$thumb_size_height != -1)) ){
           $indText = "Thumb size height must be entered and a numeric value.  It is disregarded (not used) if -1, otherwise must be between 50 and 100 inclusive";
        }
        if ( (trim($nbr_tbl_cols) == "") || (!is_numeric(trim($nbr_tbl_cols))) || ((int)$nbr_tbl_cols > 12) || ( ((int)$nbr_tbl_cols <1) && ((int)$nbr_tbl_cols != -1)) ){
           $indText = "Invalid Number of HTML Table Columns.  To disregard and not use table html enter -1.  Otherwise must be between 1 and 12 inclusive";
        }
        $caption_opt = array ();
        if (trim($caption_str) != "") {
           $caption_opt = explode ("/", $caption_str);
           foreach ($caption_opt as $key => $value) {
              if (!( ($value == "TIT") || ($value == "CAP") || ($value == "USR") || ($value == "DTE") )) {
                 $indText = "Invalid caption options.  If entered must be options of TIT, CAP, USR, DTE (any number of them) separated by /";
              }
           }
        }

//      $CKonlyCPG_config_location = '../' . $CPG_config_location;
//      fwrite($logfile, "------t1144------" . "\n");
//      fwrite($logfile, $CKonlyCPG_config_location . "\n");
        if (trim($CPG_config_location) == "") {
           $indText = "CPG config location is required";
        } elseif (!file_exists($CPG_config_location)) {
           $indText = "CPG config file can't be opened.  This is the full system path which may be something like /srv/www/virtual/yoursys.com/htdocs/yourgallery/include/config.inc.php";
        }
        echo "<p class=\"widefat\">";
        echo $indText;
        echo "</p>";

//      fwrite($logfile, "\n");
//      fwrite($logfile, "title->" . $title . "\n");
//      fwrite($logfile, "caption_str->" . $caption_str . "\n");
//      fwrite($logfile, "nbr images->" . $nbr_images . "\n");
//      fwrite($logfile, "max per album->" . $max_per_album . "\n");
//      fwrite($logfile, "max per user->" . $max_per_user . "\n");
//      fwrite($logfile, "thumb size width->" . $thumb_size_width . "\n");
//      fwrite($logfile, "thumb size height->" . $thumb_size_height . "\n");
//      fwrite($logfile, "CPG_config_location->" . $CPG_config_location . "\n");
//      fwrite($logfile, "radioUPDorRND->" . $radioUPDorRND . "\n");
//      fclose($logfile);
/*
        if ($radioUPDorRND=='UPD') {
           echo '<p>update</p>', "<br>";
        } else {
           echo '<p>random</p>', "<br>";
        }
*/
        ?>
        <?php 
    }

} // class DisplayCPGThumbnails

// register DisplayCPGThumbnails widget
add_action('widgets_init', create_function('', 'return register_widget("DisplayCPGThumbnails");'));