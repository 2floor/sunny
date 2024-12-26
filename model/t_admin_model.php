<?php
class t_admin_model
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
	 * ログイン照会
	 *
	 * @param unknown $params(id,pass)
	 */
	public function get_login_data_by_id_pass($params)
	{
		return $this->common_logic->select_logic('select * from t_admin_user where login_id = ? and pass = ?', $params);
	}

	/**
	 * セッションID更新
	 *
	 * @param unknown $id
	 * @param unknown $ses_id
	 */
	public function update_ses_id($id, $ses_id)
	{
		$this->common_logic->update_logic('t_admin_user', " where id = '" . $id . "'", array(
			'ses_id'
		), array(
			$ses_id
		));
	}

	/**
	 * 指定セッションID、管理ID、セッション保持時間内検索
	 *
	 * @param unknown $id
	 * @param unknown $ses_id
	 */
	public function count_token_chk($id, $ses_id, $ses_limit_time)
	{

		// 該当ユーザー情報取得
		$result = $this->common_logic->select_logic('select * from t_admin_user where id = ? and ses_id = ?', array(
			$id,
			$ses_id
		));

		if (count($result) > 0) {
			// 更新日時取得

			// ERROR HERE
			$update_at = $result[0]['update_at'];

			// 現在時刻と更新日時の差分を分で取得
			$result = $this->common_logic->select_logic_no_param("select timestampdiff(minute, '" . $update_at . "', now()) as diff_time");

			// セッション保持時間内判定
			if ($result[0]['diff_time'] <= $ses_limit_time) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 管理画面ユーザー一覧情報取得
	 */
	public function get_admin_user_list()
	{
		return $this->common_logic->select_logic_no_param("select * from t_admin_user order by del_flg asc, created_at desc");
	}

	/**
	 * 管理画面新規ユーザー登録
	 *
	 * @param unknown $params
	 */
	public function insert_admin_user($params)
	{
		return $this->common_logic->insert_logic("t_admin_user", $params);
	}

	/**
	 * 管理画面ユーザー詳細情報取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_admin_user_detail($id)
	{
		return $this->common_logic->select_logic('select * from t_admin_user where id = ?', array(
			$id
		));
	}

	/**
	 * 管理画面ユーザー更新
	 *
	 * @param unknown $params
	 */
	public function update_admin_user($params)
	{
		return $this->common_logic->update_logic("t_admin_user", " where id = ?", array(
			"login_id",
			"name",
			"mail",
			"pass",
			"authority"
		), $params);
	}

    public function update_admin_user_no_pass($params)
    {
        return $this->common_logic->update_logic("t_admin_user", " where id = ?", array(
            "login_id",
            "name",
            "mail",
            "authority"
        ), $params);
    }

	/**
	 * ログインIDカウント
	 *
	 * @param unknown $login_id
	 */
	public function count_login_id($login_id, $owner_id = null)
	{
        $sql = 'select count(*) as cnt from t_admin_user where login_id = ?';
        $params = [$login_id];
        if ($owner_id) {
            $sql .= ' and id != ? ';
            $params[] = $owner_id;
        }

		return $this->common_logic->select_logic($sql, $params);
	}

	/**
	 * 管理画面ユーザー削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_admin_user($id)
	{
		return $this->common_logic->update_logic("t_admin_user", " where id = ?", array(
			"del_flg"
		), array(
			'1',
			$id
		));
	}

	/**
	 * 管理画面ユーザー有効化
	 *
	 * @param unknown $id
	 */
	public function recoveryl_admin_user($id)
	{
		return $this->common_logic->update_logic("t_admin_user", " where id = ?", array(
			"del_flg"
		), array(
			'0',
			$id
		));
	}

	// ニュース

	/**
	 * ニュース管理一覧情報取得
	 */
	public function check_contents_cnt()
	{
		return $this->common_logic->select_logic("select count(*) as cnt from t_news where del_flg = ? and public_flg = ?", array(
			'1',
			'1'
		));
	}

	/**
	 * ニュース管理一覧総件数取得
	 */
	public function get_performance_list_cnt()
	{
		return $this->common_logic->select_logic_no_param("select count(*) as cnt from t_news order by del_flg asc, created_at desc");
	}


	/**
	 * ニュース管理一覧情報取得
	 */
	public function get_performance_list($offset, $limit)
	{
		return $this->common_logic->select_logic_no_param("select * from t_news order by del_flg desc, created_at asc  limit " . $limit . " offset " . $offset);
	}
	/**
	 * ニュース削除(論理削除)
	 *
	 * @param unknown $id
	 */
	public function del_performance($id)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
			"del_flg"
		), array(
			'0',
			$id
		));
	}
	/**
	 * 管理画面ユーザー有効化
	 *
	 * @param unknown $id
	 */
	public function recoveryl_performance($id)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
			"del_flg"
		), array(
			'1',
			$id
		));
	}
	/**
	 * ニュース非公開
	 *
	 * @param unknown $id
	 */
	public function private_performance($id)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
			"public_flg"
		), array(
			'0',
			$id
		));
	}
	/**
	 * ニュース公開
	 *
	 * @param unknown $id
	 */
	public function release_performance($id)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
			"public_flg"
		), array(
			'1',
			$id
		));
	}

	/**
	 * 新規ニュース登録
	 *
	 * @param unknown $params
	 */
	public function new_entry_performance($params)
	{
		return $this->common_logic->insert_logic("t_news", $params);
	}

	/**
	 * ニュース詳細情報取得
	 *
	 * @param unknown $id
	 * @return Ambigous
	 */
	public function get_performance_detail($id)
	{
		return $this->common_logic->select_logic('select * from t_news where news_id = ?', array(
			$id
		));
	}
	/**
	 * ニュース更新
	 *
	 * @param unknown $params
	 */
	public function update_performance($params)
	{
		return $this->common_logic->update_logic("t_news", " where news_id = ?", array(
			"news_type",
			"disp_date",
			"title",
			"detail",
			"img_name"
		), $params);
	}
}
