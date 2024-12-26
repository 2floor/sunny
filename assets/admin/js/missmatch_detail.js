$(document).ready(function () {
    const DPC_TYPE = 1;
    const STAGE_TYPE = 2;
    const SURVIVAL_TYPE = 3;
    const TYPE_LIST = [DPC_TYPE, STAGE_TYPE, SURVIVAL_TYPE];

    const param = getUrlParams();

    if (!TYPE_LIST.includes(param.type)) {
        window.location.href = 'missmatch.php';
    }

    let parent_file = 'missmatch.php';
    if (param.type === DPC_TYPE) {
        parent_file = 'missmatch_dpc.php';
    } else if (param.type === STAGE_TYPE) {
        parent_file = 'missmatch_stage.php';
    } else if (param.type === SURVIVAL_TYPE) {
        parent_file = 'missmatch_surv.php';
    }


    $('.loading').hide();

    const menu_a = $('li.has_sub').find('a[href*="'+parent_file+'"]');
    menu_a.addClass('active');
    menu_a.parent().addClass('active');
    menu_a.parents('.has_sub').find('.waves-effect').addClass('active subdrop');
    menu_a.parents('.list-unstyled').show();

    function showAlert(type, title, text, confirmText, callback) {
        swal({
            title: title,
            text: text,
            type: type,
            showCancelButton: true,
            confirmButtonClass: type === "error" ? 'btn-danger' : 'btn-info',
            confirmButtonText: confirmText,
            cancelButtonText: '戻る',
            closeOnConfirm: false,
            closeOnCancel: false
        }, callback);
    }

    function sendAjax(data, successCallback, failCallback) {
        ajax.get(data).done(successCallback).fail(failCallback || function (result) {
            $('body').html(result.responseText);
        });
    }

    function reloadPage() {
        window.location.reload();
    }

    function getUrlParams() {
        const urlParams = new URLSearchParams(window.location.search);
        return {
            cancer_id: urlParams.get('cancer_id') ? parseInt(urlParams.get('cancer_id')) : null,
            hospital_id: urlParams.get('hospital_id') ? parseInt(urlParams.get('hospital_id')) : null,
            type: urlParams.get('type') ? parseInt(urlParams.get('type')) : null
        };
    }

    $('#confirm_mm').off().on('click', function () {
        showAlert("info", "よろしいですか？", "各年度の医療機関名と基本の医療機関名の紐付けを設定します。", "確認する", function (isConfirm) {
            if (isConfirm) {
                const { cancer_id, hospital_id, type } = getUrlParams();
                const request = $('.mm-info').map(function () {
                    return {
                        cancer_id,
                        hospital_id,
                        type,
                        year: $(this).find('.yearMM').text().trim(),
                        search: $(this).find('.searchMM').val() || ''
                    };
                }).get();

                let formData = new FormData();
                formData.append('method', 'update_mm');
                formData.append('request', JSON.stringify(request));

                sendAjax(formData, function (result) {
                    loaded();
                    if (result.data.status) {
                        swal({
                            title: "Success!",
                            text: result.data.msg,
                            type: "success",
                            confirmButtonText: "戻る",
                            closeOnConfirm: true
                        }, function () {
                            window.location.href = parent_file;
                        });
                    } else {
                        alert(result.data.error_msg);
                    }
                });
            } else {
                swal.close();
            }
        });
    });

    $('.searchMM').on('change', function () {
        const mm_info = $(this).parents('.mm-info');
        const { cancer_id, hospital_id, type } = getUrlParams();
        const year = mm_info.find('.yearMM').text().trim();

        let formData = new FormData();
        formData.append('method', 'get_detail');
        formData.append('edit_del_id', $(this).val());
        formData.append('hospital_id', hospital_id);
        formData.append('cancer_id', cancer_id);
        formData.append('year', year);
        formData.append('type', type);

        sendAjax(formData, function (result) {
            loaded();
            if (result.data.status) {
                mm_info.find('.dpcArea').text(result.data.data.area_id || '');
                mm_info.find('.dpcMM').html(result.data.importValue || '');
                mm_info.find('.percentMM').text(result.data.isGetById ? '' : result.data.data.percent_match);
            } else {
                alert(result.data.error_msg);
            }
        });
    });

    $('.remove-icon').on('click', function () {
        const mm_info = $(this).parents('.mm-info');
        const { cancer_id, hospital_id, type } = getUrlParams();
        const year = mm_info.find('.yearMM').text().trim();

        showAlert("error", "削除の確認!", "このリンクされたデータを破棄してもよろしいですか?", "確認する", function (isConfirm) {
            if (isConfirm) {
                let formData = new FormData();
                formData.append('method', 'cancel_list');
                formData.append('hospital_id', hospital_id);
                formData.append('cancer_id', cancer_id);
                formData.append('year', year);
                formData.append('type', type);

                sendAjax(formData, function (result) {
                    loaded();
                    if (result.data.status) {
                        swal({
                            title: "Success!",
                            text: result.data.msg,
                            type: "success",
                            confirmButtonText: "戻る",
                            closeOnConfirm: true
                        }, reloadPage);
                    } else {
                        alert(result.data.error_msg);
                    }
                });
            } else {
                swal.close();
            }
        });
    });

    $('#cancer_all_mm').off().on('click', function () {
        const { cancer_id, hospital_id, type } = getUrlParams();
        const year = $('.mm-info').map(function () {
            return $(this).find('.yearMM').text().trim();
        }).get();

        showAlert("error", "削除の確認!", "リンクされたデータをすべて破棄しますか?", "確認する", function (isConfirm) {
            if (isConfirm) {
                let formData = new FormData();
                formData.append('method', 'cancel_list');
                formData.append('hospital_id', hospital_id);
                formData.append('cancer_id', cancer_id);
                formData.append('year', JSON.stringify(year));
                formData.append('type', type);

                sendAjax(formData, function (result) {
                    loaded();
                    if (result.data.status) {
                        swal({
                            title: "Success!",
                            text: result.data.msg,
                            type: "success",
                            confirmButtonText: "戻る",
                            closeOnConfirm: true
                        }, function () {
                            window.location.href = 'missmatch.php';
                        });
                    } else {
                        alert(result.data.error_msg);
                    }
                });
            } else {
                swal.close();
            }
        });
    });
});