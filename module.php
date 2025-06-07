<?php
/*
 * Copyright (C) 2017-2020 CRLibre <https://crlibre.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/** @file module.php
 * A brief file description.
 * A more elaborated file description.
 */

/** \addtogroup Core
 *  @{
 */

/**
 * \defgroup Module
 * @{
 */

/**
 * Boot up procedure
 */
function apiSicocir_bootMeUp()
{
    // Just booting up
}

/**
 * Init function
 */
function apiSicocir_init()
{
    $paths = array(


        // Scripts usuarios
        array(
            'r' => 'login_usuario',
            'action' => 'loginUsuario',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsUsuarios.php',
        ),
        array(
            'r' => 'cambia_pin',
            'action' => 'cambiaPin',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsUsuarios.php',
        ),
        array(
            'r' => 'lista_usuarios',
            'action' => 'listaUsuarios',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsUsuarios.php',
        ),
        array(
            'r' => 'actualiza_usuario',
            'action' => 'actualizaUsuario',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsUsuarios.php',
        ),
        array(
            'r' => 'lista_distribuidores',
            'action' => 'listaDistribuidores',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsUsuarios.php',
        ),
        array(
            'r' => 'actualiza_distribuidor',
            'action' => 'actualizaDistribuidor',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsUsuarios.php',
        ),
         array(
            'r' => 'consulta_distribuidor',
            'action' => 'consultaDistribuidor',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsUsuarios.php',
        ),

        // Scripts master
        array(
            'r' => 'actualiza_item',
            'action' => 'actualizaItem',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsMaster.php',
        ),
        array(
            'r' => 'lista_items',
            'action' => 'listaItems',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsMaster.php',
        ),
        array(
            'r' => 'actualiza_tipo_neg',
            'action' => 'actualizaTipoNeg',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsMaster.php',
        ),
        array(
            'r' => 'lista_tipo_neg',
            'action' => 'listaTipoNeg',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsMaster.php',
        ),
        // Scripts clientes
         array(
            'r' => 'actualiza_cliente',
            'action' => 'actualizaCliente',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsClientes.php',
         ),
          array(
            'r' => 'consulta_cliente',
            'action' => 'consultaCliente',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsClientes.php',
        ),
          array(
            'r' => 'llena_tabla_clientes',
            'action' => 'llenaTablaClientes',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsClientes.php',
          ),

          // Scripts ventas
           array(
            'r' => 'consulta_fechas_picking',
            'action' => 'consultaFechasPicking',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsVentas.php',
           ),
            array(
            'r' => 'actualiza_picking_diario',
            'action' => 'actualizaPickingDiario',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsVentas.php',
          ),
            array(
            'r' => 'lista_entrega_diaria_pdvs',
            'action' => 'listaEntregaDiariaPdvs',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsVentas.php',
          ),
            array(
            'r' => 'actualiza_entrega_diaria',
            'action' => 'actualizaEntregaDiaria',
            'access' => 'users_openAccess',
            'access_params' => 'accessName',
            'file' => 'wsVentas.php',
          ),


    );



    return $paths;
}

/**************************************************/
//In the access you can use users_openAccess if you want anyone can use the function
// or users_loggedIn if the user must be logged in
/**************************************************/

/**
 * Get the perms for this module
 */
function apiSicocir_access()
{

    $perms = array(
        array(
            # A human readable name
            'name' => 'Do something with this module',
            # Something to remember what it is for
            'description' => 'What can be achieved with this permission',
            # Internal machine name, no spaces, no funny symbols, same rules as a variable
            # Use yourmodule_ prefix
            'code' => 'mymodule_access_one',
            # Default value in case it is not set
            'def' => false, //Or true, you decide
        ),
    );
}

/**@}*/
/** @}*/
