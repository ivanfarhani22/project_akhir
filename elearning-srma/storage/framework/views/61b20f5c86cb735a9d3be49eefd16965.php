
<?php $__env->startSection('title', 'Jadwal Pelajaran'); ?>
<?php $__env->startSection('icon', 'fas fa-calendar-alt'); ?>

<?php
    $daysOrder = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    $dayLabels = ['monday'=>'Senin','tuesday'=>'Selasa','wednesday'=>'Rabu','thursday'=>'Kamis','friday'=>'Jumat','saturday'=>'Sabtu','sunday'=>'Minggu'];
    $dayColors = ['monday'=>'bg-indigo-500','tuesday'=>'bg-sky-500','wednesday'=>'bg-emerald-500','thursday'=>'bg-amber-500','friday'=>'bg-red-500','saturday'=>'bg-purple-500','sunday'=>'bg-pink-500'];
    if (!isset($classes)) $classes = collect([]);
    $schedules = collect([]);
    foreach ($classes as $class) {
        if ($class->schedules && count($class->schedules) > 0) {
            foreach ($class->schedules as $schedule) { $schedule->class = $class; $schedules->push($schedule); }
        }
    }
?>

<?php $__env->startSection('content'); ?>

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-calendar-alt mr-1"></i> Siswa / Jadwal</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-calendar-alt text-blue-500 mr-2"></i>Jadwal Pelajaran</h1>
    <p class="text-sm text-gray-500 mt-1">Lihat jadwal mingguan kelas Anda</p>
</div>

<?php if($schedules->count()): ?>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900"><i class="fas fa-table mr-2 text-gray-400"></i>Jadwal Mingguan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hari</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengajar</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $hasAny = false; ?>
                    <?php $__currentLoopData = $daysOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $daySchedules = $schedules->filter(fn($s) => strtolower($s->day_of_week) === $day)->sortBy('start_time'); ?>
                        <?php $__currentLoopData = $daySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $hasAny = true; $class = $schedule->class; ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3.5">
                                    <?php if($loop->first): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold text-white <?php echo e($dayColors[$day] ?? 'bg-indigo-500'); ?>">
                                            <?php echo e($dayLabels[$day] ?? ucfirst($day)); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-gray-800"><?php echo e($class->classSubjects?->first()?->subject?->name ?? '—'); ?></td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs"><?php echo e($class->name); ?></td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs"><?php echo e($class->classSubjects?->first()?->teacher?->name ?? '—'); ?></td>
                                <td class="px-5 py-3.5">
                                    <?php if($schedule->start_time): ?>
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-lg">
                                            <i class="fas fa-clock text-[10px]"></i>
                                            <?php echo e(\Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i')); ?><?php echo e($schedule->end_time ? ' – '.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : ''); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">TBA</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!$hasAny): ?>
                        <tr><td colspan="5" class="py-10 text-center text-xs text-gray-400">Belum ada jadwal.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php $__currentLoopData = $schedules->sortBy(fn($s) => array_search(strtolower($s->day_of_week), $daysOrder)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $class = $schedule->class; $dc = $dayColors[strtolower($schedule->day_of_week)] ?? 'bg-indigo-500'; ?>
            <div class="bg-white rounded-2xl border-2 border-gray-100 hover:border-blue-300 hover:shadow-lg transition-all duration-200 overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-blue-400 to-indigo-400"></div>
                <div class="flex justify-between items-start gap-3 p-4 border-b border-gray-100">
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 text-sm truncate"><?php echo e($class->classSubjects?->first()?->subject?->name ?? 'Mata Pelajaran'); ?></h3>
                        <p class="text-xs text-gray-400 mt-0.5"><?php echo e($class->name); ?></p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold text-white <?php echo e($dc); ?> flex-shrink-0">
                        <?php echo e($dayLabels[strtolower($schedule->day_of_week)] ?? $schedule->day_of_week); ?>

                    </span>
                </div>
                <div class="p-4 space-y-2">
                    <p class="text-xs text-gray-500"><i class="fas fa-chalkboard-teacher mr-1 text-gray-300"></i><?php echo e($class->classSubjects?->first()?->teacher?->name ?? '—'); ?></p>
                    <?php if($schedule->start_time): ?>
                        <p class="text-xs text-gray-500"><i class="fas fa-clock mr-1 text-gray-300"></i><?php echo e(\Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i')); ?><?php echo e($schedule->end_time ? ' – '.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : ''); ?></p>
                    <?php endif; ?>
                    <?php if($schedule->room): ?>
                        <p class="text-xs text-gray-500"><i class="fas fa-door-open mr-1 text-gray-300"></i><?php echo e($schedule->room); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo e(route('siswa.subjects.show', $class->id)); ?>"
                       class="mt-2 w-full inline-flex justify-center items-center gap-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-2.5 px-4 rounded-xl transition">
                        <i class="fas fa-arrow-right text-[10px]"></i> Lihat Detail
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-calendar-alt text-3xl text-gray-300"></i>
            </div>
            <?php if($classes->count() === 0): ?>
                <p class="text-gray-700 font-semibold text-sm mb-1">Anda belum terdaftar di kelas apapun</p>
                <p class="text-xs text-gray-400">Jadwal akan muncul setelah Anda terdaftar di kelas.</p>
            <?php else: ?>
                <p class="text-gray-700 font-semibold text-sm mb-1">Belum ada jadwal yang ditetapkan</p>
                <p class="text-xs text-gray-400">Hubungi guru atau admin untuk mengatur jadwal kelas Anda.</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/siswa/schedule/index.blade.php ENDPATH**/ ?>