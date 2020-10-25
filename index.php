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
include 'include/functions.php';
require XOOPS_ROOT_PATH . '/header.php';

$myts = MyTextSanitizer::getInstance();

if ('lykos_reviews' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    make_cblock();
} else {
    $xoopsOption['show_rblock'] = 0;
}

$op = $_GET['op'];
$cat_id = (int)$_GET['cat_id'];
$rev_id = (int)$_GET['rev_id'];
$sort_by = $_GET['sort_by'];
$u_id = $_GET['uid'];

if (empty($op)) {
    $op = 'i';
}

if (empty($sort_by)) {
    $sort_by = 'review_title';
}

OpenTable();

// SHOW ONE REVIEW
if ('r' == $op) {
    $result = $xoopsDB->query('SELECT category_title FROM ' . $xoopsDB->prefix('lykos_reviews_categories') . " WHERE category_id='" . $cat_id . "'");

    [$category] = $xoopsDB->fetchRow($result);

    $category = htmlspecialchars($category, ENT_QUOTES | ENT_HTML5);

    echo "<h4 style='text-align:left;'>" . _XD_DOCS . "</h4><a id='top' name='top'><a href='index.php?op=i'>" . _XD_MAIN . "</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;<b><a href=\"index.php?op=c&cat_id=" . $cat_id . '">' . $category . '</a></b><br><br>';

    //Show 1 review

    $result = $xoopsDB->query('SELECT category_id, review_title, review_item, review_label, release_date, release_format, review_contents, review_uid, review_hits, review_time, review_nohtml, review_nosmiley, review_noxcode, release_image, release_amazon FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . " WHERE review_id='" . $rev_id . "'");

    [$cat_id, $title, $item, $label, $release, $format, $contents, $ruid, $hits, $time, $nohtml, $nosmiley, $noxcode, $image, $amazon] = $xoopsDB->fetchRow($result);

    echo display_review($rev_id, $cat_id, $category, $title, $item, $label, $format, $release, $ruid, $contents, $time, $hits, $nohtml, $nosmiley, $noxcode, $image, $amazon, 1);
}

// MAIN PAGE
elseif ('i' == $op) {
    echo "<h4 style='text-align:left;'><big>" . _XD_DOCS . '<big></h4>';

    echo "<table width='100%' cellpadding='4' cellspacing='0' border='0'>";

    echo "<tr><td width='50%'><b>" . _XD_CATEGORIES . "</b></td><td width='50%'>&nbsp;</td></tr>";

    echo "<tr><td width='50%'>";

    $result = $xoopsDB->query('SELECT category_id, category_title FROM ' . $xoopsDB->prefix('lykos_reviews_categories') . ' ORDER BY category_title ASC');

    while (list($cat_id, $category) = $xoopsDB->fetchRow($result)) {
        $category = htmlspecialchars($category, ENT_QUOTES | ENT_HTML5);

        echo "&nbsp;&nbsp;<img src='" . XOOPS_URL . "/modules/lykos_reviews/images/folder.gif' width='14' height='14' border='0'>&nbsp;<a href='index.php?op=c&cat_id=" . $cat_id . "&rev_id&sort_by'>" . $category . '</a>';

        $result2 = $xoopsDB->query('SELECT review_id FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . ' WHERE review_visible=1 AND category_id=' . $cat_id . '');

        $count2 = 0;

        while ($myrow = $xoopsDB->fetchArray($result2)) {
            $count2 += 1;
        }

        echo ' (' . $count2 . ')<br>';
    }

    //echo "</td><td width='50%'> ".searchbox()."</td></tr>";

    echo "</td><td width='50%'> </td></tr>";

    echo "<tr><td width='50%'><b>" . _XD_LATEST . '</b></td>';

    echo "<td width='50%'><b>" . _XD_RANDOM . '</b></td></tr>';

    echo "<tr><td width='50%'>";

    // Latest x reviews

    $result = $xoopsDB->query('SELECT review_id, category_id, review_title, review_item, review_label, review_time, review_hits FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . ' WHERE review_visible=1 ORDER BY review_time DESC', 10, 0);

    $contents_arr = [];

    $title_arr = [];

    $myts = MyTextSanitizer::getInstance();

    while (list($rid, $cat_id, $title, $item, $label, $time, $hits) = $xoopsDB->fetchRow($result)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $title_arr[$rid] = $title;

        $cat_id = htmlspecialchars($cat_id, ENT_QUOTES | ENT_HTML5);

        $cat_arr[$rid] = $cat_id;

        $item = htmlspecialchars($item, ENT_QUOTES | ENT_HTML5);

        $item_arr[$rid] = $item;

        $label = htmlspecialchars($label, ENT_QUOTES | ENT_HTML5);

        $label_arr[$rid] = $label;

        echo display_rev_link($cat_id, $rid, $title, $item, $time, $hits, 0);
    }

    //Random Review

    echo "</td><td width='50%'>";

    $result = $xoopsDB->query('SELECT category_id, review_title, review_item, review_label, release_date, release_format, review_contents, review_uid, review_hits, review_time, review_nohtml, review_nosmiley, review_noxcode FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . ' ORDER BY RAND()');

    [$cat_id, $title, $item, $label, $release, $format, $contents, $ruid, $hits, $time, $nohtml, $nosmiley, $noxcode] = $xoopsDB->fetchRow($result);

    echo display_review($rev_id, $cat_id, $category, $title, $item, $label, $format, $release, $ruid, $contents, $time, $hits, $nohtml, $nosmiley, $noxcode, $image, $amazon, 0);

    echo "</td></tr></table>\n";
}

// SHOW LSITING FOR ONE CATEGORY
elseif ('c' == $op) {
    $result = $xoopsDB->query('SELECT category_title FROM ' . $xoopsDB->prefix('lykos_reviews_categories') . " WHERE category_id='" . $cat_id . "'");

    [$category] = $xoopsDB->fetchRow($result);

    $category = htmlspecialchars($category, ENT_QUOTES | ENT_HTML5);

    echo "<h4 style='text-align:left;'>" . _XD_DOCS . "</h4><a id='top' name='top'><a href='index.php?op=i'>" . _XD_MAIN . "</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;<b>" . $category . '</b><br><br>';

    //echo sortbyoptions();

    $sql = 'SELECT review_id, review_title, review_item, review_time, review_hits FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . " WHERE review_visible=1 AND category_id='" . $cat_id . "' ORDER BY " . $sort_by . ' ASC';

    $result2 = $xoopsDB->query($sql);

    $list = '';

    $count = 0;

    while ($myrow = $xoopsDB->fetchArray($result2)) {
        $list .= '' . display_rev_link($cat_id, $myrow['review_id'], htmlspecialchars($myrow['review_title'], ENT_QUOTES | ENT_HTML5), htmlspecialchars($myrow['review_item'], ENT_QUOTES | ENT_HTML5), $myrow['review_time'], $myrow['review_hits']) . '';

        $count += 1;
    }

    if ($count > 0) {
        //echo "<ul style='list-style-image:url(images/question.gif);'>".$list."</ul>";

        echo $list;
    } else {
        echo "$count<br>";
    }
}

// SHOW REVIEWS BY ONE USER
elseif ('u' == $op) {
    // Latest x reviews

    echo "<h4 style='text-align:left;'>" . _XD_DOCS . "</h4><a href='index.php?op=i'>" . _XD_MAIN . '</a><br>';

    echo "<table width='100%' cellpadding='4' cellspacing='0' border='0'>";

    $thisUser = new XoopsUser($u_id);

    $thisUser->getVar('uname');

    echo "<tr><td width='50%'><b>" . _XD_RBYUSER . "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $thisUser->uid() . "'>" . $thisUser->uname() . '</a></b></td></tr>';

    echo "<tr><td width='50%'>";

    $result = $xoopsDB->query('SELECT review_id, category_id, review_title, review_item, review_label, review_time, review_hits FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . " WHERE review_visible=1 AND review_uid='" . $u_id . "' ORDER BY review_time DESC", 10, 0);

    $contents_arr = [];

    $title_arr = [];

    $myts = MyTextSanitizer::getInstance();

    while (list($rid, $cat_id, $title, $item, $label, $time, $hits) = $xoopsDB->fetchRow($result)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $title_arr[$rid] = $title;

        $cat_id = htmlspecialchars($cat_id, ENT_QUOTES | ENT_HTML5);

        $cat_arr[$rid] = $cat_id;

        $item = htmlspecialchars($item, ENT_QUOTES | ENT_HTML5);

        $item_arr[$rid] = $item;

        $label = htmlspecialchars($label, ENT_QUOTES | ENT_HTML5);

        $label_arr[$rid] = $label;

        echo display_rev_link($cat_id, $rid, $title, $item, $time, $hits, 0);
    }

    echo '</td></tr></table>';
}

CloseTable();
include(XOOPS_ROOT_PATH . '/footer.php');
