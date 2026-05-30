
   setTimeout('init();',2000);
    

function init() {
    //-----------------------------------------------------------
    // Settings:
    // Legacy browser detection retained: Netscape 4, IE4, and early Gecko handling
    ns4 = (document.layers)? true:false;
    ie4 = (document.all)? true:false;
    isNS6 = (!document.all && document.getElementById) ? true : false;
    init1x = init1y = init2x = init2y = 0;
 
    xmouse = ymouse = count = 0;
    speed = 1;
    blinks = 0;
    eyes_color = "blue";

    var semMode = "";
    try {
        var elCtr = document.querySelector(".resources-middle-center[data-edge-focus]");
        if (elCtr && elCtr.getAttribute) {
            semMode = elCtr.getAttribute("data-edge-focus") || "";
        }
        if (!semMode) {
            var elAt = document.querySelector(".resources-middle-center[data-artifact-type]");
            if (elAt && elAt.getAttribute) {
                var ax = elAt.getAttribute("data-artifact-type") || "";
                if (ax === "help_guide") {
                    semMode = "tutorial";
                } else if (ax === "text/markdown") {
                    semMode = "transcript";
                }
            }
        }
    } catch (eSem) {
        semMode = "";
    }
    if (semMode === "tutorial") {
        speed = 2;
        window.lupoBlinkCloseMs = 1500;
        window.lupoBlinkOpenMs = 1800;
        window.lupoBlinkNextMs = 4200;
    } else if (semMode === "transcript") {
        speed = 0.5;
        window.lupoBlinkCloseMs = 2500;
        window.lupoBlinkOpenMs = 2800;
        window.lupoBlinkNextMs = 7000;
    }

    var imgBase = 'images/';
    if (window.LUPO_MAIN_LAYOUT && window.LUPO_MAIN_LAYOUT.publicPath) {
        var pp = String(window.LUPO_MAIN_LAYOUT.publicPath).replace(/\/$/, '');
        imgBase = pp + '/images/';
    } else if (window.LUPO_HDR && window.LUPO_HDR.base) {
        var hb = String(window.LUPO_HDR.base).replace(/\/$/, '');
        imgBase = hb + '/images/';
    }
  
   lidsblock = new Image;
   lidsblock.src = imgBase + 'lids3.png';

   blueeye = new Image;
   lblueeye = new Image;
   browneye = new Image;
   greeneye = new Image;
   redeye = new Image;
   blank = new Image;
   // Preload all sprite assets so the animation stays seamless
   blank.src = imgBase + 'blank.gif';
   lblueeye.src = imgBase + 'blueeye2.gif';   
   blueeye.src = imgBase + 'blueeye.gif';
   browneye.src = imgBase + 'browneye.gif';
   greeneye.src = imgBase + 'greeneye.gif';
   redeye.src = imgBase + 'redeye.gif';
   
   document.tempeyes.src=blank.src;
    //-----------------------------------------------------------
 
	// DynLayer global from layers.js (LupoLayer; DynLayer-compatible move/slide API)
	closedblock = new DynLayer("closedblockDiv")
	closedblock.slideInit()
	closedblock.show()

	backblock = new DynLayer("backblockDiv")
	backblock.slideInit()
	backblock.show()
	
	lefteyeblock = new DynLayer("lefteyeblockDiv")
	lefteyeblock.slideInit()
	lefteyeblock.show()
	
	righteyeblock = new DynLayer("righteyeblockDiv")
	righteyeblock.slideInit()
	righteyeblock.show()

	lidsblock = new DynLayer("lidsblockDiv")
	lidsblock.slideInit()
	lidsblock.show()
 
   // where to put the eyes brah  - love WOLFIE :) 
  ctrPosL = wheretoX;
  ctrPosT = wheretoY;

  // Calculate the initial positions
   // Offsets tuned for original GIF dimensions – adjust only if artwork changes
   // left eye
   init1x = ctrPosL+255;
   init1y = ctrPosT+185;
   // right eye
   init2x = ctrPosL+440;
   init2y = ctrPosT+180;
  
 
  // Set current eye pos variable, to the calculated initial positions
   current1x = init1x;
   current1y = init1y;
   current2x = init2x;
   current2y = init2y;
 
 

  closedblock.slideTo(ctrPosL+195,ctrPosT+166,10,20);
  lidsblock.slideTo(ctrPosL+195,ctrPosT+166,10,20);
  backblock.slideTo(ctrPosL+181,ctrPosT+150,10,20);
  lefteyeblock.slideTo(current1x,current1y,10,20);
  righteyeblock.slideTo(current2x,current2y,10,20);
  
  setTimeout("blink();",200);
  setTimeout('do_eyes()',500); 

   
   document.onmousemove = mouseMove;
 
}

// Capture cursor coordinates with legacy branching (IE event vs Netscape event)
function mouseMove(e) {
	// Set x and y to current pos of mouse
	xmouse = (ie4)? event.x : e.pageX;
	ymouse = (ie4)? event.y+document.body.scrollTop : e.pageY;

	return true;
}
 
function mouseMove2(e) {
     xmouse = e.clientX;
    ymouse = e.clientY;
    return true;
}
 
function closeeyes(){
            blinks++;
            righteyeblock.hide();
            lefteyeblock.hide();
            closedblock.show();
            // every few blinks rotate through the available eye colours
            if((blinks % 5)==3){
            switch(eyes_color){
            	case "blue":
                document.eyeone.src=browneye.src;
                document.eyetwo.src=browneye.src;                  
                eyes_color ="lblue";           
                break;
            	case "lblue":
                document.eyeone.src=lblueeye.src;
                document.eyetwo.src=lblueeye.src;                  
                eyes_color ="brown";           
                break;
            	case "brown":
                document.eyeone.src=greeneye.src;
                document.eyetwo.src=greeneye.src;                  
                eyes_color ="green";           
                break;
            	case "green":
  	             document.eyeone.src=redeye.src;
                 document.eyetwo.src=redeye.src;                
                eyes_color ="red";           
                break; 
                case "red":
                document.eyeone.src=blueeye.src;
                document.eyetwo.src=blueeye.src;                  
                eyes_color ="blue";     
                break;                    
              default:  
                document.eyeone.src=blueeye.src;
                document.eyetwo.src=blueeye.src;                  
                eyes_color ="blue";           
                break;             
            }                      
          }
}  
function openeyes(){
            righteyeblock.show();
            lefteyeblock.show();
            lidsblock.show();
            closedblock.hide();
}

 
// Blink loop: close, open, then schedule the next cycle
function blink(){
   var c = (typeof window.lupoBlinkCloseMs === "number") ? window.lupoBlinkCloseMs : 2000;
   var o = (typeof window.lupoBlinkOpenMs === "number") ? window.lupoBlinkOpenMs : 2300;
   var n = (typeof window.lupoBlinkNextMs === "number") ? window.lupoBlinkNextMs : 5500;
   setTimeout("closeeyes();", c);
   setTimeout("openeyes();", o);
   setTimeout("blink();", n);
}

// Smoothly guide each pupil toward the cursor while staying within eye bounds
function do_eyes()
{
	var targetx1;
	var targety1;
	var targetx2;
	var targety2;

	// Ease toward cursor by moving a fraction of the distance each frame
	targetx1 = ((xmouse - init1x) / 30)  + init1x;
	targety1 = ((ymouse - init1y) / 30)  + init1y;
	targetx2 = ((xmouse - init2x) / 30)  + init2x;
	targety2 = ((ymouse - init2y) / 30)  + init2y;
	
	// Change current eye pos vars, if they should be changed	
	// Boundaries prevent the pupils from drifting outside the eyelids
	if (targety1 > current1y && current1y < init1y + 13) {current1y += speed;}
	if (targety1 < current1y && current1y > init1y - 7) {current1y -= speed;}
	if (targetx1 > current1x && current1x < init1x + 11) {current1x += speed;}
	if (targetx1 < current1x && current1x > init1x - 14) {current1x -= speed;}
	if (targety2 > current2y && current2y < init2y + 13) {current2y += speed;}
	if (targety2 < current2y && current2y > init2y - 7) {current2y -= speed;}
	if (targetx2 > current2x && current2x < init2x + 20) {current2x += speed;}
	if (targetx2 < current2x && current2x > init2x - 10) {current2x -= speed;}


	// Set eyes to updated pos	
	lefteyeblock.left = current1x;
	lefteyeblock.top = current1y;
	righteyeblock.left = current2x;
	righteyeblock.top = current2y;
  
  lefteyeblock.slideTo(current1x,current1y,10,20);
  righteyeblock.slideTo(current2x,current2y,10,20);
  
	setTimeout('do_eyes()', 20);
}
 
 function openchatwindow() { 
    window.open( '/lh/livehelp.php?what=chatinsession&department=1', 'chat54050872', 'width=500,height=500,menubar=no,scrollbars=1,resizable=1' );
 }

 function showthex(){
  const xBtn = document.getElementById("eye-close-btn");
   xBtn.style.visibility = "visible";
}
 
 function hidethex(){
   const xBtn = document.getElementById("eye-close-btn"); 
   xBtn.style.visibility = "hidden";
}


 function close_all_eye_divs() {
    // JUST MOVE THEM OUT OF VIEW 
     wheretoX = window.innerWidth + 350;
     wheretoY =  window.innerHeight + 370; // updates the global variable 
     init();
     livehelpblock.slideTo(window.innerWidth+160,window.innerHeight+240,10,20);
     const xBtn = document.getElementById("eye-close-btn"); xBtn.style.visibility = "hidden";
}
setTimeout('hidethex()', 2000);
