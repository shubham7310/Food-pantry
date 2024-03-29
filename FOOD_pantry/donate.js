function submitForm() {
  // Perform form validation
  if (validateForm()) {
    // Submit the form (not implemented in this function)
    // If the form submission is successful, redirect to thank you page
    if (confirm("Confirm donation?")) {
      window.location.href = "thank_you_page.php"; // Change "thank_you_page.php" to your desired thank you page
    }
  }
}

function validateForm() {
  var fullName = document.getElementById("fullName").value;
  var phoneNumber = document.getElementById("phoneNumber").value;
  var alternatePhoneNumber = document.getElementById("alternatePhoneNumber").value;
  var address = document.getElementById("address").value;
  var city = document.getElementById("city").value;
  var state = document.getElementById("state").value;
  var pinCode = document.getElementById("pinCode").value;
  var NearbyStation = document.getElementById("NearbyStation").value;
  var foodName = document.getElementById("foodName").value;
  var quantity = document.getElementById("quantity").value;
  var foodPicture = document.getElementById("foodPicture").value;

  // Simple validation - ensure required fields are not empty
  if (fullName === "" || phoneNumber === "" || address === "" || city === "" || state === "" || pinCode === "" || NearbyStation === "" || foodName === "" || quantity === "" || foodPicture === "") {
    alert("Please fill out all required fields.");
    return false;
  }

  // Validate phone number format
  var phoneRegex = /^[0-9]{10}$/;
  if (!phoneRegex.test(phoneNumber)) {
    alert("Please enter a valid phone number.");
    return false;
  }

  // Validate alternate phone number if provided
  if (alternatePhoneNumber !== "" && !phoneRegex.test(alternatePhoneNumber)) {
    alert("Please enter a valid alternate phone number.");
    return false;
  }

  // Validate pin code format
  var pinCodeRegex = /^[0-9]{6}$/;
  if (!pinCodeRegex.test(pinCode)) {
    alert("Please enter a valid pin code.");
    return false;
  }

  // Validate quantity format
  var quantityRegex = /^[1-9][0-9]*$/; // Ensures quantity is a positive integer
  if (!quantityRegex.test(quantity)) {
    alert("Please enter a valid quantity (must be a positive integer).");
    return false;
  }

  return true; // Return true if all validations pass
}
