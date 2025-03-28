<div x-data="qrScanner" class="qr-scanner-component">
    <div class="mb-6 p-6 bg-white rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">QR Code Scanner</h3>
        
        <div class="scanner-container mb-4">
            <div id="reader" class="w-full h-auto"></div>
        </div>
        
        <template x-if="processing">
            <div class="scan-result bg-blue-100 border border-blue-400 p-4 rounded mb-4">
                <h3 class="text-lg font-semibold text-blue-800">QR Code Detected!</h3>
                <p class="text-blue-700">Processing...</p>
            </div>
        </template>
        
        <template x-if="user">
            <div class="scan-result bg-green-100 border border-green-400 p-4 rounded mb-4">
                <h3 class="text-lg font-semibold text-green-800">User Found!</h3>
                <div class="mt-2">
                    <p><strong>Name:</strong> <span x-text="user.name"></span></p>
                    <p><strong>Email:</strong> <span x-text="user.email"></span></p>
                    <p><strong>Role:</strong> <span x-text="user.role"></span></p>
                    <template x-if="userProfile">
                        <div>
                            <p><strong>Phone:</strong> <span x-text="userProfile.phone || 'Not provided'"></span></p>
                            <p><strong>Address:</strong> <span x-text="userProfile.address || 'Not provided'"></span></p>
                        </div>
                    </template>
                    
                    <div class="mt-3">
                        <a :href="'/admin/users/' + user.id" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </template>
        
        <template x-if="error">
            <div class="error-message bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4" x-text="error">
            </div>
        </template>
        
        <div class="scanner-controls flex">
            <button 
                @click="startScanner" 
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2"
                :disabled="isScanning"
                :class="{'opacity-50 cursor-not-allowed': isScanning}"
            >
                Start Scanner
            </button>
            <button 
                @click="stopScanner" 
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                :disabled="!isScanning"
                :class="{'opacity-50 cursor-not-allowed': !isScanning}"
            >
                Stop Scanner
            </button>
        </div>
    </div>
</div>

<style>
    #reader {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
    }
</style> 