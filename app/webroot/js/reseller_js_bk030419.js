 function societyAutoLogin(societyUserName,societyUserPassword,loginFlag){
        var input1 = $('<input></input>').attr('type', 'hidden').attr('name','societyAutoLogin[loginFlag]').val(loginFlag);
        var input2 = $('<input></input>').attr('type', 'hidden').attr('name','data[User][username]').val(societyUserName);
        var input3 = $('<input></input>').attr('type', 'hidden').attr('name','data[User][password]').val(societyUserPassword);
        
        var action = "/users/auto_login";
        $('body').append($('<form></form>').attr('id','society_auto_login').attr('method', 'post').attr('action',action).append(input1,input2,input3));
        $('#society_auto_login').submit();
    }
function societyAutoLogin201920(societyUserName,societyUserPassword,loginFlag){
        var input1 = $('<input></input>').attr('type', 'hidden').attr('name','societyAutoLogin[loginFlag]').val(loginFlag);
        var input2 = $('<input></input>').attr('type', 'hidden').attr('name','data[User][username]').val(societyUserName);
        var input3 = $('<input></input>').attr('type', 'hidden').attr('name','data[User][password]').val(societyUserPassword);

        var action = "/users/login201920";
        $('body').append($('<form></form>').attr('id','society_auto_login').attr('method', 'post').attr('action',action).append(input1,input2,input3));
        $('#society_auto_login').submit();
    }


