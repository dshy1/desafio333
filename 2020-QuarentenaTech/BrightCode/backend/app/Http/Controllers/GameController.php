<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Sala;
use App\Models\Jogada;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameController extends Controller
{

    private $cookie_user;

    public function createGuestCookie($nick){
        $quantidade = (Guest::all()->count()) + 1;
        // dd($quantidade);
        $guest = new Guest;
        $token = Str::random(64);
        $nick = isset($nick) ? $nick : 'Guest #' . $quantidade;
        $guest->nick = $nick;
        while (Guest::where('token', '=', $token)->count() > 0) {
            $token = Str::random(64);
        }
        $guest->token = $token;
        $guest->save();
        $dados = ['id' => $guest->id, 'nick' => $guest->nick, 'token' => $token];
        $dados = json_encode($dados);
        $minutes = 2000;
        $this->cookie_user = cookie('guest_user', $dados, $minutes);
        return $dados;
     }

     public function crirarGuest(request $request){
        $return = $this->createGuestCookie($request->nick);
        // $return = json_decode($return);
        $dados = json_decode($return);
        $dados = ['status' => 'OK', 'user' => $dados->id, 'tipo_user' => 'G'];
        return $dados;
     }

     public function verificaJogadorLogado(){
        if(isset(Auth::user()->id) AND Auth::user()->id != ''){
            $user = Auth::user()->id;
            $dados = ['status' => 'OK', 'user' => $user->id, 'tipo_user' => 'U'];
        }else{
            if($guest = Cookie::get('guest_user')){
                $this->cookie_user = $guest;
                $guest = json_decode($guest);
                $find = Guest::where('id', '=', $guest->id)->where('token', '=', $guest->token)->first();
                if(isset($find) and $find != ''){
                    $dados = ['status' => 'OK', 'user' => $guest->id, 'tipo_user' => 'G'];
                }else{
                    $dados = ['status' => 'ERROR', 'redirect' => 'login'];
                }
            }else{
                $dados = ['status' => 'ERROR', 'redirect' => 'login'];
            }
            return $dados;
        }
     }

     public function verificaJogadorEmSala($jogador){
        $user =  strval($jogador['user']);
        $tipo =  strval($jogador['tipo_user']);

        $results = Sala::where('status', '=', 'A')->where('jogador_x', '=', $user)->where('tipo_jogador_x', '=', $tipo)->first();
        if(!$results){
            $results = Sala::where('status', '=', 'A')->where('jogador_o', '=', $user)->where('tipo_jogador_o', '=', $tipo)->first();
        }

        if($results){
            return $results;
        }else{
            return ;
        }
     }


     public function verificaJogadas($mesa){
        $jogadas = Jogada::where('mesa_id', '=', $mesa)->orderBy('updated_at', 'asc')->get();
        return response()->json($jogadas);
     }

     public function verificaVez($mesa, $jogador){
        $vez_de = Jogada::where('mesa_id', '=', $mesa)->orderBy('updated_at', 'desc')->first();
        if($jogador['user'] == $vez_de->jogador_id AND $jogador['tipo_user'] == $vez_de->tipo_jogador){
            return true;
        }else{
            return false;
        }
     }


     /*
      *  COMEÇO DAS FUNÇÕES DO GAME
      */


    public function criarSala(request $request){
        $jogador = $this->verificaJogadorLogado();

        if($jogador['status'] == 'OK'){
            $has_lobby = $this->verificaJogadorEmSala($jogador);

            if(isset($jogador['status']) AND $jogador['status'] == 'OK'){
                if(!$has_lobby){
                    $sala = new Sala;
                    $sala->jogador_x = $jogador['user'];
                    $sala->tipo_jogador_x = $jogador['tipo_user'];
                    $token = Str::random(10);
                    while (Sala::where('codigo_sala', '=', $token)->count() > 0) {
                        $token = Str::random(10);
                    }
                    $sala->codigo_sala = $token;
                    $sala->save();
                    $sala_info = ['token' => $sala->codigo_sala];
                    $resultado = [
                        'status' =>  'sucesso',
                        'msg' => 'sala_criada',
                        'dados' => [
                            'sala' => $sala_info
                            ]
                        ];
                }else{
                    $resultado = [
                        'status' =>  'sucesso',
                        'msg' => 'tem_sala',
                        'dados' => [
                            'sala' => $has_lobby->codigo_sala
                            ]
                        ];
                }
            }else{
                $resultado = ['status' =>  'erro', 'msg' => 'erro ao criar sala #10001'];
            }
            return response()->json($resultado)->withCookie($this->cookie_user);
        }
        return response()->json(['status' => 'ERRO', 'redirect' => 'login']);
    }

    public function entrarNaSala($token){
        $sala = Sala::where('token', '=', $token)->first();
        if($sala){
            $jogador = $this->verificaJogadorLogado();
            if($jogador['status'] == 'OK'){
                if($sala->status != 'A'){
                    $jogadas = $this->verificaJogadas($sala->id);
                }else{
                    $jogadas = ['status' => 'sucesso', 'msg' => 'aguardando_jogadas'];
                }
                $vez_de = $this->verificaVez($sala->id, $jogador);
                if(!isset($sala->jogador_x) OR $sala->jogador_x == ''){
                    $sala->jogador_x = $jogador['user'];
                    $sala->tipo_jogador_x = $jogador['tipo_user'];
                    $sala->update();
                    $dados = [
                        'status' => 'entrou_sala',
                        'msg' => 'Entrou na sala',
                        'dados' => [
                            'sala' => $sala->token,
                            'jogador_tipo' => 'X'
                        ],
                        'jogadas' => $jogadas
                        ];
                }elseif(!isset($sala->jogador_o) OR $sala->jogador_o == ''){
                    $sala->jogador_o = $jogador['user'];
                    $sala->tipo_jogador_o = $jogador['tipo_user'];
                    $sala->update();
                    $dados = [
                        'status' => 'entrou_sala',
                        'msg' => 'Entrou na sala',
                        'dados' => [
                            'sala' => $sala->token,
                            'jogador_tipo' => 'O'
                        ],
                        'jogadas' => $jogadas
                        ];
                }else{
                    $dados = [
                        'status' => 'sala_cheia',
                        'msg' => 'Mesa Cheia',
                        'redirect' => 'lobby',
                        ];
                }
            }else{
                $dados = [
                    'status' => 'ERRO',
                    'msg' => 'Usuario não autenticado',
                    'dados' => [
                        'sala' => $sala->token]
                    ];
            }
        }else{
            $dados = [
                'status' => 'ERRO',
                'msg' => 'sala_inexistente',
                'redirect' => 'lobby'
            ];
        }
        return response()->json($dados);
    }
}
