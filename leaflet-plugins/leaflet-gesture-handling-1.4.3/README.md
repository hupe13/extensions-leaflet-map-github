# leaflet-gesture-handling.js

A Leaflet plugin that allows to prevent default map scroll/touch behaviours.


_For a working example see [demo](https://raruto.github.io/leaflet-gesture-handling/examples/leaflet-gesture-handling.html)_


<p align="center">
    <a href="https://raruto.github.io/leaflet-gesture-handling/examples/leaflet-gesture-handling.html"><img src="https://raruto.github.io/img/leaflet-gesture-handling.png" alt="Ctrl + scroll to zoom the map" /></a>
</p>


---

<blockquote>
    <p align="center">
        <em>Initially based on the <a href="https://github.com/elmarquis/Leaflet.GestureHandling">work</a> of <strong>elmarquis</strong></em>
    </p>
</blockquote>

---

## How to use

1. **include CSS & JavaScript**
    ```html
    <head>
    ...
    <style> html, body, #map { height: 100%; width: 100%; padding: 0; margin: 0; } </style>
    <!-- Leaflet (JS/CSS) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
    <!-- leaflet-gesture-handling -->
    <link rel="stylesheet" href="https://unpkg.com/@raruto/leaflet-gesture-handling@latest/dist/leaflet-gesture-handling.min.css" type="text/css">
    <script src="https://unpkg.com/@raruto/leaflet-gesture-handling@latest/dist/leaflet-gesture-handling.min.js"></script>
    ...
    </head>
    ```
2. **choose the div container used for the slippy map**
    ```html
    <body>
    ...
    <div id="map"></div>
    ...
    </body>
    ```
3. **create your first simple “leaflet-gesture-handling” slippy map**
    ```html
    <script>
      ...
      var map = new L.Map('map', {
        center: [41.4583, 12.7059],
        zoom: 5,
        gestureHandling: true,
        gestureHandlingOptions: { // OPTIONAL
          // text: {
          //   touch: "Hey bro, use two fingers to move the map",
          //   scroll: "Hey bro, use ctrl + scroll to zoom the map",
          //   scrollMac: "Hey bro, use \u2318 + scroll to zoom the map"
          // },
          // locale: 'en', // set language of the warning message.
          // duration: 5000 // set time in ms before the message should disappear.
        }
      });
      ...
    </script>
    ```
_Related: [Leaflet-UI presets](https://github.com/raruto/leaflet-ui)_

---

**Compatibile with:** leaflet@1.3.4

---

**Contributors:** [Elmarquis](https://github.com/elmarquis/Leaflet.GestureHandling), [Raruto](https://github.com/Raruto/leaflet-gesture-handling)
