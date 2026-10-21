<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title', 'type', 'messages']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title', 'type', 'messages']); ?>
<?php foreach (array_filter((['title', 'type', 'messages']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div id="alert-container" class="alert alert-<?php echo e($type); ?> fade show alert-dismissible" role="alert" style="display: <?php echo e($display); ?>;">
    <strong class="font-weight-bolder"><?php echo e($title); ?></strong>
    <div id="error-messages">
        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <p class="mb-0"><?php echo e($message); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php /**PATH /Users/bgsprtm/Herd/magang-kominfotik/resources/views/components/alert.blade.php ENDPATH**/ ?>