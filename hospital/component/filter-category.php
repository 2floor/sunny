<?php

if (!isset($category)) {
    $category = [];
}

$html = '';
$categoryId = 1;
foreach ($category as $key1 => $value1) {
    $html .= '
        <div class="filter-group">
            <div class="filter-header show-popup show-popup-dynamic" data-order="'.$categoryId.'" id="category-'.$categoryId.'">
                <h3>'.$key1.'</h3>
                <span class="badge bg-success">任意</span>
                <span class="toggle">+</span>
            </div>
            <div class="filter-content content-option">
            </div>
        </div>
    ';

    $categoryId++;
}

echo $html;