<?php
/**
 * 当クラスはセキュリティに影響がないシステムの定数を定義するクラスである
 */

/**
 * HTTPS・HTTP判断
 *
 * @var unknown
 */
define ( "HTTP_TYPE", (empty ( $_SERVER ["HTTPS"] ) ? "http://" : "https://") . $_SERVER ["HTTP_HOST"] ) . "/";

/**
 * 管理画面URL
 *
 * @var unknown
 */
// ビューリンク用path
define ( "MEDICALNET_ADMIN_PATH", HTTP_TYPE . "/delphi/admin/" );

/**
 * ini_path
 *
 * @var unknown
 */
define ( "INI_PATH", __DIR__ . '/../common/config.ini' );

/**
 * BaseUrl
 */
$ini_array = parse_ini_file (INI_PATH, true );
$base_url = $ini_array['url']['base_url'];
define('BASE_URL', $base_url);

/**
 * ページタイトル、パンくず定義
 *
 * @var unknown
 */
define ( "PAGE_HEDER_DETAIL", '{
	"menu.php":[{
		"title":"総合メニュー","pan":["menu"]
		}],
	"admin_user.php":[{
		"title":"総合メニュー","pan":["menu"]
		}],
	"hosptal_list.php":[{
		"title":"総合メニュー","pan":["menu"]
		}],
	}]
}' );

/** ニュースタイプ **/
defined('NEWS_TYPE1') or define('NEWS_TYPE1','交通情報');
defined('NEWS_TYPE2') or define('NEWS_TYPE2','天気情報');
defined('NEWS_TYPE3') or define('NEWS_TYPE3','運営からのお知らせ');
defined('NEWS_TYPE4') or define('NEWS_TYPE4','一般ニュース');
defined('NEWS_TYPE5') or define('NEWS_TYPE5','交通ニュース');
defined('NEWS_TYPE6') or define('NEWS_TYPE6','経済ニュース');
defined('NEWS_TYPE7') or define('NEWS_TYPE7','スポーツニュース');
defined('NEWS_TYPE8') or define('NEWS_TYPE8','速報');
defined('NEWS_TYPE9') or define('NEWS_TYPE9','政治');



define ( "PREF_SELECT_HTML", '
		<select name="pref" id="pref" class="form-control input_form validate" style="width:auto">
			<option value="" selected>都道府県</option>
			<option value="北海道">北海道</option>
			<option value="青森県">青森県</option>
			<option value="岩手県">岩手県</option>
			<option value="宮城県">宮城県</option>
			<option value="秋田県">秋田県</option>
			<option value="山形県">山形県</option>
			<option value="福島県">福島県</option>
			<option value="茨城県">茨城県</option>
			<option value="栃木県">栃木県</option>
			<option value="群馬県">群馬県</option>
			<option value="埼玉県">埼玉県</option>
			<option value="千葉県">千葉県</option>
			<option value="東京都">東京都</option>
			<option value="神奈川県">神奈川県</option>
			<option value="新潟県">新潟県</option>
			<option value="富山県">富山県</option>
			<option value="石川県">石川県</option>
			<option value="福井県">福井県</option>
			<option value="山梨県">山梨県</option>
			<option value="長野県">長野県</option>
			<option value="岐阜県">岐阜県</option>
			<option value="静岡県">静岡県</option>
			<option value="愛知県">愛知県</option>
			<option value="三重県">三重県</option>
			<option value="滋賀県">滋賀県</option>
			<option value="京都府">京都府</option>
			<option value="大阪府">大阪府</option>
			<option value="兵庫県">兵庫県</option>
			<option value="奈良県">奈良県</option>
			<option value="和歌山県">和歌山県</option>
			<option value="鳥取県">鳥取県</option>
			<option value="島根県">島根県</option>
			<option value="岡山県">岡山県</option>
			<option value="広島県">広島県</option>
			<option value="山口県">山口県</option>
			<option value="徳島県">徳島県</option>
			<option value="香川県">香川県</option>
			<option value="愛媛県">愛媛県</option>
			<option value="高知県">高知県</option>
			<option value="福岡県">福岡県</option>
			<option value="佐賀県">佐賀県</option>
			<option value="長崎県">長崎県</option>
			<option value="熊本県">熊本県</option>
			<option value="大分県">大分県</option>
			<option value="宮崎県">宮崎県</option>
			<option value="鹿児島県">鹿児島県</option>
			<option value="沖縄県">沖縄県</option>
		</select>' );

define ( "PREF_SELECT_HTML2", '
			<option value="" selected>都道府県</option>
			<option value="北海道">北海道</option>
			<option value="青森県">青森県</option>
			<option value="岩手県">岩手県</option>
			<option value="宮城県">宮城県</option>
			<option value="秋田県">秋田県</option>
			<option value="山形県">山形県</option>
			<option value="福島県">福島県</option>
			<option value="茨城県">茨城県</option>
			<option value="栃木県">栃木県</option>
			<option value="群馬県">群馬県</option>
			<option value="埼玉県">埼玉県</option>
			<option value="千葉県">千葉県</option>
			<option value="東京都">東京都</option>
			<option value="神奈川県">神奈川県</option>
			<option value="新潟県">新潟県</option>
			<option value="富山県">富山県</option>
			<option value="石川県">石川県</option>
			<option value="福井県">福井県</option>
			<option value="山梨県">山梨県</option>
			<option value="長野県">長野県</option>
			<option value="岐阜県">岐阜県</option>
			<option value="静岡県">静岡県</option>
			<option value="愛知県">愛知県</option>
			<option value="三重県">三重県</option>
			<option value="滋賀県">滋賀県</option>
			<option value="京都府">京都府</option>
			<option value="大阪府">大阪府</option>
			<option value="兵庫県">兵庫県</option>
			<option value="奈良県">奈良県</option>
			<option value="和歌山県">和歌山県</option>
			<option value="鳥取県">鳥取県</option>
			<option value="島根県">島根県</option>
			<option value="岡山県">岡山県</option>
			<option value="広島県">広島県</option>
			<option value="山口県">山口県</option>
			<option value="徳島県">徳島県</option>
			<option value="香川県">香川県</option>
			<option value="愛媛県">愛媛県</option>
			<option value="高知県">高知県</option>
			<option value="福岡県">福岡県</option>
			<option value="佐賀県">佐賀県</option>
			<option value="長崎県">長崎県</option>
			<option value="熊本県">熊本県</option>
			<option value="大分県">大分県</option>
			<option value="宮崎県">宮崎県</option>
			<option value="鹿児島県">鹿児島県</option>
			<option value="沖縄県">沖縄県</option>' );

define ( "PREF_SELECT_HTML3", '
			<option value="北海道">北海道</option>
			<option value="青森県">青森県</option>
			<option value="岩手県">岩手県</option>
			<option value="宮城県">宮城県</option>
			<option value="秋田県">秋田県</option>
			<option value="山形県">山形県</option>
			<option value="福島県">福島県</option>
			<option value="茨城県">茨城県</option>
			<option value="栃木県">栃木県</option>
			<option value="群馬県">群馬県</option>
			<option value="埼玉県">埼玉県</option>
			<option value="千葉県">千葉県</option>
			<option value="東京都">東京都</option>
			<option value="神奈川県">神奈川県</option>
			<option value="新潟県">新潟県</option>
			<option value="富山県">富山県</option>
			<option value="石川県">石川県</option>
			<option value="福井県">福井県</option>
			<option value="山梨県">山梨県</option>
			<option value="長野県">長野県</option>
			<option value="岐阜県">岐阜県</option>
			<option value="静岡県">静岡県</option>
			<option value="愛知県">愛知県</option>
			<option value="三重県">三重県</option>
			<option value="滋賀県">滋賀県</option>
			<option value="京都府">京都府</option>
			<option value="大阪府">大阪府</option>
			<option value="兵庫県">兵庫県</option>
			<option value="奈良県">奈良県</option>
			<option value="和歌山県">和歌山県</option>
			<option value="鳥取県">鳥取県</option>
			<option value="島根県">島根県</option>
			<option value="岡山県">岡山県</option>
			<option value="広島県">広島県</option>
			<option value="山口県">山口県</option>
			<option value="徳島県">徳島県</option>
			<option value="香川県">香川県</option>
			<option value="愛媛県">愛媛県</option>
			<option value="高知県">高知県</option>
			<option value="福岡県">福岡県</option>
			<option value="佐賀県">佐賀県</option>
			<option value="長崎県">長崎県</option>
			<option value="熊本県">熊本県</option>
			<option value="大分県">大分県</option>
			<option value="宮崎県">宮崎県</option>
			<option value="鹿児島県">鹿児島県</option>
			<option value="沖縄県">沖縄県</option>' );



define ( "TIME_OPTION_HTML", '
			<option value="">select</option>
			<option value="0">休診日</option>
			<option value="800">8：00</option>
				<option value="830">8：30</option>
				<option value="900">9：00</option>
				<option value="930">9：30</option>
				<option value="1000">10：00</option>
				<option value="1030">10：30</option>
				<option value="1100">11：00</option>
				<option value="1130">11：30</option>
				<option value="1200">12：00</option>
				<option value="1230">12：30</option>
				<option value="1300">13：00</option>
				<option value="1330">13：30</option>
				<option value="1400">14：00</option>
				<option value="1430">14：30</option>
				<option value="1500">15：00</option>
				<option value="1530">15：30</option>
				<option value="1600">16：00</option>
				<option value="1630">16：30</option>
				<option value="1700">17：00</option>
				<option value="1730">17：30</option>
				<option value="1800">18：00</option>
				<option value="1830">18：30</option>
				<option value="1900">19：00</option>
				<option value="1930">19：30</option>
				<option value="2000">20：00</option>
				<option value="2030">20：30</option>
				<option value="2100">21：00</option>
				<option value="2130">21：30</option>
				<option value="2200">22：00</option>' );

class Constants{
    static function getNewsType_string($type=null) {
        $array = array();
        for ($i=1; $i<10; $i++) {
            if (defined('NEWS_TYPE'.$i)) {
                $array[$i] = constant('NEWS_TYPE'.$i);
            }
        }

        if ($type == null) {
            return $array;
        }else if (isset($array[$type])) {
            return $array[$type];
        } else {
            return '';
        }
    }
}
