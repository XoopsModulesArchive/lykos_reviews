<?php

// Lykos Reviews Module
// Adapted from Xoopsfaq by Samuel Wright (http://www.lykoszine.co.uk)
// See readme for more info

// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //

require_once 'admin_header.php';

global $xoopsUser, $xoopsConfig;

$op = 'listcat';

if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        $$k = $v;
    }
}

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        $$k = $v;
    }
}

if (!empty($contents_preview)) {
    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    OpenTable();

    echo "<a href='index.php'>" . _XD_MAIN . "</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;Preview<br>";

    $html = empty($review_nohtml) ? 1 : 0;

    $smiley = empty($review_nosmiley) ? 1 : 0;

    $xcode = empty($review_noxcode) ? 1 : 0;

    $p_title = htmlspecialchars($review_title, ENT_QUOTES | ENT_HTML5);

    $p_item = htmlspecialchars($review_item, ENT_QUOTES | ENT_HTML5);

    $p_label = htmlspecialchars($review_label, ENT_QUOTES | ENT_HTML5);

    $p_release = htmlspecialchars($release_date, ENT_QUOTES | ENT_HTML5);

    $p_format = htmlspecialchars($release_format, ENT_QUOTES | ENT_HTML5);

    $p_image = htmlspecialchars($release_image, ENT_QUOTES | ENT_HTML5);

    $p_amazon = htmlspecialchars($release_amazon, ENT_QUOTES | ENT_HTML5);

    $p_contents = $myts->previewTarea($review_contents, $html, $smiley, $xcode);

    echo "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'><tr><td class='bg2'>
   <table width='100%' border='0' cellpadding='4' cellspacing='1'>
   <tr class='bg3' align='center'><td align='left'>" . $p_title . "</td></tr>
   <tr class='bg3' align='center'><td align='left'>" . $p_item . "</td></tr>
   <tr class='bg3' align='center'><td align='left'>" . $p_label . "</td></tr>
   <tr class='bg3' align='center'><td align='left'>" . $p_release . "</td></tr>
   <tr class='bg3' align='center'><td align='left'>" . $p_format . "</td></tr>
   <tr class='bg3' align='center'><td align='left'>" . $p_image . "</td></tr>
   <tr class='bg3' align='center'><td align='left'>" . $p_amazon . "</td></tr>
   <tr class='bg1'><td>" . $p_contents . '</td></tr></table></td></tr></table><br>';

    $review_title = htmlspecialchars($review_title, ENT_QUOTES | ENT_HTML5);

    $review_item = htmlspecialchars($review_item, ENT_QUOTES | ENT_HTML5);

    $review_label = htmlspecialchars($review_label, ENT_QUOTES | ENT_HTML5);

    $release_date = htmlspecialchars($release_date, ENT_QUOTES | ENT_HTML5);

    $release_format = htmlspecialchars($release_format, ENT_QUOTES | ENT_HTML5);

    $review_contents = htmlspecialchars($review_contents, ENT_QUOTES | ENT_HTML5);

    $release_image = htmlspecialchars($release_image, ENT_QUOTES | ENT_HTML5);

    $release_amazon = htmlspecialchars($release_amazon, ENT_QUOTES | ENT_HTML5);

    include 'contentsform.php';

    CloseTable();

    xoops_cp_footer();

    exit();
}

if ('listcat' == $op) {
    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    OpenTable();

    echo "
   <h4 style='text-align:left;'>" . _XD_DOCS . "</h4>
   <form action='index.php' method='post'>
   <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'><tr><td class='bg2'>
   <table width='100%' border='0' cellpadding='4' cellspacing='1'>
   <tr class='bg3' align='center'><td align='left'>" . _XD_CATEGORY . '</td><td>' . _XD_CONTENTS . '</td><td>&nbsp;</td></tr>';

    $result = $xoopsDB->query('SELECT c.category_id, c.category_title, COUNT(f.category_id) FROM ' . $xoopsDB->prefix('lykos_reviews_categories') . ' c LEFT JOIN ' . $xoopsDB->prefix('lykos_reviews_contents') . ' f ON f.category_id=c.category_id GROUP BY c.category_id ORDER BY category_title ASC');

    $count = 0;

    while (list($cat_id, $category, $faq_count) = $xoopsDB->fetchRow($result)) {
        $category = htmlspecialchars($category, ENT_QUOTES | ENT_HTML5);

        echo "
      <tr class='bg1'><td align='left'><input type='hidden' value='$cat_id' name='cat_id[]'><input type='hidden' value='$category' name='oldcategory[]'><input type='text' value='$category' name='newcategory[]' maxlength='255' size='20'></td>
      <td align='center'>$faq_count</td>
      <td align='right'><a href='index.php?op=listcontents&amp;cat_id=" . $cat_id . "'>" . _XD_CONTENTS . "</a> | <a href='index.php?op=delcat&amp;cat_id=" . $cat_id . "&amp;ok=0'>" . _DELETE . '</a></td></tr>';

        $count++;
    }

    if ($count > 0) {
        echo "<tr align='center' class='bg3'><td colspan='4'><input type='submit' value='" . _SUBMIT . "'><input type='hidden' name='op' value='editcatgo'><input type='hidden' name='cat_id' value='" . $cat_id . "'></td></tr>";
    }

    echo "</table></td></tr></table></form>
   <br><br>
   <h4 style='text-align:left;'>" . _XD_ADDCAT . "</h4>
   <form action='index.php' method='post'>
   <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'><tr><td class='bg2'><table width='100%' border='0' cellpadding='4' cellspacing='1'><tr nowrap='nowrap'><td class='bg3'>" . _XD_TITLE . " </td><td class='bg1'><input type='text' name='category' size='30' maxlength='255'></td></tr>
   <tr><td class='bg3'>&nbsp;</td><td class='bg1'><input type='hidden' name='op' value='addcatgo'><input type='submit' value='" . _SUBMIT . "'></td></tr>
   </table></td></tr></table></form><br>";

    echo "<h4 style='text-align:left;'>" . _XD_ADDCONTENTS . '</h4>';

    $review_title = '';

    $review_contents = '';

    $review_item = '';

    $review_label = '';

    $release_date = '';

    $release_format = '';

    $release_image = '';

    $release_amazon = '';

    $review_visible = 1;

    $review_nohtml = 0;

    $review_nosmiley = 0;

    $review_noxcode = 0;

    $review_id = 0;

    $category_id = 0;

    $op = 'addcontentsgo';

    include 'contentsform.php';

    CloseTable();

    CloseTable();

    xoops_cp_footer();

    exit();
}

if ('addcatgo' == $op) {
    $myts = MyTextSanitizer::getInstance();

    $category = $myts->addSlashes($category);

    $newid = $xoopsDB->genId($xoopsDB->prefix('lykos_reviews_categories') . '_category_id_seq');

    $sql = 'INSERT INTO ' . $xoopsDB->prefix('lykos_reviews_categories') . " (category_id, category_title) VALUES ($newid, '" . $category . "')";

    if (!$xoopsDB->query($sql)) {
        xoops_cp_header();

        echo 'Could not add category';

        xoops_cp_footer();
    } else {
        redirect_header('index.php?op=listcat', 1, _XD_DBSUCCESS);
    }

    exit();
}

if ('editcatgo' == $op) {
    $myts = MyTextSanitizer::getInstance();

    $count = count($newcategory);

    for ($i = 0; $i < $count; $i++) {
        if ($newcategory[$i] != $oldcategory[$i] || $neworder[$i] != $oldorder[$i]) {
            $category = $myts->addSlashes($newcategory[$i]);

            $sql = 'UPDATE ' . $xoopsDB->prefix('lykos_reviews_categories') . " SET category_title='" . $category . "' WHERE category_id=" . $cat_id[$i] . '';

            $xoopsDB->query($sql);
        }
    }

    redirect_header('index.php?op=listcat', 1, _XD_DBSUCCESS);

    exit();
}

if ('listcontents' == $op) {
    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    $sql = 'SELECT category_title FROM ' . $xoopsDB->prefix('lykos_reviews_categories') . " WHERE category_id='" . $cat_id . "'";

    $result = $xoopsDB->query($sql);

    [$category] = $xoopsDB->fetchRow($result);

    $category = htmlspecialchars($category, ENT_QUOTES | ENT_HTML5);

    OpenTable();

    echo "<a href='index.php'>" . _XD_MAIN . "</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;" . $category . "<br><br>
   <h4 style='text-align:left;'>" . _XD_CONTENTS . "</h4>
   <form action='index.php' method='post'>
   <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'><tr><td class='bg2'>
   <table width='100%' border='0' cellpadding='4' cellspacing='1'>
   <tr class='bg3'><td>" . _XD_TITLE . "</td><td align='center'>" . _XD_DISPLAY . '</td><td>&nbsp;</td></tr>';

    //REVIEW VISIBLE EDITED

    $result = $xoopsDB->query('SELECT review_id, review_title, review_item, review_label, release_date, release_format, review_time, review_visible FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . " WHERE category_id='" . $cat_id . "' ORDER BY review_title");

    $count = 0;

    while (list($id, $title, $item, $label, $release_date, $format, $time, $visible) = $xoopsDB->fetchRow($result)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        echo "<tr class='bg1'><td><input type='hidden' value='$id' name='id[]'>" . $title;

        $checked = (1 == $visible) ? ' checked' : '';

        echo "<td align='center'><input type='hidden' value='$visible' name='oldvisible[$id]'><input type='checkbox' value='1' name='newvisible[$id]'" . $checked . "></td>
      <td align='right'><a href='index.php?op=editcontents&amp;id=" . $id . '&amp;cat_id=' . $cat_id . "'>" . _EDIT . "</a> | <a href='index.php?op=delcontents&amp;id=" . $id . '&amp;ok=0&amp;cat_id=' . $cat_id . "'>" . _DELETE . '</a></td></tr>';

        $count++;
    }

    if ($count > 0) {
        echo "<tr align='center' class='bg3'><td colspan='4'><input type='submit' value='" . _SUBMIT . "'><input type='hidden' name='op' value='quickeditcontents'><input type='hidden' name='cat_id' value='" . $cat_id . "'></td></tr>";
    }

    echo '</table></td></tr></table></form>
   <br><br>';

    CloseTable();

    xoops_cp_footer();

    exit();
}

if ('quickeditcontents' == $op) {
    $myts = MyTextSanitizer::getInstance();

    $count = count($id);

    for ($i = 0; $i < $count; $i++) {
        $index = $id[$i];

        if ($newvisible[$index] != $oldvisible[$index]) {
            $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('lykos_reviews_contents') . ' SET review_visible=' . (int)$newvisible[$index] . ' WHERE review_id=' . $index . '');
        }
    }

    /*
    lykos_reviews_write_conts(lykos_reviews_get_conts($cat_id, 'review_title'), $cat_id, 'review_title');
    lykos_reviews_write_conts(lykos_reviews_get_conts($cat_id, 'review_item'), $cat_id, 'review_item');
    lykos_reviews_write_conts(lykos_reviews_get_conts($cat_id, 'review_label'), $cat_id, 'review_label');
    */

    redirect_header("index.php?op=listcontents&amp;cat_id=$cat_id", 1, _XD_DBSUCCESS);

    exit();
}

if ('addcontentsgo' == $op) {
    $myts = MyTextSanitizer::getInstance();

    $title = $myts->addSlashes($review_title);

    $item = $myts->addSlashes($review_item);

    $label = $myts->addSlashes($review_label);

    $release = $myts->addSlashes($release_date);

    $format = $myts->addSlashes($release_format);

    $image = $myts->addSlashes($release_image);

    $amazon = $myts->addSlashes($release_amazon);

    $contents = $myts->addSlashes($review_contents);

    if ($xoopsUser->uid()) {
        $submitter = $xoopsUser->uid();
    } else {
        $submitter = 0;
    }

    $newid = $xoopsDB->genId($xoopsDB->prefix('lykos_reviews_contents') . '_review_id_seq');

    $sql = 'INSERT INTO ' . $xoopsDB->prefix('lykos_reviews_contents') . ' (review_id, category_id, review_title, review_item, review_label, release_date, release_format, release_image, release_amazon, review_contents, review_uid, review_time, review_visible, review_nohtml, review_nosmiley, review_noxcode) VALUES (' . $newid . ', ' . $category_id . ", '" . $title . "', '" . $item . "', '" . $label . "', '" . $release . "', '" . $format . "', '" . $image . "', '" . $amazon . "', '" . $contents . "', '" . $submitter . "'," . time() . ',  ' . (int)$review_visible
           . ', ' . (int)$review_nohtml
           . ', ' . (int)$review_nosmiley
           . ', ' . (int)$review_noxcode
           . ')';

    if (!$xoopsDB->query($sql)) {
        xoops_cp_header();

        echo 'Could not add contents';

        xoops_cp_footer();
    } else {
        /*
        lykos_reviews_write_conts(lykos_reviews_get_conts($category_id, 'review_title'), $category_id, 'review_title');
        lykos_reviews_write_conts(lykos_reviews_get_conts($category_id, 'review_item'), $category_id, 'review_item');
        lykos_reviews_write_conts(lykos_reviews_get_conts($category_id, 'review_label'), $category_id, 'review_label');
        */

        redirect_header("index.php?op=listcontents&amp;cat_id=$category_id", 1, _XD_DBSUCCESS);
    }

    exit();
}

if ('editcontents' == $op) {
    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    $sql = 'SELECT category_title FROM ' . $xoopsDB->prefix('lykos_reviews_categories') . " WHERE category_id='" . $cat_id . "'";

    $result = $xoopsDB->query($sql);

    [$category] = $xoopsDB->fetchRow($result);

    $category = htmlspecialchars($category, ENT_QUOTES | ENT_HTML5);

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . " WHERE review_id='$id'");

    $myrow = $xoopsDB->fetchArray($result);

    $review_title = htmlspecialchars($myrow['review_title'], ENT_QUOTES | ENT_HTML5);

    $review_item = htmlspecialchars($myrow['review_item'], ENT_QUOTES | ENT_HTML5);

    $review_label = htmlspecialchars($myrow['review_label'], ENT_QUOTES | ENT_HTML5);

    $release_date = htmlspecialchars($myrow['release_date'], ENT_QUOTES | ENT_HTML5);

    $release_format = htmlspecialchars($myrow['release_format'], ENT_QUOTES | ENT_HTML5);

    $release_image = htmlspecialchars($myrow['release_image'], ENT_QUOTES | ENT_HTML5);

    $release_amazon = htmlspecialchars($myrow['release_amazon'], ENT_QUOTES | ENT_HTML5);

    $review_contents = htmlspecialchars($myrow['review_contents'], ENT_QUOTES | ENT_HTML5);

    $review_visible = $myrow['review_visible'];

    $review_nohtml = $myrow['review_nohtml'];

    $review_uid = $myrow['review_uid'];

    $review_nosmiley = $myrow['review_nosmiley'];

    $review_noxcode = $myrow['review_noxcode'];

    $review_id = $myrow['review_id'];

    $category_id = $myrow['category_id'];

    $op = 'editcontentsgo';

    OpenTable();

    echo "<a href='index.php'>" . _XD_MAIN . "</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;<a href='index.php?op=listcontents&amp;cat_id=$cat_id'>" . $category . "</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;" . _XD_EDITCONTENTS . "<br><br>
   <h4 style='text-align:left;'>" . _XD_EDITCONTENTS . '</h4>';

    include 'contentsform.php';

    CloseTable();

    xoops_cp_footer();

    exit();
}

if ('editcontentsgo' == $op) {
    $myts = MyTextSanitizer::getInstance();

    $title = $myts->addSlashes($review_title);

    $item = $myts->addSlashes($review_item);

    $label = $myts->addSlashes($review_label);

    $release = $myts->addSlashes($release_date);

    $format = $myts->addSlashes($release_format);

    $image = $myts->addSlashes($release_image);

    $amazon = $myts->addSlashes($release_amazon);

    $contents = $myts->addSlashes($review_contents);

    $nohtml = empty($review_nohtml) ? 0 : 1;

    $nosmiley = empty($review_nosmiley) ? 0 : 1;

    $noxcode = empty($review_noxcode) ? 0 : 1;

    $sql = 'UPDATE ' . $xoopsDB->prefix('lykos_reviews_contents') . " set review_title='" . $title . "', review_item='" . $item . "', review_label='" . $label . "', release_date='" . $release . "', release_format='" . $format . "', release_image='" . $image . "', release_amazon='" . $amazon . "', review_contents='" . $contents . "', review_time=" . time() . ', review_visible=' . (int)$review_visible
           . ', review_nohtml=' . $nohtml . ', review_nosmiley=' . $nosmiley . ', review_noxcode=' . $noxcode . ' WHERE review_id=' . $review_id . '';

    if (!$xoopsDB->query($sql)) {
        xoops_cp_header();

        echo 'Could not update contents';

        xoops_cp_footer();
    } else {
        /*
        lykos_reviews_write_conts(lykos_reviews_get_conts($category_id, 'review_title'), $category_id, 'review_title');
        lykos_reviews_write_conts(lykos_reviews_get_conts($category_id, 'review_item'), $category_id, 'review_item');
        lykos_reviews_write_conts(lykos_reviews_get_conts($category_id, 'review_label'), $category_id, 'review_label');
        */

        redirect_header("index.php?op=listcontents&amp;cat_id=$category_id", 1, _XD_DBSUCCESS);
    }

    exit();
}

if ('delcat' == $op) {
    if (1 == $ok) {
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('lykos_reviews_categories') . ' WHERE category_id=' . $cat_id . '';

        if (!$xoopsDB->query($sql)) {
            xoops_cp_header();

            echo 'Could not delete category';

            xoops_cp_footer();
        } else {
            $sql = 'DELETE FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . ' WHERE category_id=' . $cat_id . '';

            $xoopsDB->query($sql);

            //$filename = XOOPS_ROOT_PATH."/modules/lykos_reviews/cache/doc".$cat_id.".php";

            //unlink($filename);

            redirect_header('index.php?op=listcat', 1, _XD_DBSUCCESS);
        }

        exit();
    }

    xoops_cp_header();

    OpenTable();

    echo "<div align='center'>";

    echo "<h4 style='color:#ff0000'>" . _XD_RUSURECAT . '</h4>';

    echo "<table><tr><td>\n";

    echo myTextForm('index.php?op=delcat&amp;cat_id=' . $cat_id . "&amp;ok=1'", _YES);

    echo "</td><td>\n";

    echo myTextForm('index.php?op=listcat', _NO);

    echo "</td></tr></table>\n";

    echo '</div>';

    CloseTable();

    xoops_cp_footer();

    exit();
}

if ('delcontents' == $op) {
    if (1 == $ok) {
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . ' WHERE review_id=' . $id . '';

        if (!$xoopsDB->query($sql)) {
            xoops_cp_header();

            echo 'Could not delete contents';

            xoops_cp_footer();
        } else {
            //lykos_reviews_write_conts(lykos_reviews_get_conts($category_id, 'review_title'), $category_id, 'review_title');

            redirect_header('index.php', 1, _XD_DBSUCCESS);
        }

        exit();
    }

    xoops_cp_header();

    echo "<div align='center'>";

    echo "<h4 style='color=:#ff0000'>" . _XD_RUSURECONT . '</h4>';

    echo "<table><tr><td>\n";

    echo myTextForm('index.php?op=delcontents&amp;id=' . $id . '&amp;ok=1', _YES);

    echo "</td><td>\n";

    echo myTextForm("index.php?op=listcontents&amp;cat_id=$cat_id", _NO);

    echo "</td></tr></table>\n";

    echo '</div>';

    xoops_cp_footer();

    exit();
}
/*
function lykos_reviews_get_conts($cat_id, $ord){
   global $xoopsDB, $xoopsUser;
   $ret = "<table width='100%' cellpadding='4' cellspacing='0' border='0'><tr class='bg3'><td colspan='2'><span class='fg2'><b>" ._XD_TOC."</b></span> Sort by <a href=\"".XOOPS_URL."/modules/lykos_reviews/index.php?op=a&cat_id=".$cat_id."&sort_by=review_title\">title</a> / <a href=\"".XOOPS_URL."/modules/lykos_reviews/index.php?op=a&cat_id=".$cat_id."&sort_by=review_item\">album</a> / <a href=\"".XOOPS_URL."/modules/lykos_reviews/index.php?op=a&cat_id=".$cat_id."&sort_by=review_label\">label</a></td></tr><tr><td colspan='2'><ul style='list-style-image:url(images/question.gif);'>";
   $result = $xoopsDB->query("SELECT review_id, category_id, review_title, review_item, review_label, release_date, release_format, review_contents, review_uid, review_time, review_nohtml, review_nosmiley, review_noxcode FROM ".$xoopsDB->prefix("lykos_reviews_contents")." WHERE review_visible=1 AND category_id='".$cat_id."' ORDER BY ".$ord." ASC");
   $contents_arr = array();
   $title_arr = array();
   $myts = MyTextSanitizer::getInstance();
   while ( list($id, $cat_id, $title, $item, $label, $release, $format, $contents, $ruid, $time, $nohtml, $nosmiley, $noxcode) = $xoopsDB->fetchRow($result) ) {
      $title = htmlspecialchars($title);
      $title_arr[$id] = $title;
      $item = htmlspecialchars($item);
      $item_arr[$id] = $item;
      $label = htmlspecialchars($label);
      $label_arr[$id] = $label;
      $release = htmlspecialchars($release);
      $release_arr[$id] = $release;
      $format = htmlspecialchars($format);
      $format_arr[$id] = $format;
      $html = !empty($nohtml) ? 0 :1;
      $smiley = !empty($nosmiley) ? 0 :1;
      $xcode = !empty($noxcode) ? 0 :1;
      $contents_arr[$id] = $myts->displayTarea($contents, $html, $smiley, $xcode);
      $ruid = htmlspecialchars($ruid);
      $ruid_arr[$id] = $ruid;

      $ret .= "<li><a href='#".$id."'>".$title." - \"".$item."\" - ".$label."</a></li>";
   }
   $ret .= "</ul></td></tr></table><br><br><table width='100%' cellpadding='4' cellspacing='0' border='0'>";

   foreach ( $title_arr as $k => $v ) {
      if($ruid_arr[$k] == '0') {
         $submit = $xoopsConfig['anonymous'];
         //echo "Anon";
      }
      else {
         $thisUser= new XoopsUser($ruid_arr[$k] );
         $thisUser->getVar("uname");
         $thisUser->getVar("ruid");
         $submit = "<a href='".XOOPS_URL."/userinfo.php?uid=".$thisUser->uid()."'>".$thisUser->uname()."</a>";
      }

      $ret .= "<tr class='bg3'><td><a id='$k' name='$k'><b>".$v." - \"".$item_arr[$k]."\"</b> - ". $label_arr[$k] ." - ". $release_arr[$k] ."</a></td></tr><tr><td>".$contents_arr[$k]." - <i>".$submit."</i></td></tr><tr><td align='right'><a href='#top'>" ._XD_BACKTOTOP."</a></td></tr>";
   }

   $ret .= "</table>";
   return $ret;
}

function lykos_reviews_write_conts($contents, $cat_id, $orda){
   if (!xoopsfwrite()) {
      return false;
   }

   $filename = XOOPS_ROOT_PATH."/modules/lykos_reviews/cache/doc".$cat_id."_".$orda.".php";
   if ( !$file = fopen($filename, "w") ) {
      return false;
   }
   if ( fwrite($file, $contents) == -1 ) {
      return false;
   }
   fclose($file);
   return true;
}
*/
