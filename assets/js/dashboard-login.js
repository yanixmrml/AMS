$(document).ready(function(){
    
    $("#clear-button").click(function(){
        $("#username").val("");
        $("#password").val("");
        $("#warning-message").text("");
    });
    
    $("#login-button").click(function(){
        if($("#username").val()==""){
            $("#username").focus();
            $("#warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>Please enter your username " +
                             "correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#password").val()==""){
            $("#password").focus();
            $("#warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter your password correctly.</div>");
            $(document).on('alert','.alert');
        }else{
            $("#login-frm").submit();
        }
    });
    
    $("#password").keypress(function(event){
        if(event.which == 13){
            $("#login-button").trigger('click');
        }else{
            $("#warning-message").text("");
        }
    });
    
    $("#username").keypress(function(event){
        if(event.which == 13){
            $("#login-button").trigger('click');
        }else{
            $("#warning-message").text("");
        }
    });
});