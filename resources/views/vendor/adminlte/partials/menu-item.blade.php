@php
    $role=Auth::user()->role;

@endphp
@if (is_string($item))
    <li class="header">{{ $item }}</li>
@else
    @php //echo "<pre>";print_r(array_keys($permissions)); @endphp
    @if($role==2 && (!empty($item['url']) && !empty($permissions[$item['url']]) && $permissions[$item['url']]) || (!empty($item['submenu']) && array_intersect(array_column($item['submenu'], 'url'),array_keys($permissions))))
        <li class="{{ $item['class'] }}">
            <a href="{{ $item['href'] }}"
               @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
            >
                <i class="fa fa-fw fa-{{ isset($item['icon']) ? $item['icon'] : 'circle-o' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
                <span>{{ $item['text'] }}</span>
                @if (isset($item['label']))
                    <span class="pull-right-container">
                    <span
                            class="label label-{{ isset($item['label_color']) ? $item['label_color'] : 'primary' }} pull-right">{{ $item['label'] }}</span>
                </span>
                @elseif (isset($item['submenu']))
                    <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                @endif
            </a>
            @if (isset($item['submenu']))
                <ul class="{{ $item['submenu_class'] }}">
                    @each('adminlte::partials.menu-item', $item['submenu'], 'item')
                </ul>
            @endif
        </li>

    @elseif($role==1)
        <li class="{{ $item['class'] }}">
            <a href="{{ $item['href'] }}"
               @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
            >
                <i class="fa fa-fw fa-{{ isset($item['icon']) ? $item['icon'] : 'circle-o' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
                <span>{{ $item['text'] }}</span>
                @if (isset($item['label']))
                    <span class="pull-right-container">
                    <span
                            class="label label-{{ isset($item['label_color']) ? $item['label_color'] : 'primary' }} pull-right">{{ $item['label'] }}</span>
                </span>
                @elseif (isset($item['submenu']))
                    <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                @endif
            </a>
            @if (isset($item['submenu']))
                <ul class="{{ $item['submenu_class'] }}">
                    @each('adminlte::partials.menu-item', $item['submenu'], 'item')
                </ul>
            @endif
        </li>
    @endif
@endif
