<?php
/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2007                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

//if (!defined("_ECRIRE_INC_VERSION")) return;

// Cette fonction memorise dans un tableau indexe par son 5e arg
// un evenement decrit par les 4 autres (date, descriptif, titre, URL). 
// Appellee avec une date nulle, elle renvoie le tableau construit.
// l'indexation par le 5e arg autorise plusieurs calendriers dans une page

// http://doc.spip.org/@agenda_memo
function squig_agenda_memo($date=0 , $descriptif='', $titre='', $url='', $cal='')
{

/*
	$tta = split("\n",wordwrap(texte_script($titre),20));
	$tt = $tta[0];

   if( $tt != $titre) $tt = $tt . "&nbsp;(...)";
*/
   $tt = $titre;

  static $agenda = array();
  if (!$date) return $agenda;
  $idate = date_ical($date);
  $cal = trim($cal); // func_get_args (filtre alterner) rajoute \n !!!!
  $agenda[$cal][(date_anneemoisjour($date))][] =  array(
			'CATEGORIES' => $cal,
			'DTSTART' => $idate,
			/*	'DTEND' => $idate,*/
                        'DESCRIPTION' => texte_script($descriptif),
                        'SUMMARY' => $tt,
                        'URL' => $url);
  // toujours retourner vide pour qu'il ne se passe rien
  return "";
}


// Cette fonction recoit:
// - un nombre d'evenements, 
// - une chaine a afficher si ce nombre est nul, 
// - un type de calendrier
// -- et une suite de noms N.
// Elle demande a la fonction precedente son tableau
// et affiche selon le type les elements indexes par N dans ce tableau.
// Si le suite de noms est vide, tout le tableau est pris
// Ces noms N sont aussi des classes CSS utilisees par http_calendrier_init

// http://doc.spip.org/@agenda_affiche
function squig_agenda_affiche($i)
{
	include_spip('inc/agenda');
	include_spip('inc/minipres');
	$args = func_get_args();
	$nb = array_shift($args); // nombre d'evenements (on pourrait l'afficher)
	$sinon = array_shift($args);
	$type = array_shift($args);
	if (!$nb){ 
		return http_calendrier_init('', $type, '', '', str_replace('&amp;', '&', self()), $sinon);
	}	
	$agenda = squig_agenda_memo(0);
	$evt = array();
	foreach (($args ? $args : array_keys($agenda)) as $k) {  
		if (is_array($agenda[$k]))
		foreach($agenda[$k] as $d => $v) { 
			$evt[$d] = $evt[$d] ? (array_merge($evt[$d], $v)) : $v;
		}
	}
	$d = array_keys($evt);

	if (count($d)){
		$mindate = min($d);
		$start = strtotime($mindate);
	}
	else {  
		$mindate = ($j=_request('jour')) * ($m=_request('mois')) * ($a=_request('annee'));  
  		if ($mindate)
			$start = mktime(0,0,0, $j, $m, $a);
  		else $start = mktime(0,0,0);
	}
	if ($type != 'periode')
		$evt = array('', $evt);
	else {
		$min = substr($mindate,6,2);
		$max = $min + ((strtotime(max($d)) - $start) / (3600 * 24));
		if ($max < 31) $max = 0;
		$evt = array('', $evt, $min, $max);
		$type = 'mois';
	}
	return http_calendrier_init($start, $type, '', '', str_replace('&amp;', '&', self()), $evt);
}

?>
