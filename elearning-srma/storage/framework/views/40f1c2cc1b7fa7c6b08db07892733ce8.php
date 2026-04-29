
<?php $__env->startSection('title', 'Mata Pelajaran'); ?>
<?php $__env->startSection('icon', 'fas fa-book'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Siswa / Mata Pelajaran</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-book text-amber-500 mr-2"></i>Mata Pelajaran</h1>
    <p class="text-sm text-gray-500 mt-1">Daftar mata pelajaran yang Anda pelajari</p>
</div>

<?php if($classes->count() > 0): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-amber-400 hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col">
                <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="mb-3">
                        <h3 class="text-base font-bold text-gray-900 truncate"><?php echo e($class->subject->name); ?></h3>
                        <p class="text-xs text-gray-400 mt-0.5"><?php echo e($class->name); ?></p>
                    </div>
                    <p class="text-xs text-gray-500 mb-1"><i class="fas fa-chalkboard-teacher mr-1"></i><?php echo e($class->teacher->name); ?></p>
                    <?php if($class->description): ?>
                        <p class="text-xs text-gray-400 line-clamp-2 mb-3"><?php echo e(Str::limit($class->description, 80)); ?></p>
                    <?php endif; ?>

                    <div class="py-3 border-t border-b border-gray-100 my-3">
                        <p class="text-xs text-gray-400 mb-1">
                            <?php if($class->schedules && $class->schedules->count() > 0): ?>
                                <?php $sch = $class->schedules->first(); ?>
                                <i class="fas fa-calendar mr-1"></i><?php echo e(ucfirst($sch->day_of_week)); ?>

                                <?php if($sch->start_time): ?> • <?php echo e(\Carbon\Carbon::createFromTimeString($sch->start_time)->format('H:i')); ?><?php if($sch->end_time): ?> – <?php echo e(\Carbon\Carbon::createFromTimeString($sch->end_time)->format('H:i')); ?><?php endif; ?> <?php endif; ?>
                            <?php else: ?>
                                <i class="fas fa-calendar mr-1"></i>TBA
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="text-center bg-blue-50 rounded-xl py-3">
                            <p class="text-xl font-extrabold text-blue-600"><?php echo e($class->materials->count()); ?></p>
                            <p class="text-xs text-gray-400 mt-0.5">Materi</p>
                        </div>
                        <div class="text-center bg-amber-50 rounded-xl py-3">
                            <p class="text-xl font-extrabold text-amber-600"><?php echo e($class->assignments->count()); ?></p>
                            <p class="text-xs text-gray-400 mt-0.5">Tugas</p>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-auto">
                        <a href="<?php echo e(route('siswa.subjects.show', $class->id)); ?>"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold py-2.5 rounded-xl transition">
                            <i class="fas fa-eye text-[10px]"></i> Detail
                        </a>
                        <a href="<?php echo e(route('siswa.assignments.index')); ?>?class=<?php echo e($class->id); ?>"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 rounded-xl transition">
                            <i class="fas fa-tasks text-[10px]"></i> Tugas
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-book text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm mb-1">Anda belum terdaftar di mata pelajaran apapun.</p>
            <p class="text-xs text-gray-400">Hubungi administrator untuk mendaftar.</p>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/siswa/subjects/index.blade.php ENDPATH**/ ?>