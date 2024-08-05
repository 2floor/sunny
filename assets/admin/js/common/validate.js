validate_start();
function validate_start(){
	$(':text, :password, [type="tel"], [type="email"], [type="date"],  textarea, select').off('validate');
	$(':text, :password, [type="tel"], [type="email"], [type="date"],  textarea, select').on({
		'focus.validate':  function(){
			var before_val = $(this).val();
			$(this).off('validate_in');
			$(this).on('blur.validate_in',  function(){
				if($(this).hasClass('validate')){
					if(!$(this).hasClass('date')){
						realtime_validate($(this), true);
					}
				}
			});
		},
	});

	$(':radio, :checkbox, .date').off('validate');
	$(':radio, :checkbox, .date').on({
		'change.validate':  function(){
			if($(this).hasClass('validate')){
				realtime_validate($(this), true);
			}
		},
	});

	$('.selection2').on('select2:open', function() {
		$(this).on('select2:close', function() {
			if($(this).hasClass('validate')){
				realtime_validate($(this), true);
			}
		});
	});
};

var ini_color = "#fff";

//入力フォーム必須チェック
function realtime_validate(check_elem, err_flg){

	var re_bool = true;

	//エラーの初期化
	if(err_flg === true){
		check_elem.removeClass('error-form');
		check_elem.siblings(".error").remove();
		check_elem.parents(".error-form").find('.error').remove();
	}


	var tag = check_elem.get(0).tagName;

	if(tag == 'INPUT' || tag == 'TEXTAREA' || tag == 'SELECT' ){

		var type = '';
		if(tag == 'INPUT'){
			type = check_elem.attr('type');
		}else{
			type = 'text';
		}

		if(type != 'radio' && type != 'checkbox' && type != 'file' && !check_elem.hasClass('couple') ){
			if(check_elem.hasClass('validate')){
				if(check_elem.hasClass('required')){
					if(check_elem.val()=="" || check_elem.val()==null){
						re_bool = disp_error_msg(check_elem, '必須項目です');
					}
				}

				if(check_elem.hasClass('number')){
					if(isNaN(check_elem.val())){
						re_bool = disp_error_msg(check_elem, '数値のみ入力可能です');
					}
				}
				if(check_elem.hasClass('mail')){
					if(check_elem.val() && !check_elem.val().match(/.+@.+\..+/g)){
						re_bool = disp_error_msg(check_elem, 'メールアドレスの形式が異なります');
					}
				}
				if(check_elem.hasClass('mail_check')){
					if(check_elem.val() && check_elem.val()!=$("input[name="+check_elem.attr("name").replace(/^(.+)_check$/, "$1")+"]").val()){
						re_bool = disp_error_msg(check_elem, 'メールアドレスと内容が異なります');
					}
				}
				if(type == 'email'){
					if(check_elem.hasClass('mail')){
						if(re_bool && check_elem.val() && !check_elem.val().match(/.+@.+\..+/g)){
							re_bool = disp_error_msg(check_elem, 'メールアドレスの形式が異なります');
						}
					}else if(check_elem.hasClass('eng')){
						if(check_elem.val() && check_elem.val().match(/[^A-Za-z0-9]+/)){
							re_bool = disp_error_msg(check_elem, '半角英数字のみ入力可能です');
						}
					}
				}

				if(type == 'tel'){
					if(check_elem.val().match(/[０-９]/) || check_elem.val().match(/[a-zA-Zぁ-ん]/)){
						re_bool = disp_error_msg(check_elem, '半角数値のみ入力可能です');
					}
					if(check_elem.val() && check_elem.val().match(/-/)){
						re_bool = disp_error_msg(check_elem, 'ハイフンは入れないでください');
					}
				}


				if(check_elem.hasClass('password')){
					if( check_elem.val() != null && check_elem.val() != ''){
						if( ((check_elem.val().length < 8 || check_elem.val().length > 20) || !check_elem.val().match(/[^a-zA-Z]/) || !check_elem.val().match(/[^0-9]/))){
							re_bool = disp_error_msg(check_elem, '半角英字と半角数字を含む8～20文字を設定して下さい');
						}
						if( check_elem.val().match(/[ぁ-んａ-ｚＡ-Ｚ]/) != null){
							re_bool = disp_error_msg(check_elem, '半角英字と半角数字を含む8～20文字を設定して下さい');
						}
					}
				}

				if(check_elem.hasClass('password_conf')){
					if(check_elem.val() && check_elem.val()!=$('[name=password]').val()){
						re_bool = disp_error_msg(check_elem, 'パスワードと内容が異なります');
					}
				}

				if(check_elem.hasClass('tel-text')){
					if (check_elem.val() && !check_elem.val().match(/^-?[\d-]*$/)) {
						re_bool = disp_error_msg(check_elem, '入力には数値と - のみを使用してください');
					}
				}

				if(check_elem.hasClass('url')){
					if (check_elem.val() && !check_elem.val().match(/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/)) {
						re_bool = disp_error_msg(check_elem, '有効なURLを入力してください');
					}
				}

				if(check_elem.hasClass('integer')){
					if(check_elem.val() && !isInteger(check_elem.val())){
						re_bool = disp_error_msg(check_elem, '数値のみ入力可能です');
					}
				}
			}
		}else if(type == "file"){

			if(check_elem.hasClass('required')){
				var data = check_elem.parents('.form-group').find('.file_name_area').attr('file_name');
				if(data == null || data == '' || data == undefined){
					re_bool = disp_error_msg(check_elem, 'ファイルを選択してください');
				}

			}

		}else if(type == "radio"){

			if(check_elem.hasClass('required')){
				if($(":radio[name="+check_elem.attr("name")+"]:checked").size() == 0){
					re_bool = disp_error_msg(check_elem.last(), '必須項目です');
				}
			}

		}else if(type == "checkbox"){

			//チェックボックスのチェック
			var checkbox_array = [];
			$(".checkboxRequired").each(function(){
				checkbox_array.push($(this).attr('name'));
			})

			// 重複を検出したものを重複しないでリスト
			var checkbox_array = checkbox_array.filter(function (x, i, self) {
				return self.indexOf(x) === i && i !== self.lastIndexOf(x);
			});

			for (var i = 0; i < checkbox_array.length; i++) {
				if(checkbox_array[i] == check_elem.attr('name')){
					if($('[name='+checkbox_array[i]+']:checked').length == 0){
						re_bool = disp_error_msg($('[name='+checkbox_array[i]+']').parents('.formTxt'), '最低1つ選択してください');
					}
				}
			}

			//利用規約に同意する
			if(check_elem.hasClass('privacy_policy')){
				if(!check_elem.prop('checked')){
					re_bool = disp_error_msg(check_elem, '利用規約に同意してください');
					check_elem.before("<span class='error'><label>利用規約に同意してください</label><br></span>");
				}
			}

		}else if(check_elem.hasClass('couple')){

			var couple = check_elem.attr('couple');

			var couple_flg = true;
			var target = '';
			var cnt = 0
			$('[couple="' + couple + '"]').each(function(i, elem){
				cnt++;
				if($(elem).val() == null || $(elem).val() == ''){
					couple_flg = false;
					target = $('[couple="' + couple + '"]').last();
				}
			})

			if(couple_flg === false){
				re_bool = disp_error_msg(target, cnt+"つのフォームは必須項目です");
			}
		}
		return re_bool;
	}

}


function disp_error_msg(elem, msg){

	var tag = elem.get(0).tagName;
	if(tag == 'INPUT' || tag == 'TEXTAREA' ){
		var type = '';
		if(tag == 'TEXTAREA'){
			var type =  'text';
		}else{
			var type = elem.attr('type');
		}

		if(type != 'radio' && type != 'checkbox' && !elem.hasClass('couple')){
			//通常のinput系
			elem.parent('div').append('<span class="error">'+msg+'</span>');
			elem.addClass('error-form');

		}else if(type == 'checkbox' ){
			elem.append('<span class="error">'+msg+'</span>');

		}else if( elem.hasClass('couple') ){

		}
	}else{
		if(tag == 'SELECT'){
			elem.parent('div').append('<span class="error">'+msg+'</span>');
			elem.addClass('error-form');
		}
	}

	return false;

}


//全体のバリデーション
function validate_all(flg){
	if(flg == undefined) flg = true;

	$(':text, :password, :radio, :file, :checkbox, [type="tel"], [type="email"], [type="date"], textarea, select').each(function(){
		if($(this).hasClass('validate required')){
			realtime_validate($(this), flg);
		}
	});

	//custom
	$('.checkbox-content').each(function(){
		if($(this).hasClass('validate required')){
			$(this).removeClass('error-form');
			$(this).closest('.panel-group').find('.error').remove();
			if ($(this).find('input[type=checkbox]:checked').length ===0)
			{
				$(this).closest('.panel-group').append('<span class="error">必須項目です</span>');
				$(this).addClass('error-form');
			}
		}
	});
	//custom

	//エラーの際の処理
	if($(".error-form").size() > 0){
		$('html,body').animate({ scrollTop: $(".error-form").offset().top-250 });

		return false;
	}
	return true;
}

function error_reset(){

	$(':text, :password, :radio, :file, :checkbox, [type="tel"], [type="email"], [type="date"], textarea, select').each(function(){
		var check_elem = $(this)
		check_elem.removeClass('error-form');
		check_elem.siblings(".error").remove();
		check_elem.parents(".error-form").find('.error').remove();
	});

}

function isInteger(value) {
	return !isNaN(value) && parseInt(value, 10) == value;
}


