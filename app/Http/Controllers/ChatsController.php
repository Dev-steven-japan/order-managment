<?php

namespace App\Http\Controllers;

use App\Events\NuevoMensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function getListarContactos(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $nIdAuthUser=   Auth::id();

        $rpta       =   DB::select('call sp_Chat_getListarContactos (?)', [$nIdAuthUser]);
        return $rpta;
    }

    public function getListarConversaciones(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $nIdContacto    =   $request->nIdContacto;
        $nIdAuthUser    =   Auth::id();

        $rpta       =   DB::select('call sp_Chat_getListarConversaciones (?, ?)',
                                                                [
                                                                    $nIdContacto,
                                                                    $nIdAuthUser
                                                                ]);
        return $rpta;
    }

    public function setRegistrarMensaje(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $from   =   Auth::id();
        $to     =   $request->nIdContacto;
        $text   =   $request->cMensaje;

        $rpta       =   DB::select('call sp_Chat_setRegistrarMensaje (?, ?, ?)',
                                                                [
                                                                    $from,
                                                                    $to,
                                                                    $text
                                                                ]);

        broadcast(new NuevoMensaje($rpta[0]));
        return $rpta;
    }
}
