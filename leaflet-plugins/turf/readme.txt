nmp install @turf/helpers
nmp install @turf/boolean-point-in-polygon
nmp install @turf/meta

cat main.js
module.exports = {
	helpers:                  require('@turf/helpers'),
	booleanPointInPolygon: require('@turf/boolean-point-in-polygon').default,
	meta:                     require('@turf/meta')
};

browserify main.js -s turf > leafext-turf.js
minify leafext-turf.js > leafext-turf.min.js
