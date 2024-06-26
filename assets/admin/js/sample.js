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
var page_title = 'サンプル'

//画像input用配列
var input_file_name = {
		'1':'etc6',
	};

var search_select = {
	selectArea : {
		//検索項目生成用
		'ID' : {
			search 		: true,
			order		: true,
			orderInit	: true,
			ColName 	: 'sample_id',
			tableOrder 	: 1,
			type 		: 'bigint',
		},
		'貨物の重量' : {
			search 		: true,
			order		: false,
			ColName 	: 'etc1',
			tableOrder 	: 2,
			type 		: 'text',
		},
		'更新日時' : {
			search 		: false,
			order		: true,
			ColName 	: 'update_at',
			tableOrder 	: 5,
			type 		: 'date',
		},
	},
	//検索時内容
	value : {},
	order : {}
}


//初回定義
var call_ajax_pager;
var call_ajax_init;
var call_ajax_edit_init;
var now_page_num_ini, page_num_ini, page_disp_cnt_ini;

var state = {
		"actType": 'init',
		"elemName" : $(this).attr('name'),
		};
history.replaceState(state, null, null);

$(window).on('bb');
$(window).on('popstate.bb',function(e) {
	var state = e.originalEvent.state; // pushState()で渡しておいたstateオブジェクトを取得する
	if (state) {
		$('html, body').scrollTop(0);
		if(state.search_select){
			search_select = state.search_select;
			$('[name=search_input]').val(search_select.value.value);
		}
		if(state.actType == 'init'){
			$('#id').val(null);

			$('#frm').find('input, select, textarea').each(function(i, elem){
				$(elem).val(null);
			});
			$('.unit_prev_img').remove();
			$('.list_show').show();
			$('.entry_input').hide();
			$('[name=search_input]').val('');

			$('#page_type').val('list_show');

			$('#now_page_num').val(now_page_num_ini);$('#page_num').val(page_num_ini);$('#page_disp_cnt').val(page_disp_cnt_ini);
			var form_datas = append_form_prams('init', 'frm', input_file_name, now_page_num_ini, page_num_ini, page_disp_cnt_ini);
			click_ctrl(null, page_title, 'init');
			call_ajax_init(form_datas);


		}else if(state.actType == 'disp_change'){
			$('#id').val(null);
			click_ctrl($('[name='+state.elemName+']'), page_title, 'nopush');
		}else if(state.actType == 'page_change'){
			// 次に表示するページ番号
			$('#id').val(null);
			var get_next_disp_page = $('#now_page_num').val();
			var now_page_num = $('#now_page_num').val();

			// 1ページに表示する件数
			var page_disp_cnt = $('#page_disp_cnt').val();
				get_next_disp_page = state.elemName;
			$('#now_page_num').val(get_next_disp_page);

			// 入力内容取得
			var form_data =  append_form_prams('init', 'frm', input_file_name,  now_page_num, get_next_disp_page, page_disp_cnt);
			call_ajax_pager(form_data);
		}else if(state.actType == 'edit'){
			// 編集対象ID設定
			$('#id').val(state.id);
			$('#page_type').val('edit_init');
			disp_ctrl();

			// 入力内容取得
			var form_data = append_form_prams('edit_init', 'frm', null, null, null, null);

			// ajax呼び出し
			call_ajax_edit_init(form_data);
		}else if(state.actType == 'search'){

			$('#now_page_num').val(now_page_num_ini);$('#page_num').val(page_num_ini);$('#page_disp_cnt').val(page_disp_cnt_ini);
			var form_data =  append_form_prams('init', 'frm', input_file_name,  now_page_num_ini, page_num_ini, page_disp_cnt_ini);
			form_data.append('search_select', JSON.stringify(search_select));
			call_ajax_init(form_data);

		}

	}
});

$(function() {

	/**
	 * 初期処理AJAX
	 */
	call_ajax_init = function (post_data){
		ajax.get(post_data).done(function(result) {
			// 正常終了
			if (result.data.status) {
				//検索時ヒット無し
					// 正常終了 一覧表示処理呼び出し
					list_disp_exection(result.data);

					// ページタイトル設定
					$('#page_title').html('<i class="fa fa-list" aria-hidden="true"></i>'+ page_title + '一覧');

					//更新初期処理
					edit_init_exection();

					//共通処理呼び出し
					common_func_bind();

					//validate開始
					validate_start();

					//テーブル表示列切り替え
					tableColDispChange();

					//検索処理
					searchMain();

					//ロード終了
					loaded();

					/** ここから別途処理呼び出し **/

					$('#etc9').datepicker({
						format: 'YYYY-MM-DD',
						autoclose : true,
						todayHighlight : true
					});







					/** ここまで * */

					$('.pagination').html(result.data.pager_html);
					var disp_max_cnt = $('#page_disp_cnt').val();
					if (result.data.cnt < disp_max_cnt) {
						disp_max_cnt = result.data.cnt;
					}

					$('.now_disp_cnt_str').html('登録件数：' + result.data.cnt + '件中&nbsp;&nbsp;1件目～'+disp_max_cnt+'件目を表示<br>');

					// ページャー処理バインド
					pager_link_disp(1);
					$('#page_num').val(result.data.page_cnt);
					pager_exection();
			}


		}).fail(function(result) {
			// 異常終了
			$('body').html(result.responseText);
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

		now_page_num_ini = $('#now_page_num').val();
		page_num_ini = $('#page_num').val();
		page_disp_cnt_ini = $('#page_disp_cnt').val();

		// 入力内容取得
		var form_datas = append_form_prams('init', 'frm', input_file_name, null, null, null);

		// 初期処理AJAX呼び出し処理
		call_ajax_init(form_datas);

	}

	/**
	 * ページャーリンク表示処理(前後3件まで)
	 */
	function pager_link_disp(now_page_num){
		var disp_link_max = Number(now_page_num) + 3;
		var disp_link_min = Number(now_page_num) - 5;

		$('.pager_area').each(function(j, elem){
			$(elem).find('.num_link').each(function(i){
				if ((disp_link_max > i && disp_link_min < i) || now_page_num == i) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		});
	}


	/**
	 * ページャー処理
	 *
	 * @param start_num
	 *            開始番号
	 * @param end_num
	 *            終了番号
	 */
	function pager_exection(){
		// 現在のページ取得
		var now_page_num = $('#now_page_num').val();

		// ページ総数取得
		var total_page_num = $('#page_num').val();
		// 前へリンク表示制御
		if (now_page_num == 1) {
			$('.prev').hide();
		} else {
			$('.prev').show();
		}

		// 次へリンク表示制御
		$('.next').show();
		if (now_page_num >= total_page_num) {
			$('.next').hide();
		}

		// 現在のページactive処理
		$('.num_link').parent('li').removeClass('active');
		$('.num_link[disp_id="'+now_page_num+'"]').parent('li').addClass('active');

		// ページング処理
		$('.next, .prev, .num_link').on('click',function(){
			// 次に表示するページ番号
			var get_next_disp_page = $('#now_page_num').val();
			var now_page_num = $('#now_page_num').val();

			// 1ページに表示する件数
			var page_disp_cnt = $('#page_disp_cnt').val();

			if ($(this).attr('pager_type') == 'next') {
				get_next_disp_page = Number(now_page_num) + 1;
			} else if ($(this).attr('pager_type') == 'prev') {
				// 前へ処理
				get_next_disp_page = Number(now_page_num) - 1;
			} else if ($(this).attr('num_link') == 'true') {
				// 数字処理
				get_next_disp_page = $(this).attr('disp_id');
			}
			var elemName = get_next_disp_page;
			$('#now_page_num').val(get_next_disp_page);

			var state = {
				"actType": 'page_change',
				"elemName" : elemName,
				"search_select" : search_select,
			};
			history.pushState(state, null, null); //URL変更


			// 入力内容取得
			var form_data =  append_form_prams('init', 'frm', input_file_name,  now_page_num, get_next_disp_page, page_disp_cnt);

			// ページャー処理呼び出し
			call_ajax_pager(form_data);

		});
	}

	/**
	 * ページャー処理AJAX
	 */
	call_ajax_pager = function (post_data) {
		//次に表示するページ番号
		var get_next_disp_page = $('#now_page_num').val();

		ajax.get(post_data).done(function(result) {
			// 正常処理
			list_disp_exection(result.data);
			// 次のページactive処理
			$('.num_link').parent('li').removeClass('active');
			$('.num_link[disp_id="'+now_page_num+'"]').parent('li').addClass('active');
			// 一覧HTML表示
			$('#list_area').show();

			// 初期HTMLリスト表示
			$('#result').children('tbody').html(result.data.html);

			// ページャー処理バインド
			$('#page_num').val(result.data.page_cnt);

			//更新初期処理
			edit_init_exection();

			//共通処理呼び出し
			common_func_bind();

			//validate開始
			validate_start();

			//テーブル表示列切り替え
			tableColDispChange();

			//検索処理
			searchMain();

			//ロード終了
			loaded();

			/** ここから別途処理呼び出し TODO **/

			$('#etc9').datepicker({
				format: 'YYYY-MM-DD',
				autoclose : true,
				todayHighlight : true
			});








			/** ここまで **/

			// 現在のページ取得
			var now_page_num = $('#now_page_num').val();

			// ページ総数取得
			total_page_num = $('#page_num').val();
			page_disp_cnt = $('#page_disp_cnt').val();

			var disp_max_cnt = now_page_num*page_disp_cnt - page_disp_cnt + $('.count_no').length;
			var disp_min_cnt = now_page_num*page_disp_cnt - page_disp_cnt + 1;


			$('.now_disp_cnt_str').html('登録件数：' + result.data.cnt + '件中&nbsp;&nbsp;'+disp_min_cnt+'件目～'+disp_max_cnt+'件目を表示<br>');

			// 前へリンク表示制御
			if (now_page_num == 1) {
				$('.prev').hide();
			} else {
				$('.prev').show();
			}

			// 次へリンク表示制御
			if (now_page_num == $('#page_num').val()) {
				$('.next').hide();
			} else {
				$('.next').show();
			}

			// 現在のページactive処理
			$('.num_link').parent('li').removeClass('active');
			$('.num_link[disp_id="'+now_page_num+'"]').parent('li').addClass('active');
			pager_link_disp(now_page_num);

		}).fail(function(result) {
			// 異常終了
			$('body').html(result.responseText);
		});
	}

	/**
	 * 更新初期処理
	 */
	function edit_init_exection(){
		$('.edit').off();
		$('.edit').on('click',function(){

			var state = {
					"actType": 'edit',
					"id" : $(this).attr('value'),
					"search_select" : search_select,
				};
				history.pushState(state, null, null);

				// 編集対象ID設定
				$('#id').val($(this).attr('value'));
				$('#page_type').val('edit_init');
				disp_ctrl();

				// 入力内容取得
				var form_data = append_form_prams('edit_init', 'frm', null, null, null, null);


			// ajax呼び出し
			call_ajax_edit_init(form_data);

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
				console.log(result.data);
				insert_edit_data(result.data, 'frm', input_file_name);

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
		$('#list_html_area').html(data.html);

	}

});



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

