

<?php $__env->startSection('title', 'Detail Materi'); ?>
<?php $__env->startSection('icon', 'fas fa-book'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Guru / Materi / Detail</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><?php echo e($material->title); ?></h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700"><?php echo e($class->name); ?></strong>
        </span>
    </div>
    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
        <a href="<?php echo e(route('guru.materials.edit', $material)); ?>"
           class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all">
            <i class="fas fa-pen text-xs"></i> Edit
        </a>
        <a href="<?php echo e(route('guru.materials.index', ['class_id' => $class->id])); ?>"
           class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Main Info -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Informasi Materi</h2>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">v<?php echo e($material->version); ?></span>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi</p>
                <?php if($material->description): ?>
                    <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($material->description); ?></p>
                <?php else: ?>
                    <p class="text-sm text-gray-400 italic">Tidak ada deskripsi.</p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                <?php
                    $ext = strtoupper($material->file_type ?? '-');
                    $iconColors = ['PDF' => 'bg-red-50 text-red-600 border-red-100', 'DOC' => 'bg-blue-50 text-blue-600 border-blue-100', 'DOCX' => 'bg-blue-50 text-blue-600 border-blue-100', 'PPT' => 'bg-orange-50 text-orange-600 border-orange-100', 'PPTX' => 'bg-orange-50 text-orange-600 border-orange-100', 'XLS' => 'bg-green-50 text-green-600 border-green-100', 'XLSX' => 'bg-green-50 text-green-600 border-green-100'];
                    $extColor = $iconColors[$ext] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                ?>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Tipe File</p>
                    <span class="inline-block text-xs font-bold px-2 py-0.5 rounded-lg border <?php echo e($extColor); ?>"><?php echo e($ext); ?></span>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Versi</p>
                    <p class="text-sm font-bold text-gray-900">v<?php echo e($material->version); ?></p>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Dibuat</p>
                    <p class="text-sm font-bold text-gray-900"><?php echo e($material->created_at?->format('d M Y')); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($material->created_at?->format('H:i')); ?></p>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Diupdate</p>
                    <p class="text-sm font-bold text-gray-900"><?php echo e($material->updated_at?->format('d M Y')); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($material->updated_at?->format('H:i')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden self-start">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">File Materi</h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Nama File</p>
                <p class="text-sm font-semibold text-gray-900 break-all"><?php echo e(basename($material->file_path)); ?></p>
                <a href="<?php echo e(asset($material->file_path)); ?>" target="_blank"
                   class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                    <i class="fas fa-download"></i> Download / Buka File
                </a>
                <?php if(in_array($ext, ['PDF', 'JPG', 'JPEG', 'PNG', 'GIF'])): ?>
                    <a href="<?php echo e(route('guru.materials.preview', $material)); ?>" target="_blank"
                       class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        <i class="fas fa-eye"></i> Preview
                    </a>
                <?php endif; ?>
            </div>

            <form method="POST" action="<?php echo e(route('guru.materials.destroy', $material)); ?>" class="delete-form">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="button" onclick="confirmDelete(event, '<?php echo e($material->title); ?>')"
                        class="w-full inline-flex items-center justify-center gap-2 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white border border-red-200 py-2.5 rounded-xl transition-all">
                    <i class="fas fa-trash text-xs"></i> Hapus Materi
                </button>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Apakah Anda yakin ingin menghapus materi "${name}"?`, 'Konfirmasi Penghapusan', () => form.submit());
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/guru/materials/show.blade.php ENDPATH**/ ?>