

<?php $__env->startSection('title', 'Kelola Pengguna'); ?>
<?php $__env->startSection('icon', 'users'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-users text-red-500"></i>
                Manajemen Pengguna
            </h1>
            <p class="text-gray-500 text-xs sm:text-sm">Kelola guru, siswa, dan admin elearning</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <button type="button" onclick="openImportUsersModal()" class="inline-flex items-center gap-2 bg-blue-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-blue-600 transition whitespace-nowrap">
                <i class="fas fa-file-import"></i> Import
            </button>
            <a href="<?php echo e(route('admin.users.create')); ?>" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
                <i class="fas fa-plus"></i> Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importUsersModal" class="fixed inset-0 z-[9998] hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/40" onclick="closeImportUsersModal()"></div>
        <div class="relative mx-auto mt-10 sm:mt-16 w-[92%] max-w-xl">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 text-sm sm:text-base">
                        <i class="fas fa-file-import text-blue-600 mr-2"></i> Import Pengguna (CSV/Excel)
                    </h3>
                    <button type="button" onclick="closeImportUsersModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-4 sm:p-6">
                    <p class="text-xs sm:text-sm text-gray-600 mb-4">
                        Upload file <b>.csv</b> / <b>.xlsx</b> sesuai template agar kolomnya valid.
                    </p>

                    <?php if(session('import_failures')): ?>
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-xs sm:text-sm font-semibold text-red-700 mb-2">Detail baris gagal:</p>
                            <div class="max-h-40 overflow-auto text-xs text-red-800 space-y-1">
                                <?php $__currentLoopData = session('import_failures'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $failure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border-b border-red-100 pb-1">
                                        Baris <b><?php echo e($failure->row()); ?></b>:
                                        <?php echo e(implode(' | ', $failure->errors())); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('admin.users.import')); ?>" enctype="multipart/form-data" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">File Import</label>
                            <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                                   class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-blue-500 transition <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-2 block">❌ <?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-[11px] text-gray-500 mt-2">Maksimal 10MB.</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
                            <a href="<?php echo e(route('admin.users.import.template')); ?>" class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-800 px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-200 transition">
                                <i class="fas fa-download"></i> Download Template
                            </a>

                            <div class="flex gap-2">
                                <button type="button" onclick="closeImportUsersModal()" class="inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-900 px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-300 transition">
                                    Batal
                                </button>
                                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-blue-700 transition">
                                    <i class="fas fa-upload"></i> Import
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-6 mb-6">
        <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end flex-wrap">
            <!-- Search Input -->
            <div class="flex-1 min-w-xs">
                <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-search"></i> Cari Pengguna
                </label>
                <input type="text" name="search" placeholder="Cari nama atau email..." 
                       value="<?php echo e(request('search')); ?>"
                       class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition">
            </div>

            <!-- Role Filter -->
            <div class="w-full sm:w-48">
                <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-filter"></i> Role
                </label>
                <select name="role" class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition">
                    <option value="">-- Semua Role --</option>
                    <option value="admin_elearning" <?php echo e(request('role') === 'admin_elearning' ? 'selected' : ''); ?>>Admin</option>
                    <option value="guru" <?php echo e(request('role') === 'guru' ? 'selected' : ''); ?>>Guru</option>
                    <option value="siswa" <?php echo e(request('role') === 'siswa' ? 'selected' : ''); ?>>Siswa</option>
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition justify-center whitespace-nowrap">
                <i class="fas fa-search"></i> Cari
            </button>

            <!-- Reset Button -->
            <a href="<?php echo e(route('admin.users.index')); ?>" class="inline-flex items-center gap-2 bg-gray-200 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-300 transition justify-center whitespace-nowrap">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>

        <!-- Info Text -->
        <p class="text-gray-500 text-xs sm:text-sm mt-4">
            Menampilkan <strong><?php echo e($users->count()); ?></strong> dari <strong><?php echo e($users->total()); ?></strong> pengguna
        </p>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <?php if($users->isEmpty()): ?>
            <div class="text-center py-8 sm:py-16 px-4 sm:px-6">
                <i class="fas fa-inbox text-5xl sm:text-6xl text-gray-300 mb-4 block"></i>
                <h3 class="text-gray-600 font-semibold mb-2 text-sm sm:text-base">Tidak ada pengguna ditemukan</h3>
                <p class="text-gray-500 mb-6">
                    <?php if(request('search') || request('role')): ?>
                        Coba ubah pencarian atau filter Anda
                    <?php else: ?>
                        Mulai dengan <a href="<?php echo e(route('admin.users.create')); ?>" class="text-red-500 font-semibold hover:underline">membuat pengguna baru</a>
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b-2 border-gray-200 text-xs sm:text-sm">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Nama Pengguna</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900 hidden sm:table-cell">Email</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Role</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition cursor-pointer group" onclick="window.location.href='<?php echo e(route('admin.users.show', $user)); ?>'">
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-red-500 to-red-700 text-white flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">
                                            <?php echo e(substr($user->name, 0, 1)); ?>

                                        </div>
                                        <span class="font-semibold text-gray-900 text-xs sm:text-base group-hover:text-red-600 transition"><?php echo e($user->name); ?></span>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 hidden sm:table-cell">
                                    <code class="bg-gray-100 text-gray-900 text-xs px-2 py-1 rounded group-hover:bg-red-50 group-hover:text-red-600 transition">
                                        <?php echo e($user->email); ?>

                                    </code>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <?php if($user->role === 'admin_elearning'): ?>
                                        <span class="inline-flex items-center gap-1 sm:gap-2 bg-red-100 text-red-800 px-2 sm:px-3 py-1 rounded-full text-xs font-semibold group-hover:bg-red-200 transition">
                                            <i class="fas fa-shield-alt hidden sm:inline"></i> Admin
                                        </span>
                                    <?php elseif($user->role === 'guru'): ?>
                                        <span class="inline-flex items-center gap-1 sm:gap-2 bg-green-100 text-green-800 px-2 sm:px-3 py-1 rounded-full text-xs font-semibold group-hover:bg-green-200 transition">
                                            <i class="fas fa-chalkboard-user hidden sm:inline"></i> Guru
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 sm:gap-2 bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-full text-xs font-semibold group-hover:bg-blue-200 transition">
                                            <i class="fas fa-user-graduate hidden sm:inline"></i> Siswa
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4" onclick="event.stopPropagation()">
                                    <div class="flex gap-1 sm:gap-2 flex-col sm:flex-row">
                                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="inline-flex items-center gap-1 sm:gap-2 bg-blue-500 text-white px-2 sm:px-4 py-1.5 sm:py-2 rounded text-xs font-semibold hover:bg-blue-600 transition justify-center">
                                            <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" class="inline delete-form">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="button" onclick="confirmDelete(event, '<?php echo e($user->name); ?>')" class="inline-flex items-center gap-1 sm:gap-2 bg-red-500 text-white px-2 sm:px-4 py-1.5 sm:py-2 rounded text-xs font-semibold hover:bg-red-600 transition w-full sm:w-auto justify-center">
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
            <?php if($users->hasPages()): ?>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-4 py-6 sm:py-8 px-3 sm:px-6 border-t border-gray-200 text-xs sm:text-sm">
                    <!-- Previous Button -->
                    <?php if($users->onFirstPage()): ?>
                        <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-400 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm cursor-not-allowed w-full sm:w-auto justify-center">
                            <i class="fas fa-chevron-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e($users->previousPageUrl()); ?>" class="inline-flex items-center gap-2 bg-gray-300 text-gray-900 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-400 transition w-full sm:w-auto justify-center">
                            <i class="fas fa-chevron-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                        </a>
                    <?php endif; ?>

                    <!-- Page Info -->
                    <div class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-100 rounded-lg text-xs sm:text-sm text-gray-700 font-semibold whitespace-nowrap">
                        Halaman <?php echo e($users->currentPage()); ?> dari <?php echo e($users->lastPage()); ?>

                    </div>

                    <!-- Next Button -->
                    <?php if($users->hasMorePages()): ?>
                        <a href="<?php echo e($users->nextPageUrl()); ?>" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition w-full sm:w-auto justify-center">
                            <span class="hidden sm:inline">Selanjutnya</span> <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-400 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm cursor-not-allowed w-full sm:w-auto justify-center">
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
function openImportUsersModal() {
    const el = document.getElementById('importUsersModal');
    if (el) el.classList.remove('hidden');
}

function closeImportUsersModal() {
    const el = document.getElementById('importUsersModal');
    if (el) el.classList.add('hidden');
}

// Auto-open modal if there are validation errors for import or failure details
document.addEventListener('DOMContentLoaded', function () {
    const hasImportError = <?php echo $errors->has('file') ? 'true' : 'false'; ?>;
    const hasFailures = <?php echo session()->has('import_failures') ? 'true' : 'false'; ?>;
    if (hasImportError || hasFailures) {
        openImportUsersModal();
    }
});

function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Yakin ingin menghapus pengguna "${name}"?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/users/index.blade.php ENDPATH**/ ?>