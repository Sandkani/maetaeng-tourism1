<?php
/**
 * This file is an example of a page that displays tourist location details
 * with an interactive map and a simulated animation, now with added voiceover.
 * This version includes a fix for the foreign key constraint issue by ensuring
 * that the table names match the database schema. It also fixes the core problem
 * by joining with the 'media' table to retrieve images and videos.
 *
 * MODIFICATION: This version adds a debug mode to help identify issues with
 * media URLs by displaying the raw data from the database.
 */

// Start a session to manage user state (simulated)
session_start();

// --- Real Database Connection ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "maetaeng_tourism";

// Create a new MySQLi object to connect to the database.
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors and terminate the script if an error occurs.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8 to handle Thai characters properly.
$conn->set_charset("utf8mb4");

$location = null;
$media = [];
$comments = [];
$id = null;
$error_message = ''; // Variable to store error messages
$debug_data = null; // Variable to hold debug data

// Check if an ID value has been sent via the URL parameter.
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // --- Validate if the location exists before proceeding ---
    $sql_check_location = "SELECT id FROM location1 WHERE id = ?";
    if ($stmt_check = $conn->prepare($sql_check_location)) {
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        // If the location exists, fetch its data and update statistics.
        if ($result_check->num_rows > 0) {
            
            // CORRECTED: Query the `location1`, `category`, and `media` tables using LEFT JOIN to get all related data.
            // A LEFT JOIN is used so that the location data will still be retrieved even if it has no associated media.
            $sql = "SELECT l.*, c.name AS category_name, m.type, m.url FROM location1 l 
                    LEFT JOIN category c ON l.category_id = c.id
                    LEFT JOIN media m ON l.id = m.location_id 
                    WHERE l.id = ?";
            
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    // Fetch all rows from the join and organize them.
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    
                    // The main location data will be the same in all rows, so we take the first one.
                    $location = $rows[0];
                    
                    // Collect all media for this location from the joined results.
                    foreach ($rows as $row) {
                        if (!empty($row['type']) && !empty($row['url'])) {
                            $media[] = [
                                'type' => $row['type'],
                                'url' => $row['url']
                            ];
                        }
                    }

                    // For debugging, store the raw query result
                    if (isset($_GET['debug']) && $_GET['debug'] == 'true') {
                        $debug_data = $rows;
                    }

                }
                $stmt->close();
            } else {
                $error_message = "Error preparing statement: " . $conn->error;
            }

            // Now that we've confirmed the location exists, we can safely update statistics.
            $sql_update_views = "INSERT INTO statistic (locationID, viewCount) VALUES (?, 1) ON DUPLICATE KEY UPDATE viewCount = viewCount + 1";
            try {
                $stmt_views = $conn->prepare($sql_update_views);
                if ($stmt_views) {
                    $stmt_views->bind_param("i", $id);
                    $stmt_views->execute();
                    $stmt_views->close();
                }
            } catch (mysqli_sql_exception $e) {
                // Log the error but don't show it to the user.
                error_log("Failed to update statistic for location ID $id: " . $e->getMessage());
            }

            // Retrieve comments data using a prepared statement.
            $sql_comments = "SELECT cm.commentText, u.username FROM comment cm JOIN User u ON cm.userID = u.userID WHERE cm.location_id = ?";
            $stmt_comments = $conn->prepare($sql_comments);
            if ($stmt_comments) {
                $stmt_comments->bind_param("i", $id);
                $stmt_comments->execute();
                $result_comments = $stmt_comments->get_result();
                $comments = $result_comments->fetch_all(MYSQLI_ASSOC);
                $stmt_comments->close();
            }
        } else {
            $error_message = "ไม่พบสถานที่ท่องเที่ยวที่คุณต้องการ";
        }
        $stmt_check->close();
    }
}

// Check if the comment form has been submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['location_id']) && isset($_POST['comment'])) {
    // Assume a user is logged in (use user_id=1 as we've ensured it exists).
    $userId = 1; 
    $locationId = $_POST['location_id'];
    $comment = $_POST['comment'];
    $commentDate = date('Y-m-d'); // Current date

    // Check if the location exists before adding a comment to prevent foreign key errors.
    $sql_check_location = "SELECT id FROM location1 WHERE id = ?";
    if ($stmt_check = $conn->prepare($sql_check_location)) {
        $stmt_check->bind_param("i", $locationId);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows > 0) {
            // Location exists, now attempt to insert the comment.
            $sql_insert_comment = "INSERT INTO comment (location_id, userID, commentText, commentDate) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert_comment);
            if ($stmt_insert) {
                $stmt_insert->bind_param("iiss", $locationId, $userId, $comment, $commentDate);
                if ($stmt_insert->execute()) {
                    // Redirect to the same page after successful submission to prevent re-submission.
                    header("Location: location_detail.php?id=" . $locationId);
                    exit();
                } else {
                    $error_message = "เกิดข้อผิดพลาดในการบันทึกความคิดเห็น: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            } else {
                $error_message = "เกิดข้อผิดพลาดในการเตรียม Statement: " . $conn->error;
            }
        } else {
            $error_message = "ไม่พบสถานที่ที่คุณต้องการแสดงความคิดเห็น";
        }
        $stmt_check->close();
    } else {
        $error_message = "เกิดข้อผิดพลาดในการเตรียม Statement สำหรับการตรวจสอบสถานที่: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $location ? htmlspecialchars($location['name']) : 'ไม่พบสถานที่'; ?> - เที่ยวแม่แตง</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<body class="font-kanit bg-gray-100 p-4 min-h-screen flex flex-col">
    <header class="bg-indigo-600 text-white p-6 shadow-md rounded-xl">
        <div class="container mx-auto flex flex-col sm:flex-row justify-between items-center">
            <a href="index.php" class="text-3xl font-bold mb-4 sm:mb-0">🏞️ เที่ยวแม่แตง</a>
            <nav>
                <a href="index.php" class="bg-white text-indigo-600 py-2 px-4 rounded-full font-bold hover:bg-gray-200 transition-colors">กลับหน้าหลัก</a>
            </nav>
        </div>
    </header>
    <main class="container mx-auto mt-8 p-4 flex-grow">
        <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <p class="font-bold">เกิดข้อผิดพลาด!</p>
                <p class="text-sm"><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <?php if ($location): ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Main content column -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                        <h1 class="text-4xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($location['name']); ?></h1>
                        <div class="mt-4 flex items-center text-gray-500">
                             <?php if (isset($location['category_name'])): ?>
                                 <span class="bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">
                                     <?php echo htmlspecialchars($location['category_name']); ?>
                                 </span>
                             <?php endif; ?>
                        </div>
                        
                        <!-- NEW: Display images and videos from the media table -->
                        <?php if (!empty($media)): ?>
                            <div class="mt-6 space-y-4">
                                <?php foreach ($media as $item): ?>
                                     <?php if ($item['type'] === 'image'): ?>
                                         <div class="rounded-lg overflow-hidden shadow-lg">
                                             <img src="<?php echo htmlspecialchars($item['url']); ?>" alt="ภาพ <?php echo htmlspecialchars($location['name']); ?>" class="w-full h-auto object-cover" onerror="this.onerror=null;this.src='https://placehold.co/800x600/E0E7FF/4338CA?text=ไม่พบรูปภาพ';">
                                         </div>
                                     <?php elseif ($item['type'] === 'video'): ?>
                                         <div class="relative w-full aspect-video rounded-lg overflow-hidden shadow-inner">
                                             <iframe class="w-full h-full absolute top-0 left-0" src="<?php echo htmlspecialchars($item['url']); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                         </div>
                                     <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="mt-6 text-center text-gray-400">
                                <p class="mt-2">ไม่พบสื่อ (รูปภาพ/วิดีโอ) สำหรับสถานที่นี้</p>
                            </div>
                        <?php endif; ?>

                        <p id="locationDescription" class="text-gray-700 mt-6 leading-relaxed"><?php echo nl2br(htmlspecialchars($location['description'])); ?></p>
                        
                        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <button id="playVoiceoverBtn" class="bg-blue-600 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:bg-blue-700 transition-colors">
                                <span id="voiceoverText">ฟังเสียงบรรยาย</span>
                            </button>
                            <button id="showCartoonBtn" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:bg-indigo-700 transition-colors">
                                แสดงการ์ตูน!
                            </button>
                            <img id="cartoonImage" src="https://cdn.pixabay.com/animation/2023/02/18/22/32/22-32-36-879_512.gif" alt="Animated Cartoon" class="mt-4 mx-auto hidden w-48 h-48 rounded-lg" />
                            <audio id="audioPlayer" class="mt-4 w-full sm:w-auto hidden" controls>
                                <!-- Audio source will be dynamically loaded from the API -->
                                เบราว์เซอร์ของคุณไม่รองรับการเล่นไฟล์เสียง
                            </audio>
                        </div>
                    </div>
                </div>

                <!-- Map and Comments column -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                        <h2 class="text-2xl font-bold mb-4 text-gray-800">แผนที่และพิกัด</h2>
                        <?php if (isset($location['latitude']) && isset($location['longitude'])): ?>
                            <div class="relative w-full h-80 rounded-lg overflow-hidden shadow-inner">
                                <iframe
                                    width="100%"
                                    height="100%"
                                    frameborder="0"
                                    style="border:0"
                                    src="https://maps.google.com/maps?q=<?php echo htmlspecialchars($location['latitude']); ?>,<?php echo htmlspecialchars($location['longitude']); ?>&z=15&output=embed"
                                    allowfullscreen>
                                </iframe>
                            </div>
                            <div class="text-center text-gray-700 mt-4">
                                <p><strong>ละติจูด:</strong> <?php echo htmlspecialchars($location['latitude']); ?></p>
                                <p><strong>ลองจิจูด:</strong> <?php echo htmlspecialchars($location['longitude']); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-gray-500 italic">
                                **พิกัดไม่พร้อมใช้งาน**
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-bold mb-4">ความคิดเห็น</h2>
                        <?php if (count($comments) > 0): ?>
                            <div class="space-y-4">
                                <?php foreach ($comments as $comment): ?>
                                    <div class="border-b pb-4 last:border-b-0">
                                        <p class="text-gray-800 font-semibold"><?php echo htmlspecialchars($comment['username']); ?></p>
                                        <p class="text-gray-700 mt-1"><?php echo htmlspecialchars($comment['commentText']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 italic">ยังไม่มีความคิดเห็นสำหรับสถานที่นี้</p>
                        <?php endif; ?>
                        
                        <!-- Form for adding comments -->
                        <form action="location_detail.php" method="POST" class="mt-6">
                            <input type="hidden" name="location_id" value="<?php echo htmlspecialchars($id); ?>">
                            <h3 class="text-xl font-semibold mb-2">เพิ่มความคิดเห็นของคุณ</h3>
                            <div class="mb-4">
                                <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">ความคิดเห็น:</label>
                                <textarea id="comment" name="comment" rows="4" required class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700"></textarea>
                            </div>
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">ส่งความคิดเห็น</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- New: Debug Section -->
            <?php if (isset($debug_data)): ?>
                <div class="bg-gray-800 text-white rounded-lg shadow-lg p-6 mt-8">
                    <h2 class="text-2xl font-bold mb-4 text-yellow-300">🚨 โหมดดีบัก (Debug Mode)</h2>
                    <p class="mb-4 text-gray-300">ข้อมูลด้านล่างนี้คือผลลัพธ์ดิบที่ดึงมาจากฐานข้อมูล ซึ่งจะช่วยให้คุณตรวจสอบว่า URL ของรูปภาพและวิดีโอถูกต้องหรือไม่</p>
                    <pre class="bg-gray-900 p-4 rounded-lg overflow-x-auto text-sm">
                        <?php echo htmlspecialchars(json_encode($debug_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
                    </pre>
                    <p class="mt-4 text-gray-400 text-sm italic">ถ้าช่อง "url" ว่างเปล่าหรือเส้นทางผิดพลาด นี่คือสาเหตุที่รูปภาพไม่แสดงผล</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center py-10">
                <h2 class="text-2xl text-red-500">ไม่พบสถานที่ท่องเที่ยว</h2>
                <a href="index.php" class="text-indigo-500 mt-4 inline-block hover:underline">กลับหน้าหลัก</a>
            </div>
        <?php endif; ?>
    </main>
    <footer class="bg-gray-800 text-white p-4 text-center mt-8 rounded-lg">
        <p>&copy; 2024 เที่ยวแม่แตง. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const showCartoonBtn = document.getElementById('showCartoonBtn');
            const cartoonImage = document.getElementById('cartoonImage');
            const playVoiceoverBtn = document.getElementById('playVoiceoverBtn');
            const voiceoverText = document.getElementById('voiceoverText');
            const audioPlayer = document.getElementById('audioPlayer');
            const locationDescription = document.getElementById('locationDescription');
            const apiKey = "";
            const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-tts:generateContent?key=${apiKey}`;
            const API_RETRY_TIMEOUT_MS = 2000;
            const MAX_RETRIES = 5;

            // Helper function to convert base64 to ArrayBuffer
            function base64ToArrayBuffer(base64) {
                const binaryString = atob(base64);
                const len = binaryString.length;
                const bytes = new Uint8Array(len);
                for (let i = 0; i < len; i++) {
                    bytes[i] = binaryString.charCodeAt(i);
                }
                return bytes.buffer;
            }

            // Helper function to convert PCM audio to WAV format
            function pcmToWav(pcmData, sampleRate) {
                const numChannels = 1;
                const bitDepth = 16;
                const bytesPerSample = bitDepth / 8;
                const blockAlign = numChannels * bytesPerSample;
                const byteRate = sampleRate * blockAlign;

                const wavHeader = new ArrayBuffer(44);
                const headerView = new DataView(wavHeader);

                // RIFF chunk
                writeString(headerView, 0, 'RIFF');
                headerView.setUint32(4, 36 + pcmData.byteLength, true);
                writeString(headerView, 8, 'WAVE');

                // fmt chunk
                writeString(headerView, 12, 'fmt ');
                headerView.setUint32(16, 16, true);
                headerView.setUint16(20, 1, true); // Audio format (1 for PCM)
                headerView.setUint16(22, numChannels, true);
                headerView.setUint32(24, sampleRate, true);
                headerView.setUint32(28, byteRate, true);
                headerView.setUint16(32, blockAlign, true);
                headerView.setUint16(34, bitDepth, true);

                // data chunk
                writeString(headerView, 36, 'data');
                headerView.setUint32(40, pcmData.byteLength, true);

                const wavBlob = new Blob([wavHeader, pcmData], { type: 'audio/wav' });
                return wavBlob;
            }

            function writeString(view, offset, string) {
                for (let i = 0; i < string.length; i++) {
                    view.setUint8(offset + i, string.charCodeAt(i));
                }
            }
            
            async function getVoiceover(text, retries = 0) {
                const payload = {
                    contents: [{
                        parts: [{ text: text }]
                    }],
                    generationConfig: {
                        responseModalities: ["AUDIO"],
                        speechConfig: {
                            voiceConfig: {
                                prebuiltVoiceConfig: { voiceName: "Iapetus" }
                            }
                        }
                    },
                    model: "gemini-2.5-flash-preview-tts"
                };

                try {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        if (response.status === 429 && retries < MAX_RETRIES) {
                            await new Promise(resolve => setTimeout(resolve, API_RETRY_TIMEOUT_MS * (2 ** retries)));
                            return getVoiceover(text, retries + 1);
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    const part = result?.candidates?.[0]?.content?.parts?.[0];
                    const audioData = part?.inlineData?.data;
                    const mimeType = part?.inlineData?.mimeType;

                    if (audioData && mimeType && mimeType.startsWith("audio/L16")) {
                        const sampleRateMatch = mimeType.match(/rate=(\d+)/);
                        const sampleRate = sampleRateMatch ? parseInt(sampleRateMatch[1], 10) : 16000;
                        const pcmData = base64ToArrayBuffer(audioData);
                        const pcm16 = new Int16Array(pcmData);
                        const wavBlob = pcmToWav(pcm16, sampleRate);
                        return URL.createObjectURL(wavBlob);
                    } else {
                        console.error('API response is not in expected format or is missing audio data.');
                        return null;
                    }

                } catch (error) {
                    console.error('Error generating voiceover:', error);
                    return null;
                }
            }

            if (playVoiceoverBtn && audioPlayer && locationDescription) {
                let audioUrl = null;
                let isGenerating = false;

                playVoiceoverBtn.addEventListener('click', async () => {
                    if (isGenerating) {
                        return;
                    }

                    if (audioUrl) {
                        audioPlayer.play();
                    } else {
                        isGenerating = true;
                        playVoiceoverBtn.disabled = true;
                        voiceoverText.textContent = 'กำลังโหลด...';

                        const textToSynthesize = locationDescription.textContent.trim();
                        if (textToSynthesize) {
                            audioUrl = await getVoiceover(textToSynthesize);
                            if (audioUrl) {
                                audioPlayer.src = audioUrl;
                                audioPlayer.play();
                                audioPlayer.classList.remove('hidden');
                            } else {
                                voiceoverText.textContent = 'เกิดข้อผิดพลาด';
                            }
                        }
                        isGenerating = false;
                        playVoiceoverBtn.disabled = false;
                        voiceoverText.textContent = 'ฟังเสียงบรรยาย';
                    }
                });
            }

            if (showCartoonBtn && cartoonImage) {
                showCartoonBtn.addEventListener('click', () => {
                    cartoonImage.classList.remove('hidden');
                    setTimeout(() => {
                        cartoonImage.classList.add('hidden');
                    }, 2000);
                });
            }

            audioPlayer.addEventListener('ended', () => {
                // Reset button text after audio ends
                voiceoverText.textContent = 'ฟังเสียงบรรยาย';
            });
            audioPlayer.addEventListener('play', () => {
                voiceoverText.textContent = 'กำลังเล่น...';
            });
            audioPlayer.addEventListener('pause', () => {
                voiceoverText.textContent = 'หยุดชั่วคราว';
            });
        });
    </script>
</body>
</html>
