<?php
$grouproles = array();
$columns = [
    'group_roles.group_role_id', 'group_roles.permissions', 'group_roles.group_role_attribute'
];
$roles = DB::connect()->select('group_roles', $columns);

$group_role_attributes = array();

foreach ($roles as $role) {
    $roleid = $role['group_role_id'];
    $grouproles[$roleid] = $role['permissions'];
    $attribute = $role['group_role_attribute'];
    $group_role_attributes[$attribute] = $roleid;
}

$cache = json_encode($grouproles);
$cachefile = 'assets/cache/group_roles.cache';

if (file_exists($cachefile)) {
    unlink($cachefile);
}

$cachefile = fopen($cachefile, "w");
fwrite($cachefile, $cache);
fclose($cachefile);


$cache = json_encode($group_role_attributes);
$cachefile = 'assets/cache/group_role_attributes.cache';

if (file_exists($cachefile)) {
    unlink($cachefile);
}

$cachefile = fopen($cachefile, "w");
fwrite($cachefile, $cache);
fclose($cachefile);

$result = true;