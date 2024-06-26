$(function() {
	$loading.addClass("is-hide");
	$('#form_submit').on('click', function(){

		$('#error_area').empty();

		//変数storageにsessionStorageを格納
		var storage = sessionStorage;

		$.ajax({
			type : 'POST',
			url : $('#ct_url').val(),//コントローラURLを取得
			dataType: 'json',
			data: {
				'method' : $(this).attr('method'),//コントローラ内での処理判断用
				'id' : $('#form_id').val(),//パラメータ
				'pass' : $('#form_pw').val()//パラメータ
			},
			beforeSend:function(){
				$(".loading").removeClass("is-hide");
			},
		}).done(function(result, datatype){
			var result_flg = result.data.status;
			if (result_flg) {

				//sessionStorage初期化
				storage.removeItem('cms_user_id');

				//useridをsessionStorageに設定
				storage.setItem('cms_user_id', result.data.id);

				//処理正常終了時
				location.href = 'menu.php';

			} else {

				$(".loading").addClass("is-hide");
				//sessionStorage初期化
				storage.removeItem('cms_user_id');

				//処理以上終了時
				$('#error_area').html('<span class="error">'+result.data.msg+'<span>');
//				$.unblockUI();
			}
		}).fail(function(XMLHttpRequest, textStatus, errorThrown) {
			//異常終了時
			$.unblockUI();
			alert('処理が正常に行えませんでした。\r\nシステム管理者に問合せをして下さい。');

			$('body').html(XMLHttpRequest.responseText)
		});
        //サブミット後、ページをリロードしないようにする
        return false;
	});
});
