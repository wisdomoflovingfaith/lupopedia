var xmlhttp = false; 
var XMLHTTP_supported = false;

function gettHTTPreqobj(){
	try { 
	xmlhttp = new XMLHttpRequest(); 
 } catch (e1) { 
 	 try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
   } catch (e2) { 
     try { 
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
     } catch (e3) { 
    xmlhttp = false; 
   } 
  } 
 }
 return xmlhttp;
}

function buildFormBody(params){
    var pairs = [];
    for (var key in params){
        if (!Object.prototype.hasOwnProperty.call(params, key)){ continue; }
        var value = params[key];
        if (value === undefined || value === null){ value = ''; }
        pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
    }
    return pairs.join('&');
}

function sendRequest(options){
    var method = options.method || 'GET';
    var url = options.url;
    var body = options.body || null;
    var fallback = options.fallback;

    if (window.fetch){
        var fetchOpts = {
            method: method,
            credentials: 'same-origin',
            cache: 'no-store'
        };
        if (method.toUpperCase() === 'POST'){
            fetchOpts.headers = {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'};
            fetchOpts.body = (typeof body === 'string') ? body : buildFormBody(body || {});
        }
        fetch(url, fetchOpts).then(function(response){
            return response.text();
        }).then(function(text){
            if (typeof options.onSuccess === 'function'){
                options.onSuccess(text);
            }
        }).catch(function(){
            if (typeof fallback === 'function'){
                fallback();
            }
        });
    } else if (typeof fallback === 'function'){
        fallback();
    }
}

function loadXMLHTTP() { 
     randu=Math.round(Math.random()*99);
     loadOK('xmlhttp.php?whattodo=ping&rand='+ randu); 
} 

function loadOK(fragment_url) { 
    sendRequest({
        method: 'GET',
        url: fragment_url,
        onSuccess: function(text){
            if (text === 'OK'){
                XMLHTTP_supported = true;
                checkXMLHTTP();
            }
        },
        fallback: function(){
            xmlhttp = gettHTTPreqobj();
            if (!xmlhttp){ return; }
            xmlhttp.open("GET", fragment_url, true); 
            xmlhttp.onreadystatechange = function() { 
             if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { 
               isok = xmlhttp.responseText;
               if(isok == "OK")
                  XMLHTTP_supported = true;   
                  checkXMLHTTP();
             } 
            } 
            try { xmlhttp.send(null); } catch(whocares){}
        }
    });
}


 // XMLHTTP ----------------------------------------------------------------- 
 function oXMLHTTPStateHandler() { 
     if(typeof oXMLHTTP!='undefined') { 
        if( oXMLHTTP.readyState==4 ) {         
          if( oXMLHTTP.status==200 ) {                  
               try { 
                 resultingtext = oXMLHTTP.responseText;
               } catch(e) { 
                 resultingtext ="error=1;";
               }
               ExecRes(unescape(resultingtext)); 
               delete oXMLHTTP; 
               oXMLHTTP=false;
            } else { 
            	return false;
             }   
           }
         } 
      } 

function oXMLHTTPStateHandler2() { 
     if(typeof oXMLHTTP!='undefined') { 
        if( oXMLHTTP.readyState==4 ) {         
          if( oXMLHTTP.status==200 ) {                  
               try { 
                 resultingtext = oXMLHTTP.responseText;
               } catch(e) { 
                 resultingtext ="error=1;";
               }
               ExecRes2(unescape(resultingtext)); 
               delete oXMLHTTP; 
               oXMLHTTP=false;
            } else { 
            	return false;
             }   
           }
         } 
      }    
      
      
      
function oXMLHTTPStateHandler3() { 
     if(typeof oXMLHTTP!='undefined') { 
        if( oXMLHTTP.readyState==4 ) {         
          if( oXMLHTTP.status==200 ) {                  
               try { 
                 resultingtext = oXMLHTTP.responseText;
               } catch(e) { 
                 resultingtext ="error=1;";
               }
               ExecRes3(unescape(resultingtext)); 
               delete oXMLHTTP; 
               oXMLHTTP=false;
            } else { 
            	return false;
             }   
           }
         } 
      }   
      
      
      
function oXMLHTTPStateHandler4() { 
     if(typeof oXMLHTTP!='undefined') { 
        if( oXMLHTTP.readyState==4 ) {         
          if( oXMLHTTP.status==200 ) {                  
               try { 
                 resultingtext = oXMLHTTP.responseText;
               } catch(e) { 
                 resultingtext ="error=1;";
               }
               ExecRes4(unescape(resultingtext)); 
               delete oXMLHTTP; 
               oXMLHTTP=false;
            } else { 
            	return false;
             }   
           }
         } 
      }                
      function PostForm(sURL, sPostData) { 
        sendRequest({
            method: 'POST',
            url: sURL,
            body: sPostData,
            onSuccess: function(text){
                try { ExecRes(unescape(text)); } catch(err){}
            },
            fallback: function(){
                 oXMLHTTP = gettHTTPreqobj(); 
                 if( typeof(oXMLHTTP)!="object" ) return false; 
                 oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler; 
                 try { 
                    oXMLHTTP.open("POST", sURL, true); 
                 } catch(er) { 
                    return false; 
                 }    
                 oXMLHTTP.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
                 try { oXMLHTTP.send(sPostData); } catch(whocares){}
                 return true; 
            }
        });
      }  
      
      function GETForm(sURL) { 
        sendRequest({
            method: 'GET',
            url: sURL,
            onSuccess: function(text){
                try { ExecRes(unescape(text)); } catch(err){}
            },
            fallback: function(){
                 oXMLHTTP = gettHTTPreqobj();          
                 if( typeof(oXMLHTTP)!="object" ) return false;          
                 oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler; 
                 try { 
                    oXMLHTTP.open("GET", sURL, true); 
                 } catch(er) { 
                    return false; 
                 }    
                 try { oXMLHTTP.send(null); } catch(whocares){}
                 return true; 
            }
        });
      }
      function GETForm2(sURL) { 
        sendRequest({
            method: 'GET',
            url: sURL,
            onSuccess: function(text){
                try { ExecRes2(unescape(text)); } catch(err){}
            },
            fallback: function(){
                 oXMLHTTP = gettHTTPreqobj();          
                 if( typeof(oXMLHTTP)!="object" ) return false;          
                 oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler2; 
                 try { 
                    oXMLHTTP.open("GET", sURL, true); 
                 } catch(er) { 
                    return false; 
                 }    
                 try { oXMLHTTP.send(null); } catch(whocares){}
                 return true; 
            }
        });
      }
      function GETForm3(sURL) { 
        sendRequest({
            method: 'GET',
            url: sURL,
            onSuccess: function(text){
                try { ExecRes3(unescape(text)); } catch(err){}
            },
            fallback: function(){
                 oXMLHTTP = gettHTTPreqobj();          
                 if( typeof(oXMLHTTP)!="object" ) return false;          
                 oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler3; 
                 try { 
                    oXMLHTTP.open("GET", sURL, true); 
                 } catch(er) { 
                    return false; 
                 }    
                 try { oXMLHTTP.send(null); } catch(whocares){}
                 return true; 
            }
        });
      }      
      
      function GETForm4(sURL) { 
        sendRequest({
            method: 'GET',
            url: sURL,
            onSuccess: function(text){
                try { ExecRes4(unescape(text)); } catch(err){}
            },
            fallback: function(){
                 oXMLHTTP = gettHTTPreqobj();          
                 if( typeof(oXMLHTTP)!="object" ) return false;          
                 oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler4; 
                 try { 
                    oXMLHTTP.open("GET", sURL, true); 
                 } catch(er) { 
                    return false; 
                 }    
                 try { oXMLHTTP.send(null); } catch(whocares){}
                 return true; 
            }
        });
      }      
      
      
// getting started:
xmlhttp = gettHTTPreqobj();
          