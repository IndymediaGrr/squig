/** Méthodes pour gérer les cookies de session */

function setCookie(nom, valeur, expires) {
  document.cookie=nom+"="+escape(valeur)+
  ((expires==null) ? "" : ("; expires="+expires.toGMTString()));
}

function arguments_cookies(offset){
  var endstr=document.cookie.indexOf (";", offset);
  if (endstr==-1) endstr=document.cookie.length;
  return unescape(document.cookie.substring(offset, endstr)); 
}

function getCookie(nom) {
  var arg=nom+"=";
  var alen=arg.length;
  var clen=document.cookie.length;
  var i=0;
  while (i<clen){
    var j=i+alen;
    if (document.cookie.substring(i, j)==arg)
       return arguments_cookies(j);
    i=document.cookie.indexOf(" ",i)+1;
    if (i==0) break;
  }
  return null; 
}