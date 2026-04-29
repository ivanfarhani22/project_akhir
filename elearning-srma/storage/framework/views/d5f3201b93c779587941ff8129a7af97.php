
<?php $__env->startSection('title', 'Presensi - Semua Mata Pelajaran'); ?>
<?php $__env->startSection('icon', 'fas fa-clipboard-list'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-clipboard-list mr-1"></i> Guru / Presensi</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-clipboard-list text-[#A41E35] mr-2"></i>Presensi</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola presensi untuk semua mata pelajaran</p>
    </div>
    <a href="<?php echo e(route('guru.attendance.create')); ?>"
       class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition whitespace-nowrap">
        <i class="fas fa-plus text-xs"></i> Buat Presensi
    </a>
</div>

<?php if(session('success')): ?>
    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if($classSubjects->count() > 0): ?>
    <div class="space-y-5">
        <?php $__currentLoopData = $classSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classSubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $subjectSessions = $sessions->filter(fn($s) => $s->class_subject_id === $classSubject->id)->take(5); ?>
            <div class="bg-white rounded-2xl border-2 border-gray-100 overflow-hidden shadow-sm">
                <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100 bg-gray-50">
                    <div>
                        <h2 class="font-bold text-gray-900"><?php echo e($classSubject->subject->name); ?></h2>
                        <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-door-open mr-1"></i><?php echo e($classSubject->eClass->name); ?></p>
                    </div>
                    <a href="<?php echo e(route('guru.attendance.create')); ?>" data-subject="<?php echo e($classSubject->id); ?>"
                       class="inline-flex items-center gap-1.5 bg-[#A41E35] hover:bg-[#7D1627] text-white text-xs font-semibold px-3 py-2 rounded-lg transition btn-create-attendance">
                        <i class="fas fa-plus text-[10px]"></i> Buat
                    </a>
                </div>

                <div class="p-5">
                    <?php if($subjectSessions->count() > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $subjectSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50 border border-gray-100 rounded-xl p-4 hover:border-gray-200 transition">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 text-sm"><?php echo e($session->attendance_date->format('l, d F Y')); ?></p>
                                        <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-clock mr-1"></i><?php echo e($session->opened_at); ?><?php echo e($session->closed_at ? ' - '.$session->closed_at : ''); ?></p>
                                        <div class="flex flex-wrap gap-3 mt-2">
                                            <span class="text-xs text-gray-500"><i class="fas fa-users mr-1"></i><?php echo e($session->records->count()); ?> total</span>
                                            <span class="text-xs text-emerald-600 font-medium"><i class="fas fa-check mr-1"></i><?php echo e($session->records->where('status','present')->count()); ?> hadir</span>
                                            <span class="text-xs text-red-500 font-medium"><i class="fas fa-times mr-1"></i><?php echo e($session->records->where('status','absent')->count()); ?> absen</span>
                                            <span class="text-xs text-blue-600 font-medium"><i class="fas fa-percent mr-1"></i><?php echo e($session->getAttendancePercentage()); ?>%</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                            <?php echo e($session->isOpen() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($session->isClosed() ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-100 text-gray-500 border border-gray-200')); ?>">
                                            <?php echo e($session->isOpen() ? 'Terbuka' : ($session->isClosed() ? 'Ditutup' : 'Dibatalkan')); ?>

                                        </span>
                                        <a href="<?php echo e(route('guru.attendance.show', $session)); ?>"
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs transition">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($session->isOpen()): ?>
                                            <form action="<?php echo e(route('guru.attendance.close', $session)); ?>" method="POST" class="attendance-form">
                                                <?php echo csrf_field(); ?>
                                                <button type="button" onclick="confirmAttendanceClose(event)"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 hover:bg-orange-500 text-orange-500 hover:text-white border border-orange-200 rounded-lg text-xs transition">
                                                    <i class="fas fa-door-closed"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <i class="fas fa-inbox text-gray-200 text-3xl mb-2"></i>
                            <p class="text-xs text-gray-400">Belum ada presensi untuk mata pelajaran ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-clipboard-list text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Anda tidak memiliki mata pelajaran apapun.</p>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmAttendanceClose(event) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation('Tutup presensi?', 'Konfirmasi Tutup Presensi', () => form.submit());
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/guru/attendance/index-all.blade.php ENDPATH**/ ?>