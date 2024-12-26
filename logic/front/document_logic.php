<?php
session_start();
require_once __DIR__ . "/../../logic/common/common_logic.php";

class document_logic{
	private $common_logic;

	public function __construct(){
		$this->common_logic = new common_logic();
	}


	public function create_document(){

          $doc_res = $this->common_logic->select_logic("select * from t_technical where public_flg = '0' and del_flg = '0'", array());
          $doc_html = '';

          foreach($doc_res as $row){
            $doc_html .= <<< EOM
        <li class="document-item">
          <div class="document-item__thum">
            <img src="./upload_files/technical/{$row['img']}" alt="{$row['title']}" loading="lazy">
          </div>
          <div class="document-item__body">
            <p class="document-item__text modal-open" data-title="{$row['title']}" data-file="{$row['pdf']}" id="doc_{$row['technical_id']}">{$row['title']}</p>
            <p class="document-item__d-link modal-open" data-title="{$row['title']}" data-file="{$row['pdf']}" id="doc_{$row['technical_id']}">ダウンロード</p>
          </div>
        </li>
EOM;

          }
          return $doc_html;
        }


}

