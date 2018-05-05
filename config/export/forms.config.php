<?php
    $connections = require_once __DIR__.'db.config.php';
    
    return [
        'form'            => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'system_form'
            ],
//            'fields'    => [
//                'id'             => [
//                    'type' => 'int',
//                    'nullable' => true,
//                    'alias' => 'asdasd'
//                ],
//                'token'          => [
//                    'type' => 'string'
//                ],
//                'class'          => [
//                    'type'     => 'string',
//                    'nullable' => true
//                ],
//                'description'    => [
//                    'type'     => 'string',
//                    'nullable' => true
//                ],
//                'route_id'       => [
//                    'type'     => 'int',
//                    'nullable' => true
//                ],
//                'check_route_id' => [
//                    'type'     => 'int',
//                    'nullable' => true
//                ],
//                'enabled'        => [
//                    'type' => 'int'
//                ],
//                'created'        => [
//                    'type' => 'datetime'
//                ],
//                'updated'        => [
//                    'type' => 'datetime'
//                ],
//            ],
            'relations' => [
                [
                    'field' => 'route_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'route',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'check_route_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'route',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'id',
                    'type'  => 'connectedWith',
                    'table' => [
                        'name'  => 'field',
                        'field' => 'form_id'
                    ]
                ],
                [
                    'field' => 'id',
                    'type'  => 'connectedWith',
                    'table' => [
                        'name'  => 'form_group',
                        'field' => 'form_id'
                    ]
                ],
                [
                    'field' => 'id',
                    'type'  => 'connectedWith',
                    'table' => [
                        'name'  => 'form_tab',
                        'field' => 'form_id'
                    ]
                ],
                [
                    'field' => 'token',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => ['id']
        ],
        'form_tab'        => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'system_form_tab'
            ],
            'fields'    => [
                'id'       => [
                    'type' => 'int'
                ],
                'form_id'  => [
                    'type' => 'int'
                ],
                'token'    => [
                    'type' => 'string'
                ],
                'class'    => [
                    'type'     => 'string',
                    'nullable' => true,
                ],
                'label'    => [
                    'type' => 'string'
                ],
                'hidden'   => [
                    'type' => 'boolean'
                ],
                'enabled'  => [
                    'type' => 'boolean'
                ],
                'position' => [
                    'type' => 'int'
                ],
            ],
            'relations' => [
                [
                    'field' => 'token',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => [
                'id',
                'form_id'
            ]
        ],
        'form_group'      => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'system_form_group'
            ],
            'fields'    => [
                'id'         => [
                    'type' => 'int'
                ],
                'token'      => [
                    'type' => 'string'
                ],
                'class'      => [
                    'type'     => 'string',
                    'nullable' => true,
                ],
                'form_id'    => [
                    'type' => 'int'
                ],
                'tab_id'     => [
                    'type'     => 'int',
                    'nullable' => true
                ],
                'label'      => [
                    'type' => 'string'
                ],
                'hidden'     => [
                    'type' => 'boolean'
                ],
                'hide_label' => [
                    'type' => 'boolean'
                ],
                'position'   => [
                    'type' => 'int'
                ],
            ],
            'relations' => [
                [
                    'field' => 'tab_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'form_tab',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'token',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => [
                'id',
                'form_id'
            ]
        ],
        'field'           => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'system_form_field'
            ],
            'fields'    => [
                'id'             => [
                    'type' => 'int'
                ],
                'form_id'        => [
                    'type' => 'int',
                ],
                'group_id'       => [
                    'type'     => 'int',
                    'nullable' => true
                ],
                'tab_id'         => [
                    'type'     => 'int',
                    'nullable' => true
                ],
                'token'          => [
                    'type' => 'string'
                ],
                'class'          => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'label'          => [
                    'type' => 'string'
                ],
                'type_id'        => [
                    'type' => 'int'
                ],
                'required'       => [
                    'type' => 'int'
                ],
                'readonly'       => [
                    'type' => 'int'
                ],
                'disabled'       => [
                    'type' => 'int'
                ],
                'min'            => [
                    'type'     => 'float',
                    'nullable' => true
                ],
                'max'            => [
                    'type'     => 'float',
                    'nullable' => true
                ],
                'pattern'        => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'validator_id'   => [
                    'type'     => 'int',
                    'nullable' => true
                ],
                'position'       => [
                    'type' => 'int'
                ],
                'check_route_id' => [
                    'type'     => 'int',
                    'nullable' => true
                ],
                'placeholder'    => [
                    'type'     => 'string',
                    'nullable' => true
                ],
            ],
            'relations' => [
                [
                    'field' => 'group_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'form_group',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'tab_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'form_tab',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'type_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'field_type',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'validator_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'field_validator',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'check_route_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'route',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'label',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => ['form_id']
        ],
        'field_type'      => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'system_form_field_type'
            ],
            'fields'    => [
                'id'    => [
                    'type' => 'int'
                ],
                'token' => [
                    'type' => 'string'
                ]
            ],
            'relations' => [
                [
                    'field' => 'token',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => ['id']
        ],
        'field_validator' => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'system_form_field_validator'
            ],
            'fields'    => [
                'id'    => [
                    'type' => 'int'
                ],
                'token' => [
                    'type' => 'string'
                ]
            ],
            'relations' => [
                [
                    'field' => 'token',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => ['id']
        ],
        'route'           => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'system_route'
            ],
            'fields'    => [
                'id'                     => [
                    'type' => 'int'
                ],
                'method_get'             => [
                    'type' => 'int'
                ],
                'method_post'            => [
                    'type' => 'int'
                ],
                'method_put'             => [
                    'type' => 'int'
                ],
                'method_delete'          => [
                    'type' => 'int'
                ],
                'url'                    => [
                    'type' => 'string'
                ],
                'module'                 => [
                    'type' => 'string'
                ],
                'action'                 => [
                    'type' => 'string'
                ],
                'alias'                  => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'h1_token'               => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'menu_name_token'        => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'meta_title_token'       => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'meta_description_token' => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'template_id'            => [
                    'type'     => 'int',
                    'nullable' => true
                ],
                'auth_require'           => [
                    'type' => 'int',
                ],
                'on_success'             => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'on_error'               => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'enabled'                => [
                    'type' => 'int',
                ],
                'event_id'               => [
                    'type'     => 'int',
                    'nullable' => true
                ],
                'description'            => [
                    'type'     => 'string',
                    'nullable' => true
                ],
                'created'                => [
                    'type' => 'datetime',
                ],
                'updated'                => [
                    'type' => 'datetime',
                ],
                'deleted'                => [
                    'type'     => 'datetime',
                    'nullable' => true
                ],
            ],
            'relations' => [
                [
                    'field' => 'event_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'event',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'template_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'template',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'h1_token',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => ['id']
        ],
        'event'           => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'event'
            ],
            'fields'    => [
                'id'          => [
                    'type' => 'int'
                ],
                'token'       => [
                    'type' => 'string'
                ],
                'description' => [
                    'type' => 'string'
                ],
                'enabled'     => [
                    'type' => 'int'
                ],
                'date_from'   => [
                    'type'     => 'date',
                    'nullable' => true
                ],
                'date_to'     => [
                    'type'     => 'date',
                    'nullable' => true
                ],
                'time_from'   => [
                    'type'     => 'time',
                    'nullable' => true
                ],
                'time_to'     => [
                    'type'     => 'time',
                    'nullable' => true
                ],
            ],
            'relations' => [
                [
                    'field' => 'token',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => ['id']
        ],
        'template'        => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['system'],
                'table'      => 'system_template'
            ],
            'fields'    => [
                'id'       => [
                    'type' => 'int'
                ],
                'code'     => [
                    'type' => 'string'
                ],
                'token'    => [
                    'type' => 'string'
                ],
                'filename' => [
                    'type' => 'string'
                ],
            ],
            'relations' => [
                [
                    'field' => 'token',
                    'type'  => 'linkedThrough',
                    'link'  => [
                        'name'       => 'token',
                        'field'      => 'token',
                        'link_field' => 'id'
                    ],
                    'table' => [
                        'name'  => 'translation',
                        'field' => 'token_id'
                    ],
                ]
            ],
            'indexes'   => ['id']
        ],
        'token'           => [
            'provider' => [
                'type'       => 'mysqli',
                'connection' => $connections['front'],
                'table'      => 'catalog_translation_token'
            ],
            'fields'   => [
                'id'            => [
                    'type' => 'int'
                ],
                'token'         => [
                    'type' => 'string',
                ],
                'default_value' => [
                    'type' => 'string',
                ]
            ],
            'indexes'  => [
                'id',
                'token'
            ],
        ],
        'translation'     => [
            'provider'  => [
                'type'       => 'mysqli',
                'connection' => $connections['front'],
                'table'      => 'catalog_translation'
            ],
            'fields'    => [
                'id'       => [
                    'type' => 'int'
                ],
                'lang_id'  => [
                    'type' => 'int',
                ],
                'token_id' => [
                    'type' => 'int',
                ],
                'value'    => [
                    'type' => 'string',
                ]
            ],
            'relations' => [
                [
                    'field' => 'lang_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'lang',
                        'field' => 'id'
                    ]
                ],
                [
                    'field' => 'token_id',
                    'type'  => 'dependsOn',
                    'table' => [
                        'name'  => 'token',
                        'field' => 'id'
                    ]
                ],
            ]
        ],
        'lang'            => [
            'provider'   => [
                'type'       => 'mysqli',
                'connection' => $connections['front'],
                'table'      => 'catalog_language'
            ],
            'fields'     => [
                'id'    => [
                    'type' => 'int'
                ],
                'code'  => [
                    'type' => 'string'
                ],
                'token' => [
                    'type' => 'string'
                ],
            ],
            'primaryKey' => 'id',
            'indexes'    => ['id']
        ]
    ];