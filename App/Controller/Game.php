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
 *	@class		Controller_Dashboard
 *	@version	1.0
 *
 *	File: Dashboard.php
 *
 */

namespace App\Controller;
use PPI\Core\CoreException;

class Game extends Application {

	function index() {

		$this->render('game/index');
	}
}