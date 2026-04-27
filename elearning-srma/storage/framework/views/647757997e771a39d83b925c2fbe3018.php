

<?php $__env->startSection('title', 'Upload Materi Baru'); ?>
<?php $__env->startSection('icon', 'fas fa-book'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Guru / Materi / Upload</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-cloud-upload-alt text-[#A41E35] mr-2"></i>Tambah Materi</h1>
    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
        <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700"><?php echo e($class->name); ?></strong>
        <?php if(isset($classSubject)): ?>
            <span class="mx-1 text-gray-300">•</span> <?php echo e($classSubject->subject->name); ?>

        <?php endif; ?>
    </span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Formulir Upload Materi</h2>
        </div>
        <div class="p-6">
            <form method="POST"
                  action="<?php echo e(isset($classSubject) ? route('guru.class-subjects.materials.store', $classSubject) : route('guru.materials.store')); ?>"
                  enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="e_class_id" value="<?php echo e($class->id); ?>">
                <?php if(isset($classSubject)): ?>
                    <input type="hidden" name="class_subject_id" value="<?php echo e($classSubject->id); ?>">
                <?php endif; ?>

                <div class="mb-5">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Materi <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" placeholder="Contoh: Bab 1 - Pengenalan Sistem"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                        value="<?php echo e(old('title')); ?>" required>
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-5">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" placeholder="Jelaskan materi ini untuk siswa..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none"><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- File Upload -->
                <div>
                    <label for="file" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-file text-red-600 mr-2"></i>Upload File Materi
                    </label>
                    <div class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg focus-within:border-red-600 transition-colors relative">
                        <input type="file" name="file" id="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                            accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.mkv">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-lg sm:text-xl mr-2"></i>
                            <span class="text-gray-500 text-xs sm:text-sm" id="file-name">Pilih file atau drag & drop</span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-xs mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, PNG, MP4, MKV (Maks. <?php echo e((int) ceil(config('upload.material_max_kb') / 1024)); ?>MB)
                    </p>
                    <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo e(url()->previous() ?? (isset($classSubject) ? route('guru.class-subjects.materials.index', $classSubject) : route('guru.materials.index'))); ?>"
                       class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Simpan Materi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const fileInput = document.getElementById('file');
        const fileName = document.getElementById('file-name');
        if (!fileInput) return;

        const MAX_BYTES = <?php echo e((int) config('upload.material_max_kb')); ?> * 1024;

        fileInput.addEventListener('change', function () {
            const f = this.files && this.files[0];
            if (!f) {
                if (fileName) fileName.textContent = 'Pilih file atau drag & drop';
                return;
            }

            if (f.size > MAX_BYTES) {
                alert('Ukuran file terlalu besar. Maksimal <?php echo e((int) ceil(config('upload.material_max_kb') / 1024)); ?> MB.');
                this.value = '';
                if (fileName) fileName.textContent = 'Pilih file atau drag & drop';
                return;
            }

            if (fileName) fileName.textContent = f.name;
        });
    })();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/guru/materials/create.blade.php ENDPATH**/ ?>