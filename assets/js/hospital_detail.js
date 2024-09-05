$(document).ready(function() {
    let queryString = window.location.search;
    let urlParams = new URLSearchParams(queryString);
    let id = urlParams.get('id');
    let cancerId = urlParams.get('cancerId');

    function printHospitalDetail(data) {
        $.ajax({
            url: '../../controller/front/f_hospital_ct.php',
            type: 'POST',
            data: {method: 'printHospitalList', selectedItems: data},
            beforeSend: function() {
                $('.loading-overlay').show();
            },
            success: function(response) {
                window.scrollTo(0, 0);
                $('.loading-overlay').hide();
                let res = JSON.parse(response);
                if (res.status === true) {
                    handlePrintPDF(res.data.pdfFiles ?? [], '../../')
                } else {
                    Swal.fire({
                        title: "エラー!",
                        text: "病院情報の印刷に失敗しました",
                        icon: "error",
                        confirmButtonText: "閉じる",
                        customClass: {
                            confirmButton: 'order-2',
                        }
                    });
                }
            },
            error: function() {
                $('.loading-overlay').hide();
                Swal.fire({
                    title: "エラー!",
                    text: "病院情報の印刷に失敗しました",
                    icon: "error",
                    confirmButtonText: "閉じる",
                    customClass: {
                        confirmButton: 'order-2',
                    }
                });
            }
        });
    }

    function updateRemarks(method, data) {
        $.ajax({
            url: '../../controller/front/f_hospital_ct.php',
            type: 'POST',
            data: {method: method, data : data},
            beforeSend: function() {
                $('.loading-overlay').show();
            },
            success: function(response) {
                $('.loading-overlay').hide();
                let res = JSON.parse(response);
                if (res.status === true) {
                    Swal.fire({
                        title: "完了!",
                        text: "正常に更新されました",
                        icon: "success",
                        confirmButtonText: "閉じる",
                        customClass: {
                            confirmButton: 'order-2',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#cardContainer').empty();
                            $('#text-content').val('');
                            console.log(res.data.length);
                            if (res.data.length !== 0) {
                                let html = '';
                                $('#NoMemoText').remove();
                                $.each(res.data, function(index, value) {
                                    html += `<div class="card">
                                            <div class="card-content">
                                                <div class="card-header">
                                                   <div class="author"><span>${value.author || ''}</span><span>作成日時: ${value.approved_time || ''}</span>${value.updated_at ? ('<span>更新日時: ' + value.updated_at + '</span>') : ''}</span></div>
                                                   <div class="card-actions">
                                                        <a class="btnEditMemo">
                                                            <img src="../../img/icons/edit-memo-icon.png" alt="alt">
                                                        </a>
                                                        <a class="btnSaveEditMemo" style="display: none" data-remark-id="${value.id}">
                                                            <img src="../../img/icons/blue-save.png" alt="alt">
                                                        </a>
                                                        <a class="btnDeleteMemo" data-remark-id="${value.id}">
                                                            <img src="../../img/icons/delete-memo-icon.png" alt="alt">
                                                        </a>
                                                   </div>
                                                </div>
                                                <div class="card-body">
                                                   <p>${value.remarks || ''}</p>
                                                </div>
                                            </div>
                                          </div>`;
                                });

                                $('#cardContainer').append(html);
                            } else {
                                $('#cardContainer').append('<p id="NoMemoText">まだメモを追加していません!</p>');
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: "エラー!",
                        text: "アップデートに失敗しました",
                        icon: "error",
                        confirmButtonText: "Ok"
                    });
                }
            },
            error: function() {
                $('.loading-overlay').hide();
                Swal.fire({
                    title: "エラー!",
                    text: "アップデートに失敗しました",
                    icon: "error",
                    confirmButtonText: "Ok"
                });
            }
        });
    }

    $('#collapseOne').prev('.panel-heading').find('.arrow').toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
    $('#collapseOne').prev('.panel-heading').addClass('active');

    $('#accordion').on('show.bs.collapse', function(e) {
        $(e.target).prev('.panel-heading').addClass('active');
        $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    });

    $('#accordion').on('hide.bs.collapse', function(e) {
        $(e.target).prev('.panel-heading').removeClass('active');
        $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    });

    $('#printButton').on('click', function () {
        Swal.fire({
            title: "この病院の情報を印刷しますか？",
            icon: "question",
            showDenyButton: true,
            showCloseButton: true,
            confirmButtonText: "印刷",
            denyButtonText: `戻る`,
            customClass: {
                actions: 'print-confirm',
                confirmButton: 'order-2',
                denyButton: 'order-1'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let printHospitalList = [{
                    hospitalId : id,
                    cancerId : cancerId
            }];

                printHospitalDetail(printHospitalList);
            }
        });
    });

    $('#btnSaveMemo').on('click', function() {
        let textContent = $('#text-content');
        let newContent = textContent.val();
        Swal.fire({
            title: "新しいメモを追加しましたか？",
            icon: "question",
            showCloseButton: true,
            showDenyButton: true,
            confirmButtonText: "Ok",
            denyButtonText: `戻る`,
            customClass: {
                actions: 'print-confirm',
                confirmButton: 'order-2',
                denyButton: 'order-1'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateRemarks('createRemark', {remarks : newContent, hospitalId : id});
            }
        });
    });

    function getSectionNavImageSrc(id, isHover) {
        let base = isHover ? '-hover-icon.png' : '-icon.png';

        switch (id) {
            case 'navDpcTb':
                return 'bed' + base;
            case 'navStageTb':
                return 'list' + base;
            default:
                return 'healthy' + base;
        }
    }

    $('.nav-section a').hover(
        function () {
            let id = $(this).attr('id');
            let src = '../../img/icons/' + getSectionNavImageSrc(id, true);
            $(this).find('img').attr('src', src);
        },
        function () {
            if (!$(this).hasClass('nav-section-active')) {
                let id = $(this).attr('id');
                let src = '../../img/icons/' + getSectionNavImageSrc(id, false);
                $(this).find('img').attr('src', src);
            }
        }
    );

    $('.nav-section a').on('click', function () {
        $('.nav-section a').removeClass('nav-section-active');
        $(this).addClass('nav-section-active');

        $('.nav-section a').each(function () {
            let id = $(this).attr('id');
            let isActive = $(this).hasClass('nav-section-active');
            let src = '../../img/icons/' + getSectionNavImageSrc(id, isActive);
            $(this).find('img').attr('src', src);
        });
    });

    $(document).on('click', '.btnEditMemo', function() {
        let pTag = $(this).closest('.card-header').next().find('p');
        let currentText = pTag.text();
        let currentWidth = pTag.outerWidth();
        let textArea = createTextArea(currentText, currentWidth);
        pTag.replaceWith(textArea);
        textArea.focus().css('height', '150px');
        $(this).next().show();
        $(this).hide();
    });

    $(document).on('click', '.btnSaveEditMemo', function() {
        let idRemarks = $(this).data('remark-id');
        let areaTag = $(this).closest('.card-header').next().find('textarea');
        let currentText = areaTag.val();

        Swal.fire({
            title: "メモを更新しますか？",
            icon: "question",
            showCloseButton: true,
            showDenyButton: true,
            confirmButtonText: "Ok",
            denyButtonText: `戻る`,
            customClass: {
                actions: 'print-confirm',
                confirmButton: 'order-2',
                denyButton: 'order-1'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateRemarks('updateRemark', {remarks : currentText, id : idRemarks, hospitalId : id, updateType: 'update'});
            }
        });
    });

    $(document).on('click', '.btnDeleteMemo', function() {
        let idRemarks = $(this).data('remark-id');

        Swal.fire({
            title: "メモを削除しますか？",
            icon: "question",
            showCloseButton: true,
            showDenyButton: true,
            confirmButtonText: "Ok",
            denyButtonText: `戻る`,
            customClass: {
                actions: 'print-confirm',
                confirmButton: 'order-2',
                denyButton: 'order-1'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateRemarks('updateRemark', {id : idRemarks, hospitalId : id, updateType: 'delete'});
            }
        });
    });

    function createTextArea(text, width) {
        return $('<textarea>')
            .html(text)
            .css({
                'width': width + 'px',
                'height': '20px',
                'transition': 'height 1s ease'
            })
            .attr('id', 'text-content')
            .addClass('text-with-lines');
    }
});