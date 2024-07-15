$(document).ready(function () {
    let cancerTypeChecked = []
    let areaChecked = {}
    let categoryChecked = []
    let totalPages = 0;
    let ajax = {
        get: function(post_data) {
            let defer = $.Deferred();
            $.ajax({
                type: 'GET',
                url: '../controller/front/f_hospital_ct.php',
                data: post_data,
                dataType: 'json',
                success: defer.resolve,
                error: defer.reject,
            });
            return defer.promise();
        }
    };

    $('.area-selection').select2({
        placeholder: '州を選択',
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

    function handleSearchHospital(pageNumber = 1) {
        if (cancerTypeChecked.length === 0) {
            swal({
                title: 'エラー!',
                text: '少なくとも1種類のがんを選択する必要があります',
                type: "error",
                confirmButtonClass: 'btn-primary',
                confirmButtonText: "OK",
            });
        } else {
            let keyword = $('input#keyword').val();
            let postData = {
                method: 'searchHospitalList',
                data: {
                    'cancer': cancerTypeChecked,
                    'area': areaChecked,
                    'category': categoryChecked,
                    'keyword': keyword,
                    'page': pageNumber,
                    'limit': 5
                }
            };
            $('.loading-overlay').show();

            ajax.get(postData).done(function(result) {
                $('.loading-overlay').hide();

                if (result.status && result.data.html) {
                    $('.hospital-list').html('').append(result.data.html);

                    if (totalPages !== result.data.totalPages) {
                        totalPages = result.data.totalPages;
                        initPagination(totalPages);
                    }
                    $('.search-hospital-footer').show()
                } else if (!result.data.html) {
                    $('.hospital-list').html('').append('<div class="hospital-no-data"><div class="no-data-message text-danger">一致するデータが見つかりません</div></div>');
                    totalPages = 0
                    $('.search-hospital-footer').hide()
                } else {
                    $('.hospital-list').html('').append('<div class="hospital-no-data"><div class="no-data-message text-danger">エラーが発生しました</div></div>');
                    totalPages = 0
                    $('.search-hospital-footer').hide()
                }
            }).fail(function() {
                $('.loading-overlay').hide();
                $('.hospital-list').html('').append('<div class="hospital-no-data"><div class="no-data-message text-danger">エラーが発生しました</div></div>');
                totalPages = 0
                $('.search-hospital-footer').hide()
            });
        }
    }

    function initPagination(totalPages) {
        $('#pagination-container').pagination({
            dataSource: new Array(totalPages).fill(1),
            pageSize: 1,
            autoHidePrevious: true,
            autoHideNext:true,
            callback: function(data, pagination) {
                handleSearchHospital(pagination.pageNumber);
            }
        });
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

    $('.show-popup-dynamic').on('click', function () {
        order = $(this).data('order');
        handlePopupClick('#' + $(this).attr('id'), '#categoryPopup-' + order);
    });

    $('#cancerPopup .open-next-popup').on('click', function () {
        handlePopupClick('#area', '#areaPopup', '#cancerPopup');
    });

    $('#areaPopup .open-previous-popup').on('click', function () {
        handlePopupClick('#cancerType', '#cancerPopup', '#areaPopup');
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

        handleSearchHospital()
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