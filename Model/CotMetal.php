<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Metal
 *
 * @author ignacio
 */
class CotMetal extends ObjectModel {

  public $cot_metal_id;
  public $cot_metal_name;
  public $cot_metal_precio;
  public $cot_metal_last_update;
  public static $definition = array(
      'table' => 'cot_metales',
      'primary' => 'cot_metal_id',
      'multilang' => false,
      'fields' => array(
          'cot_metal_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
          'cot_metal_precio' => array('type' => self::TYPE_FLOAT),
          'cot_metal_last_update' => array('type' => self::TYPE_DATE, 'validate' => 'isInt'),
      )
  );

  function __construct($cot_metal_id, $cot_metal_name, $cot_metal_precio, $cot_metal_last_update) {
    $this->cot_metal_id = $cot_metal_id;
    $this->cot_metal_name = $cot_metal_name;
    $this->cot_metal_precio = $cot_metal_precio;
    $this->cot_metal_last_update = $cot_metal_last_update;
    parent::__construct();
  }

  public static function getMetalById($cot_metal_id) {
    return Db::getInstance()->getRow(
                    'SELECT * FROM `' . _DB_PREFIX_ . 'cot_metales`
                     WHERE `cot_metal_id` = ' . (int) $cot_metal_id
    );
  }
//  public static function insertNewMetal($cot_metal_name, $cot_metal_precio) {
//    $q = 'INSERT INTO ' . _DB_PREFIX_ . 'cot_metales VALUES (NULL, \'' . $cot_metal_name . '\', ' . $cot_metal_precio . ', ' . time() . ');';
//    DB::getInstance()->execute($q);
//  }

  public static function updateMetal($metal) {
    $q = 'UPDATE ' . _DB_PREFIX_ . 'cot_metales SET '
            . 'cot_metal_precio = ' . $metal['cot_metal_precio'] . ', '
            . 'cot_metal_last_update = ' . time()
            . ' WHERE cot_metal_id = ' . $metal['cot_metal_id'];
    DB::getInstance()->execute($q);
  }
  
//  public static function deleteMetal($cot_metal_id) {
////  DELETE FROM somelog WHERE user = 'jcole'
//    $q = 'DELETE FROM ' . _DB_PREFIX_ . 'cot_metales WHERE cot_metal_id = ' . $cot_metal_id . ';';
//    DB::getInstance()->execute($q);
//  }

  /**
   * Return the complete email collection from DB
   * @return array|false
   * @throws PrestaShopDatabaseException
   */
  public static function getAll() {
    $metales= array();
    $sql = 'SELECT * FROM `' . _DB_PREFIX_ . CotMetal::$definition['table'] . '`';
    $result = Db::getInstance()->executeS($sql);
    foreach ($result as $value) {
      $metales[$value['cot_metal_id']] = array(
          'cot_metal_id' => $value['cot_metal_id'],
          'cot_metal_name' => $value['cot_metal_name'],
          'cot_metal_precio' => $value['cot_metal_precio'],
          'cot_metal_last_update' => $value['cot_metal_last_update'],
          'id_attribute' => $value['id_attribute']
      );
    }
    return $metales;
  }

}
