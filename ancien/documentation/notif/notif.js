var fetchNotifs = function(){
    $.ajax({
        url: "notif/action.php",
        method: "POST",
        dataType: 'html',
        data: "todo=fetch",
        success: function(data) {
            $('#notifs-wrapper').html(data);
            checkNotifsNumber();
        }
    });
}

var checkNotifsNumber = function(){
    var notifsLength = $('#notifs-wrapper li:not(.notif-read)').length;
    if(notifsLength){
        $('#notifs-count').css('display', 'flex');
    }else{
        $('#notifs-count').css('display', 'none');
    }
    $('#notifs-count').html(notifsLength);
}

checkNotifsNumber();
setInterval(() => {
    fetchNotifs();
    
}, 10000)
