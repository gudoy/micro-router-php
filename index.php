<?php 

// Environment
$env = strpos($_SERVER['HTTP_HOST'], 'dev') !== false ? 'dev' : 'prod';

// In case where the app do not use a hostname but is accessed va the server IP, we have to remove the app base base from the request URI
$relativeURI	= trim(str_replace(array('index.php', $_SERVER['QUERY_STRING'], '//'), array('', '', '/'), $_SERVER['PHP_SELF']), '/');

// URI parts
$p				= !empty($relativeURI) ? preg_split('/\//', $relativeURI) : array();

// Default template path
$tplPath 		= 'templates/pages/home.html';

// Known routes
$routes			= array(
	'home'		=> 'home',									// Simple case
	'foo'		=> array(
		'/^foo\/bar\/baz(\/.*)?$/' => 'foo/bar/baz.html',
	),
);

$tlvItem	= isset($p[0]) && isset($routes[$p[0]]) ? $routes[$p[0]] : null;
$tpl		= 'home';

// If first URI part is a known top level route item
if ( isset($tlvItem) )
{
	// If the element is an array of routes
	if ( is_array($tlvItem) )
	{
		// Loop over the routes items
		foreach($tlvItem as $k => $v)
		{
			// If the current item regexp match with the current URI, set the template path
			if ( preg_match($k, $relativeURI) ){ $tpl = $v; break; }
		}
	}
	// Otherwise, use the item template as is
	else if ( is_string($tlvItem) ) { $tpl = $tlvItem; }
	
	// Set used template path
	$tplPath = 'templates/pages/' . $tpl . '.html';
}
// Special case for templates
else if ( isset($p[0]) && $p[0] === 'templates' ){ $tplPath = $relativeURI; }

//echo file_get_contents($tplPath);
echo file_exists($tplPath) ? file_get_contents($tplPath) : '';

?>