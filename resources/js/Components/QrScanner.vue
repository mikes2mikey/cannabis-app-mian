<template>
    <div class="qr-scanner">
        <div class="scanner-container mb-4">
            <div id="reader" class="w-full h-auto"></div>
        </div>
        <div v-if="scanResult" class="scan-result bg-green-100 border border-green-400 p-4 rounded mb-4">
            <h3 class="text-lg font-semibold text-green-800">QR Code Detected!</h3>
            <p class="text-green-700">Processing...</p>
        </div>
        <div v-if="error" class="error-message bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4">
            {{ error }}
        </div>
        <div class="scanner-controls flex">
            <button 
                @click="startScanner" 
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2"
                :disabled="isScanning"
            >
                Start Scanner
            </button>
            <button 
                @click="stopScanner" 
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                :disabled="!isScanning"
            >
                Stop Scanner
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Html5Qrcode } from 'html5-qrcode';
import axios from 'axios';

const props = defineProps({
    scannerWidth: {
        type: Number,
        default: 500
    },
    scannerHeight: {
        type: Number,
        default: 500
    }
});

const isScanning = ref(false);
const scanResult = ref(null);
const error = ref(null);
let html5QrCode = null;

onMounted(() => {
    html5QrCode = new Html5Qrcode("reader");
});

onBeforeUnmount(() => {
    stopScanner();
});

const startScanner = async () => {
    error.value = null;
    scanResult.value = null;
    
    try {
        const qrCodeSuccessCallback = async (decodedText, decodedResult) => {
            // Handle the QR code result here
            scanResult.value = decodedText;
            
            // Stop the scanner once we have a result
            await stopScanner();
            
            // Process the result
            try {
                await processQrCode(decodedText);
            } catch (err) {
                error.value = "Error processing QR code: " + err.message;
            }
        };
        
        const config = { 
            fps: 10, 
            qrbox: { width: props.scannerWidth * 0.6, height: props.scannerHeight * 0.6 } 
        };
        
        await html5QrCode.start(
            { facingMode: "environment" },
            config,
            qrCodeSuccessCallback
        );
        
        isScanning.value = true;
    } catch (err) {
        error.value = "Error starting scanner: " + err.message;
    }
};

const stopScanner = async () => {
    if (html5QrCode && isScanning.value) {
        try {
            await html5QrCode.stop();
            isScanning.value = false;
        } catch (err) {
            error.value = "Error stopping scanner: " + err.message;
        }
    }
};

const processQrCode = async (qrCodeData) => {
    try {
        // Send the QR code data to the server
        const response = await axios.post('/verify-qr-code', {
            qr_code_data: qrCodeData
        });
        
        // Handle the response (redirect or show user information)
        if (response.data.redirect) {
            window.location.href = response.data.redirect;
        } else if (response.data.user) {
            // You could emit an event to the parent component with the user data
            // or handle it directly here
            scanResult.value = response.data.user;
        }
    } catch (err) {
        throw new Error(err.response?.data?.message || "Failed to process QR code");
    }
};
</script>

<style>
.qr-scanner {
    width: 100%;
}
#reader {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 8px;
    overflow: hidden;
}
</style> 