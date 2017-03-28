function openWindow(url,_width,_height )
{
	var myLightWindow = new lightwindow();
	
	myLightWindow.activateWindow({
		href: url, 
		title: '',
		width: _width,
		height: _height
	});
}