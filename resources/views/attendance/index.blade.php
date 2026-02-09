<x-app-layout title="Absensi" description="Scan QR Code dan Lakukan Absensi">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Scanner Section -->
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">1. Scan QR Code</h3>

                    <button id="btn-start-scan"
                        class="mb-4 w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                        Mulai Scan QR
                    </button>

                    <div id="reader" class="hidden"></div>

                    <p class="text-sm text-gray-500 mt-2 text-center" id="scan-status">
                        Tekan tombol untuk memulai scan QR
                    </p>
                </div>


                <!-- Form Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">2. Konfirmasi Kehadiran</h3>

                        <form method="POST" action="{{ route(Auth::user()->role->lower_name . '.attendance.store') }}"
                            enctype="multipart/form-data" id="attendance-form">
                            @csrf

                            <!-- Token Input -->
                            <div class="mb-4">
                                <label for="token"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Token
                                    Sesi</label>
                                <input type="text" name="token" id="token" readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                                    placeholder="Scan QR terlebih dahulu">
                            </div>

                            <!-- Selfie (Live Camera) -->
                            <div id="selfie-section" class="mb-4 hidden">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">3. Ambil
                                    Selfie</label>

                                <div id="selfie-container"
                                    class="relative w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden flex items-center justify-center border-2 border-gray-300">
                                    <video id="selfie-video" class="absolute inset-0 w-full h-full object-cover"
                                        autoplay muted playsinline></video>
                                    <canvas id="selfie-canvas"
                                        class="absolute inset-0 w-full h-full object-cover hidden"></canvas>

                                    <!-- Camera Controls -->
                                    <div id="camera-controls" class="absolute bottom-4 flex gap-2">
                                        <button type="button" id="btn-take-photo"
                                            class="bg-indigo-600 text-white px-6 py-2 rounded-full shadow-lg font-medium hover:bg-indigo-700">
                                            Ambil Foto
                                        </button>
                                        <button type="button" id="btn-retake-photo"
                                            class="hidden bg-red-600 text-white px-6 py-2 rounded-full shadow-lg font-medium hover:bg-red-700">
                                            Ulangi
                                        </button>
                                    </div>

                                    <div id="camera-loading" class="text-gray-500 font-medium z-10">
                                        Memulai Kamera...
                                    </div>
                                </div>
                                <input type="hidden" name="selfie_photo_base64" id="selfie_photo_base64">
                                @error('selfie_photo')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Native File Fallback (Optional) -->
                            <div id="fallback-section" class="mb-4 hidden">
                                <p class="text-xs text-gray-500 mb-1">Bermasalah dengan kamera? <button type="button"
                                        onclick="toggleFallback()" class="text-indigo-600 hover:underline">Gunakan
                                        upload file</button></p>
                                <div id="fallback-input" class="hidden">
                                    <input type="file" name="selfie_photo" id="selfie_photo_file" accept="image/*"
                                        capture="user"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                        onchange="checkReadyToSubmit()">
                                </div>
                            </div>

                            <!-- Location -->
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status
                                    Kehadiran</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                                    onchange="toggleAttachment()">
                                    <option value="hadir">Hadir</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                </select>
                            </div>

                            <!-- Note & Attachment (Conditional) -->
                            <div id="additional-fields" class="hidden space-y-4">
                                <div>
                                    <label for="note"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan /
                                        Alasan</label>
                                    <textarea name="note" id="note" rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"></textarea>
                                </div>
                                <div>
                                    <label for="attachment"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bukti (Surat
                                        Dokter/Izin)</label>
                                    <input type="file" name="attachment" id="attachment"
                                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" id="btn-submit" disabled
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                    Kirim Absensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        // ===============================
        // GLOBAL STATE
        // ===============================
        let html5QrcodeScanner = null;
        let qrScannerActive = false;

        // ===============================
        // ELEMENTS
        // ===============================
        const btnSubmit = document.getElementById('btn-submit');
        const readerDiv = document.getElementById('reader');
        const tokenInput = document.getElementById('token');

        // ===============================
        // 1. QR SCANNER
        // ===============================
        document.getElementById('btn-start-scan').addEventListener('click', () => {
            if (qrScannerActive) return;

            readerDiv.classList.remove('hidden');
            qrScannerActive = true;

            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);

            document.getElementById('scan-status').innerText = "Arahkan kamera ke QR Code Guru";
        });

        function onScanSuccess(decodedText) {
            tokenInput.value = decodedText;
            document.getElementById('scan-status').innerText = "✓ QR berhasil discan! Silakan ambil selfie.";
            document.getElementById('scan-status').classList.add('text-green-600', 'font-bold');

            // Show Selfie Section
            document.getElementById('selfie-section').classList.remove('hidden');
            document.getElementById('fallback-section').classList.remove('hidden');

            stopQRScanner();
            startCamera();
            checkReadyToSubmit();
        }

        function onScanFailure(error) {
            // silent
        }

        function stopQRScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().then(() => {
                    qrScannerActive = false;
                    readerDiv.classList.add('hidden');
                }).catch(err => {
                    qrScannerActive = false;
                    readerDiv.classList.add('hidden');
                });
            }
        }

        // ===============================
        // 2. LIVE CAMERA LOGIC
        // ===============================
        const video = document.getElementById('selfie-video');
        const canvas = document.getElementById('selfie-canvas');
        const btnTake = document.getElementById('btn-take-photo');
        const btnRetake = document.getElementById('btn-retake-photo');
        const hiddenBase64 = document.getElementById('selfie_photo_base64');
        const cameraLoading = document.getElementById('camera-loading');
        let cameraStream = null;

        function startCamera() {
            cameraLoading.classList.remove('hidden');
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user'
                }
            }).then(stream => {
                cameraStream = stream;
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    video.play();
                    cameraLoading.classList.add('hidden');
                };
            }).catch(err => {
                console.error("Kamera Error:", err);
                cameraLoading.innerText = "Gagal mengakses kamera.";
                alert("Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.");
            });
        }

        btnTake.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataURL = canvas.toDataURL('image/png');
            hiddenBase64.value = dataURL;

            // Switch UI
            video.classList.add('hidden');
            canvas.classList.remove('hidden');
            btnTake.classList.add('hidden');
            btnRetake.classList.remove('hidden');

            // Stop stream to save battery/resource
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
            }

            checkReadyToSubmit();
        });

        btnRetake.addEventListener('click', () => {
            hiddenBase64.value = '';
            video.classList.remove('hidden');
            canvas.classList.add('hidden');
            btnTake.classList.remove('hidden');
            btnRetake.classList.add('hidden');
            startCamera();
            checkReadyToSubmit();
        });

        function toggleFallback() {
            const fallback = document.getElementById('fallback-input');
            fallback.classList.toggle('hidden');
        }

        // Start camera moved to onScanSuccess
        // document.addEventListener('DOMContentLoaded', () => {
        //     startCamera();
        // });

        // ===============================
        // 3. GEOLOCATION
        // ===============================
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                document.getElementById('latitude').value = pos.coords.latitude;
                document.getElementById('longitude').value = pos.coords.longitude;
            }, err => {
                console.error('Geolocation error:', err);
                alert("Mohon izinkan lokasi untuk validasi absensi.");
            });
        }

        // ===============================
        // 4. FORM LOGIC
        // ===============================
        function toggleAttachment() {
            const status = document.getElementById('status').value;
            document.getElementById('additional-fields')
                .classList.toggle('hidden', status === 'hadir');
        }

        function checkReadyToSubmit() {
            const hasToken = tokenInput.value.length > 0;
            const hasBase64 = hiddenBase64.value.length > 0;
            const hasFile = document.getElementById('selfie_photo_file').files.length > 0;
            btnSubmit.disabled = !(hasToken && (hasBase64 || hasFile));
        }

        // CLEANUP
        window.addEventListener('beforeunload', () => {
            stopQRScanner();
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
            }
        });
    </script>
</x-app-layout>
