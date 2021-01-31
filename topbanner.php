<?php
/**
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
*
*  @author    Mantas Antanaitis <antanaitis.web@gmail.com>
*  @copyright 2021 Webscript
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Topbanner extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'topbanner';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mantas Antanaitis';
        $this->need_instance = 1;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Top banner');
        $this->description = $this->l('Editable top banner to place at a top of the page.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this app?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('TOPBANNER_LIVE_MODE', false);
        Configuration::updateValue('TOPBANNER_TEXT', '');
        Configuration::updateValue('TOPBANNER_BG_COLOR', "#ff6347");
        Configuration::updateValue('TOPBANNER_TEXT_COLOR', "#ffffff");
        Configuration::updateValue('TOPBANNER_HEIGHT', 50);
        Configuration::updateValue('TOPBANNER_FONT_SIZE', 16);
        Configuration::updateValue('TOPBANNER_FONT_WEIGHT', 400);
        Configuration::updateValue('TOPBANNER_TIMER', false);
        Configuration::updateValue('TOPBANNER_TIMER_TEXT', 'Only %timer% left to enjoy it.');
        Configuration::updateValue('TOPBANNER_TIMER_DATE', '');

        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('displayBanner')
            && $this->registerHook('actionFrontControllerSetMedia');
    }

    public function uninstall()
    {
        Configuration::deleteByName('TOPBANNER_LIVE_MODE');
        Configuration::deleteByName('TOPBANNER_TEXT');
        Configuration::deleteByName('TOPBANNER_BG_COLOR');
        Configuration::deleteByName('TOPBANNER_TEXT_COLOR');
        Configuration::deleteByName('TOPBANNER_HEIGHT');
        Configuration::deleteByName('TOPBANNER_FONT_SIZE');
        Configuration::deleteByName('TOPBANNER_FONT_WEIGHT');
        Configuration::deleteByName('TOPBANNER_TIMER');
        Configuration::deleteByName('TOPBANNER_TIMER_DATE');
        Configuration::deleteByName('TOPBANNER_TIMER_TEXT');

        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall();
    }

    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitTopbannerModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('text', Configuration::get('TOPBANNER_TEXT'));
        $this->context->smarty->assign('text_color', Configuration::get('TOPBANNER_TEXT_COLOR'));
        $this->context->smarty->assign('bg_color', Configuration::get('TOPBANNER_BG_COLOR'));
        $this->context->smarty->assign('height', Configuration::get('TOPBANNER_HEIGHT'));
        $this->context->smarty->assign('font_size', Configuration::get('TOPBANNER_FONT_SIZE'));
        $this->context->smarty->assign('font_wight', Configuration::get('TOPBANNER_FONT_WEIGHT'));
        $this->context->smarty->assign('timer', Configuration::get('TOPBANNER_TIMER'));
        $this->context->smarty->assign('timer_date', Configuration::get('TOPBANNER_TIMER_DATE'));
        $this->context->smarty->assign('timer_text', Configuration::get('TOPBANNER_TIMER_TEXT'));

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTopbannerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
                                                            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enabled'),
                        'name' => 'TOPBANNER_LIVE_MODE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Height'),
                        'col' => 6,
                        'name' => 'TOPBANNER_HEIGHT',
                        'suffix' => 'px'
                    ),
                    array(
                        'type' => 'text',
                        'col' => '6',
                        'name' => 'TOPBANNER_TEXT',
                        'label' => $this->l('Text'),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Text color'),
                        'name' => 'TOPBANNER_TEXT_COLOR'
                    ),
                    array(
                        'type' => 'text',
                        'col' => '6',
                        'name' => 'TOPBANNER_FONT_SIZE',
                        'suffix' => 'px',
                        'label' => $this->l('Font size'),
                    ),
                    array(
                        'type' => 'text',
                        'col' => '6',
                        'name' => 'TOPBANNER_FONT_WEIGHT',
                        'hint' => $this->l('200, 300, 400, 500, 600, ...'),
                        'label' => $this->l('Font weight'),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Background color'),
                        'name' => 'TOPBANNER_BG_COLOR'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Timer enabled'),
                        'name' => 'TOPBANNER_TIMER',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'date',
                        'label' => $this->l('Timer end date:'),
                        'name' => 'TOPBANNER_TIMER_DATE'
                    ),
                    array(
                        'type' => 'text',
                        'col' => 6,
                        'name' => 'TOPBANNER_TIMER_TEXT',
                        'label' => $this->l('Timer text'),
                        'desc' => $this->l('You must leave %timer% in place. 
                            It shows where to put the timer. E.g. "Only %timer% left to enjoy it."')
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        $formValues = array(
            'TOPBANNER_LIVE_MODE' => Configuration::get('TOPBANNER_LIVE_MODE'),
            'TOPBANNER_TEXT' => Configuration::get('TOPBANNER_TEXT'),
            'TOPBANNER_TEXT_COLOR' => Configuration::get('TOPBANNER_TEXT_COLOR'),
            'TOPBANNER_BG_COLOR' => Configuration::get('TOPBANNER_BG_COLOR'),
            'TOPBANNER_HEIGHT' => Configuration::get('TOPBANNER_HEIGHT'),
            'TOPBANNER_FONT_SIZE' => Configuration::get('TOPBANNER_FONT_SIZE'),
            'TOPBANNER_FONT_WEIGHT' => Configuration::get('TOPBANNER_FONT_WEIGHT'),
            'TOPBANNER_TIMER' => Configuration::get('TOPBANNER_TIMER'),
            'TOPBANNER_TIMER_TEXT' => Configuration::get('TOPBANNER_TIMER_TEXT'),
            'TOPBANNER_TIMER_DATE' => Configuration::get('TOPBANNER_TIMER_DATE')
        );

        return $formValues;
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        if (!Validate::isInt(Tools::getValue('TOPBANNER_HEIGHT'))) {
            $this->context->controller->errors[] = $this->l('Height value must be a number');
            return;
        }

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/views/js/front.js');
        $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/views/css/front.css');
    }

    public function hookBackOfficeHeader()
    {
        $this->context->controller->addJS(_MODULE_DIR_ . $this->name . 'views/js/back.js');
        $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . 'views/css/back.css');
    }

    public function hookDisplayBanner()
    {
        $this->context->smarty->assign(
            [
                'enabled' => Configuration::get('TOPBANNER_LIVE_MODE'),
                'text' => Configuration::get('TOPBANNER_TEXT'),
                'text_color' => Configuration::get('TOPBANNER_TEXT_COLOR'),
                'bg_color' => Configuration::get('TOPBANNER_BG_COLOR'),
                'height' => Configuration::get('TOPBANNER_HEIGHT'),
                'font_weight' => Configuration::get("TOPBANNER_FONT_WEIGHT"),
                'font_size' => Configuration::get("TOPBANNER_FONT_SIZE"),
                'timer' => Configuration::get('TOPBANNER_TIMER'),
                'timer_text' => Configuration::get('TOPBANNER_TIMER_TEXT'),
                'timer_date' => Configuration::get('TOPBANNER_TIMER_DATE')
            ]
        );

        return $this->display(__FILE__, 'banner.tpl');
    }
}
