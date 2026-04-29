

<?php $__env->startSection('title', 'Tambah Pengguna'); ?>
<?php $__env->startSection('icon', 'user-plus'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <div class="mb-8">
        <p class="text-gray-500 text-xs sm:text-sm mb-2">Tambah Data</p>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-user-plus text-red-500"></i>
            Tambah Pengguna Baru
        </h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-bold text-gray-900">Formulir Pendaftaran Pengguna</h2>
            </div>
            <div class="p-3 sm:p-6">
                <form method="POST" action="<?php echo e(route('admin.users.store')); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            placeholder="Masukkan nama lengkap"
                            class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('name')); ?>" 
                            required
                        >
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-2 block">❌ <?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            placeholder="nama@sekolah.sch.id"
                            class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('email')); ?>" 
                            required
                        >
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-2 block">❌ <?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="Masukkan password (minimal 6 karakter)"
                            class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-2 block">❌ <?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Role / Peran <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="role" 
                            id="role" 
                            class="w-full px-3 sm:px-4 py-2 border-2 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                            <option value="">-- Pilih Role --</option>
                            <option value="admin_elearning" <?php if(old('role') === 'admin_elearning'): echo 'selected'; endif; ?>>Admin E-Learning</option>
                            <option value="guru" <?php if(old('role') === 'guru'): echo 'selected'; endif; ?>>Guru</option>
                            <option value="siswa" <?php if(old('role') === 'siswa'): echo 'selected'; endif; ?>>Siswa</option>
                        </select>
                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-2 block">❌ <?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition"
                        >
                            <i class="fas fa-save"></i> <span class="hidden sm:inline">Simpan Pengguna</span><span class="sm:hidden">Simpan</span>
                        </button>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-300 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-400 transition">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/users/create.blade.php ENDPATH**/ ?>