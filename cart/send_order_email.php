<?php
require_once('../PHPMailer/src/Exception.php');
require_once('../PHPMailer/src/PHPMailer.php');
require_once('../PHPMailer/src/SMTP.php');
require_once('../fpdf183/fpdf.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function generateOrderPDF($order, $order_items, $user)
{
    $pdf = new FPDF();
    $pdf->AddPage();

    // Header
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 20, 'FEAR OF GOD', 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Order Confirmation', 0, 1, 'C');

    // Order Info
    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Order #: ' . $order['order_id'], 0, 1);
    $pdf->Cell(0, 10, 'Date: ' . $order['order_date'], 0, 1);
    $pdf->Cell(0, 10, 'Customer: ' . $user['username'], 0, 1);

    // Shipping Info
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Shipping Address:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 6, $order['shipping_address'], 0, 'L');

    // Order Items
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 10, 'Product', 1);
    $pdf->Cell(30, 10, 'Quantity', 1);
    $pdf->Cell(35, 10, 'Price', 1);
    $pdf->Cell(35, 10, 'Total', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $subtotal = 0;

    foreach ($order_items as $item) {
        $pdf->Cell(90, 10, $item['product_name'], 1);
        $pdf->Cell(30, 10, $item['quantity'], 1);
        $pdf->Cell(35, 10, '$' . number_format($item['price_at_time_of_purchase'], 2), 1);
        $total = $item['quantity'] * $item['price_at_time_of_purchase'];
        $pdf->Cell(35, 10, '$' . number_format($total, 2), 1);
        $pdf->Ln();
        $subtotal += $total;
    }

    // Order Summary
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(120);
    $pdf->Cell(35, 10, 'Subtotal:', 0);
    $pdf->Cell(35, 10, '$' . number_format($subtotal, 2), 0);
    $pdf->Ln();

    $tax = $subtotal * 0.22;
    $pdf->Cell(120);
    $pdf->Cell(35, 10, 'IVA (22%):', 0);
    $pdf->Cell(35, 10, '$' . number_format($tax, 2), 0);
    $pdf->Ln();

    $shipping = $subtotal > 100 ? 0 : 15;
    $pdf->Cell(120);
    $pdf->Cell(35, 10, 'Shipping:', 0);
    $pdf->Cell(35, 10, $shipping > 0 ? '$' . number_format($shipping, 2) : 'FREE', 0);
    $pdf->Ln();

    $total = $subtotal + $tax + $shipping;
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(120);
    $pdf->Cell(35, 10, 'Total:', 0);
    $pdf->Cell(35, 10, '$' . number_format($total, 2), 0);

    return $pdf->Output('S');
}

function sendOrderConfirmationEmail($order, $order_items, $user)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kai.wang@iiseinaudiscarpa.edu.it';
        $mail->Password = 'rpdj ykbi mrho rttb'; // Replace with actual app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('5cinf.einaudi@gmail.com', 'Fear of God');
        $mail->addAddress($user['email'], $user['username']);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Fear of God - Order Confirmation #' . $order['order_id'];

        // Email body
        $body = "
        <h2>Thank you for your order!</h2>
        <p>Dear {$user['username']},</p>
        <p>We're pleased to confirm your order #" . $order['order_id'] . ".</p>
        <h3>Order Details:</h3>
        <p><strong>Order Date:</strong> " . $order['order_date'] . "</p>
        <p><strong>Payment Method:</strong> " . $order['payment_method'] . "</p>
        <p><strong>Total Amount:</strong> $" . number_format($order['total_amount'], 2) . "</p>
        <p>We have attached your order confirmation PDF to this email.</p>
        <p>If you have any questions, please don't hesitate to contact us.</p>
        <p>Thank you for shopping with Fear of God!</p>";

        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);

        // Attach PDF
        $pdf = generateOrderPDF($order, $order_items, $user);
        $mail->AddStringAttachment($pdf, "order_{$order['order_id']}.pdf", 'base64', 'application/pdf');

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
