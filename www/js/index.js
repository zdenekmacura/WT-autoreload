var url="http://www.brrr.cz/brrr.php?";
var href="";

$(document).ready(function(){
     if ( $("#welcomepage").length == 0) loadPage("welcomepage","slideup");
});

function loadPage(id,tran) {
    loadPageTypeParent(id,tran,"page","body");
}

function loadPageTypeParent(id,tran,type,parent) {

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

                    $(parent).append(result.css);
                    $(parent).append(result.html);
                    if (result.script.length > 0) 
                        { waitForLoadHTML(href,tran,type,result,waitForLoadScript);
                        } else {
                          waitForLoadHTML(href,tran,type,result,moveToOtherPage);     
                        }
                },
                error: function (result, ajaxOptions, thrownError) {
                     alert("Error when loading page:" + result.responseText);
                }
    });
    } else {
        if (type == "page") {
        $.mobile.changePage(href,{transition:tran});
        }
        if (type == "popup") {
        $(href).popup();
        $(href).popup( "open", {transition:tran});
        }
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
function waitForLoadHTML(selector,tran,type,result,callback) {

    if ($(selector).closest('body').length) {

        if (result.script.length > 0) {
            var script   = document.createElement("script");
            script.type  = "text/javascript";
            script.text  = result.script;             
            script.id = result.id + "script";
            document.body.appendChild(script);
            callback(selector,tran,type,moveToOtherPage);
        } else {
            callback(selector,tran,type)
        }
    } else {
        setTimeout(function () {
            waitForLoadHTML(selector,tran,result,callback);
        }, 100);
    }
}
function waitForLoadScript(selector,tran,type, callback) {

    if ($(selector + "script").closest('body').length)  {

        callback(selector,tran,type);
    } else {
        setTimeout(function () {
            waitForLoadScript(selector,tran,type,callback);
        }, 100);
    }
} 
function moveToOtherPage(href,tran,type) {
    if (type == "page") {
        $.mobile.changePage(href,{transition:tran});
        }
        if (type == "popup") {
        $(href).popup();
        $(href).popup( "open", {transition:tran});
        }
}