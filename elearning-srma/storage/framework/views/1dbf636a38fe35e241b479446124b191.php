
<?php $__env->startSection('title', $classSubject->subject->name); ?>
<?php $__env->startSection('icon', 'fas fa-book'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex justify-between items-start gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Siswa / Mata Pelajaran</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><?php echo e($classSubject->subject->name); ?></h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            <?php echo e($classSubject->eClass->name); ?> • <?php echo e($classSubject->teacher->name); ?>

        </span>
    </div>
    <a href="<?php echo e(route('siswa.subjects.index')); ?>"
       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition whitespace-nowrap">
        <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900"><i class="fas fa-info-circle mr-2 text-gray-400"></i>Informasi Kelas</h2>
        </div>
        <div class="divide-y divide-gray-100">
            <div class="px-5 py-3">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Pengajar</p>
                <p class="text-sm font-bold text-gray-900"><?php echo e($classSubject->teacher->name); ?></p>
            </div>
            <div class="px-5 py-3">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Jadwal</p>
                <?php
                    $daysOrder = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
                    $dayLabels = ['monday'=>'Senin','tuesday'=>'Selasa','wednesday'=>'Rabu','thursday'=>'Kamis','friday'=>'Jumat','saturday'=>'Sabtu','sunday'=>'Minggu'];

                    $sch = $classSubject->schedules
                        ->sortBy(fn ($s) => [
                            array_search(strtolower($s->day_of_week), $daysOrder),
                            $s->start_time,
                        ])
                        ->first();
                ?>
                <?php if($sch): ?>
                    <p class="text-sm font-bold text-gray-900">
                        <?php echo e($dayLabels[strtolower($sch->day_of_week)] ?? ucfirst($sch->day_of_week)); ?>

                        <?php if($sch->start_time): ?>
                            • <?php echo e(\Carbon\Carbon::createFromTimeString($sch->start_time)->format('H:i')); ?>

                            <?php if($sch->end_time): ?>
                                – <?php echo e(\Carbon\Carbon::createFromTimeString($sch->end_time)->format('H:i')); ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                <?php else: ?>
                    <p class="text-sm text-gray-400">TBA</p>
                <?php endif; ?>
            </div>
            <?php if($classSubject->eClass->description): ?>
                <div class="px-5 py-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Deskripsi</p>
                    <p class="text-sm text-gray-700"><?php echo e($classSubject->eClass->description); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-blue-400 to-indigo-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900"><i class="fas fa-chart-bar mr-2 text-gray-400"></i>Statistik</h2>
        </div>
        <div class="grid grid-cols-3 divide-x divide-gray-100">
            <div class="text-center py-6">
                <p class="text-2xl font-extrabold text-blue-600"><?php echo e($materials->count()); ?></p>
                <p class="text-xs text-gray-400 mt-1">Materi</p>
            </div>
            <div class="text-center py-6">
                <p class="text-2xl font-extrabold text-amber-600"><?php echo e($assignments->count()); ?></p>
                <p class="text-xs text-gray-400 mt-1">Tugas</p>
            </div>
            <div class="text-center py-6">
                <p class="text-2xl font-extrabold text-emerald-600"><?php echo e($classSubject->eClass->students->count()); ?></p>
                <p class="text-xs text-gray-400 mt-1">Siswa</p>
            </div>
        </div>
    </div>
</div>


<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-5">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900"><i class="fas fa-file-alt mr-2 text-gray-400"></i>Materi Pembelajaran</h2>
    </div>
    <div class="p-5">
        <?php if($materials->count() > 0): ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start justify-between gap-4 bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-sm truncate"><?php echo e($material->title); ?></h4>
                            <?php if($material->description): ?>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1"><?php echo e($material->description); ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-clock mr-1"></i><?php echo e($material->created_at->diffForHumans()); ?></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('siswa.materials.preview', $material)); ?>" target="_blank"
                               class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 text-xs font-semibold px-3 py-2 rounded-lg transition whitespace-nowrap">
                                <i class="fas fa-eye text-[10px]"></i> Preview
                            </a>
                            <a href="<?php echo e(route('siswa.materials.download', $material)); ?>"
                               class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 text-xs font-semibold px-3 py-2 rounded-lg transition whitespace-nowrap">
                                <i class="fas fa-download text-[10px]"></i> Download
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center py-8 text-center">
                <i class="fas fa-file-alt text-gray-200 text-3xl mb-2"></i>
                <p class="text-xs text-gray-400">Belum ada materi pembelajaran.</p>
            </div>
        <?php endif; ?>
    </div>
</div>


<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-5">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900"><i class="fas fa-tasks mr-2 text-gray-400"></i>Tugas dan Penilaian</h2>
    </div>
    <div class="p-5">
        <?php if($assignments->count() > 0): ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $sub = \App\Models\Submission::where('student_id', auth()->id())->where('assignment_id', $assignment->id)->first();
                        $done = $sub && $sub->submitted_at;
                    ?>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition">
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <h4 class="font-semibold text-gray-900 text-sm flex-1 min-w-0 truncate"><?php echo e($assignment->title); ?></h4>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full border whitespace-nowrap flex-shrink-0 <?php echo e($done ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200'); ?>">
                                <?php echo e($done ? '✓ Terkumpul' : '✗ Belum'); ?>

                            </span>
                        </div>
                        <div class="flex items-center gap-4 mb-3">
                            <span class="text-xs text-gray-500"><i class="fas fa-clock mr-1"></i><?php echo e($assignment->deadline->format('d M H:i')); ?></span>
                            <?php if($sub && $sub->grade): ?>
                                <span class="text-xs font-bold text-blue-600"><i class="fas fa-star mr-1 text-amber-400"></i><?php echo e($sub->grade->score); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('siswa.assignments.show', $assignment->id)); ?>"
                           class="w-full inline-flex justify-center items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold py-2.5 rounded-xl transition">
                            <i class="fas fa-arrow-right text-[10px]"></i> Lihat Detail
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center py-8 text-center">
                <i class="fas fa-tasks text-gray-200 text-3xl mb-2"></i>
                <p class="text-xs text-gray-400">Belum ada tugas untuk mata pelajaran ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>


<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900"><i class="fas fa-clipboard-list mr-2 text-gray-400"></i>Presensi</h2>
    </div>
    <div class="p-5">
        <?php
            $allSessions = $classSubject->attendanceSessions()->where('status','!=','cancelled')->with('records')->orderBy('attendance_date','desc')->take(10)->get();
        ?>

        <?php if($allSessions->count() > 0): ?>
            <div class="space-y-2 mb-4">
                <?php $__currentLoopData = $allSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sess): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800"><?php echo e(\Carbon\Carbon::parse($sess->attendance_date)->format('d M Y')); ?></p>
                            <p class="text-xs text-gray-400">Status: <?php echo e($sess->status); ?></p>
                        </div>
                        <a href="<?php echo e(route('siswa.attendance.show', $classSubject)); ?>"
                           class="text-xs font-semibold text-blue-600 hover:text-blue-800">Lihat</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center py-8 text-center mb-3">
                <i class="fas fa-inbox text-gray-200 text-3xl mb-2"></i>
                <p class="text-xs text-gray-400">Belum ada riwayat presensi</p>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(route('siswa.attendance.show', $classSubject)); ?>"
           class="w-full inline-flex justify-center items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold py-2.5 rounded-xl transition">
            <i class="fas fa-check-circle text-xs"></i> Buka Presensi
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/siswa/subjects/show.blade.php ENDPATH**/ ?>