

<?php $__env->startSection('title', 'Kelola Mata Pelajaran'); ?>
<?php $__env->startSection('icon', 'book'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-8 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-book text-red-500"></i>
                Manajemen Mata Pelajaran
            </h1>
            <p class="text-gray-500 text-xs sm:text-sm">Kelola semua mata pelajaran di sekolah</p>
        </div>
        <a href="<?php echo e(route('admin.subjects.create')); ?>" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
            <i class="fas fa-plus"></i> <span class="hidden sm:inline">Tambah Mata Pelajaran</span>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm p-3 sm:p-6 mb-6">
        <form method="GET" action="<?php echo e(route('admin.subjects.index')); ?>" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end">
            <!-- Search Input -->
            <div class="flex-1 min-w-xs">
                <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-search"></i> Cari Mata Pelajaran
                </label>
                <input type="text" name="search" placeholder="Cari nama, kode, atau deskripsi..." 
                       value="<?php echo e(request('search')); ?>"
                       class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition">
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center justify-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
                <i class="fas fa-search"></i> <span class="hidden sm:inline">Cari</span>
            </button>

            <!-- Reset Button -->
            <a href="<?php echo e(route('admin.subjects.index')); ?>" class="inline-flex items-center justify-center gap-2 bg-gray-300 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-400 transition whitespace-nowrap">
                <i class="fas fa-redo"></i> <span class="hidden sm:inline">Reset</span>
            </a>
        </form>

        <!-- Info Text -->
        <small class="text-gray-500 mt-4 block text-xs sm:text-sm">
            Menampilkan <?php echo e($subjects->count()); ?> dari <?php echo e($subjects->total()); ?> mata pelajaran
        </small>
    </div>

    <!-- Subjects Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <?php if($subjects->isEmpty()): ?>
            <div class="text-center py-12 sm:py-16 px-3 sm:px-6">
                <i class="fas fa-inbox text-5xl sm:text-6xl text-gray-300 mb-4 block"></i>
                <h3 class="text-gray-600 font-semibold mb-2 text-sm sm:text-base">Tidak ada mata pelajaran ditemukan</h3>
                <p class="text-gray-500 mb-6 text-xs sm:text-sm">
                    <?php if(request('search')): ?>
                        Coba ubah pencarian Anda atau <a href="<?php echo e(route('admin.subjects.index')); ?>" class="text-red-500 font-semibold hover:underline">reset filter</a>
                    <?php else: ?>
                        Mulai dengan <a href="<?php echo e(route('admin.subjects.create')); ?>" class="text-red-500 font-semibold hover:underline">membuat mata pelajaran baru</a>
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Nama Mata Pelajaran</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Kode</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900 hidden sm:table-cell">Deskripsi</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <span class="font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-book text-red-500 flex-shrink-0"></i> <span class="truncate"><?php echo e($subject->name); ?></span>
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <code class="bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold px-2 sm:px-3 py-1 rounded whitespace-nowrap">
                                        <?php echo e($subject->code); ?>

                                    </code>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-600 hidden sm:table-cell">
                                    <?php echo e(Str::limit($subject->description ?? '-', 60)); ?>

                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <div class="flex gap-1 sm:gap-2 flex-col sm:flex-row">
                                        <a href="<?php echo e(route('admin.subjects.edit', $subject)); ?>" class="inline-flex items-center justify-center gap-2 bg-blue-500 text-white px-2 sm:px-4 py-1.5 sm:py-2 rounded text-xs font-semibold hover:bg-blue-600 transition whitespace-nowrap">
                                            <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.subjects.destroy', $subject)); ?>" class="inline w-full sm:w-auto delete-form">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="button" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-500 text-white px-2 sm:px-4 py-1.5 sm:py-2 rounded text-xs font-semibold hover:bg-red-600 transition whitespace-nowrap" onclick="confirmDelete(event, '<?php echo e($subject->name); ?>')">
                                                <i class="fas fa-trash"></i> <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($subjects->hasPages()): ?>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-4 py-6 sm:py-8 px-3 sm:px-6 border-t border-gray-200">
                    <!-- Previous Button -->
                    <?php if($subjects->onFirstPage()): ?>
                        <button disabled class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-400 px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e($subjects->previousPageUrl()); ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-300 text-gray-900 px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-400 transition">
                            <i class="fas fa-chevron-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                        </a>
                    <?php endif; ?>

                    <!-- Page Info -->
                    <div class="px-3 sm:px-4 py-2 bg-gray-100 rounded-lg text-xs sm:text-sm text-gray-700 font-semibold whitespace-nowrap">
                        Hal. <?php echo e($subjects->currentPage()); ?>/<?php echo e($subjects->lastPage()); ?>

                    </div>

                    <!-- Next Button -->
                    <?php if($subjects->hasMorePages()): ?>
                        <a href="<?php echo e($subjects->nextPageUrl()); ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-500 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition">
                            <span class="hidden sm:inline">Selanjutnya</span> <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <button disabled class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-400 px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm cursor-not-allowed">
                            <span class="hidden sm:inline">Selanjutnya</span> <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(
        `Apakah Anda yakin ingin menghapus mata pelajaran "${name}"? Aksi ini tidak dapat diubah.`,
        'Konfirmasi Penghapusan',
        function() {
            form.submit();
        }
    );
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/subjects/index.blade.php ENDPATH**/ ?>