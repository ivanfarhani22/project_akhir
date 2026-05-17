

<?php $__env->startSection('title', 'Edit Materi'); ?>
<?php $__env->startSection('icon', 'fas fa-edit'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-edit mr-1"></i> Guru / Materi / Edit</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><?php echo e($material->title); ?></h1>
    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
        <i class="fas fa-sync-alt"></i> Update versi materi
    </span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Edit Materi Pembelajaran</h2>
        </div>
        <div class="p-6">

            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <p class="text-sm text-blue-800">Upload file baru akan otomatis menaikkan versi: <strong>v<?php echo e($material->version); ?> → v<?php echo e($material->version + 1); ?></strong></p>
            </div>

            <form method="POST" action="<?php echo e(route('guru.materials.update', $material)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                <div class="mb-5">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Materi <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                        value="<?php echo e(old('title', $material->title)); ?>" required>
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
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none"><?php echo e(old('description', $material->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Saat Ini</label>
                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-4">
                        <div class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-xs font-bold text-gray-500">
                            <?php echo e(strtoupper($material->file_type)); ?>

                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800"><?php echo e(strtoupper($material->file_type)); ?> — v<?php echo e($material->version); ?></p>
                            <p class="text-xs text-gray-400">Dibuat <?php echo e($material->created_at->format('d M Y')); ?></p>
                        </div>
                    </div>

                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload File Baru <span class="text-gray-400 font-normal">(Opsional)</span></label>
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

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Tampilan (Opsional)</label>
                    <input type="text" name="display_name" value="<?php echo e(old('display_name', $material->display_name)); ?>"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Contoh: Materi Bab 1 - PDF">
                    <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-gray-400 mt-1">Rename ini hanya mengubah nama yang ditampilkan/filename saat download.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo e(route('guru.materials.index', ['class_id' => request('class_id') ?? $material->class_id ?? optional($material->classSubject)->e_class_id ?? null])); ?>"
                       class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Update Materi
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
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/guru/materials/edit.blade.php ENDPATH**/ ?>