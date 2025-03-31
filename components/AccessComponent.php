<?php

namespace app\components;

use Yii;
use app\helpers\App;
use yii\base\Component;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use app\helpers\Url;

class AccessComponent extends Component
{
	const NAVIGATIONS = [
	    '1' => [
	        'label' => 'Dashboard', 
	        'link' => '/dashboard', 
	        'icon' => '<i class="fa fa-cog"></i>',
	    ],
	    '2' => [
	        'label' => 'Budget', 
	        'link' => '/budget', 
	        'icon' => '<i class="fa fa-cog"></i>',
	    ],
	    '3' => [
	        'label' => 'Events', 
	        'link' => '/event', 
	        'icon' => '<i class="fa fa-cog"></i>',
	        'sub' => [
				'3.1' => [
	                'label' => 'Un-planned Attendees',
	                'link' => '/un-planned-attendees-event',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        	'3.2' => [
	                'label' => 'List',
	                'link' => '/event',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '3.3' => [
	                'label' => 'Event Category',
	                'link' => '/event-category',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        ],
	    ],
	    '4' => [
	        'label' => 'Reports', 
	        'link' => '/report', 
	        'icon' => '<i class="fa fa-cog"></i>',
	        'sub' => [
	        	'4.1' => [
	                'label' => 'AICS',
	                'link' => '/report/aics',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        	'4.2' => [
	                'label' => 'Emergency Welfare',
	                'link' => '/report/emergency-welfare-program',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '4.3' => [
	                'label' => 'Certification',
	                'link' => '/report/certification',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '4.4' => [
	                'label' => 'Transaction Type',
	                'link' => '/report/transaction-type',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        ],
	    ],
	    '5' => [
	        'label' => 'Transactions', 
	        'link' => '/transaction', 
	        'icon' => '<i class="fa fa-cog"></i>',
	    ],
	    '6' => [
	        'label' => 'Social Pension', 
	        'link' => '/social-pension', 
	        'icon' => '<i class="fa fa-cog"></i>',
			'sub' => [
	        	'6.1' => [
	                'label' => 'Social Pension List',
	                'link' => '/social-pensioner',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        	'6.2' => [
	                'label' => 'Distribution Event',
	                'link' => '/social-pension-event',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '6.3' => [
	                'label' => 'Masterlist',
	                'link' => '/masterlist',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        ],
	    ],
	    '7' => [
	        'label' => 'Households', 
	        'link' => '/household', 
	        'icon' => '<i class="fa fa-cog"></i>',
	    ],
	    '8' => [
	        'label' => 'Members', 
	        'link' => '/member', 
	        'icon' => '<i class="fa fa-cog"></i>',
	    ],
	    '9' => [
	        'label' => 'Users',
	        'link' => '#',
	        'icon' => '<i class="fa fa-cog"></i>',
	        'sub' => [
	            '9.1' => [
	                'label' => 'List',
	                'link' => '/user',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '9.2' => [
	                'label' => 'Roles',
	                'link' => '/role',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        ],
	    ],
	    '10' => [
	        'label' => 'Files',
	        'link' => '#',
	        'icon' => '<i class="fa fa-cog"></i>',
	        'sub' => [
	            '10.1' => [
	                'label' => 'List',
	                'link' => '/file',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '10.2' => [
	                'label' => 'My Files',
	                'link' => '/my-files',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        ],
	    ],
	    '11' => [
	        'label' => 'Map',
	        'link' => '/map',
	        'icon' => '<i class="fa fa-cog"></i>',
	    ],
	    '12' => [
	        'label' => 'System',
	        'link' => '#',
	        'icon' => '<i class="fa fa-cog"></i>',
	        'sub' => [
	            '12.1' => [
	                'label' => 'Backups',
	                'link' => '/backup',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '12.2' => [
	                'label' => 'Sessions',
	                'link' => '/session',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '12.3' => [
	                'label' => 'Logs',
	                'link' => '/log',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '12.4' => [
	                'label' => 'Visit Logs',
	                'link' => '/visit-log',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '12.5' => [
	                'label' => 'Queues',
	                'link' => '/queue',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        ],
	    ],
	    '13' => [
	        'label' => 'Settings',
	        'link' => '#',
	        'icon' => '<i class="fa fa-cog"></i>',
	        'sub' => [
	        	'13.1' => [
	                'label' => 'Setting List',
	                'link' => '/setting',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '13.2' => [
	                'label' => 'General Setting',
	                'link' => '/setting/general',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '13.3' => [
	                'label' => 'Ip',
	                'link' => '/ip',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '13.4' => [
	                'label' => 'Themes',
	                'link' => '/theme',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ]
	        ]
	    ],
	    '14' => [
	        'label' => 'Locations',
	        'link' => '#',
	        'icon' => '<i class="fa fa-cog"></i>',
	        'sub' => [
	        	'14.1' => [
	                'label' => 'Country',
	                'link' => '/country',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '14.2' => [
	                'label' => 'Region',
	                'link' => '/region',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '14.3' => [
	                'label' => 'Province',
	                'link' => '/province',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '14.4' => [
	                'label' => 'Municipality',
	                'link' => '/municipality',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '14.5' => [
	                'label' => 'Barangay',
	                'link' => '/barangay',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ]
	        ]
	    ],
	    '15' => [
	        'label' => 'Notifications', 
	        'link' => '/notification', 
	        'icon' => '<i class="fa fa-cog"></i>',
	    ],
	    '16' => [
	        'label' => 'Visitors', 
	        'link' => '/visitor', 
	        'icon' => '<i class="fa fa-cog"></i>',
	    ],
	    '17' => [
	        'label' => 'Database', 
	        'link' => '#', 
	        'icon' => '<i class="fa fa-cog"></i>',
			'sub' => [
	        	'17.1' => [
	                'label' => 'Members',
	                'link' => '/database',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '17.2' => [
	                'label' => 'Un-Registered Senior',
	                'link' => '/database/unregistered-senior',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        	'17.3' => [
	                'label' => 'Report Per Sector',
	                'link' => '/database/report',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '17.4' => [
	                'label' => 'Report Per Barangay',
	                'link' => '/database/report-per-barangay',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '17.5' => [
	                'label' => 'Priority Sector',
	                'link' => '/database/priority-sector',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        ],
	    ],
	    '18' => [
	        'label' => 'Socio Economic Survey', 
	        'link' => '#', 
	        'icon' => '<i class="fa fa-cog"></i>',
			'sub' => [
	        	'18.1' => [
	                'label' => 'Survey',
	                'link' => '/specialsurvey',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        	'18.2' => [
	                'label' => 'Per Barangay Report',
	                'link' => '/specialsurvey/report-per-barangay',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '18.3' => [
	                'label' => 'Per Purok Report',
	                'link' => '/specialsurvey/report-per-purok',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	            '18.4' => [
	                'label' => 'Settings',
	                'link' => '/specialsurvey/settings',
	                'icon' => '<i class="fa fa-cog"></i>',
	            ],
	        ],
	    ],
	];
	
	public $searchModels;
	public $controllerActions;
	public $defaultNavigation;

	public function init()
	{
		parent::init();
		$this->setSearhModels();
		$this->setControllerActions();
		$this->setDefaultNavigation();
	}

	public function setControllerActions()
	{
		$controllers = FileHelper::findFiles(Yii::getAlias('@app/controllers'), [
			'recursive' => true
		]);

		$data = [];

		foreach ($controllers as $key => $controller) {

			if (($controllerID = Inflector::camel2id(substr(basename($controller), 0, -14))) == '') continue;

			$controllerName = substr(basename($controller), 0, -4);
			$controllerObject = Yii::createObject("\\app\\controllers\\{$controllerName}");
			$actions = get_class_methods($controllerObject);
			$_actions = [];

			foreach ($actions as $action) {
				if (!preg_match("/^action\w+$/", $action)) continue;

				if (($actionID = substr(Inflector::camel2id($action), 7)) == '') continue;

				$_actions[] = $actionID;
			}

			asort($_actions);
			$data[$controllerID] = $_actions;

		}
 		ksort($data);
		$this->controllerActions = $data;
	} 

	public function actions($controllerID="")
	{ 
		$controllerID = $controllerID ?: App::controllerID();

		return $this->controllerActions[$controllerID] ?? [''];
	}

 	public function my_actions($controllerID='')
 	{
 		if (App::isGuest()) { return ['']; }

		$controllerID = $controllerID ?: App::controllerID();

 		$module_access = App::identity('moduleAccess');

 		return $module_access[$controllerID] ?? [''];
 	}

 	public function userCanRoute($link='')
 	{
 		if (is_array($link)) {
            $url = $link[0];

            $explodedLink = explode('/', $url);
            if (count($explodedLink) == 1) {
                $controller = App::controllerID();
                $action = $explodedLink[0];
            }
            else {
                $controller = $explodedLink[0];
                $action = $explodedLink[1];
            }
            return $this->userCan($action, $controller);
        }
 	}
 
	public function userCan($action, $controllerID='', $user='')
 	{
 		if (App::isLogin()) {
			$controllerID = $controllerID ?: App::controllerID();

			$module_access = ($user)? $user->identity->moduleAccess: App::identity('moduleAccess');

			if (isset($module_access[$controllerID])) {
				return in_array($action, $module_access[$controllerID]) ? true: false;
			}
 		}

		return false;
 	}
 
 	public function getModuleFilter()
 	{
 		$controller_actions = $this->controllerActions;

 		$data = [];

 		$ignoreControllers = [
 			'general-setting',
 			'dashboard',
 			'site',
 			// 'api',
 			// 'model-file',
 			'event-member',
 			'household-member',
 			'value-label',
 			'session',
 			'theme',
 			'transaction-log',
 			'queue',
 			'visit-log'
 		];

 		foreach ($controller_actions as $controller => $actions) {
 			if ($this->userCan('index', $controller) 
 				&& !in_array($controller, $ignoreControllers)) {
 				$searchModelClass = Inflector::id2camel($controller) . 'Search';

            	$path = FileHelper::normalizePath(Yii::getAlias("@app/models/search/{$searchModelClass}.php"));

 				if (file_exists($path)) {
 					$data[$searchModelClass] = Inflector::camel2words(
 						Inflector::id2camel($controller)
 					);
 				}
 			}
 		}

 		return $data;
 	}

 	public function setDefaultNavigation()
 	{
 		$this->defaultNavigation = self::NAVIGATIONS;
 	} 

	public function menu($menus)
	{
	    foreach ($menus as $key => &$menu) {
	        if (isset($menu['group_menu']) && $menu['group_menu']) {
	            unset($menus[$key]);
	        }
	        else {
	            $menu['url'] = $menu['link'];
	            unset($menu['link']);
	            if (isset($menu['sub'])) {
	                $menu['items'] = $this->menu($menu['sub']);
	                unset($menu['sub']);
	            }
	        }
	    }
	    return $menus;
	}

	public function setSearhModels()
	{
		$searchModels = FileHelper::findFiles(Yii::getAlias('@app/models/search'), [
			'recursive' => true
		]);

		$ignore = [
			'DashboardSearch',
		];

		$data = [];
		foreach ($searchModels as $key => $searchModel) {
			$name = str_replace('.php', '', basename($searchModel));

			if (! in_array($name, $ignore)) {
				$data[$name] = Inflector::camel2words(str_replace('Search', '', $name));
			}
		}

		$this->searchModels = $data;
	} 
}