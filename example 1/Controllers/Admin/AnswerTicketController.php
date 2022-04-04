<?php

namespace App\Http\Controllers\Admin;

use App\DB\AnswerTicketRepo;
use App\Http\Controllers\Controller;
use App\Models\QuestionTicket;
use Illuminate\Http\Request;

class AnswerTicketController extends Controller
{

    public function index()
    {

    }

    public function create($questionTicket_id)
    {
        if (AnswerTicketRepo::QuestionAnswer($questionTicket_id)) {
            return redirect()->back();
        }

        $question = AnswerTicketRepo::findQuestion($questionTicket_id);
        return view('Admin.AnswerTickets.create', compact('question'));
    }


    public function store(Request $request, QuestionTicket $questionTicket)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $user_id=auth()->user()->id;
        $ticket_id=$questionTicket->ticket->id;
        $question_id = $questionTicket->id;
        $body = $request->input('body');

        $answer = AnswerTicketRepo::createAnswer($body,$question_id , $ticket_id,$user_id);

        $ticketTitle = " ( ".$answer->ticket->title . " ) ";
        return redirect(route('questions.list',$answer->question->id))->with('setAnswer'," پاسخی برای تیکت {$ticketTitle} ثبت شد .");
    }


    public function edit($answerTicket_id)
    {
        $answer = AnswerTicketRepo::findAnswerTicket($answerTicket_id);

        return view('Admin.AnswerTickets.edit', compact('answer'));
    }


    public function update(Request $request, $answerTicket_id)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $user_id=auth()->user()->id;
        $body = $request->input('body');

        AnswerTicketRepo::updateAnswerTicket($answerTicket_id, $body,$user_id);
        $answer = AnswerTicketRepo::findAnswerTicket($answerTicket_id);

        $ticketTitle = " ( ".$answer->ticket->title . " ) ";
        return redirect(route('questions.list',$answer->question->id))->with('setAnswer'," پاسخی که برای تیکت {$ticketTitle} بود ویرایش شد .");
    }



}
