var bustcachevar=1; //bust potential caching of external pages after initial request? (1=yes, 0=no)
var bustcacheparameter="";

function createRequestObject(){
	try	{
		xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	}	catch(e)	{
		alert('Sorry, but your browser doesn\'t support XMLHttpRequest.');
	};
	return xmlhttp;
};

function ajaxpage(url, containerid){
	var page_request = createRequestObject();
	
	if (bustcachevar) bustcacheparameter=(url.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
	page_request.open('GET', url+bustcacheparameter, true)
	page_request.send(null)

	page_request.onreadystatechange=function(){
		loadpage(page_request, containerid)
	}

}

function loadpage(page_request, containerid){
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)) {
	    document.getElementById(containerid).innerHTML=page_request.responseText;
	};
}


function LoadComments() {
	ajaxpage("guestbook.php", "GuestBook");
}

function SubmitComment() {
	if (document.GuestBookFrm.your_name.value !== '' && document.GuestBookFrm.email.value !== '' && document.GuestBookFrm.comment.value !== '') {
		ajaxpage("guestbook.php?do=add&name=" + encodeURI(document.GuestBookFrm.your_name.value) + "&email=" + encodeURI(document.GuestBookFrm.email.value) + "&comment=" + encodeURI(document.GuestBookFrm.comment.value), "GuestBook");
	} else {
		alert ("Fill in all fields.");
	}
	
}

LoadComments();