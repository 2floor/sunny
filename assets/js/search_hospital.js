function renderPDF(url, canvasContainer) {
    const loadingTask = pdfjsLib.getDocument(url);
    loadingTask.promise.then(pdf => {
        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            pdf.getPage(pageNum).then(page => {
                const viewport = page.getViewport({ scale: 1.5 });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                canvasContainer.appendChild(canvas);

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        }
    }, function(reason) {
        console.error(reason);
    });
}
$(document).ready(function () {
    handlePopupClick('#cancerType', '#cancerPopup');
    $('.search-hospital-footer').hide()

    let cancerTypeChecked = []
    let cancerStageChecked = []
    let areaChecked = {}
    let categoryChecked = []

    $('.area-selection').select2({
        placeholder: '都道府県を選択',
        allowClear: true
    });

    function handlePopupClick(targetId, popupId, hiddenPopupId = null) {
        $('.popup').each(function() {
            if ($(this).css('display') === 'block') {
                let data = {
                    idPopup: '#' + $(this).attr('id'),
                };

                $(document).trigger('popupClosed', data);
            }
        });

        $('.popup').not(popupId).hide();
        let popup = $(popupId);
        let isHidden = (popup.css('display') === 'none');
        popup.fadeToggle();

        $('.filter-group .show-popup .toggle').text('+');
        let toggleIcon = $(targetId).find('.toggle');

        if (isHidden) {
            $('.popup-container').show();
            toggleIcon.text('—');
        } else {
            $('.popup-container').hide();
            toggleIcon.text('+');

            if (!hiddenPopupId) {
                let data = {
                    idPopup: popupId,
                };

                $(document).trigger('popupClosed', data);
            }
        }

        if (hiddenPopupId) {
            let data = {
                idPopup: hiddenPopupId
            };

            $(document).trigger('popupClosed', data);
        }
    }

    function initPagination() {
        if (cancerTypeChecked.length === 0) {
            Swal.fire({
                icon: "error",
                title: 'エラー!',
                text: '少なくとも1種類のがんを選択する必要があります',
            });
        } else if (pageType == 'second-search' && cancerStageChecked.length === 0) {
            Swal.fire({
                icon: "error",
                title: 'エラー!',
                text: 'がんのステージを少なくとも 1 つ選択する必要があります',
            });
        } else {
            let keyword = $('input#keyword').val();
            let sort = $('input[name="sort"]:checked').val()
            let postData = {
                method: 'searchHospitalList',
                data: {
                    'cancer': cancerTypeChecked,
                    'stage': cancerStageChecked,
                    'area': areaChecked,
                    'category': flattenArray(categoryChecked),
                    'keyword': keyword,
                    'sort': sort
                }
            };

            let uri = toURI(postData);
            $('#pagination-container').pagination({
                dataSource: '../controller/front/f_hospital_ct.php?' + uri,
                locator: 'data.html',
                totalNumberLocator: function(response) {
                    return response.data.html[1];
                },
                pageSize: 5,
                autoHidePrevious: true,
                autoHideNext:true,
                showSizeChanger: true,
                sizeChangerOptions: [5, 10, 20],
                ajax: {
                    beforeSend: function() {
                        $('.loading-overlay').show();
                    }
                },
                callback: function(data) {
                    $('.loading-overlay').hide();
                    $('.checkbox-print-all').prop('checked', false);

                    if (data[0]) {
                        $('.total-result').show()
                        $('.total-result span').text(data[1] + '件')
                        $('.hospital-list').html('').append(data[0]);
                        $('.search-hospital-footer').show()
                    } else {
                        $('.hospital-list').html('').append('<div class="hospital-no-data"><div class="no-data-message text-danger">一致するデータが見つかりません</div></div>');
                        $('.search-hospital-footer').hide()
                        $('.total-result').hide()
                    }
                }
            });
        }
    }

    function flattenArray(nestedArray) {
        let flatArray = [];
        for (let key in nestedArray) {
            if (nestedArray.hasOwnProperty(key)) {
                flatArray = flatArray.concat(nestedArray[key]);
            }
        }
        return flatArray;
    }

    function toURI(postData) {
        let method = postData.method;
        let data = postData.data;
        let uri = `method=${encodeURIComponent(method)}`;

        function encodeArray(key, array) {
            return array.map(item => `${encodeURIComponent(key)}[]=${encodeURIComponent(item)}`).join('&');
        }

        function encodeObject(key, obj) {
            let parts = [];
            for (let subKey in obj) {
                if (obj.hasOwnProperty(subKey) && Array.isArray(obj[subKey])) {
                    parts.push(obj[subKey].map(item => `${encodeURIComponent(key)}[${encodeURIComponent(subKey)}][]=${encodeURIComponent(item)}`).join('&'));
                }
            }
            return parts.join('&');
        }

        for (let key in data) {
            if (data.hasOwnProperty(key)) {
                if (Array.isArray(data[key])) {
                    uri += `&${encodeArray(key, data[key])}`;
                } else if (typeof data[key] === 'object' && data[key] !== null) {
                    uri += `&${encodeObject(key, data[key])}`;
                } else {
                    uri += `&${encodeURIComponent(key)}=${encodeURIComponent(data[key])}`;
                }
            }
        }

        return uri;
    }


    $('.popup-close').on('click', function () {
        let popup = $(this).closest('.popup')
        popup.fadeOut()
        $('.popup-container').hide()
        $('.filter-group .show-popup .toggle').text('+');

        let data = {
            idPopup: '#' + popup.attr('id')
        };
        $(document).trigger('popupClosed', data);
    })

    $('#area').on('click', function () {
        handlePopupClick('#area', '#areaPopup');
    });

    $('#cancerType').on('click', function () {
        handlePopupClick('#cancerType', '#cancerPopup');
    });

    $('#cancerStage').on('click', function () {
        handlePopupClick('#cancerStage', '#cancerStagePopup');
    });

    $('.show-popup-dynamic').on('click', function () {
        order = $(this).data('order');
        handlePopupClick('#' + $(this).attr('id'), '#categoryPopup-' + order);
    });

    $('#cancerPopup .open-next-popup').on('click', function () {
        if (pageType == 'first-search') {
            handlePopupClick('#area', '#areaPopup', '#cancerPopup');
        }

        if (pageType == 'second-search') {
            handlePopupClick('#cancerStage', '#cancerStagePopup', '#cancerPopup');
        }
    });

    $('#cancerStagePopup .open-previous-popup').on('click', function () {
        handlePopupClick('#cancerType', '#cancerPopup', '#cancerStagePopup');
    });

    $('#cancerStagePopup .open-next-popup').on('click', function () {
        handlePopupClick('#area', '#areaPopup', '#cancerStagePopup');
    });

    $('#areaPopup .open-previous-popup').on('click', function () {
        if (pageType == 'first-search') {
            handlePopupClick('#cancerType', '#cancerPopup', '#areaPopup');
        }

        if (pageType == 'second-search') {
            handlePopupClick('#cancerStage', '#cancerStagePopup', '#areaPopup');
        }
    });

    $('#areaPopup .open-next-popup').on('click', function () {
        handlePopupClick('#category-1', '#categoryPopup-1', '#areaPopup');
    });

    $('.popup-dynamic .open-previous-popup').on('click', function () {
        order = $(this).closest('.popup-dynamic').data('order');
        if (order <= 1) {
            handlePopupClick('#area', '#areaPopup', '#categoryPopup-'+order);
        } else {
            previousOrder = order - 1
            handlePopupClick('#category-'+previousOrder, '#categoryPopup-'+previousOrder, '#categoryPopup-'+order);
        }
    });

    $('.popup-dynamic .open-next-popup').on('click', function () {
        order = $(this).closest('.popup-dynamic').data('order');
        nextOrder = order + 1
        handlePopupClick('#category-'+nextOrder, '#categoryPopup-'+nextOrder, '#categoryPopup-'+order);
    });

    $('#cancerPopup .clear-data').on('click', function () {
        $('#cancerPopup input[type="checkbox"]').prop('checked', false);
    });

    $('#cancerStagePopup .clear-data').on('click', function () {
        $('#cancerStagePopup input[type="checkbox"]').prop('checked', false);
    });

    $('#areaPopup .clear-data').on('click', function () {
        $('#areaPopup input[type="checkbox"]').prop('checked', false);
        $('.area-selection').val(null).trigger('change');
    });

    $('.popup-dynamic .clear-data').on('click', function () {
        order = $(this).closest('.popup-dynamic').data('order');

        $('#categoryPopup-'+order+' input[type="checkbox"]').prop('checked', false);
    });

    $('#expandedSearchFilter').on('click', function () {
        $('.search-filter').toggleClass('expanded');
    });

    $('.search-hospital').on('click', function () {
        if ($(this).hasClass('end-popup')) {
            order = $(this).closest('.popup-dynamic').data('order');
            handlePopupClick('#category-' + order, '#categoryPopup-' + order);
        } else {
            $('.popup').each(function() {
                if ($(this).css('display') === 'block') {
                    let data = {
                        idPopup: '#' + $(this).attr('id'),
                    };

                    $(document).trigger('popupClosed', data);
                }
            });

            $('.popup').hide();
            $('.popup-container').hide();
        }

        initPagination()
    });

    $('.checkbox-print-all').change(function() {
        if($(this).is(':checked')) {
            $('.checkbox-print').prop('checked', true);
        } else {
            $('.checkbox-print').prop('checked', false);
        }
    });

    $('#cancerPopup input[type=checkbox]').on('click', function () {
        $('#cancerPopup input[type=checkbox]').not($(this)).prop('checked', false)
    })

    $('#cancerStagePopup input[type=checkbox]').on('click', function () {
        $('#cancerStagePopup input[type=checkbox]').not($(this)).prop('checked', false)
    })

    $('#areaPopup input[type=checkbox]').on('change', function () {
        let select = $(this).closest('.form-group').find('select')

        if ($(this).is(':checked')) {
            let optionValues = [];
            select.find('option').each(function () {
                optionValues.push($(this).val());
            });
            select.val(optionValues).trigger('change');
        } else {
            select.val([]).trigger('change');
        }
    })

    $('#areaPopup select').on('change', function () {
        let checkbox = $(this).closest('.form-group').find('input[type=checkbox]')
        let selectedValues = $(this).val() || [];
        var allSelected = true;
        $(this).find('option').each(function () {
            if (selectedValues.indexOf($(this).val()) === -1) {
                allSelected = false;
                return false;
            }
        });

        checkbox.prop('checked', allSelected);
    });

    $('#printButton').on('click', function () {
        if ($('.checkbox-print:checked').length == 0) {
            Swal.fire({
                icon: "error",
                title: 'エラー!',
                text: "印刷する病院が選択されていません",
            });
        } else {
            let html = '';
            $('.checkbox-print:checked').each(function () {
                html += $(this).closest('.hospital-card').find('.hospital-info h2').text();
                html += '<br>'
            })

            // Swal.fire({
            //     title: "下記のページを印刷しますか？",
            //     icon: "question",
            //     html: html,
            //     showDenyButton: true,
            //     confirmButtonText: "Ok",
            //     denyButtonText: `キャンセル`
            // });

            $.ajax({
                url: '../controller/front/f_hospital_ct.php',
                type: 'POST',
                data: {method: 'printHospitalList', selectedItems: [{hospitalId : 1}, {hospitalId : 2}]},
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.status === 'success') {

                        $('#pdfViewer').empty();
                        res.pdfFiles.forEach(function(pdfFile) {
                            const container = $('<div>', {
                                class: 'pdf-container'
                            });
                            $('#pdfViewer').append(container);
                            renderPDF(pdfFile, container[0]);
                        });
                        $('#pdfViewer').show();
                    }
                }
            });
        }
    });

    $(document).on('popupClosed', function (e, data) {
        let idFilter = ''
        let idPopup = data.idPopup

        if (idPopup === '#cancerPopup') {
            let span = ''
            idFilter = '#cancerType'
            cancerTypeChecked = []

            let filterContent = $(idFilter).parent().find('.filter-content')
            filterContent.html('')
            $(idPopup + ' .popup-checkbox-content input:checked').each(function () {
                cancerTypeChecked.push($(this).data('key'))
                span += '<span>' + $(this).data('value') + '</span>'
            });

            filterContent.append(span)
        }

        if (idPopup === '#cancerStagePopup') {
            let span = ''
            idFilter = '#cancerStage'
            cancerStageChecked  = []

            let filterContent = $(idFilter).parent().find('.filter-content')
            filterContent.html('')
            $(idPopup + ' .popup-checkbox-content input:checked').each(function () {
                cancerStageChecked.push($(this).data('key'))
                span += '<span>' + $(this).data('value') + '</span>'
            });

            filterContent.append(span)
        }

        if (idPopup.includes("#categoryPopup")) {
            let infoPopup = idPopup.split('-');
            let span = ''
            idFilter = '#category-' + infoPopup[1]
            categoryChecked[infoPopup[1]] = []

            let filterContent = $(idFilter).parent().find('.filter-content')
            filterContent.html('')
            $(idPopup + ' .popup-checkbox-content input:checked').each(function () {
                categoryChecked[infoPopup[1]].push($(this).data('key'))
                span += '<span>' + $(this).data('value') + '</span>'
            });

            filterContent.append(span)
        }

        if (idPopup === '#areaPopup') {
            areaChecked = {}
            idFilter = '#area'

            let region = []
            let area = []
            let totalRegion = $(idPopup + ' .popup-selection-content input[type=checkbox]').length
            let filterContent = $(idFilter).parent().find('.filter-content')
            filterContent.html('')

            $(idPopup + ' .popup-selection-content input:checked').each(function () {
                region.push($(this).data('value'))
            });

            if (region.length === totalRegion) {
                areaChecked.region = region = ['全国']
            } else {
                areaChecked.region = region
            }

            areaChecked.area = []

            $('.area-selection option:selected').each(function () {
                let mainRegion = $(this).closest('.form-group').find('input[type="checkbox"]').data('value');

                if (JSON.stringify(areaChecked['region']) !== JSON.stringify(['全国']) && (areaChecked.region.indexOf(mainRegion) === -1)) {
                    areaChecked.area.push($(this).val())
                    area.push($(this).text())
                }
            });

            region.forEach(function (value) {
                filterContent.append('<span>' + value + '</span>')
            });

            area.forEach(function (value) {
                filterContent.append('<span>' + value + '</span>')
            });
        }
    });
});