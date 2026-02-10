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
                    
                    @if (request()->getScheme() !== 'https' && request()->getHost() !== 'localhost' && request()->getHost() !== '127.0.0.1')
                        <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                ⚠️ <strong>Peringatan:</strong> Fitur kamera memerlukan koneksi HTTPS. Kamera mungkin tidak berfungsi di HTTP.
                            </p>
                        </div>
                    @endif

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
                            enctype="multipart/form-data" id="attendance-form" onsubmit="return validateBeforeSubmit(event)">
                            @csrf

                            <!-- Error Display -->
                            @if ($errors->any())
                                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded">
                                    <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">❌ Ada kesalahan:</p>
                                    <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

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
                                    class="relative w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden flex items-center justify-center border-2 border-gray-300" style="background: #000;">
                                    <video id="selfie-video" style="display: block; width: 100%; height: 100%; object-fit: cover;"
                                        autoplay muted playsinline></video>
                                    <canvas id="selfie-canvas"
                                        class="absolute inset-0 w-full h-full object-cover hidden" style="display: none;"></canvas>

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
                                    
                                    <!-- Video Debug Info (Hidden) -->
                                    <div id="video-debug" class="absolute top-2 left-2 text-xs bg-black bg-opacity-50 text-white p-2 rounded hidden max-w-xs">
                                        <p id="debug-ready">Ready: -</p>
                                        <p id="debug-playing">Playing: -</p>
                                        <p id="debug-width">Size: -</p>
                                    </div>
                                </div>
                                <input type="hidden" name="selfie_photo_base64" id="selfie_photo_base64">
                                <p class="text-xs text-gray-500 mt-1" id="photo-status"></p>
                                @error('selfie_photo')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Native File Fallback (Optional) -->
                            <div id="fallback-section" class="mb-4 hidden">
                                <div id="fallback-input" class="hidden bg-blue-50 dark:bg-gray-700 border-2 border-blue-300 dark:border-blue-600 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-3">
                                        📷 Alternatif: Unggah Foto
                                    </p>
                                    <input type="file" name="selfie_photo" id="selfie_photo_file" accept="image/*" capture="user"
                                        class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-500 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-600 focus:outline-none"
                                        onchange="checkReadyToSubmit()">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Pilih foto selfie dari galeri atau ambil foto baru</p>
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
                                    <span id="submit-text">Kirim Absensi</span>
                                </button>
                            </div>
                            <p id="submit-error" class="text-xs text-red-600 mt-2 hidden"></p>
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

            // Show Selfie Section & Fallback
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
            console.log('Starting camera...');
            
            // Check HTTPS
            if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                cameraLoading.innerHTML = `<div class="text-center">
                    <p class="text-red-600 font-bold">⚠️ Akses HTTPS diperlukan untuk kamera</p>
                    <p class="text-sm text-gray-500 mt-2">Gunakan file upload di bawah</p>
                </div>`;
                setTimeout(() => showFileFallback(), 1000);
                return;
            }

            // Request camera with flexible constraints
            const constraints = {
                video: {
                    facingMode: { ideal: 'user' }
                },
                audio: false
            };

            navigator.mediaDevices.getUserMedia(constraints)
                .then(stream => {
                    console.log('✓ Camera stream acquired');
                    cameraStream = stream;
                    
                    // Set srcObject directly
                    video.srcObject = stream;
                    
                    // Ensure video is visible
                    video.style.display = 'block';
                    
                    // Try to play immediately (for some browsers)
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise
                            .then(() => {
                                console.log('✓ Video playing immediately');
                                cameraLoading.classList.add('hidden');
                            })
                            .catch(err => {
                                console.log('Play failed, waiting for metadata:', err.message);
                            });
                    }
                    
                    // Fallback: play on loaded metadata
                    video.onloadedmetadata = () => {
                        console.log('✓ Video metadata loaded, playing...');
                        video.play().catch(err => {
                            console.error('Play on metadata error:', err);
                        });
                        cameraLoading.classList.add('hidden');
                    };
                    
                    // Safety timeout - hide loading if still no video
                    setTimeout(() => {
                        if (!video.srcObject) return;
                        const readyState = video.readyState;
                        const paused = video.paused;
                        const dimensions = `${video.videoWidth}x${video.videoHeight}`;
                        
                        console.log('Camera timeout check - Video state:', {
                            readyState: readyState,
                            paused: paused,
                            dimensions: dimensions
                        });
                        
                        // Update debug info
                        document.getElementById('debug-ready').innerText = `Ready: ${readyState}`;
                        document.getElementById('debug-playing').innerText = `Playing: ${!paused}`;
                        document.getElementById('debug-width').innerText = `Size: ${dimensions}`;
                        
                        cameraLoading.classList.add('hidden');
                        
                        // If no video dimensions yet, show debug and error
                        if (video.videoWidth === 0 || video.videoHeight === 0) {
                            document.getElementById('video-debug').classList.remove('hidden');
                            showSubmitError('Video preview mungkin tidak visible. Cek F12 console atau coba refresh halaman');
                        }
                    }, 3000);
                })
                .catch(err => {
                    console.error("Kamera Error:", err.name, err.message);
                    
                    let errorMsg = "Gagal mengakses kamera. ";
                    let showFallback = true;
                    
                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        errorMsg = "❌ Izin kamera ditolak. Buka Settings → Aplikasi → E-Spacelab → Izinkan Kamera";
                    } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                        errorMsg = "❌ Kamera tidak ditemukan di perangkat ini";
                    } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                        errorMsg = "❌ Kamera sedang digunakan aplikasi lain. Tutup aplikasi lain terlebih dahulu";
                    } else if (err.name === 'SecurityError') {
                        errorMsg = "❌ Akses HTTPS diperlukan untuk fitur kamera";
                    } else if (err.name === 'AbortError') {
                        errorMsg = "❌ Permintaan kamera dibatalkan";
                    }
                    
                    cameraLoading.innerHTML = `<div class="text-center">
                        <p class="text-red-600 font-bold text-sm">${errorMsg}</p>
                        <p class="text-xs text-gray-500 mt-3">Gunakan upload file sebagai alternatif ↓</p>
                    </div>`;
                    
                    if (showFallback) {
                        setTimeout(() => showFileFallback(), 1500);
                    }
                });
        }
        
        function showFileFallback() {
            document.getElementById('selfie-container').classList.add('hidden');
            document.getElementById('fallback-section').classList.remove('hidden');
            document.getElementById('fallback-input').classList.remove('hidden');
        }

        btnTake.addEventListener('click', () => {
            console.log('Taking photo - video dimensions:', {
                videoWidth: video.videoWidth,
                videoHeight: video.videoHeight
            });
            
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Compress image to reduce base64 size - use JPEG 70% quality
            const dataURL = canvas.toDataURL('image/jpeg', 0.7);
            hiddenBase64.value = dataURL;

            // Show photo status
            const sizeInMB = (dataURL.length / (1024 * 1024)).toFixed(2);
            document.getElementById('photo-status').innerText = `✓ Foto berhasil diambil (${sizeInMB} MB)`;

            // Switch UI
            video.style.display = 'none';
            canvas.style.display = 'block';
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
            video.style.display = 'block';
            canvas.style.display = 'none';
            btnTake.className = 'bg-indigo-600 text-white px-6 py-2 rounded-full shadow-lg font-medium hover:bg-indigo-700';
            btnRetake.className = 'hidden bg-red-600 text-white px-6 py-2 rounded-full shadow-lg font-medium hover:bg-red-700';
            startCamera();
            checkReadyToSubmit();
        });

        // Start camera moved to onScanSuccess
        // document.addEventListener('DOMContentLoaded', () => {
        //     startCamera();
        // });

        // ===============================
        // 3. GEOLOCATION
        // ===============================
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    document.getElementById('latitude').value = pos.coords.latitude;
                    document.getElementById('longitude').value = pos.coords.longitude;
                    console.log('✓ Geolocation acquired:', pos.coords);
                }, 
                err => {
                    console.warn('Geolocation error:', err.code, err.message);
                    // Silently fail - let form validation catch it
                },
                {
                    timeout: 10000,
                    enableHighAccuracy: false,
                    maximumAge: 60000 // Accept location from last 1 minute
                }
            );
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

        function validateBeforeSubmit(event) {
            const hasToken = tokenInput.value.trim().length > 0;
            const hasBase64 = hiddenBase64.value.trim().length > 0;
            const hasFile = document.getElementById('selfie_photo_file').files.length > 0;
            const hasGeolocation = document.getElementById('latitude').value.length > 0;

            // Check all required fields
            if (!hasToken) {
                showSubmitError('QR Code belum di-scan');
                return false;
            }

            if (!hasBase64 && !hasFile) {
                showSubmitError('Selfie belum diambil atau file belum dipilih');
                return false;
            }

            if (!hasGeolocation) {
                showSubmitError('Lokasi belum terdeteksi. Pastikan GPS/Lokasi sudah diaktifkan');
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById('btn-submit');
            const submitText = document.getElementById('submit-text');
            submitBtn.disabled = true;
            submitText.innerText = 'Mengirim...';

            return true;
        }

        function showSubmitError(message) {
            const errorElement = document.getElementById('submit-error');
            errorElement.innerText = '❌ ' + message;
            errorElement.classList.remove('hidden');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                errorElement.classList.add('hidden');
            }, 5000);
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
