<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'label',
    'value',
    'icon' => 'fa-chart-bar',
    'color' => 'primary',   // primary | success | danger | warning | info | secondary
    'sub' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'label',
    'value',
    'icon' => 'fa-chart-bar',
    'color' => 'primary',   // primary | success | danger | warning | info | secondary
    'sub' => null,
]); ?>
<?php foreach (array_filter(([
    'label',
    'value',
    'icon' => 'fa-chart-bar',
    'color' => 'primary',   // primary | success | danger | warning | info | secondary
    'sub' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>


<div <?php echo e($attributes->merge(['class' => 'card stat-card mb-0 h-100'])); ?>>
    <div class="card-body d-flex align-items-center">
        <div class="stat-card__icon stat-card__icon--<?php echo e($color); ?>">
            <i class="fas <?php echo e($icon); ?>"></i>
        </div>
        <div class="ml-3 min-w-0">
            <div class="stat-card__label"><?php echo e($label); ?></div>
            <div class="stat-card__value"><?php echo e($value); ?></div>
            <?php if($sub): ?>
                <div class="stat-card__sub"><?php echo e($sub); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/components/stat-card.blade.php ENDPATH**/ ?>