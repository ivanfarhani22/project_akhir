
<?php $__env->startSection('title', 'Dashboard Guru'); ?>
<?php $__env->startSection('icon', 'fas fa-graduation-cap'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-graduation-cap mr-1"></i> Guru</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-graduation-cap text-[#A41E35] mr-2"></i>Dashboard Guru</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola kelas, materi, dan penilaian Anda</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php
        $stats = [
            ['label'=>'Mata Pelajaran','value'=>$totalClassSubjects,'icon'=>'fa-book','bg'=>'bg-amber-50','icon_color'=>'text-amber-500','border'=>'border-amber-400'],
            ['label'=>'Kelas','value'=>$totalClasses,'icon'=>'fa-chalkboard','bg'=>'bg-blue-50','icon_color'=>'text-blue-500','border'=>'border-blue-400'],
            ['label'=>'Siswa Total','value'=>$totalStudents,'icon'=>'fa-users','bg'=>'bg-emerald-50','icon_color'=>'text-emerald-500','border'=>'border-emerald-400'],
            ['label'=>'Materi Diunggah','value'=>$totalMaterials,'icon'=>'fa-file-alt','bg'=>'bg-red-50','icon_color'=>'text-[#A41E35]','border'=>'border-[#A41E35]'],
        ];
    ?>
    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="h-1 <?php echo e(str_replace('border-','bg-',$s['border'])); ?>"></div>
            <div class="p-5 flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1"><?php echo e($s['label']); ?></p>
                    <p class="text-3xl font-extrabold text-gray-900"><?php echo e($s['value']); ?></p>
                </div>
                <div class="w-10 h-10 <?php echo e($s['bg']); ?> rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas <?php echo e($s['icon']); ?> <?php echo e($s['icon_color']); ?>"></i>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="mb-8">
    <h2 class="text-base font-extrabold text-gray-900 mb-4"><i class="fas fa-bolt text-amber-400 mr-2"></i>Aksi Cepat</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <?php
            $actions = [
                ['href'=>route('guru.materials.index'),'icon'=>'fa-plus-square','color'=>'text-amber-500','bg'=>'bg-amber-50','label'=>'Buat Materi','desc'=>'Tambahkan materi pembelajaran baru'],
                ['href'=>route('guru.assignments.create'),'icon'=>'fa-tasks','color'=>'text-blue-500','bg'=>'bg-blue-50','label'=>'Buat Tugas','desc'=>'Buat tugas atau kuis untuk siswa'],
                ['href'=>route('guru.attendance.index'),'icon'=>'fa-clipboard-list','color'=>'text-emerald-500','bg'=>'bg-emerald-50','label'=>'Kelola Presensi','desc'=>'Catat kehadiran siswa di kelas'],
            ];
        ?>
        <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($a['href']); ?>"
               class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-gray-300 hover:shadow-md transition-all duration-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 <?php echo e($a['bg']); ?> rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas <?php echo e($a['icon']); ?> <?php echo e($a['color']); ?> text-lg"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm"><?php echo e($a['label']); ?></p>
                    <p class="text-xs text-gray-400 mt-0.5"><?php echo e($a['desc']); ?></p>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="mb-8">
    <h2 class="text-base font-extrabold text-gray-900 mb-4"><i class="fas fa-chalkboard text-[#A41E35] mr-2"></i>Mata Pelajaran Saya <span class="text-gray-400 font-normal">(<?php echo e($totalClassSubjects); ?>)</span></h2>

    <?php if($classSubjects->isEmpty()): ?>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-chalkboard text-3xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-sm">Belum ada mata pelajaran yang ditugaskan.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php $__currentLoopData = $classSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-[#A41E35] hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
                    <div class="p-5">
                        <h3 class="text-base font-bold text-gray-900 truncate"><?php echo e($cs->eClass->name); ?></h3>
                        <p class="text-xs text-gray-500 mt-0.5 mb-4"><?php echo e($cs->subject->name); ?></p>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="text-center bg-blue-50 rounded-xl py-3">
                                <p class="text-xl font-extrabold text-blue-600"><?php echo e($cs->eClass->students->count()); ?></p>
                                <p class="text-xs text-gray-400 mt-0.5">Siswa</p>
                            </div>
                            <div class="text-center bg-emerald-50 rounded-xl py-3">
                                <p class="text-xl font-extrabold text-emerald-600"><?php echo e($cs->eClass->materials->count()); ?></p>
                                <p class="text-xs text-gray-400 mt-0.5">Materi</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="<?php echo e(route('guru.materials.index', ['class' => $cs->eClass->id])); ?>"
                               class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#A41E35] hover:bg-[#7D1627] text-white text-xs font-semibold py-2.5 rounded-xl transition shadow-sm hover:shadow-md">
                                <i class="fas fa-book text-[10px]"></i> Materi
                            </a>
                            <a href="<?php echo e(route('guru.assignments.index', ['class' => $cs->eClass->id])); ?>"
                               class="flex-1 inline-flex justify-center items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 rounded-xl transition">
                                <i class="fas fa-tasks text-[10px]"></i> Tugas
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<div>
    <h2 class="text-base font-extrabold text-gray-900 mb-4"><i class="fas fa-history text-gray-400 mr-2"></i>Aktivitas Terbaru</h2>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = \App\Models\ActivityLog::where('user_id', auth()->id())->orderBy('timestamp','desc')->take(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <?php echo e($log->action); ?>

                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs"><?php echo e(Str::limit($log->description, 60)); ?></td>
                            <td class="px-5 py-3.5 text-xs text-gray-400"><?php echo e(\Carbon\Carbon::parse($log->timestamp)->diffForHumans()); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <i class="fas fa-inbox text-gray-200 text-4xl mb-3 block"></i>
                                <p class="text-gray-400 text-sm">Belum ada aktivitas.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/guru/dashboard.blade.php ENDPATH**/ ?>