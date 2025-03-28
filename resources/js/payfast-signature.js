/**
 * PayFast signature generation for client-side validation
 * This matches the server-side PHP implementation in PayFastService.php
 */

const crypto = require("crypto");

/**
 * Generate a PayFast signature matching the server implementation
 * 
 * @param {Object} data - Object containing payment data
 * @param {string|null} passPhrase - Optional passphrase
 * @returns {string} MD5 hash signature
 */
const generateSignature = (data, passPhrase = null) => {
  // Create parameter string
  let pfOutput = "";
  
  // Sort keys to ensure consistent order with PHP
  const sortedKeys = Object.keys(data).sort();
  
  for (let key of sortedKeys) {
    // Skip signature field
    if (key === 'signature') continue;
    
    if (data.hasOwnProperty(key) && data[key] !== "") {
      // Use encodeURIComponent and replace %20 with + for consistency
      pfOutput += `${key}=${encodeURIComponent(data[key].trim()).replace(/%20/g, "+")}&`
    }
  }

  // Remove last ampersand
  let getString = pfOutput.slice(0, -1);
  
  // Add passphrase if provided
  if (passPhrase !== null && passPhrase !== '') {
    getString += `&passphrase=${encodeURIComponent(passPhrase.trim()).replace(/%20/g, "+")}`;
  }

  // Log for debugging
  console.log('Signature string:', getString);
  
  // Generate MD5 hash
  return crypto.createHash("md5").update(getString).digest("hex");
};

/**
 * Verify a PayFast signature
 * 
 * @param {Object} data - Object containing payment data with signature
 * @param {string|null} passPhrase - Optional passphrase
 * @returns {boolean} Whether signature is valid
 */
const verifySignature = (data, passPhrase = null) => {
  const receivedSignature = data.signature;
  if (!receivedSignature) return false;
  
  // Create a copy of data without the signature
  const dataToVerify = {...data};
  delete dataToVerify.signature;
  
  // Generate our signature
  const calculatedSignature = generateSignature(dataToVerify, passPhrase);
  
  // Log for debugging
  console.log('Signature verification:', {
    received: receivedSignature,
    calculated: calculatedSignature,
    match: calculatedSignature === receivedSignature
  });
  
  // Compare signatures
  return calculatedSignature === receivedSignature;
};

// Export functions
module.exports = {
  generateSignature,
  verifySignature
}; 