jQuery(document).ready(function($){
    
    $('.beefup').beefup({
        openSingle: true
    });
    
    $('.single_option').click(function(){
        $('.single_option').removeClass("active");
        $(this).addClass("active");
        $('div.single_option[checked="checked"]').css("border-color", "#0074a2");
    })

});

