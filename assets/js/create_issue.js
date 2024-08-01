document.getElementById("createTicketButton").addEventListener("click", function() {
    // Make an AJAX request to your PHP script
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../api/create_issue.php", true); // Replace with the actual URL of your PHP script
    xhr.send();
});