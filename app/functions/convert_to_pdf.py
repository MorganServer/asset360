import sys
import pdfkit

if len(sys.argv) != 2:
    print("Usage: python convert_to_pdf.py <input_html_file>")
    sys.exit(1)

input_html_file = sys.argv[1]
output_pdf_file = 'asset_data.pdf'

try:
    pdfkit.from_file(input_html_file, output_pdf_file)
    sys.exit(0)
except Exception as e:
    print("Error converting HTML to PDF:", e)
    sys.exit(1)