@component('menu.menu-item', [
   'name' => 'developer_admin_panel',
   'icon' => 'icon-user-3',
   'menu' => 'admin',
   'url' => '/admin'
])@endcomponent
@component('menu.menu-item', [
   'name' => 'developer_todo',
   'icon' => 'icon-list-alt',
   'menu' => 'todo',
   'url' => '/developer/todo'
])@endcomponent
{{--            @component('menu.menu-item', [--}}
{{--                'name' => 'developer_webhook',--}}
{{--                'icon' => 'icon-wind-1',--}}
{{--                'menu' => 'webhook',--}}
{{--                'url' => '/developer/webhook'])--}}
{{--            @endcomponent--}}
@component('menu.menu-item', [
    'name' => 'request',
    'icon' => 'icon-code-2',
    'menu' => 'request',
    'url' => '/developer/request'
])@endcomponent
@component('menu.menu-item', [
    'name' => 'send_request',
    'icon' => 'icon-rocket',
    'menu' => 'send_request',
    'url' => '/developer/request/send'
])@endcomponent
@component('menu.menu-item', [
    'name' => 'developer_answers',
    'icon' => 'icon-help-1',
    'menu' => 'answers',
    'url' => '/developer/answers'
])@endcomponent
@component('menu.menu-rolled', [
    'nameItem' => 'menumenu',
    'icon' => 'icon-th',
    'name' => 'menu',
    'items' => [
        [
            'name' => 'list',
            'menu' => 'menu-list',
            'url' => '/developer/menu/list'
        ], [
           'name' => 'add',
           'menu' => 'menu-add',
           'url' => '/developer/menu/add'
        ]
    ]
])@endcomponent
{{--@component('menu.menu-rolled', [--}}
{{--    'nameItem' => 'payment',--}}
{{--    'icon' => 'icon-money-2',--}}
{{--    'name' => 'developer_pay',--}}
{{--    'items' => [--}}
{{--        [--}}
{{--            'name' => 'developer_qiwi',--}}
{{--            'menu' => 'payqiwi',--}}
{{--            'url' => '/developer/payment/qiwi'--}}
{{--        ], [--}}
{{--           'name' => 'developer_yandex_noney',--}}
{{--           'menu' => 'payyandex',--}}
{{--           'url' => '/developer/payment/yandex'--}}
{{--        ], [--}}
{{--           'name' => 'developer_webmoney',--}}
{{--           'menu' => 'paywebmoney',--}}
{{--           'url' => '/developer/payment/webmoney'--}}
{{--        ], [--}}
{{--           'name' => 'developer_paypal',--}}
{{--           'menu' => 'paypaypal',--}}
{{--           'url' => '/developer/payment/paypal'--}}
{{--        ]--}}
{{--    ]--}}
{{--])@endcomponent--}}
@component('menu.menu-rolled', [
    'nameItem' => 'developer_admin_menu',
    'icon' => 'icon-menu-1',
    'name' => 'developer_admin_menu',
    'items' => [
        [
            'name' => 'list',
            'menu' => 'developeradminmenulist',
            'url' => '/developer/menu/admin/list'
        ], [
            'name' => 'add',
            'menu' => 'developeradminmenuadd',
            'url' => '/developer/menu/admin/add'
        ]
    ]
])@endcomponent

@component('menu.menu-rolled', [
    'nameItem' => 'developer_lang',
    'icon' => 'icon-language-1',
    'name' => 'developer_lang',
    'items' => [
        [
            'name' => 'developer_lang_menu_list',
            'menu' => 'developerlangmenulist',
            'url' => '/developer/lang/menu/list'
        ], [
            'name' => 'developer_lang_menu_add',
            'menu' => 'developerlangmenuadd',
            'url' => '/developer/lang/menu/add'
        ], [
            'name' => 'developer_lang_pages_list',
            'menu' => 'developerlangpageslist',
            'url' => '/developer/lang/pages/list'
        ], [
            'name' => 'developer_lang_pages_add',
            'menu' => 'developerlangpagesadd',
            'url' => '/developer/lang/pages/add'
        ]
    ]
])@endcomponent

@component('menu.menu-item', [
    'name' => 'developer_permissions',
    'icon' => 'icon-key-4',
    'menu' => 'permissions',
    'url' => '/developer/permissions'
])@endcomponent
@component('menu.menu-item', [
    'name' => 'developer_info',
    'icon' => 'icon-info-2',
    'menu' => 'info',
    'url' => '/developer/info'
])@endcomponent
@component('menu.menu-rolled', [
    'nameItem' => 'settings',
    'icon' => 'icon-cog-alt',
    'name' => 'developer_settings',
    'items' => [
        [
            'name' => 'developer_settings_main',
            'menu' => 'settingsmain',
            'url' => '/developer/settings/main'
        ], [
           'name' => 'developer_settings_pages',
           'menu' => 'settingspages',
           'url' => '/developer/settings/pages'
        ], [
           'name' => 'developer_settings_buttons',
           'menu' => 'settingsbuttons',
           'url' => '/developer/settings/buttons'
        ]
    ]
])@endcomponent
