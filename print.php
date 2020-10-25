<?php

// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
// Based on:                             //
// myPHPNUKE Web Portal System - http://myphpnuke.com/              //
// PHP-NUKE Web Portal System - http://phpnuke.org/              //
// Thatware - http://thatware.org/                   //
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

if (empty($rev_id)) {
    redirect_header('index.php');
} else {
    $rev_id = (int)$rev_id;
}

function PrintPage($rev_id)
{
    global $xoopsConfig, $xoopsDB;

    $result = $xoopsDB->query('SELECT category_id, review_title, review_item, review_label, release_date, release_format, review_time, review_contents, review_uid, release_image FROM ' . $xoopsDB->prefix('lykos_reviews_contents') . " WHERE review_id='" . $rev_id . "'");

    [$cat_id, $title, $item, $label, $release, $format, $thetime, $contents, $image] = $xoopsDB->fetchRow($result);

    $t = date('d.m.Y - H:i', $thetime);

    echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>\n";

    echo "<html>\n<head>\n";

    echo '<title>' . $xoopsConfig['sitename'] . "</title>\n";

    echo "<meta http-equiv='Content-Type' content='text/html; charset=" . _CHARSET . "'>\n";

    echo "<meta name='AUTHOR' content='" . $xoopsConfig['sitename'] . "'>\n";

    echo "<meta name='COPYRIGHT' content='Copyright (c) 2001 by " . $xoopsConfig['sitename'] . "'>\n";

    echo "<meta name='DESCRIPTION' content='" . $xoopsConfig['slogan'] . "'>\n";

    echo "<meta name='GENERATOR' content='" . XOOPS_VERSION . "'>\n\n\n";

    echo "<body bgcolor='#ffffff' text='#000000'>
         <table border='0'><tr><td align='center'>
         <table border='0' width='640' cellpadding='0' cellspacing='1' bgcolor='#000000'><tr><td>
         <table border='0' width='640' cellpadding='20' cellspacing='1' bgcolor='#ffffff'><tr><td align='center'>
         <img src='" . XOOPS_URL . "/images/logo.gif' border='0' alt=''><br><br>
         <h3>" . $title . ' - "' . $item . '"</h3>
         ' . $label . ' ' . $format . ' ' . $release . '<br><br>
         <small><b>' . _NW_DATE . '</b>&nbsp;' . $t . '<br></small><br></td></tr>';

    echo '<tr><td>' . $contents . '<br><br>';

    echo "</td></tr></table></td></tr></table>
         <br><br>\n";

    printf(_NW_THISCOMESFROM, $xoopsConfig['sitename']);

    echo "<br><a href='" . XOOPS_URL . "/'>" . XOOPS_URL . '</a><br><br>
      ' . _NW_URLFORSTORY . "<br>                        
      <a href='" . XOOPS_URL . '/modules/lykos_reviews/index.php?op=r&cat_id' . $cat_id . '&rev_id=' . $rev_id . "'>" . XOOPS_URL . '/lykos_reviews/index.php?op=r&cat_id' . $cat_id . '&rev_id=' . $rev_id . '</a>
      </td></tr></table>
      </body>
      </html>';
}

PrintPage($rev_id);
