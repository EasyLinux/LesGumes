<?php include_once("webmaster/define.php");
  include_once("mailToReferent.php");   ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
		<div id="en_tete">
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include_once("includes/menu_gauche.php") ?>
			<table>
			<tr>
  			<td style="text-align:center; width: 20%">                                        
              <a style="text-align:center; color:yellow" href="http://maps.google.fr/maps/ms?msid=203976788313820687876.0004b0d1758141d2a3600&amp;msa=0&amp;ll=47.057961,-1.321106&amp;spn=0.696991,1.220856" 
                onclick="window.open(this.href); return false;">
                <img src="includes/plan.jpg" alt="Ouvrir la carte dans une nouvelle fenêtre" title="Cliquer ici pour ouvrir la carte dans une nouvelle fenêtre"/></a><br />
              <a style="text-align:center; color:yellow" href="http://maps.google.fr/maps/ms?msid=203976788313820687876.0004b0d1758141d2a3600&amp;msa=0&ll=47.057961,-1.321106&spn=0.696991,1.220856" 
                title="Cliquer ici pour ouvrir la carte dans une nouvelle fenêtre" onclick="window.open(this.href); return false;">Repérer les adresses sur une carte</a>     
			</td>
			<td> 			
				<h1 class="texte" style="text-align:center; "> Nos producteurs et leurs contrats</h1>
			</td>
			</tr>
			</table>
  			<table border="1">
         <tr>
           <th style="text-align:center;color:orange;margin-bottom:0;"> Producteur </th>
           <th style="text-align:center;color:orange;margin-bottom:0;"> Coordonnées </th>
           <th style="text-align:center;color:orange;margin-bottom:0;"> Contrat </th>
           <th style="text-align:center;color:orange;margin-bottom:0;"> Référent pour l'AMAP </th>      
         </tr>
        
	<tr><td>
			<h3 style="text-align:center;color:orange;margin-bottom:0;">Les rangs d'oignons</h3>
			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">  Benjamin Cochin <br />
				 <a href="page_rangs_oignons.php" title="Cliquer ici pour voir la page du producteur"><img src="images/plus.jpg" alt="ouvrir la page du producteur" /> 
				</a></h5>
        </td><td>
			<h5 class="texte" style="text-align:center">
					  Allée de Clermont, 44850 Le Cellier<br />
           		<a style="color:#00EEEE" href="mailto:lesrangsdoignons@gmail.com" title="Cliquer ici pour envoyer un mail au producteur">lesrangsdoignons@gmail.com</a><br />
      				07 68 46 48 00</h5>
      	</td><td>
      		<a style="color:#00EEEE" href="documentation/legumes/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Panier de Légumes</a>
      	</td> <td>
			<h5 class="texte" style="text-align:center">
				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_legumes', $string, $mailto); ?>	
			<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
         </td> </tr>  
		 
		 
	<tr> <td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">Les Vergers du Moulin</h3>
			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">	Maël Sinoir	
				<a href="page_marie_paule.php"  title="Cliquer ici pour voir la page du producteur">
				<br /><img src="images/plus.jpg" alt="ouvrir la page du producteur"/></a></h5>
      	</td><td> 
			<h5 class="texte" style="text-align:center">
					Le Moulin des Noues<br />
					44690 Maisdon-sur-Sèvre<br />
    				 <a style="color:#00EEEE" href="mailto:lesvergersdumoulin@free.fr" title="Cliquer ici pour envoyer un mail au producteur">lesvergersdumoulin@free.fr</a><br />
					 06 65 39 10 10</h5>
  		</td> <td>
			<a style="color:#00EEEE" href="documentation/pommes/contrat.pdf" title="Cliquer ici pour ouvrir le contrat"> Pommes / Poires</a>
		</td> <td>
			<h5 class="texte" style="text-align:center">
				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_pommes', $string, $mailto); ?>	
			<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
         </td> </tr>  
		 
		 
	<tr> <td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">EARL Bretteurs</h3>
			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">	René Peaudeau <br />
				<a style="color:yellow" href="page_rene.php"  title="Cliquer ici pour voir la page du producteur">
				<img src="images/plus.jpg" alt="ouvrir la page du producteur" title="Cliquer ici pour ouvrir la page du producteur"/></a></h5>
  		</td><td>
			<h5 class="texte" style="text-align:center">
				La Charrié 44650 Legé<br />
				 <a style="color:#00EEEE" href="mailto:peaudeau.rene@orange.fr" title="Cliquer ici pour envoyer un mail du producteur">peaudeau.rene@orange.fr</a><br />
				 06 22 82 45 60</h5>
  		</td> <td>
			<a style="color:#00EEEE" href="documentation/oeufs/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Oeufs &amp; Poulets</a>
  		</td> <td>
			<h5 class="texte" style="text-align:center">
			  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_oeufs', $string, $mailto); ?>	
				<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
        </td></tr>  
     
	 
	<tr> <td>
      	 <h3 style="text-align:center;color:orange;margin-bottom:0">Dominique Grandjouan</h3>
		<h5 class="texte" style="text-align:center"> 
			<a style="color:yellow" href="page_dominiqueGrandJouan.pdf"  title="Cliquer ici pour voir la page du producteur">
			<img src="images/plus.jpg" alt="ouvrir la page du producteur"/></a></h5>
  		 
		</td> <td>
			<h5 class="texte" style="text-align:center">
			44320 Saint Viaud	<br /> 
 <a style="color:#00EEEE" href="mailto:PainPaysan44@gmail.com" title="Cliquer ici pour envoyer un mail au producteur">PainPaysan44@gmail.com</a><br />
				 06 63 06 05 16</h5>			
		</td><td>
			<a style="color:#00EEEE" href="documentation/pain/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Pain</a>
		</td> <td>
			<h5 class="texte" style="text-align:center">
				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_pain', $string, $mailto); ?>	
			<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
      </td></tr>  
     
	 
     <tr><td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">La ferme de Rublé</h3>
  			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">
				<a href="https://www.facebook.com/fermederuble/"  title="Cliquer ici pour voir la page du producteur">
				<img src="images/plus.jpg" alt="ouvrir la page du producteur" /></a></h5>
	</td><td>
        <h5 class="texte" style="text-align:center"> Rublé 44310 Saint Colomban<br />
         <a style="color:#00EEEE" href="mailto:lafermederuble@orange.fr" title="Cliquer ici pour envoyer un mail à la ferme de Rublé">lafermederuble@orange.fr</a><br />
				 02 40 73 67 03</h5>
	</td><td>
         <a style="color:#00EEEE" href="documentation/ProduitsLaitiers/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Produits Laitiers</a><br />
	</td><td>
        <h5 class="texte" style="text-align:center">
			<?php $string = ""; $mailto = ""; MailToReferent( 'amap_produits_laitiers', $string, $mailto); ?>						  
			<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a><br />		
        </h5>
		</td> </tr>
		
	<tr><td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">La ferme de Rublé</h3>
  			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">
				<a href="http://lafermederuble.fr/"  title="Cliquer ici pour voir la page de René">
				<img src="images/plus.jpg" alt="ouvrir la page du producteur"/></a></h5>
	</td><td>
			<h5 class="texte" style="text-align:center"> Rublé 44310 Saint Colomban<br />
			<a style="color:#00EEEE" href="mailto:lafermederuble@orange.fr" title="Cliquer ici pour envoyer un mail au producteur">lafermederuble@orange.fr</a><br />
				 02 40 73 67 03</h5>
	</td><td>
			<a style="color:#00EEEE" href="documentation/viandes/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Viandes porc/boeuf/veau</a><br /><br />
			<a style="color:#00EEEE" href="documentation/viandes/contratCharcuterie.pdf" title="Cliquer ici pour ouvrir le contrat">Charcuterie</a>
	</td><td>
        <h5 class="texte" style="text-align:center">
			<?php $string = ""; $mailto = ""; MailToReferent( 'amap_viandes', $string, $mailto); ?>	
			<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
        </h5>
		</td> </tr>
		
	<tr><td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">La ferme de Jean Jean</h3>
  			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">	
				<a  href="http://lafermederuble.fr/"  title="Cliquer ici pour voir la page du producteur">
				<img src="images/plus.jpg" alt="ouvrir la page du producteur"/></a></h5>
	 </td><td>
			<h5 class="texte" style="text-align:center"> 17 demi-boeuf<br />  44310 la limouzinière<br />
				  06 87 87 78 87</h5>
	</td><td>
			<a style="color:#00EEEE" href="documentation/agneaux/contratAgneau.pdf" title="Cliquer ici pour ouvrir le contrat">Viande d'agneaux</a>
	</td><td>
        <h5 class="texte" style="text-align:center">
			<?php $string = ""; $mailto = ""; MailToReferent( 'amap_agneaux', $string, $mailto); ?>	
			<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
        </h5>
		</td> </tr>
		
		
	<tr> <td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">La chèvrerie du BonAir</h3>
			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0"> Emilie Duval<br />
				<a style="color:yellow" href="documentation/chevre/VisiteChevrerieDuBonAir.pdf"  title="Cliquer ici pour voir la page du producteur" onclick="window.open(this.href); return false;">
				<img src="images/plus.jpg" alt="ouvrir la page du producteur" /></a></h5>
			</h5>
		</td><td>
            <h5 class="texte" style="text-align:center">
            La Girardière <br /> 
            44310 La Limouzinière
			 <br /> 
			 <a style="color:#00EEEE" href="mailto:sim_leila@hotmail.com" title="Cliquer ici pour envoyer un mail au producteur">	chevreriedubonair@hotmail.com</a>
            <br />  06 50 20 92 99</h5>
  		</td> <td>
			<a style="color:#00EEEE" href="documentation/chevre/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Fromage de chèvre</a>
		</td><td>
			<h5 class="texte" style="text-align:center">
				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_chevre', $string, $mailto); ?>	
				<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
        </td> </tr>    
         
		 
      <tr> <td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">La ferme du Plantis</h3>
			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0"> Pierre Anfray &amp; Azélie Lelong<br />
				<a href="documentation/tisanes/articleTisanesLeCellier.pdf"  title="Cliquer ici pour voir la page du producteur" onclick="window.open(this.href); return false;">
				<img src="images/plus.jpg" alt="ouvrir la page du producteur" /></a></h5>
			</h5>
		</td><td>
       	  	<h5 class="texte" style="text-align:center">
    				44850 Le Cellier<br />
    				 <a style="color:#00EEEE" href="mailto:symbiose.azelie@gmail.com" title="Cliquer ici pour envoyer un mail au producteur">symbiose.azelie@gmail.com</a><br />
    				 07 85 35 19 15</h5>
    	</td> <td>
			<a style="color:#00EEEE" href="documentation/tisanes/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Tisanes</a>
		</td><td>
            <h5 class="texte" style="text-align:center">
    				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_tisanes', $string, $mailto); ?>	
				<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
            </h5>
        </td></tr>    
		
		
	
         
		 
     <tr> <td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">Coopérative Alima</h3>
			<h5 style="text-align:center;margin-top:0;margin-bottom:0">	
			  <a style="color:yellow" href="http://www.alimea.fr/" onclick="window.open(this.href); return false;" title="Cliquer ici pour voir la page du producteur">
			  <img src="images/plus.jpg" alt="ouvrir la page du producteur" title="Cliquer ici pour ouvrir la page du producteur"/></a>
			</h5>
		</td><td>
			<h5 class="texte" style="text-align:center">
			Bravone <br />
			20230 Linguizzetta <br />
			04 95 38 88 74</h5>
		</td><td> 
			<a style="color:#00EEEE" href="documentation/agrumes/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Agrumes de Ccrse</a>
		</td> <td>
			<h5 class="texte" style="text-align:center">
				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_agrumes', $string, $mailto); ?>	
			<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
        </td> </tr>   

		
	<tr><td>
			<h3 style="text-align:center;color:orange;margin-bottom:0">SARL Agari Breizh</h3>
				<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">	Anne Gragnic <br />
			  <a  href="http://www.biogolfe.org/evenement/visite-de-la-champignonniere-agari-breizh-a-moustoir-remungol-56/" onclick="window.open(this.href); return false;" title="Cliquer ici pour des infos sur la productrice">
			  <img src="images/plus.jpg" alt="ouvrir la page du producteur" /></a>
			</h5>			
			
		</td><td>
			<h5 class="texte" style="text-align:center">
			Keraffray<br /> 
			56500 Moustoir-Remungol<br />
				<a style="color:#00EEEE" href="mailto:agaribreizh@free.fr" title="Cliquer ici pour envoyer un mail au producteur">agaribreizh@free.fr</a>
			</h5>
		</td> <td> 
			<a style="color:#00EEEE" href="documentation/champignons/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Champignons de Paris</a>
		</td> <td>
			<h5 class="texte" style="text-align:center">
  				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_champignons', $string, $mailto); ?>	
				<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
        </td> </tr>	
		
		
		
	<tr><td>
			<h3 style="text-align:center;color:orange;margin-bottom:0;">Fabrice Moyon</h3>
			<h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">
				<a href="http://leslegumesbiodesaison.fr/"  title="Cliquer ici pour voir la page du producteur">
				<img src="images/plus.jpg" alt="ouvrir la page du producteur"/></a></h5>
        </td><td>
			<h5 class="texte" style="text-align:center">328, route du Moulin du Bel Air <br />44430 Le Loroux Bottereau <br />
				 <a style="color:#00EEEE" href="mailto:myriammoyon@gmail.com" title="Cliquer ici pour envoyer un mail au producteur">myriammoyon@gmail.com</a><br />
				 06 58 76 13 35
			</h5>
      	</td><td>
      			<a style="color:#00EEEE" href="documentation/kiwis/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Kiwis</a>
		</td><td>
              <h5 class="texte" style="text-align:center">
      			 <?php $string = ""; $mailto = ""; MailToReferent( 'amap_kiwis', $string, $mailto); ?>	
                <a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
              </h5>
        </td></tr>
		
		
	<tr><td>
			 <h3 style="text-align:center;color:orange;margin-bottom:0;">Magali Delaunay</h3>
			 <h5 style="text-align:center;margin-top:0;margin-bottom:0"> 
			 <a style="color:yellow" href="/documentation/pates/presentation.pdf" onclick="window.open(this.href); return false;" title="Cliquer ici pour voir la description"><img src="images/plus.jpg" alt="ouvrir la page du producteur" title="Cliquer ici pour ouvrir la page du producteur"/></a> </h5>		  
		</td><td>
			<h5 class="texte" style="text-align:center">
			Le bois vert<br />
			Varades<br />
			<a style="color:#00EEEE" href="mailto:magdelaunay@hotmail.fr" title="Cliquer ici pour envoyer un mail au producteur">magdelaunay@hotmail.fr</a><br />
			06 12 93 50 10
			</h5>	
		</td><td>
			<a style="color:#00EEEE" href="documentation/pates/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Pâtes</a>
		</td><td>
			<h5 class="texte" style="text-align:center">
				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_pates', $string, $mailto); ?>	
				<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
		</td> </tr>
		
		
	<tr><td>
			 <h3 style="text-align:center;color:orange;margin-bottom:0;">Frédéric Herrmann</h3>
			 <h5 style="text-align:center;margin-top:0;margin-bottom:0">	
			  <a style="color:yellow" href="http://www.madeincoueron.fr/producteurs/troisieme-producteur/" onclick="window.open(this.href); return false;" title="Cliquer ici pour voir la page du producteur"><img src="images/plus.jpg" alt="ouvrir la page du producteur" title="Cliquer ici pour ouvrir la page du producteur"/></a>
			</h5>
		</td> <td>
			<h5 class="texte" style="text-align:center">
				7 rue de Bellevue<br />
				44220 Couëron<br />
				<a style="color:#00EEEE" href="mailto:frederic-herrmann@orange.fr" title="Cliquer ici pour envoyer un mail au producteur">frederic-herrmann@orange.fr</a><br />
				06 51 21 00 13
			</h5>
		</td> <td>
				<a style="color:#00EEEE" href="documentation/miel/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Miel</a>
		</td><td>
			<h5 class="texte" style="text-align:center">
			  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_miel', $string, $mailto); ?>	
				<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
		</td> </tr>
		
		
	<tr><td>
			 <h3 style="text-align:center;color:orange;margin-bottom:0;">Ferme du Marais Champs</h3>
			 <h5 style="color:yellow;text-align:center;margin-top:0;margin-bottom:0">	Sébastien Pageot<br />
			  <a style="color:yellow" href="http://blogfermedumaraischamps.blogspot.fr/" onclick="window.open(this.href); return false;" title="Cliquer ici pour voir la page du producteur">
			  <img src="images/plus.jpg" alt="ouvrir la page du producteur" /></a>
			</h5>
		</td><td>
			<h5 class="texte" style="text-align:center">
			Nombreuil <br />
			44580 Bourgneuf en Retz <br />
			<a style="color:#00EEEE" href="mailto:ferme.marais.champs@wanadoo.fr" title="Cliquer ici pour envoyer un mail au producteur">ferme.marais.champs@wanadoo.fr</a><br />
			02 40 21 97 63
			</h5>	
		</td><td>
			<a style="color:#00EEEE" href="documentation/tommes/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Tomme de vache</a>
		</td><td>
		  <h5 class="texte" style="text-align:center">
				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_tommes', $string, $mailto); ?>	
			<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
		  </h5>
		</td></tr>

		
	<tr><td>
			<h3 style="text-align:center;color:orange;margin-bottom:0;">Les pêcheurs de l'ile d'Yeu</h3>
			<h5 style="text-align:center;margin-top:0;margin-bottom:0">  
				<a style="color:yellow"  href="/documentation/poissons/flyer2016.pdf" title="Cliquer ici pour voir la page des pecheurs"> <img src="images/plus.jpg" alt="ouvrir la page du producteur" title="Cliquer ici pour ouvrir la page du producteur"/> 
				</a></h5>
        </td><td>
             <h5 class="texte" style="text-align:center"></h5>
           		
      	</td> <td>
				<a style="color:#00EEEE" href="documentation/poissons/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Poissons</a>
		</td><td>
              <h5 class="texte" style="text-align:center">
      				  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_poissons', $string, $mailto); ?>	
                <a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
              </h5>
		</td> </tr>
		
		<tr><td>
			 <h3 style="text-align:center;color:orange;margin-bottom:0;">Gaec Saint-Hubert</h3>
			 <h5 style="text-align:center;margin-top:0;color:yellow">	Anne Chouin <br />
			  <a style="color:#00EEEE" href="https://www.terramillet.com/" title="Lien sur terra www.terramillet.com">découverte du millet</a>
			  et <a style="color:#00EEEE" href="https://www.terramillet.com/cuisiner-les-millets/" title="les recettes de millets">ses recettes</a>
			</h5>
		</td> <td>
			<h5 class="texte" style="text-align:center">
				Saint Hubert 
				44270 MACHECOUL<br />
				<a style="color:#00EEEE" href="mailto:chouin.annie@neuf.fr" title="Cliquer ici pour envoyer un mail au producteur">chouin.annie@neuf.fr</a><br />
				07 80 30 72 92
			</h5>
		</td> <td>
				<a style="color:#00EEEE" href="documentation/millet/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Millet &amp; Lentilles</a>
		</td><td>
			<h5 class="texte" style="text-align:center">
			  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_millet', $string, $mailto); ?>	
				<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
		</td> </tr>
		
		<tr><td>
			 <h3 style="text-align:center;color:orange;margin-bottom:0;">Brasserie La Conviviale</h3>
			 <h5 style="text-align:center;margin-top:0;color:yellow">Nicolas Burel &amp; Etienne Borré<br />
			  <a style="color:#00EEEE" href="https://www.bieresbretonnes.fr/brasseries/brasserie-la-conviviale/" title="site du producteur">
			  <img src="images/plus.jpg" alt="ouvrir la page du producteur" title="Cliquer ici pour ouvrir la page des producteurs"/> </a>
			</h5>
		</td> <td>
			<h5 class="texte" style="text-align:center">
				44690 Château-Thébaud<br />
				<a style="color:#00EEEE" href="mailto:contact@brasserielaconviviale.com" title="Cliquer ici pour envoyer un mail aux producteurs">contact@brasserielaconviviale.com
				</a>
			</h5>
		</td> <td>
				<a style="color:#00EEEE" href="documentation/bieres/contrat.pdf" title="Cliquer ici pour ouvrir le contrat">Bières</a>
		</td><td>
			<h5 class="texte" style="text-align:center">
			  <?php $string = ""; $mailto = ""; MailToReferent( 'amap_bieres', $string, $mailto); ?>	
				<a style="color:yellow" href="<?php echo $mailto?>"><?php echo $string?></a>
			</h5>
		</td> </tr>
		
         
    </table>
    
    
		</div>		
	
	</body>
</html>
