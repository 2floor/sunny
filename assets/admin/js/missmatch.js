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
var currentPage = window.location.pathname.split('/').pop();
var page_title = '';
if (currentPage === 'missmatch_surv.php') {
	page_title = '医療機関名寄せ一覧（生存率のデータ)';
} else if (currentPage === 'missmatch_stage.php') {
	page_title = '医療機関名寄せ一覧（年間新規入院患者数（ステージ）のデータ）';
} else {
	page_title = '医療機関名寄せ一覧（年間入院患者数データ)';
}

//画像input用配列
var input_file_name = {};

var search_select = {
	selectArea : {
		'がん種' : {
			search 		 : false,
			order		 : true,
			orderInit	: false,
			ColName 	 : 'area_name',
			tableOrder 	 : 2,
			type 		 : 'text',
			foreignRelation : 'cancer',
		},
		'医療機関名' : {
			search 		 : false,
			order		 : true,
			ColName 	 : 'hospital_name',
			tableOrder 	 : 4,
			type 		 : 'text',
			foreignRelation : 'hospital',
		},
	},
	value : {},
	order : {}
};
var searchnew_select = {};

const lastSegment = window.location.pathname.split('/').filter(Boolean).pop();
const storedSearchSelect = localStorage.getItem(`searchnew_select_${lastSegment}`);

if (storedSearchSelect) {
	searchnew_select = JSON.parse(storedSearchSelect);
	load_data_search();
}

//初回定義
var call_ajax_init;
var call_ajax_edit_init;

var currentPage = 1;

const storedCurrentPage = localStorage.getItem(`searchnew_select_${lastSegment}_current_page`);
if (storedCurrentPage) {
	currentPage = storedCurrentPage;
}


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
						checkListAction();
						searchNew();

						$('.pagination-info .total-result span').text(data[1] + ' 結果');
						$('#page_title').html('<i class="fa fa-list" aria-hidden="true"></i>'+ page_title);

						$('.thead_type').text('('+data[3]+')');
						data[2].forEach((year,key) => {
							$('#thead_year_'+key).text('('+year+')');
						});

						// $(document).ready(function() {
						// 		$('#dataTable').DataTable();
						// });

						$(".loading").hide();

						if (afterChange && pagination.pageNumber !== startPage) {
								$('#pagination-container').pagination('go', startPage);
						}
						afterChange = false
				},
				afterPageOnClick: function(event, pageNumber) {
						currentPage = pageNumber;
						localStorage.setItem(`searchnew_select_${lastSegment}_current_page`, pageNumber);
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
		call_ajax_init(form_datas, currentPage, true);

		$('button[type="reset"]').on('click', function() {
			$('[name="search_form"]').get(0).reset();
		});

	}

	/**
	 * 更新初期処理
	 */
	function edit_init_exection(){
		$('.cancel').off();
		$('.cancel').on('click',function(){
			var id = $(this).attr('value');
			if(id) {
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
			} else {
				swal({
					title: "エラー",
					text: "この項目はキャンセルできません。",
					type: "error",
					confirmButtonClass: 'btn-danger',
					confirmButtonText: "理解した",
					closeOnConfirm: true
				});
			}
		});

		$('.accept').off();
		$('.accept').on('click',function(){
			var id = $(this).attr('value');

			if(id) {
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
			} else {
				swal({
					title: "エラー",
					text: "この項目は有効にできません。",
					type: "error",
					confirmButtonClass: 'btn-danger',
					confirmButtonText: "理解した",
					closeOnConfirm: true
				});
			}
		});
	}

	function checkListAction(){
		$('#selectAllCheckbox').off();
		$('.row-checkbox').off();
		$('#submitAction').off();
		$('#actionSelect').off();

		$('#selectAllCheckbox').on('change', function () {
			const isChecked = $(this).is(':checked');
			$('.row-checkbox').prop('checked', isChecked);
			$('.row-checkbox').trigger('change');
		});


		$('.row-checkbox').on('change', function () {
			if ($('.row-checkbox:checked').length > 0) {
				$('.action-footer').show();
			} else {
				$('.action-footer').hide();
			}
			const allCheckboxes = $('.row-checkbox').length;
			const checkedCheckboxes = $('.row-checkbox:checked').length;
			$('#selectAllCheckbox').prop('checked', allCheckboxes === checkedCheckboxes);
		});



		$('#submitAction').on('click', function () {
			const selectedAction = $('#actionSelect').val();
			if (!selectedAction) {
				swal({
					title : "警告",
					text: "アクションを選択してください。",
					type : "warning",
					confirmButtonClass : 'btn-warning',
					confirmButtonText : "理解した",
					closeOnConfirm : true,
				});
				return;
			}

			const selectedCheckboxes = $('.row-checkbox:checked');
			if (selectedCheckboxes.length === 0) {
				swal({
					title : "警告",
					text: "少なくとも1行を選択してください",
					type : "warning",
					confirmButtonClass : 'btn-warning',
					confirmButtonText : "理解した",
					closeOnConfirm : true,
				});
				return;
			}

			const selectedValues = selectedCheckboxes.map(function () {
				return $(this).val();
			}).get();

			const mapingValues = selectedValues.flatMap(value => value.split(',')).filter(Boolean).map(Number);

			const mapAlert = {'accept_list': 'すべてを受け入れる', 'cancel_list': 'すべて削除する'};

			if (mapingValues.length < 0) {
				swal({
					title : "エラー",
					text: "無効なID",
					type : "danger",
					confirmButtonClass : 'btn-warning',
					confirmButtonText : "理解した",
					closeOnConfirm : true,
				});
				return;
			} else {
				swal({
					title : mapAlert[selectedAction],
					text : '管理ID' + mapingValues.join(',') + ' ' + mapAlert[selectedAction] +"。よろしいですか？",
					type : "warning",
					showCancelButton : true,
					confirmButtonClass : 'btn-warning',
					confirmButtonText : "有効にする",
					cancelButtonText : 'キャンセル',
					closeOnConfirm : false,
					closeOnCancel : false
				}, function(isConfirm) {
					if (isConfirm) {
						var form_data = append_form_prams(selectedAction, 'frm', null, false);
						form_data.append('id', mapingValues.join(','));

						call_ajax_change_state(form_data);
						$('#actionSelect').trigger('change');
					} else {
						swal.close();
					}
				});
			}
		});


		$('#actionSelect').on('change', function () {
			const selectedValue = $(this).val();
			$(this).removeClass('text-muted text-success text-danger');

			if (selectedValue === 'accept_list') {
					$(this).addClass('text-success');
			} else if (selectedValue === 'cancel_list') {
					$(this).addClass('text-danger');
			} else {
					$(this).addClass('text-muted');
			}
		});

		$('#actionSelect').trigger('change');
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

function fetch_data_search(){
	$('form[name="search_form"]').find('input, select, textarea').each(function() {
		let name = $(this).attr('name');
		let type = $(this).attr('type');

		if (name) {
			if (type === 'checkbox') {
				if (!searchnew_select[name]) searchnew_select[name] = [];
				if ($(this).is(':checked')) {
						searchnew_select[name].push($(this).val());
				}
			} else if (type === 'radio') {
				if ($(this).is(':checked')) {
						searchnew_select[name] = $(this).val();
				}
			} else {
				searchnew_select[name] = $(this).val();
			}
		}
	});
}
function load_data_search() {
	$('form[name="search_form"]').find('input, select, textarea').each(function() {
		let name = $(this).attr('name');
		if (name && searchnew_select[name] !== undefined) {
			let type = $(this).attr('type');
			if (type === 'checkbox' || type === 'radio') {
				$(this).prop('checked', searchnew_select[name].includes($(this).val()));
			} else {
				$(this).val(searchnew_select[name]);
			}
		}
	});
}

function searchNew(){
	$('button[name="search_submit"]').off();
	$('button[name="search_submit"]').on('click', function(){

		fetch_data_search();
		const lastSegment = window.location.pathname.split('/').filter(Boolean).pop();
		localStorage.setItem(`searchnew_select_${lastSegment}`, JSON.stringify(searchnew_select));
		localStorage.setItem(`searchnew_select_${lastSegment}_current_page`, 1);

		var form_data = append_form_prams('init', 'frm', null, false);
		call_ajax_init(form_data);
	});

	if(search_select != undefined || search_select != null || search_select != '') {
		if(!$('.tableHeadArea').hasClass('changedOrder')){
			var selectOption = '', selectFlg = false;
			var orderOption = {}, orderFlg = false;
			$.each(search_select['selectArea'], function(name, options){
				if(options['order']){
					orderFlg = true;
					orderOption[options['tableOrder']] = {};
					if(options['orderInit']){
						orderOption[options['tableOrder']]['icon'] = '<i class="fa fa-sort-amount-desc"></i>';
						orderOption[options['tableOrder']]['active'] = 'orderActive';
						orderOption[options['tableOrder']]['order'] = 'desc';
					}else{
						orderOption[options['tableOrder']]['icon'] = '<i class="fa fa-sort"></i>';
						orderOption[options['tableOrder']]['active'] = '';
					}
				}
			});
			if(orderFlg){
				$('.tableHeadArea').addClass('changedOrder');
				$.each(orderOption, function(order, options){
					$('.tableHeadArea th').eq(order).addClass('changeOrder ' + options['active']).append(options['icon']);
					if(options['active']){
						$('.tableHeadArea th').eq(order).attr('order', options['order']);
					}
				});
			}
		}

		newBind();
	}

}

function newBind(){
	$('.changeOrder').off('.changeOrder');
	$('.changeOrder').on('click.changeOrder', function(){
		orderIconDispChange($(this), function(){
			var form_data =  append_form_prams('init', 'frm', input_file_name);
			call_ajax_init(form_data);
		});
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

// $('.group-selection').select2({
//     placeholder: '質問グループを選択してください',
//     allowClear: true
// });

$('.cancer-selection').select2({
	placeholder: 'がんの種類'
});

$('.area-selection').select2({
	placeholder: '地域を選択',
	allowClear: true
});