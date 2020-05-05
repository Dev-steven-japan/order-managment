<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function getListarUsuarios(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $cNombre    =   $request->cNombre;
        $cUsuario   =   $request->cUsuario;
        $cCorreo    =   $request->cCorreo;
        $cEstado    =   $request->cEstado;

        $cNombre    =   ($cNombre   ==  NULL) ? ($cNombre   =   '') :   $cNombre;
        $cUsuario   =   ($cUsuario   ==  NULL) ? ($cUsuario   =   '') :   $cUsuario;
        $cCorreo    =   ($cCorreo   ==  NULL) ? ($cCorreo   =   '') :   $cCorreo;
        $cEstado    =   ($cEstado   ==  NULL) ? ($cEstado   =   '') :   $cEstado;

        $rpta       =   DB::select('call sp_Usuario_getListarUsuarios (?, ?, ?, ?)',
                                                                [
                                                                    $cNombre,
                                                                    $cUsuario,
                                                                    $cCorreo,
                                                                    $cEstado
                                                                ]);
        return $rpta;
    }

    public function setRegistrarUsuario(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $cPrimerNombre  =   $request->cPrimerNombre;
        $cSegundoNombre =   $request->cSegundoNombre;
        $cApellido      =   $request->cApellido;
        $cUsuario       =   $request->cUsuario;
        $cCorreo        =   $request->cCorreo;
        $cContrasena    =   Hash::make($request->cContrasena);
        $oFotografia    =   $request->oFotografia;

        $cPrimerNombre  =   ($cPrimerNombre   ==  NULL) ? ($cPrimerNombre   =   '') :   $cPrimerNombre;
        $cSegundoNombre =   ($cSegundoNombre   ==  NULL) ? ($cSegundoNombre   =   '') :   $cSegundoNombre;
        $cApellido      =   ($cApellido   ==  NULL) ? ($cApellido   =   '') :   $cApellido;
        $cUsuario       =   ($cUsuario   ==  NULL) ? ($cUsuario   =   '') :   $cUsuario;
        $cCorreo        =   ($cCorreo   ==  NULL) ? ($cCorreo   =   '') :   $cCorreo;
        $cContrasena    =   ($cContrasena   ==  NULL) ? ($cContrasena   =   '') :   $cContrasena;
        $oFotografia    =   ($oFotografia   ==  NULL) ? ($oFotografia   =   NULL) :   $oFotografia;

        DB::select('call sp_Usuario_setRegistrarUsuario (?, ?, ?, ?, ?, ?, ?)',
                                                                [
                                                                    $cPrimerNombre,
                                                                    $cSegundoNombre,
                                                                    $cApellido,
                                                                    $cUsuario,
                                                                    $cCorreo,
                                                                    $cContrasena,
                                                                    $oFotografia
                                                                ]);
    }
}
