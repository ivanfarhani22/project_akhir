
<?php $__env->startSection('title', 'Tugas'); ?>
<?php $__env->startSection('icon', 'fas fa-tasks'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-tasks mr-1"></i> Guru / Tugas</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-tasks text-[#A41E35] mr-2"></i>Daftar Tugas</h1>
    </div>
    <a href="<?php echo e(route('guru.assignments.create')); ?>"
       class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition whitespace-nowrap">
        <i class="fas fa-plus text-xs"></i> Buat Tugas
    </a>
</div>

<?php if($classes->count() > 0): ?>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Filter Kelas</h2>
        </div>
        <div class="p-4 flex flex-wrap gap-2">
            <a href="<?php echo e(route('guru.assignments.index')); ?>"
               class="inline-flex items-center gap-1.5 bg-[#A41E35] text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                <i class="fas fa-list text-[10px]"></i> Semua
            </a>
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('guru.assignments.index', ['class_id' => $class->id])); ?>"
                   class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg transition">
                    <?php echo e($class->name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Semua Tugas</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full"><?php echo e($assignments->count()); ?> Tugas</span>
    </div>

    <?php if($assignments->isEmpty()): ?>
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-tasks text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm mb-4">Belum ada tugas.</p>
            <a href="<?php echo e(route('guru.assignments.create')); ?>"
               class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                <i class="fas fa-plus text-xs"></i> Buat Tugas Pertama
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul Tugas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengumpulan</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $passed = $assignment->deadline < now(); ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 font-semibold text-gray-800"><?php echo e($assignment->eClass->name); ?></td>
                            <td class="px-5 py-3.5 text-gray-700"><?php echo e(Str::limit($assignment->title, 40)); ?></td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($passed ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200'); ?>">
                                    <i class="fas <?php echo e($passed ? 'fa-times-circle' : 'fa-clock'); ?> text-[10px]"></i>
                                    <?php echo e($assignment->deadline->format('d M Y H:i')); ?>

                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200">
                                    <i class="fas fa-file-upload text-[10px]"></i>
                                    <?php echo e($assignment->submissions->count()); ?>/<?php echo e($assignment->eClass->students->count()); ?>

                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex justify-center gap-2">
                                    <a href="<?php echo e(route('guru.assignments.edit', $assignment)); ?>"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('guru.assignments.destroy', $assignment)); ?>" class="delete-form">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="button" onclick="confirmDelete(event, '<?php echo e($assignment->title); ?>')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 rounded-lg text-xs transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Apakah Anda yakin ingin menghapus tugas "${name}"?`, 'Konfirmasi Penghapusan', () => form.submit());
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/guru/assignments/index-all.blade.php ENDPATH**/ ?>