// Dynamic Layer Object
// sophisticated layer/element targeting and animation object which provides the core functionality needed in most DHTML applications
// 19990604

// Copyright (C) 1999 Dan Steinman
// Distributed under the terms of the GNU Library General Public License
// Available at http://www.dansteinman.com/dynapi/

// updated 20011228 by Bob Clary <bc@bclary.com>
// to support Gecko

function DynLayer(id,nestref,frame) {
	//bc:maybe? if (!is.ns5 && !DynLayer.set && !frame) DynLayerInit()
	if (!DynLayer.set && !frame) DynLayerInit()
	this.frame = frame || self
	//bc:if (is.ns) {
	if (is.ns4) {
		if (is.ns4) {
			if (!frame) {
				if (!nestref) var nestref = DynLayer.nestRefArray[id]
				if (!DynLayerTest(id,nestref)) return
				this.css = (nestref)? eval("document."+nestref+".document."+id) : document.layers[id]
			}
			else this.css = (nestref)? eval("frame.document."+nestref+".document."+id) : frame.document.layers[id]
			this.elm = this.event = this.css
			this.doc = this.css.document
		}
		//bc:else if (is.ns5) {
		//bc:	this.elm = document.getElementById(id)
		//bc:	this.css = this.elm.style
		//bc:	this.doc = document
		//bc: }
	}
		this.x = this.css.left
		this.y = this.css.top
		this.w = this.css.clip.width
		this.h = this.css.clip.height
	}
	//bc:else if (is.ie) {
	else if (is.ie || is.ns5) {
    //bc:
    if (is.ie)
		this.elm = this.event = this.frame.document.all[id]
    //bc:
    else 
		this.elm = this.event = this.frame.document.getElementById(id)

		//bc:this.css = this.frame.document.all[id].style
		this.css = this.elm.style
		this.doc = document
		this.x = this.elm.offsetLeft
		this.y = this.elm.offsetTop
		this.w = (is.ie4)? this.css.pixelWidth : this.elm.offsetWidth
		this.h = (is.ie4)? this.css.pixelHeight : this.elm.offsetHeight
	}
	this.id = id
	this.nestref = nestref
	this.obj = id + "DynLayer"
	eval(this.obj + "=this")
}
function DynLayerMoveTo(x,y) {
	if (x!=null) {
		this.x = x
		//bc:if (is.ns) this.css.left = this.x
		if (is.ns4) this.css.left = this.x
		//bc:else this.css.pixelLeft = this.x
		else if (is.ie) this.css.pixelLeft = this.x
		else if (is.ns5) this.css.left = Math.floor(this.x) + 'px'
	}
	if (y!=null) {
		this.y = y
		//bc:if (is.ns) this.css.top = this.y
		if (is.ns4) this.css.top = this.y
		//bc:else this.css.pixelTop = this.y
		else if (is.ie) this.css.pixelTop = this.y
		else if (is.ns5) this.css.top = Math.floor(this.y) + 'px'
	}
}
function DynLayerMoveBy(x,y) {
	this.moveTo(this.x+x,this.y+y)
}
function DynLayerShow() {
	this.css.visibility = (is.ns4)? "show" : "visible"
}
function DynLayerHide() {
	this.css.visibility = (is.ns4)? "hide" : "hidden"
}
DynLayer.prototype.moveTo = DynLayerMoveTo
DynLayer.prototype.moveBy = DynLayerMoveBy
DynLayer.prototype.show = DynLayerShow
DynLayer.prototype.hide = DynLayerHide
DynLayerTest = new Function('return true')

// DynLayerInit Function
function DynLayerInit(nestref) {
	if (!DynLayer.set) DynLayer.set = true
	//bc:if (is.ns) {
	if (is.ns4) {
		if (nestref) ref = eval('document.'+nestref+'.document')
		else {nestref = ''; ref = document;}
		for (var i=0; i<ref.layers.length; i++) {
			var divname = ref.layers[i].name
			var index = divname.indexOf("Div")
			if (index > 0) {
				eval(divname.substr(0,index)+' = new DynLayer("'+divname+'","'+nestref+'")')
			}
		}
	}
	//bc:else if (is.ns5) {
	//bc:	var nodeList = document.getElementsByTagName('div');
	//bc:		for (var i=0; i<nodeList.length; i++) {
	//bc:		var divname = nodeList[i].id
	//bc:		var index = divname.indexOf("Div")
	//bc:		if (index > 0) {
	//bc:			eval(divname.substr(0,index)+' = new DynLayer("'+divname+'","'+nestref+'")')
	//bc:		}
	//bc:	}
	//bc: }
	}
	return true;
}
DynLayer.nestRefArray = new Array()
DynLayer.set = false
