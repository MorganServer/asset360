import mysql.connector
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import random
import string
import sys

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
    'smtp_server': 'smtp.gmail.com',
    'smtp_port': 587,
    'smtp_username': 'garrett.morgan.pro@gmail.com',
    'smtp_password': 'vvbl gien cbrg zxrf'
}

# Function to generate random 8-digit code
def generate_random_code():
    return ''.join(random.choices(string.digits, k=8))

try:
    # Connect to MySQL database
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    # Retrieve email and code from command-line arguments
    recipient_email = sys.argv[1]
    code = sys.argv[2]

    # Compose email message
    subject = "Password Change Verification Code"
    body = f"Your verification code for changing the password is: {code}"

    message = MIMEMultipart()
    message['From'] = email_config['sender_email']
    message['To'] = recipient_email
    message['Subject'] = subject
    part1 = MIMEText(body, "plain")

    message.attach(part1)

    # Connect to SMTP server and send email
    with smtplib.SMTP(email_config['smtp_server'], email_config['smtp_port']) as server:
        server.starttls()
        server.login(email_config['smtp_username'], email_config['smtp_password'])
        server.sendmail(email_config['sender_email'], recipient_email, message.as_string())

    print("Email sent successfully!")

except mysql.connector.Error as err:
    print("MySQL Error:", err)

except smtplib.SMTPException as err:
    print("SMTP Error:", err)

finally:
    # Close database connection
    if 'conn' in locals() and conn.is_connected():
        cursor.close()
        conn.close()
