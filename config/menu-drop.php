<?php

return [
    'system' => [
        'name' => '系统管理',
        'url' => '',// url 可以填写http 也可以直接写路由
        'urlParams' => 'xxx=33&xxxd=4',//如果是url填写的是路由则启用参数否则不启用
        'permit' => 1,//如果不设置则不会做权限检测
        'menu' => 1,//如果不设置则不显示菜单，子菜单也将不显示
        'icon' => '',//菜单图标
        'child' => [
            'menu' => [
                'name' => '菜单管理',
                'permit' => 1,
                'menu' => 1,
                'icon' => '',
                'url' => 'menu.index',
                'urlParams' => ['parent_id' => 5],
                'child' => [
                    'menu.index' => ['name' => '菜单列表', 'permit' => 1,],
                    'menu.add' => ['name' => '新增菜单', 'permit' => 1,],
                    'menu.edit' => ['name' => '修改菜单', 'permit' => 1,],
                    'menu.sort' => ['name' => '菜单排序', 'permit' => 1,],
                    'menu.status' => ['name' => '菜单状态管理', 'permit' => 1,],
                    'menu.delete' => ['name' => '删除菜单', 'permit' => 1,],
                ]
            ],
            'user' => [
                'name' => '用户管理',
                'permit' => 1,
                'menu' => 1,
                'icon' => '',
                'url' => 'user.index',
                'child' => [
                    'user.index' => ['name' => '用户列表', 'permit' => 1],
                    'user.add' => ['name' => '新增用户', 'permit' => 1,],
                    'user.edit' => ['name' => '修改用户', 'permit' => 1,],
                    'user.status' => ['name' => '用户状态管理', 'permit' => 1,],
                    'user.delete' => ['name' => '删除用户', 'permit' => 1,],
                ]
            ],
            'role' => [
                'name' => '角色管理'
            ],
        ]
    ],
    'goods' => [
        'name' => '产品管理',
        'url' => '',// url 可以填写http 也可以直接写路由
        'urlParams' => ['parent_id' => 5],//如果是url填写的是路由则启用参数否则不启用
        'permit' => 1,//如果不设置则不会做权限检测
        'menu' => 1,//如果不设置则不显示菜单，子菜单也将不显示
        'icon' => '',//菜单图标
        'child' => [
            'brand' => [
                'name' => '品牌管理',
                'url' => 'goods.brand.index',// url 可以填写http 也可以直接写路由
                'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                'permit' => 1,//如果不设置则不会做权限检测
                'menu' => 1,//如果不设置则不显示菜单，子菜单也将不显示
                'icon' => '',//菜单图标
                'child' => [
                    'goods.brand.add' => [
                        'name' => '创建品牌',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标]
                    ],
                    'goods.brand.edit' => [
                        'name' => '修改品牌',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标
                    ],
                    'goods.brand.deleted' => [
                        'name' => '删除品牌',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标
                    ]
                ]
            ],
            'category' => [
                'name' => '分类管理',
                'url' => 'goods.category.index',// url 可以填写http 也可以直接写路由
                'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                'permit' => 1,//如果不设置则不会做权限检测
                'menu' => 1,//如果不设置则不显示菜单，子菜单也将不显示
                'icon' => '',//菜单图标
                'child' => [
                    'goods.category.add' => [
                        'name' => '创建分类',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标]
                    ],
                    'goods.category.edit' => [
                        'name' => '修改分类',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标
                    ],
                    'goods.category.deleted' => [
                        'name' => '删除分类',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标
                    ]
                ]
            ],
            'comment' => [
                'name' => '评论管理',
                'url' => 'goods.comment.index',// url 可以填写http 也可以直接写路由
                'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                'permit' => 1,//如果不设置则不会做权限检测
                'menu' => 1,//如果不设置则不显示菜单，子菜单也将不显示
                'icon' => '',//菜单图标
                'child' => [
                    'goods.comment.add' => [
                        'name' => '创建评论',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标]
                    ],
                    'goods.comment.edit' => [
                        'name' => '修改评论',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标
                    ],
                    'goods.comment.deleted' => [
                        'name' => '删除评论',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标
                    ],
                    'goods.comment.reply' => [
                        'name' => '回复评论',
                        'url' => '',// url 可以填写http 也可以直接写路由
                        'urlParams' => [],//如果是url填写的是路由则启用参数否则不启用
                        'permit' => 1,//如果不设置则不会做权限检测
                        'menu' => false,//如果不设置则不显示菜单，子菜单也将不显示
                        'icon' => '',//菜单图标
                    ]
                ]
            ]
        ]
    ]
];