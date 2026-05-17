

<?php $__env->startSection('title', 'Tambah Mata Pelajaran'); ?>
<?php $__env->startSection('icon', 'fas fa-book'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 mb-6 text-sm text-gray-600">
            <a href="<?php echo e(route('admin.classes.index')); ?>" class="text-red-600 hover:text-red-700 font-medium">
                <i class="fas fa-chalkboard"></i> Kelola Kelas
            </a>
            <span>/</span>
            <a href="<?php echo e(route('admin.classes.show', $class)); ?>" class="text-red-600 hover:text-red-700 font-medium">
                <?php echo e($class->name); ?>

            </a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Tambah Mata Pelajaran</span>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-book"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Mata Pelajaran</h1>
            </div>
            <p class="text-gray-600">Tambahkan mata pelajaran baru ke kelas <?php echo e($class->name); ?></p>
        </div>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
                    <div>
                        <p class="font-semibold text-red-900">Terjadi kesalahan:</p>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p class="text-red-700 text-sm mt-1"><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                    <i class="fas fa-pen-to-square"></i>
                    Form Tambah Mata Pelajaran
                </h2>
            </div>

            <!-- Card Body -->
            <div class="p-6 space-y-6">
                <form method="POST" action="<?php echo e(route('admin.class-subjects.store', $class)); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <!-- Mata Pelajaran (dengan Search) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Pilih Mata Pelajaran
                            <span class="text-red-600">*</span>
                        </label>
                        <select 
                            name="subject_id" 
                            id="subjectSelect" 
                            required
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-red-500 transition"
                        >
                            <option value="">-- Cari & Pilih Mata Pelajaran --</option>
                            <?php $__currentLoopData = $availableSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?> (<?php echo e($subject->code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($availableSubjects->isEmpty()): ?>
                            <p class="text-xs text-red-600 mt-2">
                                ⚠️ Semua mata pelajaran sudah ditambahkan ke kelas ini atau belum ada mata pelajaran.
                            </p>
                        <?php else: ?>
                            <p class="text-xs text-gray-500 mt-2">Hanya menampilkan mata pelajaran yang belum ditambahkan ke kelas ini</p>
                        <?php endif; ?>
                    </div>

                    <!-- Guru Pengajar (dengan Search Select2) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Guru Pengajar
                            <span class="text-red-600">*</span>
                        </label>
                        <select 
                            name="teacher_id" 
                            id="teacherSelect" 
                            required
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-red-500 transition"
                        >
                            <option value="">-- Cari & Pilih Guru --</option>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($teacher->id); ?>" <?php echo e(old('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                    <?php echo e($teacher->name); ?> (<?php echo e($teacher->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($teachers->isEmpty()): ?>
                            <p class="text-xs text-red-600 mt-2">
                                ⚠️ Tidak ada guru tersedia.
                            </p>
                        <?php else: ?>
                            <p class="text-xs text-gray-500 mt-2">Guru yang akan mengajar mata pelajaran ini</p>
                        <?php endif; ?>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea 
                            name="description" 
                            rows="3"
                            placeholder="Deskripsi singkat tentang mata pelajaran ini di kelas..."
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-red-500 transition resize-none"
                        ><?php echo e(old('description')); ?></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 justify-end pt-6 border-t border-gray-200">
                        <a 
                            href="<?php echo e(route('admin.classes.show', $class)); ?>" 
                            class="inline-flex items-center gap-2 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition"
                        >
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg font-semibold text-sm hover:bg-red-600 transition"
                        >
                            <i class="fas fa-plus"></i> Tambah Mata Pelajaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CDN Select2 & Styles -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for Subject
            $('#subjectSelect').select2({
                placeholder: 'Cari & Pilih Mata Pelajaran',
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for Teacher
            $('#teacherSelect').select2({
                placeholder: 'Cari & Pilih Guru',
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <style>
        .select2-container--default .select2-selection--single {
            border: 2px solid #d1d5db !important;
            border-radius: 0.5rem;
            padding: 8px 12px !important;
            font-size: 14px;
            height: auto !important;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .select2-dropdown {
            border: 2px solid #d1d5db !important;
            border-radius: 0.5rem;
        }

        .select2-results__option--highlighted {
            background-color: #ef4444 !important;
        }
    </style>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/admin/class-subjects/create.blade.php ENDPATH**/ ?>