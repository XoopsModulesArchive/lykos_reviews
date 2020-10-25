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

function b_lykos_reviews_latest_show()
{
    global $xoopsDB, $xoopsConfig;

    $myts = new MyTextSanitizer();

    $block = [];

    $block['content'] = '';

    $result = $xoopsDB->query('SELECT review_id, category_id, review_title, review_item, review_label,  review_time FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . ' WHERE review_visible=1 ORDER BY review_time DESC', 10, 0);

    $contents_arr = [];

    $title_arr = [];

    $myts = MyTextSanitizer::getInstance();

    while (list($rid, $cat_id, $title, $item, $label, $release, $contents, $ruid, $time, $nohtml, $nosmiley, $noxcode) = $xoopsDB->fetchRow($result)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $title_arr[$rid] = $title;

        $cat_id = htmlspecialchars($cat_id, ENT_QUOTES | ENT_HTML5);

        $cat_arr[$rid] = $cat_id;

        $item = htmlspecialchars($item, ENT_QUOTES | ENT_HTML5);

        $item_arr[$rid] = $item;

        $label = htmlspecialchars($label, ENT_QUOTES | ENT_HTML5);

        $label_arr[$rid] = $label;

        $block['content'] .= '<big><b>&middot;</b></big> ' . $title . '<br>';

        $block['content'] .= '<a href="' . XOOPS_URL . '/modules/lykos_reviews/index.php?op=r&cat_id=' . $cat_id . '&rev_id=' . $rid . '&sort_by">' . $item . '</a><br>';
    }

    $block['title'] = '' . _MI_B_LATEST . '';

    return $block;
}
