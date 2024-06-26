<?php
/**
 *
 * 各階層ごとのページリンク系クラス
 * 下記をページ上部で 必 ず 宣言（下記を記載）すること
 *
 * PROJECT_DIRの設定を忘れずに
 * ローカルやテストサーバ、お客様のサーバーでテストディレクトリを切るとき
 * PROJECT_DIRにそのディレクトリ名を記載
 * ※設定していない場合、pathやmetaがうまく表示されない可能性有
 * ※サブとメインを使用して、そこがrootになる際は除く
 *
 * 各種共通ファイルはrequired/data内に格納
 * 共通のパスの頭には「[path]」を記載することで、多階層にも対応
 * 下記をコピーしての使用OK　※その際には「[パスを記載]」を変更すること
 *
	require_once  __DIR__ . "[パスを記載]/required/page_init.php";
	$page_init = new page_init();
 *
 *
 */

require_once __DIR__ . "/./required/data/meta.php";
require_once __DIR__ . "/./required/html_conf.php";
class page_init{

	const PROJECT_DIR = "nippo";//テストディレクトリ（ドメインから階層が下がるとき）　ないときはnull

	//メディア使用テーブルカラム名
	const MEDIA_USE_TABLE = array(
// 			"t_article" => array(
// 					"thumbnail",
// 					"detail",
// 			),
	);


	private $meta;
	private $html_conf;

	private $page_name;
	private $path;
	private $index_flg;
	private $metatag;
	private $title;
	private $keyword;
	private $description;
	private $html_head;
	private $header;
	private $footer;
	private $other;



	public function __construct() {
		$this->meta = new meta();
		$this->html_conf = new html_conf();


		$this->page_name;
		$this->path;
		$this->index_flg;
		$this->metatag;
		$this->title;
		$this->keyword;
		$this->description;
		$this->html_head;
		$this->header;
		$this->footer;
		$this->other;


		$this->create_page_name();
		$this->create_path();
		$this->create_meta();
		$this->create_html_base();

	}


	/**
	 * ページ情報取得
	 * @return stdClass
	 */
	public function get_info(){
		$return = new stdClass();
		$return->page_name = $this->page_name;
		$return->path = $this->path;
		$return->index_flg = $this->index_flg;
		$return->metatag = $this->metatag;
		$return->title = $this->title;
		$return->keyword = $this->keyword;
		$return->description = $this->description;
		$return->html_head = $this->html_head;
		$return->header = $this->header;
		$return->footer = $this->footer;
		$return->other = $this->other;
		return $return;
	}



	private function create_page_name (){

		$http = ($_SERVER['HTTPS'] != null)? "https://": "http://";
		$host = $_SERVER['HTTP_HOST'];

		$now_url = $_SERVER['REQUEST_URI'];

		$this->page_name = str_replace(array($http,$host,self::PROJECT_DIR, "//"), "", $now_url);
	}


	/**
	 * パス生成
	 */
	private function create_path(){
		//初期設定

		$domain = $_SERVER['SERVER_NAME'];
		$nowDir = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$nowDirAr = explode("/", $nowDir);

		$path_base = "./";
		$DirCounter = 0;
		if(strpos($domain, "localhost") !== false || strpos($domain, "2floor.space") !== false) {
			//ローカル、2ｆテスト環境時
			$DirCounter -= 1;
		}

		foreach ($nowDirAr as $ND) {
			if($ND == '') continue;
			if(strpos($ND, '.php') !== false)continue;
			if(strpos($ND, '?') !== false)continue;


			if($domain != $ND ){
				++$DirCounter;
			}
		}
		$path_base .= str_repeat("../", $DirCounter);
		$this->path = $path_base;
	}



	/**
	 * メタファイル生成
	 * meta.phpを編集
	 */
	private function create_meta(){

		$meta_data = $this->meta->get_meta($this->page_name);

		$this->metatag = $meta_data['metatag'];
		$this->title = $meta_data['title'];
		$this->keyword = $meta_data['keyword'];
		$this->description = $meta_data['description'];
		$this->index_flg = $meta_data['index_flg'];

	}


	/**
	 * 各種html生成
	 * dataディレクトリ内を編集
	 */
	private function create_html_base(){
		$info = $this->get_info();
		$html_base = $this->html_conf->create_html_base($info);

		$this->html_head = $html_base['html_head'];
		$this->header = $html_base['header'];
		$this->footer = $html_base['footer'];
		$this->other = $html_base['other'];
	}

}

