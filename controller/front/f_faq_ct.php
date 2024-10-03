<?php

use App\Models\FAQ;

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../third_party/bootstrap.php';
require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . "/../../logic/front/auth_logic.php";

/**
 * セキュリティチェック
 */
// インスタンス生成
$security_common_logic = new security_common_logic();

// XSSチェック、NULLバイトチェック
$security_result = $security_common_logic->security_exection($_POST, $_REQUEST, $_COOKIE);

// セキュリティチェック後の値を再設定
$_POST = $security_result[0];
$_REQUEST = $security_result[1];
$_COOKIE = $security_result[2];

if (isset($_GET['method'])) {
    $ct = new f_faq_ct();
    $data = $ct->mainAjaxGet($_GET);
    echo json_encode($data);
} elseif (isset($_POST['method'])) {
    $ct = new f_faq_ct();
    $data = $ct->mainAjaxPost($_POST);
    echo json_encode($data);
}

class f_faq_ct
{
    protected $auth_logic;

    public function  __construct()
    {
        $this->auth_logic = new auth_logic();
    }

    public function mainAjaxGet($get)
    {
        $data = [
            'status' => false,
            'data' => []
        ];

        return $data;
    }

    public function mainAjaxPost($post)
    {
        $data = [
            'status' => false,
            'data' => []
        ];

        return $data;
    }

    public function pageIndex()
    {
        $permFAQ = $this->auth_logic->check_permission('view.faq');
        if (!$permFAQ) {
            return [];
        }

        $faqs = FAQ::select('id', 'question', 'answer', 'group_answer')
            ->orderBy('group_answer', 'ASC')
            ->get()
            ->groupBy('group_answer');

        return [
            'faqs' => $faqs->toArray(),
        ];
    }
}
