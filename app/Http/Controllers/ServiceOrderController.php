<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceOrderFormRequest;
use App\Models\ServiceOrder;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Client;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ImportOSService;

class ServiceOrderController extends Controller
{
    protected $serviceOrder;
    protected $title;

    public function __construct(ServiceOrder $serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;
        $this->title = 'Ordens de serviço';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = $this->title;
        $year = $request->input('year');
        $client_id = $request->input('client_id');
        $city_id = $request->input('city_id');
        $service_id = $request->input('service_id');
        $month = $request->input('month');

        $quey = $this->serviceOrder->query();

        $quey->where('user_id', Auth::user()->id);

        if (is_null($year)) {
            $year = now()->year;
        }

        if (is_null($month)) {
            $month = now()->month;
        }

        $quey->whereYear('published_at', $year);

        if ($month) {
            $quey->whereMonth('published_at', $month);
        }

        if ($client_id) {
            $quey->where('client_id', $client_id);
        }

        if ($city_id) {
            $quey->where('city_id', $city_id);
        }

        if ($service_id) {
            $quey->where('service_id', $service_id);
        }

        $serviceOrders = $quey->get();

        $years = $this->serviceOrder
            ->select(DB::raw('YEAR(published_at) as year'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('published_at')
            ->get()
            ->pluck('year', 'year')->toArray();

        $clients = $this->serviceOrder
            ->select(DB::raw('client_id'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('client_id')
            ->with('client')
            ->get()
            ->pluck('client.name', 'client.id')->toArray();

        $cities = $this->serviceOrder
            ->select(DB::raw('city_id'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('city_id')
            ->with('city')
            ->get()
            ->pluck('city.name', 'city.id')->toArray();

        $services = $this->serviceOrder
            ->select(DB::raw('service_id'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('service_id')
            ->with('service')
            ->get()
            ->pluck('service.name', 'service.id')->toArray();

        $data = [
            'title' => $title,
            'serviceOrders' => $serviceOrders,
            'years' => $years,
            'year' => $year,
            'month' => $month,
            'clients' => $clients,
            'client_id' => $client_id,
            'cities' => $cities,
            'city_id' => $city_id,
            'services' => $services,
            'service_id' => $service_id
        ];

        return view('admin.service-orders.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title;
        $subtitle = "Adicionar ordem de serviço";
        $cities = City::where('user_id', Auth::user()->id)->pluck('name', 'id');
        $clients = Client::where('user_id', Auth::user()->id)->pluck('name', 'id');
        $services = Service::where('user_id', Auth::user()->id)->pluck('name', 'id');

        return view('admin.service-orders.form', compact('title', 'subtitle', 'cities', 'clients', 'services'));
    }

    /*
    * Exibe formulário para importar arquivo txt
    */
    public function import()
    {
        $title = $this->title;
        $subtitle = "Importar arquivo .txt com ordens de serviço";

        return view('admin.service-orders.import', compact('title', 'subtitle'));
    }

    /*
    * Exibe formulário para importar arquivo txt
    */
    public function importTxt(Request $request)
    {
        $title = $this->title;
        $subtitle = "Importar arquivo .txt com ordens de serviço";

        foreach ($request->file('txt') as $file) {
            $file_contents = file_get_contents($file->getRealPath());

            $lines = array_filter(preg_split('/\n|\r\n?/', $file_contents));

            $import_service = new ImportOSService();
            $data = $import_service->returnDataOSFromTXT($lines);

            //Valida se já foi inserida a OS
            $os = $this->serviceOrder::where('code', $data['code'])->first();

            //Já existe a OS
            if($os != null){
                $error = 'Já existe uma OS com o código: '.$data['code'];

                return view('admin.service-orders.import', compact('error', 'title', 'subtitle'));
            }

            $user = Auth::user();
            $data['user_id'] = $user->id;

            //Search client reference
            $client = Client::where('name', utf8_encode($data['client_name']))->first();

            if($client == null){
                //Cria um cliente com o nome vindo do arquivo
                $clienteData['user_id'] = $user->id;
                $clienteData['name'] = $data['client_name'];
                $client = Client::create($clienteData);
            }

            $data['client_id'] = $client->id;

            //Search city reference
            $city = City::where('name', $data['city_name'])->first();

            if($city == null){
                //Cria uma cidade com nome vindo do arquivo
                $cityData['user_id'] = $user->id;
                $cityData['name'] = $data['city_name'];
                $cityData['state'] = $data['uf_name'];
                $city = City::create($cityData);
            }

            $data['city_id'] = $city->id;

            //Search service reference
            $service = Service::where('name', $data['service_name'])->first();

            if($service == null){
                //Cria um serviço com o nome vindo do arquivo
                $dataService['user_id'] = $user->id;
                $dataService['name'] = $data['service_name'];
                $service = Service::create($dataService);
            }

            $data['service_id'] = $service->id;

            $data['inspection'] = 0;
            $data['report'] = 0;
            $data['finished'] = 0;

            $serviceOrder = $this->serviceOrder->create($data);
        }

        return \Redirect::route('ordens-de-servico.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceOrderFormRequest $request)
    {
        $user = Auth::user();

        $dataForm = $request->all();
        $dataForm['user_id'] = $user->id;
        $dataForm['published_at'] = date('Y-m-d', strtotime($dataForm['published_at']));
        if (isset($dataForm['scheduled_at'])) {
            $dataForm['scheduled_at'] = date('Y-m-d', strtotime($dataForm['scheduled_at']));
        } else {
            $dataForm['scheduled_at'] = null;
        }
        $dataForm['amount'] = (float)$dataForm['amount'];
        $dataForm['displacement'] = (float)$dataForm['displacement'];

        if (isset($dataForm['delivered_at'])) {
            $dataForm['delivered_at'] = date('Y-m-d', strtotime($dataForm['delivered_at']));
        }

        $serviceOrder = $this->serviceOrder->create($dataForm);

        if ($serviceOrder) {
            return redirect('/ordens-de-servico')->with('success', 'Ordem de serviço criada com sucesso!');
        } else {
            return redirect('/news')->with('fail', 'Falha ao criar ordem de serviço!');
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
        $serviceOrder = $this->serviceOrder->findOrFail($id);
        $this->authorize('update', $serviceOrder);

        $title = $this->title;
        $subtitle = "Adicionar ordem de serviço";

        $cities = City::where('user_id', Auth::user()->id)->pluck('name', 'id');
        $clients = Client::where('user_id', Auth::user()->id)->pluck('name', 'id');
        $services = Service::where('user_id', Auth::user()->id)->pluck('name', 'id');

        return view('admin.service-orders.form', compact('title', 'subtitle', 'serviceOrder', 'cities', 'clients', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceOrderFormRequest $request, $id)
    {
        $serviceOrder = $this->serviceOrder->findOrFail($id);
        $this->authorize('update', $serviceOrder);

        $dataForm = $request->all();

        $dataForm['published_at'] = date('Y-m-d', strtotime($dataForm['published_at']));
        if (isset($dataForm['scheduled_at'])) {
            $dataForm['scheduled_at'] = date('Y-m-d', strtotime($dataForm['scheduled_at']));
        } else {
            $dataForm['scheduled_at'] = null;
        }
        $dataForm['amount'] = (float)$dataForm['amount'];
        $dataForm['displacement'] = (float)$dataForm['displacement'];

        if (isset($dataForm['delivered_at'])) {
            $dataForm['delivered_at'] = date('Y-m-d', strtotime($dataForm['delivered_at']));
        }

        if ($serviceOrder->update($dataForm)) {
            return redirect('/ordens-de-servico')->with('success', 'Ordem de serviço atualizada com sucesso!');
        } else {
            return redirect('/ordens-de-servico')->with('fail', 'Falha ao atualizar ordem de serviço!');
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
        $serviceOrder = $this->serviceOrder->findOrFail($id);
        $this->authorize('delete', $serviceOrder);

        if ($this->serviceOrder->destroy($id)) {
            return redirect('/ordens-de-servico')->with('success', 'Ordem de serviço excluída com sucesso!');
        }

        return redirect('/ordens-de-servico')->with('fail', 'Falha ao excluir ordem de serviço!');
    }

    /**
     * Update the specified collumn of resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $value
     * @param  varchar  $type
     * @return \Illuminate\Http\Response
     */
    public function updateOS($id, $type, $value)
    {
        switch ($type) {
            case 'r':
                $val = $value == 1 ? 0 : 1;
                DB::table('service_orders')
                ->where('id', $id)
                ->update(['report' => $val]);
                break;

            case 'i':
                $val = $value == 1 ? 0 : 1;
                DB::table('service_orders')
                ->where('id', $id)
                ->update(['inspection' => $val]);
                break;

            case 'f':
                $val = $value == 1 ? 0 : 1;
                DB::table('service_orders')
                ->where('id', $id)
                ->update(['finished' => $val]);
                break;

            case 'd':
                $val = $value;
                DB::table('service_orders')
                ->where('id', $id)
                ->update(['delivered_at' => $val]);
                break;

            case 's':
                $val = $value;
                DB::table('service_orders')
                ->where('id', $id)
                ->update(['scheduled_at' => $val]);
                break;

            default:
                echo 'Opção inválida';
                break;
        }
    }
}
