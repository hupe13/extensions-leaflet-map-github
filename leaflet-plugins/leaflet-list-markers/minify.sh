#!/usr/bin/bash
~/node_modules/.bin/css-minify -f leaflet-list-markers.css -o ../dist/
~/node_modules/.bin/uglifyjs leaflet-list-markers.js -o ../dist/leaflet-list-markers.min.js
ls -la ../dist
