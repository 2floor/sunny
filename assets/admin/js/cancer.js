/**
 * フォームの表示、非表示処理はassets/admin/js/common.jsの共通関数を読み込んでます。
 *
 * ページの状態 一覧表示：.list_show 新規登録入力：.entry_input 新規登録確認：.entry_conf
 * 編集入力：.edit_input 編集確認：.edit_conf 削除確認：.del_conf
 *
 * 表示状態 一覧表示：.list_show_disp 新規登録入力：.entry_input 新規登録確認：.entry_conf
 * 編集入力：.edit_input 編集確認：.edit_conf 削除確認：.del_conf
 *
 * ページタイトル : {String} page_title
 * 画像用ネーム : {Array} input_file_name = { jq_id : input_name }
 */

//パラメータ取得
var query = getUrlVars();

//ページタイトル
var page_title = 'がんの種類';

//画像input用配列
var input_file_name = {};

var search_select = {
    selectArea : {
        //検索項目生成用
        'ID' : {
            search 		: true,
            order		: true,
            orderInit	: false,
            ColName 	: 'id',
            tableOrder 	: 1,
            type 		: 'bigint',
        },
        'がん種名' : {
            search 		: true,
            order		: true,
            ColName 	: 'cancer_type',
            tableOrder 	: 2,
            type 		: 'text',
        },
        'がん種(DPC)' : {
            search 		: true,
            order		: true,
            ColName 	: 'cancer_type_dpc',
            tableOrder 	: 3,
            type 		: 'text',
        },
        'がん種(Stage)' : {
            search 		: true,
            order		: true,
            ColName 	: 'cancer_type_stage',
            tableOrder 	: 4,
            type 		: 'text',
        },
        'がん種(Surv)' : {
            search 		: true,
            order		: true,
            ColName 	: 'cancer_type_surv',
            tableOrder 	: 5,
            type 		: 'text',
        },
        '表示順' : {
            search 		 : false,
            order		 : true,
            ColName 	 : 'order_num',
            tableOrder 	 : 6,
            type 		 : 'int',
        },
        '更新日時' : {
            search 		: false,
            order		: true,
            ColName 	: 'updated_at',
            tableOrder 	: 8,
            type 		: 'date',
        },
    },
    //検索時内容
    value : {},
    order : {}
};


//初回定義
var call_ajax_init;
var call_ajax_edit_init;
var now_page_num_ini, page_num_ini, page_disp_cnt_ini;

var state = {
    "actType": 'init',
    "elemName" : $(this).attr('name'),
};
history.replaceState(state, null, null);

$(window).on('bb');
$(window).on('popstate.bb',function(e) {
    var state = e.originalEvent.state; // pushState()で渡しておいたstateオブジェクトを取得する
    if (state) {
        $('html, body').scrollTop(0);
        if(state.search_select){
            search_select = state.search_select;
            $('[name=search_input]').val(search_select.value.value);
        }
        if(state.actType == 'init'){
            $('#id').val(null);

            $('#frm').find('input, select, textarea').each(function(i, elem){
                $(elem).val(null);
            });
            $('.unit_prev_img').remove();
            $('.list_show').show();
            $('.entry_input').hide();
            $('[name=search_input]').val('');

            $('#page_type').val('list_show');

            $('#now_page_num').val(now_page_num_ini);$('#page_num').val(page_num_ini);$('#page_disp_cnt').val(page_disp_cnt_ini);
            var form_datas = append_form_prams('init', 'frm', null, now_page_num_ini, page_num_ini, page_disp_cnt_ini);
            click_ctrl(null, page_title, 'init');
            call_ajax_init(form_datas);
        }else if(state.actType == 'disp_change'){
            $('#id').val(null);
            click_ctrl($('[name='+state.elemName+']'), page_title, 'nopush');
        }else if(state.actType == 'edit'){
            // 編集対象ID設定
            $('#id').val(state.id);
            $('#page_type').val('edit_init');
            disp_ctrl();

            // 入力内容取得
            var form_data = append_form_prams('edit_init', 'frm', null, null, null, null);

            // ajax呼び出し
            call_ajax_edit_init(form_data);
        }else if(state.actType == 'search'){
            $('#now_page_num').val(now_page_num_ini);$('#page_num').val(page_num_ini);$('#page_disp_cnt').val(page_disp_cnt_ini);
            var form_data =  append_form_prams('init', 'frm', null,  now_page_num_ini, page_num_ini, page_disp_cnt_ini);
            form_data.append('search_select', JSON.stringify(search_select));
            call_ajax_init(form_data);

        }
    }
});

$(function() {

    /**
     * 初期処理AJAX
     */
    call_ajax_init = function (post_data){
        let uri = new URLSearchParams(post_data).toString();
        $('#pagination-container').pagination({
            dataSource: $('#ct_url').val() + '?' + uri,
            locator: 'data.html',
            totalNumberLocator: function(response) {
                return response.data.html[1];
            },
            pageSize: 10,
            autoHidePrevious: true,
            autoHideNext:true,
            showSizeChanger: true,
            sizeChangerOptions: [10, 20, 30],
            ajax: {
                beforeSend: function() {
                    $(".loading").show()
                }
            },
            callback: function(data) {
                list_disp_exection(data[0]);
                edit_init_exection();
                common_func_bind();
                validate_start();
                tableColDispChange();
                searchMain();

                $('.pagination-info .total-result span').text(data[1] + ' 結果');
                $('#page_title').html('<i class="fa fa-list" aria-hidden="true"></i>'+ page_title + '一覧');
                $(".loading").hide();
            }
        });
    }

    //初期処理
    page_init()

    /**
     * 初期(一覧取得)処理
     */
    function page_init() {
        // コントローラー呼び出し
        page_ctrl(page_title);

        // 画面TOPへスクロール
        $('html,body').animate({ scrollTop: 0 }, 'fast');

        // 各種表示状態初期化
        $('.disp_area').hide();

        // 一覧表示処理
        $('.list_show').show();

        // 入力内容取得
        var form_datas = append_form_prams('init', 'frm', null, null, null, null);

        // 初期処理AJAX呼び出し処理
        call_ajax_init(form_datas);
    }

    /**
     * 更新初期処理
     */
    function edit_init_exection(){
        $('.edit').off();
        $('.edit').on('click',function(){

            var state = {
                "actType": 'edit',
                "id" : $(this).attr('value'),
                "search_select" : search_select,
            };
            history.pushState(state, null, null);

            // 編集対象ID設定
            $('#id').val($(this).attr('value'));
            $('#page_type').val('edit_init');
            disp_ctrl();

            // 入力内容取得
            var form_data = append_form_prams('edit_init', 'frm', null, null, null, null);

            // ajax呼び出し
            call_ajax_edit_init(form_data);
        });
    }

    /**
     * 更新初期処理処理AJAX
     */
    call_ajax_edit_init = function (post_data){
        ajax.get(post_data).done(function(result) {
            // ページタイトル設定
            $('#page_title').html('<i class="fa fa-wrench" aria-hidden="true"></i>' + page_title + '情報編集');

            // 正常終了
            if (result.data.status) {
                //更新情報自動入力
                console.log(result.data);
                insert_edit_data(result.data, 'frm', null);

                //ロード終了
                loaded();

                /** ここから別途処理呼び出し TODO **/
                /** ここまで **/

            }else if (!result.data.status && result.data.error_code == 0){
                // PHP返却エラー
                alert(result.data.error_msg);
                location.href = result.data.return_url;
            }

        }).fail(function(result) {
            // 異常終了
            $('body').html(result.responseText);
        });
    }

    /**
     * 一覧表示処理
     */
    function list_disp_exection(data){
        // 一覧表示処理
        $('.list_disp_area').show();

        // 初期HTMLリスト表示
        $('#list_html_area').html(data);

    }
});


/**
 * ページが切り替わる際の処理
 */
function disp_change_func(type){
}

/**
 * FormData追記
 */
function fd_add(fd){
    return fd;
}