<x-guest-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 min-h-screen flex flex-col justify-center">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <!-- Header dengan Hari dan Tanggal -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-2 text-black">Attendance QR Code</h2>
                <div class="text-gray-600 text-lg">
                    <p><span class="font-semibold">{{ $dayName }}</span>, {{ $dateFormatted }}</p>
                </div>
            </div>

            <!-- Kondisi: QR Code Ada -->
            @if($activeSessionToken)
                <div class="flex flex-col items-center">
                    
                    <div class="mb-6">
                        <div id="qrcode" class="border-4 border-gray-300 p-4 bg-white rounded-lg shadow-md"></div>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-gray-700 font-medium mb-2">Scan QR code ini untuk mencatat kehadiran:</p>
                        <p class="text-sm text-gray-500 break-all">{{ $activeSessionToken->token ?? $activeSessionToken }}</p>
                    </div>
                </div>
            @else
                <!-- Kondisi: QR Code Tidak Ada -->
                <div class="flex flex-col items-center">
                    <div class="mb-6 p-6 bg-yellow-50 border border-yellow-200 rounded-lg w-full">
                        <div class="flex items-center justify-center">
                            <x-heroicon-o-exclamation-triangle class="w-12 h-12 text-yellow-600 mr-4" />
                            <div>
                                <p class="text-yellow-800 font-semibold text-lg">Sesi Absensi Belum Aktif</p>
                                <p class="text-yellow-700 text-sm mt-1">QR code belum tersedia untuk hari ini ({{ $dateFormatted }})</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center text-gray-600">
                        <p class="mb-2">Mohon tunggu hingga sesi absensi dimulai.</p>
                        <p class="text-sm">Silakan refresh halaman ini untuk memeriksa status terbaru.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const token = "{{ $activeSessionToken->token ?? $activeSessionToken ?? '' }}";
        if (token) {
            const qrContainer = document.getElementById('qrcode');
            if (qrContainer) {
                new QRCode(qrContainer, {
                    text: token,
                    width: 200,
                    height: 200,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        }
    </script>
</x-guest-layout>