jQuery(document).ready(function ( $ ){
    var tool_btn_elem = $('.dynaimc-fields-wrapper > .toolbox > .tool-btn');

    tool_btn_elem.click(function (){

        alert($(this).text());


    });
});
