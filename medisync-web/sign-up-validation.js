document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const emailInput = form.querySelector("input[name='Email']");
    const phoneInput = form.querySelector("input[name='Phone']");
    const pincodeInput = form.querySelector("input[name='Pincode']");
    const passwordInput = form.querySelector("input[name='Password']");
    const confirmPasswordInput = form.querySelector("input[name='ConfPassword']");
  
    form.addEventListener("submit", (event) => {
      let isValid = true;
  
      // Validate Phone Number (only numbers)
      if (!/^[0-9]+$/.test(phoneInput.value)) {
        alert("Phone number must contain only numbers.");
        isValid = false;
      }
  
      // Validate Pincode (only numbers)
      if (!/^[0-9]+$/.test(pincodeInput.value)) {
        alert("Pincode must contain only numbers.");
        isValid = false;
      }
  
      // Validate Password Match
      if (passwordInput.value !== confirmPasswordInput.value) {
        alert("Passwords do not match.");
        isValid = false;
      }
  
      // Prevent form submission if validation fails
      if (!isValid) {
        event.preventDefault();
        location.href = 'sign-up.html';
      }
    });
  });
  