<?php
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';

if($_POST != null && $_POST != ''){
	$f_member_ct = new f_member_ct();
	$data = $f_member_ct->ct($_POST);
	echo json_encode ( compact ( 'data' ) );

}else{
	header(__DIR__ . '/../../index.php');
	exit();
}

class f_member_ct{

	private $common_logic;

	public function  __construct(){
		$this->common_logic = new common_logic();
	}

	public function ct($post){
		if($post['method'] == "mailCheck"){
			$data = $this->mailCheck($post);
		}
		return $data;
	}

	private function mailCheck($post){
		$re = $this->common_logic->select_logic("select `member_id` from `t_member` where `mail` = ? ", array($post['mail']));
		if($re[0]['member_id'] != null && $re[0]['member_id'] != ''){
			$double = false;
		}else{
			$double = true;
		}

		return array(
				'status' => true,
				'doubleStatus' => $double,
		);
	}


}

?>