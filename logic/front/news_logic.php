<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . "/../../logic/common/common_logic.php";
class news_logic{
	private $common_logic;

	public function __construct(){
		$this->common_logic = new common_logic();
	}


	public function create_news($limit, $offset = null){

          $news_res = $this->common_logic->select_logic("select * from t_news where public_flg = '0' and del_flg = '0' ORDER BY disp_date DESC LIMIT ? OFFSET ? ", array($limit, $offset));
          $news_html = '';
          $total = 0;
          $top_news = '';

          if(isset($news_res)){
            $total = count($news_res);
            foreach($news_res as $row){
              $title = mb_strimwidth($row['title'], 0, 120, "...", "UTF-8");
              if($top_news == ''){
                $top_news = <<< EOM
				<div class="mv-news__heading">
					<span class="mv-news__title">News</span>
					<time class="mv-news__time" datetime="{$row['disp_date']}">{$row['disp_date']}</time>
				</div>
				<a href="news_detail.php?n={$row['news_id']}" class="mv-news__text">{$title}</a>
EOM;
              }

              $news_html .= <<< EOM
				<li class="news__list">
					<div class="news__info">
						<time class="news__date">{$row['disp_date']}</time>
						<span class="news__tag">お知らせ</span>
					</div>
					<a href="news_detail.php?n={$row['news_id']}" class="news__text">{$title}</a>
				</li><!-- ./news__list -->
EOM;
            }
          }else{
            $news_html = <<< EOM
				<li class="news__list">
					申し訳ございません、該当する情報がありません
				</li><!-- ./news__list -->

EOM;

          }

          return array(
                        'news_html' => $news_html,
                        'total' => $total,
                        'top_news' => $top_news
                      );
        }

        public function check_news($get){
          $news_res = $this->common_logic->select_logic("select * from t_news where news_id = ? and public_flg = '0' and del_flg = '0'", array($get['n']));
          $num_f = (empty($news_res)) ? false : true;
          return $num_f;
        }

        public function create_news_detail($get){
          $news_res = $this->common_logic->select_logic("select * from t_news where news_id = ? and public_flg = '0' and del_flg = '0'", array($get['n']));
          return $news_res[0];
        }
}

