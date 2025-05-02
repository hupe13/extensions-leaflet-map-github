/**
 * Javascript functions for Shortcode elevation
 *
 * @package Extensions for Leaflet Map
 */

// .

function leafext_formatTime(t) {
	// console.log(t);
	var date = new Date( t );
	// console.log("fkt "+date);
	var days  = Math.floor( t / (1000 * 60 * 60 * 24) );
	var hours = date.getUTCHours();
	if (days == 0 && hours == 0) {
		hours = "";
	} else {
		hours = hours + ":";
	}
	var minutes = "0" + date.getUTCMinutes();
	minutes     = minutes.substr( -2 ) + "\'";
	var seconds = "0" + date.getUTCSeconds();
	if (days > 0) {
		seconds = "";
	} else {
		seconds = seconds.substr( -2 ) + "\'\'";
	}
	if (days == 0) {
		days = "";
	} else {
		days = days + "d ";
	}
	return (days + hours + minutes + seconds);
}

function leafext_elevation_prep_js() {
	// BEGIN
	const toPrecision = (x, n) => Number( parseFloat( x.toPrecision( n ) ).toFixed( n ) );

	// Save a reference of default "L.Control.Elevation" (for later use)
	const elevationProto = L.extend( {}, L.Control.Elevation.prototype );
	// Override default "_registerHandler" behaviour.
	L.Control.Elevation.include(
		{
			// ref: https://github.com/Raruto/leaflet-elevation/blob/c58250e7c20d52490aa3a50b611dbb282ff00a57/src/control.js#L1063-L1128
			_registerHandler: function (props) {
				if (typeof props === "object") {
					switch (props.name) {
						// ref: https://github.com/Raruto/leaflet-elevation/blob/c58250e7c20d52490aa3a50b611dbb282ff00a57/src/handlers/acceleration.js#L41-L61
						case "acceleration":
							let accelerationLabel               = this.options.accelerationLabel || L._( this.options.imperial ? "ft/s²" : "m/s²" );
							props.tooltip.chart                 = (item)        => L._( "a: " ) + toPrecision( item.acceleration || 0, 2 ) + " " + accelerationLabel;
							props.tooltip.marker                = (item)        => toPrecision( item.acceleration, 2 ) + " " + accelerationLabel;
							props.summary.minacceleration.value = (track, unit) => toPrecision( track.acceleration_min || 0, 2 ) + "&nbsp;" + unit;
							props.summary.maxacceleration.value = (track, unit) => toPrecision( track.acceleration_max || 0, 2 ) + "&nbsp;" + unit;
							props.summary.avgacceleration.value = (track, unit) => toPrecision( track.acceleration_avg || 0, 2 ) + "&nbsp;" + unit;
						break;
						case "altitude":
							props.summary.minele.value = (track, unit) => (track.elevation_min || 0).toFixed( 0 ) + "&nbsp;" + unit;
							props.summary.maxele.value = (track, unit) => (track.elevation_max || 0).toFixed( 0 ) + "&nbsp;" + unit;
							props.summary.avgele.value = (track, unit) => (track.elevation_avg || 0).toFixed( 0 ) + "&nbsp;" + unit;
						break;
						// cadence
						case "distance":
							if (this.options.distance) {
								let distlabel              = this.options.distance.label || L._( this.options.imperial ? "mi" : this.options.xLabel );
								props.tooltip.chart        = (item) => L._( "x: " ) + toPrecision( item.dist, (item.dist > 10) ? 3 : 2 ) + " " + distlabel;
								props.summary.totlen.value = (track) => toPrecision( track.distance || 0, 3 ) + "&nbsp;" + distlabel;
							}
						break;
						case "pace":
							if (this.options.pace) {
								// let paceLabel = this.options.paceLabel || L._(opts.imperial ? "min/mi" : "min/km");
								let paceLabel               = this.options.imperial ? "/mi" : "/km";
								props.tooltip.chart         = (item)        => L._( "pace: " ) + (leafext_formatTime( item.pace * 1000 * 60 ) || 0) + " " + paceLabel;
								props.tooltip.marker        = (item)        => (leafext_formatTime( item.pace * 1000 * 60 ) || 0) + " " + paceLabel;
								props.summary.minpace.value = (track, unit) => (leafext_formatTime( track.pace_max * 1000 * 60 ) || 0) + "&nbsp;" + paceLabel;
								props.summary.maxpace.value = (track, unit) => (leafext_formatTime( track.pace_min * 1000 * 60 ) || 0) + "&nbsp;" + paceLabel;
								props.summary.avgpace.value = (track, unit) => leafext_formatTime( Math.abs( (track.time / track.distance) / this.options.paceFactor ) * 60 ) + "&nbsp;" + paceLabel;
							}
						break;
						case "slope":
							let slopeLabel      = this.options.slopeLabel || "%";
							props.tooltip.chart = (item) => L._( "m: " ) + Math.round( item.slope ) + slopeLabel;
						break;
						case "speed":
							//console.log( this.options.speed );
							if (this.options.speed) {
								let speedLabel               = this.options.speedLabel || L._( this.options.imperial ? "mph" : "km/h" );
								props.tooltip.chart          = (item) => L._( "v: " ) + toPrecision( item.speed,2 ) + " " + speedLabel;
								props.tooltip.marker         = (item) => toPrecision( item.speed,3 ) + " " + speedLabel;
								props.summary.minspeed.value = (track, unit) => toPrecision( track.speed_min || 0, 2 ) + "&nbsp;" + unit;
								props.summary.maxspeed.value = (track, unit) => toPrecision( track.speed_max || 0, 2 ) + "&nbsp;" + unit;
								props.summary.avgspeed.value = (track, unit) => toPrecision( track.speed_avg || 0, 2 ) + "&nbsp;" + unit;
								// props.summary.avgspeed.value = (track, unit) => (track.speed_avg || 0) + "&nbsp;" + unit;
							}
						break;
						case "time":
							if (this.options.time) {
								props.tooltips.find( ({ name }) => name === "time" ).chart = (item) => L._( "T: " ) + leafext_formatTime( item.duration || 0 );
								props.summary.tottime.value                                = (track) => leafext_formatTime( track.time || 0 );
							}
						break;
					}
				}
				elevationProto._registerHandler.apply( this, [props] );
			}
		}
	);
	// Proceed as usual
	// var controlElevation = L.control.elevation(opts.elevationControl.options);
	// controlElevation.load(opts.elevationControl.url);
	// END
}

function leafext_elevation_locale_js() {
	const { __, _x, _n, sprintf } = wp.i18n;
	var mylocale                  = {
		"Acceleration"		: _x( "Acceleration", "In Frontend", "extensions-leaflet-map" ),
		"Altitude"		: _x( "Altitude", "In Frontend", "extensions-leaflet-map" ),
		"Slope"			: _x( "Slope", "In Frontend", "extensions-leaflet-map" ),
		"Speed"			: _x( "Speed", "In Frontend", "extensions-leaflet-map" ),
		"Total Time: "		: _x( "Total Time", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Total Length: "	: _x( "Total Length", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Max Elevation: "	: _x( "Max Elevation", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Min Elevation: "	: _x( "Min Elevation", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Avg Elevation: "	: _x( "Avg Elevation", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Total Ascent: "	: _x( "Total Ascent", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Total Descent: "	: _x( "Total Descent", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Min Slope: "		: _x( "Min Slope", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Max Slope: "		: _x( "Max Slope", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Avg Slope: "		: _x( "Avg Slope", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Min Speed: "		: _x( "Min Speed", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Max Speed: "		: _x( "Max Speed", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Avg Speed: "		: _x( "Avg Speed", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Min Acceleration: "	: _x( "Min Acceleration", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Max Acceleration: "	: _x( "Max Acceleration", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Avg Acceleration: "	: _x( "Avg Acceleration", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Pace"			: _x( "Pace", "In Frontend", "extensions-leaflet-map" ),
		"Min Pace: "		: _x( "Min Pace", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Max Pace: "		: _x( "Max Pace", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Avg Pace: "		: _x( "Avg Pace", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"ECG"			: _x( "ECG", "In Frontend", "extensions-leaflet-map" ),
		"Min BPM: "		: _x( "Min BPM", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Max BPM: "		: _x( "Max BPM", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Avg BPM: "		: _x( "Avg BPM", "In Frontend", "extensions-leaflet-map" ) + ": ",
		"Download" 		: _x( "Download", "In Frontend", "extensions-leaflet-map" ),
		"Elevation" 		: _x( "Elevation", "In Frontend", "extensions-leaflet-map" ),

		"a: " 			: _x( "a",    "In Frontend: Abbreviation for acceleration in the chart", "extensions-leaflet-map" ) + ": ",
		"cad: " 		: _x( "cad",  "In Frontend: Abbreviation for cadence in the chart", "extensions-leaflet-map" ) + ": ",
		"hr: " 			: _x( "hr",   "In Frontend: Abbreviation for heart rate in the chart", "extensions-leaflet-map" ) + ": ",
		"m: " 			: _x( "m",    "In Frontend: Abbreviation for slope in the chart", "extensions-leaflet-map" ) + ": ",
		"pace: " 		: _x( "pace", "In Frontend: Abbreviation for pace in the chart", "extensions-leaflet-map" ) + ": ",
		"t: " 			: _x( "t",    "In Frontend: Abbreviation for time in the chart", "extensions-leaflet-map" ) + ": ",
		"T: " 			: _x( "T",    "In Frontend: Abbreviation for duration in the chart", "extensions-leaflet-map" ) + ": ",
		"v: " 			: _x( "v",    "In Frontend: Abbreviation for speed in the chart", "extensions-leaflet-map" ) + ": ",
		"x: " 			: _x( "x",    "In Frontend: Abbreviation for length in the chart", "extensions-leaflet-map" ) + ": ",
		"y: " 			: _x( "y",    "In Frontend: Abbreviation for altitude in the chart", "extensions-leaflet-map" ) + ": ",
	};
	L.registerLocale( "wp", mylocale );
	L.setLocale( "wp" );
}
