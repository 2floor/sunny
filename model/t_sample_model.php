<?php
class t_sample_model {
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
	public function get_sample_list($offset, $limit, $sqlAdd) {
		return $this->common_logic->select_logic ( "SELECT * FROM `t_sample` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " limit " . $limit . " offset " . $offset , $sqlAdd['whereParam'] );
	}

	/**
	 * 総件数取得
	 */
	public function get_sample_list_cnt($sqlAdd ) {
		return $this->common_logic->select_logic ( "SELECT COUNT(*) AS `cnt` FROM `t_sample` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " " , $sqlAdd['whereParam'] );
	}

	/**
	 * 詳細取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_sample_detail($sample_id) {
		return $this->common_logic->select_logic ( 'select * from t_sample where sample_id = ?', array (
				$sample_id
		) );
	}

	/**
	 * 最後に登録されたidを入手
	 */
	public function search_sample(){
		return $this->common_logic->select_logic_no_param('select sample_id from t_sample order by create_at desc limit 1');
	}

	/**
	 * 新規登録
	 *
	 * @param unknown $params
	 */
	public function entry_sample($params) {
		return $this->common_logic->insert_logic ( "t_sample", $params );
	}

	/**
	 * 編集更新
	 */
	public function update_sample($params) {
		$this->common_logic->update_logic ( "t_sample", " where sample_id = ?", array (
				'etc1',
				'etc2',
				'etc3',
				'etc4',
				'etc5',
				'etc6',
				'etc7',
				'etc8',
				'etc9',
		), $params );

	}


	/**
	 * 削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_sample($id) {
		return $this->common_logic->update_logic ( "t_sample", " where sample_id = ?", array (
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
	public function recoveryl_sample($id) {
		return $this->common_logic->update_logic ( "t_sample", " where sample_id = ?", array (
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
	public function private_sample($id) {
		return $this->common_logic->update_logic ( "t_sample", " where sample_id = ?", array (
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
	public function release_sample($id) {
		return $this->common_logic->update_logic ( "t_sample", " where sample_id = ?", array (
				"public_flg"
		), array (
				'0',
				$id
		) );
	}
}