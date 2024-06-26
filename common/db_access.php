<?php
class db_access {
	private $pdo;
	private $ini_array;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		// 設定ファイル読込
		$ini_array = parse_ini_file ( __DIR__ . '/../common/config.ini', true );
		$dbname = $ini_array ['db_setting'] ['dbname'];
		$host = $ini_array ['db_setting'] ['host'];
		$user = $ini_array ['db_setting'] ['user'];
		$pass = $ini_array ['db_setting'] ['pass'];

		$this->pdo = new PDO ( 'mysql:dbname=' . $dbname . ';host=' . $host . ';charset=utf8', '' . $user . '', '' . $pass . '', array (

				// カラム型に合わない値がINSERTされようとしたときSQLエラーとする
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode='TRADITIONAL'",

				// SQLエラー発生時にPDOExceptionをスローさせる
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

				// プリペアドステートメントのエミュレーションを無効化する
				PDO::ATTR_EMULATE_PREPARES => false
		) );
	}

	/**
	 * insert,update処理(汎用型)
	 *
	 * @param クエリ $sql
	 * @return 結果(boolean)
	 */
	public function insert_update_executed($query, $param_array) {
		try {

			// 静的プレースホルダを指定
			$this->pdo->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );

			// エラー発生時に例外を投げる
			$this->pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

			$pdo = $this->pdo;

			$stmt = $pdo->prepare ( $query );

			// トランザクション処理を開始
			$this->pdo->beginTransaction ();

			try {

				$result = $stmt->execute ( $param_array );

				// コミット
				$pdo->commit ();

				return $result;
			} catch ( PDOException $e ) {
				// ロールバック
				$pdo->rollback ();
				trigger_error (  $e->getMessage (), E_USER_ERROR );

				return $e;
				die ();
			}
		} catch ( PDOException $e ) {
			trigger_error (  $e->getMessage (), E_USER_ERROR );
			return $e;
				die ();
		}
	}

	/**
	 * select処理(汎用型)
	 *
	 * @param クエリ $sql
	 * @return 結果(array)
	 */
	public function select_executed_param($sql, $param_array) {
		try {
			$result_array = array();
			$pdo = $this->pdo;

			// $stmt = $pdo->query ( $sql );
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ( $param_array );
			// echo $sql;

			while ( $result = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$result_array [] = $result;
			}

			return $result_array;
		} catch ( PDOException $e ) {
			trigger_error (  $e->getMessage (), E_USER_ERROR );
			print ('Error:' . $e->getMessage ()) ;
			die ();
		}
	}

	/**
	 * select処理(汎用型)
	 *
	 * @param クエリ $sql
	 * @return 結果(array)
	 */
	public function select_executed($sql) {
		try {
			$pdo = $this->pdo;

			$stmt = $pdo->query ( $sql );
			// echo $sql;

			while ( $result = $stmt->fetch ( PDO::FETCH_ASSOC ) ) {
				$result_array [] = $result;
			}

			return $result_array;
		} catch ( PDOException $e ) {
			trigger_error (  $e->getMessage (), E_USER_ERROR );
			print ('Error:' . $e->getMessage ()) ;
			die ();
		}
	}

	/**
	 * delete処理(汎用型)
	 *
	 * @param クエリ $sql
	 * @return 結果(boolean)
	 */
	public function delete_executed_no_param($sql) {
		try {
			$pdo = $this->pdo;

			$stmt = $pdo->prepare ( $sql );

			$pdo->beginTransaction ();

			$result = $stmt->execute ();

			$pdo->commit ();

			return $result;
		} catch ( PDOException $e ) {
			trigger_error (  $e->getMessage (), E_USER_ERROR );
			print ('Error:' . $e->getMessage ()) ;
			die ();
		}
	}


	/**
	 * delete処理
	 *
	 * @param クエリ $sql
	 * @return 結果(boolean)
	 */
	public function delete_executed($sql, $pram) {
		try {
			$pdo = $this->pdo;

			$stmt = $pdo->prepare ( $sql );

			$pdo->beginTransaction ();

			$result = $stmt->execute ($pram);

			$pdo->commit ();

			return $result;
		} catch ( PDOException $e ) {
			trigger_error (  $e->getMessage (), E_USER_ERROR );
			print ('Error:' . $e->getMessage ()) ;
			die ();
		}
	}
}

?>