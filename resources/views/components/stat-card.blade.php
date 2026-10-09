@props([
    'label',
    'value',
    'icon' => 'fa-chart-bar',
    'color' => 'primary',   // primary | success | danger | warning | info | secondary
    'sub' => null,
])

{{-- Kartu ringkasan seragam dipakai di seluruh aplikasi (beranda admin,
     beranda magang, Data User). Satu gaya untuk semua agar konsisten. --}}
<div {{ $attributes->merge(['class' => 'card stat-card mb-0 h-100']) }}>
    <div class="card-body d-flex align-items-center">
        <div class="stat-card__icon stat-card__icon--{{ $color }}">
            <i class="fas {{ $icon }}"></i>
        </div>
        <div class="ml-3 min-w-0">
            <div class="stat-card__label">{{ $label }}</div>
            <div class="stat-card__value">{{ $value }}</div>
            @if($sub)
                <div class="stat-card__sub">{{ $sub }}</div>
            @endif
        </div>
    </div>
</div>
