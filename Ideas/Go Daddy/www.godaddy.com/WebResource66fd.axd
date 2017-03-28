var LBD_ImgId = null;
var LBD_Img = null;
var LBD_NewImg = null; 
var LBD_Parent = null;
var LBD_Prompt = null;
var LBD_Timer = null;
var LBD_TimerTicks = 0;

function LBD_LoadSound(soundPlaceholderId, soundLink)
{
    if(document.getElementById)
    {
        var i = soundLink.indexOf('&d=');
        if (-1 != i) {
          soundLink = soundLink.substring(0, i);
        }
        
        soundLink = soundLink + '&d=' + LBD_GetTimestamp();
        
        if ( (-1 == soundLink.indexOf('&e=')) && (document.location.protocol == "https:")) {
          soundLink = soundLink + '&e=1';
        }

        var placeholder = document.getElementById(soundPlaceholderId);
        var objectSrc = "<object id='captchaSound' classid='clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95' height='0' width='0' style='width:0; height:0;'><param name='AutoStart' value='1' /><param name='Volume' value='0' /><param name='PlayCount' value='1' /><param name='FileName' value='" + soundLink + "' /><embed id='captchaSoundEmbed' src='"+ soundLink + "' autoplay='true' hidden='true' volume='100' type='"+ LBD_GetMimeType() +"' style='display:inline;' /></object>";

        placeholder.innerHTML = "";
        placeholder.innerHTML = objectSrc;
    }
}

function LBD_GetTimestamp() {
  var d = new Date();
  var t = d.getTime() + (d.getTimezoneOffset() * 60000);
  return t;
}

function LBD_GetMimeType()
{
    var mimeType = "audio/x-wav";
    return mimeType;
}

function LBD_ReloadImage(imgId)
{  
	if(imgId)
	{
		LBD_ImgId = imgId;
		LBD_Img = document.getElementById(LBD_ImgId);
		var src = LBD_Img.src;
		
		var i = src.indexOf('&d=');
        if (-1 != i) {
          src = src.substring(0, i);
        }
		var newSrc = src + '&d=' + LBD_GetTimestamp();

		LBD_NewImg = document.createElement('img');
		LBD_NewImg.onload = LBD_ShowImage;
		LBD_NewImg.id = LBD_Img.id;
		LBD_NewImg.alt = LBD_Img.alt;
		LBD_NewImg.src = newSrc;

		LBD_Prompt = document.createElement('span');
		LBD_Prompt.appendChild(document.createTextNode('.'));

        LBD_PreReloadImage();
		LBD_Parent = LBD_Img.parentNode;
		LBD_Parent.removeChild(LBD_Img);
		LBD_Parent.appendChild(LBD_Prompt);
		
		LBD_ShowProgress()
	}
}

function LBD_ShowProgress()
{
    if((LBD_Prompt)&&(LBD_TimerTicks < 100))
    {
        LBD_TimerTicks = LBD_TimerTicks + 1;
    
        if(0 == LBD_TimerTicks % 5)
        {
             LBD_Prompt.firstChild.nodeValue = '.';     
        }
        else
        {
             LBD_Prompt.firstChild.nodeValue = LBD_Prompt.firstChild.nodeValue + '.';
        }
        
        LBD_Timer = setTimeout("LBD_ShowProgress()", 250);
    }
    else
    {
        clearTimeout(LBD_Timer);
        LBD_TimerTicks = 0;
    }
}

function LBD_ShowImage()
{
	if(LBD_NewImg && LBD_Parent && LBD_Prompt)
	{
		LBD_Parent.removeChild(LBD_Prompt);
		LBD_Parent.appendChild(LBD_NewImg);
		LBD_Prompt = null;
		LBD_PostReloadImage();
	}
}

function LBD_PreReloadImage() {}
function LBD_PostReloadImage() {}

function LBD_RegisterHandler(eventName, userHandler)
{
    switch(eventName.toLowerCase())
    {
        case 'prereloadcaptchaimage':
            var LBD_OldHandler = LBD_PreReloadImage;
            LBD_PreReloadImage = function() {
                LBD_OldHandler();
                userHandler();
            }
            break;
       case 'postreloadcaptchaimage':
            var oldHandler = LBD_PostReloadImage;
            LBD_PostReloadImage = function() {
                LBD_OldHandler();
                userHandler();
            }
            break;
    }
}

if ( (typeof(Sys) != "undefined") && (typeof(Sys.Application) != "undefined") ) {
  Sys.Application.notifyScriptLoaded();
}
