<?php
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';


if ($_POST != null && $_POST != '') {
	$f_baggage_logic = new f_evaluation();
	$f_baggage_logic->ct($_POST);
} else {
	header(__DIR__ . '/../../index.php');
	exit();
}

class f_evaluation
{

	private $common_logic;

	public function  __construct()
	{
		$this->common_logic = new common_logic();
	}

	public function ct($post)
	{
		$this->insert($post);
	}

	private function insert($post)
	{

		$res = $this->common_logic->insert_logic("t_evaluation", array(
			$post['a_agreement_id'],
			$_SESSION['cclue']['login']['member_id'],
			$post['a_for_id'],
			$post['evaluation'],
			$post['comment'],
			"0",
		));

		header("Location: ../../close_search_list.php");
		exit();
	}

}
