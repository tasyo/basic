$(document).ready(function () {
   var ajax;

    $('#download').click(function (e) {
        e.preventDefault();
        // parse();
        for(var i=0;i<=20;i++)
        {
            parse(i);
        }
    });






   function parse(i) {
       ajax=$.ajax('/product/parse',{
           beforeSend:function () {
               $('#parseInfo').text('Parsing..........');
           },

           success:function (data) {

           },
          error:function (x,y,z) {
              $('#parseInfo').text('Error parse '+x+' '+y+' '+z);
          },
           complete:function () {
               $('#parseInfo').text('Complete ');
           },
           timeout:i*1000
       });

   }

   $('#download').click(function (e) {
      e.preventDefault();
      parse();
   });
   $('#stop').click(function () {
       if(ajax)
       {
           ajax.abort();
       }
   })

});