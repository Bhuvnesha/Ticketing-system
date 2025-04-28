<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $allowedFields = ['ticket_id', 'user_id', 'comment_text', 'created_at'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'ticket_id' => 'required|numeric',
        'user_id' => 'required|numeric',
        'comment_text' => 'required|string'
    ];

    public function getCommentsWithUser($ticket_id)
    {
        return $this->select('comments.*, users.username')
                   ->join('users', 'users.id = comments.user_id')
                   ->where('comments.ticket_id', $ticket_id)
                   ->orderBy('comments.created_at', 'DESC')
                   ->findAll();
    }
}