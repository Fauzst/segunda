<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
session_start();

class Send_otp {



    private $stmp_host;
    private $stmp_username;
    private $stmp_port;
    private $stmp_password;
    private $stmp_email;
    private $otp;


    public function __construct($path)
    {
        
        // READ ENV FILES
        if (!file_exists($path))
        {
            echo "<script>alert('flag6')</script>";
            exit;
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line)
        {
            if (str_starts_with(trim($line),'#')) continue;
            [$name, $value] = explode('=',$line,2);

            $name = trim($name);
            $value = trim($value);

            putenv("$name=$value");
            $_ENV[$name] = $value;
        }

        // ASSIGN DATA FROM INSTANCES FIELD
        $this->stmp_host = $_ENV['STMP_SERVER'];
        $this->stmp_password = $_ENV['STMP_PASSWORD'];
        $this->stmp_port = $_ENV['STMP_PORT'];
        $this->stmp_username = $_ENV['STMP_LOGIN'];
        $this->stmp_email = $_ENV['STMP_EMAIL'];

        // CALL GENERATE OTP FUNCTION
        $this->generateOTP();
    }

    private function generateOTP()
    {
        
        $otp = rand(100000,999999);
        
        $_SESSION['otp'] = $otp;
        
    }

    public function sendOTP($user_email)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $this->stmp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->stmp_username;
            $mail->Password = $this->stmp_password;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->stmp_port;

            $mail->setFrom($this->stmp_email, 'Segunda');
            $mail->addAddress($user_email);

            $mail->isHTML(true);
            $otp = $_SESSION['otp'];
            $mail->Subject = 'Segunda Registration OTP Code';
            $mail->Body = "
                <div style=\"
                    border: 2px solid #4A90E2; 
                    border-radius: 8px; 
                    padding: 20px; 
                    max-width: 300px; 
                    font-family: Arial, sans-serif; 
                    background-color: #f0f8ff;
                    color: #333;
                \">
                    <h2 style=\"color: #4A90E2; margin-top: 0;\">Segunda OTP Verification</h2>
                    <p style=\"font-size: 16px;\">Your OTP code is:</p>
                    <p style=\"
                        font-size: 24px; 
                        font-weight: bold; 
                        color: #d9534f; 
                        margin: 10px 0;
                        letter-spacing: 3px;
                        text-align: center;
                    \">$otp</p>
                    <p style=\"font-size: 14px; color: #555;\">Please enter this code to complete your registration. This code expires in 10 minutes.</p>
                </div>
                ";
            $mail->send();
            echo "<script>alert('OTP Sent!')</script>";
            session_abort();
            return ['success' => true];
        } catch (Exception $e) {
            echo "<script>alert('Error: OTP Sending Error! $e')</script>";
            session_abort();
            return ['success' => false, 'error' => $mail->ErrorInfo];
        }
    }

}

?>