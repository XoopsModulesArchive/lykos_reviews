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

include 'header.php';
$myts = MyTextSanitizer::getInstance();

$xoopsOption['show_rblock'] = 0;
require XOOPS_ROOT_PATH . '/header.php';

//Global $xoopsUser, $xoopsConfig;

$op = 'submit';

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

    //OpenTable();

    //echo ""._XD_CATEGORY.": ".$category_id;

    $html = empty($review_nohtml) ? 1 : 0;

    $smiley = empty($review_nosmiley) ? 1 : 0;

    $xcode = empty($review_noxcode) ? 1 : 0;

    $p_title = htmlspecialchars($review_title, ENT_QUOTES | ENT_HTML5);

    $p_item = htmlspecialchars($review_item, ENT_QUOTES | ENT_HTML5);

    $p_label = htmlspecialchars($review_label, ENT_QUOTES | ENT_HTML5);

    $p_release = htmlspecialchars($release_date, ENT_QUOTES | ENT_HTML5);

    $p_format = htmlspecialchars($release_format, ENT_QUOTES | ENT_HTML5);

    $p_contents = $myts->previewTarea($review_contents, $html, $smiley, $xcode);

    echo "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'><tr><td class='odd'>
   <table width='100%' border='0' cellpadding='4' cellspacing='1'>
   <tr class='bg3' align='center'><td align='left'>$p_title</td></tr>
   <tr class='bg3' align='center'><td align='left'>$p_item</td></tr>
   <tr class='bg3' align='center'><td align='left'>$p_label</td></tr>
   <tr class='bg3' align='center'><td align='left'>$p_release</td></tr>
   <tr class='bg3' align='center'><td align='left'>$p_format</td></tr>
   <tr class='bg1'><td>$p_contents</td></tr></table></td></tr></table><br>";

    $review_title = htmlspecialchars($review_title, ENT_QUOTES | ENT_HTML5);

    $review_item = htmlspecialchars($review_item, ENT_QUOTES | ENT_HTML5);

    $review_label = htmlspecialchars($review_label, ENT_QUOTES | ENT_HTML5);

    $release_date = htmlspecialchars($release_date, ENT_QUOTES | ENT_HTML5);

    $release_format = htmlspecialchars($release_format, ENT_QUOTES | ENT_HTML5);

    $review_contents = htmlspecialchars($review_contents, ENT_QUOTES | ENT_HTML5);

    include 'contentsform.php';

    //CloseTable();

    include(XOOPS_ROOT_PATH . '/footer.php');

    exit();
}

if ('submit' == $op) {
    OpenTable();

    $myts = MyTextSanitizer::getInstance();

    echo "<h4 style='text-align:left;'>" . _XD_ADDCONTENTS . '</h4>';

    $review_title = '';

    $review_contents = '';

    $review_item = '';

    $review_label = '';

    $release_date = '';

    $release_format = '';

    $review_visible = 0;

    $review_nohtml = 0;

    $review_nosmiley = 0;

    $review_noxcode = 0;

    $review_id = 0;

    $category_id = 0;

    $op = 'addcontentsgo';

    include 'contentsform.php';

    CloseTable();

    include(XOOPS_ROOT_PATH . '/footer.php');

    exit();
}

if ('addcontentsgo' == $op) {
    OpenTable();

    $myts = MyTextSanitizer::getInstance();

    $title = $myts->addSlashes($review_title);

    $item = $myts->addSlashes($review_item);

    $label = $myts->addSlashes($review_label);

    $release = $myts->addSlashes($release_date);

    $format = $myts->addSlashes($release_format);

    $contents = $myts->addSlashes($review_contents);

    /*
    $result = $xoopsDB->query("SELECT category_id, category_title FROM ".$xoopsDB->prefix("lykos_reviews_categories")."");
    while(list($cat,$cat_title) = $xoopsDB->fetchRow($result)) {
       if($category_id==$cat_title) {
          $category_id=$cat;
       }
    }
    */

    $category_id = $myts->addSlashes($category_id);

    if ($xoopsUser->uid()) {
        $submitter = $xoopsUser->uid();
    } else {
        $submitter = 0;
    }

    $newid = $xoopsDB->genId($xoopsDB->prefix('lykos_reviews_contents') . '_review_id_seq');

    $sql = 'INSERT INTO ' . $xoopsDB->prefix('lykos_reviews_contents') . ' (review_id, category_id, review_title, review_item, review_label, release_date, release_format, review_contents, review_uid, review_time, review_visible, review_nohtml, review_nosmiley, review_noxcode) VALUES (' . $newid . ', ' . $category_id . ", '" . $title . "', '" . $item . "', '" . $label . "', '" . $release . "', '" . $format . "', '" . $contents . "', '" . $submitter . "'," . time() . ',  ' . (int)$review_visible
           . ', ' . (int)$review_nohtml
           . ', ' . (int)$review_nosmiley
           . ', ' . (int)$review_noxcode
           . ')';

    if (!$xoopsDB->query($sql)) {
        echo 'Could not add contents';

        CloseTable();

        include(XOOPS_ROOT_PATH . '/footer.php');
    } else {
        redirect_header('submit.php', 1, _XD_DBSUCCESS);
    }

    exit();
}
