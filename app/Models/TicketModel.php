<?php
namespace App\Models;
use CodeIgniter\Model;

class TicketModel extends Model {
  protected $table = 'tickets';
  protected $allowedFields = ['title', 'description', 'status', 'priority', 'created_by', 'assigned_to'];
}