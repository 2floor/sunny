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

    function updateRemarks(data) {
        $.ajax({
            url: '../../controller/front/f_hospital_ct.php',
            type: 'POST',
            data: {method: 'updateRemarks', data : data},
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
                            let html = `<div class="card">
                                                <div class="card-content">
                                                    <div class="card-header">
                                                       <div class="author"><span>${res.data.author || ''}</span><span>${res.data.approved_time || ''}</span></div>
                                                       <div class="card-actions">
                                                            <a id="btnEditMemo">
                                                                <img src="../../img/icons/edit-memo-icon.png" alt="alt">
                                                            </a>
                                                            <a id="btnDeleteMemo">
                                                                <img src="../../img/icons/delete-memo-icon.png" alt="alt">
                                                            </a>
                                                       </div>
                                                    </div>
                                                    <div class="card-body">
                                                       <p>${res.data.remarks || ''}</p>
                                                    </div>
                                                </div>
                                              </div>`;

                            $('#cardContainer').append(html);
                            $('#NoMemoText').remove();
                            $('#text-content').val('');
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
                updateRemarks({remarks : newContent, hospitalId : id});
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
});