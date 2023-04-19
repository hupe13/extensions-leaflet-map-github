//
// extensions-leaflet-map
// Copy Shortcode to clipboard
//

function leafext_createShortcode(shortcode,uploadurl,file,end) {
  //console.log(shortcode,uploadurl,file,end);
  evt = window.event;
  var target = evt.currentTarget.querySelector(".leafextcopy");
  var leafext_short = document.createElement("input");
  document.body.appendChild(leafext_short);
  leafext_short.value = shortcode+"\""+uploadurl + "" + file + "\"" + end;
  if (navigator.clipboard) {
    navigator.clipboard.writeText(leafext_short.value)
    .then(
      () => {
        zwsp = target.textContent;
        target.textContent = "Copied: " + leafext_short.value;
        console.log(leafext_short.value);
        setTimeout(() => {
          target.textContent = zwsp;
        }, 5000);
      }
    )
  } else {
    console.log("No Clipboard");
  }
  leafext_short.remove();
}

function leafext_outFunc() {
  leafextTooltip.innerHTML = "Copy to clipboard";
}
