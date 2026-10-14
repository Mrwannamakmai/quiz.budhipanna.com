{{-- เมนูที่โมดูลลงทะเบียนไว้ (App\Support\Modules\ModuleRegistry) --}}
@php
    $__modItems = app(\App\Support\Modules\ModuleRegistry::class)->menuFor($area, auth()->user());
    $__lastSection = null;
@endphp
@foreach($__modItems as $__m)
    @if(($style ?? 'sidebar') === 'navbar')
        <li class="nav-item {{ request()->routeIs($__m['active'] ?? $__m['route']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route($__m['route']) }}"><i class="{{ $__m['icon'] }} mr-1"></i>{{ $__m['label'] }}</a>
        </li>
    @else
        @if($__m['section'] && $__m['section'] !== $__lastSection)
            <div class="nav-section">{{ $__m['section'] }}</div>
            @php $__lastSection = $__m['section']; @endphp
        @endif
        <a href="{{ route($__m['route']) }}" class="nav-link {{ request()->routeIs($__m['active'] ?? $__m['route']) ? 'active' : '' }}" data-title="{{ $__m['label'] }}">
            <i class="{{ $__m['icon'] }} fa-fw"></i> <span>{{ $__m['label'] }}</span>
        </a>
    @endif
@endforeach
