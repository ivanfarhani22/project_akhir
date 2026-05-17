
<?php $__env->startSection('title', 'Mata Pelajaran'); ?>
<?php $__env->startSection('icon', 'fas fa-book'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-start justify-between gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Siswa / Mata Pelajaran</p>
        <h1 class="text-2xl font-extrabold text-gray-900">Mata Pelajaran</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar mata pelajaran yang Anda ikuti.</p>
    </div>
</div>

<?php if(($classSubjects ?? collect())->isEmpty()): ?>
    <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
        <p class="text-gray-500 text-sm">Belum ada mata pelajaran.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php $__currentLoopData = $classSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('siswa.subjects.show', $cs)); ?>"
               class="group block bg-white rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-md transition overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-emerald-400 to-teal-400"></div>
                <div class="p-5">
                    <h3 class="font-extrabold text-gray-900 truncate"><?php echo e($cs->subject->name); ?></h3>
                    <p class="text-xs text-gray-500 mt-1"><?php echo e($cs->eClass->name); ?></p>
                    <p class="text-xs text-gray-400 mt-2"><i class="fas fa-chalkboard-teacher mr-1"></i><?php echo e($cs->teacher->name); ?></p>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/siswa/subjects/index.blade.php ENDPATH**/ ?>