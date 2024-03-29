<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT

   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class ht_nprogress
  {
    public string $code;
    public $group;
    public $title;
    public $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;
    public $pages;

    public function __construct()
    {

      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_header_tags_nprogress_title');
      $this->description = CLICSHOPPING::getDef('module_header_tags_nprogress_description');

      if (\defined('MODULE_HEADER_NPROGRESS_STATUS')) {
        $this->sort_order = MODULE_HEADER_NPROGRESS_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_NPROGRESS_STATUS == 'True');
        $this->pages = MODULE_HEADER_NPROGRESS_DISPLAY_PAGES;
      }
    }

    public function execute()
    {
      $CLICSHOPPING_Template = Registry::get('Template');

      $footer = '<!-- Nprogress Start -->' . "\n";
      $footer .= '<script src="' . CLICSHOPPING::link('sources/javascript/nprogress/nprogress.min.js', null, false) . '"></script>' . "\n";
      $footer .= '<script>';
      $footer .= 'NProgress.start(); ';
      $footer .= 'setTimeout(function() { NProgress.done(); $(\'.fade\').removeClass(\'out\'); }, 1000);';
      $footer .= '</script>' . "\n";
      $footer .= '<!-- Nprogress stop -->' . "\n";

      $CLICSHOPPING_Template->addBlock($footer, 'footer_scripts');

      $nprogress = '<link rel="stylesheet" href="' . CLICSHOPPING::link('sources/javascript/nprogress/nprogress.min.css', null, false) . '">';

      $CLICSHOPPING_Template->addBlock($nprogress, $this->group);
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function check()
    {
      return \defined('MODULE_HEADER_NPROGRESS_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Souhaitez-vous activer ce module ?',
          'configuration_key' => 'MODULE_HEADER_NPROGRESS_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Souhaitez vous activer ce module à votre boutique ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_HEADER_NPROGRESS_SORT_ORDER',
          'configuration_value' => '10',
          'configuration_description' => 'Sort order. Lowest is displayed in first',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer ou la boxe doit s\'afficher',
          'configuration_key' => 'MODULE_HEADER_NPROGRESS_DISPLAY_PAGES',
          'configuration_value' => 'all',
          'configuration_description' => 'Sélectionnez les pages o&ugrave; la boxe doit être présente',
          'configuration_group_id' => '6',
          'sort_order' => '7',
          'set_function' => 'clic_cfg_set_select_pages_list',
          'date_added' => 'now()'
        ]
      );

    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return array('MODULE_HEADER_NPROGRESS_STATUS',
        'MODULE_HEADER_NPROGRESS_SORT_ORDER',
        'MODULE_HEADER_NPROGRESS_DISPLAY_PAGES'
      );
    }
  }
