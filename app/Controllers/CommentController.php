<?php

namespace App\Controllers;

use App\Models\CommentModel;
use CodeIgniter\Controller;

class CommentController extends Controller
{
    protected $commentModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
        helper('form');
    }

    public function save()
    {
        $validation = \Config\Services::validation();

        $data = [
            'ticket_id' => $this->request->getPost('ticket_id'),
            'user_id' => session()->get('user_id'), // Assuming user is logged in
            'comment_text' => $this->request->getPost('comment_text'),
            'created_at' => date("Y-m-d H:i:s")
        ];

        if (!$this->validate($this->commentModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->commentModel->save($data);
        return redirect()->back()->with('success', 'Comment added successfully');
    }
}