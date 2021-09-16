<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityFormRequest;
use App\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    protected $city;
    protected $title;

    public function __construct(City $city)
    {
        $this->city = $city;
        $this->title = 'Cidades';
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
        $cities = $this->city->where('user_id', Auth::user()->id)->paginate(10);
        return view('admin.cities.index', compact('title','cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;
        $subtitle = "Adicionar cidade";

        return view('admin.cities.form', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityFormRequest $request)
    {
        $dataForm = $request->all();
        $user = Auth::user();
        $dataForm['user_id'] = $user->id;
        $city = $this->city->create($dataForm);

        if ($city) {
            return redirect('/cidades')->with('success', 'Cidade criada com sucesso!');
        } else {
            return redirect('/cidades')->with('fail', 'Falha ao criar cidade!');
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
        $city = $this->city->findOrFail($id);

        $this->authorize('update', $city);

        $title = $this->title;
        $subtitle = "Adicionar cidade";
        return view('admin.cities.form', compact('title', 'subtitle', 'city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityFormRequest $request, $id)
    {
        $city = $this->city->findOrFail($id);
        $this->authorize('update', $city);

        $dataForm = $request->all();

        if ($city->update($dataForm)) {
            return redirect('/cidades')->with('success', 'Cidade atualizada com sucesso!');
        } else {
            return redirect('/cidades')->with('fail', 'Falha ao atualizar cidade!');
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
        $city = $this->city->findOrFail($id);
        $this->authorize('delete', $city);

        if ($this->city->destroy($id)) {
            return redirect('/cidades')->with('success', 'Cidade excluída com sucesso!');
        }

        return redirect('/cidades')->with('fail', 'Falha ao excluir cidade!');
    }
}
