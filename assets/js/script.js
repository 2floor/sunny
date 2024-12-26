'use strict';

jQuery(function ($) {
  // この中であればWordpressでも「$」が使用可能になる
  var topBtn = $('.pagetop');
  topBtn.hide(); // ボタンの表示設定

  $(window).scroll(function () {
    if ($(this).scrollTop() > 70) {
      // 指定px以上のスクロールでボタンを表示
      topBtn.fadeIn();
    } else {
      // 画面が指定pxより上ならボタンを非表示
      topBtn.fadeOut();
    }
  }); // ボタンをクリックしたらスクロールして上に戻る

  topBtn.click(function () {
    $('body,html').animate(
      {
        scrollTop: 0,
      },
      300,
      'swing'
    );
    return false;
  }); //ドロワーメニュー

  $('.js-drawer').click(function () {
    $('.js-drawer-open').toggleClass('open');
    $('.drawer-menu').toggleClass('open');
    $('body').toggleClass('is-fixed');
  }); // スムーススクロール (絶対パスのリンク先が現在のページであった場合でも作動)

  $(document).on('click', 'a[href*="#"]', function () {
    var time = 400;
    var header = $('header').innerHeight();
    var target = $(this.hash);
    if (!target.length) return;
    var targetY = target.offset().top - header;
    $('html,body').animate(
      {
        scrollTop: targetY,
      },
      time,
      'swing'
    );
    return false;
  }); //globalアコーディオン

  $('.js-accordion').click(function () {
    $('.global__lists').slideToggle();
    $('.parts-detail__lists').slideToggle();
    $('.parts-menu__name').toggleClass('open');
  }); //partsアコーディオン

  $('.js-accordion2').click(function () {
    // $(".parts-detail__lists").slideToggle();
    // $(".parts-menu__name").toggleClass("open");
    $(this).next('.parts-detail__lists').slideToggle();
    $(this).toggleClass('open');
    $('.js-accordion2').not($(this)).next('.parts-detail__lists').slideUp();
    $('.js-accordion2').not($(this)).removeClass('open');
  }); //swiper1

  var swiper1 = new Swiper('.swiper', {
    pagination: {
      el: '.swiper-pagination',
      //ページネーションの要素
      type: 'bullets',
      //ページネーションの種類
      clickable: true, //クリックに反応させる
    },
    autoplay: {
      delay: 8000,
    },
  }); //swiper2

  var swiper2 = new Swiper('.swiper2', {
    autoplay: {
      delay: 8000,
      stopOnLastSlide: 'false',
    },
  }); //メガドロップ

  $('.js-drop').hover(
    function () {
      $(this).find('.js-drop-open').addClass('open');
    },
    function () {
      $(this).find('.js-drop-open').removeClass('open');
    }
  ); //画面の高さ取得

  var windowHeight = document.documentElement.clientHeight;
  $('.mv').height(windowHeight); //スクロールでヘッダーの色変更

  $(window).scroll(function () {
    var sliderHeight = $('.header').height();

    if (sliderHeight < $(this).scrollTop()) {
      $('.js-header').addClass('header-scroll');
    } else {
      $('.js-header').removeClass('header-scroll');
    }
  });
});
// ページ内リンク
$(document).ready(function () {
  // ヘッダーの高さを取得
  let headerHeight = $('.header').outerHeight();
  // URLからハッシュ（#）部分を取得
  let hash = window.location.hash;
  // ハッシュが存在し、対応する要素がある場合にスクロール
  if (hash && $(hash).length) {
    let position = $(hash).offset().top - headerHeight;
    $('html, body').animate({ scrollTop: position }, 600, 'swing');
  }
  // ページ内リンクに対してスムーズスクロールを適用
  $('a[href^="#"]').click(function (e) {
    e.preventDefault();
    let href = $(this).attr('href');
    let target = $(href == '#' || href == '' ? 'html' : href);
    let position = target.offset().top - headerHeight;
    $('html, body').animate({ scrollTop: position }, 600, 'swing');
  });
});
