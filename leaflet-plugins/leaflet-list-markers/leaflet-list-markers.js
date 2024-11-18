(function () {

	L.Control.ListMarkers = L.Control.extend(
		{
			includes: L.version[0] === '1' ? L.Evented.prototype : L.Mixin.Events,

			options: {
				layer: false,
				maxItems: 20,
				collapsed: false,
				label: 'title',
				itemIcon: L.Icon.Default.imagePath + '/marker-icon.png',
				itemArrow: '&#10148;',	//visit: https://character-code.com/arrows-html-codes.php
				maxZoom: 9,
				position: 'bottomleft',
				update: true,
				maxheight: 0.7,
				maxwidth: 0.5
				//TODO autocollapse
			},

			initialize: function (options) {
				L.Util.setOptions( this, options );
				this._container = null;
				this._list      = null;
				this._layer     = this.options.layer || new L.LayerGroup();
				this._greatest  = 0;
			},

			onAdd: function (map) {

				map.options._collapse = this.options.collapsed;
				map.options._greatest = 0;
				mapupdate             = true;
				map.on(
					"update-end",
					function (e) {
						// console.log("update-end", map.options._collapse);
						if ( ! map.options._collapse ) {
							let greatest_before = map.options._greatest;
							// console.log("greatest_before",greatest_before);
							const createdlist = document.getElementsByClassName( 'list-markers-li' );
							let length        = createdlist.length;
							for (let i = 0; i < length; i++) {
								let rectwidth = createdlist[i].childNodes[0].childNodes[0].offsetWidth;
								if ( rectwidth > map.options._greatest) {
									map.options._greatest = rectwidth;
								}
							}
							for (let i = 0; i < length; i++) {
								createdlist[i].childNodes[0].style.width = map.options._greatest + 30 + "px";
							}
							// console.log("greatest_new",map.options._greatest);
							if ( mapupdate ) {
								mapupdate  = false;
								let center = map.getCenter();
								// console.log( center );
								let countlat = center.lat.toString().split( "." );
								let countlng = center.lng.toString().split( "." );
								let newlat   = (( center.lat + 0.001 ) * 1000) / 1000;
								let newlng   = (( center.lng + 0.001 ) * 1000) / 1000;
								// console.log( newlat, newlng );
								map.setView( L.latLng( newlat, newlng ) );
								map.once(
									"update-end",
									function (e) {
										// console.log( "once update-end", mapupdate );
										if (mapupdate) {
											map.setView( center );
										}
									}
								);
							}
						} else {
							mapupdate = false;
						}
					}
				);

				this._map     = map;
				var container = this._container = L.DomUtil.create( 'div', 'list-markers-x-y list-markers' );
				this._list    = L.DomUtil.create( 'ul', 'list-markers-ul', container );
				this._initToggle();
				map.on( 'moveend', this._updateList, this );
				var s           = map.getSize();
				var style       = document.createElement( 'style' );
				style.type      = 'text/css';
				style.innerHTML = '.list-markers-x-y { max-height: ' + (s.y) * this.options.maxheight + 'px; max-width: ' + (s.x * this.options.maxwidth) + 'px;}';
				document.getElementsByTagName( 'head' )[0].appendChild( style );
				this._updateList();

				return container;
			},

			onRemove: function (map) {
				map.off( 'moveend', this._updateList, this );
				this._container = null;
				this._list      = null;
			},

			_createItem: function (layer) {
				var li = L.DomUtil.create( 'li', 'list-markers-li' ),
				a      = L.DomUtil.create( 'a', '', li ),
				icon   = this.options.itemIcon ? '<img src="' + this.options.itemIcon + '" />' : '',
				that   = this;
				a.href = '#';
				L.DomEvent
				.disableClickPropagation( a )
				.on( a, 'click', L.DomEvent.stop, this )
				.on(
					a,
					'click',
					function (e) {
						mapupdate = false;
						//this._moveTo( layer.getLatLng() );
						that.fire( 'item-click', {layer: layer } );
					},
					this
				)
				.on(
					a,
					'mouseover',
					function (e) {
						that.fire( 'item-mouseover', {layer: layer } );
					},
					this
				)
				.on(
					a,
					'mouseout',
					function (e) {
						that.fire( 'item-mouseout', {layer: layer } );
					},
					this
				);

				//console.log('_createItem',layer.options);
				if ( layer.options.hasOwnProperty( this.options.label ) ) {
					//a.innerHTML = icon + '<span>' + layer.options[this.options.label] + '</span> <b>' + this.options.itemArrow + '</b>';
					a.innerHTML    = '<span>' + layer.options[this.options.label] + '</span> <b>' + this.options.itemArrow + '</b>';
					let thislength = layer.options[this.options.label].length;
					// console.log(thislength);
					if ( thislength > this._greatest ) {
						this._greatest = thislength;
						// console.log("li",this._greatest);
						itemstyle           = document.createElement( 'style' );
						itemstyle.type      = 'text/css';
						thislength          = 8.5 * thislength;
						itemstyle.innerHTML = '.list-markers-li a { width: ' + thislength + 'px; }';
						// console.log(itemstyle);
						document.getElementsByTagName( 'head' )[0].appendChild( itemstyle );
					}

					//TODO use related marker icon!
					//TODO use template for item
				} else {
					console.log( "propertyName '" + this.options.label + "' not found in marker" );
				}

				return li;
			},

			_updateList: function () {
				// console.log("_updateList");
				var that             = this,
				n                    = 0;
				this._list.innerHTML = '';
				this._layer.eachLayer(
					function (layer) {
						if (layer instanceof L.Marker) {
							if ( that.options.update ) {
								if ( that._map.getBounds().contains( layer.getLatLng() ) ) {
									if (++n < that.options.maxItems) {
													that._list.appendChild( that._createItem( layer ) );
									}
								}
							} else {
								if (++n < that.options.maxItems) {
									that._list.appendChild( that._createItem( layer ) );
								}
							}
						}
					}
				);
				this._map.fire( 'update-end' );
			},

			_initToggle: function () {

				/* inspired by L.Control.Layers */

				var container = this._container;

				//Makes this work on IE10 Touch devices by stopping it from firing a mouseout event when the touch is released
				container.setAttribute( 'aria-haspopup', true );

				if (this.options.collapsed) {
					this._collapse();

					L.DomEvent
					.on( container, 'click', this._expand, this )
					.on( container, 'click', this._collapse, this );

					var link   = this._button = L.DomUtil.create( 'a', 'leaflet-control-layers-toggle', container );
					link.href  = '#';
					link.title = 'List Markers';

					L.DomEvent
					.on( link, 'click', L.DomEvent.stop )
					.on( link, 'click', this._expand, this );

					this._map.on( 'click', this._collapse, this );
					// TODO keyboard accessibility
				}
			},

			_expand: function () {
				this._container.className = this._container.className.replace( ' list-markers-collapsed', '' );
				L.DomUtil.addClass( this._container, 'list-markers-x-y' );
				this._map.options._collapse = false;
				this._map.fire( 'update-end' );
			},

			_collapse: function () {
				L.DomUtil.addClass( this._container, 'list-markers-collapsed' );
				this._container.className   = this._container.className.replace( 'list-markers-x-y ', '' );
				this._map.options._collapse = true;
			},

			_moveTo: function (latlng) {
				if (this.options.maxZoom) {
					this._map.setView( latlng, Math.min( this._map.getZoom(), this.options.maxZoom ) );
				} else {
					this._map.panTo( latlng );
				}
			}
		}
	);

	L.control.listMarkers = function (options) {
		return new L.Control.ListMarkers( options );
	};

	L.Map.addInitHook(
		function () {
			if (this.options.listMarkersControl) {
				this.listMarkersControl = L.control.listMarkers( this.options.listMarkersControl );
				this.addControl( this.listMarkersControl );
			}
		}
	);

}).call( this );
