<?php
session_start();
require_once __DIR__ . "/../../logic/common/common_logic.php";

ini_set('display_errors', "On"); 

class recruit_logic{
	private $common_logic;

	public function __construct(){
		$this->common_logic = new common_logic();
	}



        public function create_recruit_html(){
          
          $recruit_res = $this->common_logic->select_logic("select * from t_recruitment where public_flg = 0 and del_flg = 0", array());

          $rec_html = '';

          foreach($recruit_res as $row){

$title = nl2br($row['title']);
$job_type = nl2br($row['job_type']);
$job_description = nl2br($row['job_description']);
$emp_status = nl2br($row['emp_status']);
$work_place = nl2br($row['work_place']);
$acad = nl2br($row['acad']);
$salary = nl2br($row['salary']);
$pay_raise = nl2br($row['pay_raise']);
$bonus = nl2br($row['bonus']);
$allowance = nl2br($row['allowance']);

              $rec_html .= <<< EOM
        <div class="accordion">

          <div class="recruit-content-unit">
            <div class="page__title--jp-wrapper">
              <p class="page__title--jp">{$title}</p>
              <span class="toggle-icon"></span>
            </div>
            <dl class="mt20">
              <div class="recruit-content__item">
                <dt>職種</dt>
                <dd>{$job_type}</dd>
              </div>
              <div class="recruit-content__item">
                <dt>仕事内容</dt>
                <dd>{$job_description}</dd>
              </div>
              <div class="recruit-content__item">
                <dt>雇用形態</dt>
                <dd>
                  {$emp_status}
                </dd>
              </div>
              <div class="recruit-content__item">
                <dt>就業場所</dt>
                <dd>
                  {$work_place}
                </dd>
              </div>
              <div class="recruit-content__item">
                <dt>学歴</dt>
                <dd>
                  {$acad}
                </dd>
              </div>
              <div class="recruit-content__item">
                <dt>給与</dt>
                <dd>
                  {$salary}
                </dd>
              </div>
              <div class="recruit-content__item">
                <dt>昇給</dt>
                <dd>
                  {$pay_raise}
                </dd>
              </div>
              <div class="recruit-content__item">
                <dt>賞与</dt>
                <dd>
                  {$bonus}
                </dd>
              </div>
              <div class="recruit-content__item">
                <dt>手当</dt>
                <dd>
                  {$allowance}
                </dd>
              </div>
            </dl>
          </div>
        </div>
EOM;
            }
            return $rec_html;
         }

}

