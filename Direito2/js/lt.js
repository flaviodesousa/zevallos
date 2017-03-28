var nvg3g='';
var tuple='';
var nvgupd='';


if (document.cookie.indexOf('navegg_') == -1) nvgupd = '&nvup=1';
r=document.getElementById("navegg").src.split('?');
sit=r[1];
var nvgpflid = ltgc('nvgpfl');
if (nvgpflid) nvgpflid='&nvgpflid='+nvgpflid;
else nvgpflid='';

function ltload ()
{
	if ( (tuple != "(null)") && (tuple>' '))
	{
		tar = tuple.split('_');
		var date = new Date();
		date.setTime(date.getTime()+(60*60*1000*200));
		var expires = ";expires="+date.toGMTString();
		if ( tar[0] ) document.cookie = 'navegg_gender'+"="+tar[0]+expires+";path=/";
		if ( tar[1] ) document.cookie = 'navegg_age'+"="+tar[1]+expires+";path=/";
		if ( tar[2] ) document.cookie = 'navegg_interests'+"="+tar[2]+expires+";path=/";	
		if ( tar[3] ) document.cookie = 'navegg_education'+"="+tar[3]+expires+";path=/";		
		if ( tar[4] ) document.cookie = 'navegg_city'+"="+tar[4]+expires+";path=/";		
		if ( tar[5] ) document.cookie = 'navegg_region'+"="+tar[5]+expires+";path=/";		
		if ( tar[6] ) document.cookie = 'navegg_country'+"="+tar[6]+expires+";path=/";		
		if ( tar[7] ) document.cookie = 'navegg_shopping'+"="+tar[7]+expires+";path=/";		
		if ( tar[8] ) document.cookie = 'navegg_musics'+"="+tar[8]+expires+";path=/";		
		if ( tar[9] ) document.cookie = 'navegg_style'+"="+tar[9]+expires+";path=/";		
		if ( tar[10] ) document.cookie = 'navegg_other'+"="+tar[10]+expires+";path=/";		
		if ( tar[11] ) document.cookie = 'navegg_custom'+"="+tar[11]+expires+";path=/";		
	}	
}

function ltsetid(ltid)
{
	var date = new Date();
	date.setTime(date.getTime()+(60*60*1000*30*60*60));
	var expires = ";expires="+date.toGMTString();
	if (document.cookie.indexOf('nvgpfl') == -1) document.cookie = 'nvgpfl'+"="+ltid+expires+";path=/";
	nvgpflid=ltid;
	if (nvg3g) ltPartnerC();
}


function ltgc( name ) 
{
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) return null;
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ";", len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
}

function ltset(field,value)
{
	var date = new Date();
	date.setTime(date.getTime()+(60*60*1000*30*60*60));
	var expires = ";expires="+date.toGMTString();
	document.cookie = field+"="+value+expires+";path=/";
}

function ltPartner(partn)
{
	nvg3g=1;
}

function ltPartnerC(partn)
{
	if (document.cookie.indexOf('bbck') == -1)
	{
		document.cookie = 'bbck=1;expires=0;path=/';
		var b=document.getElementsByTagName("head")[0],c=document.createElement("script");
		c.type="text/javascript";
		c.src='http://navegg.boo-box.com/sc.lt?id='+nvgpflid;
		c.id="naveggpartiner";
		b.appendChild(c);
	}
}


if (typeof(nvgchannel) != 'undefined') nvgupd+='&nvcn='+nvgchannel;

if (nvgpflid.indexOf('deleted') == -1) 
{
	var b=document.getElementsByTagName("head")[0],c=document.createElement("script");
	c.type="text/javascript";
	c.src='http://lt.navegg.com/g.lt?nvst='+sit+'&nvtt=z'+nvgupd+nvgpflid;
	c.id="navegg";
	b.appendChild(c);
}
