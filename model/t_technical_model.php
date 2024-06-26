<?php
class t_technical_model {
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
	public function get_technical_list($offset, $limit, $sqlAdd) {
		return $this->common_logic->select_logic ( "SELECT * FROM `t_technical` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " limit " . $limit . " offset " . $offset , $sqlAdd['whereParam'] );
	}

	/**
	 * 総件数取得
	 */
	public function get_technical_list_cnt($sqlAdd ) {
		return $this->common_logic->select_logic ( "SELECT COUNT(*) AS `cnt` FROM `t_technical` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " " , $sqlAdd['whereParam'] );
	}

	/**
	 * 詳細取得
	 *
	 * @param unknown $admin_user_id
	 * @return Ambigous
	 */
	public function get_technical_detail($technical_id) {
		return $this->common_logic->select_logic ( 'select * from t_technical where technical_id = ?', array (
				$technical_id
		) );
	}

	/**
	 * 最後に登録されたidを入手
	 */
	public function search_technical(){
		return $this->common_logic->select_logic_no_param('select technical_id from t_technical order by create_at desc limit 1');
	}

	/**
	 * 新規登録
	 *
	 * @param unknown $params
	 */
	public function entry_technical($params) {
		return $this->common_logic->insert_logic ( "t_technical", $params );
	}

	/**
	 * 編集更新
	 */
	public function update_technical($params) {
		$this->common_logic->update_logic ( "t_technical", " where technical_id = ?", array (
            'img',
            'title',
            'pdf',
		), $params );

	}


	/**
	 * 削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_technical($id) {
		return $this->common_logic->update_logic ( "t_technical", " where technical_id = ?", array (
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
	public function recoveryl_technical($id) {
		return $this->common_logic->update_logic ( "t_technical", " where technical_id = ?", array (
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
	public function private_technical($id) {
		return $this->common_logic->update_logic ( "t_technical", " where technical_id = ?", array (
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
	public function release_technical($id) {
		return $this->common_logic->update_logic ( "t_technical", " where technical_id = ?", array (
				"public_flg"
		), array (
				'0',
				$id
		) );
	}
}