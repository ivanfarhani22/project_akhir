
<?php $__env->startSection('title', 'Detail Tugas'); ?>
<?php $__env->startSection('icon', 'fas fa-eye'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $submittedCount = $assignment->submissions->whereNotNull('submitted_at')->count();
    $totalStudents = $class->students->count();
    $pct = $totalStudents > 0 ? round(($submittedCount/$totalStudents)*100) : 0;
    $downloadUrl = $assignment->file_path ? (str_starts_with($assignment->file_path,'storage/') ? asset($assignment->file_path) : asset('storage/'.ltrim($assignment->file_path,'/'))) : null;
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-tasks mr-1"></i> Guru / Tugas / Detail</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><?php echo e($assignment->title); ?></h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700"><?php echo e($class->name); ?></strong>
        </span>
    </div>
    <div class="flex gap-2">
        <a href="<?php echo e(route('guru.assignments.edit', $assignment)); ?>"
           class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
            <i class="fas fa-pen text-xs"></i> Edit
        </a>
        <a href="<?php echo e(route('guru.assignments.index', ['class_id' => request('class_id')])); ?>"
           class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900"><i class="fas fa-info-circle mr-2 text-gray-400"></i>Informasi Tugas</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center gap-4">
                    <span class="text-sm text-gray-500 font-semibold">Deadline</span>
                    <span class="text-sm font-bold text-gray-900"><?php echo e($assignment->deadline?->format('d M Y H:i') ?? '-'); ?></span>
                </div>
                <?php if($downloadUrl): ?>
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Lampiran Soal</p>
                        <a href="<?php echo e($downloadUrl); ?>" target="_blank"
                           class="inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 text-sm font-semibold px-4 py-2 rounded-xl transition">
                            <i class="fas fa-download text-xs"></i> Download File
                        </a>
                    </div>
                <?php endif; ?>
                <?php if($assignment->description): ?>
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line"><?php echo e($assignment->description); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900"><i class="fas fa-file-upload mr-2 text-gray-400"></i>Pengumpulan Siswa</h2>
                <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full"><?php echo e($assignment->submissions->count()); ?></span>
            </div>

            <?php if($assignment->submissions->isEmpty()): ?>
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <i class="fas fa-inbox text-gray-200 text-4xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Belum ada data submission.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Submit</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">File</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $assignment->submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-3.5 font-semibold text-gray-800"><?php echo e($submission->student?->name ?? '-'); ?></td>
                                    <td class="px-5 py-3.5">
                                        <?php if($submission->submitted_at): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fas fa-check-circle text-[10px]"></i> Submitted
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                <i class="fas fa-clock text-[10px]"></i> Belum
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-500 text-xs"><?php echo e($submission->submitted_at?->format('d M Y H:i') ?? '—'); ?></td>
                                    <td class="px-5 py-3.5">
                                        <?php if($submission->file_path): ?>
                                            <a href="<?php echo e(route('guru.submissions.download', $submission)); ?>"
                                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-300">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden self-start">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900"><i class="fas fa-chart-pie mr-2 text-gray-400"></i>Ringkasan</h2>
        </div>
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-3">
                <div class="text-center bg-gray-50 border border-gray-100 rounded-xl py-4">
                    <p class="text-2xl font-extrabold text-[#A41E35]"><?php echo e($submittedCount); ?></p>
                    <p class="text-xs text-gray-400 mt-0.5">Submitted</p>
                </div>
                <div class="text-center bg-gray-50 border border-gray-100 rounded-xl py-4">
                    <p class="text-2xl font-extrabold text-gray-700"><?php echo e($totalStudents); ?></p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Siswa</p>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-xs font-semibold text-gray-500">Progress</span>
                    <span class="text-xs font-bold text-gray-700"><?php echo e($pct); ?>%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full transition-all" style="width:<?php echo e($pct); ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/guru/assignments/show.blade.php ENDPATH**/ ?>