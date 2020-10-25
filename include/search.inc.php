<?php

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
function lykos_reviews_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $sql = 'SELECT review_id, category_id, review_title, review_item, review_label, review_contents, review_time, review_uid FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . ' WHERE review_visible=1';

    // because count() returns 1 even if a supplied variable

    // is not an array, we must check if $querryarray is really an array

    $count = count($queryarray);

    if ($count > 0 && is_array($queryarray)) {
        $sql .= " AND ((review_title LIKE '%$queryarray[0]%' OR review_contents LIKE '%$queryarray[0]%' OR review_item LIKE '%$queryarray[0]%' OR review_label LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(review_title LIKE '%$queryarray[$i]%' OR review_contents LIKE '%$queryarray[$i]%' OR review_item LIKE '%$queryarray[$i]%' OR review_label LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= ' ORDER BY review_id DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $ret = [];

    $i = 0;

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret[$i]['image'] = 'images/question.gif';

        $ret[$i]['link'] = 'index.php?op=r&rev_id=' . $myrow['review_id'] . '&cat_id=' . $myrow['category_id'];

        $ret[$i]['title'] = $myrow['review_title'] . ' - "' . $myrow['review_item'] . '" - ' . $myrow['review_label'];

        $ret[$i]['time'] = $myrow['review_time'];

        //$ret[$i]['uid'] = $myrow['review_uid'];

        $i++;
    }

    return $ret;
}
