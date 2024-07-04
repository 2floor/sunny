<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . "/../required/page_init.php";
require_once __DIR__ . "/../logic/front/auth_logic.php";

$auth_logic = new auth_logic();
$auth_logic->check_authentication();
$page_init = new page_init();
$pageinfo = $page_init->get_info();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php print $pageinfo->html_head; ?>
    <style>
        main {
            padding-top: 100px;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 90vw;
        }

        .main-search {
            flex: 1;
            box-sizing: border-box;
            display: grid;
            grid-template-columns: 30% 70%;
            grid-template-areas: "search-filter search-result";
            gap: 20px;
        }

        .search-filter,
        .search-result {
            background-color: #ffffff;
            padding: 50px 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            max-height: 1100px;
            overflow-y: auto;
        }

        .search-filter {
            grid-area: search-filter;
            transition: max-height 0.3s ease;
        }

        .search-result {
            grid-area: search-result;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            position: relative;
        }

        .search-filter h2 {
            text-align: center;
            font-weight: bolder;
            margin-bottom: 50px;
        }

        .filter-group {
            margin-bottom: 40px;
        }

        .filter-header {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
        }

        .show-popup {
            cursor: pointer;
        }

        .show-popup:hover {
            background-color: #bfbfbf;;
        }

        .filter-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bolder;
        }

        .filter-header .badge {
            position: absolute;
            top: -7px;
            right: -7px;
            background-color: #ff5733;
            color: #ffffff;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 12px;
        }

        .filter-content {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }

        .filter-content span {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            margin: 5px 0.2rem;
        }

        .filter-content .keyword {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .content-required span {
            background-color: #FFA629;
            color: #2E2E2E;
        }

        .content-option span {
            background-color: #9747FF;
            color: #ffffff;
        }

        .filter-group .toggle {
            font-size: 20px;
            cursor: pointer;
            font-weight: bolder;
        }

        .filter-group-spaced {
            margin-top: 200px;
        }

        .radio-group {
            display: flex;
            flex-direction: column;
        }

        .radio-group label {
            margin-bottom: 10px;
        }

        .radio-group input[type="radio"] {
            margin-right: 10px;
        }

        input[type="radio"], input[type="checkbox"] {
            appearance: auto;
            display: inline-block;
        }

        .search-button {
            width: 100%;
            padding: 10px;
            background-color: #0D99FF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bolder;
        }

        .search-button:hover {
            background-color: #0984dc;
        }

        .toggle-button {
            display: none;
        }

        /* Popup styles */
        .popup-container {
            position: absolute;
            top: 12%;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            height: 100%;
            display: none;
            z-index: 10;
        }

        .popup {
            display: none;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            z-index: 1000;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }

        .category-popup-header {
            margin-bottom: 0;
        }

        .popup-header h2 {
            margin: 0;
            font-size: 30px;
            font-weight: bolder;
        }

        .popup-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: bolder;
        }

        .popup-close {
            cursor: pointer;
            font-size: 24px;
            font-weight: bolder;
        }

        .popup-checkbox-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 15px;
        }

        .category-content {
            margin-bottom: 10px;
        }

        .popup-checkbox-content label {
            display: flex;
            align-items: center;
            font-size: 16px;
        }

        .popup-checkbox-content input[type="checkbox"] {
            margin-right: 10px;
        }

        .popup-selection-content h2 {
            font-weight: bolder;
        }

        .popup-selection-content label {
            font-size: 16px;
        }

        .popup-selection-content .form-group {
            margin-bottom: 30px;
            display: flex;
            flex-direction: row;
        }

        .popup-selection-content .form-group label {
            display: block;
            margin-bottom: 5px;
            width: 30%;
        }

        .popup-selection-content .form-group input[type="checkbox"] {
            margin-right: 10px;
        }

        .popup-selection-content .form-group .select2-container {
            width: 100% !important;
            font-size: 16px;
        }

        .popup-selection-content .select2-selection--single {
            height: 35px;
            align-content: center;
        }

        .popup-footer {
            padding: 15px;
            text-align: right;
        }

        .next-footer {
            display: flex;
            justify-content: end;
        }

        .previous-footer {
            display: flex;
            justify-content: space-between;
        }

        .popup-footer button {
            background-color: #0D99FF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
        }

        .popup-footer button:hover {
            background-color: #0984dc;
        }

        .search-result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            width: 100%;
            padding: 0 20px;
            margin-top: 5px;
        }

        .search-result-header label{
            font-weight: bolder;
            font-size: 16px;
        }

        .search-result-header-right {
            display: flex;
            align-items: center;
        }

        .search-result-header-right span {
            margin-right: 14px;
            font-weight: bolder;
            font-size: 16px;
        }

        .confirm-button {
            background-color: #0D99FF;
            color: #fff;
            border: none;
            padding: 5px 30px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .confirm-button:hover {
            background-color: #0984dc;
        }

        .hospital-card {
            display: grid;
            grid-template-columns: 8% 52% 40%;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        .hospital-card .card-checkbox {
            display: flex;
            align-items: start;
            justify-content: center;
            padding: 20px;
        }

        .hospital-card .card-info {
            padding: 20px;
        }

        .hospital-card .card-info .tag {
            color: #0b0b0b;
            background-color: #e0e0e0;
            padding: 7px 15px;
            border-radius: 10px;
            display: inline-block;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .hospital-info h2 {
            margin-bottom: 0;
        }

        .hospital-info span{
            font-size: 12px;
        }

        .hospital-card i {
            margin-bottom: 15px;
        }

        .hospital-info p {
            width: 80%;
            font-size: 14px;
            color: #555;
            font-weight: 600;
        }

        .card-info .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .card-info .categories span {
            background-color: #e0e0e0;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            color: #0b0b0b;
            font-weight: 700;
            max-width: 70px;
            text-align: center;
            max-height: 48px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .card-stats {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 30px;
        }

        .hospital-rank {
            display: grid;
            gap: 15px;
            font-weight: 700;
            grid-template-columns: 20% 20% 20% 40%;
            grid-template-areas:
        "header1 header2 header3 header4"
        "stat1 stat2 stat3 stat4"
        "stat5 stat6 stat7 stat8"
        "stat9 stat10 stat11 stat12";
        }

        .header1, .header2, .header3, .header4,
        .stat1, .stat2, .stat3, .stat4,
        .stat5, .stat6, .stat7, .stat8,
        .stat9, .stat10, .stat11, .stat12 {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header1, .header2, .header3, .header4 {
            font-weight: bolder;
        }

        .rank-icon {
            border-radius: 50%;
            color: #fff;
            font-weight: bold;
            padding: 5px 10px;
        }

        .rank-row-1 {
            background-color: #f39c12;
        }

        .rank-row-2 {
            background-color: #27ae60;
        }

        .rank-row-3 {
            background-color: #2980b9;
        }

        .stat {
            position: relative;
        }

        .stat-info-extended {
            position: absolute;
            transform: translateX(-5%);
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .dpc-info {
            text-align: center;
        }

        .dpc-info p {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .dpc-info p span {
            font-size: 30px;
        }

        @media (max-width: 1200px) {
            .main-search {
                grid-template-columns: 1fr;
                grid-template-areas: "search-filter" "search-result";
            }

            .search-filter {
                max-height: 90px;
                overflow: hidden;
                padding: 20px;
            }

            .search-filter.expanded {
                max-height: none;
            }

            .search-title {
                display: flex;
                flex-direction: row;
            }

            .search-title h2 {
                flex-grow: 3
            }

            .toggle-button {
                display: block;
                text-align: right;
                cursor: pointer;
                font-size: 24px;
                padding: 10px;
            }

            .popup-container {
                top: 10%
            }
            
            .popup-checkbox-content {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 15px;
            }

            .search-result {
                position: unset;
            }

            .popup-selection-content .form-group {
                flex-direction: column;
            }

            .popup-selection-content .form-group label {
                width: 100%;
            }

            .hospital-card {
                grid-template-columns: 1fr;
            }

            .hospital-card .card-checkbox {
                justify-content: left;
                padding: 10px 20px;
            }

            .search-result-header {
                flex-direction: column;
            }

            .search-result-header-right {
                flex-direction: column;
                margin-top: 10px;
                gap: 10px;
            }

            .confirm-button {
                padding: 5px 25px;
            }

            .stat-info-extended {
                padding: 5px 0;
            }

            .header1, .header2, .header3, .header4 {
                align-items: start;
            }
        }
    </style>
</head>

<body>
<?php print $pageinfo->header; ?>

<main>
    <div class="container">
        <div class="main-search">
            <div class="search-filter">
                <div class="search-title">
                    <h2>検索条件を選択</h2>
                    <div class="toggle-button" id="expandedSearchFilter">☰</div>
                </div>
                <div class="filter-group">
                    <div class="filter-header show-popup" id="cancerType">
                        <h3>ガン種類</h3>
                        <span class="badge bg-danger">必須</span>
                        <span class="toggle">+</span>
                    </div>
                    <div class="filter-content content-required">
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-header show-popup" id="area">
                        <h3>エリア</h3>
                        <span class="badge bg-success">任意</span>
                        <span class="toggle">+</span>
                    </div>
                    <div class="filter-content content-option">
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-header show-popup" id="hospitalDetail">
                        <h3>病院詳細</h3>
                        <span class="badge bg-success">任意</span>
                        <span class="toggle">+</span>
                    </div>
                    <div class="filter-content content-option">
                    </div>
                </div>

                <div class="filter-group filter-group-spaced">
                    <div class="filter-header">
                        <h3>その他</h3>
                        <span class="toggle">—</span>
                    </div>
                    <div class="filter-content">
                        <input type="text" class="keyword" placeholder="特に指定がない場合は、こちらに入力してください。">
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-header">
                        <h3>並び替え</h3>
                        <span class="toggle">—</span>
                    </div>
                    <div class="filter-content">
                        <div class="radio-group">
                            <label><input type="radio" name="sort" value="入院患者数">入院患者数</label>
                            <label><input type="radio" name="sort" value="外来患者数">外来患者数</label>
                            <label><input type="radio" name="sort" value="その他">その他</label>
                        </div>
                    </div>
                </div>
                <button class="search-button search-hospital">検索</button>
            </div>

            <div class="search-result">
                <div class="popup-container">
                    <div class="popup" id="cancerPopup">
                        <div class="popup-header">
                            <h2>ガン種類をご選択ください。 <span class="badge bg-danger">必須</span></h2>
                            <span class="popup-close">✖</span>
                        </div>
                        <div class="popup-checkbox-content">
                            <label><input type="checkbox" data-key="1" data-value="胃がん"> 胃がん</label>
                            <label><input type="checkbox" data-key="2" data-value="肝細胞がん"> 肝細胞がん</label>
                            <label><input type="checkbox" data-key="3" data-value="子宮頸がん"> 子宮頸がん</label>
                            <label><input type="checkbox" data-key="4" data-value="肝内胆管がん"> 肝内胆管がん</label>
                            <label><input type="checkbox" data-key="5" data-value="子宮体がん"> 子宮体がん</label>
                            <label><input type="checkbox" data-key="6" data-value="膀胱がん"> 膀胱がん</label>
                            <label><input type="checkbox" data-key="7" data-value="卵巣がん"> 卵巣がん</label>
                            <label><input type="checkbox" data-key="8" data-value="腎（細胞）がん"> 腎（細胞）がん</label>
                            <label><input type="checkbox" data-key="9" data-value="直腸がん"> 直腸がん</label>
                            <label><input type="checkbox" data-key="10" data-value="咽頭がん"> 咽頭がん</label>
                            <label><input type="checkbox" data-key="11" data-value="腎孟尿管がん"> 腎孟尿管がん</label>
                            <label><input type="checkbox" data-key="12" data-value="肺がん"> 肺がん</label>
                            <label><input type="checkbox" data-key="13" data-value="前立腺がん"> 前立腺がん</label>
                            <label><input type="checkbox" data-key="14" data-value="食道がん"> 食道がん</label>
                            <label><input type="checkbox" data-key="15" data-value="結腸がん"> 結腸がん</label>
                            <label><input type="checkbox" data-key="16" data-value="胆嚢がん"> 胆嚢がん</label>
                            <label><input type="checkbox" data-key="17" data-value="すい臓がん"> すい臓がん</label>
                            <label><input type="checkbox" data-key="18" data-value="甲状腺がん"> 甲状腺がん</label>
                            <label><input type="checkbox" data-key="19" data-value="乳がん"> 乳がん</label>
                            <label><input type="checkbox" data-key="20" data-value="脳腫瘍"> 脳腫瘍</label>
                        </div>
                        <div class="popup-footer next-footer">
                            <div style="margin-right: 5px">
                                <button class="clear-data bg-warning">クリア</button>
                            </div>
                            <div>
                                <button class="open-next-popup">次へ</button>
                            </div>
                        </div>
                    </div>

                    <div class="popup" id="areaPopup">
                        <div class="popup-header">
                            <h2>エリアをご選択ください。<span class="badge bg-success">任意</span></h2>
                            <span class="popup-close">✖</span>
                        </div>
                        <div class="popup-selection-content">
                            <h2>地方</h2>
                            <div class="form-group">
                                <label><input type="checkbox" data-value="北海道・東北"> 北海道・東北</label>
                                <select class="area-selection" multiple="multiple">
                                    <option value="1">都道府県1</option>
                                    <option value="2">都道府県2</option>
                                    <!-- Add more options here -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" data-value="関東・甲信越"> 関東・甲信越</label>
                                <select class="area-selection" multiple="multiple">
                                    <option value="3">都道府県3</option>
                                    <option value="4">都道府県4</option>
                                    <!-- Add more options here -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" data-value="北陸・東海"> 北陸・東海</label>
                                <select class="area-selection" multiple="multiple">
                                    <option value="5">都道府県5</option>
                                    <option value="6">都道府県6</option>
                                    <!-- Add more options here -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" data-value="近畿"> 近畿</label>
                                <select class="area-selection" multiple="multiple">
                                    <option value="7">都道府県7</option>
                                    <option value="8">都道府県8</option>
                                    <!-- Add more options here -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" data-value="中国・四国"> 中国・四国</label>
                                <select class="area-selection" multiple="multiple">
                                    <option value="9">都道府県9</option>
                                    <option value="10">都道府県10</option>
                                    <!-- Add more options here -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" data-value="九州・沖縄"> 九州・沖縄</label>
                                <select class="area-selection" multiple="multiple">
                                    <option value="11">都道府県11</option>
                                    <option value="12">都道府県12</option>
                                    <!-- Add more options here -->
                                </select>
                            </div>
                        </div>
                        <div class="popup-footer previous-footer">
                            <div>
                                <button class="open-previous-popup">戻る</button>
                            </div>
                            <div>
                                <button class="clear-data bg-warning">クリア</button>
                                <button class="open-next-popup">次へ</button>
                            </div>
                        </div>
                    </div>

                    <div class="popup" id="hospitalDetailPopup">
                        <div class="popup-header category-popup-header">
                            <h2>医療機関の条件をご選択ください。 <span class="badge bg-success">任意</span></h2>
                            <span class="popup-close">✖</span>
                        </div>
                        <div class="popup-checkbox-content category-content">
                            <label><input type="checkbox" data-key="1" data-value="名医">名医</label>
                            <label><input type="checkbox" data-key="2" data-value="特別室">特別室</label>
                            <label><input type="checkbox" data-key="3" data-value="緩和ケア">緩和ケア</label>
                            <label><input type="checkbox" data-key="4"
                                          data-value="集学的治療体制">集学的治療体制</label>
                        </div>
                        <div class="popup-header category-popup-header">
                            <h3>病院区分</h3>
                        </div>
                        <div class="popup-checkbox-content category-content">
                            <label><input type="checkbox" data-key="5" data-value="国⽴がん研究センター">国⽴がん研究センター</label>
                            <label><input type="checkbox" data-key="6" data-value="都道府県がん診療拠点病院">都道府県がん診療拠点病院　</label>
                            <label><input type="checkbox" data-key="7" data-value="地域がん診療拠点病院">地域がん診療拠点病院</label>
                            <label><input type="checkbox" data-key="8"
                                          data-value="地域がん診療病院">地域がん診療病院</label>
                            <label><input type="checkbox" data-key="9" data-value="その他">その他</label>
                        </div>
                        <div class="popup-header category-popup-header">
                            <h3>ゲノム拠点病院区分</h3>
                        </div>
                        <div class="popup-checkbox-content category-content">
                            <label><input type="checkbox" data-key="10" data-value="がんゲノム医療中核拠点病院">がんゲノム医療中核拠点病院</label>
                            <label><input type="checkbox" data-key="11" data-value="がんゲノム医療拠点病院">がんゲノム医療拠点病院</label>
                            <label><input type="checkbox" data-key="12" data-value="遺がんゲノム医療連携病院">遺がんゲノム医療連携病院</label>
                            <label><input type="checkbox" data-key="13" data-value="その他">その他</label>
                        </div>
                        <div class="popup-footer previous-footer">
                            <div>
                                <button class="open-previous-popup">戻る</button>
                            </div>
                            <div>
                                <button class="clear-data bg-warning">クリア</button>
                                <button class="search-hospital end-popup">検索</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="search-result-header">
                    <label><input type="checkbox" class="m-r-10 checkbox-print-all"> 全チェック</label>
                    <div class="search-result-header-right">
                        <span>チェックした対象を</span>
                        <button class="confirm-button">印刷</button>
                    </div>
                </div>

                <div class="hospital-card">
                    <div class="card-checkbox">
                        <label><input type="checkbox" class="checkbox-print"></label>
                    </div>
                    <div class="card-info">
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県</div>
                        <div class="hospital-info">
                            <h2>昭和大学横浜市北部病院</h2>
                            <span class="info-icon"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> (ホームページが開設されます)</span>
                            <p>神奈川県横浜市都筑区茅ヶ崎中央35-1 045-949-7000 (代表)</p>
                        </div>
                        <div class="categories">
                            <span>陽子線治療陽子線治療</span>
                            <span>光免疫療法</span>
                            <span>光線力学療法</span>
                            <span>温熱療法</span>
                        </div>
                    </div>
                    <div class="card-stats">
                        <div class="hospital-rank">
                            <div class="header1">全国順位</div>
                            <div class="header2">地方順位</div>
                            <div class="header3">全国</div>
                            <div class="header4"></div>

                            <div class="stat1 rank-icon"><div class="rank-icon rank-row-1">1</div></div>
                            <div class="stat2">5位</div>
                            <div class="stat3">5位</div>
                            <div class="stat4 stat"><div class="stat-info stat-info-extended rank-row-1">入院患者数: 3,000人</div></div>

                            <div class="stat5 rank-icon"><div class="rank-icon rank-row-2">3</div></div>
                            <div class="stat6">7位</div>
                            <div class="stat7">7位</div>
                            <div class="stat8 stat"><div class="stat-info stat-info-extended rank-row-2">新規がん患者数: 2,000人</div></div>

                            <div class="stat9 rank-icon"><div class="rank-icon rank-row-3">2</div></div>
                            <div class="stat10">15位</div>
                            <div class="stat11">15位</div>
                            <div class="stat12 stat"><div class="stat-info stat-info-extended rank-row-3">生存率係数: 80.5%</div></div>
                        </div>

                        <div class="dpc-info">
                            <p>DPC治療指数: <span>600</span></p>
                            <button class="confirm-button">この医療機関の詳細を見る</button>
                        </div>
                    </div>
                </div>
                <div class="hospital-card">
                    <div class="card-checkbox">
                        <label><input type="checkbox" class="checkbox-print"></label>
                    </div>
                    <div class="card-info">
                        <div class="tag">神奈川県神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県神奈川県</div>
                        <div class="hospital-info">
                            <h2>北里大学病院</h2>
                            <span class="info-icon"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> (ホームページが開設されます)</span>
                            <p>神奈川県相模原市南区北里1-15-1 042-778-8111 (代表) 神奈川県相模原市南区北里1-15-1 042-778-8111 (代表)</p>
                        </div>
                        <div class="categories">
                            <span>陽子線治療</span>
                            <span>光免疫療法</span>
                            <span>光線力学療法陽子線治療</span>
                            <span>温熱療法</span>
                            <span>光線力学療法</span>
                            <span>温熱療法</span>
                        </div>
                    </div>
                    <div class="card-stats">
                        <div class="hospital-rank">
                            <div class="header1">全国順位</div>
                            <div class="header2">地方順位</div>
                            <div class="header3">全国</div>
                            <div class="header4"></div>

                            <div class="stat1 rank-icon"><div class="rank-icon rank-row-1">1</div></div>
                            <div class="stat2">5位</div>
                            <div class="stat3">5位</div>
                            <div class="stat4 stat"><div class="stat-info stat-info-extended rank-row-1">入院患者数: 3,000人</div></div>

                            <div class="stat5 rank-icon"><div class="rank-icon rank-row-2">3</div></div>
                            <div class="stat6">7位</div>
                            <div class="stat7">7位</div>
                            <div class="stat8 stat"><div class="stat-info stat-info-extended rank-row-2">新規がん患者数: 2,000人</div></div>

                            <div class="stat9 rank-icon"><div class="rank-icon rank-row-3">2</div></div>
                            <div class="stat10">15位</div>
                            <div class="stat11">15位</div>
                            <div class="stat12 stat"><div class="stat-info stat-info-extended rank-row-3">生存率係数: 80.5%</div></div>
                        </div>

                        <div class="dpc-info">
                            <p>DPC治療指数: <span>600</span></p>
                            <button class="confirm-button">この医療機関の詳細を見る</button>
                        </div>
                    </div>
                </div>
                <div class="hospital-card">
                    <div class="card-checkbox">
                        <label><input type="checkbox" class="checkbox-print"></label>
                    </div>
                    <div class="card-info">
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県</div>
                        <div class="hospital-info">
                            <h2>昭和大学横浜市北部病院</h2>
                            <span class="info-icon"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> (ホームページが開設されます)</span>
                            <p>神奈川県横浜市都筑区茅ヶ崎中央35-1 045-949-7000 (代表)</p>
                        </div>
                        <div class="categories">
                            <span>陽子線治療陽子線治療</span>
                            <span>光免疫療法</span>
                            <span>光線力学療法</span>
                            <span>温熱療法</span>
                        </div>
                    </div>
                    <div class="card-stats">
                        <div class="hospital-rank">
                            <div class="header1">全国順位</div>
                            <div class="header2">地方順位</div>
                            <div class="header3">全国</div>
                            <div class="header4"></div>

                            <div class="stat1 rank-icon"><div class="rank-icon rank-row-1">1</div></div>
                            <div class="stat2">5位</div>
                            <div class="stat3">5位</div>
                            <div class="stat4 stat"><div class="stat-info stat-info-extended rank-row-1">入院患者数: 3,000人</div></div>

                            <div class="stat5 rank-icon"><div class="rank-icon rank-row-2">3</div></div>
                            <div class="stat6">7位</div>
                            <div class="stat7">7位</div>
                            <div class="stat8 stat"><div class="stat-info stat-info-extended rank-row-2">新規がん患者数: 2,000人</div></div>

                            <div class="stat9 rank-icon"><div class="rank-icon rank-row-3">2</div></div>
                            <div class="stat10">15位</div>
                            <div class="stat11">15位</div>
                            <div class="stat12 stat"><div class="stat-info stat-info-extended rank-row-3">生存率係数: 80.5%</div></div>
                        </div>

                        <div class="dpc-info">
                            <p>DPC治療指数: <span>600</span></p>
                            <button class="confirm-button">この医療機関の詳細を見る</button>
                        </div>
                    </div>
                </div>
                <div class="hospital-card">
                    <div class="card-checkbox">
                        <label><input type="checkbox" class="checkbox-print"></label>
                    </div>
                    <div class="card-info">
                        <div class="tag">神奈川県神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県神奈川県</div>
                        <div class="hospital-info">
                            <h2>北里大学病院</h2>
                            <span class="info-icon"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> (ホームページが開設されます)</span>
                            <p>神奈川県相模原市南区北里1-15-1 042-778-8111 (代表) 神奈川県相模原市南区北里1-15-1 042-778-8111 (代表)</p>
                        </div>
                        <div class="categories">
                            <span>陽子線治療</span>
                            <span>光免疫療法</span>
                            <span>光線力学療法陽子線治療</span>
                            <span>温熱療法</span>
                            <span>光線力学療法</span>
                            <span>温熱療法</span>
                        </div>
                    </div>
                    <div class="card-stats">
                        <div class="hospital-rank">
                            <div class="header1">全国順位</div>
                            <div class="header2">地方順位</div>
                            <div class="header3">全国</div>
                            <div class="header4"></div>

                            <div class="stat1 rank-icon"><div class="rank-icon rank-row-1">1</div></div>
                            <div class="stat2">5位</div>
                            <div class="stat3">5位</div>
                            <div class="stat4 stat"><div class="stat-info stat-info-extended rank-row-1">入院患者数: 3,000人</div></div>

                            <div class="stat5 rank-icon"><div class="rank-icon rank-row-2">3</div></div>
                            <div class="stat6">7位</div>
                            <div class="stat7">7位</div>
                            <div class="stat8 stat"><div class="stat-info stat-info-extended rank-row-2">新規がん患者数: 2,000人</div></div>

                            <div class="stat9 rank-icon"><div class="rank-icon rank-row-3">2</div></div>
                            <div class="stat10">15位</div>
                            <div class="stat11">15位</div>
                            <div class="stat12 stat"><div class="stat-info stat-info-extended rank-row-3">生存率係数: 80.5%</div></div>
                        </div>

                        <div class="dpc-info">
                            <p>DPC治療指数: <span>600</span></p>
                            <button class="confirm-button">この医療機関の詳細を見る</button>
                        </div>
                    </div>
                </div>
                <div class="hospital-card">
                    <div class="card-checkbox">
                        <label><input type="checkbox" class="checkbox-print"></label>
                    </div>
                    <div class="card-info">
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県神奈川県</div>
                        <div class="tag">神奈川県</div>
                        <div class="tag">神奈川県神奈川県</div>
                        <div class="hospital-info">
                            <h2>昭和大学横浜市北部病院</h2>
                            <span class="info-icon"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> (ホームページが開設されます)</span>
                            <p>神奈川県横浜市都筑区茅ヶ崎中央35-1 045-949-7000 (代表)</p>
                        </div>
                        <div class="categories">
                            <span>陽子線治療陽子線治療</span>
                            <span>光免疫療法</span>
                            <span>光線力学療法</span>
                            <span>温熱療法</span>
                        </div>
                    </div>
                    <div class="card-stats">
                        <div class="hospital-rank">
                            <div class="header1">全国順位</div>
                            <div class="header2">地方順位</div>
                            <div class="header3">全国</div>
                            <div class="header4"></div>

                            <div class="stat1 rank-icon"><div class="rank-icon rank-row-1">1</div></div>
                            <div class="stat2">5位</div>
                            <div class="stat3">5位</div>
                            <div class="stat4 stat"><div class="stat-info stat-info-extended rank-row-1">入院患者数: 3,000人</div></div>

                            <div class="stat5 rank-icon"><div class="rank-icon rank-row-2">3</div></div>
                            <div class="stat6">7位</div>
                            <div class="stat7">7位</div>
                            <div class="stat8 stat"><div class="stat-info stat-info-extended rank-row-2">新規がん患者数: 2,000人</div></div>

                            <div class="stat9 rank-icon"><div class="rank-icon rank-row-3">2</div></div>
                            <div class="stat10">15位</div>
                            <div class="stat11">15位</div>
                            <div class="stat12 stat"><div class="stat-info stat-info-extended rank-row-3">生存率係数: 80.5%</div></div>
                        </div>

                        <div class="dpc-info">
                            <p>DPC治療指数: <span>600</span></p>
                            <button class="confirm-button">この医療機関の詳細を見る</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
<?php print $pageinfo->html_foot; ?>
<script>
    $(document).ready(function () {
        let cancerTypeChecked = []
        let areaChecked = []
        let hospitalDetailChecked = []

        $('.area-selection').select2({
            placeholder: '州を選択',
            allowClear: true
        });

        function handlePopupClick(targetId, popupId, hiddenPopupId = null) {
            $('.popup').not(popupId).hide();

            let popup = $(popupId);
            let isHidden = (popup.css('display') === 'none');
            popup.fadeToggle();

            $('.filter-group .show-popup .toggle').text('+');
            let toggleIcon = $(targetId).find('.toggle');

            if (isHidden) {
                $('.popup-container').show();
                toggleIcon.text('—');
            } else {
                $('.popup-container').hide();
                toggleIcon.text('+');

                if (!hiddenPopupId) {
                    let data = {
                        idPopup: popupId
                    };

                    $(document).trigger('popupClosed', data);
                }
            }

            if (hiddenPopupId) {
                let data = {
                    idPopup: hiddenPopupId
                };

                $(document).trigger('popupClosed', data);
            }
        }

        function handleSearchHospital()
        {
            if (cancerTypeChecked.length === 0) {
                swal({
                    title: 'エラー!',
                    text: '少なくとも1種類のがんを選択する必要があります',
                    type : "error",
                    confirmButtonClass : 'btn-primary',
                    confirmButtonText : "OK",
                })
            } else {
                swal({
                    title: "Dev!",
                    text: '開発中',
                    type: "success",
                    confirmButtonClass: 'btn-success',
                    confirmButtonText: "OK"
                })
            }
        }

        $('.popup-close').on('click', function () {
            let popup = $(this).closest('.popup')
            popup.fadeOut()
            $('.popup-container').hide()
            $('.filter-group .show-popup .toggle').text('+');

            let data = {
                idPopup: '#' + popup.attr('id')
            };
            $(document).trigger('popupClosed', data);
        })

        $('#area').on('click', function () {
            handlePopupClick('#area', '#areaPopup');
        });

        $('#cancerType').on('click', function () {
            handlePopupClick('#cancerType', '#cancerPopup');
        });

        $('#hospitalDetail').on('click', function () {
            handlePopupClick('#hospitalDetail', '#hospitalDetailPopup');
        });

        $('#cancerPopup .open-next-popup').on('click', function () {
            handlePopupClick('#area', '#areaPopup', '#cancerPopup');
        });

        $('#areaPopup .open-previous-popup').on('click', function () {
            handlePopupClick('#cancerType', '#cancerPopup', '#areaPopup');
        });

        $('#areaPopup .open-next-popup').on('click', function () {
            handlePopupClick('#hospitalDetail', '#hospitalDetailPopup', '#areaPopup');
        });

        $('#hospitalDetailPopup .open-previous-popup').on('click', function () {
            handlePopupClick('#area', '#areaPopup', '#hospitalDetailPopup');
        });

        $('#cancerPopup .clear-data').on('click', function () {
            $('#cancerPopup input[type="checkbox"]').prop('checked', false);
        });

        $('#areaPopup .clear-data').on('click', function () {
            $('#areaPopup input[type="checkbox"]').prop('checked', false);
            $('.area-selection').val(null).trigger('change');
        });

        $('#hospitalDetailPopup .clear-data').on('click', function () {
            $('#hospitalDetailPopup input[type="checkbox"]').prop('checked', false);
        });

        $('#expandedSearchFilter').on('click', function () {
            $('.search-filter').toggleClass('expanded');
        });

        $('.search-hospital').on('click', function () {
            if ($(this).hasClass('end-popup')) {
                handlePopupClick('#hospitalDetail', '#hospitalDetailPopup');
            }

            handleSearchHospital()
        });

        $('.checkbox-print-all').change(function() {
            if($(this).is(':checked')) {
                $('.checkbox-print').prop('checked', true);
            } else {
                $('.checkbox-print').prop('checked', false);
            }
        });

        $(document).on('popupClosed', function (e, data) {
            let idFilter = ''
            let idPopup = data.idPopup

            if (idPopup === '#cancerPopup') {
                let span = ''
                idFilter = '#cancerType'
                cancerTypeChecked = []

                let filterContent = $(idFilter).parent().find('.filter-content')
                filterContent.html('')
                $(idPopup + ' .popup-checkbox-content input:checked').each(function () {
                    cancerTypeChecked.push($(this).data('key'))
                    span += '<span>' + $(this).data('value') + '</span>'
                });

                filterContent.append(span)
            }

            if (idPopup === '#hospitalDetailPopup') {
                let span = ''
                idFilter = '#hospitalDetail'
                hospitalDetailChecked = []

                let filterContent = $(idFilter).parent().find('.filter-content')
                filterContent.html('')
                $(idPopup + ' .popup-checkbox-content input:checked').each(function () {
                    hospitalDetailChecked.push($(this).data('key'))
                    span += '<span>' + $(this).data('value') + '</span>'
                });

                filterContent.append(span)
            }

            if (idPopup === '#areaPopup') {
                areaChecked = []
                idFilter = '#area'

                let region = []
                let area = []
                let totalRegion = $(idPopup + ' .popup-selection-content input[type=checkbox]').length
                let filterContent = $(idFilter).parent().find('.filter-content')
                filterContent.html('')

                $(idPopup + ' .popup-selection-content input:checked').each(function () {
                    region.push($(this).data('value'))
                });

                if (region.length === totalRegion) {
                    areaChecked['region'] = region = ['全国']
                } else {
                    areaChecked['region'] = region
                }

                areaChecked['area'] = []

                $('.area-selection option:selected').each(function () {
                    let mainRegion = $(this).closest('.form-group').find('input[type="checkbox"]').data('value');

                    if (JSON.stringify(areaChecked['region']) !== JSON.stringify(['全国']) && (areaChecked['region'].indexOf(mainRegion) === -1)) {
                        areaChecked['area'].push($(this).val())
                        area.push($(this).text())
                    }
                });

                region.forEach(function (value) {
                    filterContent.append('<span>' + value + '</span>')
                });

                area.forEach(function (value) {
                    filterContent.append('<span>' + value + '</span>')
                });
            }
        });
    });
</script>
</html>