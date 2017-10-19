<?php

/**
 * Mondido Payment Module for PrestaShop
 * @author    Mondido
 * @copyright 2017 Mondido
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @link https://www.mondido.com
 */
if (!defined('_PS_VERSION_')) {
  exit;
}

//include_once (CURRENT_MODULE_DIR . '/vendor/PayWithAmazon/Client.php');
//use AdminCotizacionController;
//use DbQuery;
//use ProductCore;

class cotizacion extends Module {

  protected $_html = '';
  protected $_postErrors = [];
  private $metales = array();
  private $mensajes = array();

  public function __construct() {
    $this->name = 'cotizacion';
    $this->author = 'Ignacio Farré';
    $this->version = '1.0.0';
    $this->tab = 'front_office_features';
    $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];
    $this->bootstrap = true;
    $this->need_instance = 1;
    parent::__construct();

    $this->displayName = $this->l('Control de cotización'); // Nombre del módulo
    $this->description = $this->l('Control de la cotización de los metales.'); //Descripción del módulo
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); //mensaje de alerta al desinstalar el módulo.

    if (!Configuration::get('cotizacion')) {
      $this->warning = $this->l('No name provided.');
    }
  }

  public function install() {
    // Creamos la tabla
    $result = $this->createTable();
    // insertamos los valores
    if ($result) {
      $this->llenarTabla();
    }
    if (!parent::install())
      return false;
    return true;
  }

  public function uninstall() {
    Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'cot_metales` ;');
    if (!parent::uninstall())
      return false;
    return true;
  }

  public function getContent() {
    require_once (realpath(dirname(__FILE__)) . '/Model/CotMetal.php');
    // recogemos los valores para el formulario
    $this->metales = CotMetal::getAll();

    if (Tools::isSubmit('submit_cot_metales_update_products')) {
      $this->actualizarPrecios();
    }

    // Modificamos valores de los metales
    if (Tools::isSubmit('submit_cot_metales')) {
      $this->checkAndSaveMetales();
    }
    // Mandamos a la vista los datos
    $this->smarty->assign(array(
        'current_url' => $this->context->link->getAdminLink('AdminModules') . '&configure=cotizacion',
        'metales' => $this->metales,
        'successes' => $this->mensajes
    ));
    return $this->display(__FILE__, 'views/templates/admin/cotizacion.tpl');
  }

  private function actualizarPrecios() {
    $productCore = new ProductCore();
    $products = $productCore->getProducts(1, 0, 0, 'id_product', 'ASC');
    $productos_actualizados = FALSE;
    foreach ($products as $p) {
      // Cogemos el producto
      $product = new ProductCore($p['id_product']);
      // cogewmos los atributos del producto
      $attributes = $product->getAttributeCombinations();
      foreach ($attributes as $a) {
        if ($a['group_name'] == 'metal') {
          // ahora buscamos el precio qeu tiene ese metal
          foreach ($this->metales as $metal) {
            if($metal['id_attribute'] == $a['id_attribute']) {
              // hacemos el calculo del precio
              $precio_final = $metal['cot_metal_precio'] * $a['weight'] + $a['wholesale_price'];
              // lo modificamos
              $product->updateAttribute((int)$a['id_product_attribute'], (float)$a['wholesale_price'], (float)round($precio_final, 2), (float)$a['weight'], (float)$a['unit_price_impact'], (float)$a['ecotax'], NULL, $a['reference'], $a['ean13'], $a['default_on']);
              $productos_actualizados = TRUE;                
            }
          }
        }
      }
    }
    if($productos_actualizados) {
      $this->mensajes[] = '<p>Se han actualizado los precios de los productos:</p>';      
    }
  }

  private function llenarTabla() {
    // hay que coger el id attribute del metal. ps_attribute_lang.name
    $query = new DbQuery();
    $query->select('al.name, al.id_attribute');
    $query->from('attribute_lang', 'al');
    $query->join('
			LEFT JOIN ' . _DB_PREFIX_ . 'attribute a ON a.id_attribute = al.id_attribute
			LEFT JOIN ' . _DB_PREFIX_ . 'attribute_group_lang gl ON gl.id_attribute_group = a.id_attribute_group
        ');
    $query->where('gl.name = \'metal\' AND al.id_lang = 1');
    $query->groupBy('al.id_attribute');
    $result = Db::getInstance()->executeS($query);
    if (is_array($result)) {
      foreach ($result as $r) {
        // Ahora llenamos la tabla  
        $query = "INSERT INTO `" . _DB_PREFIX_ . "cot_metales` (cot_metal_id, cot_metal_name, cot_metal_precio, cot_metal_last_update, id_attribute) 
              VALUES 
              (NULL, '" . $r['name'] . "', 0, " . time() . ", " . $r['id_attribute'] . ");";
        DB::getInstance()->execute($query);
      }
    }
  }

  private function createTable() {
    return Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cot_metales` (
				`cot_metal_id` int(10) unsigned NOT NULL auto_increment PRIMARY KEY,
				`cot_metal_name` varchar(255) NOT NULL,
				`cot_metal_precio` float(6,2) NOT NULL,
        `cot_metal_last_update` int(11) NOT NULL,
        `id_attribute` INTEGER UNSIGNED NOT NULL
		) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;
				');
  }

  private function checkAndSaveMetales() {
    foreach ($this->metales as $key => $metal) {
      $cot_metal_precio = Tools::getValue('cot_metal_id_' . $key);
      if ($cot_metal_precio != $metal['cot_metal_precio']) {
        // Se ha modificado el valor, hay que guardarlo en la base de datos
        $metal['cot_metal_precio'] = $cot_metal_precio;
        CotMetal::updateMetal($metal);

        // modificamos el valor para que el formulario lo pinte
        $this->metales[$key]['cot_metal_precio'] = number_format((float) $cot_metal_precio, 2, '.', '');
        $this->mensajes[] = 'Se han guardado los precios de los metales.';      
      }
    }
  }
}
