// JavaScript Document


/*********************************************************************
 * No onMouseOut event if the mouse pointer hovers a child element 
 * *** Please do not remove this header. ***
 * This code is working on my IE7, IE6, FireFox, Opera and Safari
 * 
 * Usage: 
 * <div onMouseOut="fixOnMouseOut(this, event, 'JavaScript Code');"> 
 *		So many childs 
 *	</div>
 *
 * @Author Hamid Alipour Codehead @ webmaster-forums.code-head.com		
**/
function is_child_of(parent, child) {
	if( child != null ) {			
		while( child.parentNode ) {
			if( (child = child.parentNode) == parent ) {
				return true;
			}
		}
	}
	return false;
}
function fixOnMouseOut(element, event, JavaScript_code) {
	var current_mouse_target = null;
	if( event.toElement ) {				
		current_mouse_target 			 = event.toElement;
	} else if( event.relatedTarget ) {				
		current_mouse_target 			 = event.relatedTarget;
	}
	if( !is_child_of(element, current_mouse_target) && element != current_mouse_target ) {
		eval(JavaScript_code);
	}
}
/*********************************************************************/

// Note that using Google Gears requires loading the Javascript
// at http://code.google.com/apis/gears/gears_init.js

/* Inizio definizioni per oggetto array marker */
google.maps.Map.prototype.markers = new Array();

google.maps.Map.prototype.addMarker = function(marker) {
	this.markers[this.markers.length] = marker;
};

google.maps.Map.prototype.getMarkers = function() {
	return this.markers
};

google.maps.Map.prototype.clearMarkers = function() {
	for(var i=0; i<this.markers.length; i++){
		this.markers[i].setMap(null);
	}
	this.markers = new Array();
};
/* Fine definizioni per oggetto array marker */



/* Inizio definizioni per oggetto popup */
popup.prototype = new google.maps.OverlayView();

function popup() {

  this.div_ = null;
  this.visible_ = false;
  this.content_ = "";
  this.position_ = null;
  this.width_ = "250";
  this.height_ = "70";
  
  this.setMap(du_map.map);

  // Note: an overlay's receipt of onAdd() indicates that
  // the map's panes are now available for attaching
  // the overlay to the map via the DOM.

  // Create the DIV and set some basic attributes.
  var div = document.createElement('DIV');
  div.id = "popup_container";
	/*div.onmouseout = function() {
		this.visible_ = false;
		du_map.popup.draw();
	}*/
  div.onmouseout = function(event) {
    //alert(typeof popup);
    if (!event) event=window.event;
    fixOnMouseOut(this, event, 'du_map.popup.hide();');
    //map.removeOverlay(popup);
  }

  // Create an IMG element and attach it to the DIV.
  /*var img = document.createElement("img");
  img.src = this.image_;
  img.style.width = "100%";
  img.style.height = "100%";
  div.appendChild(img);*/
  div.innerHTML = this.content_;

  // Set the overlay's div_ property to this DIV
  this.div_ = div;

  // We add an overlay to a map via one of the map's panes.
  // We'll add this overlay to the overlayImage pane.
  //var panes = this.getPanes();
  //panes.overlayImage.appendChild(div);
  
  document.getElementById('map_container').appendChild(div);
}

popup.prototype.updatePopupContent = function(map_popup_content) {

  this.content_ = map_popup_content;
  var div = this.div_;
  div.innerHTML=this.content_;

}

popup.prototype.updatePopupPosition = function(Latlng) {

  this.position_ = Latlng;

}

popup.prototype.draw = function() {

  // Size and position the overlay. We use a southwest and northeast
  // position of the overlay to peg it to the correct position and size.
  // We need to retrieve the projection from this overlay to do this.
  //var overlayProjection = this.getProjection();

  // Retrieve the southwest and northeast coordinates of this overlay
  // in latlngs and convert them to pixels coordinates.
  // We'll use these coordinates to resize the DIV.
  /*var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
  var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

  // Resize the image's DIV to fit the indicated dimensions.
  var div = this.div_;
  div.style.left = sw.x + 'px';
  div.style.top = ne.y + 'px';
  div.style.width = (ne.x - sw.x) + 'px';
  div.style.height = (sw.y - ne.y) + 'px';*/
  
  var div = this.div_;
  
  if (this.visible_) {
  	//alert("Visible");
  	
	  var overlayProjection = this.getProjection();
	  //var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
	  //var ne = overlayProjection.fromLatLngToDivPixel(this.position_);
	  var point = overlayProjection.fromLatLngToContainerPixel(this.position_);

		//alert(dump(getPos(document.getElementById('map_container'))));

	  div.style.left = (point.x - (this.width_ / 2) - 4) + 'px';
	  div.style.top = (point.y) + 'px';
	  div.style.width = this.width_ + 'px';
	  div.style.height = this.height_ + 'px';
  	
		div.style.display = 'block';
  } else {
  	//alert("Not visible");
		div.style.display = 'none';
  }
  
}

popup.prototype.hide = function() {
	this.visible_ = false;
	this.draw();
	//$('#annotazione').toggle('fast');
}

popup.prototype.onRemove = function() {
	//du_map.popup.visible_ = false;
  this.div_.parentNode.removeChild(this.div_);
  this.div_ = null;
}
/* Inizio definizioni per oggetto popup */
