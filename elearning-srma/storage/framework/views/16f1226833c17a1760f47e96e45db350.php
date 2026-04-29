

<?php $__env->startSection('title', 'Kelola Materi Pembelajaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 mb-6">
        <i class="fas fa-book text-red-600"></i>
        <span class="text-gray-600">Admin</span>
        <span class="text-gray-400">/</span>
        <span class="font-semibold text-gray-800">Materi Pembelajaran</span>
    </div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-black text-gray-800 mb-2">Materi Pembelajaran</h1>
            <p class="text-gray-600">Kelola semua materi pembelajaran dari berbagai kelas</p>
        </div>
        <a href="<?php echo e(route('admin.materials.create')); ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold px-6 py-3 rounded-lg hover:from-red-700 hover:to-red-800 transition">
            <i class="fas fa-plus"></i> Tambah Materi
        </a>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-start gap-3">
            <i class="fas fa-check-circle text-green-600 mt-1"></i>
            <div>
                <p class="text-green-700 font-semibold">Berhasil!</p>
                <p class="text-green-600 text-sm"><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
            <div>
                <p class="text-red-700 font-semibold">Terjadi Kesalahan!</p>
                <p class="text-red-600 text-sm"><?php echo e(session('error')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-3 sm:p-6 mb-6">
        <form action="<?php echo e(route('admin.materials.index')); ?>" method="GET" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end flex-wrap">
            <div class="flex-1 min-w-xs">
                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-search text-red-600 mr-2"></i>Cari Materi
                </label>
                <input type="text" name="search" placeholder="Cari judul atau deskripsi..." 
                    value="<?php echo e(request('search')); ?>" 
                    class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:border-red-600 focus:outline-none">
            </div>
            <div class="w-full sm:w-48">
                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-filter text-red-600 mr-2"></i>Filter Kelas
                </label>
                <select name="class" class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:border-red-600 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>" <?php if(request('class') == $class->id): echo 'selected'; endif; ?>>
                            <?php echo e($class->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition justify-center whitespace-nowrap">
                <i class="fas fa-search"></i> Cari
            </button>

            <!-- Reset Button -->
            <a href="<?php echo e(route('admin.materials.index')); ?>" class="inline-flex items-center gap-2 bg-gray-200 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-300 transition justify-center whitespace-nowrap">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>

        <!-- Info Text -->
        <p class="text-gray-500 text-xs sm:text-sm mt-4">
            Menampilkan <strong><?php echo e($materials->count()); ?></strong> dari <strong><?php echo e($materials->total()); ?></strong> materi
        </p>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <?php if($materials->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-12">#</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Judul Materi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Uploader</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600 font-semibold"><?php echo e($loop->iteration); ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800"><?php echo e($material->title); ?></div>
                                    <div class="text-xs text-gray-500 mt-1"><?php echo e(Str::limit($material->description, 50) ?? 'No description'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                        <?php echo e($material->eClass->name); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo e($material->uploadedBy->name ?? 'Admin'); ?>

                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo e($material->created_at->format('d M Y H:i')); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo e(route('admin.materials.show', $material)); ?>" 
                                            class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-3 py-1 rounded-lg hover:bg-blue-200 transition text-sm font-semibold" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.materials.edit', $material)); ?>" 
                                            class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg hover:bg-yellow-200 transition text-sm font-semibold" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.materials.download', $material)); ?>" 
                                            class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1 rounded-lg hover:bg-green-200 transition text-sm font-semibold" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.materials.destroy', $material)); ?>" 
                                            method="POST" class="inline delete-form" >
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="button" onclick="confirmDelete(event, '<?php echo e($material->title); ?>')" class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-lg hover:bg-red-200 transition text-sm font-semibold" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-gray-400" style="font-size: 2rem;"></i>
                                    <p class="text-gray-500 mt-3">Tidak ada materi</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo e($materials->appends(request()->query())->links('pagination::tailwind')); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-400" style="font-size: 3rem;"></i>
                <p class="text-gray-600 mt-4">Belum ada materi. <a href="<?php echo e(route('admin.materials.create')); ?>" class="text-red-600 font-semibold hover:underline">Tambah materi sekarang</a></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Statistics Card -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Statistik Materi</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow-md p-6">
                <div class="text-4xl font-bold mb-2"><?php echo e($statistics['total'] ?? 0); ?></div>
                <p class="text-red-100">Total Materi</p>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-md p-6">
                <div class="text-4xl font-bold mb-2"><?php echo e($statistics['this_month'] ?? 0); ?></div>
                <p class="text-green-100">Bulan Ini</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-md p-6">
                <div class="text-4xl font-bold mb-2"><?php echo e($statistics['by_class'] ?? 0); ?></div>
                <p class="text-yellow-100">Total Kelas</p>
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
    showConfirmation(`Yakin ingin menghapus "${name}"?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/materials/index.blade.php ENDPATH**/ ?>