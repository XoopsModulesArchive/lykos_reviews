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
echo "

<form action='submit.php' method='post'>

<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'><tr><td class='odd'>

<table width='100%' border='0' cellpadding='4' cellspacing='1'>";

echo "<tr><td nowrap='nowrap' class='bg3'>" . _XD_CATEGORY . " </td><td class='odd'>";
echo '<select name="category_id">';
$a = 0;
$result = $xoopsDB->query('SELECT category_id, category_title FROM ' . $xoopsDB->prefix('lykos_reviews_categories') . '');
while (list($cat_id, $cat_title) = $xoopsDB->fetchRow($result)) {
    if ($cat_id == $category_id) {
        echo "<option value=\"$cat_id\" selected>$cat_title</option>\n";

        $a = 1;
    } else {
        echo "<option value=\"$cat_id\">$cat_title</option>\n";
    }
}
if (0 == $a) {
    echo '<option selected>[' . _REV_CHOOSECAT . ']';
}
echo '</select>';

/*

echo "<tr><td nowrap='nowrap' class='bg3'>"._XD_CATEGORY." </td><td class='bg1'>
      <select name='category_id' >";

$result = $xoopsDB->query("SELECT category_id, category_title FROM ".$xoopsDB->prefix("lykos_reviews_categories")."");
while(list($cat_id,$cat_title) = $xoopsDB->fetchRow($result)) {
   if($category_id==$cat_title) {
      echo "<option name\"$cat\" selected>$cat_title</option>\n";
   }
   else {
      echo "<option name\"$cat\">$cat_title</option>\n";
   }
}

echo "</select>\n";*/

echo '</td></tr> ';

echo "<tr><td nowrap='nowrap' class='bg3'>" . _XD_QUESTION . " </td><td class='bg1'><input type='text' name='review_title' value='$review_title' size='31' maxlength='255'></td></tr>

<tr><td nowrap='nowrap' class='bg3'>" . _XD_ITEM . " </td><td class='bg1'><input type='text' name='review_item' value='$review_item' size='31' maxlength='255'></td></tr>

<tr><td nowrap='nowrap' class='bg3'>" . _XD_LABEL . " </td><td class='bg1'><input type='text' name='review_label' value='$review_label' size='31' maxlength='255'></td></tr>

<tr><td nowrap='nowrap' class='bg3'>" . _XD_RELEASE . " </td><td class='bg1'><input type='text' name='release_date' value='$release_date' size='31' maxlength='255'></td></tr>

<tr><td nowrap='nowrap' class='bg3'>" . _XD_FORMAT . " </td><td class='bg1'><input type='text' name='release_format' value='$release_format' size='31' maxlength='255'></td></tr>";

$checked = (1 == $review_visible) ? ' checked' : '';

//echo "<tr><td nowrap='nowrap' class='bg3'>"._XD_DISPLAY." </td><td class='bg1'><input type='checkbox' name='review_visible' value='0'$checked></td></tr>

echo "<tr><td nowrap='nowrap' class='bg3'>" . _XD_ANSWER . " </td><td class='bg1'>";

require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';

xoopsCodeTarea('review_contents', 60, 20);

xoopsSmilies('review_contents');

//$checked = ($review_nohtml == 1) ? " checked" : "";

//echo "<br><input type='checkbox' name='review_nohtml' value='1'$checked>"._XD_NOHTML."<br>";

$checked = (1 == $review_nosmiley) ? ' checked' : '';

echo "<br><input type='checkbox' name='review_nosmiley' value='1'$checked>" . _XD_NOSMILEY . '<br>';

$checked = (1 == $review_noxcode) ? ' checked' : '';

echo "<input type='checkbox' name='review_noxcode' value='1'$checked>" . _XD_NOXCODE . "</td></tr>

<tr><td nowrap='nowrap' class='bg3'>&nbsp;</td><td class='bg1'><input type='hidden' name='review_visible' value='0'><input type='hidden' name='review_nohtml' value='0'><input type='hidden' name='review_id' value='" . $review_id . "'><input type='hidden' name='op' value='$op'><input type='submit' name='contents_preview' value='" . _PREVIEW . "'>&nbsp;<input type='submit' name='contents_submit' value='" . _SUBMIT . "'></td></tr>

</table></td></tr></table>

</form>";
