function setHomepage()
{
    if (document.all)
    {
        document.body.style.behavior='url(#default#homepage)';
        document.body.setHomePage('http://www.barganhando.com');
    }
    else if (window.sidebar)
    {
        if(window.netscape)
        {
            try
            {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }
            catch(e)
            {
                alert("this action was aviod by your browser，if you want to enable，please enter about:config in your address line,and change the value of signed.applets.codebase_principal_support to true");
            }
         }
         var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
         prefs.setCharPref('browser.startup.homepage','http://www.barganhando.com');
     }
}
