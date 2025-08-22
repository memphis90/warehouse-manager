<?php

return [
    'admin' => [
        [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'dashboard',
            'active' => ['dashboard']
        ],
        [
            'name' => 'Users',
            'route' => 'admin.users.index',
            'icon' => 'users',
            'active' => ['admin.users.*']
        ],
        [
            'name' => 'Items',
            'route' => 'admin.items.index',
            'icon' => 'items',
            'active' => ['admin.items.*']
        ],
        [
            'name' => 'Requests',
            'route' => 'admin.requests.index',
            'icon' => 'requests',
            'active' => ['admin.requests.*']
        ],
    ],
    
    'user' => [
        [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'dashboard',
            'active' => ['dashboard']
        ],
        [
            'name' => 'My Requests',
            'route' => 'user.requests.index',
            'icon' => 'requests',
            'active' => ['user.requests.*']
        ],
        [
            'name' => 'New Request',
            'route' => 'user.requests.create',
            'icon' => 'plus',
            'active' => ['user.requests.create']
        ],
    ],
];