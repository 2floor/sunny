<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . "/../../required/page_init.php";
require_once __DIR__ . "/../../controller/front/f_hospital_ct.php";

$page_init = new page_init();
$pageinfo = $page_init->get_info();

$id = $_GET['id'] ?? null;
$cancerId = $_GET['cancerId'] ?? null;

$f_hospital_ct = new f_hospital_ct();
$initData = $f_hospital_ct->getDetailById($id, $cancerId);
$cancerName = $initData['cancerName'] ?? '';
$avgData = $initData['avgData'] ?? [];
$infoHospital = $initData['infoHospital'] ?? [];
$infoTreatment = $initData['infoTreatment'] ?? [];
$stages = $initData['stages'] ?? [];
$dpcs = $initData['dpcs'] ?? [];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php print $pageinfo->html_head; ?>
    <link rel="stylesheet" href="./../../assets/css/detail_hospital.css">
</head>

<body>
<?php print $pageinfo->header; ?>

<main>
    <div class="container">
        <div class="main-detail">
            <div class="title">
                <h3 class="text-center"><?php echo $cancerName; ?></h3>
                <h1 class="text-center"><?php echo $infoHospital['name'] ?? ''; ?></h1>
            </div>


            <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="true">サマリー</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">医療機関情報</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="treatment-tab" data-toggle="tab" href="#treatment" role="tab" aria-controls="treatment" aria-selected="false">提供する治療情報</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="results-tab" data-toggle="tab" href="#results" role="tab" aria-controls="results" aria-selected="false">治療実績</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="tabContent">
                <div class="tab-pane fade active in" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                    <div class="summary-content-tab">
                        <div class="treatment-results">
                            <h3>治療実績 (直近3年平均)</h3>
                            <?php include 'component/summary-content-table.php'; ?>
                        </div>

                        <div class="overall-rating text-left">
                            <label for="overall"><h3>総評</h3></label>
                            <input type="text" id="overall" name="overall" class="form-control w-50 mx-auto" disabled>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                    <div class="info-content-tab">
                        <?php include 'component/info-content-table.php';?>
                    </div>
                </div>
                <div class="tab-pane fade" id="treatment" role="tabpanel" aria-labelledby="treatment-tab">
                    <div class="treatment-content-tab">
                        <?php include 'component/treatment-content-tab.php'; ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="results-tab">
                    <div class="result-content-tab">
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    <h4 class="panel-title">
                                        年間入院患者数
                                        <span class="glyphicon glyphicon-chevron-down arrow"></span>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table num-new-tb overflow-auto">
                                                <thead>
                                                <tr class="border-top border-bottom">
                                                    <th>年度</th>
                                                    <th>統計價</th>
                                                    <th>都道府</th>
                                                    <th>地方</th>
                                                    <th>全国</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $html = '';
                                                    for ($i = 0; $i < 3; $i++) {
                                                        $criteria = ($i == 0) ? '令和3年度' : (($i == 1) ? '令和2年度' : '令和元年度');

                                                        if (in_array($dpcs[$i]['rank_pref_dpc'] ?? null, [1, 2, 3])) {
                                                            $prefRank = '<img src="../../img/icons/rank' . $dpcs[$i]['rank_pref_dpc'] . '.png" alt="rank-img">';
                                                        } else {
                                                            $prefRank = ($dpcs[$i]['rank_pref_dpc']) ? $dpcs[$i]['rank_pref_dpc'] . '位' : '-';
                                                        }

                                                        if (in_array($dpcs[$i]['rank_area_dpc'] ?? null, [1, 2, 3])) {
                                                            $localRank = '<img src="../../img/icons/rank' . $dpcs[$i]['rank_area_dpc'] . '.png" alt="rank-img">';
                                                        } else {
                                                            $localRank = ($dpcs[$i]['rank_area_dpc']) ? $dpcs[$i]['rank_area_dpc'] . '位' : '-';
                                                        }

                                                        if (in_array($dpcs[$i]['rank_nation_dpc'] ?? null, [1, 2, 3])) {
                                                            $totalRank = '<img src="../../img/icons/rank' . $dpcs[$i]['rank_nation_dpc'] . '.png" alt="rank-img">';
                                                        } else {
                                                            $totalRank = ($dpcs[$i]['rank_nation_dpc']) ? $dpcs[$i]['rank_nation_dpc'] . '位' : '-';
                                                        }

                                                        $tr = '<tr class="border-top border-bottom">';
                                                        $tr .= '<td class="criteria">'.$criteria.'</td>';
                                                        $tr .= '<td>' . ($dpcs[$i]['n_dpc'] ? $dpcs[$i]['n_dpc'] . '人' : '-') . '</td>';
                                                        $tr .= '<td>'.$prefRank.'</td>';
                                                        $tr .= '<td>'.$localRank.'</td>';
                                                        $tr .= '<td>'.$totalRank.'</td>';
                                                        $tr .= '<tr>';

                                                        $html = $tr . $html;
                                                    }

                                                    echo $html;
                                                ?>
                                                <tr class="border-top border-bottom">
                                                    <td class="criteria">直近3年平均</td>
                                                    <td><?php echo ($avgData['avgDpc'] ? $avgData['avgDpc'] . '人' : '-') ?></td>
                                                    <td>
                                                        <?php
                                                        if (in_array($avgData['avgPrefDpcRank'] ?? null, [1, 2, 3])) {
                                                            echo '<img src="../../img/icons/rank' . $avgData['avgPrefDpcRank'] . '.png" alt="rank-img">';
                                                        } else {
                                                            echo ($avgData['avgPrefDpcRank']) ? $avgData['avgPrefDpcRank'] . '位' : '-';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (in_array($avgData['avgAreaDpcRank'] ?? null, [1, 2, 3])) {
                                                            echo '<img src="../../img/icons/rank' . $avgData['avgAreaDpcRank'] . '.png" alt="rank-img">';
                                                        } else {
                                                            echo ($avgData['avgAreaDpcRank']) ? $avgData['avgAreaDpcRank'] . '位' : '-';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (in_array($avgData['avgGlobalDpcRank'] ?? null, [1, 2, 3])) {
                                                            echo '<img src="../../img/icons/rank' . $avgData['avgGlobalDpcRank'] . '.png" alt="rank-img">';
                                                        } else {
                                                            echo ($avgData['avgGlobalDpcRank']) ? $avgData['avgGlobalDpcRank'] . '位' : '-';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    <h4 class="panel-title">
                                        年間新規入院患者数
                                        <span class="glyphicon glyphicon-chevron-down arrow"></span>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table num-in-year-tb">
                                                <thead>
                                                <tr class="border-top border-bottom">
                                                    <th class="table-title">年度</th>
                                                    <th class="table-title">統計値</th>
                                                    <th class="table-title">都道府県</th>
                                                    <th class="table-title">地方</th>
                                                    <th class="table-title">全国</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $html = '';
                                                for ($i = 0; $i < 3; $i++) {
                                                    if (in_array($stages[$i]['pref_num_rank'] ?? null, [1, 2, 3])) {
                                                        $prefRank = '<img src="../../img/icons/rank' . $stages[$i]['pref_num_rank'] . '.png" alt="rank-img">';
                                                    } else {
                                                        $prefRank = ($stages[$i]['pref_num_rank']) ? $stages[$i]['pref_num_rank'] . '位' : '-';
                                                    }

                                                    if (in_array($stages[$i]['local_num_rank'] ?? null, [1, 2, 3])) {
                                                        $localRank = '<img src="../../img/icons/rank' . $stages[$i]['local_num_rank'] . '.png" alt="rank-img">';
                                                    } else {
                                                        $localRank = ($stages[$i]['local_num_rank']) ? $stages[$i]['local_num_rank'] . '位' : '-';
                                                    }

                                                    if (in_array($stages[$i]['total_num_rank'] ?? null, [1, 2, 3])) {
                                                        $totalRank = '<img src="../../img/icons/rank' . $stages[$i]['total_num_rank'] . '.png" alt="rank-img">';
                                                    } else {
                                                        $totalRank = ($stages[$i]['total_num_rank']) ? $stages[$i]['total_num_rank'] . '位' : '-';
                                                    }

                                                    $tr = '<tr class="border-top border-bottom">';
                                                    $tr .= '<td class="criteria">'.($stages[$i]['year'] ?? '-').'</td>';
                                                    $tr .= '<td>' . ($stages[$i]['total_num_new'] ? $stages[$i]['total_num_new'] . '人' : '-') . '</td>';
                                                    $tr .= '<td>'.$prefRank.'</td>';
                                                    $tr .= '<td>'.$localRank.'</td>';
                                                    $tr .= '<td>'.$totalRank.'</td>';
                                                    $tr .= '<tr>';

                                                    $html = $tr . $html;
                                                }

                                                echo $html;
                                                ?>
                                                <tr class="border-top border-bottom">
                                                    <td class="criteria">直近3年平均</td>
                                                    <td><?php echo ($avgData['avgNewNum'] ? $avgData['avgNewNum'] . '人' : '-') ?></td>
                                                    <td>
                                                        <?php
                                                        if (in_array($avgData['avgPrefNewNumRank'] ?? null, [1, 2, 3])) {
                                                            echo '<img src="../../img/icons/rank' . $avgData['avgPrefNewNumRank'] . '.png" alt="rank-img">';
                                                        } else {
                                                            echo ($avgData['avgPrefNewNumRank']) ? $avgData['avgPrefNewNumRank'] . '位' : '-';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (in_array($avgData['avgLocalNewNumRank'] ?? null, [1, 2, 3])) {
                                                            echo '<img src="../../img/icons/rank' . $avgData['avgLocalNewNumRank'] . '.png" alt="rank-img">';
                                                        } else {
                                                            echo ($avgData['avgLocalNewNumRank']) ? $avgData['avgLocalNewNumRank'] . '位' : '-';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (in_array($avgData['avgGlobalNewNumRank'] ?? null, [1, 2, 3])) {
                                                            echo '<img src="../../img/icons/rank' . $avgData['avgGlobalNewNumRank'] . '.png" alt="rank-img">';
                                                        } else {
                                                            echo ($avgData['avgGlobalNewNumRank']) ? $avgData['avgGlobalNewNumRank'] . '位' : '-';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div><h3><b>ステージ別</b></h3></div>
                                        <div class="table-responsive">
                                            <table class="table num-in-year-detail-tb">
                                                <thead>
                                                <tr class="border-top border-bottom">
                                                    <th class="table-title">年度</th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title">ステージI</th>
                                                    <th class="table-title">ステージII</th>
                                                    <th class="table-title">ステージIII</th>
                                                    <th class="table-title">ステージIV</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="border-top border-bottom">
                                                    <td rowspan="4">2019</td>
                                                    <td>産患者数</td>
                                                    <td>85人</td>
                                                    <td>32人</td>
                                                    <td>22人</td>
                                                    <td>2人</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>都道府県</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>地方</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>全国</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td rowspan="4">2020</td>
                                                    <td>産患者数</td>
                                                    <td>76人</td>
                                                    <td>41人</td>
                                                    <td>21人</td>
                                                    <td>5人</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>都道府県</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>地方</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>全国</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td rowspan="4">2021</td>
                                                    <td>産患者数</td>
                                                    <td>72人</td>
                                                    <td>22人</td>
                                                    <td>13人</td>
                                                    <td>5人</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>都道府県</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>地方</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>全国</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td rowspan="4">直近3年平均</td>
                                                    <td>産患者数</td>
                                                    <td>78.0人</td>
                                                    <td>32.0人</td>
                                                    <td>19.0人</td>
                                                    <td>4.0人</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>都道府県</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>地方</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>全国</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                    <h4 class="panel-title">
                                        5年後生在率・生存幸係数
                                        <span class="glyphicon glyphicon-chevron-down arrow"></span>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table num-in-year-tb">
                                                <thead>
                                                <tr class="border-top border-bottom">
                                                    <th class="table-title">年度</th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title">集計対象者数</th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title">生在率係数</th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title"></th>
                                                </tr>
                                                </thead>
                                                <thead>
                                                <tr class="border-top border-bottom">
                                                    <th class="table-title"></th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title">都道府県</th>
                                                    <th class="table-title">地方</th>
                                                    <th class="table-title">全国</th>
                                                    <th class="table-title"></th>
                                                    <th class="table-title">都道府県</th>
                                                    <th class="table-title">地方</th>
                                                    <th class="table-title">全国</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="border-top border-bottom">
                                                    <td>2012-2013</td>
                                                    <td></td>
                                                    <td>200人</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td>127.97</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">2位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>2013-2014</td>
                                                    <td></td>
                                                    <td>200人</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td>127.97</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">2位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>2014-2015</td>
                                                    <td></td>
                                                    <td>200人</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td>127.97</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">2位</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>直近3年平均</td>
                                                    <td></td>
                                                    <td>200人</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td>127.97</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">2位</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div><h3><b>ステージ別</b></h3></div>
                                        <div class="table-responsive">
                                            <table class="table num-in-year-detail-tb">
                                                <thead>
                                                    <tr class="border-top border-bottom">
                                                        <th class="table-title">年度</th>
                                                        <th class="table-title"></th>
                                                        <th class="table-title">集計対象者数</th>
                                                        <th class="table-title"></th>
                                                        <th class="table-title"></th>
                                                        <th class="table-title"></th>
                                                        <th class="table-title">生在率係数</th>
                                                        <th class="table-title"></th>
                                                        <th class="table-title"></th>
                                                        <th class="table-title"></th>
                                                    </tr>
                                                </thead>
                                                <thead>
                                                    <tr class="border-top border-bottom">
                                                        <th class="table-title"></th>
                                                        <th class="table-title"></th>
                                                        <th class="table-title">ステージI</th>
                                                        <th class="table-title">ステージII</th>
                                                        <th class="table-title">ステージIII</th>
                                                        <th class="table-title">ステージIV</th>
                                                        <th class="table-title">ステージI</th>
                                                        <th class="table-title">ステージII</th>
                                                        <th class="table-title">ステージIII</th>
                                                        <th class="table-title">ステージIV</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="border-top border-bottom">
                                                    <td rowspan="5">2012-2013</td>
                                                    <td>参考:全国平均</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>54.40%</td>
                                                    <td>39.4%</td>
                                                    <td>13.5%</td>
                                                    <td>3.90%</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>実績価</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>67.00%</td>
                                                    <td>54.10%</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>都道府県順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>地方順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>全国順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td rowspan="5">2013-2014</td>
                                                    <td>参考:全国平均</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>54.40%</td>
                                                    <td>39.4%</td>
                                                    <td>13.5%</td>
                                                    <td>3.90%</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>実績価</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>67.00%</td>
                                                    <td>54.10%</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>都道府県順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>地方順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>全国順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td rowspan="5">2014-2015</td>
                                                    <td>参考:全国平均</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>54.40%</td>
                                                    <td>39.4%</td>
                                                    <td>13.5%</td>
                                                    <td>3.90%</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>実績価</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>97人</td>
                                                    <td>67.00%</td>
                                                    <td>54.10%</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>都道府県順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>地方順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                <tr class="border-top border-bottom">
                                                    <td>全国順位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">3位</td>
                                                    <td class="icon icon-person">4位</td>
                                                    <td class="icon icon-person">7位</td>
                                                    <td class="icon icon-person">2位</td>
                                                    <td class="icon icon-person">-</td>
                                                    <td class="icon icon-person">-</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    $(document).ready(function() {
        $('#collapseOne').prev('.panel-heading').find('.arrow').toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        $('#collapseOne').prev('.panel-heading').addClass('active');

        $('#accordion').on('show.bs.collapse', function(e) {
            $(e.target).prev('.panel-heading').addClass('active');
            $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        });

        $('#accordion').on('hide.bs.collapse', function(e) {
            $(e.target).prev('.panel-heading').removeClass('active');
            $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        });
    });
</script>
</html>
