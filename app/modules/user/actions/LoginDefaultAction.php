<?php defined('ACCESS') or die("No direct script access allowed");

class LoginDefaultAction extends DAction
{	
	public function run()
	{		
		$view = $this->controller->prepare($this->xtype, $this);
		$view->render();
	}
	
	public function actionPost($form, $returnUrl)
	{
		if(!DBase::isPost())
			return false;
		
		if($this->validate==true)
		{
			if($form->submitted('login') && $form->validate())
			{
				//echo($form->attributes['style']);
			}
		}			
		return false;
	}
}

/*
public function actionRegister()
{
    $form = new CForm('application.views.user.registerForm');
    $form['user']->model = new User;
    $form['profile']->model = new Profile;
    if($form->submitted('register') && $form->validate())
    {
        $user = $form['user']->model;
        $profile = $form['profile']->model;
        if($user->save(false))
        {
            $profile->userID = $user->id;
            $profile->save(false);
            $this->redirect(array('site/index'));
        }
    }
 
    $this->render('register', array('form'=>$form));
}
    text
    hidden
    password
    textarea
    file
    radio
    checkbox
    listbox
    dropdownlist
    checkboxlist
    radiolist
 
CForm example
$f=array(
    'elements'=>array(
        'user'=>array(
            'type'=>'form',
            'title'=>'Login information',
            'elements'=>array(
                'username'=>array(
                    'type'=>'text',
                ),
                'password'=>array(
                    'type'=>'password',
                ),
                'email'=>array(
                    'type'=>'text',
                ),
				'gender'=>array(
    				'type'=>'dropdownlist',
    				'items'=>array(0 => 'Male', 1 => 'Female'),
    				'prompt'=>'Please select:',
				),
            ),
        ),
 
        'profile'=>array(
            'type'=>'form',
            'title'=>'Profile information',
            'elements'=>array(
                'firstName'=>array(
                    'type'=>'text',
                ),
                'lastName'=>array(
                    'type'=>'text',
                ),
            ),
        ),
    ),
 
    'buttons'=>array(
        'register'=>array(
            'type'=>'submit',
            'label'=>'Register',
        ),
    ),
);
	$form = new CForm($f, $m);
	$this->controller->render('default',array('form'=>$form));
*/