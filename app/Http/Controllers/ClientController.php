<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientFormRequest;
use App\Models\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    protected $client;
    protected $title;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->title = 'Clientes';
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
        $clients = $this->client->where('user_id', Auth::user()->id)->paginate(10);
        return view('admin.clients.index', compact('title','clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;
        $subtitle = "Adicionar cliente";

        return view('admin.clients.form', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientFormRequest $request)
    {
        $dataForm = $request->all();
        $user = Auth::user();
        $dataForm['user_id'] = $user->id;
        $client = $this->client->create($dataForm);

        if ($client) {
            return redirect('/clientes')->with('success', 'Cliente criado com sucesso!');
        } else {
            return redirect('/news')->with('fail', 'Falha ao criar cliente!');
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
        $client = $this->client->findOrFail($id);
        $this->authorize('update', $client);

        $title = $this->title;
        $subtitle = "Adicionar cliente";
        return view('admin.clients.form', compact('title', 'subtitle', 'client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientFormRequest $request, $id)
    {
        $client = $this->client->findOrFail($id);
        $this->authorize('update', $client);

        $dataForm = $request->all();

        if ($client->update($dataForm)) {
            return redirect('/clientes')->with('success', 'Cliente atualizado com sucesso!');
        } else {
            return redirect('/clientes')->with('fail', 'Falha ao atualizar cliente!');
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
        $client = $this->client->findOrFail($id);
        $this->authorize('delete', $client);

        if ($this->client->destroy($id)) {
            return redirect('/clientes')->with('success', 'Cliente excluído com sucesso!');
        }

        return redirect('/clientes')->with('fail', 'Falha ao excluir cliente!');
    }
}
