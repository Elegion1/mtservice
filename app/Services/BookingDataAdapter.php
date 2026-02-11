<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BookingDataAdapter
{
    /**
     * Adapt incoming booking data to the database storage format
     * 
     * @param array $data Raw booking data from frontend
     * @return array Adapted data ready for Booking::create()
     * @throws \Exception
     */
    public static function adapt(array $data): array
    {
        try {
            // Validate required fields
            if (!isset($data['name']) || !isset($data['surname']) || !isset($data['price'])) {
                Log::error('Dati obbligatori mancanti: name, surname o price');
                throw new \Exception('Dati obbligatori mancanti');
            }

            return match($data['type'] ?? null) {
                'transfer' => self::adaptTransfer($data),
                'escursione' => self::adaptExcursion($data),
                'noleggio' => self::adaptRental($data),
                default => throw new \Exception('Tipo di prenotazione sconosciuto: ' . ($data['type'] ?? 'null'))
            };
        } catch (\Exception $e) {
            Log::error('Errore durante l\'adattamento dei dati: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Adapt transfer booking data
     */
    private static function adaptTransfer(array $data): array
    {
        $route = explode(' - ', $data['route'] ?? '');

        return [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'dial_code' => null,
            'body' => $data['message'] ?? '',
            'info' => [
                'flight' => [
                    'flightNumber' => $data['flightNumber'] ?? null,
                    'departureAirport' => $data['departureAirport'] ?? null,
                    'departureTime' => $data['departureTime'] ?? null,
                    'arrivalAirport' => $data['arrivalAirport'] ?? null,
                    'arrivalTime' => $data['arrivalTime'] ?? null,
                ],
            ],
            'bookingData' => [
                'type' => 'transfer',
                'price' => $data['price'],
                'original_price' => $data['price'],
                'date_dep' => $data['dateStart'] . 'T' . $data['timeStart'],
                'date_ret' => isset($data['dateReturn']) && isset($data['timeReturn']) 
                    ? $data['dateReturn'] . 'T' . $data['timeReturn'] 
                    : null,
                'sola_andata' => !isset($data['dateReturn']) || !isset($data['timeReturn']),
                'duration' => $data['duration'] ?? 1,
                'passengers' => $data['passengers'],
                'departure_id' => null,
                'departure_name' => $route[0] ?? '',
                'arrival_name' => $route[1] ?? '',
                'sito_favignana' => true,
                'transferType' => $data['transferType'] ?? null,
            ],
            'code' => $data['code'],
            'service_date' => $data['dateStart'],
            'status' => 'confirmed',
            'payment_status' => $data['paymentStatus'] === 'COMPLETED' ? 'paid' : 'pending',
            'locale' => $data['locale'] ?? 'it',
        ];
    }

    /**
     * Adapt excursion booking data
     */
    private static function adaptExcursion(array $data): array
    {
        return [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'dial_code' => null,
            'body' => $data['message'] ?? '',
            'info' => [
                'flight' => [
                    'flightNumber' => $data['flightNumber'] ?? null,
                    'departureAirport' => $data['departureAirport'] ?? null,
                    'departureTime' => $data['departureTime'] ?? null,
                    'arrivalAirport' => $data['arrivalAirport'] ?? null,
                    'arrivalTime' => $data['arrivalTime'] ?? null,
                ],
            ],
            'bookingData' => [
                'type' => 'escursione',
                'price' => $data['price'],
                'original_price' => $data['price'],
                'date_dep' => $data['dateStart'] . 'T' . $data['timeStart'],
                'passengers' => $data['passengers'],
                'sito_favignana' => true,
                'departure_name' => $data['excursion'],
                'departure_location' => $data['departureLocation'] ?? null,
            ],
            'code' => $data['code'],
            'service_date' => $data['dateStart'],
            'status' => 'confirmed',
            'payment_status' => $data['paymentStatus'] === 'COMPLETED' ? 'paid' : 'pending',
            'locale' => $data['locale'] ?? 'it',
        ];
    }

    /**
     * Adapt rental booking data
     */
    private static function adaptRental(array $data): array
    {
        return [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'dial_code' => null,
            'body' => $data['message'] ?? '',
            'info' => [],
            'bookingData' => [
                'type' => 'noleggio',
                'price' => $data['price'],
                'original_price' => $data['price'],
                'date_start' => $data['dateStart'] . 'T' . $data['timeStart'],
                'date_end' => $data['dateEnd'] . 'T' . $data['timeEnd'],
                'car_id' => $data['carId'] ?? null,
                'passengers' => $data['passengers'] ?? 1,
            ],
            'code' => $data['code'],
            'service_date' => $data['dateStart'],
            'status' => 'confirmed',
            'payment_status' => $data['paymentStatus'] === 'COMPLETED' ? 'paid' : 'pending',
            'locale' => $data['locale'] ?? 'it',
        ];
    }
}
