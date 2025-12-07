<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    protected array $errors = [];


    public function getErrors(): array
    {
        return $this->errors;
    }


    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function validateSignup(array $data): bool
    {

        if (empty($data['name'])) {
            $this->errors['name'] = 'Name is required.';
        }

        if (empty($data['email'])) {
            $this->errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format.';
        }

        if (empty($data['password'])) {
            $this->errors['password'] = 'Password is required.';
        } elseif (strlen($data['password']) < 6) {
            $this->errors['password'] = 'Password must be at least 6 characters.';
        }

        return $this->isValid();
    }


    public function validateLogin(array $data): bool
    {

        if (empty($data['email'])) {
            $this->errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format.';
        }


        if (empty($data['password'])) {
            $this->errors['password'] = 'Password is required.';
        }

        return $this->isValid();
    }


    public function validateSubmission(array $data): bool
    {
 
        if (!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
            $this->errors['amount'] = 'Amount must be a positive number.';
        }

        // Buyer only text, spaces and numbers, not more than 20 characters.
        if (empty($data['buyer'])) {
            $this->errors['buyer'] = 'Buyer name is required.';
        } elseif (!preg_match('/^[a-zA-Z0-9\s]{1,20}$/', $data['buyer'])) {
            $this->errors['buyer'] = 'Buyer name must be 1-20 characters, containing only text, spaces, and numbers.';
        }

 
        if (empty($data['receipt_id'])) {
            $this->errors['receipt_id'] = 'Receipt ID is required.';
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $data['receipt_id'])) {
            $this->errors['receipt_id'] = 'Receipt ID must contain only text and numbers.';
        }

        if (empty($data['items'])) {
            $this->errors['items'] = 'Items list is required.';
        }


        if (empty($data['buyer_email'])) {
            $this->errors['buyer_email'] = 'Buyer email is required.';
        } elseif (!filter_var($data['buyer_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['buyer_email'] = 'Invalid buyer email format.';
        }


        if (isset($data['note'])) {
            $wordCount = str_word_count($data['note']);
            if ($wordCount > 30) {
                $this->errors['note'] = 'Note must not exceed 30 words.';
            }
        }

        if (empty($data['city'])) {
            $this->errors['city'] = 'City is required.';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/u', $data['city'])) {
            $this->errors['city'] = 'City must contain only text and spaces.';
        }

        if (empty($data['phone'])) {
            $this->errors['phone'] = 'Phone number is required.';
        } elseif (!preg_match('/^\d+$/', $data['phone'])) {
            $this->errors['phone'] = 'Phone number must contain only numbers.';
        }


        if (!isset($data['entry_by']) || !is_numeric($data['entry_by']) || $data['entry_by'] <= 0) {
            $this->errors['entry_by'] = 'Entry By must be a positive number.';
        }

        return $this->isValid();
    }
}
