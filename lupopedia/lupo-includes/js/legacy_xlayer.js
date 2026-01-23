/******************************************************************* 
* 
* File    : xLayer.js 
* 
* Created : 2000/06/08 
* 
* Author  : Roy Whittle  (Roy@Whittle.com) www.Roy.Whittle.com 
* 
* Purpose : To create a cross browser dynamic layers. This 
*		library is based on the library defined in the 
*		excellent book. "JavasScript - The Definitive guide" 
*		by David Flanagan. Published by O'Reilly. 
*		ISBN 1-56592-392-8 
* 
* History 
* Date         Version        Description 
* 
* 2000-06-08	1.0		Initial version 
* 2000-06-17	1.1		Changed function name to setzIndex()
* 2000-06-17	1.2		Changed function name to getzIndex()
* 2000-08-07	1.3		Added event handling functionality
*					from the book.
* 2000-08-15	1.4		Finally! The NS functions are now prototypes.
* 2000-10-14	1.5		Attempting to add NS6 (W3C Standard) functionality
* 2000-11-04	1.6		Added NS6 Event handling
* 2000-11-06	1.7		Added xLayerFromObj - Allows pre existing 
*					layers/divs to gain functionality of xLayer.
* 2000-11-19	1.8		Changed the event handling to use an event object
*					and make the core properties the same.
*					e.type, e.button
*					e.layerX, e.layerY, e.clientX, e.clientY, e.pageX, e.pageY
*					e.keyCode, e.altKey, e.ctrlKey, e.shiftKey
***********************************************************************/
var xLayerNo=0; 
function xLayer(newLayer, x, y) 
{
	if(x==null)x=0; 
	if(y==null)y=0; 
	if(document.layers) 
	{
		if(typeof newLayer == "string")
		{
			this.layer=new Layer(100); 
			this.layer.document.open(); 
			this.layer.document.write(newLayer); 
			this.layer.document.close(); 
		}
		else
			this.layer=newLayer;
		}
	}
	else
	{
		this.layer=newLayer;
	}
}
