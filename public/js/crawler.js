$(function(){
    $("#ad-list li, .olx-content li").each(function(event, item){ if($(item).text() == ''){$(item).remove()}})

    $("#ad-list li, .olx-content li img").attr("src", $("#ad-list li, .olx-content li img").data('src'));
})