
function switchClass(selector,neueclass){
	switch(neueclass){
		case 'free': 
			$(selector).fadeOut(100, function(){
				$(selector).removeClass('booked');
				$(selector).removeClass('myBook');
				$(selector).removeClass('bought');
				$(selector).fadeIn(100, function(){
					$(selector).addClass('free');
				});
			});

			break;
		case 'booked':
			$(selector).fadeOut(100, function(){
				$(selector).removeClass('free');
				$(selector).removeClass('myBook');
				$(selector).removeClass('bought');
				$(selector).fadeIn(100,function(){
					$(selector).addClass('booked');
				});
			});

			break;
		case 'myBook':
			$(selector).fadeOut(100, function(){
				$(selector).removeClass('free');
				$(selector).removeClass('booked');
				$(selector).removeClass('bought');
				$(selector).fadeIn(100,function(){
					$(selector).addClass('myBook');
				});
			});

			break;
		case 'bought':
			$(selector).fadeOut(100, function(){
				$(selector).removeClass('free');
				$(selector).removeClass('myBook');
				$(selector).removeClass('booked');
				$(selector).fadeIn(100,function(){
					$(selector).addClass('bought');
				});
			});

			break;
		default: break;
	}
}


function validInputSuccess(response){
    console.log("server answer = "+JSON.stringify(response));
};


function cleanResponse(response){
	let str = JSON.stringify(response);
	str=str.replace('{"','');
	str=str.replace('":"',':');
	str=str.replace('"}','\n');
	return str;
}


function alertFailure(data){
	var d=cleanResponse(data);
	var fail = d.split(':')[1].split(' - ')[0];
	var msg = d.split(':')[1].split(' - ')[1];
	
	if(fail=='fail'){
		alert(msg);
		return null;
	}
	else if(fail=='success'){
		return msg;
	}
	else return null;
}


function getJSONKey(d){
	d=cleanResponse(d);
	return str = d.split(':')[0];
}

function getJSONValue(d){
	d=cleanResponse(d);
	d = d.split(':')[1];
	return d;
}
function getJSONResultKey(d){
	d = cleanResponse(d);
	d = d.split(':')[1];
	d = d.split(' - ')[0];
	return d;
}
function getJSONResultValue(d){
	d=cleanResponse(d);
	d = d.split(':')[1];
	d = d.split(' - ')[1];
	return d;
}

function checkOutStatus(cid){
	$.ajax({ 
		url: 'ajaxCall.php',
        data: ({action: 'checkSitStatus', id: cid}),
        type: 'POST',
        dataType : "json",
        success: function(data){
        	var selector=$("#"+cid);
        	if(!selector.hasClass(data)) switchClass(selector,data);
        },
        error: function(test) {
             alert('There was some error performing the AJAX call!');
             alert(JSON.stringify(test));
        }
	});
}

function reloadSits(){
	$.ajax({ 
		url: 'ajaxCall.php',
        data: ({action: 'reloadSits'}),
        type: 'POST',
        dataType : "json",
        success: function(data) {
        	var tds = getJSONValue(data);
        	var arr= tds.split('||<br/>');
        	arr.pop();
        	var row, col, id, status,user;
        	$.each( arr, function( index, value ) {
        	    var values = value.split('||');
        	    row = values[0].split('row=>')[1];
        	    col = values[1].split('col=>')[1];
        	    id = "td-"+row+"-"+col;
        	    status = values[2].split('status=>')[1];
        	    user = values[3].split('user=>')[1];
    	    	if(user!='other'&&status=='booked')
    	    		status='myBook';
    	    	else if(user=='other'&&status=='booked') status="booked";
    	    	switchClass($('#'+id),status);
    	    	
        	});
        	
        },
        error: function(test) {
             alert('There was some error performing the AJAX call!'+JSON.stringify(test));
        }
	});
}



function refresh(){

	request = new ajaxRequest();
	request.open("POST", "ajaxCall.php", true);
	request.send("main_page.php");
	
	request.onreadystatechange = function() {
		  if(this.readyState == this.HEADERS_RECEIVED) {
		    var contentType = request.getResponseHeader("Content-Type");
		    if (contentType != "text/html; charset=UTF-8") {
		    	request.abort();
		    }
		    window.location.replace("welcome.php");
		  }
		}
}
function ajaxRequest()
{
	try
	{
		var request = new XMLHttpRequest();
	}
	catch(e1)
	{
		try
		{
			request = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e2)
		{
			try
			{
				request = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e3)
			{
				request = false;
			}
		}
	}
	return request;
}

