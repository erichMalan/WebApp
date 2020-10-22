
function bookSit(cid,previousStatus){
	$.ajax({ 
		url: 'ajaxCall.php',
        data: ({action: 'reserve', id: cid}),
        type: 'POST',
        dataType : "json",
        success: function(data){
     		var msg=getJSONValue(data);
    	    if(msg=='fail - user id not found, please relog') {
    	    	alert(msg);
    	    	refresh();
    	    	return;
    	    }
        	var status =alertFailureShort(data);
        	var type = stringToken(data,':',0);
        	if(status!=null){
        		if(type=='reservation'){
        			switchClass("#"+cid,"myBook");
        			checkOutStatus(cid);
        			getMySits();
        		}
        		
        		else if(type=='unbook'){
        			checkOutStatus(cid);
            		$("#my_sits_booked").text(function(){
            			var mySits=getMySits();
        				return mySits;
        			});       		
        		}
        			
        		checkOutStatus(cid);

        	}
        	else
        		checkOutStatus(cid);
        },
        error: function(test) {
             //alert('There was some error performing the AJAX call!');
             alert(JSON.stringify(test));
        }
	});
}

function getMySits(){
	$.ajax({ 
		url: 'ajaxCall.php',
        data: ({action: 'getMySits'}),
        type: 'POST',
        dataType : "json",
        success: function(data) {
         		var msg=getJSONValue(data);
        	    if(msg=='fail - user id not found, please relog') {
        	    	refresh();
        	    	return;
        	    }
        	    else{
	                var arr=msg.split(" | ");	
        	    	$("#my_sits_booked").text(function(){
    					return arr[0];
    				});
        	    	$("#my_sits_bought").text(function(){
    					return arr[1];
    				});
        	    }
        },
        error: function(test) {
             alert('There was some error performing the AJAX call!'+JSON.stringify(test));
        }
	});
}



function buySits(selector){
	var counter=$('td.myBook').size();
	$.ajax({ 
		url: 'ajaxCall.php',
        data: ({action: 'buySits', count: counter}),
        type: 'POST',
        dataType : "json",
        success: function(data) {
         		var msg=getJSONValue(data);
        	    alert(msg);
        	    if(msg=='fail - user id not found, please relog') {
        	    	refresh();
        	    	return;
        	    }
        	    else {
        	    	reloadSits();
        	    	getMySits();
        	    }
        },
        error: function(test) {
             alert('There was some error performing the AJAX call!'+JSON.stringify(test));
        }
	});
}



function stringToken(data,splitter,index){
	data=cleanResponse(data);
	data=data.split(splitter)[index];
	return data;
}


function alertFailureShort(data){
	var d=cleanResponse(data);
	var fail = d.split(' - ')[0];
	var msg = d.split(' - ')[1];
	if(d.indexOf('fail')>-1){
		alert(msg);
		return null;
	}
	else if(d.indexOf('success')>-1) return msg;
	else return null;
}

function logoutUser(){
	$.ajax({ 
		url: 'ajaxCall.php',
        data: ({userAction: 'logOut'}),
        type: 'POST',
        dataType : "json",
        success: function(data){
        	var session=alertFailure(data);
        	if(session!=null){
        		refresh();
        	}
        },
        error: function(test) {
             alert('There was some error performing the AJAX call!');
             //alert(JSON.stringify(test));
        }
	});
}

function refreshData()
{
    if($('td.myBook').size()>0)
    	$("#buybtn").css("display","");
    else
    	$("#buybtn").css("display","none");
    setTimeout(function(){ refreshData();}, 30);
}


$(document).ready(function(){
	
	refreshData();
	
	$("#logoutbtn").click(function(){ // click su login
		logoutUser();
	})
	
    $('td').click(function(){ // click su posto modalità utente
	    var cid = $(this).attr('id');
	    var ps = $(this).attr('class');
	    bookSit(cid,ps);
    });

	$("#buybtn").click(function(){ // click su login
		buySits('td.myBook');
	})
	
	$("#refreshbutton").click(function(){ 
		reloadSits();
	})
	

});



