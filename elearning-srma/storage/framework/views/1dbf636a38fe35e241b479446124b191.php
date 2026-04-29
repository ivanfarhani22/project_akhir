
<?php $__env->startSection('title', $class->subject->name); ?>
<?php $__env->startSection('icon', 'fas fa-book'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex justify-between items-start gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Siswa / Mata Pelajaran</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><?php echo e($class->subject->name); ?></h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            <?php echo e($class->name); ?> • <?php echo e($class->teacher->name); ?>

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
                <p class="text-sm font-bold text-gray-900"><?php echo e($class->teacher->name); ?></p>
            </div>
            <div class="px-5 py-3">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Jadwal</p>
                <?php if($class->schedules && $class->schedules->count() > 0): ?>
                    <?php $sch = $class->schedules->first(); ?>
                    <p class="text-sm font-bold text-gray-900">
                        <?php echo e(ucfirst($sch->day_of_week)); ?>

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
            <?php if($class->description): ?>
                <div class="px-5 py-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Deskripsi</p>
                    <p class="text-sm text-gray-700"><?php echo e($class->description); ?></p>
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
                <p class="text-2xl font-extrabold text-blue-600"><?php echo e($class->materials->count()); ?></p>
                <p class="text-xs text-gray-400 mt-1">Materi</p>
            </div>
            <div class="text-center py-6">
                <p class="text-2xl font-extrabold text-amber-600"><?php echo e($class->assignments->count()); ?></p>
                <p class="text-xs text-gray-400 mt-1">Tugas</p>
            </div>
            <div class="text-center py-6">
                <p class="text-2xl font-extrabold text-emerald-600"><?php echo e($class->students->count()); ?></p>
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
        <?php if($class->materials->count() > 0): ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $class->materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start justify-between gap-4 bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-sm truncate"><?php echo e($material->title); ?></h4>
                            <?php if($material->description): ?>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1"><?php echo e($material->description); ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-clock mr-1"></i><?php echo e($material->created_at->diffForHumans()); ?></p>
                        </div>
                        <a href="<?php echo e(route('siswa.materials.download', $material)); ?>"
                           class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 text-xs font-semibold px-3 py-2 rounded-lg transition whitespace-nowrap">
                            <i class="fas fa-download text-[10px]"></i> Buka
                        </a>
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
        <?php if($class->assignments->count() > 0): ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $class->assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
            $openSessions = [];
            foreach($class->classSubjects as $cs) {
                $sess = $cs->attendanceSessions()->where('status','open')->where('attendance_date', today())->first();
                if ($sess) $openSessions[$cs->id] = $sess;
            }
        ?>

        <?php if(count($openSessions) > 0): ?>
            <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 mb-4">
                <i class="fas fa-circle-notch animate-spin text-emerald-500 mt-0.5 text-xs"></i>
                <p class="text-sm font-semibold text-emerald-700">Presensi Terbuka Hari Ini</p>
            </div>
            <div class="space-y-2 mb-4">
                <?php $__currentLoopData = $openSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $csId => $sess): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('siswa.attendance.show', $class->classSubjects->find($csId))); ?>"
                       class="w-full inline-flex justify-center items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                        <i class="fas fa-check-circle text-xs"></i> Absensi — <?php echo e($class->classSubjects->find($csId)->subject->name); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center py-8 text-center mb-3">
                <i class="fas fa-inbox text-gray-200 text-3xl mb-2"></i>
                <p class="text-xs text-gray-400">Belum ada presensi hari ini</p>
            </div>
        <?php endif; ?>

        <?php if($class->classSubjects->count() > 0): ?>
            <div class="space-y-1 pt-3 border-t border-gray-100">
                <?php $__currentLoopData = $class->classSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('siswa.attendance.show', $cs)); ?>"
                       class="flex items-center gap-2 text-xs text-blue-500 hover:text-blue-700 font-semibold transition py-1">
                        <i class="fas fa-history text-[10px]"></i> Riwayat — <?php echo e($cs->subject->name); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/siswa/subjects/show.blade.php ENDPATH**/ ?>