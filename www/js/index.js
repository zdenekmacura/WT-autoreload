var url="http://www.brrr.cz/brrr.php?";
var href="";

$(document).ready(function(){
     if ( $("#welcomepage").length == 0) loadPage("welcomepage",'');
});

function loadPage(id,tran) {

    var senddata = {loadpage: id};
    href = "#" + id;

    if ( $(href).length == 0) {
    $.ajax({
                type: "post",
                url: url,
                async: 'true',
                dataType: 'json',
                data: senddata,
                crossDomain: true,
                cache: false,
                beforeSend: function(){ $.mobile.loading('show');},
                complete: function() { $.mobile.loading('hide'); },
                success: function (result){
                    $("body").append(result.css);
                    $("body").append(result.html);
                    alert ("in loadpage" + result.script.length);
                    if (result.script.length > 0) 
                        { waitForLoadHTML(href,tran,result,waitForLoadScript);
                        } else {
                          waitForLoadHTML(href,tran,result,moveToOtherPage);     
                        }
                },
                error: function (result, ajaxOptions, thrownError) {
                     alert(result.responseText);
                }
    });
    } else {
       $.mobile.changePage(href,{transition:tran});
    }

}

function cleanAndReload(){
     $("div[id!='default'][data-role='page']").remove();
     $("script[loaded='yes']").remove();
     $("style[loaded='yes']").remove();
     loadPage("welcomepage",'');
}       
function callWhenReady(selector,tran, callback) {
    if ($(selector).closest('body').length) {
        callback(selector,tran);
    } else {
        setTimeout(function () {
            callWhenReady(selector,tran, callback);
        }, 100);
    }
} 
function waitForLoadHTML(selector,tran,result,callback) {
      alert ("in waitfor loadhtml before if" + result.script.length);

    if ($(selector).closest('body').length) {
        alert ("in waitfor loadhtml after if" + result.script.length);

        if (result.script.length > 0) {
            $("body").append(result.script);
            callback(selector,tran,moveToOtherPage);
        } else {
            callback(selector,tran)
        }
    } else {
        setTimeout(function () {
            waitForLoadHTML(selector,tran,result,callback);
        }, 100);
    }
}
function waitForLoadScript(selector,tran, callback) {
        alert ("in waitfor loadscript before if");

    if ($(selector + "script").closest('body').length)  {
         alert ("in waitfor loadscript after if");

        callback(selector,tran);
    } else {
        setTimeout(function () {
            waitForLoadScript(selector,tran, callback);
        }, 100);
    }
} 
function moveToOtherPage(href,tran) {
   //alert ("in move to other page" );

    $.mobile.changePage(href,{transition: tran}); 
}