{{-- resources/views/components/loading.blade.php --}}
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-[9999]">
  <div class="flex flex-col items-center">
    <div class="w-16 h-16 border-4 border-white border-t-transparent rounded-full animate-spin mb-4"></div>
    <p class="text-white text-lg font-semibold">Memproses...</p>
  </div>
</div>

<style>
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  .animate-spin {
    animation: spin 1s linear infinite;
  }
</style>
