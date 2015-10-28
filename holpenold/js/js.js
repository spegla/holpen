function changeScreenImages(url_screen,id_screen,count_screen) {
	document.getElementById('screen').src = url_screen;
	for (var i=1;i<=count_screen;i++) {
		if (i == id_screen) {
			document.getElementById('screen_li_'+i).className = 'active';
		} else {
			document.getElementById('screen_li_'+i).className = '';
		}
	}
}