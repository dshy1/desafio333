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
    public function trocaTokenPorID($dados){
        if($dados->tipo_user == 'G'){
            $guest =  Guest::where('token', '=', $dados->user)->first();
            if(isset($guest) AND $guest != ''){
                $guest =  Guest::where('token', '=', $dados->user)->first();
                return ['user' => $guest->id, 'tipo_user' => 'G'];
            }
        }else{
            return 'NO_EXISTENTE';
        }

    }

    public function createGuestCookie($nick){
        $quantidade = (Guest::all()->count()) + 1;
        // dd($quantidade);
        $guest = new Guest;
        $token = Str::random(64);
        $nick = isset($nick) ? $nick : 'Guest #' . $quantidade;
        $guest->nick = $nick . ' #'.$quantidade;
        while (Guest::where('token', '=', $token)->count() > 0) {
            $token = Str::random(64);
        }
        $guest->token = $token;
        $guest->save();
        $dados = ['user' => $guest->token, 'nick' => $guest->nick];
        $dados = json_encode($dados);
        return $dados;
     }

     public function criarGuest(request $request){
        $return = $this->createGuestCookie($request->nick);
        // $return = json_decode($return);
        $dados = json_decode($return);
        $dados = ['status' => 'OK', 'user_token' => $dados->user, 'tipo_user' => 'G'];
        return $dados;
     }

     public function verificaJogadorLogado($request){
        if(isset($request->user) AND $request->user != ''){
            if($request->tipo_user == 'U'){
                // $user = Auth::where('t');
                $dados = ['status' => 'ERR', 'user' => 'USEROFF LN 50', 'tipo_user' => 'U'];
            }elseif($request->tipo_user == 'G'){
                $guest = Guest::where('token', '=', $request->user)->first();
                if(isset($guest) and $guest != ''){
                    $dados = ['status' => 'OK', 'user' => $guest->id, 'tipo_user' => 'G'];
                }else{
                    $dados = ['status' => 'ERROR', 'redirect' => 'login'];
                }
            }
        }else{
            $dados = ['status' => 'ERROR', 'redirect' => 'login'];
        }
        return $dados;
     }

     public function verificaJogadorEmSala($jogador){
        $verifica = $this->trocaTokenPorID($jogador);
        $user = $verifica['user'];
        $tipo =  $verifica['tipo_user'];

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
        $jogadas = Jogada::where('sala_id', '=', $mesa)->orderBy('updated_at', 'asc')->get();
        return response()->json($jogadas);
     }

     public function verificaVez($mesa, $jogador){
        $vez_de = Jogada::where('sala_id', '=', $mesa)->orderBy('updated_at', 'desc')->first();
        if(isset($vez_de) AND $vez_de != ''){
            if($jogador['user'] == $vez_de->jogador_id AND $jogador['tipo_user'] == $vez_de->tipo_jogador){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }

     }


     /*
      *     COMEÇO DAS FUNÇÕES DO GAME
      */


    public function criarSala(request $request){
        // return $request;
        $jogador = $this->verificaJogadorLogado($request);

        if($jogador['status'] == 'OK'){
            $has_lobby = $this->verificaJogadorEmSala($request);
            // return $has_lobby;

            if(isset($jogador['status']) AND $jogador['status'] == 'OK'){
                // return 'oi';
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
                        'status' =>  'erro',
                        'msg' => 'ja_esta_em_sala',
                        'dados' => [
                            'sala' => $has_lobby->codigo_sala
                            ]
                        ];
                }
            }else{
                $resultado = ['status' =>  'erro', 'msg' => 'erro ao criar sala #10001'];
            }
            return response()->json($resultado);
        }
        return response()->json(['status' => 'login_invalido', 'msg' => 'Erro no login','redirect' => 'login']);
    }

    public function entrarNaSala(request $request, $token){
        $sala = Sala::where('codigo_sala', '=', $token)->first();
        if($sala){
            $jogador = $this->verificaJogadorLogado($request);

            if($sala->status != 'A'){
                $jogadas = $this->verificaJogadas($sala->id);
            }else{
                $jogadas = ['status' => 'aguardando_jogador'];
            }

            if($jogador['status'] == 'OK'){
                if(($sala->jogador_x == $jogador['user'] AND $sala->tipo_jogador_x == $jogador['tipo_user'])){
                    $dados = [
                        'status' => 'ja_esta_sala',
                        'msg' => 'Já está na sala',
                        'dados' => [
                            'sala' => $sala->codigo_sala,
                            'jogador_tipo' => 'X'
                        ],
                        'sala' => $jogadas
                        ];
                    return $dados;
                }elseif($sala->jogador_o == $jogador['user'] AND $sala->tipo_jogador_o == $jogador['tipo_user']){
                    $dados = [
                        'status' => 'ja_esta_sala',
                        'msg' => 'Já está na sala',
                        'dados' => [
                            'sala' => $sala->codigo_sala,
                            'jogador_tipo' => 'O'
                        ],
                        'sala' => $jogadas
                        ];
                    return $dados;
                }
                // $vez_de = $this->verificaVez($sala->id, $jogador);
                if(!isset($sala->jogador_x) OR $sala->jogador_x == ''){
                    $sala->jogador_x = $jogador['user'];
                    $sala->tipo_jogador_x = $jogador['tipo_user'];
                    $sala->update();
                    $dados = [
                        'status' => 'entrou_sala',
                        'msg' => 'Entrou na sala',
                        'dados' => [
                            'sala' => $sala->codigo_sala,
                            'jogador_tipo' => 'X'
                        ],
                        'sala' => $jogadas
                        ];
                }elseif(!isset($sala->jogador_o) OR $sala->jogador_o == ''){
                    $sala->jogador_o = $jogador['user'];
                    $sala->tipo_jogador_o = $jogador['tipo_user'];
                    $sala->update();
                    $dados = [
                        'status' => 'entrou_sala',
                        'msg' => 'Entrou na sala',
                        'dados' => [
                            'sala' => $sala->codigo_sala,
                            'jogador_tipo' => 'O'
                        ],
                        'sala' => $jogadas
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
                    'status' => 'sem_autenticacao',
                    'msg' => 'Usuario não autenticado',
                    'dados' => [
                        'sala' => $sala->codigo_sala
                    ],
                    'sala' => $jogadas
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
