<?php

if (!isset($category)) {
    $category = [];
}

$html = '';
$categoryId = 1;
$totalCategory = count($category);
foreach ($category as $key1 => $value1) {
    $html .= '<div class="popup popup-dynamic" data-order="'.$categoryId.'" id="categoryPopup-'.$categoryId.'">';

    $index = 0;
    foreach ($value1 as $key2 => $value2) {
        if ($index == 0) {
            $html .= '<div class="popup-header category-popup-header"><h2>'.$key2.'<span class="badge bg-success">任意</span></h2><span class="popup-close">✖</span></div>';
        } else {
            $html .= '<div class="popup-header category-popup-header"><h3>'.$key2.'</h3></div>';
        }

        $html .= '<div class="popup-checkbox-content category-content">';
        foreach ($value2 as $value3) {
            $html .= '<label><input type="checkbox" data-key="'.$value3['id'].'" data-value="'.$value3['level3'].'">'.$value3['level3'].'</label>';
        }

        $html .= '</div>';

        $index++;
    }

    $html .= '<div class="popup-footer previous-footer"><div><button class="open-previous-popup">戻る</button></div>';

    if ($categoryId >= $totalCategory) {
        $html .= '<div><button class="clear-data bg-warning m-r-5">クリア</button><button class="search-hospital end-popup">検索</button></div>';
    } else {
        $html .= '<div><button class="clear-data bg-warning m-r-5">クリア</button><button class="open-next-popup">次へ</button></div>';
    }


    $html .= '</div></div>';

    $categoryId++;
}

echo $html;