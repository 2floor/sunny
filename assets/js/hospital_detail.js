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
});