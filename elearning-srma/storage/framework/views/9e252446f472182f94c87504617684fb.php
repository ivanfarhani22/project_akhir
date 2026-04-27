
<?php $__env->startSection('title', 'Upload Materi'); ?>
<?php $__env->startSection('icon', 'fas fa-book'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Guru / Materi</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-book text-[#A41E35] mr-2"></i>Materi Pembelajaran</h1>
    <p class="text-sm text-gray-500 mt-1">Pilih kelas/mapel untuk mengelola materi pembelajaran</p>
</div>

<?php if($classSubjects->count() > 0): ?>
    <div class="grid grid-cols-1 sm:flex-row sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php $__currentLoopData = $classSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-[#A41E35] hover:shadow-lg transition-all duration-200 overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
                <div class="p-5">
                    <h3 class="text-base font-bold text-gray-900 mb-1"><?php echo e($cs->eClass->name); ?></h3>
                    <p class="text-xs text-gray-500 mb-0.5">
                        <i class="fas fa-book-open mr-1"></i>
                        <?php echo e($cs->subject->name); ?>

                    </p>
                    <p class="text-xs text-gray-400 mb-5"><i class="fas fa-users mr-1"></i> <?php echo e($cs->eClass->students->count()); ?> siswa</p>

                    <a href="<?php echo e(route('guru.materials.index', ['class_subject_id' => $cs->id])); ?>"
                       class="w-full inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition-all shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-right text-xs"></i> Kelola Materi
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-chalkboard text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Anda belum mengajar kelas apapun.</p>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/guru/materials/create-select-class.blade.php ENDPATH**/ ?>