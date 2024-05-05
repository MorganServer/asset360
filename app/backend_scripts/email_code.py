import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import sys

def send_email(recipient_email, code):
    # Email configurations
    sender_email = "garrett.morgan.pro@gmail.com"  # Replace with your email address
    password = "vvbl gien cbrg zxrf"  # Replace with your email password

    subject = "Your Verification Code"
    body = f"Your verification code is: {code}"

    # Create a multipart message
    message = MIMEMultipart()
    message["From"] = sender_email
    message["To"] = recipient_email
    message["Subject"] = subject

    # Add body to email
    message.attach(MIMEText(body, "plain"))

    # Connect to the SMTP server
    with smtplib.SMTP("smtp.gmail.com", 587) as server:  # Replace with your SMTP server and port
        server.starttls()
        server.login(sender_email, password)
        text = message.as_string()
        server.sendmail(sender_email, recipient_email, text)

if __name__ == "__main__":
    # Retrieve recipient's email and code from command-line arguments
    recipient_email = sys.argv[1]
    code = sys.argv[2]

    # Call the function to send the email
    send_email(recipient_email, code)
