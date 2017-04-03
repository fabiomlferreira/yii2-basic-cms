<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\rbac\UserRoleRule;

class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); //com isto basta correr o comando php yii rbac/init e corre sempre independememente do que tinha antes


        //CREATE PERMISSIONS
        //Admin the app, can do everything
        $adminApp = $auth->createPermission('adminApp');
        $adminApp->description = 'Administrar a App';
        $auth->add($adminApp);
        
        //Manage, pode gerir coisas básicas da app
        $manageApp = $auth->createPermission('manageApp');
        $manageApp->description = 'Pode gerir a app';
        $auth->add($manageApp);
        
        //Sell, pode vender coisas no site
        $sellApp = $auth->createPermission('sellApp');
        $sellApp->description = 'Pode vender produtos na app';
        $auth->add($sellApp);
        
 
        //Basic - Basic user stuff user stuff, everyuser can do this
        $basicApp = $auth->createPermission('basicApp');
        $basicApp->description = 'Permissão mais básica de todas permite a um utilizador fazer apenas o básico, qualquer utilizador tem esta permissão';
        $auth->add($basicApp);


        //CREATE ROLES
        $rule = new UserRoleRule(); //Get the roles from DB
        $auth->add($rule);

        //Most basic role "user"
        $user = $auth->createRole('user');
        $user->ruleName = $rule->name;
        $auth->add($user);
         // ... add permissions as children of $contributor ..
        $auth->addChild($user, $basicApp);
        
     
        
        //Seller
        $seller = $auth->createRole('seller');
        $seller->ruleName = $rule->name;
        $auth->add($seller);
        $auth->addChild($seller, $user);
        // ... add permissions as children of $contributor ..
        $auth->addChild($seller, $sellApp);
        
        //Manager
        $manager = $auth->createRole('manager');
        $manager->ruleName = $rule->name;
        $auth->add($manager);
        $auth->addChild($manager, $seller);
        // ... add permissions as children of $admin ..
        $auth->addChild($manager, $manageApp);

        //Admin
        $admin = $auth->createRole('admin');
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        $auth->addChild($admin, $manager);
        // ... add permissions as children of $admin ..
        $auth->addChild($admin, $adminApp);
    }

}
