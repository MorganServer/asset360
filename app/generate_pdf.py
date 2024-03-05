import mysql.connector
from reportlab.lib.pagesizes import letter
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.lib import colors

# MySQL database credentials
db_config = {
    'host': 'localhost',
    'user': 'dbuser',
    'password': 'DBuser123!',
    'database': 'asset_management'
}

# Function to fetch data from the database
def fetch_data():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    query = "SELECT * FROM assets"
    cursor.execute(query)
    data = cursor.fetchall()
    conn.close()
    return data

# Function to generate PDF
def generate_pdf(data):
    pdf_filename = 'asset_data.pdf'
    doc = SimpleDocTemplate(pdf_filename, pagesize=letter)
    table_data = [list(data[0].keys())] + [[str(value) for value in row.values()] for row in data]
    table = Table(table_data)

    # Table style
    style = TableStyle([('BACKGROUND', (0, 0), (-1, 0), colors.gray),
                        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
                        ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
                        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
                        ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
                        ('BACKGROUND', (0, 1), (-1, -1), colors.beige),
                        ('GRID', (0, 0), (-1, -1), 1, colors.black)])

    table.setStyle(style)

    # Build PDF
    doc.build([table])

    print(f"PDF generated successfully: {pdf_filename}")

if __name__ == "__main__":
    data = fetch_data()
    if data:
        generate_pdf(data)
    else:
        print("No data found.")