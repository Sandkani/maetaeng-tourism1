<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thai TTS & Audio Upload App</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-lg w-full">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">แอปพลิเคชัน TTS และอัปโหลดไฟล์เสียง</h1>

        <!-- ส่วนของ Text-to-Speech (TTS) -->
        <div class="mb-8 p-6 bg-blue-50 rounded-lg">
            <h2 class="text-xl font-semibold text-blue-800 mb-4">ข้อความแปลงเป็นเสียงพูด</h2>
            <textarea id="text-input" rows="4" class="w-full p-3 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" placeholder="พิมพ์ข้อความที่นี่..."></textarea>
            <button id="tts-button" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 shadow-md">
                เล่นเสียงจากข้อความ
            </button>
        </div>

        <div class="text-center text-gray-500 font-semibold mb-8">
            <span class="inline-block p-2 bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center mx-auto">หรือ</span>
        </div>

        <!-- ส่วนของการอัปโหลดไฟล์เสียง -->
        <div class="p-6 bg-green-50 rounded-lg">
            <h2 class="text-xl font-semibold text-green-800 mb-4">อัปโหลดไฟล์เสียงของคุณเอง</h2>
            <div class="flex items-center justify-center w-full">
                <label for="audio-file-input" class="flex flex-col items-center justify-center w-full h-32 border-2 border-green-300 border-dashed rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition duration-200">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.207l-.73.73A4.77 4.77 0 0 0 4 6.5a4.77 4.77 0 0 0-.277.106M15 11l-3-3m0 0l-3 3m3-3v8"></path>
                        </svg>
                        <p class="mb-2 text-sm text-green-500"><span class="font-semibold">คลิกเพื่ออัปโหลด</span> หรือลากและวางที่นี่</p>
                        <p class="text-xs text-green-400">รองรับไฟล์เสียง (MP3, WAV, OGG)</p>
                    </div>
                    <input id="audio-file-input" type="file" class="hidden" accept="audio/*" />
                </label>
            </div>
            <audio id="audio-player" class="mt-4 w-full rounded-lg" controls></audio>
        </div>

        <p id="message" class="text-sm text-center text-gray-600 mt-6"></p>
    </div>

    <script>
        // ฟังก์ชันสำหรับแปลง Base64 เป็น ArrayBuffer และสร้างไฟล์ WAV
        // จำเป็นสำหรับการแปลงเสียงจาก API TTS ที่เป็น PCM
        function base64ToArrayBuffer(base64) {
            const binaryString = atob(base64);
            const len = binaryString.length;
            const bytes = new Uint8Array(len);
            for (let i = 0; i < len; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes.buffer;
        }

        // ฟังก์ชันแปลง PCM เป็น WAV
        // จำเป็นสำหรับการแปลงเสียงจาก API TTS ที่เป็น PCM
        function pcmToWav(pcmData, sampleRate) {
            const numChannels = 1;
            const bytesPerSample = 2; // Signed 16-bit PCM
            const buffer = new ArrayBuffer(44 + pcmData.length * bytesPerSample);
            const view = new DataView(buffer);

            // RIFF chunk descriptor
            writeString(view, 0, 'RIFF');
            view.setUint32(4, 36 + pcmData.length * bytesPerSample, true);
            writeString(view, 8, 'WAVE');

            // fmt sub-chunk
            writeString(view, 12, 'fmt ');
            view.setUint32(16, 16, true);
            view.setUint16(20, 1, true); // Audio format (1 = PCM)
            view.setUint16(22, numChannels, true);
            view.setUint32(24, sampleRate, true);
            view.setUint32(28, sampleRate * numChannels * bytesPerSample, true);
            view.setUint16(32, numChannels * bytesPerSample, true);
            view.setUint16(34, bytesPerSample * 8, true);

            // data sub-chunk
            writeString(view, 36, 'data');
            view.setUint32(40, pcmData.length * bytesPerSample, true);

            // PCM data
            for (let i = 0; i < pcmData.length; ++i) {
                view.setInt16(44 + i * bytesPerSample, pcmData[i], true);
            }

            return new Blob([view], { type: 'audio/wav' });

            function writeString(view, offset, string) {
                for (let i = 0; i < string.length; i++) {
                    view.setUint8(offset + i, string.charCodeAt(i));
                }
            }
        }
        
        // ฟังก์ชันจำลอง API เพื่อแปลงข้อความเป็นเสียง
        async function fetchTTS(text) {
            document.getElementById('message').textContent = 'กำลังสร้างไฟล์เสียง...';
            const audioPlayer = document.getElementById('audio-player');
            audioPlayer.src = '';
            
            // ข้อมูลจำลองสำหรับ API call
            const payload = {
                contents: [{
                    parts: [{ text: text }]
                }],
                generationConfig: {
                    responseModalities: ["AUDIO"],
                    speechConfig: {
                        voiceConfig: {
                            prebuiltVoiceConfig: { voiceName: "Zephyr" }
                        }
                    }
                },
                model: "gemini-2.5-flash-preview-tts"
            };
            const apiKey = "";
            const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-tts:generateContent?key=${apiKey}`;

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const result = await response.json();
                const part = result?.candidates?.[0]?.content?.parts?.[0];
                const audioData = part?.inlineData?.data;
                const mimeType = part?.inlineData?.mimeType;

                if (audioData && mimeType && mimeType.startsWith("audio/")) {
                    const sampleRateMatch = mimeType.match(/rate=(\d+)/);
                    const sampleRate = sampleRateMatch ? parseInt(sampleRateMatch[1], 10) : 16000;
                    
                    const pcmData = base64ToArrayBuffer(audioData);
                    const pcm16 = new Int16Array(pcmData);
                    
                    const wavBlob = pcmToWav(pcm16, sampleRate);
                    const audioUrl = URL.createObjectURL(wavBlob);
                    
                    audioPlayer.src = audioUrl;
                    audioPlayer.play();
                    document.getElementById('message').textContent = 'สร้างไฟล์เสียงสำเร็จ';
                } else {
                    document.getElementById('message').textContent = 'เกิดข้อผิดพลาดในการสร้างไฟล์เสียง';
                }

            } catch (error) {
                console.error('Error fetching TTS:', error);
                document.getElementById('message').textContent = 'เกิดข้อผิดพลาด: ' + error.message;
            }
        }

        // --- ส่วนของการจัดการไฟล์อัปโหลด ---
        
        // รับข้อมูลจาก DOM
        const audioFileInput = document.getElementById('audio-file-input');
        const audioPlayer = document.getElementById('audio-player');
        const ttsButton = document.getElementById('tts-button');
        const textInput = document.getElementById('text-input');
        const message = document.getElementById('message');

        // Event Listener สำหรับปุ่ม TTS
        ttsButton.addEventListener('click', () => {
            const text = textInput.value;
            if (text.trim() === '') {
                message.textContent = 'กรุณาพิมพ์ข้อความที่ต้องการแปลงเป็นเสียง';
                return;
            }
            fetchTTS(text);
        });

        // Event Listener สำหรับการอัปโหลดไฟล์เสียง
        audioFileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                // ตรวจสอบว่าไฟล์ที่เลือกเป็นประเภท audio หรือไม่
                if (file.type.startsWith('audio/')) {
                    message.textContent = 'กำลังโหลดไฟล์...';
                    const reader = new FileReader();

                    // เมื่อไฟล์ถูกอ่านเสร็จสมบูรณ์
                    reader.onload = (e) => {
                        // กำหนด source ของ audio player ให้เป็นข้อมูลของไฟล์
                        audioPlayer.src = e.target.result;
                        audioPlayer.style.display = 'block'; // แสดงเครื่องเล่นเสียง
                        audioPlayer.play();
                        message.textContent = `โหลดไฟล์ "${file.name}" สำเร็จแล้ว`;
                    };

                    // อ่านไฟล์ในรูปแบบ Data URL (Base64)
                    reader.readAsDataURL(file);
                } else {
                    message.textContent = 'กรุณาเลือกไฟล์เสียง (เช่น MP3, WAV)';
                    audioPlayer.src = '';
                }
            } else {
                message.textContent = 'ไม่มีไฟล์ที่ถูกเลือก';
                audioPlayer.src = '';
            }
        });
    </script>
</body>
</html>
