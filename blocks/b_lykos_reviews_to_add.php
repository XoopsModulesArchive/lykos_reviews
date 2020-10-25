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

function b_lykos_reviews_to_add_show()
{
    global $xoopsDB, $xoopsConfig;

    $myts = new MyTextSanitizer();

    $block = [];

    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . ' where review_visible=0');

    if ($result) {
        [$num] = $xoopsDB->fetchRow($result);

        $block['content'] .= '<big><b>&middot;</b></big><a href="' . XOOPS_URL . '/modules/lykos_reviews/admin/index.php">' . _MI_B_SUBMIT . '</a>: ' . $num;
    }

    $block['title'] = '' . _MI_B_SUBMIT . '';

    return $block;
}
