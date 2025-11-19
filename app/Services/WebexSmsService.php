<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebexSmsService
{
    protected $apiKey;
    protected $senderName;
    protected $adminNumber;

    public function __construct()
    {
        $this->apiKey = config('services.webex.api_key');
        $this->senderName = config('services.webex.sender_name');
        $this->adminNumber = config('services.webex.admin_number');
    }

    /**
     * Send SMS to a phone number
     *
     * @param string $to Phone number to send SMS to
     * @param string $message Message content
     * @return array
     */
    public function sendSms(string $to, string $message): array
    {
        try {
            // Webex Interact SMS API endpoint
            $endpoint = 'https://api.webexinteract.com/v1/sms';

            // Webex Interact API uses X-AUTH-KEY header for authentication
            $headers = [
                'X-AUTH-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)->post($endpoint, [
                'message_body' => $message,
                'from' => $this->senderName,
                'to' => [
                    [
                        'phone' => [$this->formatPhoneNumber($to)]
                    ]
                ]
            ]);

            if ($response->successful()) {
                Log::info('Webex SMS sent successfully', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'data' => $response->json()
                ];
            } else {
                Log::error('Webex SMS failed', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to send SMS',
                    'error' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Webex SMS exception', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Exception occurred while sending SMS',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send SMS to customer
     *
     * @param string $customerPhone Customer phone number
     * @param string $message Message content
     * @return array
     */
    public function sendToCustomer(string $customerPhone, string $message): array
    {
        if (empty($customerPhone)) {
            return [
                'success' => false,
                'message' => 'Customer phone number is required'
            ];
        }

        return $this->sendSms($customerPhone, $message);
    }

    /**
     * Send SMS to admin
     *
     * @param string $message Message content
     * @return array
     */
    public function sendToAdmin(string $message): array
    {
        if (empty($this->adminNumber)) {
            Log::warning('Admin phone number not configured for Webex SMS');
            return [
                'success' => false,
                'message' => 'Admin phone number not configured'
            ];
        }

        return $this->sendSms($this->adminNumber, $message);
    }

    /**
     * Format phone number (remove spaces, dashes, etc.)
     *
     * @param string $phone Phone number
     * @return string
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // If phone doesn't start with +, add country code (UK: +44)
        if (!str_starts_with($phone, '+')) {
            // Remove leading 0 and add +44 for UK numbers
            if (str_starts_with($phone, '0')) {
                $phone = '+44' . substr($phone, 1);
            } else {
                $phone = '+44' . $phone;
            }
        }
        
        return $phone;
    }

    /**
     * Send booking confirmation SMS to customer and admin
     *
     * @param \App\Models\Booking $booking
     * @return array
     */
    public function sendBookingConfirmation($booking): array
    {
        $results = [
            'customer' => null,
            'admin' => null
        ];

        // Load customer relationship if not already loaded
        if (!$booking->relationLoaded('customer')) {
            $booking->load('customer');
        }

        // Send SMS to customer only if it's NOT a company booking
        if (!$booking->is_company_booking) {
            if ($booking->customer && $booking->customer->phone) {
                $customerMessage = $this->buildCustomerMessage($booking);
                $results['customer'] = $this->sendToCustomer($booking->customer->phone, $customerMessage);
            } else {
                Log::warning('Cannot send SMS to customer - phone number missing', [
                    'booking_id' => $booking->id,
                    'customer_id' => $booking->customer_id
                ]);
                $results['customer'] = [
                    'success' => false,
                    'message' => 'Customer phone number not available'
                ];
            }
        } else {
            // Company booking - skip customer SMS
            Log::info('Skipping customer SMS for company booking', [
                'booking_id' => $booking->id,
                'company_id' => $booking->company_id
            ]);
            $results['customer'] = [
                'success' => false,
                'message' => 'Skipped - Company booking'
            ];
        }

        // Send SMS to admin
        $adminMessage = $this->buildAdminMessage($booking);
        $results['admin'] = $this->sendToAdmin($adminMessage);

        return $results;
    }

    /**
     * Build customer confirmation message
     *
     * @param \App\Models\Booking $booking
     * @return string
     */
    protected function buildCustomerMessage($booking): string
    {
        $customerName = $booking->customer->name ?? 'Customer';
        $bookingDate = $booking->booking_date ? $booking->booking_date->format('d/m/Y') : 'N/A';
        $startTime = $booking->start_time ? $booking->start_time->format('H:i') : 'N/A';
        $pickupAddress = $booking->pickup_address ?? 'N/A';
        $contactPhone = $this->adminNumber; // Using admin number as contact number
        
        // Format phone number for display (remove +44 and format)
        $displayPhone = $contactPhone;
        if (str_starts_with($displayPhone, '+44')) {
            $displayPhone = '0' . substr($displayPhone, 3);
        }
        
        // Generate tracking URL
        $trackingUrl = url('/track/' . $booking->booking_reference);
        
        $message = "Hi {$customerName}! Just confirming your move with us on {$bookingDate}";
        
        if ($startTime !== 'N/A') {
            $message .= " at {$startTime}";
        }
        
        $message .= ".\n\nWe'll see you at {$pickupAddress}. If you have any questions, call or text us at {$displayPhone}.\n\nTrack your booking: {$trackingUrl}";
        
        return $message;
    }

    /**
     * Build admin notification message
     *
     * @param \App\Models\Booking $booking
     * @return string
     */
    protected function buildAdminMessage($booking): string
    {
        $customerName = $booking->customer->name ?? 'Unknown';
        $bookingRef = $booking->booking_reference;
        $bookingDate = $booking->booking_date ? $booking->booking_date->format('d/m/Y') : 'N/A';
        
        $message = "New booking confirmed: {$bookingRef} - {$customerName} on {$bookingDate}";
        
        if ($booking->pickup_address) {
            $message .= ". From: " . substr($booking->pickup_address, 0, 40);
        }
        
        return $message;
    }
}

