

<?php $__env->startSection('title', 'Offline'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-xl mx-auto px-4 py-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
        <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-wifi-slash text-2xl"></i>
        </div>
        <h1 class="text-xl font-bold text-gray-900">Anda sedang offline</h1>
        <p class="text-gray-600 mt-2">Periksa koneksi internet Anda, lalu coba lagi.</p>
        <button type="button" onclick="window.location.reload()" class="mt-6 inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
            Coba muat ulang
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/offline/offline.blade.php ENDPATH**/ ?>