<?php
session_start();
require_once __DIR__ . "/../../logic/common/common_logic.php";

ini_set('display_errors', "On"); 

class product_logic{
	private $common_logic;

	public function __construct(){
		$this->common_logic = new common_logic();
	}


	public function create_product_list($now, $add = array(), $index = null){
          //現在のページ（GETパラメータ[now]にて取得）
          //※現在の表示ページ番号 != $now_pageであるので注意
          //※現在の表示ページ番号 == $now_page + 1 である
          $now_page = 0;
          if ($now != null && $now != '') {
            $now_page = $now;
          }

          $where_p_base = array();

          $where = "";
/*
          if ($add == null) {
            $add = array();
            $add['s_tag'] = null;
          }

          if ($add['s_tag'] != '' && $add['s_tag'] != null) {
            $where = 'AND user_name LIKE "%' . $add['tag'] . '%"';
          }
*/
          $cate = '';

          if(isset($add) && ($add != null)){
            $where .= ' and (';
            $i_n = 0;
            foreach($add as $val){
              if($i_n > 0){
                $where .= ' OR';
              }
              $where .= ' etc1 LIKE "%' . $val . '%" OR etc2 LIKE "%' . $val . '%" ';
              $i_n++;
            }
            $where .= ') ';
          }
          $items_res = array();

          //総件数
          $count_data = $this->common_logic->select_logic("select count(items_id) AS `cnt` from t_items where del_flg = 0 and public_flg = 0 " . $where . " order by disp_date desc ", $where_p_base);
          $all_cnt = count($items_res);

          //総ページ数
          $disp_num = 12;
          $page_num = ceil($all_cnt / $disp_num);

          $limit = $disp_num;
          $offset = $now_page * $disp_num;
          array_push($where_p_base, $limit, $offset);

          //データ取得
          //$items_res = $this->common_logic->select_logic("select * from t_items where del_flg = 0 and public_flg = 0 and disp_date <= CURDATE() " . $where . " order by disp_date desc LIMIT ? OFFSET ? ", $where_p_base);
          if($where != ''){
            $items_res = $this->common_logic->select_logic("select * from t_items where del_flg = 0 and public_flg = 0 " . $where . " order by disp_date desc LIMIT ? OFFSET ? ", $where_p_base);

          }elseif($index != null || $index != "") {
            if(isset($index['cate'])){
              switch($index['cate']){
                case '01':
                  $cate = '電子部品';
                  break;
                case '02':
                  $cate = '半導体';
                  break;
                case '03':
                  $cate = 'ディスプレイ/ガラス';
                  break;
                case '04':
                  $cate = '発振器';
                  break;

              }

              $items_res = $this->common_logic->select_logic("select * from t_items where del_flg = 0 and public_flg = 0 and category = ? ", array($cate));
            }
          }else{
            $items_res = $this->common_logic->select_logic("select * from t_items where del_flg = 0 and public_flg = 0 order by disp_date desc LIMIT ? OFFSET ? ", $where_p_base);
          }




          //$items_res = $this->common_logic->select_logic("select * from t_items where public_flg = '0' and del_flg = '0'", array());
          $items_html = '';

          foreach($items_res as $row){
            $img_path = ($row['img'] == '') ? '../img/noimage.jpg' : '../upload_files/items/'.$row["img"];
            $tags_all = explode(",", $row['etc1']);
            $tags_all2 = explode(",", $row['etc2']);
            foreach($tags_all2 as $r){
              array_push($tags_all,$r);
            }
            $arrayUnique = array_unique($tags_all);
            $tag_src = '';
            foreach($arrayUnique as $val){
              if(($val != '') && ($val != NULL)){
                $tag_src .= '<li><span>'.$val.'</span></li>';
              }
            }
            $items_link = 'product_detail.php?id='.$row["items_id"];
            $items_html .= <<< EOM
						<li>
							<a href="{$items_link}"><img src="{$img_path}" alt="{$row['title']}" class="imgt"></a>
							<ul class="tag_w">{$tag_src}</ul>
							<p class="title_t"><a href="{$items_link}">{$row['title']}</a></p>
							<p><a href="{$items_link}" class="detail_btn">詳細を見る</a></p>
						</li>
EOM;

          }
		$pager = '';
		if ($all_cnt > $disp_num) {

			$url_add = '';
			if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
				$url_add_ar = explode('?', $_SERVER['REQUEST_URI']);
				$de = urldecode($url_add_ar[1]);
				$gp = explode('&', $de);
				foreach ($gp as $k => $as) {
					if (strpos($as, 'now') !== false) array_splice($gp, $k, 1);
				}
				array_values($gp);
				$url_add = implode('&', $gp);
			}

			$pager_start = 0;
			if ($now_page > 2) {
				$pager_start = $now_page - 2;
				if ($now_page + 2 >= $page_num) {
					$pager_start = $now_page - 4  + ($page_num - $now_page);
				}
			}
			if ($pager_start < 0) {
				$pager_start = 0;
			}


			//戻る処理
			if ($now_page != 0) {
				$prev_num = $now_page - 1;
				$p = '?now=' . $prev_num;
				if ($url_add != '') $p .= '&' . $url_add;

				$pager .= '
					<li><a href="' . $p . '"><i class="fas fa-chevron-left "></i></a></li>
						';
			}

			$max_page = $all_cnt / $disp_num;
			define('DISPLAY_PAGER_CNT', 5);
			$cnt = 0;
			for ($i = $pager_start; $i < $page_num; $i++) {
				$cnt++;

				if ($cnt > DISPLAY_PAGER_CNT) {
					break;
				}

				$disp_i = $i + 1;

				if ($disp_i == $max_page + 1) {
					break;
				}

				//現在のページクラス付与用
				$active = '';
				$url = '?now=' . $i;
				if ($url_add != '') $url .= '&' . $url_add;
				if ($i == $now_page) {
					$url = "javascript:void(0);";
					$active = 'active';
				}

				//数字処理
				$pager .= '
					<li><a ' . $active . '" href="' . $url . '">' . $disp_i . '</a></li>
					';
			}

			//次へ処理
			if ($page_num != $now_page + 1) {
				$next_page = $now_page + 1;
				$p = '?now=' . $next_page;
				if ($url_add != '') $p .= '&' . $url_add;
				$pager .= '
					<li><a href="' . $p . '"><i class="fas fa-chevron-right "></i></a></li>
						';
			}
		}

		return array(
			'html' => $items_html,
			'pager' => $pager,
			'cate' => $cate,
		);
        }

        public function create_product_detail($cate){
          
          $items_res = $this->common_logic->select_logic("select * from t_items where category = ? and public_flg = 0 and del_flg = 0", array($cate));

          $items_html = '';

          foreach($items_res as $row){

            $d_res = $this->common_logic->select_logic("select * from t_items_detail where items_id = ? and public_flg = 0 and del_flg = 0", array($row['items_id']));
            $detail_btn = '';
            if(isset($d_res[0])){
               $detail_btn = <<<EOM
			<div class="news__btn">
				<a href="detail.php?d_id={$d_res[0]['items_detail_id']}" class="btn btn--news">詳細を見る</a>
			</div>
EOM;
            }

            $movie_html = '';
            if($row['movie_url'] != ''){
              $movie_html = <<<EOM
				<div class="movie2">
					<div class="movie-wrap2">
						<p>{$row['movie_title']}</p>
						<video src="{$row['movie_url']}" controls="" buffered="" height="100%" width="100%"></video>
					</div>
				</div><!-- ./movie -->

EOM;
            }

            $img_html = '';
            if($row['img'] != ''){
              $img_html = <<< EOM
					<div class="parts2-main__bg">
						<div class="parts2-main__img">
							<img src="../upload_files/items/{$row['img']}" alt="">
						</div>
					</div>
EOM;
            }
            $lead = htmlspecialchars_decode($row['lead']);
            $detail = htmlspecialchars_decode($row['detail']);
            $items_html .= <<< EOM
				<div class="parts2-sub">
					<a id="{$row['items_id']}" class="anchor"></a>
					<div class="parts2-sub__head">
						<p class="parts2-sub-head__lead">{$row['title']}</p>
					</div><!-- ./parts2-sub__head -->
					{$img_html}
					<div class="text-wrapper">
						<p class="parts2-main__text">{$lead}</p>
						{$detail}

					</div>
				</div>
				{$movie_html}
				{$detail_btn}

EOM;

          }
        }

	//製品詳細
        public function create_product_detail_txt($get){
          
          //$row = $this->common_logic->select_logic("select * from t_items_detail where items_detail_id = ?", array($get['d_id']))[0];

          //$items = $this->common_logic->select_logic("select * from t_items where items_id = ?", array($row['items_id']))[0];
          $items = $this->common_logic->select_logic("select * from t_items where items_id = ?", array($get['id']))[0];

          $items_html = '';

            $movie_html = '';
            if($items['movie_url'] != ''){
              $movie_html = <<<EOM
				<div class="movie2">
					<div class="movie-wrap2">
						<p>{$items['movie_title']}</p>
						<video src="{$items['movie_url']}" controls="" buffered="" height="100%" width="100%"></video>
					</div>
				</div><!-- ./movie -->

EOM;
            }

            $img_html = '';
            if($items['img'] != ''){
              $img_html = <<< EOM
					<div class="parts2-main__bg">
						<div class="parts2-main__img">
							<img src="../upload_files/items/{$items['img']}" alt="">
						</div>
					</div>
EOM;
            }
//            $lead = htmlspecialchars_decode($items['lead']);
//            $detail = htmlspecialchars_decode($items['detail']);
//            $lead = html_entity_decode($items['lead']);
//           $detail = html_entity_decode($items['detail']);

            $lead = html_entity_decode($items['lead'], ENT_QUOTES, 'UTF-8');//htmlentities(strip_tags($items['lead']), ENT_QUOTES, 'UTF-8');
            $detail = $items['detail'];

            $items_html .= <<< EOM
				<div class="parts2-sub">
					<div class="parts2-sub__head">
						<p class="parts2-sub-head__lead">{$items['title']}</p>
					</div><!-- ./parts2-sub__head -->
					{$img_html}
					<div class="text-wrapper">
						<p class="parts2-main__text">{$lead}</p>
						{$detail}

					</div>
				</div>
				{$movie_html}

EOM;

	$img = '';
	switch($items['category']){
		case '電子部品':
			$img = 'parts1-bg.png';
			break;
		case '半導体':
			$img = 'parts3-bg.png';
			break;
		case 'ディスプレイ/ガラス':
			$img = 'parts2-bg.png';
			break;
		case '発振器':
			$img = 'parts4-bg.png';
			break;

	}

          return array(
            'html' => $items_html,
            'title' => $items['title'],
            'img' => $img,
          );
        }




        public function create_product_ber(){


          $items_res = $this->common_logic->select_logic("select * from t_items where public_flg = 0 and del_flg = 0", array());

          $ber_html01 = $ber_html02 = $ber_html03 = $ber_html04 = '';

          foreach($items_res as $row){
            if($row['category'] == '電子部品'){
              $ber_html01 .= $this->create_ber_link($row);
            }elseif($row['category'] == '半導体'){
              $ber_html02 .= $this->create_ber_link($row);
            }elseif($row['category'] == 'ディスプレイ/ガラス'){
              $ber_html03 .= $this->create_ber_link($row);
            }elseif($row['category'] == '発振器'){
              $ber_html04 .= $this->create_ber_link($row);
            }
          }

          $ber_01_w = ($ber_html01 == '') ? '' : '
    <li class="parts-menu__list">
      <a href="product.php?cate=01" class="parts-menu__name">電子部品</a>
      <ul class="parts-detail__lists">'.$ber_html01.'</ul>
    </li>';

          $ber_02_w = ($ber_html02 == '') ? '' : '
    <li class="parts-menu__list">
      <a href="product.php?cate=02" class="parts-menu__name">半導体</a>
      <ul class="parts-detail__lists">'.$ber_html02.'</ul>
    </li>';

          $ber_03_w = ($ber_html03 == '') ? '' : '
    <li class="parts-menu__list">
      <a href="product.php?cate=03" class="parts-menu__name">ディスプレイ/ガラス</a>
      <ul class="parts-detail__lists">'.$ber_html03.'</ul>
    </li>';

          $ber_04_w = ($ber_html04 == '') ? '' : '
    <li class="parts-menu__list">
      <a href="product.php?cate=04" class="parts-menu__name">発振器</a>
      <ul class="parts-detail__lists">'.$ber_html04.'</ul>
    </li>';

          $ber_html = <<< EOM
<div class="parts-menu u-desktop">
  <ul class="parts-menu__lists">
    {$ber_01_w}
    {$ber_02_w}
    {$ber_03_w}
    {$ber_04_w}
  </ul>
</div><!-- /.parts-menu -->

EOM;
          return $ber_html;

        }

        private function create_ber_link($row){
          $html = '
        <li class="parts-detail__list">
          <a href="../products-list/product_detail.php?id='.$row['items_id'].'" class="parts-detail__link">'.$row['title'].'</a>
        </li>
';
          return $html;
        }

	//検索タグ
        public function create_search_tag(){
          $html = '';
          //アプリケーションカテゴリ
          $items_res = $this->common_logic->select_logic("select DISTINCT(etc1) from t_items where public_flg = '0' and del_flg = '0' and etc1 IS NOT NULL", array());
          $idx = 1;
          $tags = array();
          foreach($items_res as $row){

            $items = explode(",", $row['etc1']);
            foreach($items as $val){
              $tags[] = $val;
            }
          }

          if(isset($tags)){
             $html .= '<p class="tag_ttl">アプリケーションカテゴリ</p><ul class="search_w">';
             $tags_all_arr = array_unique($tags);
             foreach($tags_all_arr as $tag_name){
               if($tag_name != '' && $tag_name != null){
                 $html .= '<li><label for="ch'.$idx.'" class="chk_label"><input type="checkbox" id="ch'.$idx.'" name="s_tag[]" value="'.$tag_name.'">'.$tag_name.'</label></li>';
                 $idx++;
               }
             }
             $html .= '</ul>';
          }


          //加工対象カテゴリ
          $items_res = $this->common_logic->select_logic("select DISTINCT(etc2) from t_items where public_flg = '0' and del_flg = '0' and etc2 IS NOT NULL", array());
          $tags2 = array();
          foreach($items_res as $row){

            $items = explode(",", $row['etc2']);
            foreach($items as $val){
              $tags2[] = $val;
            }
          }

          if(isset($tags2)){
             $html .= '<p class="tag_ttl">加工対象カテゴリ</p><ul class="search_w">';
             $tags_all_arr2 = array_unique($tags2);
             foreach($tags_all_arr2 as $tag_name){
               if($tag_name != '' && $tag_name != null){
                 $html .= '<li><label for="ch'.$idx.'" class="chk_label"><input type="checkbox" id="ch'.$idx.'" name="s_tag[]" value="'.$tag_name.'">'.$tag_name.'</label></li>';
                 $idx++;
               }
             }
             $html .= '</ul>';
          }


          return $html;

        }

}

