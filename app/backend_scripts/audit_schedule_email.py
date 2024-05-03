import mysql.connector
from datetime import date
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import random
import string

# Database connection configuration
db_config = {
    'host': 'localhost',
    'user': 'dbuser',
    'password': 'DBuser123!',
    'database': 'asset_management'
}

# Email configuration
email_config = {
    'sender_email': 'garrett.morgan.pro@gmail.com',
    'receiver_email': 'garrett.morgan@morganserver.com',
    'smtp_server': 'smtp.gmail.com',
    'smtp_port': 587,
    'smtp_username': 'garrett.morgan.pro@gmail.com',
    'smtp_password': 'vvbl gien cbrg zxrf'
}

# Function to generate random ID number
def generate_random_id():
    return ''.join(random.choices(string.digits, k=6))

# Connect to MySQL database
try:
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    # Get today's date
    today = date.today()
    formatted_today = today.strftime('%Y-%m-%d')

    # Query to find assets with audit_schedule set to today's date
    query = "SELECT * FROM assets WHERE audit_schedule = %s"
    cursor.execute(query, (formatted_today,))

    assets = cursor.fetchall()

    # If assets found, send an email and create notifications
    if assets:
        # Compose email message
        subject = "Assets with Audit Scheduled for Today"
        body = f"""\
        <html>
          <head></head>
          <body>
            <p>The following assets have an audit scheduled for {formatted_today}:</p>
            <table border="1">
              <tr>
                <th>Asset Tag</th>
                <th>Asset Name</th>
                <th>Link</th>
              </tr>
        """
        for asset in assets:
            body += f"""
              <tr>
                <td>{asset[2]}</td>
                <td>{asset[3]}</td>
                <td><a href='https://asset360.morganserver.com/asset/view/?id={asset[0]}'>Perform</a></td>
              </tr>
            """

            # Insert notification into notifications table
            notification_id = generate_random_id()
            notification_details = f"{asset[2]} awaiting audit."
            assigned_asset_tag_no = asset[2]
            insert_query = "INSERT INTO notifications (idno, details, assigned_asset_tag_no) VALUES (%s, %s, %s)"
            cursor.execute(insert_query, (notification_id, notification_details, assigned_asset_tag_no))
            conn.commit()

        body += """
            </table>
          </body>
        </html>
        """

        message = MIMEMultipart()
        message['From'] = email_config['sender_email']
        message['To'] = email_config['receiver_email']
        message['Subject'] = subject
        part1 = MIMEText(body, "html")

        message.attach(part1)

        # Connect to SMTP server and send email
        with smtplib.SMTP(email_config['smtp_server'], email_config['smtp_port']) as server:
            server.starttls()
            server.login(email_config['smtp_username'], email_config['smtp_password'])
            server.sendmail(email_config['sender_email'], email_config['receiver_email'], message.as_string())

        print("Email sent successfully!")

    else:
        print("No assets found with audit scheduled for today.")

except mysql.connector.Error as err:
    print("Error:", err)

finally:
    # Close database connection
    if 'conn' in locals() and conn.is_connected():
        cursor.close()
        conn.close()
