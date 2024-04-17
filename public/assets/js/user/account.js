$(document).ready(function() {


    $("#password").click(function() {
        openPassword();
        closeAddress();
    });

    $(".close_password").click(function() {
        closePassword();
    });


    $("#address").click(function() {
        openAddress();
        closePassword();
    });

    $(".close_address").click(function() {
        closeAddress();
    });

    
    function openPassword(){
        $(".password_input").css("display","flex");
        $(".address_input").css("display","none");
        $(".all_addresses").css("display","none");
        $("#password").css("border-bottom","none");
        $("#password").css("border-top","1px solid #ccc");
        $("#password").text("Change Password");
    }

    function closePassword(){
        $(".password_input").css("display","none");
        $("#password").css("border-top","none");
        $("#password").css("border-bottom","1px solid #ccc");
        $("#password").text("Password");
    }

    function openAddress(){
        $(".address_input").css("display","flex");
        $(".password_input").css("display","none");
        $(".all_addresses").css("display","flex");
        $("#address").css("border-bottom","none");
        $("#address").css("border-top","1px solid #ccc");
        $("#address").text("Add Address");
    }

    function closeAddress(){
        $(".address_input").css("display","none");
        $(".all_addresses").css("display","none");
        $("#address").css("border-top","none");
        $("#address").css("border-bottom","1px solid #ccc");
        $("#address").text("Addresses");
    }

});