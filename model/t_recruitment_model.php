<?php
class t_recruitment_model
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
	public function get_recruitment_list($offset, $limit, $sqlAdd)
	{
		return $this->common_logic->select_logic("SELECT * FROM `t_recruitment` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " limit " . $limit . " offset " . $offset, $sqlAdd['whereParam']);
	}

	/**
	 * 総件数取得
	 */
	public function get_recruitment_list_cnt($sqlAdd)
	{
		return $this->common_logic->select_logic("SELECT COUNT(*) AS `cnt` FROM `t_recruitment` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " ", $sqlAdd['whereParam']);
	}

	/**
	 * 詳細取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_recruitment_detail($recruitment_id)
	{
		return $this->common_logic->select_logic('select * from t_recruitment where recruitment_id = ?', array(
			$recruitment_id
		));
	}

	/**
	 * 最後に登録されたidを入手
	 */
	public function search_recruitment()
	{
		return $this->common_logic->select_logic_no_param('select recruitment_id from t_recruitment order by created_at desc limit 1');
	}

	/**
	 * 新規登録
	 *
	 * @param unknown $params
	 */
	public function entry_recruitment($params)
	{
		return $this->common_logic->insert_logic("t_recruitment", $params);
	}

	/**
	 * 編集更新
	 */
	public function update_recruitment($params)
	{
		$this->common_logic->update_logic("t_recruitment", " where recruitment_id = ?", array(
			'title',
			'job_type',
			'job_description',
			'emp_status',
			'work_place',
			'acad',
			'salary',
			'pay_raise',
			'bonus',
			'allowance',
			'etc1',
			'etc2',
			'etc3',
			'etc4',
			'etc5',
		), $params);
	}


	/**
	 * 削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_recruitment($id)
	{
		return $this->common_logic->update_logic("t_recruitment", " where recruitment_id = ?", array(
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
	public function recoveryl_recruitment($id)
	{
		return $this->common_logic->update_logic("t_recruitment", " where recruitment_id = ?", array(
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
	public function private_recruitment($id)
	{
		return $this->common_logic->update_logic("t_recruitment", " where recruitment_id = ?", array(
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
	public function release_recruitment($id)
	{
		return $this->common_logic->update_logic("t_recruitment", " where recruitment_id = ?", array(
			"public_flg"
		), array(
			'0',
			$id
		));
	}
}
