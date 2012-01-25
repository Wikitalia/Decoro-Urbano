{*
 * ----------------------------------------------------------------------------
 * Decoro Urbano version 0.2.1
 * ----------------------------------------------------------------------------
 * Copyright Maiora Labs Srl (c) 2012
 * ----------------------------------------------------------------------------   
 * 
 * This file is part of Decoro Urbano.
 * 
 * Decoro Urbano is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Decoro Urbano is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with Decoro Urbano.  If not, see <http://www.gnu.org/licenses/>.
 *}

{include file="includes/header.tpl"}

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/mappa_elenco.js"></script>
<script type="text/javascript" src="js/popup.js"></script>
<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>


<div id="filtri">
    Tipo segnalazione:<br />
    <form name="tipi" id="tipi">
    	<ul id="naviCategories">
      	<li>Filtra per categoria</li>
        <li>Rifiuti</li>
        <li>Select</li>
      </ul>
      <input type="radio" name="tipo_segnalazione" id="tipo_segnalazione" value="0" checked="true" onClick="segnalazioni_tipi_filtra();">Tutti
      {*<?foreach(data_get("tab_tipi") as $tipo) {?>*}
      <br />
      <input type="radio" name="tipo_segnalazione" id="tipo_segnalazione" value="<?=$tipo['id_tipo']?>" onClick="segnalazioni_tipi_filtra();"><?=$tipo['nome']?>
      {*<?}?>*}
    </form>
</div>
<div id="map_container">
	<div id="map_canvas"></div>
</div>

<script type="text/javascript">
du_map.init('#map_canvas',roma,15);
</script>

{include file="includes/footer.tpl"}