npm install @turf/helpers
npm install @turf/boolean-point-in-polygon
npm install @turf/meta

cat main.js
module.exports = {
	helpers:                  require('@turf/helpers'),
	booleanPointInPolygon:    require('@turf/boolean-point-in-polygon').default,
	meta:                     require('@turf/meta')
};

./node_modules/.bin/browserify main.js -s turf > leafext-turf.js
~/node_modules/.bin/uglifyjs leafext-turf.js > leafext-turf.min.js
