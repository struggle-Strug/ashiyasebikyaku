$(function () {
  function toggleDrawerMenu() {
    $('.drawer-icon').toggleClass('is-active');
    $('.drawer-content').toggleClass('is-active');
    $('.drawer-background').toggleClass('is-active');
  }
  
  $(document).ready(function () {
    // ドロワーメニューアイコンと背景をクリックしたときのイベント処理
    $('.drawer-icon, .drawer-background').on('click', function (e) {
      e.preventDefault();
      toggleDrawerMenu(); // メニューの開閉を切り替える関数を呼び出す
      return false;
    });
  
    // ドロワーメニューの各項目をクリックしたときのイベント処理
    $('.drawer-content__item-link, .drawer-link a, .drawer-sns__item a').on('click', function (e) {
      if ($('.drawer-content').hasClass('is-active')) {  
        // メニューを閉じる
        toggleDrawerMenu();
      }
    });
  
    // ページ遷移直前にスクロール位置を保存
    $(window).on('beforeunload', function () {
      sessionStorage.setItem('scrollPosition', $(window).scrollTop());
    });
  
    // ページがロードされたときに、sessionStorageに保存されたスクロール位置があれば復元する
    var savedScrollPosition = sessionStorage.getItem('scrollPosition');
    if (savedScrollPosition) {
      $(window).scrollTop(savedScrollPosition);
    }
  
    // 768px以上のときの処理
    $(window).resize(function () {
      var w = $(window).width();
      var x = 768;
      if (w >= x) {
        $('.drawer-icon').removeClass('is-active');
        $('.drawer-content').removeClass('is-active');
        $('.drawer-background').removeClass('is-active');
        $('.drawer-content__item-link').removeClass('is-active');
        $('.drawer-content').css('transform', ''); // ウィンドウリサイズ時にtransformをリセット
      }
    });
  });

   // ページ内スクロール
   $('a[href^="#"]').on("click", function() {
    var speed = 1000;
    var href = $(this).attr("href");
    var target = $(href == "#" ? "html" : href);
    var position = target.offset().top - 110;
    $("html,body").animate({ scrollTop: position }, speed);
    return false;
  });

  // サムネイル
  const sliderThumbnail = new Swiper(".slider-thumbnail", {
    slidesPerView: 3,
    spaceBetween: 10,
    grid: {
      rows: 2,
      fill: 'row',
    },
    breakpoints: {
      // スライドの表示枚数：500px以上の場合
      576: {
        slidesPerView: 6,
      }
    }
  });
  // スライダー
  const slider = new Swiper(".slider", {
    loop: true,
    // 前後の矢印
    navigation: {
      prevEl: ".swiper-button-prev",
      nextEl: ".swiper-button-next",
    },
    thumbs: {
      swiper: sliderThumbnail,
    },
  });

  // ヘッダーを途中から固定
  var navPos = $('#header-nav').offset().top; // グローバルメニューの位置
  var navHeight = $('#header-nav').outerHeight(); // グローバルメニューの高さ
  $(window).on('scroll', function () {
    if (140 < $(this).scrollTop()) { // 1000px以上スクロールしたら
      $('#header-nav').addClass('m_fixed');
      $('.top-visual').addClass('fv-fixed');
      $('.main-visual').addClass('mv-fixed');
    } else {
      $('#header-nav').removeClass('m_fixed');
      $('.top-visual').removeClass('fv-fixed');
      $('.main-visual').removeClass('mv-fixed');
    }
  });

  // スクロールするとロゴ画像変更
  $(function () {
    $(window).on("scroll", function () {
      const sliderHeight = $("#header-nav").outerHeight();
      if (sliderHeight - 30 < $(this).scrollTop()) {
        $(".js-logo").addClass("logo_fixed");

      } else {
        $(".js-logo").removeClass("logo_fixed");
      }
    });
  });
})



