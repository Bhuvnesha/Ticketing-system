<?php
namespace App\Controllers;
use App\Models\TicketModel;
use App\Models\CommentModel;

class Tickets extends BaseController
{
    public function index()
    {
        $model = new TicketModel();

        $search = $this->request->getGet('search'); // Get search query
    
        // Filter tickets based on search
        if ($search) {
            $model->like('title', $search)
                  ->orLike('status', $search);
        }

        $data = [
            'tickets' => $model->paginate(10), // Show 10 tickets per page
            'pager'   => $model->pager,
            'search' => $search //Pass search term to view
        ];
        return view('tickets/list', $data);
    }

    public function create()
    {
        return view('tickets/create');
    }

    public function store()
    {
        $model = new TicketModel();
        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'priority'    => $this->request->getPost('priority'),
            'status'      => 'Open', // Default status
            'created_by'  => session()->get('user_id') // Logged-in user
        ];

        $model->save($data);
        session()->setFlashdata('success', 'Ticket created successfully!');
        return redirect()->to('/tickets');
    }

    // View a single ticket (GET /tickets/1)
    public function show($id)
    {
        $model = new TicketModel();
        $model2 = new CommentModel();

        $data['ticket'] = $model->find($id);
        $data['comments'] = $model2->getCommentsWithUser($id);
        return view('tickets/show', $data); // Create this view later
    }

    // Edit Ticket (GET /tickets/edit/1)
    public function edit($id)
    {
        $model = new TicketModel();
        $data['ticket'] = $model->find($id);

        // Ensure only the ticket creator or admin can edit
        if ($data['ticket']['created_by'] != session()->get('user_id') && session()->get('role') != 'admin') {
            return redirect()->to('/tickets')->with('error', 'Unauthorized action!');
        }

        return view('tickets/edit', $data);
    }

    // Update Ticket (POST /tickets/update/1)
    public function update($id)
    {
        $model = new TicketModel();
        $ticket = $model->find($id);
        $oldStatus = $ticket['status'];
        $newStatus = $this->request->getPost('status');

        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'priority'    => $this->request->getPost('priority'),
            'status'      => $newStatus // Add a dropdown in edit form
        ];

        // Send email if status changed
        if ($oldStatus != $newStatus) {
            $email = \Config\Services::email();
            $email->setTo('recipient@example.com'); // Fetch assignee's email from DB
            $email->setSubject("Ticket #{$id} Status Updated");
            $email->setMessage("
                Ticket: {$ticket['title']}
                New Status: {$newStatus}
                View: " . base_url("tickets/{$id}")
            );
            $email->send();
        }

        $model->update($id, $data);
        session()->setFlashdata('success', 'Ticket updated successfully!');
        return redirect()->to('/tickets');
    }

    // Delete Ticket (GET /tickets/delete/1)
    public function delete($id)
    {
        $model = new TicketModel();
        $ticket = $model->find($id);

        // Authorization check
        if ($ticket['created_by'] != session()->get('user_id') && session()->get('role') != 'admin') {
            return redirect()->to('/tickets')->with('error', 'Unauthorized action!');
        }

        $model->delete($id);
        session()->setFlashdata('success', 'Ticket deleted successfully!');
        return redirect()->to('/tickets');
    }

    // app/Controllers/Tickets.php
    public function updateStatus($id)
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid request');

        $model = new TicketModel();
        $model->update($id, [
            'status' => $this->request->getJSON()->status
        ]);

        return $this->respondUpdated(['message' => 'Status updated']);
    }

}