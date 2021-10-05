<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\MatchRequest;
use App\Components\FlashMessages;

class Matches extends Controller
{
    use FlashMessages;

    public $routes = [
        'index'=>'matches',
        'create'=>'matchCreate',
        'store'=>'matchStore',
        'show'=>'matchShow',
        'edit'=>'matchEdit',
        'update'=>'matchUpdate',
        'updateClosed'=>'matchUpdateClosed',
        'destroy'=>'matchDestroy'
    ];

    public $fields = [
        'name' => ['type'=>'varchar', 'label'=>'Partida', 'enabled'=>'1', 'position'=>1, 'notnull'=>0, 'visible'=>1,],
        'match_at' => ['type'=>'datetime', 'label'=>'Data da partida', 'enabled'=>'1', 'position'=>2, 'notnull'=>0, 'visible'=>1],
        'location' => ['type'=>'varchar', 'label'=>'Quadra', 'enabled'=>'1', 'position'=>3, 'notnull'=>0, 'visible'=>1,],
        'address' => ['type'=>'varchar', 'label'=>'Endereço', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1,],
        'created_team_at' => ['type'=>'datetime', 'label'=>'Sorteio', 'enabled'=>'1', 'position'=>5, 'notnull'=>0, 'visible'=>5],
        'closed_at' => ['type'=>'datetime', 'label'=>'Partida encerrada', 'enabled'=>'1', 'position'=>5, 'notnull'=>0, 'visible'=>4],
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $routes = &$routes;
        $fields = &$fields;
    }

    /**
     * Proccess Fields to generate array
     *
     * @return array
     */
    public function process_fields($fields) {
        foreach ($fields as $key => $value) {
            /*
            if (isset($value['arraykeyval']) && count($value['arraykeyval']) >= 1) {
                foreach ($value['arraykeyval'] as $k => $v) {
                    $value[$k] = $v;
                }
            }
            */
            $list[$key] = $value;
        }
        return $list;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Match::all();

        if (count($data) >= 1) {
            foreach ($data as $key => $value) {
                if (!empty($value->match_at)) {
                    $date = explode(' ', $value->match_at);
                    $time = substr($date[1], 0, -3);
                    $date = $date[0];
                    $date = implode('/', array_reverse(explode('-', $date)));
                    $value->match_at_vw = "{$date} {$time}";
                } else { $value->match_at_vw = ''; }
                if (!empty($value->created_team_at)) {
                    $date = explode(' ', $value->created_team_at);
                    $time = substr($date[1], 0, -3);
                    $date = $date[0];
                    $date = implode('/', array_reverse(explode('-', $date)));
                    $value->created_team_at_vw = "{$date} {$time}";
                } else { $value->created_team_at_vw = ''; }
                if (!empty($value->closed_at)) {
                    $date = explode(' ', $value->closed_at);
                    $time = substr($date[1], 0, -3);
                    $date = $date[0];
                    $date = implode('/', array_reverse(explode('-', $date)));
                    $value->closed_at_vw = "{$date} {$time}";
                } else { $value->closed_at_vw = ''; }
                $result[] = $value;
            }
            $data  = $result;
        } else {
            $data = [];
        }

        if ($request->dataReturn == "json") {
            return json_encode(['data' => $data]);
        } else {
            $fields = $this->fields;
            $headTitle = 'Partidas';
            $title = "Lista de partidas";
            $routes = $this->routes;
            $here = 'match';
            $newReg = true;
            return view('common_pages.ajax_index', compact(['headTitle','title','here','routes','fields','data','newReg']));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $result = false;
        $data = false;
        $headTitle = 'Partida';
        $title = "Cadastro de partida";
        $here = 'match';
        $routes = $this->routes;
        $method = false;
        $fields = $this->process_fields($this->fields);

        return view('common_pages.create', compact(['headTitle','title','here','routes','fields','method','result','data']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MatchRequest $request)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $data = Match::create($request->all());

        if ($data) {
            $created_by = Match::find($data['id']);
            $created_by->update(['created_by' => Auth::user()->id]);
            $created_by->save();
        }

        $message = 'Partida criada com sucesso';
        $status = 'success';
        if ($request->dataReturn == "json") {
            $data = [
                'data' => $data,
                'message' => $message,
                'status' => $status
            ];
            return $data;
        } else {
            self::message($status, $message);
            return redirect()->route('matches');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $data = Match::where('id','=',$id)->get();
        $data = (array)json_decode($data[0]);

        $result['name'] = $data['name'];

        if (!empty($data['match_at'])) {
            $data['match_at'] = str_replace(' ', 'T', $data['match_at']);
        }

        if (!empty($data['closed_at'])) {
            $data['closed_at'] = str_replace(' ', 'T', $data['closed_at']);
        }

        if ($request->dataReturn == "json") {
            $data = [
                'data' => $data,
                'status' => 'success'
            ];
            return $data;
        } else {
            $headTitle = 'Partida';
            $title = "Editar partida";
            $here = 'match';
            $routes = $this->routes;
            $method = 'PUT';
            $fields = $this->process_fields($this->fields);

            return view('common_pages.edit', compact(['headTitle','title','here','routes','fields','method','data','result']));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MatchRequest $request, $id)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        if ($request->restore == 1) {
            $data = Match::onlyTrashed()->where('id','=',$id)->get();
            $data[0]->restore();
            $restored_by = Match::find($id);
            $restored_by->update(['restored_by' => Auth::user()->id]);
            $restored_by->save();
            return redirect()->route('players');
        } elseif ($request->force_delete == 1) {
            $data = Match::onlyTrashed()->where('id','=',$id)->get();
            $data[0]->forceDelete();
            return redirect()->route('players');
        } else {
            $data = Match::find($id);
            $data->fill($request->all());
            $data->update(['updated_by' => Auth::user()->id]);
            $data->save();

            $message = 'Partida atualizada com sucesso';
            $status = 'success';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => $data,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message($status, $message);
                return redirect()->route('matches');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateClosed(Request $request, $id)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $data = Match::find($id);
        $data->fill([
            'closed_at'=>date('Y-m-d H:m:i', time()),
            'updated_by' => Auth::user()->id
        ]);
        $data->save();
        $data['closed_at'] = date('Y-m-d H:m', time());
        $data['closed_at_vw'] = date('d/m/Y H:m', time());

        $message = 'Partida encerrada com sucesso';
        $status = 'success';
        if ($request->dataReturn == "json") {
            $data = [
                'data' => $data,
                'message' => $message,
                'status' => $status
            ];
            return $data;
        } else {
            self::message($status, $message);
            return redirect()->route('presences');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $deleted_by = Match::find($id);
        $deleted_by->update(['deleted_by' => Auth::user()->id]);
        $deleted_by->save();

        $data = Match::destroy($id);

        $message = 'Partida apagada com sucesso';
        $status = 'success';
        if ($request->dataReturn == "json") {
            $data = [
                'data' => $id,
                'message' => $message,
                'status' => $status
            ];
            return $data;
        } else {
            self::message($status, $message);
            return redirect()->route('matches');
        }
    }
}
