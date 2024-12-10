/**
 * フォームの表示、非表示処理はassets/admin/js/common.jsの共通関数を読み込んでます。
 *
 * ページの状態 一覧表示：.list_show 新規登録入力：.entry_input 新規登録確認：.entry_conf
 * 編集入力：.edit_input 編集確認：.edit_conf 削除確認：.del_conf
 *
 * 表示状態 一覧表示：.list_show_disp 新規登録入力：.entry_input 新規登録確認：.entry_conf
 * 編集入力：.edit_input 編集確認：.edit_conf 削除確認：.del_conf
 *
 * ページタイトル : {String} page_title
 * 画像用ネーム : {Array} input_file_name = { jq_id : input_name }
 */

//パラメータ取得
var query = getUrlVars();

//ページタイトル
var page_title = '処理済一覧'

//画像input用配列
var input_file_name = {};

var search_select = {};



//初回定義
var call_ajax_init;
var call_ajax_edit_init;

var currentPage = 1;


var state = {
	"actType": 'init',
	"elemName" : $(this).attr('name'),
};
history.replaceState(state, null, null);


$(function() {

	/**
	 * 初期処理AJAX
	 */
	call_ajax_init = function (post_data, startPage = 1, afterChange = false){
		let uri = new URLSearchParams(post_data).toString();
		$('#pagination-container').pagination({
				dataSource: $('#ct_url').val() + '?' + uri,
				locator: 'data.html',
				totalNumberLocator: function(response) {
						return response.data.html[1];
				},
				pageSize: 10,
				autoHidePrevious: true,
				autoHideNext:true,
				showSizeChanger: true,
				sizeChangerOptions: [10, 20, 30],
				ajax: {
						beforeSend: function() {
								$(".loading").show()
						}
				},
				callback: function(data, pagination) {
						list_disp_exection(data[0]);
						edit_init_exection();
						common_func_bind();
						validate_start();
						tableColDispChange();
						searchNew();

						$('.pagination-info .total-result span').text(data[1] + ' 結果');
						$('#page_title').html('<i class="fa fa-list" aria-hidden="true"></i>'+ page_title + '一覧');

						$('.thead_type').text('('+data[3]+')');
						data[2].forEach((year,key) => {
							$('#thead_year_'+key).text('('+year+')');
						});
						$(".loading").hide();

						if (afterChange && pagination.pageNumber !== startPage) {
								$('#pagination-container').pagination('go', startPage);
						}
						afterChange = false
				},
				afterPageOnClick: function(event, pageNumber) {
						currentPage = pageNumber;
				}
		});
	}



	//初期処理
	page_init()

	/**
	 * 初期(一覧取得)処理
	 */
	function page_init(){
		// コントローラー呼び出し
		page_ctrl(page_title);

		// 画面TOPへスクロール
		$('html,body').animate({ scrollTop: 0 }, 'fast');

		// 各種表示状態初期化
		$('.disp_area').hide();

		// 一覧表示処理
		$('.list_show').show();

		// 入力内容取得
		var form_datas = append_form_prams('init', 'frm', null, false);

		// 初期処理AJAX呼び出し処理
		call_ajax_init(form_datas);

	}

	/**
	 * 更新初期処理
	 */
	function edit_init_exection(){
		$('.cancel').off();
		$('.cancel').on('click',function(){
			var id = $(this).attr('value');

			swal({
				title : "有効にする",
				text : '管理ID' + id + "を有効にします。よろしいですか？",
				type : "warning",
				showCancelButton : true,
				confirmButtonClass : 'btn-warning',
				confirmButtonText : "有効にする",
				cancelButtonText : 'キャンセル',
				closeOnConfirm : false,
				closeOnCancel : false
			}, function(isConfirm) {
				if (isConfirm) {
					var form_data = append_form_prams('cancel_list', 'frm', null, false);
					form_data.append('id', id);

					call_ajax_change_state(form_data);
				} else {
					swal.close();
				}
			});
		});
		$('.accept').off();
		$('.accept').on('click',function(){
			var id = $(this).attr('value');

			swal({
				title : "有効にする",
				text : '管理ID' + id + "を有効にします。よろしいですか？",
				type : "warning",
				showCancelButton : true,
				confirmButtonClass : 'btn-warning',
				confirmButtonText : "有効にする",
				cancelButtonText : 'キャンセル',
				closeOnConfirm : false,
				closeOnCancel : false
			}, function(isConfirm) {
				if (isConfirm) {
					var form_data = append_form_prams('accept_list', 'frm', null, false);
					form_data.append('id', id);

					call_ajax_change_state(form_data);
				} else {
					swal.close();
				}
			});
		});
	}

	/**
	 * 更新初期処理処理AJAX
	 */
	call_ajax_edit_init = function (post_data){
		ajax.get(post_data).done(function(result) {
			// ページタイトル設定
			$('#page_title').html('<i class="fa fa-wrench" aria-hidden="true"></i>' + page_title + '情報編集');

			// 正常終了
			if (result.data.status) {
				//更新情報自動入力
				insert_edit_data(result.data, 'frm', null);
				$('.group-selection').val(null).trigger('change');

				let group = result.data.group_answer || null;
				$('.group-selection').val(group).trigger('change');
				//ロード終了
				loaded();

				/** ここから別途処理呼び出し TODO **/
				/** ここまで **/

			}else if (!result.data.status && result.data.error_code == 0){
					// PHP返却エラー
					alert(result.data.error_msg);
					location.href = result.data.return_url;
			}

		}).fail(function(result) {
				// 異常終了
				$('body').html(result.responseText);
		});
	}

	/**
	 * 一覧表示処理
	 */
	function list_disp_exection(data){
		// 一覧表示処理
		$('.list_disp_area').show();

		// 初期HTMLリスト表示
		$('#list_html_area').html(data);
	}
});

function searchNew(){
	$('button[name="search_submit"]').off();
    $('button[name="search_submit"]').on('click', function(){
			$('form[name="search_form"]').find('input, select, textarea').each(function() {
				let name = $(this).attr('name');
				let type = $(this).attr('type');

				if (name) {
					if (type === 'checkbox') {
						if (!search_select[name]) search_select[name] = [];
						if ($(this).is(':checked')) {
								search_select[name].push($(this).val());
						}
					} else if (type === 'radio') {
						if ($(this).is(':checked')) {
								search_select[name] = $(this).val();
						}
					} else {
						search_select[name] = $(this).val();
					}
				}
			});

		var form_data = append_form_prams('init', 'frm', null, false);
		call_ajax_init(form_data);
	});
}

/**
 * ページが切り替わる際の処理
 */
function disp_change_func(type){

}

/**
 * FormData追記
 */
function fd_add(fd){
	return fd;
}

$('.group-selection').select2({
    placeholder: '質問グループを選択してください',
    allowClear: true
});
