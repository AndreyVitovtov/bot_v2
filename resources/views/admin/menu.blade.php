@php
    $menu = json_decode(file_get_contents(public_path('json/menu-admin.json')), true);
@endphp
@foreach($menu as $name => $m)
    @accessP($name)
        @if(is_array($m) && $m['type'] === 'item')
            @component('menu.menu-item', [
                'name' => $m['name'],
                'icon' => $m['icon'],
                'menu' => $m['menu'],
                'url' => route($m['url'])
            ])@endcomponent
        @else
            @php
            foreach($m['items'] as &$i) {
                $url = $i['url'];
                $i['url'] = route($url);
            }
            @endphp
            @component('menu.menu-rolled', [
                'nameItem' => $m['nameItem'],
                'icon' => $m['icon'],
                'name' => $m['name'],
                'items' => $m['items']
            ])@endcomponent
        @endif
    @endaccessP
@endforeach
