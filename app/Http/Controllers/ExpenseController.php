<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseFormRequest;
use App\Models\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    protected $expense;
    protected $title;

    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
        $this->title = 'Despesas';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = $this->title;
        $expenses = $this->expense->where('user_id', Auth::user()->id)->paginate(10);
        return view('admin.expenses.index', compact('title','expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;
        $subtitle = "Adicionar despesa";

        return view('admin.expenses.form', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseFormRequest $request)
    {
        $dataForm = $request->all();
        $user = Auth::user();
        $dataForm['user_id'] = $user->id;
        $dataForm['date'] = date('Y-m-d', strtotime($dataForm['date']));
        $dataForm['amount'] = (float)$dataForm['amount'];

        $expense = $this->expense->create($dataForm);

        if ($expense) {
            return redirect('/despesas')->with('success', 'Despesa criada com sucesso!');
        } else {
            return redirect('/despesas')->with('fail', 'Falha ao criar despesa!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = $this->expense->findOrFail($id);
        $this->authorize('update', $expense);

        $title = $this->title;
        $subtitle = "Adicionar despesa";
        return view('admin.expenses.form', compact('title', 'subtitle', 'expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseFormRequest $request, $id)
    {
        $expense = $this->expense->findOrFail($id);
        $this->authorize('update', $expense);

        $dataForm = $request->all();
        $dataForm['date'] = date('Y-m-d', strtotime($dataForm['date']));
        $dataForm['amount'] = (float)$dataForm['amount'];

        if ($expense->update($dataForm)) {
            return redirect('/despesas')->with('success', 'Despesa atualizada com sucesso!');
        } else {
            return redirect('/despesas')->with('fail', 'Falha ao atualizar despesa!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = $this->expense->findOrFail($id);
        $this->authorize('delete', $expense);

        if ($this->expense->destroy($id)) {
            return redirect('/despesas')->with('success', 'Despesa excluída com sucesso!');
        }

        return redirect('/despesas')->with('fail', 'Falha ao excluir despesa!');
    }
}
