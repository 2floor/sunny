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
                        confirmButtonText: "Ok"
                    });
                }
            },
            error: function() {
                $('.loading-overlay').hide();
                Swal.fire({
                    title: "エラー!",
                    text: "病院情報の印刷に失敗しました",
                    icon: "error",
                    confirmButtonText: "Ok"
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
                    console.log($("input[name='remarks']"))
                    $("input[name='remarks']").val(res.data.remarks);
                    Swal.fire({
                        title: "完了!",
                        text: "正常に更新されました",
                        icon: "success",
                        confirmButtonText: "Ok"
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
            title: "下記のページを印刷しますか？",
            icon: "question",
            showDenyButton: true,
            confirmButtonText: "Ok",
            denyButtonText: `キャンセル`,
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

    $('.btn-edit-memo').on('click', function() {
        let textContent = $('#text-content');

        if (textContent.is("textarea")) {
            let newContent = textContent.val();
            Swal.fire({
                title: "備考を変更しますか？",
                icon: "question",
                showDenyButton: true,
                confirmButtonText: "Ok",
                denyButtonText: `キャンセル`,
                customClass: {
                    actions: 'print-confirm',
                    confirmButton: 'order-2',
                    denyButton: 'order-1'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateRemarks({remarks : newContent, hospitalId : id});
                    textContent.replaceWith(createTextDiv(newContent));
                } else {
                    textContent.replaceWith(createTextDiv($("input[name='remarks']").val()));
                }

                $(this).find('.text').text('印刷');
                $(this).find('img').attr('src','../../img/icons/green-edit.png');
            });
        } else {
            let currentText = textContent.text();
            let currentWidth = textContent.outerWidth();
            textContent.replaceWith(createTextArea(currentText, currentWidth));
            $(this).find('.text').text('保存');
            $(this).find('img').attr('src','../../img/icons/green-save.png');
        }
    });

    function createTextArea(text, width) {
        return $('<textarea>')
            .val(text)
            .css({
                'width': width + 'px',
            })
            .attr('id', 'text-content')
            .addClass('text-with-lines');
    }

    function createTextDiv(text) {
        return $('<div>')
            .text(text)
            .attr('id', 'text-content')
            .addClass('text-with-lines');
    }
});