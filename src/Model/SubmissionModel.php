<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\Model;
use PDO;

class SubmissionModel extends Model
{
    protected string $table = 'submissions';


    public function create(array $data): int|false
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute($data);

        return $result ? (int)$this->db->lastInsertId() : false;
    }


    public function getAll(string $startDate = null, string $endDate = null, int $userId = null): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if ($startDate) {
            $sql .= " AND entry_at >= :start_date";
            $params['start_date'] = $startDate;
        }

        if ($endDate) {
            $sql .= " AND entry_at <= :end_date";
            $params['end_date'] = $endDate;
        }

        if ($userId) {
            $sql .= " AND entry_by = :user_id";
            $params['user_id'] = $userId;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
