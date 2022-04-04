<?php

namespace App\Http\Controllers\Admin;

use App\DB\TicketRepo;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{


    public function index()
    {
        return view('Admin.Tickets.index');
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $ticket = TicketRepo::createTicket(auth()->user(),$request->input('title'));

        TicketRepo::createQuestionTicket($ticket->id, $request->input('body'));
    }

    public function edit(Ticket $ticket)
    {
        //
    }


    public function update(Request $request, Ticket $ticket)
    {
        //
    }
}
