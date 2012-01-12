<?php
/*
 *	CodeFred(tm) : Rapid Development Framework
 *	Copyright 2008-2012, JuvaSoft Web Solutions
 *						 <www.juvasoft.com>
 *
 *	Licensed under the MIT License
 *	Redistributions of files must retain the above copyright notice.
 *
 *	@package	CodeFred
 *	@author		Alfredo Juárez
 *	@version	1.0
 *
 */

namespace App\Data;
use PPI\Core;

class Usuarios extends Application {

	protected $_meta = array(
		'conn' => 'main',
		'table' => 'usuarios',
		'primary' => 'id'
	);
}