<?php

class meta
{
    public function __construct()
    {
    }

    public function get_meta($page_name)
    {
        $page_ttl = '';
        $page_keywords = '';
        $page_description = '';
        $index_flg = false;
        $no_meta = false;
        $remove_meta = false;    //メタ非表示にする際にtrue

        /**
         * 設定ここから
         */

        // 下記設定に当てはまらなかったら表示するもの
        $page_ttl_ini = 'non/title';
        $page_keywords_ini = 'keywords';
        $page_description_ini = 'description';

        $site_name = "｜サニーヘルス株式会社"; // タイトルの末尾に追加される不要であれば「""」のみにする

        if (strpos($page_name, "hospital/first-search.php") !== false) {
            $page_ttl = '病院検索 | 新規医療機関検索' . $site_name;
            $page_keywords = '';
            $page_description = '';

        } elseif (strpos($page_name, "hospital/second-search.php") !== false) {
            $page_ttl = '病院検索 | セカンドオピニオン検索' . $site_name;
            $page_keywords = '';
            $page_description = '';

        } elseif (strpos($page_name, "hospital/detail/index.php") !== false) {
            $page_ttl = '病院検索 |詳細' . $site_name;
            $page_keywords = '';
            $page_description = '';
        } elseif ($page_name == "" || $page_name == "/" || strpos($page_name, "index.php") !== false) {
            $index_flg = true;
            $page_ttl = '';
            $page_keywords = '';
            $page_description = '';

        }

        /**
         * 設定ここまで
         */


        // 設定されていなかった場合に表示する
        if ($page_ttl == null || $page_ttl == '') {
            $page_ttl = $page_ttl_ini . $site_name;
            $no_meta = true;
        }

        if ($page_keywords == null || $page_keywords == '') {
            $page_keywords = $page_keywords_ini;
            $no_meta = true;
        }

        if ($page_description == null || $page_description == '') {
            $page_description = $page_description_ini;
            $no_meta = true;
        }


        $metatag = '
<title>' . $page_ttl . '</title>
<meta name="description" content="' . $page_description . '">
<meta name="keywords" content="' . $page_keywords . '">
';


        if ($no_meta) {
            //本番時ではないとき、表示される
            //$metatag .= '<script>if(location.href.match(/localhost/g) != null || location.href.match(/2floor\.xyz/g) != null ){alert("メタタグの設定がされていません");}</script>';
        }

        //メタ非表示
        if ($remove_meta) $metatag = '';

        return array(
            "title" => $page_ttl,
            "keyword" => $page_keywords,
            "description" => $page_description,
            "metatag" => $metatag,
            "index_flg" => $index_flg
        );

    }

}