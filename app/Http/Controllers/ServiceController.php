<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceFormRequest;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    protected $service;
    protected $title;

    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->title = 'Serviços';
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
        $services = $this->service->where('user_id', Auth::user()->id)->paginate(10);
        return view('admin.services.index', compact('title','services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;
        $subtitle = "Adicionar serviço";

        return view('admin.services.form', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceFormRequest $request)
    {
        $dataForm = $request->all();
        $user = Auth::user();
        $dataForm['user_id'] = $user->id;

        $service = $this->service->create($dataForm);

        if ($service) {
            return redirect('/servicos')->with('success', 'Serviço criado com sucesso!');
        } else {
            return redirect('/servicoes')->with('fail', 'Falha ao criar serviço!');
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
        $service = $this->service->findOrFail($id);
        $this->authorize('update', $service);

        $title = $this->title;
        $subtitle = "Adicionar serviço";
        return view('admin.services.form', compact('title', 'subtitle', 'service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceFormRequest $request, $id)
    {
        $service = $this->service->findOrFail($id);
        $this->authorize('update', $service);

        $dataForm = $request->all();

        if ($service->update($dataForm)) {
            return redirect('/servicos')->with('success', 'Serviço atualizado com sucesso!');
        } else {
            return redirect('/servicos')->with('fail', 'Falha ao atualizar serviço!');
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
        $service = $this->service->findOrFail($id);
        $this->authorize('delete', $service);

        if ($this->service->destroy($id)) {
            return redirect('/servicos')->with('success', 'Serviço excluído com sucesso!');
        }

        return redirect('/servicos')->with('fail', 'Falha ao excluir serviço!');
    }
}
