<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
/*
The main routine for the CPGWidget plug in.
*/
/*
    Copyright (C) 2010  macmiller

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
	certain attributes may be filtered out upon saving
	declare them as safe here
	of course, you should move any of your changes to another
	files so future updates don't overwrite your changes
*/
define("BRIDGEDtoPHPBB3", TRUE);
define("sqldb_MAX_READS", -1);
define("scaleImages", FALSE);
define("sizeDownOnly", TRUE);

function validate_parms($captionStra,$captionPrea,$captionLena,$captionCona,$nbrIMGa,$maxALBa,$maxUSERa,$thbWidtha,$thbHeighta,$nbrTCa,$conLa,$updORrnda) {
// $path = $_SERVER['PHP_SELF'];
// if (strpos($path, "wp-admin")===FALSE) {
//    $logfile = fopen("./zzCPGlog.txt", "a+");
// } else {
//    $logfile = fopen("../zzCPGlog.txt", "a+");
// }
   if (!isset($connCPGa)) {
      $connCPGa = FALSE;
   }
// fwrite($logfile, "--validate parms--" . "\n");
// fwrite($logfile, "captionStra->" . $captionStra . "\n");
// fwrite($logfile, "nbrIMGa->" . $nbrIMGa . "\n");
// fwrite($logfile, "maxALBa->" . $maxALBa . "\n");
// fwrite($logfile, "maxUSERa->" . $maxUSERa . "\n");
// fwrite($logfile, "thbWidtha->" . $thbWidtha . "\n");
// fwrite($logfile, "thbHeighta->" . $thbHeighta . "\n");
// fwrite($logfile, "nbrTCa->" . $nbrTCa . "\n");
// fwrite($logfile, "conLa->" . $conLa . "\n");
// fwrite($logfile, "updORrnda->" . $updORrnda . "\n");

   $returnvara = array();
   $CPGdba = array();
   $phpBBdba = array();
   $caption_opta = array ();
   $caption_prea = array ();
   $caption_lena = array ();
   $caption_cona = array ();
   if (!trim($captionStra == "")) {
      $caption_opta = explode ("/", $captionStra);
      foreach ($caption_opta as $key08 => $value08) {
         if (!( ($value08 == "TIT") || ($value08 == "CAP") || ($value08 == "USR") || ($value08 == "DTE") )) {
            $returnvara['msg'] = "Invalid caption options.  If entered must be options of TIT, CAP, USR, DTE (any number of them) separated by /";
            $returnvara['ind'] = FALSE;
            return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
         }
      }
   }
   if (!trim($captionPrea == "")) {
      $caption_prea = explode ("/", $captionPrea);
   }
   if (!trim($captionLena == "")) {
      $caption_lena = explode ("/", $captionLena);
   }
   if (!trim($captionCona == "")) {
      $caption_cona = explode ("/", $captionCona);
   }
   if ( (trim($nbrIMGa) == "") || (!is_numeric(trim($nbrIMGa))) || ((int)$nbrIMGa > 100) || ((int)$nbrIMGa < 1)){
      $returnvara['msg'] = "Number of Images must be specified and must be a number between 1 and 100 inclusive";
      $returnvara['ind'] = FALSE;
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
   if ( (trim($maxALBa) == "") || (!is_numeric(trim($maxALBa))) || ((int)$maxALBa > 5) || ((int)$maxALBa == 0)){
      $returnvara['msg'] = "Max per album must be specified but is disregarded if set to -1.  Numeric integer between 1 and 5 inclusive";
      $returnvara['ind'] = FALSE;
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
   if ( (trim($maxUSERa) == "") || (!is_numeric(trim($maxUSERa))) || ((int)$maxUSERa > 5) || ((int)$maxUSERa == 0)){
      $returnvara['msg'] = "Max per user must be specified but is disregarded if set to -1.  Numeric integer between 1 and 5 inclusive";
      $returnvara['ind'] = FALSE;
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
   if ( (trim($thbWidtha) == "") || (!is_numeric(trim($thbWidtha))) || ((int)$thbWidtha > 100) || ( ((int)$thbWidtha <50) && ((int)$thbWidtha != -1)) ){
      $returnvara['msg'] = "Thumb size width must be entered and a numeric value.  It is disregarded (not used) if -1, otherwise must be between 50 and 100 inclusive";
      $returnvara['ind'] = FALSE;
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
   if ( (trim($thbHeighta) == "") || (!is_numeric(trim($thbHeighta))) || ((int)$thbHeighta > 100) || ( ((int)$thbHeighta <50) && ((int)$thbHeighta != -1)) ){
      $returnvara['msg'] = "Thumb size height must be entered and a numeric value.  It is disregarded (not used) if -1, otherwise must be between 50 and 100 inclusive";
      $returnvara['ind'] = FALSE;
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
   if ( (trim($nbrTCa) == "") || (!is_numeric(trim($nbrTCa))) || ((int)$nbrTCa > 12) || (((int)$nbrTCa < 1) && ((int)$nbrTCa != -1))   ){
      $returnvara['msg'] = "Invalid Nbr HTML columns.  To disregard enter -1.  Max value is 12 and must not be equal to zero";
      $returnvara['ind'] = FALSE;
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
   $CPGdbIncluded = FALSE;
   if(file_exists($conLa)) {
//    fwrite($logfile, "Z4444" . "\n");
      include($conLa);
//    foreach ($CONFIG as $key=>$value) {
//       fwrite($logfile, $key . "=>" . $value . "\n");
//    }
      // these CPG db variables are needed to open a connection
      $CPGdba = $CONFIG;
//    fwrite($logfile, "CPGdba[dbname]" . $CPGdba['dbname'] . "\n");
      $CPGdbIncluded = TRUE;
   } else {
//    fwrite($logfile, "Z4450" . "\n");
      $returnvara['ind'] = FALSE;
      $returnvara['msg'] = 'unable to open indicated CPG config file:' . $conLa;
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
//return multiple vars use list
//list($vara, $varb) = function_call_a ($a, $b, $c) {

   if (($updORrnda != 'UPD') && ($updORrnda != 'RND')) {
      $returnvara['ind'] = FALSE;
      $returnvara['msg'] = 'invalid value for update or random indicator:' . $updORrnd;
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
   // open a CPG connection
   if (!isset($connCPGa) || ($connCPGa === FALSE)) {
      $connCPGa = mysql_connect($CPGdba['dbserver'],$CPGdba['dbuser'],$CPGdba['dbpass'],TRUE);
   }
   if (!$connCPGa) {
      $returnvara['ind'] = FALSE;
      $returnvara['msg'] = 'CPG mysql_connect failed->' . mysql_error();
      return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
   }
   if (BRIDGEDtoPHPBB3) {
//    fwrite($logfile, "Z4455" . "\n");
      if ( (!isset($phpBBconfigLoc)) && ($CPGdbIncluded)) {
//       fwrite($logfile, "A1000" . "\n");
         $sel01 = mysql_select_db($CPGdba['dbname']);
//       fwrite($logfile, $CPGdba['dbname'] . "\n");
         if (!$sel01) {
            $returnvara['ind'] = FALSE;
            $returnvar['msg'] = 'error selecting DB while getting bridge info->' . mysql_error();
            return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
         }
         
         // the bridge file in CPG tells us where the phpBB3 config file is located
         $sql_command01 = "SELECT * FROM {$CPGdba['TABLE_PREFIX']}bridge WHERE {$CPGdba['TABLE_PREFIX']}bridge.name = 'full_forum_url' LIMIT 1";
//       $sql_command01 = "SELECT * FROM {$CPGdba['TABLE_PREFIX']}bridge WHERE {$CPGdba['TABLE_PREFIX']}bridge.name = 'relative_path_to_config_file' LIMIT 1";
         $sql_result01 = mysql_query($sql_command01,$connCPGa);
         if (!$sql_result01) {
            $returnvara['ind'] = FALSE;
            $returnvara['msg'] = "error in mysql_query while getting bridge info->" . $sql_result;
            return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
         }
         $sql_numrows01 = 0;
         $sql_numrows01 = mysql_num_rows($sql_result01);
         if ($sql_numrows01 == 0) {
            $returnvara['ind'] = FALSE;
            $returnvara['msg'] = "'full_forum_url' record not found in bridge table while getting bridge info";
            return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
         }
         if ($sql_numrows01 > 1) {
            $returnvara['ind'] = FALSE;
            $returnvara['msg'] = "'full_forum_url' mult recs found in bridge table while getting bridge info";
            return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
         }
         while ($row01 = mysql_fetch_array($sql_result01)) {
            $p01value = $row01["value"];
            list ($returnvara1, $fileFMTa1) = makeFileformat($p01value);
            if ($returnvara1['ind'] === FALSE) {
               $returnvara = $returnvara1;
               return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
            }
            $phpBBconfigLoc = $fileFMTa1 . '/config.php';
            if(file_exists($phpBBconfigLoc)) {
               include($phpBBconfigLoc);
               $phpBBdba = array ('dbhost' => $dbhost, 'dbname' => $dbname, 'dbuser' => $dbuser, 'dbpasswd' => $dbpasswd, 'table_prefix' => $table_prefix);
            } else {
               $returnvara['ind'] = FALSE;
               $returnvara['msg'] = "config file for bridge couldn't be opened->" . $phpBBconfigLoc;
               return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
            } // if(file_exists($phpBBconfigLoc)) {
         } // while ($row01 = mysql_fetch_array($sql_result01)) {
      } // if ( (!isset($phpBBconfigLoc)) && ($CPGdbIncluded)) {
   } // if (BRIDGEDtoPHPBB3) {
// foreach ($returnvara as $key=>$value) {
//    fwrite($logfile, $key . "=>" . $value . "\n");
// }
// fclose($logfile);
   $returnvara['ind'] = TRUE;
   $returnvara['msg'] = "variables pass ck";
   return array ($returnvara, $CPGdba, $phpBBdba, $caption_opta, $caption_prea, $caption_lena, $caption_cona, $connCPGa);
}

function get_user_str($caption_optb, $phpBBdbb, $CPGdbb, $connCPGb, $pownerb) {
// $path = $_SERVER['PHP_SELF'];
// if (strpos($path, "wp-admin")===FALSE) {
//    $logfile = fopen("./zzCPGlog.txt", "a+");
// } else {
//    $logfile = fopen("../zzCPGlog.txt", "a+");
// }
// fwrite($logfile, "--get user--" . "\n");
   $userSTRb = "not found";
   $returnvarb['ind'] = TRUE;
   $returnvarb['msg'] = 'user string processing complete';

   if (!(in_array("USR", $caption_optb))) {
      return $userSTRb;
   }
// fwrite($logfile, "C4400" . "\n");
   if (BRIDGEDtoPHPBB3) {
// there is only one datafile accessed from the phpbb3 database, only in the case of bridging and it is only accessed
// locally in this function.  
// this is the users table.  we must open the connection to the db here only in the case it already hasn't been opened

//    fwrite($logfile, "C4402" . "\n");
      if (!isset($connPHPbb)) {
//       fwrite($logfile, "C4404" . "\n");
         $connPHPbb = mysql_connect($phpBBdbb['dbhost'],$phpBBdbb['dbuser'],$phpBBdbb['dbpasswd'],TRUE);
         if (!$connPHPbb) {
            $returnvarb['ind'] = FALSE;
            $returnvarb['msg'] = 'phpBB mysql_connect failed->' . mysql_error();
            return array ($returnvarb, $userSTRb);
         }
      }
//    fwrite($logfile, "C4406" . "\n");
      $sel02 = mysql_select_db($phpBBdbb['dbname'], $connPHPbb);
      if (!$sel02) {
         $returnvarb['ind'] = FALSE;
         $returnvarb['msg'] = 'phpBB mysql_select_db failed->' . mysql_error();
         return array ($returnvarb, $userSTRb);
      }
//    fwrite($logfile, "C4408" . "\n");
      $sql_command02 = "SELECT * FROM {$phpBBdbb['table_prefix']}users WHERE {$phpBBdbb['table_prefix']}users.user_id = '$pownerb' LIMIT 1;";
//    fwrite($logfile, $sql_command02 . "\n");
      $sql_result02 = mysql_query($sql_command02,$connPHPbb);
   } else {
//    fwrite($logfile, "C4410" . "\n");
      $sql_command02 = "SELECT * FROM {$CPGdbb['TABLE_PREFIX']}users WHERE {$CPGdbb['TABLE_PREFIX']}users.user_id = '$pownerb' LIMIT 1;";
      $sql_result02 = mysql_query($sql_command02,$connCPGb);
   } // if (BRIDGEDtoPHPBB3) {
// fwrite($logfile, "C4412" . "\n");
   if (!$sql_result02) {
      $userSTRb = "not found->" . $pownerb;
      return array ($returnvarb, $userSTRb);
   } // if (!$sql_result02) {
// fwrite($logfile, "C4414" . "\n");
   $sql_numrows02 = 0;
   $sql_numrows02 = mysql_num_rows($sql_result02);
// fwrite($logfile, "C4416" . "\n");
   if ($sql_numrows02 == 0) {
      $userSTR = "not found->" . $pownerb;
      return array ($returnvarb, $userSTRb);
   } // if ($sql_numrows02 == 0) {
   while ($row02 = mysql_fetch_array($sql_result02)) {
//    fwrite($logfile, "C4418" . "\n");
      $p02username_clean = $row02["username_clean"];
      $userSTRb = $p02username_clean;
      return array ($returnvarb, $userSTRb);
   } // while ($row02 = mysql_fetch_array($sql_result02)) {
}

function makeURLformat($fileFMT) {
// function to return URL format when given file FMT
   $returnvarc = array();
   $urlFMT = "";
   $rightPartofURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$fileFMT,$nbrREPL);
   if($nbrREPL == 0) {
      $returnvarc['ind'] = FALSE;
      $returnvarc['msg'] = 'error determining URL address with the following unresolved->' . $_SERVER['DOCUMENT_ROOT'];
      return array ($returnvarc, $urlFMT);
   }
   $urlFMT = 'http://' . $_SERVER['SERVER_NAME'] . $rightPartofURL;
   $returnvarc['ind'] = TRUE;
   $returnvarc['msg'] = 'filepath converted to URL';
   return array ($returnvarc, $urlFMT);
}

function makeFILEformat($urlFMT) {
   $returnvard = array();
   $fileFMT = "";
   $serverLOC = strpos($urlFMT,$_SERVER['SERVER_NAME'],0);
   if($serverLOC === FALSE) {
      $returnvard['ind'] = FALSE;
      $returnvard['msg'] = $_SERVER['SERVER_NAME'] . ' not found in ->' . $urlFMT;
      return array ($returnvard, $fileFMT);
   }
   $serverLen = strlen($_SERVER['SERVER_NAME']);
   $serverLOCr = $serverLOC + $serverLen;
   $urlLen = strlen($urlFMT);
   $rightPartLen = $urlLen - $serverLOCr;
   $rightpartofURL = substr($urlFMT,$serverLOCr,$rightPartLen);
   $fileFMT = $_SERVER['DOCUMENT_ROOT'] . $rightpartofURL;
   $returnvard['ind'] = TRUE;
   $returnvard['msg'] = 'interim string production->' . $fileFMT . '->' . $serverLOC;
   return array ($returnvard, $fileFMT);
}

function get_echo_cpg_info($captionStr,$captionPre,$captionLen,$captionCon,$nbrIMG,$maxALB,$maxUSER,$thbWidth,$thbHeight,$nbrTC,$conL,$updORrnd) {

// $path = $_SERVER['PHP_SELF'];
// if (strpos($path, "wp-admin")===FALSE) {
//    $logfile = fopen("./zzCPGlog.txt", "a+");
// } else {
//    $logfile = fopen("../zzCPGlog.txt", "a+");
// } // if (strpos($path, "index.php")===FALSE) {
// fwrite($logfile, "--get_echo_cpg_info--" . "\n");
   $li_thumb_prefix03 = "thumb_";
// fwrite($logfile, "z1110a" . "\n");

// spacing vars for HTML
   $ht_line_prefix = "	                    ";
   $ht_cr = "\n";
   $ht_spaces = "   ";
   $ht_tab = chr(9);


   // verify parameters, make connection to CPG db, include CPG config and wells a phpBB3 config if bridged
   list ($returnvar, $CPGdb, $phpBBdb, $caption_opt, $caption_pre, $caption_len, $caption_con, $connCPG) = validate_parms($captionStr,$captionPre,$captionLen,$captionCon,$nbrIMG,$maxALB,$maxUSER,$thbWidth,$thbHeight,$nbrTC,$conL,$updORrnd);
   if (!$returnvar['ind']) {return $returnvar;}

// fwrite($logfile, "z0110a" . "\n");
   $sel = mysql_select_db($CPGdb['dbname']);
// fwrite($logfile, $CPGdb['dbname'] . "\n");
   if (!$sel) {
      $returnvar['ind'] = FALSE;
      $returnvar['msg'] = mysql_error();
      return $returnvar;
   } // if (!$sel) {
// fwrite($logfile, "z0010a" . "\n");
   if (sqldb_MAX_READS == -1) {$maxLIMstr ="";}
   else {$maxLIMstr = 'limit ' . sqldb_MAX_READS;}
   $dbTBLprefix = $CPGdb['TABLE_PREFIX'];
   $falbTBL = $dbTBLprefix . 'albums';
   $fpicTBL = $dbTBLprefix . 'pictures';
   $sqlPART1 = "select " . $fpicTBL . ".*, " . $falbTBL . ".owner ";
   $sqlPART2 = "FROM " . $fpicTBL . "," . $falbTBL . " ";
   $sqlPART3 = "WHERE " . $fpicTBL . ".aid = " . $falbTBL . ".aid" . " ";
   $sqlPART4a = "ORDER BY rand() DESC " . $maxLIMstr . ";";
   $sqlPART4b = "ORDER BY ctime DESC " . $maxLIMstr . ";";

   if ( $updORrnd == 'RND' ) {
      $sql_command = $sqlPART1 . $sqlPART2 . $sqlPART3 . $sqlPART4a;
   } else {
      $sql_command = $sqlPART1 . $sqlPART2 . $sqlPART3 . $sqlPART4b;
   } //if ( $updORrnd == 'RND' ) {
// fwrite($logfile, $sql_command . "\n");
   $saveOWN = array ();
   $saveALB = array ();
   $sql_result = mysql_query($sql_command,$connCPG);
   if (!$sql_result) {
      $returnvar['ind'] = FALSE;
      $returnvar['msg'] = "error in mysql_query " . $sql_result;
      return($returnvar);
   } //if (!$sql_result) {
   $sql_numrows = 0;
   $sql_numrows = mysql_num_rows($sql_result);
   if ($sql_numrows == 0) {
      $returnvar['ind'] = FALSE;
      $returnvar['msg'] = "NO DATA IN SOURCE Pictures FILE";
      return($returnvar);
   } //if (!$sql_result) {
   $img_reccnt = 0;
   $last_album_selected = "";
   $last_user_selected = "";
   $html_imgpart = array ();
   $html_cappart = array ();
   $first_rec = TRUE;
// table to keep track of hits per album/user
// key to use pid, 

   while ($row = mysql_fetch_array($sql_result)) {
// this loops throug all the records
      $ppid = $row["pid"];
      $paid = $row["aid"];
      $pfilepath = $row["filepath"];
      $pfilename = $row["filename"];
      $ptitle = $row["title"];
      $pcaption = $row["caption"];
      $powner = $row["owner"];
      $pctime = $row["ctime"];
      date_default_timezone_set('Asia/Bangkok');
      $fmt_ctime_time = date('Y-m-d H:i:s',$pctime);
//    fwrite($logfile, "pid->" . $ppid);
//    fwrite($logfile, "aid->" . $paid);
//    fwrite($logfile, "powner->" . $powner);
         
      if ($img_reccnt >= $nbrIMG) {break;}
// loop though all records
// for each record 
//    compare total selected so far for owner/album and see if this one meets the criteria
// test alb
      $process_rec = TRUE;
      if (($maxALB != -1) && (isset($saveALB[$paid])) && ($saveALB[$paid] >=  $maxALB)){
         $process_rec = FALSE;
      } // if (($maxALB != -1) && (isset($saveALB[$paid])) && ($saveALB[$paid] >=  $maxALB)){
      if (($maxUSER != -1) && (isset($saveOWN[$powner])) && ($saveOWN[$powner] >=  $maxUSER)){
         $process_rec = FALSE;
      } //if (($maxUSER != -1) && (isset($saveOWN[$powner])) && ($saveOWN[$powner] >=  $maxUSER)){
//    test code
//    if (($paid > 10) || ($powner > 200)) {
//       $process_rec = FALSE;
//    }// if (($paid > 10) || ($powner > 200)) {
//    fwrite($logfile, "process->" . $process_rec . "\n");
//    building a link to return.  
//    the config file location came accoss as ../gallery/includes/configfile  
//    the fully formed url for the image will be something like
//    ../gallery/ + filepath/ + filename
//    need to assume that the significant part to parse is the part to the left of the includes
      if ($process_rec) {
//       take and make $conL into URL format
//       fwrite($logfile, "T7777->" . $conL . "\n");
         list ($returnvar, $conLURL) = makeURLformat($conL);
//       fwrite($logfile, "T1188->" . $returnvar['ind'] . "\n");
//       fwrite($logfile, "T1188a->" . $returnvar['msg'] . "\n");
//       fwrite($logfile, "T1188b->" . $conLURL . "\n");
         if (!$returnvar['ind']) {
            return($returnvar);
         }
//       ck to see if USER name is needed
         list ($returnvar, $userSTR) = get_user_str($caption_opt, $phpBBdb, $CPGdb, $connCPG, $powner);
         if (!$returnvar['ind']) {
            return($returnvar);
         } // if (!$returnvar['ind']) {
         $strINCL = strpos($conLURL, "/include/", 0);
         if ($strINCL === FALSE) {
            $returnvar['ind'] = FALSE;
            $returnvar['msg'] = "invalid path for CPG gallery doesn't have include folder ->" . $conLURL;
            return $returnvar;
         } else { 
            $leftPartofURL = substr($conLURL, 0, $strINCL);
//          fwrite($logfile, "T1488->" . $leftPartofURL . "\n");
         } // if ($strINCL === FALSE) {
//    a sample of tags to be included:
//    <td><a href="http://somesite.com/gallery/displayimage.php?pos=-10054"  ><img  src="http://somesite.com/gallery/albums/userpics/10291/thumb_IMG_2128.jpg" alt="IMG_2128.jpg" title="IMG_2128.jpg"  /></a><br /><br /><center>Dad and Son <br> 2 Views  </center></td>
             
         $usePicCap = "";
         foreach($caption_opt as $key09 => $value09) {
            $thisSingleOption = "";
            switch ($value09) {
               case "CAP": 
                  $thisSingleOption = $pcaption;
                  break;
               case "TIT":
                  $thisSingleOption = $ptitle;
                  break;
               case "USR":
                  $thisSingleOption = $userSTR;
                  break;
               case "DTE":
                  $thisSingleOption = $fmt_ctime_time;
                  break;
            } // switch ($value09) {
//          if (isset($caption_len[$key09])) {
//             fwrite($logfile, "caption_len[key09]->" . $caption_len[$key09] . "\n");
//          }
            $thisSingleOptionTruncT = $thisSingleOption;
            if ( (isset($caption_len[$key09])) && (is_numeric($caption_len[$key09])) && ((int)$caption_len[$key09] > 0) ) {
               $thisSingleOption = substr($thisSingleOption, 0, (int)$caption_len[$key09]);
            }
            if ( ($thisSingleOption != $thisSingleOptionTruncT) && (isset($caption_con[$key09])) ) {
               $thisSingleOption .= $caption_con[$key09];
            }
            if ( (isset($caption_pre[$key09])) ) {
               $thisSingleOption = $caption_pre[$key09] . $thisSingleOption;
            }
            $usePicCap .= $thisSingleOption; 
         } // foreach($caption_opt as $key01 => $value09) {
         $useTitle = "";
         $useTitle = (trim($ptitle)) . " " . (trim($pcaption));
         if (strlen($useTitle) > 40) {
            $useTitle = substr($useTitle, 0, 40);
            $useTitle = $useTitle . "...";
         } // if (strlen($useTitle) > 40) {
         if (trim($useTitle) == "") {
            $useTitle = $pfilename;
         }
         $useTitle = $useTitle . '"';
//       $useTitle = $paid . "/" . $powner . "/" . $useTitle;
         $li_thumb_prefix01 = "<a href=\"";
         $wk_thumb_prefix02 = $leftPartofURL . "/albums/";
         $li_thumb_prefix03 = "thumb_";
         $li_thumb_prefix04 = '">';
         $li_thumb_prefix05 = "<img src=\"";
         $wk_CPG_URL = $leftPartofURL . "/displayimage.php?pid=" . $ppid;
// height and width need to be inline css to override style sheet
// syntax will be style="width: xx; height: xx;"
         $wk_width_inline = "";
         $wk_height_inline = "";
         $wk_size_str_inline = "";
         $li_thumb_fn = $wk_thumb_prefix02 . $pfilepath . $li_thumb_prefix03 . $pfilename;
//       $li_thumb_fn_F = $_SERVER['DOCUMENT_ROOT'] . 
//       fwrite($logfile, 'T1764->' . $li_thumb_fn . "\n");

         list($returnvar, $li_thumb_fn_FILE) = makeFILEformat($li_thumb_fn);
         if ($returnvar['ind'] === FALSE) {
            return $returnvar;
         }
         if (file_exists($li_thumb_fn_FILE)) {
//          fwrite($logfile, 'T9764a->' . $li_thumb_fn . "\n");
            $img_size = array();
            $img_size = getimagesize($li_thumb_fn);
            if ($img_size === FALSE) {
               $returnvar['ind'] = FALSE;
               $returnvar['msg'] = "getimagesize error ->" . $li_thumb_fn;
               return $returnvar;
            }
//          fwrite($logfile, 'T9764b->' . $img_size . "\n");
            $img_width = $img_size[0];
            $img_height = $img_size[1];
            if (($thbWidth != -1) && ($thbHeight != -1) && (!scaleImages)) {
               if (($img_width > $thbWidth) && ($img_height > $thbHeight)) {
                  $wk_width_inline = "width: " . $thbWidth . "px;";
               } elseif ($img_width > $thbWidth) {
                  $wk_width_inline = "width: " . $thbWidth . "px;";
               } elseif ($img_height > $thbHeight) {
                  $wk_height_inline = "height: " . $thbHeight . "px;";
               } else {
                  $wk_width_inline = "width: " . $thbWidth . "px;";
               } //if (($img_width > $thbWidth) && ($img_height > $thbHeight)) {
            } else { // if (($thbWidth != -1) && ($thbHeight != -1) && (!scaleImages)) {
               if ($thbWidth != -1) {
                  $wk_width_inline = "width: " . $thbWidth . "px;";
               } // if ($thbWidth != -1) {
               if ($thbHeight != -1) {
                  $wk_height_inline = "height: " . $thbHeight . "px;";
               } // if ($thbHeight != -1) {
            } // if (($thbWidth != -1) && ($thbHeight != -1) && (!scaleImages)) {
//          this is checking if we only want pic sizes reduced but not increased
//          if so then space out the styling if it would yield a larger img
            if (sizeDownOnly) {
               if ($wk_width_inline != "") {
                  if ($thbWidth > $img_width) {
                     $wk_width_inline = "";
                  }
               }
               if ($wk_height_inline != "") {
                  if ($thbHeight > $img_height) {
                     $wk_height_inline = "";
                  }
               }
            }
            if (($wk_width_inline != "") || ($wk_height_inline != "")) {
               $wk_size_str_inline = "style=" . '"';
               $wk_size_str_inline .= $wk_width_inline;
               if (($wk_width_inline != "") && ($wk_height_inline != "")) {
                  $wk_size_str_inline .= " ";
               }
               $wk_size_str_inline .= $wk_height_inline;
               $wk_size_str_inline .= '"';
            } // if (($wk_width_inline != "") || ($wk_height_inline != "")) {
             
//          fwrite($logfile, 'FFFF' . '\n');
            $buildImgTag = $li_thumb_prefix01 . $wk_CPG_URL . $li_thumb_prefix04 . $li_thumb_prefix05 . $wk_thumb_prefix02 . $pfilepath . $li_thumb_prefix03 . $pfilename . '"' . ' alt="' . 'image ' . $pfilename . '"' . ' title="' . $useTitle . " " . $wk_size_str_inline . '/></a>';
//          reference format for within CPG
//          http://www.anysite.com/cpggallery/displayimage.php?pid=764
            
//          fwrite($logfile, 'imgtag->' . $buildImgTag . "\n");
             
// to tableize the entire thing I need to know how many per row
// on each one I would add tds and rows would add tr with table at beginning and end.
// easiest way to do this is to shove everything in an array here and then return it
// and add tags in the other widget.
            $html_imgpart[$img_reccnt] = $buildImgTag;
            $html_cappart[$img_reccnt] = $usePicCap;
            ++$img_reccnt;
            if (isset($saveALB[$paid])) {
               ++$saveALB[$paid];
            } else {
               $saveALB[$paid] = 1;
            } // if (isset($saveALB[$paid])) {
            if (isset($saveOWN[$powner])) {
               ++$saveOWN[$powner];
            } else {
               $saveOWN[$powner] = 1;
            } // if (isset($saveOWN[$powner])) {
//          echo $buildImgTag;
         } // if (fileexists($li_thumb_fn) {
      } // if ($process_rec) {
//    $html_output[$img_reccnt] = $buildImgTag;
   } // while ($row = mysql_fetch_array($sql_result)) {
   $returnvar['ind'] = TRUE;
   $returnvar['msg'] = "pictures successfully assembled for tableing";

   // if var $nbrInCol = 2
// fwrite($logfile, "nbrTC->" . $nbrTC . "\n");
   if ($nbrTC > 0) {
//    fwrite($logfile, "T1133>" . "\n");
      if ($img_reccnt > 0) {
//       fwrite($logfile, "T1433a" . "\n");
         echo $ht_cr;
         echo $ht_tab, 
              $ht_tab;
         echo "<table class=\"CPGImage\">";
         $sw = 0;
         foreach ($html_imgpart as $key19=>$value19) {
//          fwrite($logfile, "T1433b" . "\n");
            ++$sw;
            if ($sw == 1) {
               echo $ht_cr,
                    $ht_tab,
                    $ht_tab,
                    $ht_tab;
               echo "<tr>";
            } //if ($sw == 1) {
            echo $ht_cr,
                 $ht_tab,
                 $ht_tab,
                 $ht_tab,
                 $ht_tab;
            echo "<td>";
//          echo "<div class=\"picandcaption\">";
            echo $ht_cr,
                 $ht_tab,
                 $ht_tab,
                 $ht_tab,
                 $ht_tab,
                 $ht_tab;
            echo $value19;
            
            if(!trim($html_cappart[$key19]=="")) {
               echo "<p>$html_cappart[$key19]</p>";
            }
//          echo "</div>";
            echo $ht_cr,
                 $ht_tab,
                 $ht_tab,
                 $ht_tab,
                 $ht_tab;
            echo "</td>";
            if ($sw == $nbrTC) {
            echo $ht_cr,
                 $ht_tab,
                 $ht_tab,
                 $ht_tab;
               echo "</tr>";
            } //if ($sw == 1) {
            if ($sw > ($nbrTC - 1)) {$sw = 0;}
//          fwrite($logfile, $key19 . "=>" . $value19 . "\n");
         } //foreach ($html_imgpart as $key19=>$value19) {
//       fwrite($logfile, "T1433d" . "\n");
            echo $ht_cr,
                 $ht_tab,
                 $ht_tab;
         echo "</table>";
      } // if ($img_reccnt > 0) {
   } else { // if ($nbrTC > 0) {
//    fwrite($logfile, "T3433a" . "\n");
      foreach ($html_imgpart as $key23=>$value23) {
//       fwrite($logfile, "T3433b" . "\n");
         echo $value23;
//       fwrite($logfile, $key23 . "=>" . $value23 . "\n");
      } //foreach ($html_imgpart as $key23=>$value23) {
   } // else { // if ($nbrTC > 0) {
   return $returnvar;
}
?>