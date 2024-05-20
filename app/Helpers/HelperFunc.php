<?php

namespace App\Helpers;

use App\Models\Candidate;
use App\Models\User;

class HelperFunc
{
    /**
     * Generate a random reference number.
     *
     * @return string
     */
    public static function generateReferenceNumber(string $type = 'candidate')
    {
        // $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $letters = 'ABCDEGKRTVWYZ';
        $numbers = '123456789';
        $characters = $letters . $numbers;

        switch ($type) {
            case 'candidate':
                $targetClass = Candidate::class;
                $prefix = 'C-';
                break;
            case 'employer':
                $prefix = 'E-';
                break;
            case 'job':
                $prefix = 'J-';
                break;
            case 'application':
                $prefix = 'A-';
                break;
            default:
                $prefix = 'U-';
                break;
        }

        do {
            // Ensure at least one letter and one number
            $referenceNumber = $prefix;
            // $referenceNumber .= $numbers[rand(0, strlen($numbers) - 1)];
            for ($i = 0; $i < 3; $i++) {
                $referenceNumber .= $numbers[rand(0, strlen($numbers) - 1)];
            }
            $referenceNumber .= $letters[rand(0, strlen($letters) - 1)];

            // Fill the remaining 4 characters with random letters or numbers
            for ($i = 0; $i < 4; $i++) {
                $referenceNumber .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Shuffle the resulting string (excluding the 'C-' prefix)
            $referenceNumber = 'C-' . str_shuffle(substr($referenceNumber, 2));
        } while ($targetClass::where($type.'_ref', $referenceNumber)->exists());

        return $referenceNumber;
    }
}
