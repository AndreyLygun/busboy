
function showMsg(msg, reload=false) {
    if (msg != '') {
        var toast = document.getElementById('liveToast');
        document.getElementById('toast-message').innerHTML=msg;
        var toast = new bootstrap.Toast(toast);
        toast.show();
    }
    if (reload)
        $('.toast').on('hidden.bs.toast', () => {window.location.reload()});
    else $('.toast').unbind('hidden.bs.toast');
}


function showWait(wait) {
    if (wait) {
        $('body').addClass('wait');
        $('#loader').css('display', 'block');
    } else {
        $('body').removeClass('wait');
        $('#loader').css('display', 'none');
    }
}



function sendOrder() {
    $.get('/sendOrder.html',
        '',
        function (r) {
            if (r['status']==1)
                showMsg('Заказ отправлен', true)
            else showMsg("Извините, произошла ошибка. Обратитесь, пожалуйста, к официанту");
        }).fail(function() {
        showMsg("Извините, произошла ошибка. Обратитесь, пожалуйста, к официанту");
    });

}

function oldShowMsg (msg, reload=false) {
    if (msg!=='') {
        $('#toast-message').html(msg);
        $('.toast').toast('show');
    }

}

function add2cart_old(id) {
    html_id='#dish_'+id;
    name = document.querySelector(html_id).dataset.name;
    price = document.querySelector(html_id).dataset.price;
    optionsTxt = '';
    selects = document.querySelectorAll(html_id+' .dish_options select');
    for(i=0; i<selects.length; i++) {
        select = selects[i];
        optionsTxt = optionsTxt+select[select.selectedIndex].text+';';
    }
    data = 'action=add_dish'+'&id='+id+'&name='+name+'&option='+optionsTxt+'&price='+price;
    showWait(true);
    $.get('/carthandler.html',
        data,
        function(data) {showWait(false); showMsg(data.msg);},
        'json'
    ).fail(function() {showMsg("Sorry, some error happens");});
}
