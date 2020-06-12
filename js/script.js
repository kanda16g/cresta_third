$(function() {

    // ヘッダー追従

    var keyHeight = $('.key').height();

    $(window).scroll(function() {

        if($(this).scrollTop() > keyHeight ) {
            $('.header').addClass('fixed');
            $('.hammenu').addClass('fixed');
        } else {
            $('.header').removeClass('fixed');
            $('.hammenu').removeClass('fixed');
        }

    });

    

    // ハンバーガーメニュー

    $('.hammenu').click(function() {
        $(this).toggleClass('active');
        $('.bg').toggleClass('active');
        $('.glonav').toggleClass('active');

        return false;
    });

    $('.bg').click(function() {
        $('.hammenu').removeClass('active');
        $(this).removeClass('active');
        $('.glonav').removeClass('active');
    });
});