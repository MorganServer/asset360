// Get all error message elements
var errorMessages = document.querySelectorAll('.alert-danger');
    
// Function to adjust the height of the main container based on error messages
function adjustMainHeight() {
  var main = document.querySelector('.main');
  var totalHeight = 450;
  errorMessages.forEach(function(message) {
    // Calculate total height by adding the height of each error message
    totalHeight += message.clientHeight;
  });
  // Set the height of the main container to accommodate the error messages
  main.style.height = (totalHeight + 20) + 'px'; // Add a buffer for spacing
}

// Initial adjustment on page load
adjustMainHeight();

// Listen for changes in error messages and readjust height
errorMessages.forEach(function(message) {
  new MutationObserver(adjustMainHeight).observe(message, { attributes: true });
});