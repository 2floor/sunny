$( document ).ready(function() {
    $('.loading').hide();

    $('#confirm_mm').off().on('click', function(){
        swal({
            title: "選択を確認する!",
            text: "現在のリンクを確認しますか?",
            type : "info",
            showCancelButton : true,
            confirmButtonClass : 'btn-info',
            confirmButtonText : "確認する",
            cancelButtonText : '戻る',
            closeOnConfirm : false,
            closeOnCancel : false
        }, function(isConfirm) {
            if (isConfirm) {
                const urlParams = new URLSearchParams(window.location.search);
                const cancer_id = urlParams.get('cancer_id') || null;
                const hospital_id = urlParams.get('hospital_id') || null;
                const request = [];

                $('.mm-info').each(function () {
                    let year = $(this).find('.yearMM').text().trim();
                    let search = $(this).find('.searchMM').val() || '';
                    request.push({
                        cancer_id : cancer_id,
                        hospital_id : hospital_id,
                        year : year,
                        search : search
                    });
                });

                var form_data = new FormData();
                form_data.append('method', 'update_mm_dpc');
                form_data.append('request', JSON.stringify(request));

                ajax.get(form_data).done(function(result) {
                    loaded();
                    if (result.data.status) {
                        swal({
                            title : "Success!",
                            text : result.data.msg,
                            type : "success",
                            confirmButtonText : "戻る",
                            closeOnConfirm : true
                        }, function() {
                            if (isConfirm) {
                                window.location.href = 'missmatch.php';
                            }
                        });
                    } else if (!result.data.status && result.data.error_code == 0) {
                        alert(result.data.error_msg);
                    }

                }).fail(function(result) {
                    // 異常終了
                    $('body').html(result.responseText);
                });
            } else {
                swal.close();
            }
        });
    });

    $('.searchMM').on('change', function () {
        const mm_info = $(this).parents('.mm-info');
        const year = mm_info.find('.yearMM').text().trim();
        const urlParams = new URLSearchParams(window.location.search);
        const cancer_id = urlParams.get('cancer_id') || null;
        const hospital_id = urlParams.get('hospital_id') || null;

        var form_data = new FormData();
        form_data.append('method', 'get_detail');
        form_data.append('edit_del_id', $(this).val());
        form_data.append('hospital_id', hospital_id);
        form_data.append('cancer_id', cancer_id);
        form_data.append('year', year);
        form_data.append('type', 1);

        ajax.get(form_data).done(function(result) {
            loaded();
            if (result.data.status) {
                const value = JSON.parse(result.data.data.import_value || '');
                mm_info.find('.dpcArea').text(result.data.data.area_id || '');
                mm_info.find('.dpcMM').text(value[2] || '');
                mm_info.find('.percentMM').text(result.data.isGetById ? '' : result.data.data.percent_match);
            } else if (!result.data.status && result.data.error_code == 0) {
                alert(result.data.error_msg);
            }
        }).fail(function(result) {
            $('body').html(result.responseText);
        });
    });
});