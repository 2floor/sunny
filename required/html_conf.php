<?php
/**
 * headタグ生成
 */
class html_conf{
	private $info_data;
	private $data_path;
	private $data;
	private $other;
	private $request;
	public function __construct(){
		$this->info_data;
		$this->data_path = __DIR__ . "/../required/data/";
		$this->data = new stdClass();
		$this->other = new stdClass();
		$http = ($_SERVER['HTTPS'] != null)? "https://": "http://";
		$this->request = $http . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	}


	/**
	 * ここで各ファイル名を設定
	 * 各ファイルはrequired/data内に配置すること
	 *
	 * $info->page_name;
	 * $info->path;
	 * $info->index_flg;
	 * $info->metatag;
	 * $info->title;
	 * $info->keyword;
	 * $info->description;
	 * を使用可能
	 */

	private function get_conf(){


		$analytics = "analytics.php"; //google analyticsタグファイル名
		$head_file_name = "html_head.php"; //ヘッドファイル名
		$header_file_name = "header2.php"; //ヘッダーファイル名
		$foot_file_name = "html_foot.php"; //フッターファイル名

		//そのファイル処理　コンバートなどは別途かけること
		//$this->otherの中にHTMLを格納すること
		// ex)
		// $this->other->share = $this->convert_html(file_get_contents($this->data_path . "share.php"));


		$this->data->head = $head_file_name;
		$this->data->analytics = $analytics;
		$this->data->header = $header_file_name;
		$this->data->foot = $foot_file_name;
	}

	public function create_html_base($info){
		$this->info_data = $info;

		$this->get_conf();

		$html_head_base = file_get_contents($this->data_path . $this->data->head);
		$analytics_base = file_get_contents($this->data_path . $this->data->analytics);
		$header_base = file_get_contents($this->data_path . $this->data->header);
		$foot_base = file_get_contents($this->data_path . $this->data->foot);

		return array(
				'html_head' => $this->convert_html($analytics_base) . $this->convert_html($this->info_data->metatag.$html_head_base),
				'header' => $this->convert_html($header_base),
				'html_foot' => $this->convert_html($foot_base),
				'other' => $this->other,
		);
	}

	private function convert_html($html_base){
		return  str_replace(array("[path]", "[now]", "[title]"), array($this->info_data->path, $this->request, $this->info_data->title), $html_base);
	}

}
?>
