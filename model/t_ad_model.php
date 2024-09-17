<?php
class t_ad_model {
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
	public function get_ad_list($offset, $limit, $sqlAdd) {
		return $this->common_logic->select_logic ( "SELECT * FROM `t_ad` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " limit " . $limit . " offset " . $offset , $sqlAdd['whereParam'] );
	}

	/**
	 * 総件数取得
	 */
	public function get_ad_list_cnt($sqlAdd ) {
		return $this->common_logic->select_logic ( "SELECT COUNT(*) AS `cnt` FROM `t_ad` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " " , $sqlAdd['whereParam'] );
	}

	/**
	 * 詳細取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_ad_detail($ad_id) {
		return $this->common_logic->select_logic ( 'select * from t_ad where ad_id = ?', array (
				$ad_id
		) );
	}

	/**
	 * 最後に登録されたidを入手
	 */
	public function search_ad(){
		return $this->common_logic->select_logic_no_param('select ad_id from t_ad order by create_at desc limit 1');
	}

	/**
	 * 新規登録
	 *
	 * @param unknown $params
	 */
	public function entry_ad($params) {
		return $this->common_logic->insert_logic ( "t_ad", $params );
	}

	/**
	 * 編集更新
	 */
	public function update_ad($params) {
		$this->common_logic->update_logic ( "t_ad", " where ad_id = ?", array (
            'type',
            'disp_date',
            'title',
            'detail',
            'img',
		), $params );

	}


	/**
	 * 削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_ad($id) {
		return $this->common_logic->update_logic ( "t_ad", " where ad_id = ?", array (
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
	public function recoveryl_ad($id) {
		return $this->common_logic->update_logic ( "t_ad", " where ad_id = ?", array (
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
	public function private_ad($id) {
		return $this->common_logic->update_logic ( "t_ad", " where ad_id = ?", array (
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
	public function release_ad($id) {
		return $this->common_logic->update_logic ( "t_ad", " where ad_id = ?", array (
				"public_flg"
		), array (
				'0',
				$id
		) );
	}
}