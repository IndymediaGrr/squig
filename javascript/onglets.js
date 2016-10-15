//-----------------------------------------------------------------------------
// Gestion de l'affichage par onglets
//-----------------------------------------------------------------------------

function setMenuCookie(nom, valeur, expires)
{
  document.cookie=nom+"="+escape(valeur)+
  ((expires==null) ? "" : ("; expires="+expires.toGMTString()));
}

function tabs_switch(e)
{
   menu = e.parentNode;
   tabs = menu.childNodes;

   for(i = 0; i < tabs.length; i++)
   {
      tab = tabs[i];
      if( !tab || !tab.nodeName || tab.nodeName != 'A') continue;
      tabId = tab.id;
      panId = tabId + '_pan';
      pan = document.getElementById(panId);

	   cl = String(tab.className);
      if(tabId != e.id)
      {
         pan.style.display='none';
         tab.className = cl.replace(/\bon\b/g,"");
      }
      else
      {
         tab.blur();		
         tab.className = cl.replace(/^/,"on ");
         pan.style.display='block';
      }
   }

	setMenuCookie(menu.id + '_id', e.id);

	return false;
}
	
