<?php
/**
 * Email Service - Wrapper for PHPMailer
 * Provides email functionality using PHPMailer library
 * PHP 7.4+ compatible, self-contained implementation
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. EmailService.php cannot be called directly.");
}

// Load PHPMailer manually (no Composer)
require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';
require_once __DIR__ . '/../PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mail;
    private $last_error = '';

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configure();
    }

    /**
     * Configure PHPMailer with settings
     */
    private function configure()
    {
        try {
            // Use SMTP if configured
            if (defined('SMTP_HOST') && SMTP_HOST) {
                $this->mail->isSMTP();
                $this->mail->Host = SMTP_HOST;
                $this->mail->SMTPAuth = defined('SMTP_AUTH') && SMTP_AUTH;
                
                if (defined('SMTP_USERNAME') && SMTP_USERNAME) {
                    $this->mail->Username = SMTP_USERNAME;
                    $this->mail->Password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
                }
                
                $this->mail->SMTPSecure = defined('SMTP_SECURE') ? SMTP_SECURE : 'tls';
                $this->mail->Port = defined('SMTP_PORT') ? SMTP_PORT : 587;
                
                // Set timeout for shared hosting
                $this->mail->Timeout = 30;
            } else {
                // Use PHP mail() as fallback
                $this->mail->isMail();
            }
            
            // Set charset
            $this->mail->CharSet = 'UTF-8';
            
        } catch (Exception $e) {
            $this->last_error = 'Configuration error: ' . $e->getMessage();
            error_log("EmailService Configuration Error: " . $e->getMessage());
        }
    }

    /**
     * Send email
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body Email body
     * @param bool $isHTML Whether body is HTML
     * @param array $options Additional options (from_name, reply_to, attachments, etc.)
     * @return bool Success status
     */
    public function sendEmail($to, $subject, $body, $isHTML = false, $options = array())
    {
        try {
            // Reset for new email
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            // Set sender
            $from_email = defined('EMAIL_FROM') ? EMAIL_FROM : 'noreply@' . $_SERVER['HTTP_HOST'];
            $from_name = defined('EMAIL_FROM_NAME') ? EMAIL_FROM_NAME : 'Lupopedia';
            $this->mail->setFrom($from_email, $from_name);
            
            // Add recipient
            $this->mail->addAddress($to);
            
            // Set reply-to if specified
            if (isset($options['reply_to']) && !empty($options['reply_to'])) {
                $reply_name = isset($options['reply_name']) ? $options['reply_name'] : '';
                $this->mail->addReplyTo($options['reply_to'], $reply_name);
            }
            
            // Add CC if specified
            if (isset($options['cc']) && is_array($options['cc'])) {
                foreach ($options['cc'] as $cc_email) {
                    $this->mail->addCC($cc_email);
                }
            }
            
            // Add BCC if specified
            if (isset($options['bcc']) && is_array($options['bcc'])) {
                foreach ($options['bcc'] as $bcc_email) {
                    $this->mail->addBCC($bcc_email);
                }
            }
            
            // Add attachments if specified
            if (isset($options['attachments']) && is_array($options['attachments'])) {
                foreach ($options['attachments'] as $attachment) {
                    if (is_array($attachment)) {
                        // Array with path and name
                        $this->mail->addAttachment($attachment['path'], $attachment['name']);
                    } else {
                        // Just path
                        $this->mail->addAttachment($attachment);
                    }
                }
            }
            
            // Set email content
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->isHTML($isHTML);
            
            // Add plain text version for HTML emails
            if ($isHTML) {
                $this->mail->AltBody = $this->htmlToText($body);
            }
            
            // Send email
            $result = $this->mail->send();
            $this->last_error = '';
            return $result;
            
        } catch (Exception $e) {
            $this->last_error = 'Send error: ' . $e->getMessage();
            error_log("EmailService Send Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a simple text email
     */
    public function sendTextEmail($to, $subject, $message)
    {
        return $this->sendEmail($to, $subject, $message, false);
    }

    /**
     * Send an HTML email
     */
    public function sendHTMLEmail($to, $subject, $html_content)
    {
        return $this->sendEmail($to, $subject, $html_content, true);
    }

    /**
     * Send email with template
     */
    public function sendTemplateEmail($to, $subject, $template_file, $variables = array())
    {
        if (!file_exists($template_file)) {
            $this->last_error = "Template file not found: $template_file";
            return false;
        }
        
        // Read template
        $template = file_get_contents($template_file);
        
        // Replace variables
        foreach ($variables as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        
        // Detect if HTML (basic check)
        $is_html = stripos($template, '<html') !== false || stripos($template, '<!DOCTYPE') !== false;
        
        return $this->sendEmail($to, $subject, $template, $is_html);
    }

    /**
     * Get last error message
     */
    public function getLastError()
    {
        return $this->last_error;
    }

    /**
     * Test email configuration
     */
    public function testConfiguration()
    {
        try {
            // Create a test email without sending
            $this->mail->clearAddresses();
            $this->mail->setFrom('test@example.com', 'Test');
            $this->mail->addAddress('test@example.com');
            $this->mail->Subject = 'Test';
            $this->mail->Body = 'Test';
            
            // Check if we can get the message content (this doesn't send)
            $message = $this->mail->getSentMIMEMessage();
            
            return array(
                'success' => true,
                'message' => 'Configuration OK',
                'transport' => defined('SMTP_HOST') ? 'SMTP (' . SMTP_HOST . ')' : 'PHP mail()'
            );
            
        } catch (Exception $e) {
            return array(
                'success' => false,
                'message' => $e->getMessage(),
                'transport' => 'Unknown'
            );
        }
    }

    /**
     * Convert HTML to plain text (basic implementation)
     */
    private function htmlToText($html)
    {
        // Remove HTML tags
        $text = strip_tags($html);
        
        // Convert HTML entities
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        
        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }

    /**
     * Validate email address
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Send password reset email
     */
    public function sendPasswordReset($to, $reset_link, $username = '')
    {
        $subject = 'Password Reset - Lupopedia';
        
        $body = "
        <html>
        <head>
            <title>Password Reset</title>
        </head>
        <body>
            <h2>Password Reset Request</h2>
            <p>Hello " . htmlspecialchars($username ?: 'User') . ",</p>
            <p>You requested a password reset for your Lupopedia account.</p>
            <p>Click the link below to reset your password:</p>
            <p><a href=\"" . htmlspecialchars($reset_link) . "\">Reset Password</a></p>
            <p>This link will expire in 1 hour.</p>
            <p>If you didn't request this, please ignore this email.</p>
            <hr>
            <p><small>This is an automated message from Lupopedia.</small></p>
        </body>
        </html>";
        
        return $this->sendHTMLEmail($to, $subject, $body);
    }

    /**
     * Send welcome email
     */
    public function sendWelcomeEmail($to, $username = '')
    {
        $subject = 'Welcome to Lupopedia';
        
        $body = "
        <html>
        <head>
            <title>Welcome to Lupopedia</title>
        </head>
        <body>
            <h2>Welcome to Lupopedia!</h2>
            <p>Hello " . htmlspecialchars($username ?: 'User') . ",</p>
            <p>Thank you for joining Lupopedia, the multi-agent coordination platform.</p>
            <p>Your account has been created and you can now:</p>
            <ul>
                <li>Log in to your account</li>
                <li>Select an agent identity</li>
                <li>Participate in multi-agent workflows</li>
            </ul>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <hr>
            <p><small>This is an automated message from Lupopedia.</small></p>
        </body>
        </html>";
        
        return $this->sendHTMLEmail($to, $subject, $body);
    }
}
