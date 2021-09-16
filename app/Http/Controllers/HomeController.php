<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ServiceOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $queyIncome = ServiceOrder::query();
        $queyExpense = Expense::query();
        $displacementTotal = 0;
        $serviceTotal = 0;

        $year = now()->year;
        $month = '';

        if ($request->input('year')) {
            $year = $request->input('year');
        }

        if ($request->input('month')) {
            if ($request->input('month') != 30) {
                $month = $request->input('month');
            }
        } else {
            $month = now()->month;
        }

        if ($month) {
            $queyIncome->where('user_id', Auth::user()->id)->whereYear('published_at', $year)->whereMonth('published_at', $month);
        } else {
            $queyIncome->where('user_id', Auth::user()->id)->whereYear('published_at', $year);
        }

        $allOrders = json_decode(json_encode($this->allServiceOrders($year, $month)), true);

        foreach ($allOrders as $key => $order) {
            $displacementTotal += $order['displacement'];
            $serviceTotal += $order['amount'];
        }

        $incomes = $queyIncome->get()->groupBy(function ($incomesByMonth) {
            return Carbon::parse($incomesByMonth->published_at)->format('m'); // grouping by months
        })->map(function ($incomes) {
            $incomes = $incomes->toArray();
            $summed = 0;

            foreach ($incomes as $serviceOrder) {
                $summed += $serviceOrder['amount'];
                $summed += $serviceOrder['displacement'];
            }

            return $summed;
        });

        if ($month) {
            $queyExpense->where('user_id', Auth::user()->id)->whereYear('date', $year)->whereMonth('date', $month);
        } else {
            $queyExpense->where('user_id', Auth::user()->id)->whereYear('date', $year);
        }

        $expenses = $queyExpense->get()->groupBy(function ($expensesByMonth) {
            return Carbon::parse($expensesByMonth->date)->format('m'); // grouping by months
        })->map(function ($expenses) {
            $expenses = $expenses->toArray();
            $summed = 0;

            foreach ($expenses as $expense) {
                $summed += $expense['amount'];
            }

            return $summed;
        });

        $years = ServiceOrder::select(DB::raw('YEAR(created_at) as year'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('created_at')
            ->get()
            ->pluck('year', 'year')->toArray();

        $cities = $this->incomesGeralCities($year, $month);
        $clients = $this->incomesGeralClients($year, $month);
        $services = $this->incomesGeralServices($year, $month);
        $osMonths = $this->countOsMonths();

        return view('home', compact('years', 'year', 'month', 'incomes', 'expenses', 'cities', 'clients', 'services', 'osMonths', 'displacementTotal', 'serviceTotal'));
    }

    public function countOsMonths()
    {
        $osMonths = DB::select("SELECT count(id) as qtd_os, month(published_at) as month
        FROM service_orders
        where user_id = " . Auth::user()->id . "
        group by month(published_at)");

        foreach ($osMonths as $osm) {
            switch ($osm->month) {
                case '1':
                    $osm->month = 'Janeiro';
                    break;
                case '2':
                    $osm->month = 'Fevereiro';
                    break;
                case '3':
                    $osm->month = 'Março';
                    break;
                case '4':
                    $osm->month = 'Abril';
                    break;
                case '5':
                    $osm->month = 'Maio';
                    break;
                case '6':
                    $osm->month = 'Junho';
                    break;
                case '7':
                    $osm->month = 'Julho';
                    break;
                case '8':
                    $osm->month = 'Agosto';
                    break;
                case '9':
                    $osm->month = 'Setembro';
                    break;
                case '10':
                    $osm->month = 'Outubro';
                    break;
                case '11':
                    $osm->month = 'Novembro';
                    break;
                case '12':
                    $osm->month = 'Dezembro';
                    break;
                default:
                    # code...
                    break;
            }
        }
        
        return $osMonths;
    }

    public function allServiceOrders($year, $month)
    {
        $month = $month ? "and MONTH(T0.delivered_at) = " . $month : '';
        $serviceOrders = DB::select("SELECT *
        FROM service_orders T0
        where T0.user_id = " . Auth::user()->id . "
        AND YEAR(T0.delivered_at) = " . $year . "
        " . $month . "
        order by T0.amount");

        return $serviceOrders;
    }

    public function incomesGeralServices($year, $month)
    {
        $month = $month ? "and MONTH(T0.delivered_at) = " . $month : '';
        $services = DB::select("SELECT T1.name, SUM(T0.amount) as total
        FROM service_orders T0 inner join services T1 on T0.service_id = T1.id
        where T0.user_id = " . Auth::user()->id . "
        AND YEAR(T0.delivered_at) = " . $year . "
        " . $month . "
        group by T0.service_id
        order by T0.amount desc
        limit 5");

        return $services;
    }

    public function incomesGeralCities($year, $month)
    {
        $month = $month ? "and MONTH(T0.delivered_at) = " . $month : '';
        $cities = DB::select("SELECT T1.name, SUM(T0.amount) as total
        FROM service_orders T0 inner join cities T1 on T0.city_id = T1.id
        where T0.user_id = " . Auth::user()->id . "
        AND YEAR(T0.delivered_at) = " . $year . "
        " . $month . "
        group by T0.city_id
        order by T0.amount desc
        limit 5");

        return $cities;
    }

    public function incomesGeralClients($year, $month)
    {
        $month = $month ? "and MONTH(T0.delivered_at) = " . $month : '';
        $clients = DB::select("SELECT T1.name, SUM(T0.amount) as total
        FROM service_orders T0 inner join clients T1 on T0.client_id = T1.id
        where T0.user_id = " . Auth::user()->id . "
        AND YEAR(T0.delivered_at) = " . $year . "
        " . $month . "
        group by T0.client_id
        order by T0.amount desc
        limit 5");

        return $clients;
    }

    public function incomesByCities(Request $request)
    {
        $queyIncome = ServiceOrder::query();
        $totalIncomes = 0;
        $year = now()->year;

        if ($request->input('year')) {
            $year = $request->input('year');
        }

        $queyIncome->where('user_id', Auth::user()->id)->whereYear('published_at', $year);

        $incomes = $queyIncome->with(['city'])->get()->groupBy(function ($date) {
            return $date->city->name; // grouping by citys
        })->map(function ($incomes) {
            $months = ['01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0, 'total' => 0];

            foreach ($incomes as $serviceOrder) {
                $monthIncome = ($serviceOrder->displacement + $serviceOrder->amount);
                $months[Carbon::parse($serviceOrder->published_at)->format('m')] += $monthIncome;
                $months['total'] += $monthIncome;
            }

            return $months;
        });

        foreach ($incomes as $key => $serviceOrder) {
            $totalIncomes += $serviceOrder['total'];
        }

        $incomes = collect($incomes)->sortBy('total')->reverse();

        $years = ServiceOrder::select(DB::raw('YEAR(published_at) as year'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('published_at')
            ->get()
            ->pluck('year', 'year')->toArray();

        return view('reports.cities', compact('years', 'year', 'incomes', 'totalIncomes'));
    }

    public function incomesByClients(Request $request)
    {
        $queyIncome = ServiceOrder::query();
        $totalIncomes = 0;
        $year = now()->year;

        if ($request->input('year')) {
            $year = $request->input('year');
        }

        $queyIncome->where('user_id', Auth::user()->id)->whereYear('published_at', $year);

        $incomes = $queyIncome->with(['client'])->get()->groupBy(function ($date) {
            return $date->client->name; // grouping by clients
        })->map(function ($incomes) {
            $months = ['01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0, 'total' => 0];

            foreach ($incomes as $serviceOrder) {
                $monthIncome = ($serviceOrder->displacement + $serviceOrder->amount);
                $months[Carbon::parse($serviceOrder->published_at)->format('m')] += $monthIncome;
                $months['total'] += $monthIncome;
            }

            return $months;
        });

        foreach ($incomes as $key => $serviceOrder) {
            $totalIncomes += $serviceOrder['total'];
        }

        $incomes = collect($incomes)->sortBy('total')->reverse();

        $years = ServiceOrder::select(DB::raw('YEAR(published_at) as year'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('published_at')
            ->get()
            ->pluck('year', 'year')->toArray();

        return view('reports.clients', compact('years', 'year', 'incomes', 'totalIncomes'));
    }

    public function incomesByServices(Request $request)
    {
        $queyIncome = ServiceOrder::query();
        $totalIncomes = 0;
        $year = now()->year;

        if ($request->input('year')) {
            $year = $request->input('year');
        }

        $queyIncome->where('user_id', Auth::user()->id)->whereYear('published_at', $year);

        $incomes = $queyIncome->with(['service'])->get()->groupBy(function ($date) {
            return $date->service->name; // grouping by services
        })->map(function ($incomes) {
            $months = ['01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0, 'total' => 0];

            foreach ($incomes as $serviceOrder) {
                $monthIncome = ($serviceOrder->displacement + $serviceOrder->amount);
                $months[Carbon::parse($serviceOrder->published_at)->format('m')] += $monthIncome;
                $months['total'] += $monthIncome;
            }

            return $months;
        });

        foreach ($incomes as $key => $serviceOrder) {
            $totalIncomes += $serviceOrder['total'];
        }

        $incomes = collect($incomes)->sortBy('total')->reverse();

        $years = ServiceOrder::select(DB::raw('YEAR(published_at) as year'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('published_at')
            ->get()
            ->pluck('year', 'year')->toArray();

        return view('reports.services', compact('years', 'year', 'incomes', 'totalIncomes'));
    }
}
