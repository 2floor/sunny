<?php
require_once 'common_logic.php';

$type = $_GET['file_type'];

session_start ();

sampleCsv ($type);

/**
 * CSV関連
 */

/**
 * データ参照のCSVダウンロード処理を行います。
 *
 * @throws Exception
 */
function sampleCsv($type) {
	try {
		$common_logic = new common_logic ();

// 		$_SESSION ['csv_select'];
// 		echo $_SESSION ['csv_select'];

// 		$file_name_csv = str_replace('-', '', substr($_SESSION['csv_select_data3'], 0, 10)) . str_replace(':', '', substr($_SESSION['csv_select_data3'], 11, 3)) . '_' . $_SESSION['csv_select_data4'] . '.csv';

// 		$result = $common_logic->select_logic_no_param ( $_SESSION['csv_select']);

// 		// CSV形式で情報をファイルに出力のための準備
// 		$csvFileName = '../../tmp/' . time () . rand () . '.csv';
// 		$res = fopen ( $csvFileName, 'w' );
// 		if ($res === FALSE) {
// 			throw new Exception ( 'ファイルの書き込みに失敗しました。' );
// 		}

// 		$dataList = null;
// 		$dataList [] = array (
// 				"日時",
// 				"子機番号",
// 				"RSSI",
// 				"水位",
// 				"温度",
// 				"電池",
// 				"中継器1",
// 				"中継器2",
// 				"中継器3"
// 		);

// 		$terminal_name = '';
// 		for($i = 0; $i < count ( $result ); $i ++) {
// 			$row = $result [$i];

// 			// 中継端末JSONデコード
// 			$relay_addr_array = json_decode ( $row ['relay_addr'], true );

// 			// 中継端末成型
// 			$relay_addr1 = '----';
// 			if ($relay_addr_array [0] != null && $relay_addr_array [0] != '') {
// 				$relay_addr1 = $relay_addr_array [0];
// 			}

// 			$relay_addr2 = '----';
// 			if ($relay_addr_array [1] != null && $relay_addr_array [1] != '') {
// 				$relay_addr2 = $relay_addr_array [1];
// 			}

// 			$relay_addr3 = '----';
// 			if ($relay_addr_array [2] != null && $relay_addr_array [2] != '') {
// 				$relay_addr3 = $relay_addr_array [2];
// 			}

// 			$terminal_result = $common_logic->select_logic_no_param ( 'select * from t_terminal where terminal_id = ' . $row ['terminal_id'] . ' limit 1' );

// 			$datetime = str_replace ( "-", "/", $row ["create_at"] );

// 			$temperature_array = json_decode($row ["temperature"], true);


// 			$dataList [] = array (
// 					$datetime,
// 					$terminal_result [0] ["wireless_id"],
// 					$row ["rssi"],
// 					$row ["water_level"],
// 					$temperature_array ["water_temp"],
// 					$row ["battery_capacity"],
// 					$relay_addr1,
// 					$relay_addr2,
// 					$relay_addr3
// 			);

// 			if ($i == 0) {
// 				$terminal_name = $terminal_result[0]['terminal_name'];
// 			}
// 		}
// // 		$file_name_csv = $terminal_name . $file_name_csv;
// 		$file_name_csv = $file_name_csv;

// 		// ループしながら出力
// 		foreach ( $dataList as $value ) {

// 			// 文字コード変換。エクセルで開けるようにする
// 			mb_convert_variables ( 'Shift_JIS', 'UTF-8', $value );

// // 			$value = str_replace('\"', '', $value);
// 			$value = preg_replace("/^\"(.*)\"$/", "$1", $value);

// 			// ファイルに書き出しをする
// 			fputcsv ( $res, $value);
// 		}

// 		// ハンドル閉じる
// 		fclose ( $res );


		// ダウンロード開始
		header ( 'Content-Type: application/octet-stream' );

		// ここで渡されるファイルがダウンロード時のファイル名になる
		header ( 'Content-Disposition: attachment; filename='.$_SESSION['out_file_name'] );
		header ( 'Content-Transfer-Encoding: binary' );
		header ( 'Content-Length: ' . filesize ( $_SESSION['out_file_path'] ) );
		readfile ( $_SESSION['out_file_path'] );
	} catch ( Exception $e ) {

		// 例外処理をここに書きます
		echo $e->getMessage ();
	}
}