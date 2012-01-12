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

class Application extends \PPI\DataSource\ActiveQuery {

	protected $_meta = array(
		'conn' => 'main',
		'table' => null,
		'primary' => 'id'
	);

	/**
	 *	Acumula una sentencia SQL
	 *
	 *	@var $ssql
	 */

	private $ssql;

	function fetchAll() {
		return parent::fetchAll(new \PPI\DataSource\Criteria());
	}

	public function getDataForSelect( $field, $where = NULL ) {

		try {

			$datos	= array();
			$where	= isset($where) ? $where : null;
			return  $this->select("{$this->_meta['primary']}, {$field}")
							->from($this->_meta['table'])
							->where($where)
							->getForSelect($field);

		} catch( PDOException $e ) {
			throw new cfException($e->getMessage());
		} catch(cfException $e) {
			throw new cfException($e->getMessage());
		}
	}

	/*
	 * FLUENT INTERFACE
	 *
	 * SELECT'S
	 *
	 */

	 public function select( $options = '' ) {
		$options	= !empty($options) ? $options : '*';
		$this->ssql = "SELECT {$options}";
		return $this;
	}

	public function from( $table = '' ) {
		$table		 = $table ? $table : "{$this->_meta['table']}";
		$this->ssql .= " FROM {$table}";
		return $this;
	}

	public function inner( $table, $on ) {

		$this->ssql .= " INNER JOIN {$table} ON {$on}";
		return $this;
	}

	public function left( $table, $on ) {

		$this->ssql .= " LEFT JOIN {$table} ON {$on} ";
		return $this;
	}

	public function right( $table, $on ) {

		$this->ssql .= " RIGHT JOIN {$table} ON {$on} ";
		return $this;
	}

	public function where( $options = null ) {

		/* Turn the filters into a string (if not already) */
		if (is_array($options) && !empty($options))  {
			$where = ' WHERE ' . implode(' AND ', $options);
		} else if ( is_string($options) && $options != '' ) {
			$where = ' WHERE ' . $options;
		} else {
			$where = '';
		}

		$this->ssql .= $where;
		return $this;
	}

	public function andWhere( $options = null ) {

		$and = isset($options) ? " AND {$options} " : '';
		$this->ssql .= $and;
		return $this;
	}

	public function orWhere( $options = null ) {

		$or = isset($options) ? " OR {$options} " : '';
		$this->ssql .= $or;
		return $this;
	}

	public function order($options) {
		$this->ssql .= $options ? " ORDER BY {$options}" : '';
		return $this;
	}

	public function group($options) {
		$this->ssql .= $options ? " GROUP BY {$options}" : '';
		return $this;
	}

	public function limit($options) {
		$this->ssql .= $options ? " LIMIT {$options}" : '';
		return $this;
	}

	public function call($sp, $options) {
		$this->ssql = "CALL {$sp} ($options)";
		return $this;
	}

	public function run( $fetchMode = 'both' ) {

		//echo $this->ssql;

		try {

			return $this->fetchAll($this->ssql);
		} catch( PDOException $e ) {
			throw new cfException($e->getMessage());
		} catch(cfException $e) {
			throw new cfException($e->getMessage());
		}
	}

	public function getSql() {
		return $this->ssql;
	}

	/**
	 * Devuelve un arreglo utilizado para los selects en un formulario.
	 *
     * @function getForSelect( $field )
	 * @param $field el Campo
	 * @return array $datos
	 */
	public function getForSelect( $field ) {

		try {

			$datos = array( );
			$aRecord = $this->fetchAll($this->ssql);

			foreach ( $aRecord as $row ) {
				$datos[$row[$field]] = $row['id'];
			}

			return $datos;

		} catch( PDOException $e ) {
			throw new cfException($e->getMessage());
		} catch(cfException $e) {
			throw new cfException($e->getMessage());
		}
	}

	/*
	 *
	 * END FLUENT INTERFACE
	 *
	 */

	/**
	 *
	 * Obtiene los registros de la tabla en caso de que coincida la ID con la clave primaria.
	 *
	 * @function fetchDataFromID( );
	 */

	public function fetchDataFromID( $id, $order = '', $limit = '', $group = '' ) {

		try {
			$ssql = $this->select()
						->from()
						->where($this->_meta['primary'] . "=".$id)
						->order($order)
						->group($group)
						->limit($limit)
						->getSql();

			return $this->fetch($ssql);
		} catch( PDOException $e ) {
			throw new cfException($e->getMessage());
		} catch(cfException $e) {
			throw new cfException($e->getMessage());
		}
	}

	/**
	 *
	 * Obtiene los registros de la tabla.
	 * recibe como parametros algunas clausulas.
	 *
	 * @function fetchData( );
	 */

	public function fetchData( $filter = '', $order = '', $limit = '', $group = '' ) {

		try {

			$ssql = $this->select()
						->from()
						->where($filter)
						->order($order)
						->limit($limit)
						->group($group)
						->getSql();

			return $this->fetchAll($ssql);

		} catch( PDOException $e ) {
			throw new cfException($e->getMessage());
		} catch(cfException $e) {
			throw new cfException($e->getMessage());
		}
	}
}