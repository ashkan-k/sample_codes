<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;

class QuestionTicketController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {

    }


    public function store(Request $request)
    {

    }


    public function edit(QuestionTicket $questionTicket)
    {

    }


    public function update(Request $request, QuestionTicket $questionTicket)
    {

    }


    public function list(Ticket $ticket)
    {
        return view('Admin.QuestionTickets.list',compact('ticket'));
    }
}
