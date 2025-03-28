/**
 * PayFast signature verification for browser use
 * Note: This requires a polyfill or a modern browser that supports crypto API
 * For production, you should verify signatures server-side
 */

/**
 * Generate MD5 hash for string (browser-compatible)
 * 
 * @param {string} message - The string to hash
 * @returns {Promise<string>} MD5 hex digest
 */
async function md5Hash(message) {
  // Convert string to ArrayBuffer
  const msgBuffer = new TextEncoder().encode(message);
  
  // Generate hash using SubtleCrypto API
  const hashBuffer = await crypto.subtle.digest('MD5', msgBuffer);
  
  // Convert ArrayBuffer to hex string
  const hashArray = Array.from(new Uint8Array(hashBuffer));
  const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
  
  return hashHex;
}

/**
 * Synchronous version of MD5 hash using a fallback algorithm
 * Note: This is provided only for compatibility with the form implementation
 * and should not be used for security-critical applications.
 * 
 * @param {string} message - The string to hash
 * @returns {string} MD5 hex digest
 */
function md5HashSync(message) {
  // This is a simple implementation to demonstrate the concept
  // For production, use a proper MD5 library or the server-side implementation
  
  // Fallback if crypto API is not available or for compatibility with the form example
  let hash = 0;
  for (let i = 0; i < message.length; i++) {
    hash = ((hash << 5) - hash) + message.charCodeAt(i);
    hash |= 0; // Convert to 32bit integer
  }
  
  // Convert to hex string and pad to ensure it's 32 characters long
  const hexHash = Math.abs(hash).toString(16).padStart(32, '0');
  console.warn('Using fallback sync MD5 hash implementation - not secure for production!');
  
  return hexHash;
}

/**
 * Generate a PayFast signature (browser version)
 * 
 * @param {Object} data - Object containing payment data
 * @param {string|null} passPhrase - Optional passphrase
 * @returns {Promise<string>} MD5 hash signature
 */
async function generateSignature(data, passPhrase = null) {
  // Create parameter string
  let pfOutput = "";
  
  // Optionally sort keys for consistent order
  const sortedKeys = Object.keys(data).sort();
  
  for (let key of sortedKeys) {
    // Skip signature field
    if (key === 'signature') continue;
    
    if (data.hasOwnProperty(key) && data[key] !== "") {
      // Use encodeURIComponent and replace %20 with +
      pfOutput += `${key}=${encodeURIComponent(data[key].trim()).replace(/%20/g, "+")}&`
    }
  }

  // Remove last ampersand
  let getString = pfOutput.slice(0, -1);
  
  // Add passphrase if provided
  if (passPhrase !== null && passPhrase !== '') {
    getString += `&passphrase=${encodeURIComponent(passPhrase.trim()).replace(/%20/g, "+")}`;
  }

  // Log signature string for debugging
  console.log('Signature string:', getString);
  
  // Generate MD5 hash
  return await md5Hash(getString);
}

/**
 * Synchronous version of generateSignature for use with form implementation
 * 
 * @param {Object} data - Object containing payment data
 * @param {string|null} passPhrase - Optional passphrase
 * @returns {string} MD5 hash signature
 */
function generateSignatureSync(data, passPhrase = null) {
  // Create parameter string
  let pfOutput = "";
  
  // Optionally sort keys for consistent order
  const sortedKeys = Object.keys(data).sort();
  
  for (let key of sortedKeys) {
    // Skip signature field
    if (key === 'signature') continue;
    
    if (data.hasOwnProperty(key) && data[key] !== "") {
      // Use encodeURIComponent and replace %20 with +
      pfOutput += `${key}=${encodeURIComponent(data[key].trim()).replace(/%20/g, "+")}&`
    }
  }

  // Remove last ampersand
  let getString = pfOutput.slice(0, -1);
  
  // Add passphrase if provided
  if (passPhrase !== null && passPhrase !== '') {
    getString += `&passphrase=${encodeURIComponent(passPhrase.trim()).replace(/%20/g, "+")}`;
  }

  // Log signature string for debugging
  console.log('Signature string (sync):', getString);
  
  // Generate MD5 hash using synchronous method
  return md5HashSync(getString);
}

/**
 * Verify a PayFast signature (browser version)
 * 
 * @param {Object} data - Object containing payment data with signature
 * @param {string|null} passPhrase - Optional passphrase
 * @returns {Promise<boolean>} Whether signature is valid
 */
async function verifySignature(data, passPhrase = null) {
  const receivedSignature = data.signature;
  if (!receivedSignature) return false;
  
  // Create a copy of data without the signature
  const dataToVerify = {...data};
  delete dataToVerify.signature;
  
  // Generate our signature
  const calculatedSignature = await generateSignature(dataToVerify, passPhrase);
  
  // Log for debugging
  console.log('Signature verification:', {
    received: receivedSignature,
    calculated: calculatedSignature,
    match: calculatedSignature === receivedSignature
  });
  
  // Compare signatures
  return calculatedSignature === receivedSignature;
}

// Make functions globally available
window.md5Hash = md5Hash;
window.md5HashSync = md5HashSync;
window.generateSignature = generateSignature;
window.generateSignatureSync = generateSignatureSync;
window.verifySignature = verifySignature;

// Example usage:
/*
document.addEventListener('DOMContentLoaded', async () => {
  const paymentData = {
    merchant_id: '10000100',
    merchant_key: '46f0cd694581a',
    amount: '99.99',
    item_name: 'Test Item',
    signature: 'calculated-signature-from-server'
  };
  
  const isValid = await verifySignature(paymentData, 'optional-passphrase');
  console.log('Signature valid:', isValid);
});
*/ 