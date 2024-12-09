<?php
class t_member_model
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
	public function get_member_list($offset, $limit, $sqlAdd)
	{
		return $this->common_logic->select_logic("SELECT * FROM `t_member` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " limit " . $limit . " offset " . $offset, $sqlAdd['whereParam']);
	}

	/**
	 * 総件数取得
	 */
	public function get_member_list_cnt($sqlAdd)
	{
		return $this->common_logic->select_logic("SELECT COUNT(*) AS `cnt` FROM `t_member` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " ", $sqlAdd['whereParam']);
	}

	/**
	 * 詳細取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_member_detail($member_id)
	{
		return $this->common_logic->select_logic('select * from t_member where member_id = ?', array(
			$member_id
		));
	}

	/**
	 * 最後に登録されたidを入手
	 */
	public function search_member()
	{
		return $this->common_logic->select_logic_no_param('select member_id from t_member order by create_at desc limit 1');
	}

	/**
	 * 新規登録
	 *
	 * @param unknown $params
	 */
	public function entry_member($params)
	{
		return $this->common_logic->insert_logic("t_member", $params);
	}

	/**
	 * 編集更新
	 */
	public function update_member($params)
	{
		$this->common_logic->update_logic("t_member", " where member_id = ?", array(
			'name',
			'name_kana',
			'office_name',
			'office_name_kana',
			'zip',
			'pref',
			'addr',
			'tel',
			'tel2',
			'fax',
			'resp_name',
			'job',
			'mail',
			'payment',
			'jigyou',
			'truck_num',
			'url',
			'questionnaire',
			'etc1',
			'etc2',
			'etc3',
			'etc4',
			'etc5',
			'etc6',
			'etc7',
			'etc8',
		), $params);
	}


	/**
	 * 編集更新
	 */
	public function update_member_pw($params)
	{
		$this->common_logic->update_logic("t_member", " where member_id = ?", array(
			'password',
		), $params);
	}


	/**
	 * 削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_member($id)
	{
		return $this->common_logic->update_logic("t_member", " where member_id = ?", array(
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
	public function recoveryl_member($id)
	{
		return $this->common_logic->update_logic("t_member", " where member_id = ?", array(
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
	public function private_member($id)
	{
		return $this->common_logic->update_logic("t_member", " where member_id = ?", array(
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
	public function release_member($id)
	{
		return $this->common_logic->update_logic("t_member", " where member_id = ?", array(
			"public_flg"
		), array(
			'0',
			$id
		));
	}
}
