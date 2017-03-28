Type.registerNamespace('Atlantis.Web.Controls');

Atlantis.Web.Controls.CssTabContainer = function(element) {
    Atlantis.Web.Controls.CssTabContainer.initializeBase(this, [element]);
    this._AutoPostBack = false;
    this._UpdateProgressDivClientId = null;
    this._pageRequestManager = null;
    this._partialUpdateBeginRequestHandler = null;
    this._partialUpdateEndRequestHandler = null;
    this._triggerClientIds = null;
    this._TabWidth = null;
}

Atlantis.Web.Controls.CssTabContainer.prototype = {
    get_triggerClientIds : function() { 
        if (this._triggerClientIds == null) {
            this._triggerClientIds = [];
        }
        return this._triggerClientIds; 
    },
    
    get_AutoPostBack : function() {
        return this._AutoPostBack;
    },
    set_AutoPostBack : function(value) {
        this._AutoPostBack = value;
    },
    
    get_UpdateProgressDivClientId : function() {
        return this._UpdateProgressDivClientId;
    },
    set_UpdateProgressDivClientId : function(value) {
        this._UpdateProgressDivClientId = value;
    },
    
    get_TabWidth : function() {
		return this._TabWidth;
	},
	set_TabWidth : function(value) {
		this._TabWidth = value;
	},
    
    initialize : function() {
        Atlantis.Web.Controls.CssTabContainer.callBaseMethod(this, 'initialize');
        if (this._AutoPostBack == Atlantis.Web.Controls.CssTabContainerAutoPostBackOption.PartialPage) {
            if (Sys && Sys.WebForms && Sys.WebForms.PageRequestManager) {
                this._pageRequestManager = Sys.WebForms.PageRequestManager.getInstance();
                if (this._pageRequestManager) {
                    this._partialUpdateBeginRequestHandler = Function.createDelegate(this, this._partialUpdateBeginRequest);
                    this._pageRequestManager.add_beginRequest(this._partialUpdateBeginRequestHandler);
                    this._partialUpdateEndRequestHandler = Function.createDelegate(this, this._partialUpdateEndRequest);
                    this._pageRequestManager.add_endRequest(this._partialUpdateEndRequestHandler);
                }
            }
        }
    },

    dispose : function() {
        Atlantis.Web.Controls.CssTabContainer.callBaseMethod(this, 'dispose');
        if (this._AutoPostBack == Atlantis.Web.Controls.CssTabContainerAutoPostBackOption.PartialPage) {
            if (this._pageRequestManager) {
                if (this._partialUpdateBeginRequestHandler) {
                    this._pageRequestManager.remove_beginRequest(this._partialUpdateBeginRequestHandler);
                    this._partialUpdateBeginRequestHandler = null;
                }
                if (this._partialUpdateEndRequestHandler) {
                    this._pageRequestManager.remove_endRequest(this._partialUpdateEndRequestHandler);
                    this._partialUpdateEndRequestHandler = null;
                }
                this._pageRequestManager = null;
            }
        }
    },
    
    _partialUpdateBeginRequest : function(sender, beginRequestEventArgs) {
        if (this._triggerClientIds != null) {
            if (Array.contains(this._triggerClientIds, beginRequestEventArgs.get_postBackElement().id)) {
                //  get the tabcontainer element        
                var tabContainer = this.get_element();
                
                var updateProgressDiv = $get(this._UpdateProgressDivClientId);
                
                // make it visible
                updateProgressDiv.style.display = '';	    
                
                // get the bounds of both the gridview and the progress div
                var tabContainerwBounds = Sys.UI.DomElement.getBounds(tabContainer);
                var updateProgressDivBounds = Sys.UI.DomElement.getBounds(updateProgressDiv);
                
                //  center of tabcontainer
                var x = tabContainerwBounds.x + Math.round(tabContainerwBounds.width / 2) - Math.round(updateProgressDivBounds.width / 2);
                var y = tabContainerwBounds.y + Math.round(tabContainerwBounds.height / 2) - Math.round(updateProgressDivBounds.height / 2);	    

                //	set the progress element to this position
                Sys.UI.DomElement.setLocation (updateProgressDiv, x, y);    
            }
        }
    },
    
    _partialUpdateEndRequest : function(sender, endRequestEventArgs) {
        // make it invisible
        var updateProgressDiv = $get(this._UpdateProgressDivClientId);
        updateProgressDiv.style.display = 'none';
    },
    
    raiseActiveTabChanged : function() {
        var eh = this.get_events().getHandler("activeTabChanged");
        if (eh) {
            eh(this, Sys.EventArgs.Empty);
        }
        
        if ((this._AutoPostBack == Atlantis.Web.Controls.CssTabContainerAutoPostBackOption.FullPage) && (this._autoPostBackId)) {
            __doPostBack(this._autoPostBackId, "activeTabChanged:" + this.get_activeTabIndex());
        }
        
        if (this._AutoPostBack == Atlantis.Web.Controls.CssTabContainerAutoPostBackOption.PartialPage) {
            var activeTab = this.get_activeTab();
            var autoPostBackOption = activeTab.get_AutoPostBackOption();
            var fireTrigger = false;
            if (autoPostBackOption == Atlantis.Web.Controls.CssTabPanelAutoPostBackOption.LoadOnFirstActivation) {
                if (null == $get(activeTab.get_InnerPanelClientId())) {
                    fireTrigger = true;
                }
            }
            else
                fireTrigger = true;
                
            if (fireTrigger) {
                var trigger = $get(activeTab.get_TriggerClientId());
                trigger.click();
            }
        }
    }
}

Atlantis.Web.Controls.CssTabContainer.inheritsFrom(AjaxControlToolkit.TabContainer);
Atlantis.Web.Controls.CssTabContainer.registerClass('Atlantis.Web.Controls.CssTabContainer', AjaxControlToolkit.TabContainer);

Atlantis.Web.Controls.CssTabContainerAutoPostBackOption = function() {
    throw Error.invalidOperation();
}
Atlantis.Web.Controls.CssTabContainerAutoPostBackOption.prototype = {
    None : 0,
    FullPage : 1,
    PartialPage : 2
}
Atlantis.Web.Controls.CssTabContainerAutoPostBackOption.registerEnum("Atlantis.Web.Controls.CssTabContainerAutoPostBackOption", false);

if (typeof(Sys) !== 'undefined') Sys.Application.notifyScriptLoaded();

if(typeof(Sys)!=='undefined')Sys.Application.notifyScriptLoaded();