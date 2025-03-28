/**
 * PayFast Form Generator
 * This file helps create PayFast payment forms dynamically in the browser
 */

// Import the signature generator if using module system
// import { generateSignature } from './payfast-browser.js';

/**
 * Creates a PayFast payment form with the provided data
 * 
 * @param {Object} paymentData - The payment data
 * @param {string} passphrase - Optional PayFast passphrase
 * @param {boolean} testMode - Whether to use sandbox environment
 * @param {string} buttonText - Text for submit button
 * @param {string} buttonClass - CSS class for button styling
 * @returns {string} HTML form ready to insert into the DOM
 */
function createPayFastForm(paymentData, passphrase = null, testMode = true, buttonText = 'Pay Now', buttonClass = 'btn btn-primary') {
  // Clone the data to avoid modifying the original
  const myData = {...paymentData};
  
  // Set host based on test mode
  const pfHost = testMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
  
  // Generate signature if not already provided
  if (!myData.signature && window.generateSignature) {
    // If using the async browser version, this won't work synchronously
    // In that case, the signature should be generated server-side
    myData.signature = generateSignature(myData, passphrase);
  }
  
  // Build the HTML form
  let htmlForm = `<form action="https://${pfHost}/eng/process" method="post">`;
  
  // Add all form fields
  for (let key in myData) {
    if (myData.hasOwnProperty(key)) {
      const value = myData[key];
      if (value !== "") {
        // Escape HTML to prevent XSS
        const safeValue = String(value).trim()
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
          
        htmlForm += `<input name="${key}" type="hidden" value="${safeValue}" />`;
      }
    }
  }
  
  // Add submit button
  htmlForm += `<input type="submit" value="${buttonText}" class="${buttonClass}" />`;
  htmlForm += '</form>';
  
  return htmlForm;
}

/**
 * Example function to create and insert a PayFast form
 * 
 * @param {string} containerId - ID of container element to insert form
 */
function insertPayFastForm(containerId) {
  const container = document.getElementById(containerId);
  if (!container) return;
  
  // Example payment data
  const paymentData = {
    // Merchant details
    merchant_id: document.querySelector('meta[name="payfast-merchant-id"]')?.content || "10000100",
    merchant_key: document.querySelector('meta[name="payfast-merchant-key"]')?.content || "46f0cd694581a",
    return_url: document.querySelector('meta[name="payfast-return-url"]')?.content || window.location.origin + "/payment/success",
    cancel_url: document.querySelector('meta[name="payfast-cancel-url"]')?.content || window.location.origin + "/payment/cancel",
    notify_url: document.querySelector('meta[name="payfast-notify-url"]')?.content || window.location.origin + "/payment/notify",
    
    // Buyer details (example - these should be populated from form inputs)
    name_first: document.querySelector('#first_name')?.value || "Test",
    name_last: document.querySelector('#last_name')?.value || "User",
    email_address: document.querySelector('#email')?.value || "test@example.com",
    
    // Transaction details (example - these should be populated dynamically)
    m_payment_id: document.querySelector('meta[name="payfast-payment-id"]')?.content || "1234",
    amount: document.querySelector('meta[name="payfast-amount"]')?.content || "99.99",
    item_name: document.querySelector('meta[name="payfast-item-name"]')?.content || "Membership Plan",
    
    // Optional recurring billing
    subscription_type: document.querySelector('meta[name="payfast-subscription-type"]')?.content || "",
    billing_date: document.querySelector('meta[name="payfast-billing-date"]')?.content || "",
    recurring_amount: document.querySelector('meta[name="payfast-recurring-amount"]')?.content || "",
    frequency: document.querySelector('meta[name="payfast-frequency"]')?.content || "",
    cycles: document.querySelector('meta[name="payfast-cycles"]')?.content || ""
  };
  
  // Get passphrase and test mode from meta tags
  const passphrase = document.querySelector('meta[name="payfast-passphrase"]')?.content || null;
  const testMode = document.querySelector('meta[name="payfast-test-mode"]')?.content === "true";
  
  // Create button text
  const buttonText = document.querySelector('meta[name="payfast-button-text"]')?.content || "Pay Now";
  
  // Create the form
  const form = createPayFastForm(paymentData, passphrase, testMode, buttonText, "btn btn-primary btn-lg w-100");
  
  // Insert form into container
  container.innerHTML = form;
}

// Expose functions globally when included directly via script tag
window.createPayFastForm = createPayFastForm;
window.insertPayFastForm = insertPayFastForm; 