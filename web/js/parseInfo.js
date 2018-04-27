$(document).ready(function () {
    $('#loader').hide();
   buttonSetting();
   $('#get-category').on('click',{url:'/category/parse'},getData);
   $('#get-product').on('click',{url:'/product/parse'},getData);
   $('#clear-database').on('click',{url:'/site/truncate-tables'},getData);


});



function buttonSetting() {
    $.ajax('/category/count',{
        success:function (data) {
            if(data>0)
            {
                $('#get-product').attr('disabled',false);
                $('#clear-database').attr('disabled',false);
            }
            else
            {
                $('#get-product').attr('disabled',true);
                $('#clear-database').attr('disabled',true);
            }
        },

    });
}

function getData(e) {

    e.preventDefault();
    $.ajax(e.data.url,{
        beforeSend:function () {
            $('#parseInfo').text('');
            $('#loader').show();
        },
       success:function (data) {
            $('#parseInfo').text(data);
            buttonSetting();
       },
       error:function (x,y,z) {
           $('#parseInfo').text(x+' '+y+' '+z);
       },
       complete:function () {
           $('#loader').hide();
       }
    });
}

