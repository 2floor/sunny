<?php
class t_items_model {
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->common_logic = new common_logic ();
	}

	/**
	 * 一覧情報取得
	 */
	public function get_items_list($offset, $limit, $sqlAdd) {
		return $this->common_logic->select_logic ( "SELECT * FROM `t_items` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " limit " . $limit . " offset " . $offset , $sqlAdd['whereParam'] );
	}

	/**
	 * 総件数取得
	 */
	public function get_items_list_cnt($sqlAdd ) {
		return $this->common_logic->select_logic ( "SELECT COUNT(*) AS `cnt` FROM `t_items` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " " , $sqlAdd['whereParam'] );
	}

	/**
	 * 詳細取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_items_detail($items_id) {
		return $this->common_logic->select_logic ( 'select * from t_items where items_id = ?', array (
				$items_id
		) );
	}

	/**
	 * 最後に登録されたidを入手
	 */
	public function search_items(){
		return $this->common_logic->select_logic_no_param('select items_id from t_items order by create_at desc limit 1');
	}

	/**
	 * 新規登録
	 *
	 * @param unknown $params
	 */
	public function entry_items($params) {
		return $this->common_logic->insert_logic ( "t_items", $params );
	}

	/**
	 * 編集更新
	 */
	public function update_items($params) {
		$this->common_logic->update_logic ( "t_items", " where items_id = ?", array (
			"category",
			"title",
			"lead",
			"movie_title",
			"movie_url",
			"img",
			"detail",
			"disp_date",
			"etc1",
			"etc2",
			"etc3",
			"public_flg",
		), $params );
	}


	/**
	 * 削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_items($id) {
		return $this->common_logic->update_logic ( "t_items", " where items_id = ?", array (
				"del_flg"
		), array (
				'1',
				$id
		) );
	}
	/**
	 * 有効化
	 *
	 * @param unknown $id
	 */
	public function recoveryl_items($id) {
		return $this->common_logic->update_logic ( "t_items", " where items_id = ?", array (
				"del_flg"
		), array (
				'0',
				$id
		) );
	}

	/**
	 * 非公開化
	 *
	 * @param unknown $id
	 */
	public function private_items($id) {
		return $this->common_logic->update_logic ( "t_items", " where items_id = ?", array (
				"public_flg"
		), array (
				'1',
				$id
		) );
	}
	/**
	 * 公開
	 *
	 * @param unknown $id
	 */
	public function release_items($id) {
		return $this->common_logic->update_logic ( "t_items", " where items_id = ?", array (
				"public_flg"
		), array (
				'0',
				$id
		) );
	}
}