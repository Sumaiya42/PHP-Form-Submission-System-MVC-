<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\BaseController;
use App\Core\Validator;
use App\Model\SubmissionModel;

class SubmissionController extends BaseController
{

    public function index(): void
    {
        $this->render('submission/form', ['title' => 'Data Submission']);
    }


    public function handleSubmission(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $validator = new Validator();

        //Backend Validation
        if (!$validator->validateSubmission($input)) {
            $this->jsonResponse(['success' => false, 'message' => 'Validation failed.', 'errors' => $validator->getErrors()], 400);
        }

        //Cookie Check for 24-hour limit
        $cookieName = 'last_submission_' . $_SESSION['user_id'];
        if (isset($_COOKIE[$cookieName])) {
            $lastSubmissionTime = (int)$_COOKIE[$cookieName];
            if (time() - $lastSubmissionTime < 86400) { // 86400 seconds = 24 hours
                $this->jsonResponse(['success' => false, 'message' => 'You can only submit once every 24 hours.'], 429);
            }
        }

        //Data Preparation and Transformation
        $data = [
            'amount'      => (int)$input['amount'],
            'buyer'       => $input['buyer'],
            'receipt_id'  => $input['receipt_id'],
            'items'       => $input['items'],
            'buyer_email' => $input['buyer_email'],
            'note'        => $input['note'] ?? null,
            'city'        => $input['city'],
            'phone'       => $input['phone'],
            'entry_by'    => (int)$input['entry_by'],
            'entry_at'    => date('Y-m-d'), 
        ];


        $data['buyer_ip'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        //Generate hash_key (encrypted string of 'receipt_id' and a proper 'salt' using sha-512)
        $salt = $_ENV['APP_SALT'] ?? 'default_salt';
        $data['hash_key'] = hash('sha512', $data['receipt_id'] . $salt);

        //Database Insertion
        $submissionModel = new SubmissionModel();
        $submissionId = $submissionModel->create($data);

        if ($submissionId) {
            //Set Cookie for 24-hour limit
            setcookie($cookieName, (string)time(), time() + 86400, '/');

            $this->jsonResponse(['success' => true, 'message' => 'Submission successful.', 'id' => $submissionId], 201);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Submission failed due to a server error.'], 500);
        }
    }
}
