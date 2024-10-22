/**
 * 管理画面js共通化処理
 * sample_module/assets/admin/js/sample.jsを参考にする
 * common_func_bind()を必ず呼び出すこと。
 */



/**
 * Ajax実行
 */

var $loading = $(".loading");
var loaded = function(){
	$loading.addClass("is-hide");
}

var ajax = {
		get : function(post_data) {
			var defer = $.Deferred();
			$.ajax({
				type : 'POST',
				url : $('#ct_url').val(),// コントローラURLを取得
				data : post_data,
				processData : false,
				contentType : false,
				dataType : 'json',
				success : defer.resolve,
				error : defer.reject,
				beforeSend:function(){
					$loading.removeClass("is-hide");
				},
			});
			return defer.promise();
		}
};

var ajaxNoLoading = {
		get : function(post_data) {
			var defer = $.Deferred();
			$.ajax({
				type : 'POST',
				url : $('#ct_url').val(),// コントローラURLを取得
				data : post_data,
				processData : false,
				contentType : false,
				dataType : 'json',
				success : defer.resolve,
				error : defer.reject,
			});
			return defer.promise();
		}
};

/**
 * Ajax実行
 */
var ajax_common = {
		get : function(post_data) {
			var defer = $.Deferred();
			$.ajax({
				type : 'POST',
				url : $('#common_ct_url').val(),// コントローラURLを取得
				data : post_data,
				processData : false,
				contentType : false,
				dataType : 'json',
				success : defer.resolve,
				error : defer.reject,
				beforeSend:function(){
					$loading.removeClass("is-hide");
				},
			});
			return defer.promise();
		}
};

/**
 * Ajax実行
 */
var ajax_commonNoLoading = {
		get : function(post_data) {
			var defer = $.Deferred();
			$.ajax({
				type : 'POST',
				url : $('#common_ct_url').val(),// コントローラURLを取得
				data : post_data,
				processData : false,
				contentType : false,
				dataType : 'json',
				success : defer.resolve,
				error : defer.reject,
			});
			return defer.promise();
		}
};

/**
 * フォームデータ取得処理
 *
 * 使用時は
 * 		var fd = append_form_prams(method, from_id, now_page_num, get_next_disp_page, page_disp_cnt, input_file_name);
 * と記述する。
 *
 *	method				: ct用method
 *	from_id				: 取得したいフォームのid
 *	now_page_num 		: 現在のページ
 *	get_next_disp_page	: 次のページ
 *	page_disp_cnt		: 全体のページ数
 *	input_file_name		: ファイル用連想配列(file_no : テーブルカラム名)
 *
 * 例外はinputなどのclassにexceptionを付与
 * もしくはinput_file_nameに記載しない
 *
 */
function append_form_prams(method, form_id, input_file_name, isGetFormData = true){
	//フォーム活性化
	disp_input();

	//対象フォーム設定
	if(form_id != null){
		var $form = $('#' + form_id);
	}

	//フォームの値を&区切りの文字列で取得
	var param_array = new Array();

	var fd = new FormData();

	if (isGetFormData) {
		$($form).find('input, select, textarea').each(function(i, elem){
			param_array[i] = {
				'name' : $(this).attr('name'),
				'value' :$(this).val(),
				'type': $(this).attr('type'),
				'checked': $(this).prop('checked')
			};
		});

		//フォームの値を取得FormDataに設定
		$.each(param_array, function(i, v) {
			var name = v.name || '';
			var escapedName = name.replace(/\[\]/g, '');

			if (!$('[name="' + escapedName + '"]').hasClass('exception')) {
				if (v.type != 'radio' && v.type != 'checkbox') {
					fd.append(name, v.value);
				} else if (v.type == 'radio') {
					fd.append(name, $('[name="' + escapedName + '"]:checked').val());
				} else if (v.type == 'checkbox') {
					if (v.checked) {
						fd.append(escapedName + '[]', v.value);
					}
				}
			}
		});
	}

	//ファイル名取得
	if(input_file_name != null && input_file_name != undefined){
		var file_name_array = new Array();
		$('input').each(function(){
			if($(this).attr('type') == 'file'){
				var name = $(this).attr('name');
				var cnt = $(this).attr('jq_id');
				file_name_array[input_file_name[cnt]] = "";
				$(this).parents('.formRow').find('.file_name_area').each(function(i, elem){
					if($(elem).attr('file_name') != undefined){
						file_name_array[input_file_name[cnt]] += $(elem).attr('file_name') + ',';
					}
				});

				$.each(input_file_name, function(input_name, col_name){
					if(file_name_array[col_name] != null && file_name_array[col_name] != ''){
						var append_data = file_name_array[col_name].substr(0, Number(file_name_array[col_name].length) - 1)
						fd.append(col_name , append_data);
					}
				});
			}
		});
	}

	//FormData追記
	fd_add(fd);

	fd.append('method', method);
	fd.append('edit_del_id', $('#id').val());

	if(search_select != undefined || search_select != null || search_select != ''){
		fd.append('search_select', JSON.stringify(search_select));
	}

	return fd;
}

/**
 * 更新情報初期値自動入力
 *
 * 使用時は
 * 		insert_edit_data(result.data, form_id, input_file_name);
 * と記述する。
 *
 * data 			: inputに挿入するデータ
 * form_id 			: formのid名
 * input_file_name	: ファイル用連想配列(file_no : テーブルカラム名)
 *
 * 例外はinputなどのclassにexceptionを付与
 * もしくはinput_file_nameに記載しない
 *
 */
function insert_edit_data(data, form_id, input_file_name){

	//対象フォーム設定
	var $form = $('#' + form_id);

	//複数回処理させない
	var check_flg = new Array();

	//入力処理
	$($form).find('input, select, textarea').each(function(i, elem){
		if(!$(this).hasClass('exception')){
			var type = $(this).attr('type');
			var name = $(this).attr('name');

			//複数回処理させない
			if(check_flg[name] !== true){

				if(type != 'checkbox' && type != 'radio' && type != 'file'){
					//テキスト
					$(this).val(data[name]);

				}else if(type == 'checkbox'){
					if(data[name]){
						console.log(data[name]);
						//チェックボックス
						var check_array = new Array();
						if(data[name].match(/,/g)){
							//複数
							check_array = data[name].split(',');
						}else{
							//単数
							check_array[0] = data[name];
						}
						$('[name=' + name + ']').val(check_array);
					}
				}else if(type == 'radio'){
					//ラジオボタン
					$('[name=' + name + ']').val([data[name]]);
				}
				check_flg[name] = true;
			}
		}

	});

	if(input_file_name != null && input_file_name != undefined){
		$.each(input_file_name, function(num, col_name){

			//対象フォーム設定
			var $form = $('#upload_form' + num);

			//ファイル
			var file_name_comma = '';
			var file_name_array = new Array();
			var file_no = '';

			//ファイル名取得
			file_name_comma = data[col_name];
			file_no = num;

			if(file_name_comma != '' && file_name_comma != null ){
				//カンマ区切り
				if(file_name_comma.match(/,/g)){
					file_name_array = file_name_comma.split(',');
					//画像セット
					for ( var i in file_name_array) {
						set_img_area( '../upload_files/' + $('#img_path' + file_no).val() + file_name_array[i] , file_no);
					}
				}else{
					var file_name = file_name_comma;
					set_img_area( '../upload_files/' + $('#img_path' + file_no).val() + file_name , file_no);
				}
			}
		});
	}
}

/**
 * 処理コントローラー
 */
function page_ctrl(str_page_title){

	var page_title = str_page_title

	$('[name=conf],[name=return],[name=submit],[name=return_disp],[name=new_entry]').on('click', function(){
		click_ctrl($(this), page_title);
	});
}

function click_ctrl($elem, page_title, nopush){
	var click_name;
	if(nopush == undefined){
		var state = {
				"actType": 'disp_change',
				"elemName" : $elem.attr('name'),
			};
		if( !(state.elemName == 'conf' || state.elemName == 'return' || state.elemName == 'submit' ) ){
			history.pushState(state, null, null);
		}
		click_name = $elem.attr('name');
	}else if(nopush == 'init'){
		click_name = 'return_disp';
	}else{
		click_name = $elem.attr('name');
	}

	if (click_name == 'conf') {
		if (validate_all()) {
			if ($('#page_type').val() == 'edit_init') {
				// 確認ボタン処理
				$('#page_type').val('edit_conf');
			} else {
				// 確認ボタン処理
				$('#page_type').val('entry_conf');
			}
		}
	} else if (click_name == 'return') {

		if ($('#page_type').val() == 'edit_conf') {
			// 戻るボタン処理
			$('#page_type').val('edit_init');
		} else {
			// 戻るボタン処理
			$('#page_type').val('entry_input');
		}
	} else if (click_name == 'submit') {

		if ($('#page_type').val() == 'entry_conf') {
			// 登録処理呼び出し
			submit_exec();
		} else if ($('#page_type').val() == 'edit_conf') {
			// 更新処理呼び出し
			edit_exection();
		}
	} else if (click_name == 'new_entry') {

		// 新規登録ボタン処理
		$('#page_type').val('entry_input');


	} else if (click_name == 'return_disp') {
		// パンくず一覧へ戻る処理
		$('#page_type').val('list_show');

		// ページタイトル設定
		$('#page_title').html('<i class="fa fa-list" aria-hidden="true"></i>'+ page_title +'一覧');

		// フォームリセット
		$('#frm').find('input, select, textarea').each(function(i, elem){
			if($(elem).attr('type') != 'radio' && $(elem).attr('type') != 'checkbox'){
				$(elem).val(null);
			}else{
				$(elem).prop('checked', false);
			}
		});
		$('.error').removeClass('error');
		$('.error-form').removeClass('error-form');
		$('.prevImgArea').empty();

	}
	if($("span.error").size() == 0){
		// 表示コントロール
		disp_ctrl(page_title);
	}
}


/**
 * 表示コントローラー
 */
function disp_ctrl(page_title){
	var type = $('#page_type').val();

	// ボタン初期化
	$('.button_form').hide();

	if (type == 'entry_input') {
		$('.list_show').hide();
		$('.entry_input').show();

		// ボタン制御
		$('.button_input').show();

		// 入力フォーム非活性処理呼び出し
		disp_input();

		// ページタイトル設定
		$('#page_title').html('<i class="fa fa-wrench" aria-hidden="true"></i>新規'+ page_title +'登録');

		$('#fileArea').show();

// tinymce.init({
//   selector: 'textarea',
//   plugins: 'image code',
//   toolbar: 'undo redo | image code',
//   images_upload_url: 'postAcceptor.php',
//   images_upload_base_path: '../upload_files/items',
//   language: 'ja' ,
//   language_url: '../assets/admin/js/ja.js',
//
//
//   images_upload_handler: function (blobInfo, success, failure, progress) {
// /*
//     setTimeout(function() {
//       success('http://moxiecode.cachefly.net/tinymce/v9/images/logo.png');
//     }, 2000);
// */
//     var xhr;
//
//     xhr = new XMLHttpRequest();
//     xhr.withCredentials = false;
//     xhr.open('POST', 'postAcceptor.php');
//
//     xhr.upload.onprogress = function (e) {
//       progress(e.loaded / e.total * 100);
//     };
//
//     xhr.onload = function() {
//       var json;
//
//       if (xhr.status === 403) {
//         failure('HTTP Error: ' + xhr.status, { remove: true });
//         return;
//       }
//
//       if (xhr.status < 200 || xhr.status >= 300) {
//         failure('HTTP Error: ' + xhr.status);
//         return;
//       }
//
//       json = JSON.parse(xhr.responseText);
//
//       if (!json || typeof json.location != 'string') {
//         failure('Invalid JSON: ' + xhr.responseText);
//         return;
//       }
//
//      success(json.location);
//     };
//
//     xhr.onerror = function () {
//       failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
//     };
//
//   },
//
//   init_instance_callback: function (ed) {
//     //ed.execCommand('mceImage');
//   }
// });

	} else if (type == 'entry_conf') {
		$('.list_show').hide();
		$('.entry_input').show();

		// ボタン制御
		$('.button_conf').show();

		// 入力フォーム非活性処理呼び出し
		disp_disabled();

	} else if (type == 'edit_init') {
		$('.list_show').hide();
		$('.entry_input').show();

		// ボタン制御
		$('.button_input').show();

		// 入力フォーム非活性処理呼び出し
		disp_input();

	} else if (type == 'edit_conf') {
		$('.list_show').hide();
		$('.entry_input').show();

		// ボタン制御
		$('.button_conf').show();

		// 入力フォーム非活性処理呼び出し
		disp_disabled();

	} else if (type == 'list_show') {
		// フォームリセット
		$('.entry_input').find('input, select, textarea').each(function(i, elem){
			if($(elem).attr('type') != 'radio' && $(elem).attr('type') != 'checkbox'){
				$(elem).val(null);
			}else{
				$(elem).prop('checked', false);
			}
		});
		$('.unit_prev_img').remove();
		$('.list_show').show();
		$('.entry_input').hide();
	}
	disp_change_func(type);
}

/**
 * 新規登録処理
 */
function submit_exec(){
	// 入力フォームの値取得
	var form_data = append_form_prams('entry', 'frm', input_file_name);

	// ajax呼び出し
	call_ajax_change_state(form_data);
}

/**
 * 更新登録処理
 */
function edit_exection(){
	// 入力フォームの値取得
	var form_data = append_form_prams('edit', 'frm', input_file_name);

	// ajax呼び出し
	call_ajax_change_state(form_data);
}


/**
 * 共通処理呼び出し
 */
function common_func_bind(){

	// 削除処理バインド
	$('.del').off();
	$('.del').on('click',function(){
		var id = $(this).attr('value');
		swal({
			title : "削除する",
			text : '管理ID' + id + "を削除します。よろしいですか？",
			type : "warning",
			showCancelButton : true,
			confirmButtonClass : 'btn-warning',
			confirmButtonText : "削除する",
			cancelButtonText : 'キャンセル',
			closeOnConfirm : false,
			closeOnCancel : false
		}, function(isConfirm) {
			if (isConfirm) {
				// 呼び出し前method定義
				var form_data = append_form_prams('delete', 'frm',
						null, false);
				form_data.append('id', id);

				// ajax呼び出し
				call_ajax_change_state(form_data);
			} else {
				swal("Cancelled", "キャンセルしました。", "error");
			}
		});
	});

	// 削除状態変更処理バインド
	$('.recovery').off();
	$('.recovery').on('click',function(){
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
					// 呼び出し前method定義
					var form_data = append_form_prams('recovery', 'frm',
							null, false);
					form_data.append('id', id);

					// ajax呼び出し
					call_ajax_change_state(form_data);
				} else {
					swal.close();
				}
			});
	});

	// 公開処理
	$('.release').off();
	$('.release').on('click',function(){
		var id = $(this).attr('value');

		swal({
			title : "公開にする",
			text : '管理ID' + id + "を公開にします。よろしいですか？",
			type : "warning",
			showCancelButton : true,
			confirmButtonClass : 'btn-warning',
			confirmButtonText : "公開する",
			cancelButtonText : 'キャンセル',
			closeOnConfirm : false,
			closeOnCancel : false
		}, function(isConfirm) {
			if (isConfirm) {
				// 呼び出し前method定義
				var form_data = append_form_prams('release', 'frm',
						null, false);
				form_data.append('id', id);

				// ajax呼び出し
				call_ajax_change_state(form_data);
			} else {
				swal.close();
			}
		});
	});

	//非公開処理
	$('.private').off();
	$('.private').on('click',function(){
		var id = $(this).attr('value');

		swal({
			title : "非公開にする",
			text : '管理ID' + id + "を非公開にします。よろしいですか？",
			type : "warning",
			showCancelButton : true,
			confirmButtonClass : 'btn-warning',
			confirmButtonText : "非公開にする",
			cancelButtonText : 'キャンセル',
			closeOnConfirm : false,
			closeOnCancel : false
		}, function(isConfirm) {
			if (isConfirm) {
				// 呼び出し前method定義
				var form_data = append_form_prams('private', 'frm',
						null, false);
				form_data.append('id', id);

				// ajax呼び出し
				call_ajax_change_state(form_data);
			} else {
				swal.close();
			}
		});
	});


}

/**
 * 状態（更新、登録、削除、公開）更新処理AJAX
 */
function call_ajax_change_state (post_data){
	ajax.get(post_data).done(function(result) {
		// 正常終了
		loaded();
		if (result.data.status) {
			// 完了時表示メッセージ
			swal({
				title : "Success!",
				text : result.data.msg,
				type : "success",
				confirmButtonText : "Close",
				closeOnConfirm : true
			}, function() {
				if(result.data.method == 'entry' || result.data.method == 'update' ){
					$('#page_type').val('list_show');
					disp_ctrl(page_title)
				}
				// 再読み込み
				var form_data = append_form_prams('init', 'frm', null, false);
				call_ajax_init(form_data, currentPage, true);
			});
		} else if (!result.data.status && result.data.error_code == 0) {
			// PHP返却エラー
			alert(result.data.error_msg);
			// location.href = result.data.return_url;
		}

	}).fail(function(result) {
		// 異常終了
		$('body').html(result.responseText);
	});
}


/**
 * 入力エリアundisable処理(入力画面用)
 */
function disp_input() {
	// テキスト入力可処理
	$(":text").removeAttr("disabled");
	$(":text").removeClass('conf_color');

	// パスワード入力可処理
	$(":password").removeAttr("disabled");
	$(":password").removeClass('conf_color');

	// チェックボックス入力可処理
	$("input:checkbox").attr({
		'disabled' : false
	});
	$("input:checkbox").removeClass('conf_color');

	// セレクトボックス変更可
	$("select").removeAttr("disabled");
	$("select").removeClass('conf_color');
	$("select").removeClass('conf_select');

	// テキストエリア変更不可
	$("textarea").removeAttr("disabled");
	$("textarea").removeClass('conf_color');

	// ラジオボタン活性化
	$('input[type="radio"]').removeAttr("disabled");
	$('input[type="radio"]').removeClass('conf_color');

	// フォーム活性化
	$('input[type="email"]').removeAttr("disabled");
	$('input[type="email"]').removeClass('conf_color');

	// フォーム活性化
	$('input[type="tel"]').removeAttr("disabled");
	$('input[type="tel"]').removeClass('conf_color');

	//各種表示
	$('.require_text').show();
	$('.conf_text').hide();
	$('.fileupload').show();
	$('input[type="file"]').show();
	$('.del_img').show();
	$('.progressArea').find('progress').each(function(){
		if($(this).val() != 0){
			$(this).parent('.progressArea').show();
		}
	});

	//スクロール処理
	var page_type = $('#page_type').val();
	if(page_type =="edit_init" || page_type =="entry_input" ){
		$('html,body').animate({ scrollTop: 0 }, 'fast');
	}

};

/**
 * 入力エリアdisable処理(確認画面用)
 */
function disp_disabled() {


	// テキスト入力不可処理
	$(":text").attr("disabled", "disabled");
	$(":text").addClass('conf_color');

	// パスワード入力不可処理
	$(":password").attr("disabled", "disabled");
	$(":password").addClass('conf_color');

	// チェックボックス入力不可処理
	$("input:checkbox").attr({
		'disabled' : true
	});
	$("input:checkbox").addClass('conf_color');

	// セレクトボックス変更不可
	$("select").attr("disabled", "disabled");
	$("select").addClass('conf_color');
	$("select").addClass('conf_select');


	// テキストエリア変更不可
	$("textarea").attr("disabled", "disabled");
	$("textarea").addClass('conf_color');

	// ラジオボタン非活性化
	$('input[type="radio"]').attr("disabled", "disabled");
	$('input[type="radio"]').addClass('conf_color');

	// フォーム非活性化
	$('input[type="email"]').attr("disabled", "disabled");
	$('input[type="email"]').addClass('conf_color');

	// フォーム非活性化
	$('input[type="tel"]').attr("disabled", "disabled");
	$('input[type="tel"]').addClass('conf_color');

	//各種非表示化
	$('.require_text').hide();
	$('.conf_text').show();
	$('.fileupload').hide();
	$('input[type="file"]').hide();
	$('.del_img').hide();
	$('.progressArea').find('progress').each(function(){
		if($(this).val() != 0){
			$(this).parent('.progressArea').hide();
		}
	});

	//スクロール処理
	var page_type = $('#page_type').val();
	if(page_type =="edit_conf" || page_type =="entry_conf" ){
		$('html,body').animate({ scrollTop: 0 }, 'fast');
	}
};


/**
 * Htmlタグを除去
 * @param {string} str Htmlタグが含まれた文字列(<h1>サンプル文字列</h1>)
 * @returns {string} Htmlタグ除去された文字列(サンプル文字列)
 */
function removeHtmlTag(str) {
    return String(str).replace(/<("[^"]*"|'[^']*'|[^'">])*>/g, '');
};

/**
 * URLをパースしてGET値のオブジェクトにする
 * @returns {{}} GET値のオブジェクトです。
 */
function purseQuery() {
    var result = {};
    var query = decodeURIComponent(location.search);
    var query_ary = query.substring(1).split("&");
    for (var item in query_ary) {
        var match_index = item.search(/=/);
        var key = "";
        if (match_index !== -1) {
            key = item.slice(0, match_index);
        }
        var value = item.slice(item.indexOf("=", 0) + 1);
        if (key !== "") {
            result[key] = value
        }
    }
    return result
};

///**
// * 全角英数を半角英数にする
// * @param {string} str 全角英数(ａｂｃ１２３)
// * @returns {string} 半角英数(abc123)
// */
//function convertHalfWidthToFullWidth(str) {
//    return String(str).replace(/[Ａ-Ｚａ-ｚ０-９]/g, e => {
//        return String.fromCharCode(e.charCodeAt(0) - 0xFEE0);
//    });
//};


/**
 * 数値文字列にカンマ区切りにする
 * @param  {string} numberString 数値文字列。
 * @returns {string} カンマ区切りの数値文字列
 */
function insertCommaDelimiter(numberString) {
    return String(numberString).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
};

/**
 * カンマ区切りの数値文字列を数値にする
 * @param  {string} numberString 数値文字列。
 * @returns {number} カンマを取り除いた数値
 */
function removeCommaDelimiter(numberString) {
    return Number(numberString.replace(/,/g, ''));
};


/**
 * 日付文字列を整形してYYYY/mm/ddで返す
 * @param {string} dateStr Dateがパースできる文字列 例:IEでDateTime型はパースできない
 * @param {boolean} isDateTime DateTime方式にするか
 * @returns {string} 整形した日時 "2017/01/01 00:00:00"
 */
function convertDateTime(dateStr, isDateTime) {

    const now = new Date(dateStr);
    // 年を取得
    var year = now.getFullYear();
    // 月を取得 0~11で取得されるので実際の月は+1したものとなる
    var month = now.getMonth() + 1;
    // 日を取得
    var day = now.getDate();
    // 時を取得
    var hour = now.getHours();
    // 分を取得
    var minute = now.getMinutes();
    // 秒を取得
    var second = now.getSeconds();

    // 日付時刻文字列のなかで常に2ケタにしておきたい部分はここで処理
    // 1~9の数値場合"01"~"09"という文字にする
    if (month < 10) {
        month = "0" + month;
    }
    if (day < 10) {
        day = "0" + day;
    }
    if (hour < 10) {
        hour = "0" + hour;
    }
    if (minute < 10) {
        minute = "0" + minute;
    }
    if (second < 10) {
        second = "0" + second;
    }

    var datetime = year + "/" + month + "/" + day;
    if (isDateTime) {
        datetime += " " + hour + ":" + minute + ":" + second;
    }
    return datetime;
};

/**
 * userAgentからブラウザ判定
 * @returns {string} ブラウザ名
 */
function getBrowserName() {
    var ua = window.navigator.userAgent.toLowerCase();
    var name = "unknown";
    if (ua.indexOf("msie") !== -1) {
        const ver = window.navigator.appVersion.toLowerCase();
        if (ver.indexOf("msie 6.") !== -1) {
            name = 'ie6';
        } else if (ver.indexOf("msie 7.") !== -1) {
            name = 'ie7';
        } else if (ver.indexOf("msie 8.") !== -1) {
            name = 'ie8';
        } else if (ver.indexOf("msie 9.") !== -1) {
            name = 'ie9';
        } else if (ver.indexOf("msie 10.") !== -1) {
            name = 'ie10';
        } else {
            name = 'ie';
        }
    } else if (ua.indexOf('trident/7') !== -1) {
        name = 'ie11';
    } else if (ua.indexOf('edge') !== -1) {
        name = 'edge';
    } else if (ua.indexOf('chrome') !== -1 && ua.indexOf('edge') === -1) {
        name = 'chrome';
    } else if (ua.indexOf('safari') !== -1 && ua.indexOf('chrome') === -1) {
        name = 'safari';
    } else if (ua.indexOf('opera') !== -1) {
        name = 'opera';
    } else if (ua.indexOf('firefox') !== -1) {
        name = 'firefox';
    }
    return name;
};


///**
// * _+小文字を大文字にする(例:_a を A)
// * @param {string} str スネークケース(snake_to_camel)
// * @returns {string} キャメルケース(snakeToCamel)
// */
//function snakeToCamel(str) {
//    return String(str).replace(/_./g,
//        e => {
//            return e.charAt(1).toUpperCase();
//        }
//    );
//};
//
///**
// * 大文字を_+小文字にする(例:A を _a)
// * @param {string} str キャメルケース(camelToSnake)
// * @returns {string} スネークケース(snake_to_camel)
// */
//function camelToSnake(str) {
//    return String(str).replace(/([A-Z])/g,
//        e => {
//            return '_' + e.charAt(0).toLowerCase();
//        }
//    );
//};

/**
 * UUIDっぽいものを生成する関数
 * @returns {string} UUID(550e8400-e29b-41d4-a716-446655440000)
 */
function getUuid() {
    var uuid = "";
    for (var i = 0; i < 32; i++) {
        var random = Math.random() * 16 | 0;
        if (i === 8 || i === 12 || i === 16 || i === 20) {
            uuid += "-"
        }
        uuid += (i === 12 ? 4 : (i === 16 ? (random & 3 | 8) : random)).toString(16);
    }
    return uuid;
};

/**
 * バイト数整形
 * @param {number} byte_num バイト数値(1024)
 * @returns {string} 整形したバイト文字列(1KB)
 */
function formatByteSizeUnits(byte_num) {
    var byte_str = '0B';
    if (byte_num >= 1099511627776) {
        byte_str = (byte_num / 1099511627776).toFixed(2) + 'TB';
    } else if (byte_num >= 1073741824) {
        byte_str = (byte_num / 1073741824).toFixed(2) + 'GB';
    } else if (byte_num >= 1048576) {
        byte_str = (byte_num / 1048576).toFixed(2) + 'MB';
    } else if (byte_num >= 1024) {
        byte_str = (byte_num / 1024).toFixed(2) + 'KB';
    } else if (byte_num >= 1) {
        byte_str = byte_num + 'B';
    }
    return byte_str;
};

/**
 * ブラウザの横幅取得
 * @returns {number} ブラウザの横幅
 */
function getBrowserWidth() {
    var width = 0;
    if (window.innerWidth) {
        width = window.innerWidth;
    } else if (document.documentElement && document.documentElement.clientWidth !== 0) {
        width = document.documentElement.clientWidth
    } else if (document.body && document.body.clientWidth !== 0) {
        width = document.body.clientWidth;
    }
    return width
};

/**
 * ブラウザの高さ取得
 * @returns {number}  ブラウザの高さ
 */
function getBrowserHeight() {
    var height = 0;
    if (window.innerHeight) {
        height = window.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight !== 0) {
        height = document.documentElement.clientHeight;
    } else if (document.body && document.body.clientHeight !== 0) {
        height = document.body.clientHeight;
    }
    return height;
};

/**
 * ウェブページ自体の横幅
 * @returns {number} ウェブページ自体の横幅
 */
function getContentsWidth() {
    var calc_array = [
        document.body.clientWidth,
        document.body.scrollWidth,
        document.documentElement.scrollWidth,
        document.documentElement.clientWidth
    ];
    var max_width = Math.max.apply(null, calc_array);
    return max_width ? max_width : 0
};

/**
 * ウェブページ自体の高さ取得
 * @returns {number} ウェブページ自体の高さ
 */
function getContentsHeight() {
	var calc_array = [
        document.body.clientHeight,
        document.body.scrollHeight,
        document.documentElement.scrollHeight,
        document.documentElement.clientHeight
    ];
	var max_height = Math.max.apply(null, calc_array);
    return max_height ? max_height : 0
};

/**
 * ブラウザの高さとウェブページの高さから現在の位置を計算する
 * @returns {{}}
 */
function calculatePosition() {
    /**
     * ブラウザの高さ(px)
     * @type {number}
     */
    var browser_height = getBrowserHeight();

    /**
     * Webページの高さ(px)
     * @type {number}
     */
    var contents_height = getContentsHeight();

    /*
     * 下記のページ数処理がを行う際にブラウザの高さとコンテンツの高さが
     * 取得できないとページ数計算が進まないので
     * 取得できない場合はは処理しない
     */
    if (!contents_height && !browser_height) {
        return false
    }

    /**
     * スクロールの上からの移動位置(px)
     * @type {number}
     */
    var scroll_top_value = window.pageYOffset ? window.pageYOffset : 0;

    /**
     * コンテンツの高さをブラウザの高さで割って、何ページあるか計算
     * @type {number}
     */
    var total_page_number = Math.floor(contents_height / browser_height);

    /**
     * 1ページの高さ計算
     * @type {number}
     */
    var page_height = contents_height / total_page_number;

    /**
     * 現在のスクロール位置がのコンテンツの何ページ目か取得
     * @type {number}
     */
    var page_number = function (t, s, p) {
        // スクロール位置が特定のページの範囲にあればループからでる
        for (var i = 1; i <= t; i++) {
        	var min_page_height = p * (i - 1);
        	var max_page_height = p * i;
            if (
                min_page_height < s &&
                s < max_page_height ||
                s === 0
            ) {
                return i;
            }
        }
        return 0;
    }(total_page_number, scroll_top_value, page_height);

    return {
        browser_height: browser_height,
        contents_height: contents_height,
        scroll_top_value: scroll_top_value,
        total_page_number: total_page_number,
        page_number: page_number
    }
};

/**
 * GETパラメータ取得処理
 * @returns {Array}
 */
function getUrlVars() {
	var vars = [], hash;
	var hashes = window.location.href.slice(
			window.location.href.indexOf('?') + 1).split('&');
	for (var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		if(hash[1] != null && hash[1] != ''){
			if(hash[1].match(/#/g) != null){
				hash[1].split("#")[0];
			}
			vars[hash[0]] = hash[1];
		}
	}
	return vars;
}

/**
 * カレンダーで期間を入力する
 * start_elem 開始日入力フォーム(jqueryObject)
 * end_elem　終了日入力フォーム(jqueryObject)
 */
function term_input_datepicker(start_elem, end_elem){

	/**
	 * deskpicker設定
	 */
	$.datepicker.setDefaults({
	    changeYear: true,
	    changeMonth: true,
//		showOn: "both",
//	    buttonImage: "../assets/admin/js/common/colorpicker/images/calendar-icon.png",
//	    buttonText: "カレンダーから選択",
//	    buttonImageOnly: true,
	    dateFormat:'yy-mm-dd'
	  });

	var min_date = new Date;
	$(start_elem).datepicker({
		 onClose: function( selectedDate ) {
	        $( end_elem ).datepicker( "option", "minDate", selectedDate );
	      },
	});
	$(end_elem).datepicker({
		 onClose: function( selectedDate ) {
	        $(start_elem ).datepicker( "option", "maxDate", selectedDate );
	      },
	});

	$('.ui-datepicker-trigger').hide();

}

/**
 * console.log省略 TODO
 * @param obj consoleに吐き出したい値(object)
 */
function cn(obj, opt){
	var option = 'normal'
	if(isNull(opt)){
		option = opt;
	}
	if(option == 'normal'){
		console.log(obj);

	}else if(option == 'detail'){
		var type = $.type(obj);
		console.log('type  : %s', type );
		if(type == 'string'){
			console.log('value : %s', obj);
		}else if(type == 'number'){
			console.log('value : %d', obj);
		}else if(type == 'boolean'){
			console.log('value : %s', obj);
		}else if(type == 'object'){
			if(isNull(obj.selector)){
				console.log('object: jQuery');
				console.log('detail: %s', obj.get()[0]);
				console.log('length: %s', obj.length);
			}else{
				console.log('object: %o', obj);
				console.log('json  : %s', JSON.stringify(obj, null, '\t' ));
			}
		}else if(type == 'array'){
			console.log('object: %o', obj);
			console.log('json  : %s', JSON.stringify(obj, null, '\t' ));
		}
	}
}


/**
 * Nullチェック
 * null,undefined,空の場合falseを返却
 * @param obj
 * @returns {Boolean}
 */
function isNull(obj){
	if(obj != undefined && obj != null && obj != ''){
		return true;
	}else{
		return false;
	}

}

/**
 * POSTデータ受け渡し(確認画面)プラグイン
 */
$.fn.pass_post_data = function(opt){

	//確認ボタンプロパティ
	var element = $(this);

	//格納するセッション名
	var s_name = 'post_data';
	if(opt.ses_name != '' && opt.ses_name != null && opt.ses_name != undefined){
		s_name = opt.ses_name;
	}

	//オプションの設定
	var options = $.extend({
		//遷移先URL
		url  : opt.url,

		//取得するフォームID(formタグでなくても可)
		form : opt.form,

		//画像があった場合の画像名配列（append_form_pramsを参照）
		input_file_name : opt.input_file_name,

		//格納するセッション名
		ses_name : s_name,
	}, opt);

	//送信ボタンクリック時処理
	element.off();
	element.on('click', function(){

		//取得
		var fd = append_form_prams('pass_post_data', options.form, options.input_file_name);
		fd.append('ses_name', options.ses_name);

		ajax_common.get(fd).done(function(result) {
			location.href = options.url;

		}).fail(function(result) {
			// 異常終了
			$('body').html(result.responseText);
			alert();
		});
	});

}
/**
 * POSTデータ挿入(入力画面戻る)処理
 */
$.fn.set_post_data = function(opt){

	//確認ボタンプロパティ
	var element = $(this).attr('id');

	//格納するセッション名
	var s_name = 'post_data';
	if(opt.ses_name != '' && opt.ses_name != null && opt.ses_name != undefined){
		s_name = opt.ses_name;
	}

	//オプションの設定
	var options = $.extend({
		//POSTされていたデータ
		data : opt.data,

		//取得するフォームID(formタグでなくても可)
		form : element,

		//画像があった場合の画像名配列（append_form_pramsを参照）
		input_file_name : opt.input_file_name,

	}, options);

	var json_data = JSON.parse(options.data);
	insert_edit_data(json_data, options.form, options.input_file_name);

}

/**
 * 上記pass_post_data設定処理
 */
function conf_post_data(post_json){

	var p_d = JSON.parse(post_json);
	$('.set_post').each(function(){
		var tar = $(this).attr('tar');
		$(this).text(p_d[tar]);
	});

}

/**
 * 改行変換（\r\n => <br>）
 */
var nl2br = function (str) {
    return str.replace(/\n/g, '<br>');
};

/**
 * テーブル表示切替処理
 * .tableColDisp => 表示切り替え用チェックリスト
 * .tableHradArea => テーブルへッド
 * .tableBodyArea => テーブルボディ
 */
function tableColDispChange(){
	if($('.tableColDisp').html() == null || $('.tableColDisp').html() == ''){
		var colHtml = '';
		$('.tableHeadArea').find('th').each(function(i, elem){
			var text = $(elem).text();
			colHtml += '<li class="checkbox-row">';
			colHtml += '	<div class="checkbox checkbox-primary">';
			colHtml += '		<input type="checkbox" name="colDispChange" id="colDispChange'+i+'" value="'+i+'" checked="checked">';
			colHtml += '		<label for="colDispChange'+i+'" >'+text+'</label>';
			colHtml += '	</div>';
			colHtml += '</li>';

			$(elem).attr('colDispChangeName', i).addClass('thCol');

		});
		$('.tableBodyArea').find('tr').each(function(i, elemParent){
			$(elemParent).find('td').each(function(j, elem){
				$(elem).attr('colDispChangeName', j).addClass('thCol');
			});
		});

		$('.tableColDisp').html(colHtml);
	}else{
		$('.tableBodyArea').find('tr').each(function(i, elemParent){
			$(elemParent).find('td').each(function(j, elem){
				$(elem).attr('colDispChangeName', j).addClass('thCol');
			});
		});
		colDispChangeMain();
	}

	$('[name=colDispChange]').off('.colDispChange');
	$('[name=colDispChange]').on('click.colDispChange', function(){
		colDispChangeMain();
	});

	$('[name=colDispChangeAll]').off('.colDispChange');
	$('[name=colDispChangeAll]').on('click.colDispChange', function(){
		$('[name=colDispChange]').prop("checked", true);
		colDispChangeMain();
	});
	function colDispChangeMain(){
		var colDispChangeArray = $('[name=colDispChange]:checked').map(function(){ return $(this).val(); }).get();
		$('.thCol').hide();
		$.each(colDispChangeArray, function(k, val){
			var name
			$('[colDispChangeName="'+val+'"]').show();
		});
	}
}

/**
 * 検索用処理
 */
function searchMain(){
	//定義なし->実行なし
	if(search_select == undefined || search_select == null || search_select == '') return false;
	if($('.searchAreaSelect')[0].childElementCount == 0){
		var selectOption = '', selectFlg = false;
		var orderOption = {}, orderFlg = false;
		$.each(search_select['selectArea'], function(name, options){
			if(options['search']){
				selectFlg = true;
				selectOption += '<option value="'+options['ColName']+'" data-foreign-relation="'+(options['foreignRelation'] ? options['foreignRelation'] : '')+'">'+name+'</option>';
			}
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
		if(selectFlg){ $('.searchAreaSelect').html(selectOption);}
		else{ $('.searchArea').hide(); }

		if(orderFlg){
			$.each(orderOption, function(order, options){
				$('.tableHeadArea th').eq(order).addClass('changeOrder ' + options['active']).append(options['icon']);
				if(options['active']){
					$('.tableHeadArea th').eq(order).attr('order', options['order']);
				}
			});
		}
		Bind();

	}else{
		Bind();
	}

	function Bind(){
		$('.callSearch').off('.search');
		$('.callSearch').on('click.search', function(){
			var target = $('.searchAreaSelect').val();
			var val = $('[name=search_input]').val();
			var indexName = $('.searchAreaSelect option:selected').text();
			var foreignRelation = $('.searchAreaSelect option:selected').data('foreign-relation');
			search_select['value'] = {
					target : target,
					value : val,
					name : indexName,
					foreignRelation : foreignRelation ? foreignRelation : null,
			}

			var state = {
				"actType": 'search',
				"search_select" : search_select,
			};
			history.pushState(state, null, null); //URL変更

			var form_data =  append_form_prams('init', 'frm', input_file_name);
			form_data.append('search_select', JSON.stringify(search_select));
			call_ajax_init(form_data);
		});

		$('.changeOrder').off('.changeOrder');
		$('.changeOrder').on('click.changeOrder', function(){
			orderIconDispChange($(this), function(){
				var form_data =  append_form_prams('init', 'frm', input_file_name);
				form_data.append('search_select', JSON.stringify(search_select));
				call_ajax_init(form_data);
			});
		});
	}
}

/**
 * ソートマーク切り替え
 */
var orderIconDispChange = function($target, Callback){
	var order, target, indexName, foreignRelation;
	if($target.hasClass('orderActive')){
		if($target.attr('order') == 'desc'){
			$target.find('i').addClass('fa-sort-amount-asc').removeClass('fa-sort-amount-desc');
			$target.attr('order', 'asc');
			order = 'asc';
		}else{
			$target.find('i').addClass('fa-sort-amount-desc').removeClass('fa-sort-amount-asc');
			$target.attr('order', 'desc');
			order = 'desc';
		}
	}else{
		$('.orderActive').removeAttr('order').removeClass('orderActive').find('i').removeClass('fa-sort-amount-asc fa-sort-amount-desc').addClass('fa-sort')
		$target.addClass('orderActive').attr('order', 'desc').find('i').addClass('fa-sort-amount-desc').removeClass('fa-sort');
		order = 'desc';
	}
	var index = $('.tableHeadArea th').index($target);
	$.each(search_select['selectArea'], function(name, options){
		if(options['tableOrder'] == index){
			target = options['ColName'];
			indexName = name;
			foreignRelation = options['foreignRelation'] ? options['foreignRelation'] : null;
			return false;
		}
	});
	search_select['order'] = {
			target : target,
			order : order,
			name : indexName,
			foreignRelation : foreignRelation
	}

    Callback();
};

$(window).on('scroll', function() {
	var backToTop = $('#backToTop');
	if ($(this).scrollTop() > 100) {
		backToTop.fadeIn();
	} else {
		backToTop.fadeOut();
	}
});

$('#backToTop').on('click', function() {
	$('html, body').animate({ scrollTop: 0 }, 'smooth');
});


//Upload file js
$('#file-name-display').click(function () {
	$('#upload-file').click();
});

$('#upload-file').on('change', function () {
	var fileName = $(this).val().split('\\').pop();

	if (fileName) {
		$('.callUpload').prop('disabled', false);
		$('#file-name-display').val(fileName);
	} else {
		$('.callUpload').prop('disabled', true);
	}
});

var callAjaxImport = function (import_id)
{
	var type = $('#upload-file').data('type');
	var parent = $('#upload-file').data('parent');

	var formData = new FormData();
	formData.append('import_id',import_id);
	formData.append('type', type);
	formData.append('method', 'import');

	if (parent) {
		formData.append('parent_id', parent);
	}

	$.ajax({
		url: $('#upload_csv_ct_url').val(),
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		async: true
	});


};

var callAjaxCheckImport = function () {
	var fileInput = $('#upload-file')[0];

	if (fileInput.files.length === 0) {
		swal({
			title : "失敗!",
			text : 'アップロードするファイルを選択してください',
			type : "error",
			confirmButtonText : "近い",
			closeOnConfirm : true
		});
		return;
	}

	var file = fileInput.files[0];
	var allowedTypes = ['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
	if (!allowedTypes.includes(file.type)) {
		swal({
			title : "失敗!",
			text : 'ファイルの種類を選択してください csv または xlsx',
			type : "error",
			confirmButtonText : "近い",
			closeOnConfirm : true
		});
		return;
	}

	var type = $('#upload-file').data('type');
	var parent = $('#upload-file').data('parent');

	var formData = new FormData();
	formData.append('upload-file', fileInput.files[0]);
	formData.append('type', type);
	formData.append('method', 'check');

	if (parent) {
		formData.append('parent_id', parent);
	}

	$.ajax({
		url: $('#upload_csv_ct_url').val(),
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		beforeSend: function() {
			$(".loading").show();
		},
		success: function (response) {
			response = JSON.parse(response);
			let message = response.data.message || '';

			if (response.data.status) {
				callAjaxImport(response.data.import_id);

				setTimeout(function() {
					$(".loading").hide();
					swal({
						title: "処理中!",
						text: message,
						type: "success",
						confirmButtonText: "処理状況を確認",
						closeOnConfirm: true
					}, function(isConfirm) {
						if (isConfirm) {
							window.location.href = "import.php";
						} else {
							swal.close();
						}
					});
				}, 1000);
			} else {
				$(".loading").hide();
				swal({
					title : "失敗!",
					text : message,
					type : "error",
					confirmButtonText : "近い",
					closeOnConfirm : true
				});
			}
		},
		error: function () {
			$(".loading").hide();
			swal({
				title : "失敗!",
				text : 'ファイルのアップロードに失敗しました',
				type : "error",
				confirmButtonText : "近い",
				closeOnConfirm : true
			});
		}
	});
};

$('.callUpload').click(function () {
	callAjaxCheckImport();
});

