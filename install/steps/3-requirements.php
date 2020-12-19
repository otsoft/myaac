<?php
defined('MYAAC') or die('Direct access not allowed!');

// configuration
$extensions_required = [
	'pdo', 'pdo_mysql', 'xml', 'zip'
];
/*
 *
 * @param string $name
 * @param boolean $ok
 * @param mixed $info
 */
function version_check($name, $ok, $info = '', $warning = false)
{
	global $failed;
	echo '<div class="alert alert-' . ($ok ? 'success' : ($warning ? 'warning' : 'danger')) . '">' . $name;
	if(!empty($info))
		echo ': <b>' . $info . '</b>';

	echo '</div>';
	if(!$ok && !$warning)
		$failed = true;
}

$failed = false;

echo ($failed) ? '<div class="alert alert-warning"><span>' . $locale['step_requirements_failed'] . '</span></div>' : '';

// start validating
version_check($locale['step_requirements_php_version'], (PHP_VERSION_ID >= 50500), PHP_VERSION);
foreach(array('images/guilds', 'images/houses', 'images/gallery') as $value)
{
	$is_writable = is_writable(BASE . $value);
	version_check($locale['step_requirements_write_perms'] . ': ' . $value, $is_writable);
}

$ini_register_globals = ini_get_bool('register_globals');
version_check('register_long_arrays', !$ini_register_globals, $ini_register_globals ? $locale['on'] : $locale['off']);

$ini_safe_mode = ini_get_bool('safe_mode');
version_check('safe_mode', !$ini_safe_mode, $ini_safe_mode ? $locale['on'] : $locale['off'], true);

foreach ($extensions_required as $ext) {
	$loaded = extension_loaded($ext);
	version_check(str_replace('$EXTENSION$', strtoupper($ext), $locale['step_requirements_extension']) , $loaded, $loaded ? $locale['loaded'] : $locale['not_loaded']);
}

echo '<div class="text-center m-3">';

if($failed) {
	echo next_form(true, false);
}else {
	echo next_form(true, true);
}

echo '</div>';

?>