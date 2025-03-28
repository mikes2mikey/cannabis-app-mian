import { Html5Qrcode } from 'html5-qrcode';
import axios from 'axios';

document.addEventListener('alpine:init', () => {
    Alpine.data('qrScanner', () => ({
        isScanning: false,
        scanResult: null,
        error: null,
        html5QrCode: null,
        processing: false,
        user: null,
        userProfile: null,
        
        init() {
            this.html5QrCode = new Html5Qrcode("reader");
        },
        
        async startScanner() {
            this.error = null;
            this.scanResult = null;
            this.user = null;
            this.userProfile = null;
            
            try {
                const qrCodeSuccessCallback = async (decodedText, decodedResult) => {
                    // Handle the QR code result here
                    this.scanResult = decodedText;
                    
                    // Stop the scanner once we have a result
                    await this.stopScanner();
                    
                    // Process the result
                    try {
                        this.processing = true;
                        await this.processQrCode(decodedText);
                        this.processing = false;
                    } catch (err) {
                        this.processing = false;
                        this.error = "Error processing QR code: " + err.message;
                    }
                };
                
                const config = { 
                    fps: 10, 
                    qrbox: { width: 300, height: 300 } 
                };
                
                await this.html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    qrCodeSuccessCallback
                );
                
                this.isScanning = true;
            } catch (err) {
                this.error = "Error starting scanner: " + err.message;
            }
        },
        
        async stopScanner() {
            if (this.html5QrCode && this.isScanning) {
                try {
                    await this.html5QrCode.stop();
                    this.isScanning = false;
                } catch (err) {
                    this.error = "Error stopping scanner: " + err.message;
                }
            }
        },
        
        async processQrCode(qrCodeData) {
            try {
                // Send the QR code data to the server
                const response = await axios.post('/verify-qr-code', {
                    qr_code_data: qrCodeData
                });
                
                // Handle the response
                if (response.data.user) {
                    this.user = response.data.user;
                    this.userProfile = response.data.user_profile || null;
                }
            } catch (err) {
                throw new Error(err.response?.data?.message || "Failed to process QR code");
            }
        }
    }));
}); 