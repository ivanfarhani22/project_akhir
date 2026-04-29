

<?php $__env->startSection('title', 'Kelola Kelas'); ?>
<?php $__env->startSection('icon', 'fas fa-chalkboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-8">
        <div>
            <h1 class="text-xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 flex-shrink-0">
                    <i class="fas fa-chalkboard text-sm sm:text-base"></i>
                </span>
                Kelola Kelas
            </h1>
            <p class="text-gray-600 mt-2 text-xs sm:text-sm">Kelola kelas, mata pelajaran, guru, dan siswa</p>
        </div>
        <a href="<?php echo e(route('admin.classes.create')); ?>" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Kelas
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-6 mb-6">
        <form method="GET" action="<?php echo e(route('admin.classes.index')); ?>" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end">
            <!-- Search Input -->
            <div class="flex-1">
                <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-search"></i> Cari Kelas
                </label>
                <input type="text" name="search" placeholder="Cari nama kelas..." 
                       value="<?php echo e(request('search')); ?>"
                       class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition">
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition justify-center whitespace-nowrap">
                <i class="fas fa-search"></i> Cari
            </button>

            <!-- Reset Button -->
            <a href="<?php echo e(route('admin.classes.index')); ?>" class="inline-flex items-center gap-2 bg-gray-200 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-300 transition justify-center whitespace-nowrap">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>

        <!-- Info Text -->
        <p class="text-gray-500 text-xs sm:text-sm mt-4">
            Menampilkan <strong><?php echo e($classes->count()); ?></strong> dari <strong><?php echo e($classes->total()); ?></strong> kelas
        </p>
    </div>

    <?php if($classes->isEmpty()): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
            <i class="fas fa-inbox text-5xl sm:text-6xl text-gray-300 mb-4 inline-block"></i>
            <h3 class="text-gray-600 text-base sm:text-lg font-semibold mb-2">Tidak ada kelas ditemukan</h3>
            <p class="text-gray-500 mb-4 text-xs sm:text-sm">
                <?php if(request('search')): ?>
                    Coba ubah pencarian Anda atau <a href="<?php echo e(route('admin.classes.index')); ?>" class="text-red-600 hover:text-red-700 font-semibold">reset filter</a>
                <?php else: ?>
                    Mulai dengan <a href="<?php echo e(route('admin.classes.create')); ?>" class="text-red-600 hover:text-red-700 font-semibold">membuat kelas baru</a>
                <?php endif; ?>
            </p>
        </div>
    <?php else: ?>
        <!-- Classes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mb-8">
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-4 text-white">
                        <h3 class="text-lg font-bold mb-2"><?php echo e($class->name); ?></h3>
                        <?php if($class->day_of_week): ?>
                            <div class="text-sm opacity-90">
                                <i class="fas fa-calendar"></i> <?php echo e(ucfirst($class->day_of_week)); ?>

                                <?php if($class->start_time && $class->end_time): ?>
                                    • <?php echo e(\Carbon\Carbon::createFromFormat('H:i', $class->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::createFromFormat('H:i', $class->end_time)->format('H:i')); ?>

                                <?php endif; ?>
                                <?php if($class->room): ?>
                                    • <?php echo e($class->room); ?>

                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-4 flex flex-col">
                        <!-- Subjects Section -->
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-semibold text-gray-600 uppercase">📖 Mata Pelajaran</span>
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold"><?php echo e($class->classSubjects->count()); ?></span>
                            </div>
                            <?php if($class->classSubjects->isNotEmpty()): ?>
                                <ul class="space-y-1">
                                    <?php $__currentLoopData = $class->classSubjects->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="text-sm text-gray-600">• <?php echo e($cs->subject->name); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($class->classSubjects->count() > 2): ?>
                                        <li class="text-xs text-gray-500 italic">+<?php echo e($class->classSubjects->count() - 2); ?> lainnya</li>
                                    <?php endif; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">⚠️ Belum ada mata pelajaran</p>
                            <?php endif; ?>
                        </div>

                        <!-- Students Section -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold text-gray-600 uppercase">👥 Siswa</span>
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-semibold"><?php echo e($class->students->count()); ?></span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 mt-auto">
                            <a href="<?php echo e(route('admin.classes.show', $class)); ?>" class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-900 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="<?php echo e(route('admin.classes.edit', $class)); ?>" class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-900 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <?php if($classes->hasPages()): ?>
            <div class="flex justify-center items-center gap-3">
                <!-- Previous Button -->
                <?php if($classes->onFirstPage()): ?>
                    <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-semibold text-sm opacity-50 cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                <?php else: ?>
                    <a href="<?php echo e($classes->previousPageUrl()); ?>" class="inline-flex items-center gap-2 bg-gray-200 text-gray-900 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                <?php endif; ?>

                <!-- Page Info -->
                <div class="px-4 py-2 bg-gray-100 rounded-lg text-sm text-gray-600 font-medium">
                    Halaman <?php echo e($classes->currentPage()); ?> dari <?php echo e($classes->lastPage()); ?>

                </div>

                <!-- Next Button -->
                <?php if($classes->hasMorePages()): ?>
                    <a href="<?php echo e($classes->nextPageUrl()); ?>" class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-semibold text-sm opacity-50 cursor-not-allowed">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/classes/index.blade.php ENDPATH**/ ?>