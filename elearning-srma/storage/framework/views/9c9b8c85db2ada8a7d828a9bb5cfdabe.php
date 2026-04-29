

<?php $__env->startSection('title', 'Kelola Tugas'); ?>
<?php $__env->startSection('icon', 'fas fa-tasks'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-xs sm:text-sm text-gray-600">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Tugas</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-9 sm:w-10 h-9 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-sm sm:text-base">
                    <i class="fas fa-tasks"></i>
                </span>
                Kelola Tugas
            </h1>
            <p class="text-gray-600 mt-2 text-xs sm:text-sm">Kelola semua tugas untuk semua kelas dan mata pelajaran</p>
        </div>
        <a href="<?php echo e(route('admin.assignments.create')); ?>" class="inline-flex items-center justify-center gap-2 px-3 sm:px-6 py-2 sm:py-3 bg-red-500 text-white rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
            <i class="fas fa-plus"></i> <span class="hidden sm:inline">Tambah Tugas</span><span class="sm:hidden">Tambah</span>
        </a>
    </div>

    <!-- Success Alert -->
    <?php if(session('success')): ?>
        <div class="mb-6 p-3 sm:p-4 bg-green-100 border-2 border-green-500 text-green-700 rounded-lg flex items-center justify-between text-xs sm:text-sm">
            <span class="flex items-center gap-2">
                <i class="fas fa-check-circle flex-shrink-0"></i>
                <?php echo e(session('success')); ?>

            </span>
            <button onclick="this.parentElement.style.display='none';" class="text-green-700 hover:text-green-900 flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Search & Filter Card -->
    <div class="bg-white rounded-lg shadow-md p-3 sm:p-6 mb-8">
        <form action="<?php echo e(route('admin.assignments.index')); ?>" method="GET" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end">
            <input type="text" name="search" placeholder="Cari tugas..." 
                class="flex-1 min-w-xs px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition"
                value="<?php echo e(request('search')); ?>">
            <select name="class" class="w-full sm:w-48 px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition">
                <option value="">Semua Kelas</option>
                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($class->id); ?>" <?php if(request('class') == $class->id): echo 'selected'; endif; ?>>
                        <?php echo e($class->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 sm:px-6 py-2 bg-blue-500 text-white rounded-lg font-semibold text-xs sm:text-sm hover:bg-blue-600 transition whitespace-nowrap">
                <i class="fas fa-search"></i> <span class="hidden sm:inline">Cari</span>
            </button>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-3 sm:py-4">
            <h2 class="text-white font-semibold text-sm sm:text-lg flex items-center gap-2">
                <i class="fas fa-list"></i>
                Daftar Tugas
            </h2>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <?php if($assignments->count() > 0): ?>
                <table class="w-full border-collapse text-xs sm:text-sm">
                    <thead class="bg-gray-100">
                        <tr class="border-b-2 border-gray-300">
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 w-8 sm:w-10">#</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700">Judul Tugas</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 hidden sm:table-cell w-24 sm:w-32">Kelas</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 hidden md:table-cell w-24 sm:w-32">Guru</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 hidden lg:table-cell w-32 sm:w-40">Deadline</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 hidden lg:table-cell w-32 sm:w-40">Submission</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $className = $assignment->classSubject?->eClass?->name ?? $assignment->eClass?->name;

                                // Teacher fallback:
                                // - preferred: assignment->classSubject->teacher
                                // - legacy: derive from class (EClass accessor uses first classSubject teacher)
                                $teacherName = $assignment->classSubject?->teacher?->name
                                    ?? $assignment->eClass?->teacher?->name;
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-2 sm:px-6 py-2 sm:py-4 text-gray-900 font-medium"><?php echo e($loop->iteration); ?></td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4">
                                    <div class="font-semibold text-gray-900 truncate"><?php echo e($assignment->title); ?></div>
                                    <div class="text-xs text-gray-600 hidden sm:block"><?php echo e(Str::limit($assignment->description, 50)); ?></div>
                                </td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4 hidden sm:table-cell">
                                    <span class="inline-block px-2 sm:px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                        <?php echo e($className ?? '-'); ?>

                                    </span>
                                </td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4 hidden md:table-cell text-gray-700"><?php echo e($teacherName ? Str::limit($teacherName, 15) : '-'); ?></td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4 hidden lg:table-cell">
                                    <?php if($assignment->deadline): ?>
                                        <span class="<?php if(now() > $assignment->deadline): ?> text-red-600 font-semibold <?php else: ?> text-gray-700 <?php endif; ?>">
                                            <?php echo e($assignment->deadline->format('d M Y H:i')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-500">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4 hidden lg:table-cell">
                                    <div class="flex gap-1 sm:gap-2">
                                        <span class="inline-block px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-700 rounded text-xs font-semibold whitespace-nowrap">
                                            <?php echo e($assignment->submissions_count ?? 0); ?>

                                        </span>
                                        <span class="inline-block px-1.5 sm:px-2 py-0.5 sm:py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold whitespace-nowrap">
                                            <?php echo e($assignment->pending_count ?? 0); ?>

                                        </span>
                                    </div>
                                </td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4">
                                    <div class="flex gap-1 sm:gap-2 flex-col sm:flex-row">
                                        <a href="<?php echo e(route('admin.assignments.show', $assignment)); ?>" 
                                            class="inline-flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-3 py-1.5 sm:py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-xs font-semibold whitespace-nowrap"
                                            title="Lihat">
                                            <i class="fas fa-eye"></i> <span class="hidden sm:inline">Lihat</span>
                                        </a>
                                        <a href="<?php echo e(route('admin.assignments.edit', $assignment)); ?>" 
                                            class="inline-flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-3 py-1.5 sm:py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition text-xs font-semibold whitespace-nowrap"
                                            title="Edit">
                                            <i class="fas fa-pencil"></i> <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <form action="<?php echo e(route('admin.assignments.destroy', $assignment)); ?>" 
                                            method="POST" class="inline w-full sm:w-auto delete-form">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="button" onclick="confirmDelete(event, '<?php echo e($assignment->title); ?>')" class="w-full sm:w-auto inline-flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-3 py-1.5 sm:py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-xs font-semibold whitespace-nowrap" 
                                                title="Hapus">
                                                <i class="fas fa-trash"></i> <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-3 sm:px-6 py-6 sm:py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2 block opacity-50"></i>
                                    <p class="mt-2">Tidak ada tugas</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <?php echo e($assignments->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500 mb-4">Belum ada tugas dibuat</p>
                    <a href="<?php echo e(route('admin.assignments.create')); ?>" class="inline-flex items-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                        <i class="fas fa-plus"></i> Buat Tugas Baru
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-chart-bar"></i>
                Statistik Tugas
            </h2>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-red-600"><?php echo e($statistics['total'] ?? 0); ?></span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Total Tugas</p>
                <p class="text-gray-600 text-sm mt-1">Semua tugas yang dibuat</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-green-600"><?php echo e($statistics['this_month'] ?? 0); ?></span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Bulan Ini</p>
                <p class="text-gray-600 text-sm mt-1">Tugas dibuat bulan ini</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-blue-600"><?php echo e($statistics['submission_rate'] ?? 0); ?>%</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Tingkat Pengumpulan</p>
                <p class="text-gray-600 text-sm mt-1">Rata-rata pengumpulan</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-yellow-600"><?php echo e($statistics['pending_grading'] ?? 0); ?></span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Menunggu Nilai</p>
                <p class="text-gray-600 text-sm mt-1">Tugas belum dinilai</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Yakin ingin menghapus tugas "${name}"?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/assignments/index.blade.php ENDPATH**/ ?>