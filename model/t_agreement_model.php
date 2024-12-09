<?php
class t_agreement_model
{
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	function __construct()
	{
		$this->common_logic = new common_logic();
	}

	/**
	 * 一覧情報取得
	 */
	public function get_agreement_list($offset, $limit, $sqlAdd)
	{
		return $this->common_logic->select_logic("SELECT * FROM `t_agreement` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " limit " . $limit . " offset " . $offset, $sqlAdd['whereParam']);
	}

	/**
	 * 総件数取得
	 */
	public function get_agreement_list_cnt($sqlAdd)
	{
		return $this->common_logic->select_logic("SELECT COUNT(*) AS `cnt` FROM `t_agreement` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " ", $sqlAdd['whereParam']);
	}

	/**
	 * 詳細取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_agreement_detail($agreement_id)
	{
		return $this->common_logic->select_logic('select * from t_agreement where agreement_id = ?', array(
			$agreement_id
		));
	}

	/**
	 * 最後に登録されたidを入手
	 */
	public function search_agreement()
	{
		return $this->common_logic->select_logic_no_param('select agreement_id from t_agreement order by created_at desc limit 1');
	}

	/**
	 * 新規登録
	 *
	 * @param unknown $params
	 */
	public function entry_agreement($params)
	{
		return $this->common_logic->insert_logic("t_agreement", $params);
	}

	/**
	 * 編集更新
	 */
	public function update_agreement($params)
	{
		$this->common_logic->update_logic("t_agreement", " where agreement_id = ?", array(
			'delivery_fee',
			'hightway_fee',
		), $params);
	}


	/**
	 * 削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_agreement($id)
	{
		return $this->common_logic->update_logic("t_agreement", " where agreement_id = ?", array(
			"del_flg"
		), array(
			'1',
			$id
		));
	}
	/**
	 * 有効化
	 *
	 * @param unknown $id
	 */
	public function recoveryl_agreement($id)
	{
		return $this->common_logic->update_logic("t_agreement", " where agreement_id = ?", array(
			"del_flg"
		), array(
			'0',
			$id
		));
	}

	/**
	 * 非公開化
	 *
	 * @param unknown $id
	 */
	public function private_agreement($id)
	{
		return $this->common_logic->update_logic("t_agreement", " where agreement_id = ?", array(
			"public_flg"
		), array(
			'1',
			$id
		));
	}
	/**
	 * 公開
	 *
	 * @param unknown $id
	 */
	public function release_agreement($id)
	{
		return $this->common_logic->update_logic("t_agreement", " where agreement_id = ?", array(
			"public_flg"
		), array(
			'0',
			$id
		));
	}
}
