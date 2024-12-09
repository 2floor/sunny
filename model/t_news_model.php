<?php
class t_news_model
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
	public function get_news_list($offset, $limit, $sqlAdd)
	{
		return $this->common_logic->select_logic("SELECT * FROM `t_news` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " limit " . $limit . " offset " . $offset, $sqlAdd['whereParam']);
	}

	/**
	 * 総件数取得
	 */
	public function get_news_list_cnt($sqlAdd)
	{
		return $this->common_logic->select_logic("SELECT COUNT(*) AS `cnt` FROM `t_news` " . $sqlAdd['where'] . " " . $sqlAdd['order'] . " ", $sqlAdd['whereParam']);
	}

	/**
	 * 詳細取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_news_detail($news_id)
	{
		return $this->common_logic->select_logic('select * from t_news where news_id = ?', array(
			$news_id
		));
	}

	/**
	 * 最後に登録されたidを入手
	 */
	public function search_news()
	{
		return $this->common_logic->select_logic_no_param('select news_id from t_news order by created_at desc limit 1');
	}

	/**
	 * 新規登録
	 *
	 * @param unknown $params
	 */
	public function entry_news($params)
	{
		return $this->common_logic->insert_logic("t_news", $params);
	}

	/**
	 * 編集更新
	 */
	public function update_news($params)
	{
		$this->common_logic->update_logic("t_news", " where news_id = ?", array(
			'type',
			'disp_date',
			'title',
			'detail',
			'img',
			'public_flg',
		), $params);
	}


	/**
	 * 削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_news($id)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
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
	public function recoveryl_news($id)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
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
	public function private_news($id)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
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
	public function release_news($id)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
			"public_flg"
		), array(
			'0',
			$id
		));
	}
}
