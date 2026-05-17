

<?php $__env->startSection('title', 'Buat Kelas Baru'); ?>
<?php $__env->startSection('icon', 'fas fa-plus-circle'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-xs sm:text-sm text-gray-600">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="<?php echo e(route('admin.classes.index')); ?>" class="hover:text-red-600 transition">Kelas</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Buat Kelas</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
            <span class="w-9 sm:w-10 h-9 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-sm sm:text-base">
                <i class="fas fa-plus-circle"></i>
            </span>
            Buat Kelas Baru
        </h1>
        <p class="text-gray-600 mt-2 text-xs sm:text-sm">Buat kelas baru dan atur informasi dasar</p>
    </div>

    <!-- Error Messages -->
    <?php if($errors->any()): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-3 sm:p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs sm:text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                    <div class="mt-2 text-xs sm:text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-3 sm:py-4">
            <h2 class="text-white font-bold text-base sm:text-lg">Form Buat Kelas</h2>
        </div>

        <form method="POST" action="<?php echo e(route('admin.classes.store')); ?>" class="p-3 sm:p-6 space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Nama Kelas -->
            <div>
                <label for="name" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                    Nama Kelas <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="<?php echo e(old('name')); ?>" 
                    placeholder="Misal: Kelas X-A, XI IPA-1, XII IPS-2"
                    class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    required
                >
                <p class="text-gray-500 text-xs mt-2">Format jelas seperti X-A, XI-IPA-1, XII-IPS-2</p>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                    Deskripsi <span class="text-gray-500 text-xs">(Opsional)</span>
                </label>
                <textarea 
                    name="description" 
                    id="description"
                    rows="4"
                    placeholder="Deskripsi singkat tentang kelas ini..."
                    class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                ><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t-2 border-gray-200 mt-6">
                <a href="<?php echo e(route('admin.classes.index')); ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white border-2 border-gray-300 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-50 transition">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="w-full sm:w-auto sm:ml-auto inline-flex items-center justify-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition">
                    <i class="fas fa-save"></i> <span class="hidden sm:inline">Buat Kelas</span><span class="sm:hidden">Buat</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/classes/create.blade.php ENDPATH**/ ?>