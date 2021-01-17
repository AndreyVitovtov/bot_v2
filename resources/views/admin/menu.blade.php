@accessP('statistics')
    @component('menu.menu-item', [
        'name' => 'statistics',
        'icon' => 'icon-gauge',
        'menu' => 'statistics',
        'url' => '/admin'])
    @endcomponent
@endaccessP

@accessP('users')
    @component('menu.menu-item', [
        'name' => 'users',
        'icon' => 'icon-users-3',
        'menu' => 'users',
        'url' => '/admin/users'])
    @endcomponent
@endaccessP

@accessP('mailing')
    @component('menu.menu-item', [
        'name' => 'mailing',
        'icon' => 'icon-mail-4',
        'menu' => 'mailing',
        'url' => '/admin/mailing'])
    @endcomponent
@endaccessP

@accessP('moderators')
    @component('menu.menu-rolled', [
        'nameItem' => 'moderators',
        'icon' => 'icon-users-2',
        'name' => 'moderators',
        'items' => [[
               'name' => 'moderators_list',
               'menu' => 'moderatorslist',
               'url' => '/admin/moderators'
            ],[
               'name' => 'moderators_add',
               'menu' => 'moderatorsadd',
               'url' => '/admin/moderators/add'
            ],[
               'name' => 'moderators_permissions',
               'menu' => 'moderatorspermissions',
               'url' => '/admin/moderators/permissions'
            ]
        ]])
    @endcomponent
@endaccessP

@accessP('languages')
    @component('menu.menu-rolled', [
        'nameItem' => 'languages',
        'icon' => 'icon-language-1',
        'name' => 'languages',
        'items' => [[
                'name' => 'languages_list',
                'menu' => 'languageslist',
                'url' => '/admin/languages/list'
            ],[
               'name' => 'languages_add',
               'menu' => 'languagesadd',
               'url' => '/admin/languages/add'
            ]
        ]])
    @endcomponent
@endaccessP

@accessP('contacts')
    @component('menu.menu-rolled', [
        'nameItem' => 'contacts',
        'icon' => 'icon-book',
        'name' => 'contacts',
        'items' => [[
                'name' => 'contacts_general',
                'menu' => 'contactsgeneral',
                'url' => '/admin/contacts/general'
            ],
            [
               'name' => 'contacts_access',
               'menu' => 'contactsaccess',
               'url' => '/admin/contacts/access'
            ],
            [
               'name' => 'contacts_advertising',
               'menu' => 'contactsadvertising',
               'url' => '/admin/contacts/advertising'
            ],[
               'name' => 'contacts_offers',
               'menu' => 'contactsoffers',
               'url' => '/admin/contacts/offers'
            ]
        ]])
    @endcomponent
@endaccessP

@accessP('answers')
    @component('menu.menu-rolled', [
        'nameItem' => 'answers',
        'icon' => 'icon-help-1',
        'name' => 'answers',
        'items' => [[
                'name' => 'answers_list',
                'menu' => 'answerslist',
                'url' => '/admin/answers/list'
            ],[
               'name' => 'answers_add',
               'menu' => 'answersadd',
               'url' => '/admin/answers/add'
            ]
        ]])
    @endcomponent
@endaccessP

{{--@accessP('payment')--}}
{{--    @component('menu.menu-rolled', [--}}
{{--        'nameItem' => 'payment',--}}
{{--        'icon' => 'icon-money-2',--}}
{{--        'name' => 'admin_pay',--}}
{{--        'items' => [[--}}
{{--                'name' => 'admin_qiwi',--}}
{{--                'menu' => 'payqiwi',--}}
{{--                'url' => '/admin/payment/qiwi'--}}
{{--            ],[--}}
{{--               'name' => 'admin_yandex_noney',--}}
{{--               'menu' => 'payyandex',--}}
{{--               'url' => '/admin/payment/yandex'--}}
{{--            ],[--}}
{{--               'name' => 'admin_webmoney',--}}
{{--               'menu' => 'paywebmoney',--}}
{{--               'url' => '/admin/payment/webmoney'--}}
{{--            ],[--}}
{{--               'name' => 'admin_paypal',--}}
{{--               'menu' => 'paypaypal',--}}
{{--               'url' => '/admin/payment/paypal'--}}
{{--            ]--}}
{{--        ]])--}}
{{--    @endcomponent--}}
{{--@endaccessP--}}

@accessP('settings')
    @component('menu.menu-rolled', [
        'nameItem' => 'settings',
        'icon' => 'icon-cog-alt',
        'name' => 'settings',
        'items' => [
            [
            'name' => 'settings_main',
            'menu' => 'settingsmain',
            'url' => '/admin/settings/main'
            ],
            [
               'name' => 'settings_pages',
               'menu' => 'settingspages',
               'url' => '/admin/settings/pages'
            ],[
               'name' => 'settings_buttons',
               'menu' => 'settingsbuttons',
               'url' => '/admin/settings/buttons'
            ]
        ]])
    @endcomponent
@endaccessP
