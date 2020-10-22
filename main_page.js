

function checkoutUser(login_email,login_password){
	$.ajax({ 
		url: 'ajaxCall.php',
        data: ({userAction: 'checkAccount', id: login_email, pwd: login_password}),
        type: 'POST',
        dataType : "json",
        success: function(data){
        	var key=getJSONResultKey(data);
        	var msg=getJSONResultValue(data);
        	if(key=='fail') alert(msg);
        	else if(key=='success'){
        		refresh();
        	} 
        },
        error: function(test) {
             alert('There was some error performing the AJAX call!'+JSON.stringify(test));
        }
	});
}

function createAccount(email,password){
	$.ajax({ 
		url: 'ajaxCall.php',
	    data: ({userAction: 'createAccount', id: email, pwd: password}),
	    type: 'POST',
	    dataType : "json",
	    success: function(data){
	    	var user=alertFailure(data);
            if(user!=null){
            	refresh();
            }
            //else alert('please insert valid user and/or password');

	    },
	    error: function(test) {
	         //alert('There was some error performing the AJAX call!');
	         alert(JSON.stringify(test));
	    }
	});
}

$(document).ready(function(){
	
	setTimeout(function(){$('.opacity_medium').each(function(){$(this).css("background-color","rgba(255, 255, 255,1)");})},1000);
	
	var pattern = /(?=.*[a-z])(?=.*([A-Z]|\d)).*$/;
	
    $('td').click(function(){ // click su posto modalità neutra
	    var cid = $('td').attr('id');
    	checkOutStatus(cid);
    });


	$("#loginbtn").click(function(e){ // click su login
		e.preventDefault();
	    if ($('#login_email').is(':valid')&&pattern.test($('#login_password').val())) { 
			var login_email=$("#login_email").val();
			var login_password=$("#login_password").val();
			checkoutUser(login_email,login_password);
	      }
	    else if ($('#login_email').is(':valid')&&!pattern.test($('#login_password').val())) { 
	    	alert("please insert a valid password");
	    }
	    else alert('alert please insert a valid email');

	});


	$("#signupconfirmbtn").click(function(e){// click su signup
		e.preventDefault();
	    if ($('#signup_email').is(':valid')&&pattern.test($('#signup_password').val())) { 
			var login_email=$("#signup_email").val();
			var login_password=$("#signup_password").val();
			createAccount(login_email,login_password);
	    }
	    else if ($('#signup_email').is(':valid')&&!pattern.test($('#signup_password').val())) { 
	    	alert("please insert a valid password");
	    }
	    else alert('alert please insert a valid email');
	})
	
	$('#login_email').focusout(function() {
	    	if ($(this).val().length === 0) {        
	        	alert('email field is empty');
	    	}
	});
	$('#login_password').focusout(function() {
    	if ($(this).val().length === 0) {        
        	alert('password field is empty');
    	}
	});
	$('#signup_email').focusout(function() {
    	if ($(this).val().length === 0) {        
        	alert('email field is empty');
    	}
	});
	$('#signup_password').focusout(function() {
    	if ($(this).val().length === 0) {        
        	alert('password field is empty');
    	}
	});

			

	$("#signupbtn").click(function(){ // mostra e nasconde register form login form
		$("#signupform").css("display","");
		$("#signupbnt").slideToggle('slow');
		$("#loginform").slideToggle('slow');
		$('#already_registered').slideToggle('slow');
		$('#not_already_registered').slideToggle('none');
	})

	
	$("#loginformbtn").click(function(){
		$("#signupform").slideToggle('slow');
		$("#signupbnt").slideToggle('slow');
		$("#loginform").slideToggle('slow');
		$('#already_registered').slideToggle('slow');
		$('#not_already_registered').slideToggle('slow');
	})
	
	$("#refreshbutton").click(function(){ 
		reloadSits();
	})
	
    $('#login_checkbox').click(function(){
        if($(this).prop("checked") == true){
    	    $('#login_password').prop('type','text');
        }
        else if($(this).prop("checked") == false){
        	$('#login_password').prop('type','password');
        }
    });
	
    $('#signup_checkbox').click(function(){
        if($(this).prop("checked") == true){
        	$('#signup_password').prop('type','text');
        }
        else if($(this).prop("checked") == false){
        	$('#signup_password').prop('type','password');
        }
    });
	
});



