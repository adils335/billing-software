$(function(){
    
    $(document).ready(function(){
       $('[data-toggle="tooltip"]').tooltip();   
    });
    $(".box").on("click",".btn-box-tool",function(event){
        event.preventDefault();
        event.stopPropagation();
        $(this).closest(".box").toggleClass("collapsed-box");
        $(this).find(".fa").toggleClass("fa-minus fa-plus");
    });
    $("button[data-dismiss='modal']").click(function(){
        $(this).closest("modal").hide();
    });

    $(document).on("change",".bill-action",function(){
        var id = $(this).attr("id");
        var url = $(this).attr("url");
        var refresh = $(this).attr("refresh");
        var status = $(this).val();
        var agreement = $(this).closest(".agreement .active");
        $.ajax({
            url:url,
            data:{id:id,status:status,refresh:refresh},
            beforeSend:function(){
                 $("#loader").show();
            },
            success:function(res){
                if( res.status){
                    if( res.refresh == 1 ){
                        location.href = location.href; 
                    }else{
                        agreement.click().click();
                    }
                }
            },
            error: function(xhr) { // if error occured
                console.log(xhr.statusText + xhr.responseText);
                $("#loader").hide();;
            },
            complete: function() {
                $("#loader").hide();
            }
        });
    });
    
    $(document).on("change",".agreement-action",function(){
        var id = $(this).attr("id");
        var url = $(this).attr("url");
        var refresh = $(this).attr("refresh");
        var status = $(this).val();
        var agreement = $(this).closest("tr.agreement .active");
        $.ajax({
            url:url,
            data:{id:id,status:status,refresh:refresh},
            beforeSend:function(){
                 $("#loader").show();
            },
            success:function(res){
                if( res.status){
                    if( res.refresh == 1 ){
                        location.href = location.href; 
                    }else{
                        agreement.click().click();
                    }
                }
            },
            error: function(xhr) { // if error occured
                console.log(xhr.statusText + xhr.responseText);
                $("#loader").hide();;
            },
            complete: function() {
                $("#loader").hide();
            }
        });
    });
    $(document).on("click",".sync-btn",function(event){
        event.preventDefault();
        var url = $(this).attr('url');
        var id = $(this).attr('bill_id');
        $.ajax({
            url:url,
            method:'GET',
            data:{id:id},
            beforeSend:function(){
                 $("#loader").show();
            },
            success:function(res){
                 if( res['status'] ){
                     $("#success-modal").removeClass("fade");
                     $("#success-modal").modal("show");
                     $("#success-modal .message").text(res['message']);
                 }else{
                     $("#error-modal").removeClass("fade");
                     $("#error-modal").modal("show");
                     $("#error-modal .message").text(res['message']);
                 }
            },
            error: function(xhr) { // if error occured
                console.log(xhr.statusText + xhr.responseText);
                $("#loader").hide();;
            },
            complete: function() {
                $("#loader").hide();
            }
       });
    });

    $(document).on("click",".cancel-irn-btn",function(event){
        event.preventDefault();
        $.ajax({
           url:$("#cancel-irn-form").attr("action"),
           method:'POST',
           data:$("#cancel-irn-form").serialize(),
           beforeSend:function(){
                $("#loader").show();
           },
           success:function(res){
                if( res['status'] ){
                    $("#success-modal").removeClass("fade");
                    $("#success-modal").modal("show");
                    $("#success-modal .message").text(res['message']);
                }else{
                    $("#error-modal").removeClass("fade");
                    $("#error-modal").modal("show");
                    $("#error-modal .message").text(res['message']);
                }
           },
           error: function(xhr) { // if error occured
               console.log(xhr.statusText + xhr.responseText);
               $("#loader").hide();;
           },
           complete: function() {
               $("#loader").hide();
           }
       });
    });

   $(document).on("click",".show-sidebar-popup",function(event){
        event.preventDefault();
        var swidth = $(this).attr('swidth');
        $("#popup-sidebar").addClass('active');
        var url = $(this).attr("data-url");
        $("#popup-sidebar .box-body").html("");
        $.ajax({
             url:url,
             type:'GET',
             beforeSend:function(){
                $("#loader").show();
             },
             success:function( res ){
                if (typeof swidth !== 'undefined' && swidth !== false) {
                    $(".popup-sidebar.active").css({"width":swidth+"%"});     
                }
                $("#popup-sidebar .box-body").html(res);
             },
             error: function(xhr) { // if error occured
                console.log(xhr.statusText + xhr.responseText);
                $("#loader").hide();;
             },
             complete: function() {
                $("#loader").hide();
             }
        });
    });

    $(document).on("click","#sidebar-close-btn",function(){
        $("#popup-sidebar").removeClass('active');
        $("#popup-sidebar").removeAttr("style");
    });
    $('button[data-dismiss="modal"]').click(function(){
        $(this).closest(".modal").modal("hide");
    });
    $(document).on('DOMNodeInserted', '.agreement-record', function() {
        $(".agreement-record").find(".btn-box-tool").attr("data-widget","");
    });
    /*$(document).on("click",".btn-box-tool",function(e){
        e.preventDefault();
        e.stopPropagation();
        console.log("test");
        var box = $(this).closest('.box');
        if(box.hasClass("collapsed-box")){
            box.removeClass("collapsed-box");
        }else{
            box.addClass("collapsed-box");
        }
    });*/
});