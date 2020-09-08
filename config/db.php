<?php

/**
 * Environment dependent settings
 * @var array $env
 */

return [
    'class' => 'yii\db\Connection',
    'dsn' => $env['db']['dsn'],
    'username' => $env['db']['username'],
    'password' => $env['db']['password'],
    'charset' => 'utf8',

    'enableSchemaCache' => $env['db']['enableSchemaCache'],
    'schemaCacheDuration' => $env['db']['schemaCacheDuration'],
    'schemaCache' => 'cache',
];
