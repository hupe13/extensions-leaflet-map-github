/**
 * Javascript function for Shortcodes parentgroup
 *
 * @package Extensions for Leaflet Map
 */

/**
 * Create Javascript code for parentgroup
 */

function leafext_parentgroup_js(parent, childs, grouptext,expandall,collapseall,closedSymbol,openedSymbol) {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;

	console.log( "parent " + parent + " on map " + map_id + "; childs:",childs );

	if (typeof layerControl == "undefined" ) {
		layerControl = [];
		parents      = [];
		children     = [];
	}

	if (typeof layerControl[map_id] == "undefined" ) {
		parents[map_id]  = [];
		children[map_id] = [];

		layerControl[map_id]                   = L.control.layers.tree(
			null,
			parents[map_id],
			{
				closedSymbol: closedSymbol,
				openedSymbol: openedSymbol,
				spaceSymbol: '&nbsp;',
				collapseAll: collapseall,
				expandAll: expandall,
			}
		);
		layerControl[map_id].options.collapsed = control[map_id].options.collapsed;
		layerControl[map_id].options.position  = control[map_id].options.position;

		map.removeControl( control[map_id] );
		layerControl[map_id].addTo( map ).collapseTree().expandSelected().collapseTree( true );
	}

	children[map_id][parent] = [];

	for (key in childs) {
		control[map_id].removeLayer( featGroups[map_id][childs[key]] );
		children[map_id][parent].push(
			{
				label: '&nbsp; ' + leafext_unescapeHTML( [grouptext[childs[key]]] ),
				layer: featGroups[map_id][childs[key]]
			}
		);
	}

	if ( ! ( parents[map_id].length == 0 && collapseall == '' && expandall == '' ) ) {
		parents[map_id].push(
			{
				label: '<div class="leaflet-control-layers-separator"></div>'
			}
		);
	}

	parents[map_id].push(
		{
			label: '&nbsp; <b>' + parent + '</b>',
			selectAllCheckbox: true,
			children: children[map_id][parent]
		}
	);

	layerControl[map_id].setOverlayTree( parents[map_id] ).collapseTree( true ).expandSelected( true );

}
