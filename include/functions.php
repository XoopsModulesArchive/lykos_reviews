<?php

function newlinkgraphic($time)
{
    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $time) {
        return '&nbsp;<img src="' . XOOPS_URL . '/modules/lykos_reviews/images/newred.gif" alt="' . _MD_NEWTHISWEEK . '">';
    }

    return '';
}

function popgraphic($hits)
{
    $popular = 20;

    if ($hits >= $popular) {
        return '&nbsp;<img src="' . XOOPS_URL . '/modules/lykos_reviews/images/pop.gif" alt="' . _MD_POPULAR . '">';
    }

    return '';
}

function friendgraphic($rev_id, $cat_id, $title, $item)
{
    global $xoopsConfig;

    $disp = "<a target='_top' href='mailto:?subject=" . _MD_INTRESTLINK . '' . $xoopsConfig['sitename'] . '&body=' . sprintf(_MD_INTLINKFOUND . $title . ' - ' . $item, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/lykos_reviews/index.php?op=r%26rev_id=' . $rev_id . '%26cat_id=' . $cat_id . "'><img src='images/friend.gif' width='15' height='11'></a> ";

    return $disp;
}

function printgraphic($rev_id)
{
    return "<a target='_top' href='print.php?rev_id=" . $rev_id . "'><img src='images/print.gif' width='15' height='11'></a>";
}

function display_rev_link($cat_id, $rev_id, $title, $item, $time, $hits, $break)
{
    $disp = "<big><b>&middot;</b></big>$title";

    if ($break) {
        $disp .= newlinkgraphic($time);

        $disp .= popgraphic($hits);

        $disp .= '<br>';
    } else {
        $disp .= ' ';
    }

    $disp .= '<a href="index.php?op=r&cat_id=' . $cat_id . '&rev_id=' . $rev_id . '&sort_by">' . $item . '</a>';

    if (!$break) {
        $disp .= newlinkgraphic($time);

        $disp .= popgraphic($hits);
    }

    $disp .= '<br>';

    return $disp;
}

function display_review($rev_id, $cat_id, $category, $title, $item, $label, $format, $release, $ruid, $contents, $time, $hits, $nohtml, $nosmiley, $noxcode, $image, $amazon, $showrelated)
{
    global $xoopsUser,$xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $item = htmlspecialchars($item, ENT_QUOTES | ENT_HTML5);

    $label = htmlspecialchars($label, ENT_QUOTES | ENT_HTML5);

    $release = htmlspecialchars($release, ENT_QUOTES | ENT_HTML5);

    $format = htmlspecialchars($format, ENT_QUOTES | ENT_HTML5);

    $html = !empty($nohtml) ? 0 : 1;

    $smiley = !empty($nosmiley) ? 0 : 1;

    $xcode = !empty($noxcode) ? 0 : 1;

    $contents = $myts->displayTarea($contents, $html, $smiley, $xcode);

    $ruid = htmlspecialchars($ruid, ENT_QUOTES | ENT_HTML5);

    $image = htmlspecialchars($image, ENT_QUOTES | ENT_HTML5);

    $amazon = htmlspecialchars($amazon, ENT_QUOTES | ENT_HTML5);

    if (0 == $ruid) {
        $submit = $xoopsConfig['anonymous'];
    } else {
        $thisUser = new XoopsUser($ruid);

        $thisUser->getVar('uname');

        $thisUser->getVar('ruid');

        $submit = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $thisUser->uid() . "'>" . $thisUser->uname() . '</a>';
    }

    $hits++;

    $disp = "<table width='100%' cellpadding='4' cellspacing='0' border='0'>";

    if ($showrelated) {
        $disp .= "<tr><td width='70%' class='bg3'><b>" . $title . '</b> - "' . $item . '"';
    } else {
        $disp .= "<tr><td width='1000%' class='bg3'><b>" . $title . '</b> - "' . $item . '"';
    }

    if (!empty($label)) {
        $disp .= ' (' . $label . ') ';
    }

    $disp .= $format . ' ' . $release . '</a>';

    $disp .= newlinkgraphic($time);

    $disp .= popgraphic($hits);

    $disp .= '<br>(' . $hits . ' ' . _REV_READ . ') ';

    if ($showrelated) {
        $disp .= "</td><td width='30%' border='1' padding='10'><b><big>" . _RELATEDINFO . '</big></b></td></tr>';
    } else {
        $disp .= '</td></tr>';
    }

    if ($showrelated) {
        $disp .= "<tr><td width='70%'>" . $contents . ' - <i>' . $submit . '</i><br>';
    } else {
        $disp .= "<tr><td width='100%'>" . $contents . ' - <i>' . $submit . '</i><br>';
    }

    if (!empty($image) && mb_strlen($image) > 1) {
        $disp .= "<img src='images/reviews/" . $image . "' align='right' hspace='10' vspace='10'>";
    }

    if (!empty($amazon)) {
        $disp .= "<a href='" . $amazon . "'>" . _REV_BUY . '</a>';
    }

    if ($showrelated) {
        $disp .= "</td><td width='30%' border='1' padding='10'>";

        $disp .= "<a href='index.php?op=c&cat_id=" . $cat_id . "'>" . _MORECAT . ' ' . $category . ' ' . _MORECAT2 . '</a><br>';

        if (0 != $ruid) {
            $disp .= "<a href='index.php?op=u&uid=" . $ruid . "'>" . _MOREUSER . ' ' . $thisUser->getVar('uname') . '</a><br>';
        }

        $disp .= '<br><br>' . friendgraphic($rev_id, $cat_id, $title, $item);

        $disp .= printgraphic($rev_id) . '<br>';

        if ($xoopsUser) {
            $xoopsModule = XoopsModule::getByDirname('lykos_reviews');

            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                $disp .= "[<a href='admin/index.php?op=editcontents&id=" . $rev_id . '&cat_id=' . $cat_id . "'>" . _REV_EDIT . '</a> /';

                $disp .= " <a href='admin/index.php?op=delcontents&id=" . $rev_id . '&cat_id=' . $cat_id . "'>" . _REV_DELETE . '</a>] <br>';
            }
        }
    } else {
        $disp .= '';
    }

    $disp .= '</td></tr></table>';

    $sql3 = 'UPDATE ' . $xoopsDB->prefix('lykos_reviews_contents') . " SET review_hits='" . $hits . "'  WHERE review_id='" . $rev_id . "'";

    $xoopsDB->queryF($sql3);

    return $disp;
}

function searchbox()
{
    $disp = "<div align='center'><br><form action='" . XOOPS_URL . "/search.php' method='post'>\n"
          . "<input type='text' name='query' size='14'>\n"
          . "<input type='hidden' name='action' value='results'>\n"
          . "<br><input type='submit' value='" . _MB_SYSTEM_SEARCH . "'>\n"
          . "</form>\n</div>";

    return $disp;
}

function sortbyoptions()
{
    $disp = 'Sort by <a href="' . XOOPS_URL . '/modules/lykos_reviews/index.php?op=c&cat_id=' . $cat_id . '&sort_by=review_title">title</a>';

    $disp .= '/ <a href="' . XOOPS_URL . '/modules/lykos_reviews/index.php?op=c&cat_id=' . $cat_id . '&sort_by=review_item">album</a>';

    $disp .= '/ <a href="' . XOOPS_URL . '/modules/lykos_reviews/index.php?op=c&cat_id=' . $cat_id . '&sort_by=review_label">label</a><br>';

    //$disp .= "<span style='font-weight:bold;'>&raquo;&raquo;</span> <a href=\"".XOOPS_URL."/modules/lykos_reviews/index.php?op=a&cat_id=".$cat_id."&sort_by=".$sort_by."\">"._XD_LISTALL."</a><br><br>";

    return $disp;
}
