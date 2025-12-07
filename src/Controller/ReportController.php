<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\BaseController;
use App\Model\SubmissionModel;

class ReportController extends BaseController
{
    protected SubmissionModel $submissionModel;

    public function __construct()
    {
        $this->submissionModel = new SubmissionModel();
    }


    public function index(): void
    {
        $this->render('report/index', ['title' => 'Submission Report']);
    }


    public function getReportData(): void
    {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $userId = $_GET['user_id'] ?? null;

        // Basic validation for date format (YYYY-MM-DD)
        if ($startDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid start date format.'], 400);
        }
        if ($endDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid end date format.'], 400);
        }

        // Basic validation for user ID
        if ($userId && (!is_numeric($userId) || (int)$userId <= 0)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid user ID.'], 400);
        }
        $userId = $userId ? (int)$userId : null;

        try {
            $data = $this->submissionModel->getAll($startDate, $endDate, $userId);
            $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            // Log error in a real application
            $this->jsonResponse(['success' => false, 'message' => 'An error occurred while fetching data.'], 500);
        }
    }
}
