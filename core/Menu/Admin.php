<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik
 * @package Piwik_Menu
 */
namespace Piwik\Menu;

use Piwik\Menu\MenuAbstract;
use Piwik\Piwik;

/**
 * @package Piwik_Menu
 */
class Admin extends MenuAbstract
{
    static private $instance = null;

    /**
     * @return \Piwik\Menu\Admin
     */
    static public function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Triggers the Menu.Admin.addItems hook and returns the menu.
     *
     * @return Array
     */
    public function get()
    {
        if (!$this->menu) {

            /**
             * This event is triggered to collect all available admin menu items. Subscribe to this event if you want
             * to add one or more items to the Piwik admin menu. It's fairly easy. Just define the name of your menu
             * item as well as a controller and an action that should be executed once a user selects your menu item.
             * It is also possible to display the item only for users having a specific role.
             *
             * Example:
             * ```
             * public function addMenuItems()
             * {
             *     Piwik_AddAdminSubMenu(
             *         'MenuName',
             *         'SubmenuName',
             *         array('module' => 'MyPlugin', 'action' => 'index'),
             *         Piwik::isUserIsSuperUser(),
             *         $order = 6
             *     );
             * }
             * ```
             */
            Piwik_PostEvent('Menu.Admin.addItems');
        }
        return parent::get();
    }

    /**
     * Returns the current AdminMenu name
     *
     * @return boolean
     */
    function getCurrentAdminMenuName()
    {
        $menu = Piwik_GetAdminMenu();
        $currentModule = Piwik::getModule();
        $currentAction = Piwik::getAction();
        foreach ($menu as $submenu) {
            foreach ($submenu as $subMenuName => $parameters) {
                if (strpos($subMenuName, '_') !== 0 &&
                    $parameters['_url']['module'] == $currentModule
                    && $parameters['_url']['action'] == $currentAction
                ) {
                    return $subMenuName;
                }
            }
        }
        return false;
    }
}

