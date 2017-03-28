Type.registerNamespace('Atlantis.Web.Controls');

Atlantis.Web.Controls.CssTabPanel = function(element) {
    Atlantis.Web.Controls.CssTabPanel.initializeBase(this, [element]);
    
    this._curvesWrapper = null;
    this._curveLeft = null;
    this._curveRight = null;
    this._curveMiddle = null;
    this._AutoPostBackOption = null;
    this._TriggerClientId = null;
    this._InnerPanelClientId = null;
    this._TabWidth = null;
}

Atlantis.Web.Controls.CssTabPanel.prototype = {
    
    get_AutoPostBackOption : function() {
        return this._AutoPostBackOption;
    },
    set_AutoPostBackOption : function(value) {
        this._AutoPostBackOption = value;
    },
    
    get_InnerPanelClientId : function() {
        return this._InnerPanelClientId;
    },
    set_InnerPanelClientId : function(value) {
        this._InnerPanelClientId = value;
    },
    
    get_TriggerClientId : function() {
        return this._TriggerClientId;
    },
    set_TriggerClientId : function(value) {
        this._TriggerClientId = value;
    },
    
    get_TabWidth : function() {
		return this._TabWdith;
	},
	set_TabWidth : function(value) {
		this._TabWidth = value;
	},
    
    initialize : function() {
        Atlantis.Web.Controls.CssTabPanel.callBaseMethod(this, 'initialize');
        
        var tempTab = document.createElement('div');
        this._curvesWrapper = document.createElement('div');
        this._curveLeft = document.createElement('div');
        this._curveRight = document.createElement('div');
        this._curveMiddle = document.createElement('div');
        
        var id = this.get_id();
        tempTab.id = id + "_tab";
        this._tab.parentNode.replaceChild(tempTab, this._tab);
        this._tab = tempTab;
        
        this._tab.appendChild(this._curvesWrapper);
        this._curvesWrapper.appendChild(this._curveLeft);
        this._curvesWrapper.appendChild(this._curveMiddle);
        this._curvesWrapper.appendChild(this._curveRight);
        this._tab.appendChild(this._header);
                
        Sys.UI.DomElement.addCssClass(this._tab, "ajax__tab_normal");
        Sys.UI.DomElement.addCssClass(this._curvesWrapper, "ajax__tab_curves");
        Sys.UI.DomElement.addCssClass(this._curveLeft, "ajax__tab_left");
        Sys.UI.DomElement.addCssClass(this._curveRight, "ajax__tab_right");
        Sys.UI.DomElement.addCssClass(this._curveMiddle, "ajax__tab_middle");
       
        var curveBounds = Sys.UI.DomElement.getBounds(this._curvesWrapper);
        var parentBounds = Sys.UI.DomElement.getBounds(this._tab.parentNode);
        var headerHeight = parentBounds.height - curveBounds.height
        if (headerHeight > 0) {
            this._header.style.height = headerHeight + "px";
        }
        this._header.style.display = "block";
        this._header.style.whiteSpace = "normal";
        this._header.style.marginRight = "0px";
        
        if (!this._enabled) {
            this._hide();
        }

        var owner = this.get_owner();
		var tabWidth = 0;
		
		if (this._TabWidth > 0) {
			tabWidth = this._TabWidth;
		}
		else if (owner.get_TabWidth() > 0) {
			tabWidth = owner.get_TabWidth();
		}        
        
        var firstTab = owner.getFirstTab(false);
        if (firstTab) {
            if (id == firstTab.get_id()) {
                Sys.UI.DomElement.addCssClass(this._header, "ajax__tab_first_tab");
				if (tabWidth > 0) {
					this._header.style.width = tabWidth-1 + "px";
				}
            }
        }
        
        if (tabWidth > 0) {
			this._header.style.width = tabWidth + "px";
			var totalTabWidth = Sys.UI.DomElement.getBounds(this._header).width
			this._tab.style.width = totalTabWidth + "px";
			var leftCurveWidth = Sys.UI.DomElement.getBounds(this._curveLeft).width;
			var rightCurveWidth = Sys.UI.DomElement.getBounds(this._curveRight).width;
			var curveMiddleWidth = totalTabWidth - (leftCurveWidth + rightCurveWidth);
			if (curveMiddleWidth > 0)
			    this._curveMiddle.style.width = curveMiddleWidth + "px";
        }

        
        if (this._TriggerClientId) {
            Array.add(owner.get_triggerClientIds(), this._TriggerClientId);
        }
    },

    dispose : function() {
        Atlantis.Web.Controls.CssTabPanel.callBaseMethod(this, 'dispose');
    }
}

Atlantis.Web.Controls.CssTabPanel.inheritsFrom(AjaxControlToolkit.TabPanel);
Atlantis.Web.Controls.CssTabPanel.registerClass('Atlantis.Web.Controls.CssTabPanel', AjaxControlToolkit.TabPanel);

Atlantis.Web.Controls.CssTabPanelAutoPostBackOption = function() {
    throw Error.invalidOperation();
}
Atlantis.Web.Controls.CssTabPanelAutoPostBackOption.prototype = {
    LoadOnFirstActivation : 0,
    LoadOnEachActivation : 1
}
Atlantis.Web.Controls.CssTabPanelAutoPostBackOption.registerEnum("Atlantis.Web.Controls.CssTabPanelAutoPostBackOption", false);


if (typeof(Sys) !== 'undefined') Sys.Application.notifyScriptLoaded();

if(typeof(Sys)!=='undefined')Sys.Application.notifyScriptLoaded();