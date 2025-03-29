<?php

use craft\helpers\App;

return [
	'*' => [
    'apiKey' => App::env('REMOTE_API_KEY'),
    'notes' => '',
		'host' => [
			'name' => 'Servd',
       'handle' => 'servd',
			 'plan' => 'Starter (Monthly)',
       'region' => 'us-east-6',
       'owner' => 'Good Work',
       'server_access' => true,
       'dns_provider' => 'Client Managed',
       'dns_access' => false,
       'notes' => '',
			 'meta' => [
         'Asset Platform' => 'V3',
         'Ingress Platform' => 'Updated',
         'Database Upgrade Available' => true,
         'Static Caching' => true
       ],
		]
	],
  'staging' => [
    'host' => [
       'url' => 'https://app.servd.host/hzX9mjvb/project/bp-energy/env/production/settings',
		],
	],
  'production' => [
    'host' => [
       'url' => 'https://app.servd.host/hzX9mjvb/project/bp-energy/env/production/settings',
    ]
  ]
];