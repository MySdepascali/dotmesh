<?php 

$config = array();

$config['db']['host']         = 'localhost';
$config['db']['username']     = 'dotmesh';
$config['db']['password']     = 'cSc4ngqG*I!Ssbz';
$config['db']['dbname']       = 'dotmesh1';
$config['db']['table_prefix'] = '';
$config['db']['logging']      = 1;
$config['db']['implicit_migration'] = 1;

$config['web']['hide_script_name'] = 1;

$config['cookie']['session_check_ip'] = 1;
$config['cookie']['session_handler'] = 'apc';
//$config['cookie']['timeout'] = 3600;
//$config['cookie']['domain'] = null;
//$config['cookie']['path'] = null;

$config['modules']['DotMesh']['thumb_root_path'] = 'dotmesh/thumbs';

return $config;