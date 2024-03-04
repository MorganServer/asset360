    function exportToPDF() {
        // Send an AJAX request to a PHP script to generate the PDF
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "generate_pdf.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.responseType = "blob"; // Response type is blob for binary data
        xhr.onload = function() {
            if (this.status === 200) {
                // Create a blob URL from the response
                var blob = new Blob([this.response], { type: "application/pdf" });
                var url = window.URL.createObjectURL(blob);
                // Create a link element and trigger the download
                var a = document.createElement("a");
                a.href = url;
                a.download = "asset_inventory.pdf";
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            }
        };
        xhr.send();
    }

