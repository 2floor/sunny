<?php

class render_html_helper
{
    public static function renderRank($rank, $iconPath)
    {
        if (in_array(($rank ?? null), [1, 2, 3])) {
            $html = '<img src="'.$iconPath.'/rank' . $rank . '.png" alt="rank-img">';
        } else {
            $html = ($rank != null && $rank != '') ? $rank . '位' : '-';
        }

        return $html;
    }
}
