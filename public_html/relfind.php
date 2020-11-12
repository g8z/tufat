<?php
/**
 * TUFaT, The Ultimate Family Tree Creator
 * Copyright 1999 - 2007, Darren G. Gates, All Rights Reserved
 * http://www.tufat.com
 * info@tufat.com
 * Selling the code for this script without prior written consent of
 * Darren G. Gates is expressly forbidden. The license of TUFaT
 * permits you to use install this script on one domain or one
 * physical server. Taking credit for any part of this software is a
 * violation of the copyright. TUFaT comes with no guarantees
 * for reliability or accuracy – in other words, you use this script
 * at your own risk! By using this software, you accept these risks,
 * and agree to indemnify Darren G. Gates for any liability that
 * might arise from its use. You must obtain permission from Darren
 * G. Gates before redistributing TUFaT in any form, over the
 * Internet or any other medium. In ALL cases this copyright notice,
 * as well as the (c) tufat.com notice on the actual TUFaT pages,
 * must remain intact. Removing or modifying this copyright notice
 * is a violation of the license agreement and may subject you to
 * legal proceedings.
 *
 */

require_once('config.php');
require_once('relation_find.inc.php');

@ini_set('max_execution_time', "999");
$db = new FamilyDatabase();

if ($si1 != '' && $si2 != '') {
  $person1 = $db->sG($si1);
  $person2 = $db->sG($si2);
  // // Same person
  if ($si1 == $si2) {
    if (!ANIMALPEDIGREE) $mydata['showmenumsg'] = $db->mytrans("##The person is the same##");
    else $mydata['showmenumsg'] = $db->mytrans("##The animal is the same##");
    show_menu();
    $mydata['skip'] = true;
    $smarty->assign("mydata", $mydata);
    $atree[0][0] = celltext($si1);
    $smarty->assign("atree", $atree);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }
  // // Spouse
  $l = $db->getSpouses($si1);
  $cm = $db->getCommon(array($si2), $l);
  if (count($cm) > 0) {
    $mydata['showmenumsg'] = $db->mytrans("##$person1 is the spouse of $person2##");
    show_menu();
    $mydata['skip'] = true;
    $atree[0][0] = celltext($si1);
    $atree[0][1] = celltext('-');
    $atree[0][2] = celltext($si2);
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }
  // Father or Mother
  /* Major checking which was missed from starting itself.
    Parents and children      Added on 7/JUl/2004 Vijay Nair*/
  /* First father or mother checking  */
  if ($si2 == $db->getFather($si1)) {
    $t = $db->mytrans("##father##");
    $mydata['showmenumsg'] = $db->sG($si2) . " " . $db->mytrans("##is the##") . " " . $t . " " . $db->mytrans("##of##") . " " . $db->sG($si1);
    show_menu();
    $mydata['skip'] = true;
    $atree[0][0] = celltext($si2);
    $atree[1][0] = celltext('|');
    $atree[2][0] = celltext($si1);
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }
  if ($si1 == $db->getFather($si2)) {
    $t = $db->mytrans("##father##");
    $mydata['showmenumsg'] = $db->sG($si1) . " " . $db->mytrans("##is the##") . " " . $t . " " . $db->mytrans("##of##") . " " . $db->sG($si2);
    show_menu();
    $mydata['skip'] = true;
    $atree[0][0] = celltext($si1);
    $atree[1][0] = celltext('|');
    $atree[2][0] = celltext($si2);
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }
  if ($si2 == $db->getMother($si1)) {
    $t = $db->mytrans("##mother##");
    $mydata['showmenumsg'] = $db->sG($si2) . " " . $db->mytrans("##is the##") . " " . $t . " " . $db->mytrans("##of##") . " " . $db->sG($si1);
    show_menu();
    $mydata['skip'] = true;
    $atree[0][0] = celltext($si2);
    $atree[1][0] = celltext('|');
    $atree[2][0] = celltext($si1);
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }
  if ($si1 == $db->getMother($si2)) {
    $t = $db->mytrans("##mother##");
    $mydata['showmenumsg'] = $db->sG($si1) . " " . $db->mytrans("##is the##") . " " . $t . " " . $db->mytrans("##of##") . " " . $db->sG($si2);
    show_menu();
    $mydata['skip'] = true;
    $atree[0][0] = celltext($si1);
    $atree[1][0] = celltext('|');
    $atree[2][0] = celltext($si2);
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }
  /* End of added checking routine  */

  // // Brother or Sister IN LAW
  $a = $si1;
  $b = $si2;
  $sp1 = $db->getSpouses($a);

  $in1 = array();
  $in2 = array();
  foreach($sp1 as $k => $v) {
    $t = $db->getSiblings($v, "M");
    foreach($t as $k2 => $v2) {
      $in1[] = $v2;
      $in2[] = $v;
    }
    $t = $db->getSiblings($v, "F");
    foreach($t as $k2 => $v2) {
      $in1[] = $v2;
      $in2[] = $v;
    }
  }
  
  if (in_array($b, $in1)) {
    $bkey = array_search($b, $in1);
    $s = $db->getItem("SEX", $a);

    if ($s == "M") {
      $t = $db->mytrans("##brother##");
    } else {
      $t = $db->mytrans("##sister##");
    }

    $mydata['showmenumsg'] = $db->sG($a) . " " . $db->mytrans("##is the##") . " " . $t . " " . $db->mytrans("##in law##") . " " . $db->mytrans("##of##") . " " . $db->sG($b);
    show_menu();
    $mydata['skip'] = true;
    $atree[0][0] = celltext($a);
    $atree[0][1] = celltext('-');
    $atree[0][2] = celltext($in2[$bkey]);
    $atree[0][3] = celltext('-');
    $atree[0][4] = celltext($b);
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }
  
  
  $a = $si2;
  $b = $si1;
  $sp1 = $db->getSpouses($a);
  $in1 = array();
  // aTufa fix
  $in2 = array();
  foreach($sp1 as $k => $v) {
    $t = $db->getSiblings($v, "M");
    foreach($t as $k2 => $v2) {
      $in1[] = $v2;
      $in2[] = $v;
    }
    $t = $db->getSiblings($v, "F");
    foreach($t as $k2 => $v2) {
      $in1[] = $v2;
      $in2[] = $v;
    }
  }

  if (in_array($b, $in1)) {
    $bkey = array_search($b, $in1);
    $s = $db->getItem("SEX", $a);
    if ($s == "M") $t = $db->mytrans("##brother##");
    else $t = $db->mytrans("##sister##");
    $mydata['showmenumsg'] = $db->sG($a) . " " . $db->mytrans("##is the##") . " " . $t . " " . $db->mytrans("##in law##") . " " . $db->mytrans("##of##") . " " . $db->sG($b);
    show_menu();
    $mydata['skip'] = true;
    $atree[0][0] = celltext($a);
    $atree[0][1] = celltext('-');
    $atree[0][2] = celltext($in2[$bkey]);
    $atree[0][3] = celltext('-');
    $atree[0][4] = celltext($b);
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }

  // // Father or Mother IN LAW
  $a = $si1;
  $b = $si2;
  $sp1 = $db->getSpouses($a);
  $fl1 = array();
  // aTufa fix
  $fl2 = array();
  foreach($sp1 as $k => $v) {
    $t = $db->getFather($v);
    if ($t >= 0) $fl1[] = $t;
    $fl2[] = $v;
    $t = $db->getMother($v);
    if ($t >= 0) $fl1[] = $t;
    $fl2[] = $v;
  }
  foreach($fl1 as $k => $v) {
    if ($v == $b) {
      $s = $db->getItem("SEX", $b);
      if ($s == "M") $t = $db->mytrans("##father##");
      else $t = $db->mytrans("##mother##");
      $mydata['showmenumsg'] = $db->sG($b) . " " . $db->mytrans("##is the##") . " " . $t . " " . $db->mytrans("##in law##") . " " . $db->mytrans("##of##") . " " . $db->sG($a);
      show_menu();
      $mydata['skip'] = true;
      $atree[0][0] = celltext($b);
      $atree[0][1] = celltext(' ');
      $atree[0][2] = celltext(' ');
      $atree[1][0] = celltext('|');
      $atree[1][1] = celltext(' ');
      $atree[1][2] = celltext(' ');
      $atree[2][0] = celltext($fl2[$k]);
      $atree[2][1] = celltext('-');
      $atree[2][2] = celltext($a);
      $smarty->assign("atree", $atree);
      $smarty->assign("mydata", $mydata);
      $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
      $smarty->display('index.tpl');
      exit;
    }
  }
  $a = $si2;
  $b = $si1;
  $sp1 = $db->getSpouses($a);
  $fl1 = array();
  // aTufa fix
  $fl2 = array();
  foreach($sp1 as $k => $v) {
    $t = $db->getFather($v);
    if ($t >= 0) $fl1[] = $t;
    $fl2[] = $v;
    $t = $db->getMother($v);
    if ($t >= 0) $fl1[] = $t;
    $fl2[] = $v;
  }
  foreach($fl1 as $k => $v) {
    if ($v == $b) {
      $s = $db->getItem("SEX", $b);
      if ($s == "M") $t = $db->mytrans("##father##");
      else $t = $db->mytrans("##mother##");
      $mydata['showmenumsg'] = $db->sG($b) . " " . $db->mytrans("##is the##") . " " . $t . " " . $db->mytrans("##in law##") . " " . $db->mytrans("##of##") . " " . $db->sG($a);
      show_menu();
      $mydata['skip'] = true;
      $atree[0][0] = celltext($b);
      $atree[0][1] = celltext(' ');
      $atree[0][2] = celltext(' ');
      $atree[1][0] = celltext('|');
      $atree[1][1] = celltext(' ');
      $atree[1][2] = celltext(' ');
      $atree[2][0] = celltext($fl2[$k]);
      $atree[2][1] = celltext('-');
      $atree[2][2] = celltext($a);
      $smarty->assign("atree", $atree);
      $smarty->assign("mydata", $mydata);
      $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
      $smarty->display('index.tpl');
      exit;
    }
  }

  // // MATERNAL RELATIONSHIPS //////////////////////////////////
  // // Step/_ Great,Grand Mother !
  $m2 = $db->getMothers($si2);
  $cm = $db->getCommon(array($si1), $m2);
  if (count($cm) > 0) {
    $lm2 = $db->getLev($si2, $cm[0]);
    $mydata['showmenumsg'] = $db->sG($cm[0]) . " " . $db->mytrans("##is the##") . " ";
    if ($lm2 > 2) {
      while ($lm2 > 2) {
        $mydata['showmenumsg'].= $db->mytrans("##great##") . "-";
        $lm2--;
      }
    }

    if ($lm2 == 2) {
      $mydata['showmenumsg'].= $db->mytrans("##grand##");
      $lm2--;
    }

    if ($db->testStepMother($si2, $si1)) {
      $mydata['showmenumsg'].= " " . $db->mytrans("##step##") . " ";
    }

    if ($lm2 == 1) $mydata['showmenumsg'].= $db->mytrans("##mother##");
    $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " " . $db->sG($si2);
    show_menu();
    $mydata['skip'] = true;
    // Graphic Tree part (aTufa)
    $m2m = $db->getMothersRel($si2);
    // Add himself to tree
    $m2m['id'][] = $si2;
    $m2m['par'][] = 0;
    // Get subtree and send to Smarty
    $stre = getRelTree($m2m, $si1);
    $i = 0;
    for ($k = 0;$k < count($stre);$k++) {
      $atree[$i][0] = celltext($stre[$k]);
      if ($k < count($stre) - 1) $atree[$i + 1][0] = celltext('|');
      $i+= 2;
    }
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }

  // viceversa
  $m1 = $db->getMothers($si1);
  $cm = $db->getCommon(array($si2), $m1);
  if (count($cm) > 0) {
    $lm1 = $db->getLev($si1, $cm[0]);
    $mydata['showmenumsg'] = $db->sG($cm[0]) . " " . $db->mytrans("##is the##") . " ";
    if ($lm1 > 2) {
      while ($lm1 > 2) {
        $mydata['showmenumsg'].= $db->mytrans("##great##") . "-";
        $lm1--;
      }
    }
    if ($lm1 == 2) {
      $mydata['showmenumsg'].= $db->mytrans("##grand##");
      $lm1--;
    }
    if ($db->testStepMother($si1, $si2)) {
      $mydata['showmenumsg'].= " " . $db->mytrans("##step##") . " ";
    }
    if ($lm1 == 1) $mydata['showmenumsg'].= $db->mytrans("##mother##");
    $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " " . $db->sG($si1);
    show_menu();
    $mydata['skip'] = true;
    // Graphic Tree part (aTufa)
    $m1m = $db->getMothersRel($si1);
    // Add himself to tree
    $m1m['id'][] = $si1;
    $m1m['par'][] = 0;
    // Get subtree and send to Smarty
    $stre = getRelTree($m1m, $si2);
    $i = 0;
    for ($k = 0;$k < count($stre);$k++) {
      $atree[$i][0] = celltext($stre[$k]);
      if ($k < count($stre) - 1) $atree[$i + 1][0] = celltext('|');
      $i+= 2;
    }
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }

  // // PATERNAL RELATIONSHIPS  /////////////////////////////////
  // // Step/_ Great,Grand Father !
  $f2 = $db->getFathers($si2);
  $cm = $db->getCommon(array($si1), $f2);
  if (count($cm) > 0) {
    $mydata['showmenumsg'] = "";
    $lf2 = $db->getLev($si2, $cm[0]);
    $mydata['showmenumsg'].= $db->sG($cm[0]) . " " . $db->mytrans("##is the##") . " ";
    if ($lf2 > 2) {
      while ($lf2 > 2) {
        $mydata['showmenumsg'].= $db->mytrans("##great##") . "-";
        $lf2--;
      }
    }
    if ($lf2 == 2) {
      $mydata['showmenumsg'].= $db->mytrans("##grand##");
      $lf2--;
    }
    if ($db->testStepFather($si2, $si1)) $mydata['showmenumsg'].= " " . $db->mytrans("##step##") . " ";
    if ($lf2 == 1) $mydata['showmenumsg'].= $db->mytrans("##father##");
    $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " " . $db->sG($si2);
    show_menu();
    $mydata['skip'] = true;
    // Graphic Tree part (aTufa)
    $m2m = $db->getFathersRel($si2);
    // Add himself to tree
    array_pop($m2m['id']);
    $m2m['id'][] = $si2;
    $m2m['par'][] = 0;
    // Get subtree and send to Smarty
    // xdebug_var_dump($m2m);
    $stre = getRelTree($m2m, $si1);
    $i = 0;
    for ($k = 0;$k < count($stre);$k++) {
      $atree[$i][0] = celltext($stre[$k]);
      if ($k < count($stre) - 1) $atree[$i + 1][0] = celltext('|');
      $i+= 2;
    }
    $treeee = array(0 => array(0 => array('ID' => '1', 'model' => 'O', 'name' => 'Chandrashekhar Dixith', 'spic' => false,),), 1 => array(0 => array('ID' => '|', 'model' => '|',),), 2 => array(0 => array('ID' => '8', 'model' => 'O', 'name' => 'Gurumurthy Rao Dixith', 'spic' => false,),),);
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }

  // viceversa
  $f1 = $db->getFathers($si1);
  $cm = $db->getCommon(array($si2), $f1);
  $mydata['showmenumsg'] = "";
  if (count($cm) > 0) {
    $lf1 = $db->getLev($si1, $cm[0]);
    $mydata['showmenumsg'].= $db->sG($cm[0]) . " " . $db->mytrans("##is the##") . " ";
    if ($lf1 > 2) {
      while ($lf1 > 2) {
        $mydata['showmenumsg'].= $db->mytrans("##great##") . "-";
        $lf1--;
      }
    }
    if ($lf1 == 2) {
      $mydata['showmenumsg'].= $db->mytrans("##grand##");
      $lf1--;
    }
    if ($db->testStepFather($si1, $si2)) $mydata['showmenumsg'].= " " . $db->mytrans("##step##") . " ";
    if ($lf1 == 1) $mydata['showmenumsg'].= $db->mytrans("##father##");
    $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " " . $db->sG($si1);
    show_menu();
    $mydata['skip'] = true;
    // Graphic Tree part (aTufa)
    $m1m = $db->getFathersRel($si1);
    // Add himself to tree
    $m1m['id'][] = $si1;
    $m1m['par'][] = 0;
    // Get subtree and send to Smarty
    $stre = getRelTree($m1m, $si2);
    $i = 0;
    for ($k = 0;$k < count($stre);$k++) {
      $atree[$i][0] = celltext($stre[$k]);
      if ($k < count($stre) - 1) $atree[$i + 1][0] = celltext('|');
      $i+= 2;
    }
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }

  // // Step/_ Brother or Sister Paternal!
  $mydata['showmenumsg'] = "";
  $cm = $db->getCommon($f2, $f1);

  if (count($cm) > 0) {
    $lf1 = $db->getLev($si1, $cm[0]);
    $lf2 = $db->getLev($si2, $cm[0]);

    $s1 = $db->getItem("sex", $si1);
    $s2 = $db->getItem("sex", $si2);
    $person1 = $db->sG($si1);
    $person2 = $db->sG($si2);
    if ($lf1 == $lf2) {
      if ($lf1 == 1) {
        if ($db->testStepFather($si1, $cm[0]) == false && $db->testStepFather($si2, $cm[0]) == false) $step = " ";
        else $step = " " . $db->mytrans("##step##") . " ";
        if ($s1 == "M") {
          $mydata['showmenumsg'] = $db->mytrans('##$person1 is the $step brother of $person2##');
        } else {
          $mydata['showmenumsg'] = $db->mytrans('##$person1 is the $step sister of $person2##');
        }
      } else {
        // // Paternal Cousins n
        $lf1--;
        $mydata['showmenumsg'] = $db->sG($si1) . " " . $db->mytrans("##is the##") . " ";
        if ($lf1 >= 1) {
          $mydata['showmenumsg'].= " ";
          $mydata['showmenumsg'].= show_word($lf1);
          $mydata['showmenumsg'].= " ";
        }
        $mydata['showmenumsg'].= $db->mytrans("##cousin##");
        $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " " . $db->sG($si2);
      }
    } else {
      $mydata['showmenumsg'] = "";
      if ($lf1 > $lf2) $tl = ($lf1 - $lf2);
      else $tl = ($lf2 - $lf1);
      if ($lf1 >= 2 && $lf2 >= 2) {
        if ($lf1 < $lf2) {
          $mydata['showmenumsg'].= $db->sG($si2);
          $a1 = $si2;
          $a2 = $si1;
        } else {
          $a1 = $si1;
          $a2 = $si2;
          $mydata['showmenumsg'].= $db->sG($si1);
        }
        $mydata['showmenumsg'].= " " . $db->mytrans("##is the##") . " ";
        $x = $db->getSameLev($a1, $a2, $cm[0], "F");
        $n = $db->getLev($x, $cm[0]);
        if ($n - 1 == 0) {
          $x = $db->getSameLev($a1, $a2, $cm[0], 'M');
          $n = $db->getLev($x, $cm[0]);
        }
        $mydata['showmenumsg'].= show_word($n - 1);
        $mydata['showmenumsg'].= " " . $db->mytrans("##cousin##") . " ";
        $mydata['showmenumsg'].= show_word2($tl) . " " . $db->mytrans("##removed##") . " ";
        $m = $db->getLev($x, $cm[0]);
        $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " ";
        if ($lf1 < $lf2) $mydata['showmenumsg'].= $db->sG($si1);
        else $mydata['showmenumsg'].= $db->sG($si2);
      }
      // // Parental Uncle, Aunt
      if ($lf1 < 2 || $lf2 < 2) {
        if ($lf1 > $lf2) {
          $mydata['showmenumsg'].= $db->sG($si2);
          $ts = $s2;
        } else {
          $ts = $s1;
          $mydata['showmenumsg'].= $db->sG($si1);
        }
        $mydata['showmenumsg'].= " " . $db->mytrans("##is the##");
        $tl--;
        $mydata['showmenumsg'].= " ";
        while ($tl > 1) {
          $mydata['showmenumsg'].= $db->mytrans("##great##") . "-";
          $tl--;
        }
        if ($tl == 1) $mydata['showmenumsg'].= $db->mytrans("##grand##") . " ";
        if ($ts == "M") $mydata['showmenumsg'].= $db->mytrans("##uncle##");
        else $mydata['showmenumsg'].= $db->mytrans("##aunt##");
        $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " ";
        if ($lf1 > $lf2) $mydata['showmenumsg'].= $db->sG($si1);
        else $mydata['showmenumsg'].= $db->sG($si2);
      }
    }

    show_menu();
    $mydata['skip'] = true;
    // Add common father to tree
    $atree[0][0] = celltext(' ');
    $atree[0][1] = celltext($cm[0]);
    // Graphic Tree part for si1 (aTufa)
    $atree[0][2] = celltext(' ');
    $m1m = $db->getFathersRel($si1);
    // Add himself to tree
    $m1m['id'][] = $si1;
    $m1m['par'][] = 0;
    // Get subtree and send to Smarty
    $stre = getRelTree($m1m, $cm[0]);
    $i = 1;

    for ($k = 1;$k < count($stre);$k++) {
      $atree[$i][0] = celltext($stre[$k]);
      if ($k < count($stre) - 1) $atree[$i + 1][0] = celltext('|');
      $atree[$i][1] = celltext(' ');
      $atree[$i + 1][1] = celltext(' ');
      $i+= 2;
    }

    // Graphic Tree part for si2 (aTufa)
    $m2m = $db->getFathersRel($si2);
    // Add himself to tree
    $m2m['id'][] = $si2;
    $m2m['par'][] = 0;

    // Get subtree and send to Smarty
    $stre = getRelTree($m2m, $cm[0]);
    $i = 1;
    for ($k = 1;$k < count($stre);$k++) {
      $atree[$i][2] = celltext($stre[$k]);
      if ($k < count($stre) - 1) $atree[$i + 1][2] = celltext('|');
      $atree[$i][1] = celltext(' ');
      $atree[$i + 1][1] = celltext(' ');
      $i+= 2;
    }

    // Fill emptry cells (otherwise layout bug)
    for ($i = 0;$i < count($atree);$i++) {
      for ($j = 0;$j <= 2;$j++) {
        if (!isset($atree[$i][$j]['model'])) $atree[$i][$j]['model'] = ' ';
      }

      ksort($atree[$i]);
    }

    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }

  // // Step/_ Brother or Sister Maternal
  $cm = $db->getCommon($m2, $m1);
  if (count($cm) > 0) {
    $mydata['showmenumsg'] = "";
    $lf1 = $db->getLev($si1, $cm[0]);
    $lf2 = $db->getLev($si2, $cm[0]);
    $s1 = $db->getItem("sex", $si1);
    $s2 = $db->getItem("sex", $si2);
    $person1 = $db->sG($si1);
    $person2 = $db->sG($si2);
    if ($lf1 == $lf2) {
      if ($lf1 == 1) {
        if ($db->testStepMother($si1, $cm[0]) == false && $db->testStepMother($si2, $cm[0]) == false) $step = " ";
        else $step = " " . $db->mytrans("##step##") . " ";
        if ($s1 == "M") {
          $mydata['showmenumsg'] = $db->mytrans("##$person1 is the $step brother of $person2##");
        } else {
          $mydata['showmenumsg'] = $db->mytrans("##$person1 is the $step sister of $person2##");
        }
        $atree[1][0] = celltext($si1);
        $atree[1][1] = celltext(' ');
        $atree[1][2] = celltext($si2);
      } else {
        // // Maternal Cousins n
        $lf1--;
        $mydata['showmenumsg'] = $db->sG($si1) . " " . $db->mytrans("##is the##") . " ";
        if ($lf1 >= 1) {
          $mydata['showmenumsg'].= " ";
          $mydata['showmenumsg'].= show_word($lf1);
          $mydata['showmenumsg'].= " ";
        }
        $mydata['showmenumsg'].= $db->mytrans("##cousin##");
        $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " " . $db->sG($si2);
      }
    } else {
      if ($lf1 > $lf2) $tl = ($lf1 - $lf2);
      else $tl = ($lf2 - $lf1);
      if ($lf1 >= 2 && $lf2 >= 2) {
        if ($lf1 < $lf2) {
          $mydata['showmenumsg'].= $db->sG($si2);
          $a1 = $si2;
          $a2 = $si1;
        } else {
          $a1 = $si1;
          $a2 = $si2;
          $mydata['showmenumsg'].= $db->sG($si1);
        }
        $mydata['showmenumsg'].= " " . $db->mytrans("##is the##");
        $x = $db->getSameLev($a1, $a2, $cm[0], "F");
        $n = $db->getLev($x, $cm[0]);
        if ($n - 1 == 0) {
          $x = $db->getSameLev($a1, $a2, $cm[0], 'M');
          $n = $db->getLev($x, $cm[0]);
        }
        $mydata['showmenumsg'].= show_word($n - 1);
        $mydata['showmenumsg'].= " " . $db->mytrans("##cousin##") . " ";
        $mydata['showmenumsg'].= show_word2($tl) . " " . $db->mytrans("##removed##") . " ";
        $m = $db->getLev($x, $cm[0]);
        $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " ";
        if ($lf1 < $lf2) $mydata['showmenumsg'].= $db->sG($si1);
        else $mydata['showmenumsg'].= $db->sG($si2);
      }
      // // Maternal Uncle, Aunt !!  Fixed by aTufa /May-2005
      if ($lf1 < 2 || $lf2 < 2) {
        if ($lf1 > $lf2) {
          $mydata['showmenumsg'].= $db->sG($si2);
          $ts = $s2;
        } else {
          $ts = $s1;
          $mydata['showmenumsg'].= $db->sG($si1);
        }
        $mydata['showmenumsg'].= " " . $db->mytrans("##is the##");
        $tl--;
        $mydata['showmenumsg'].= " ";
        while ($tl > 1) {
          $mydata['showmenumsg'].= $db->mytrans("##great##") . "-";
          $tl--;
        }
        if ($tl == 1) $mydata['showmenumsg'].= $db->mytrans("##grand##") . " ";
        if ($ts == "M") $mydata['showmenumsg'].= $db->mytrans("##uncle##");
        else $mydata['showmenumsg'].= $db->mytrans("##aunt##");
        $mydata['showmenumsg'].= " " . $db->mytrans("##of##") . " ";
        if ($lf1 > $lf2) $mydata['showmenumsg'].= $db->sG($si1);
        else $mydata['showmenumsg'].= $db->sG($si2);
      }
    }
    show_menu();
    $mydata['skip'] = true;
    // Add common mother to tree
    $atree[0][0] = celltext(' ');
    $atree[0][1] = celltext($cm[0]);
    $atree[0][2] = celltext(' ');
    ksort($atree);
    // Graphic Tree part for si1 (aTufa)
    $m1m = $db->getMothersRel($si1);
    // Add himself to tree
    $m1m['id'][] = $si1;
    $m1m['par'][] = 0;
    // Get subtree and send to Smarty
    $stre = getRelTree($m1m, $cm[0]);
    $i = 1;
    for ($k = 1;$k < count($stre);$k++) {
      $atree[$i][0] = celltext($stre[$k]);
      if ($k < count($stre) - 1) $atree[$i + 1][0] = celltext('|');
      $atree[$i][1] = celltext(' ');
      $atree[$i + 1][1] = celltext(' ');
      $i+= 2;
    }
    // Graphic Tree part for si2 (aTufa)
    $m2m = $db->getMothersRel($si2);
    // Add himself to tree
    $m2m['id'][] = $si2;
    $m2m['par'][] = 0;
    // Get subtree and send to Smarty
    $stre = getRelTree($m2m, $cm[0]);
    $i = 1;
    $atree_tmp = $atree;
    for ($k = 1;$k < count($stre);$k++) {
      $atree[$i][2] = celltext($stre[$k]);
      if ($k < count($stre) - 1) $atree[$i + 1][2] = celltext('|');
      $atree[$i][1] = celltext(' ');
      $atree[$i + 1][1] = celltext(' ');
      $i+= 2;
    }
    // Fill emptry cells (otherwise layout bug)
    for ($i = 0;$i < count($atree);$i++) {
      for ($j = 0;$j <= 2;$j++) {
        if (!isset($atree[$i][$j]['model'])) $atree[$i][$j]['model'] = ' ';
      }
      ksort($atree[$i]);
    }
    $smarty->assign("atree", $atree);
    $smarty->assign("mydata", $mydata);
    $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
    $smarty->display('index.tpl');
    exit;
  }
  $mydata['showmenumsg'] = $db->mytrans('##A relationship between $person1 and $person2 could not be found##');
  show_menu();
  $mydata['skip'] = true;
  $smarty->assign("mydata", $mydata);
  $atree[0][0] = celltext($si1);
  $atree[0][1] = celltext(' ');
  $atree[0][2] = celltext($si2);
  $smarty->assign("atree", $atree);
  $smarty->assign('rendered_page', $smarty->fetch("relfind.tpl"));
  $smarty->display('index.tpl');
} else {
  $all = array();
  $names = array();
  $hides = array();
  $ids = array();
  $mydata['ID'] = $ID;
  if ($ssort == '') $ssort = 'surn';
  $sql = "select distinct id from {$db->gedcomTable} where tree = " . $db->dbh->quote($user) . " and type ='I'";
  $allids = $db->getCol($sql);
  foreach($allids as $aid) {
    list($name, $surn, $hide) = $db->getItems(array('name', 'surn', 'hide'), $aid);
    if ($ssort == 'surn') {
      $k = $surn . "|" . $name . "|" . $aid;
    } elseif ($ssort == 'name') {
      $k = $name . "|" . $aid;
    } else {
      $k = $aid;
    }
    $all[] = $k;
    $names[$aid] = $name;
    $hides[$aid] = $hide;
  }
  asort($all);
  $mydata['nameselected'] = ($ssort == "name") ? ' selected="selected" ' : "";
  $mydata['surnselected'] = ($ssort == "surn") ? ' selected="selected" ' : "";
  $mydata['idselected'] = ($ssort == "id") ? ' selected="selected" ' : "";
  $i = 0;
  foreach($all as $idx) {
    $ids = explode("|", $idx);
    if ($ssort == 'surn') {
      $id = $ids[2];
    } elseif ($ssort == 'name') {
      $id = $ids[1];
    } else $id = $ids[0];
    if ($id != - 1) {
      $optList[$i]->val = $id;
      $optList[$i]->desc = $db->sG($id, $names[$id], $hides[$id]);
      $i++;
    }
  }
  show_menu(1);
  $smarty->assign("optList", $optList);
  $smarty->assign("mydata", $mydata);
  $smarty->assign('rendered_page', $smarty->fetch('relfind.tpl'));
  $smarty->display("index.tpl");
}
