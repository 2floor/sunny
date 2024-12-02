$(document).ready(function () {
    $('.custom-tooltip-container').hover(
        function () {
            $(this).find('.custom-tooltip').css('display', 'block');
        },
        function () {
            $(this).find('.custom-tooltip').css('display', 'none');
        }
    );
});