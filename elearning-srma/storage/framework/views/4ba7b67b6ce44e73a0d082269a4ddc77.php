

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('icon', 'chart-line'); ?>

<?php $__env->startSection('content'); ?>
    <!-- STATISTICS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
        <!-- Total Siswa -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Total Siswa</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e(\App\Models\User::where('role', 'siswa')->count()); ?></p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg flex-shrink-0">
                    <i class="fas fa-users text-red-500 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Total Guru</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e(\App\Models\User::where('role', 'guru')->count()); ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg flex-shrink-0">
                    <i class="fas fa-chalkboard text-blue-500 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Total Kelas</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e(\App\Models\EClass::count()); ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg flex-shrink-0">
                    <i class="fas fa-book text-green-500 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Materi -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-amber-500">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Total Materi</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e(\App\Models\Material::count()); ?></p>
                </div>
                <div class="bg-amber-100 p-3 rounded-lg flex-shrink-0">
                    <i class="fas fa-file-alt text-amber-500 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <i class="fas fa-lightning-bolt text-red-500"></i>
            Aksi Cepat
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6">
            <!-- Tambah Pengguna -->
            <a href="<?php echo e(route('admin.users.create')); ?>" class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-4 sm:p-6 text-center group">
                <div class="text-4xl text-red-500 mb-4 group-hover:scale-110 transition">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Tambah Pengguna</h3>
                <p class="text-xs sm:text-sm text-gray-600">Buat akun guru atau siswa baru</p>
            </a>

            <!-- Tambah Kelas -->
            <a href="<?php echo e(route('admin.classes.create')); ?>" class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-4 sm:p-6 text-center group">
                <div class="text-4xl text-green-500 mb-4 group-hover:scale-110 transition">
                    <i class="fas fa-plus-square"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Tambah Kelas</h3>
                <p class="text-xs sm:text-sm text-gray-600">Buat kelas baru dengan guru</p>
            </a>

            <!-- Tambah Mata Pelajaran -->
            <a href="<?php echo e(route('admin.subjects.create')); ?>" class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-4 sm:p-6 text-center group">
                <div class="text-4xl text-amber-500 mb-4 group-hover:scale-110 transition">
                    <i class="fas fa-bookmark"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Tambah Mata Pelajaran</h3>
                <p class="text-xs sm:text-sm text-gray-600">Tambahkan mata pelajaran baru</p>
            </a>
        </div>
    </div>

    <!-- RECENT ACTIVITY -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <i class="fas fa-history text-red-500"></i>
            Aktivitas Terbaru
        </h2>
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left font-semibold text-gray-900">Pengguna</th>
                            <th class="px-3 sm:px-6 py-3 text-left font-semibold text-gray-900 hidden sm:table-cell">Aksi</th>
                            <th class="px-3 sm:px-6 py-3 text-left font-semibold text-gray-900 hidden md:table-cell">Deskripsi</th>
                            <th class="px-3 sm:px-6 py-3 text-left font-semibold text-gray-900 hidden lg:table-cell">IP Address</th>
                            <th class="px-3 sm:px-6 py-3 text-left font-semibold text-gray-900">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = \App\Models\ActivityLog::with('user')->orderBy('timestamp', 'desc')->take(15)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-red-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            <?php echo e(substr($log->user->name, 0, 1)); ?>

                                        </div>
                                        <span class="text-xs sm:text-sm text-gray-900"><?php echo e($log->user->name); ?></span>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 hidden sm:table-cell">
                                    <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-2 sm:px-3 py-1 rounded-full">
                                        <?php echo e($log->action); ?>

                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-600 hidden md:table-cell"><?php echo e(Str::limit($log->description, 50)); ?></td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">
                                    <code class="bg-gray-100 text-gray-900 text-xs px-2 py-1 rounded"><?php echo e($log->ip_address); ?></code>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-500">
                                    <?php echo e(\Carbon\Carbon::parse($log->timestamp)->diffForHumans()); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-3 sm:px-6 py-8 sm:py-12 text-center text-gray-500 text-xs sm:text-sm">
                                    <i class="fas fa-inbox text-3 sm:text-4xl mb-4 block opacity-30"></i>
                                    <p>Tidak ada aktivitas</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SYSTEM INFO -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
        <!-- Statistik Pengguna -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-chart-bar text-red-500"></i>
                Statistik Pengguna
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 text-xs sm:text-sm">
                    <span class="text-gray-600">Admin:</span>
                    <strong class="text-gray-900"><?php echo e(\App\Models\User::where('role', 'admin_elearning')->count()); ?></strong>
                </div>
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 text-xs sm:text-sm">
                    <span class="text-gray-600">Guru:</span>
                    <strong class="text-gray-900"><?php echo e(\App\Models\User::where('role', 'guru')->count()); ?></strong>
                </div>
                <div class="flex justify-between items-center text-xs sm:text-sm">
                    <span class="text-gray-600">Siswa:</span>
                    <strong class="text-gray-900"><?php echo e(\App\Models\User::where('role', 'siswa')->count()); ?></strong>
                </div>
            </div>
        </div>

        <!-- Statistik Pembelajaran -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-book-open text-green-500"></i>
                Statistik Pembelajaran
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 text-xs sm:text-sm">
                    <span class="text-gray-600">Kelas:</span>
                    <strong class="text-gray-900"><?php echo e(\App\Models\EClass::count()); ?></strong>
                </div>
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 text-xs sm:text-sm">
                    <span class="text-gray-600">Mata Pelajaran:</span>
                    <strong class="text-gray-900"><?php echo e(\App\Models\Subject::count()); ?></strong>
                </div>
                <div class="flex justify-between items-center text-xs sm:text-sm">
                    <span class="text-gray-600">Tugas:</span>
                    <strong class="text-gray-900"><?php echo e(\App\Models\Assignment::count()); ?></strong>
                </div>
            </div>
        </div>

        <!-- Informasi Sistem -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-clock text-blue-500"></i>
                Informasi Sistem
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 text-xs sm:text-sm">
                    <span class="text-gray-600">Versi:</span>
                    <strong class="text-gray-900">1.0.0</strong>
                </div>
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 text-xs sm:text-sm">
                    <span class="text-gray-600">Environment:</span>
                    <strong class="text-gray-900"><?php echo e(app()->environment()); ?></strong>
                </div>
                <div class="flex justify-between items-center text-xs sm:text-sm">
                    <span class="text-gray-600">Database:</span>
                    <strong class="text-gray-900">MySQL 8.0+</strong>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>