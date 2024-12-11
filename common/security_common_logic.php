<?php
// 設定ファイル読込
require_once __DIR__ . '/../logic/common/common_logic.php';
require_once __DIR__ . '/../common/common_constant.php';
require_once __DIR__ . '/../common/log.php';
require_once __DIR__ . '/../model/t_admin_model.php';

/**
 * 当クラスは、様々な不正アクセスに対する
 * セキュリティクラスを構成するものである
 *
 * @author Second floor Seidou
 *
 */
class security_common_logic {
	private $ttl;
	private $name;

	/**
	 * セキュリティチェック実行
	 *
	 * @param unknown $post
	 * @return multitype:
	 */
	function security_exection($post, $request, $cookie) {
		$security_common_logic = new security_common_logic ();
		$post = $security_common_logic->is_uri ( $post );
		$post = $security_common_logic->delete_nullbyte ( $post );

		$request = $security_common_logic->is_uri ( $request );
		$request = $security_common_logic->delete_nullbyte ( $request );

		$cookie = $security_common_logic->is_uri ( $cookie );
		$cookie = $security_common_logic->delete_nullbyte ( $cookie );

		return array (
				$post,
				$request,
				$cookie
		);
	}


	/**
	 * クロスサイトスクリプティング(XSS)対策追加処理
	 * htmlspecialcharsだけでは以下の対処ができない為、当functionにてチェックを行う
	 * 1.style タグや script タグの内部に外部からの入力を挿入する対策
	 * 2.タグの属性部分に外部からの変数を挿入する対策
	 *
	 * @param unknown $uri
	 * @return boolean
	 */
	function is_uri($uri) {
		$b = $uri;

		//多次元配列に対応する為、再帰処理にて実行
		array_walk_recursive ( $b, function (&$value) {

			$value = htmlspecialchars ( $value );

			if ( strpos ( $value, "ftp" )) {
				if (! preg_match ( "!^(?:https?|ftp)://" . // scheme( http | https | ftp )
						"(?:\w+:\w+@)?" . // ( user:pass )?
						"(" . "(?:[-_0-9a-z]+\.)+(?:[a-z]+)\.?|" . // ( domain name |
						"\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|" . // IP Address |
						"localhost" . // localhost )
						")" . "(?::\d{1,5})?(?:/|$)!iD", // ( :Port )?
						$value )) {
							trigger_error ( "不正な操作を感知しました。", E_USER_ERROR );
						}
			}
		} );

		return $b;
	}

	/**
	 * NULLバイトチェック
	 * 当メソッドはNULLバイトが含まれていた場合、NULLバイトを除去した値を返す
	 *
	 * @param unknown $arr
	 * @return multitype: mixed
	 */
	function delete_nullbyte($arr) {
		$b = $arr;

		//多次元配列に対応する為、再帰処理にて実行
		array_walk_recursive ( $b, function (&$value) {
			$value = str_replace ( "\0", "", $value );
		} );

		return $b;
	}

	/**
	 * HTTP レスポンス分割攻撃対処(改行を除去)
	 *
	 * @param unknown $param
	 * @return multitype:boolean mixed |multitype:boolean
	 */
	function isCRLF($params) {
		if (is_array ( $params )) {
			foreach ( $params as $key => $param ) {
				if ($param != null && $param != "") {
					$params [$key] = str_replace ( array (
							"\r",
							"\n"
					), "", $param );
				} else {
					$params [$key] = $param;
				}
			}
			return $params;
		} else {
			if ($params != null && $params != "") {
				return str_replace ( array (
						"\r",
						"\n"
				), "", $params );
			} else {
				return $params;
			}
		}
	}

	/**
	 * token処理
	 *
	 * @param string $name
	 * @param number $ttl
	 */
	function Token($name = 'tokens', $ttl = 1800) {
		// CSRF 検出トークン最大有効期限(秒)
		// 最小期限はこの値の 1/2 (1800 の場合は、900秒間は最低保持される)
		$this->ttl = ( int ) $ttl;

		// セッションに登録するトークン配列の名称
		$this->name = $name;
	}

	/**
	 * トークンを生成
	 */
	function createToken($t_admin_id) {
		$t_admin_model = new t_admin_model ();
		$curr = time ();
		$tokens = isset ( $_SESSION ['adminer'][$this->name] ) ? $_SESSION ['adminer'][$this->name] : array ();
		foreach ( $tokens as $id => $time ) {
			// 有効期限切れの場合はリストから削除
			if ($time < $curr - $this->ttl) {
				unset ( $tokens [$id] );
			} else {
				$uniq_id = $id;
			}
		}
		if (count ( $tokens ) < 2) {
			if (! $tokens || ($curr - ( int ) ($this->ttl / 2)) >= max ( $tokens )) {
				$uniq_id = sha1 ( uniqid ( rand (), TRUE ) );
				$tokens [$uniq_id] = time ();
			}
		}

		// リストをセッションに登録
		$_SESSION ['adminer'][$this->name] = $tokens;
		$_SESSION ['adminer']['uniq_id'] = $uniq_id;

		// セッションID更新
		$t_admin_model->update_ses_id ( $t_admin_id, $uniq_id );

		return $uniq_id;
	}

	/**
	 * token(セッションID)チェック実行処理
	 *
	 * @return status:返却結果
	 * @return error_code:エラー識別コード
	 * @return error_msg:フロントに出力するエラーメッセージ
	 * @return return_url:エラーメッセージ出力後の遷移先URL
	 */
	function isTokenExection() {
		// tokenチェック
		$security_common_logic = new security_common_logic ();
		$result = $security_common_logic->isToken ( $_SESSION ['adminer']['user_id'] );
		if (! $result) {

			// AJAX返却用データ成型
			$data = array (
					'status' => false,
					'error_code' => 0,
					'error_msg' => '一定時間操作がされなかった為、セッションタイムアウトしました。再ログインして下さい。',
					'return_url' => 'logout.php'
			);
		} else {
			// AJAX返却用データ成型
			$data = array (
					'status' => true
			);
		}
		return $data;
	}

	/**
	 * セッションIDチェック
	 * DBに登録されているses_idとsessionのses_idが一致し、
	 * かつ設定された時間内であった場合はses_idを新たに発行しDBのses_idを更新する。
	 * 上記の条件に全て一致した場合はtrueを返却し、一致しない場合はfalseを返却する。
	 *
	 * @param unknown $ses_id
	 */
	function isToken($ses_id) {
		$t_admin_model = new t_admin_model ();

		// 設定ファイル読込
		$ini_array = parse_ini_file ( INI_PATH, true );
		$set_minutes = $ini_array ['crf_setting'] ['set_minutes'];

		if (isset ( $_SESSION ['adminer']['user_id'] ) && isset ( $_SESSION ['adminer']['uniq_id'] )) {

			// DBのses_id取得
			$result = $t_admin_model->count_token_chk ( $_SESSION ['adminer']['user_id'], $_SESSION ['adminer']['uniq_id'], $set_minutes );

			if ($result) {
				// セッションID再設定
				$security_common_logic = new security_common_logic ();
				$security_common_logic->createToken ( $_SESSION ['adminer']['user_id'] );
				return true;
			}
		}
		return false;
	}

	/**
	 * セッションのリストにトークンが存在し、トークンが有効期限内の場合は FALSE を返す
	 */
	function isCSRF($token) {
		$tokens = $_SESSION ['adminer'][$this->name];
		if (isset ( $tokens [$token] ) && $tokens [$token] > time () - $this->ttl) {
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Email ヘッダ・インジェクションチェック
	 * 当メソッドはメール送信時に不正な値が入力しているかチェックを行う
	 *
	 * @param unknown $value
	 * @return boolean
	 */
	function isMail($value) {
		if (! preg_match ( '/^[-.\w]+@([-\w]+\.)\w+$/D', $value )) {
			return false;
		}

		return true;
	}

	/**
	 * セッション終了
	 * 当メソッドはサーバーセッション、及びセッションクッキーの削除を行う
	 */
	function destroy_session() {
		$_SESSION = array ();

		if (isset ( $_COOKIE [session_name ()] )) {
            setcookie(
                session_name (),
                '',
                [
                    "expires" => time () - 42000,
                    "path" => "/",
                    "secure" => true,
                    "httponly" => true,
                    "samesite" => "Strict"
                ]
            );
		}

		@session_destroy ();
	}

	/**
	 * ウィジウィグ登録、更新処理
	 *
	 * @param unknown $string(ウィジウィグ内容)
	 * @param unknown $save_file_path(保存先パス)
	 * @param unknown $save_file_name(保存ファイル名)
	 * @return array(結果、ステータスメッセージ)
	 */
	function wisiwyg_input_control($string, $save_file_path, $save_file_name) {
		$common_logic = new common_logic ();
		$status = true;
		$status_code = "0";
		$msg = "success";

		// ディレクトリ存在チェック
		$result = $common_logic->chk_dir ( $save_file_path );

		// ファイルパス
		$sPath = $save_file_path . $save_file_name;

		if ($result) {
			// ファイル存在チェック
			if (file_exists ( $sPath )) {
				$status = false;
				$msg = "ファイルが既に存在しています";
				$status_code = "1";
			} else {
				// ファイルを作成
				if (touch ( $sPath )) {
					// ファイルをオープン
					if ($filepoint = fopen ( $sPath, "w" )) {
						// ファイルのロック
						if (flock ( $filepoint, LOCK_EX )) {
							// ファイルへ書き込み
							if (fwrite ( $filepoint, $string )) {

								// ファイルのパーティションの変更
								if (chmod ( $sPath, 0644 )) {
									// ファイルのアンロック
									if (! flock ( $filepoint, LOCK_UN )) {
										$status = false;
										$msg = "ファイルのアンロック失敗";
										$status_code = "7";
									}
								} else {
									$status = false;
									$msg = "ファイルパーミッション変更失敗";
									$status_code = "6";
								}
							} else {
								$status = false;
								$msg = "ファイル書き込み失敗";
								$status_code = "5";
							}
						} else {
							$status = false;
							$msg = "ファイルのロック失敗";
							$status_code = "4";
						}
					} else {
						$status = false;
						$msg = "ファイルのオープン失敗";
						$status_code = "3";
					}
				} else {
					$status = false;
					$msg = "ファイル作成失敗";
					$status_code = "2";
				}
			}
		}

		if (! $status && $status_code != "1") {
			// ファイル削除
			unlink ( $sPath );
		}

		return array (
				$status,
				$msg
		);
	}

    function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    function validateCsrfToken($token) {
        return $token === $_SESSION['csrf_token'];
    }
}