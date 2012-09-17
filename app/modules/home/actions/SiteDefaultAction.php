<?php defined('ACCESS') or die("No direct script access allowed");

class SiteDefaultAction extends DAction
{	
	public function run()
	{		
		$c = $this->getController();
		$c->beginWidget(DBase::CLIPWIDGET, array('id'=>'Kasthuri'));
			echo date("Y-m-d H:i:s",time()) ;
			//print_r(DBase::getApp()->getController()->actions());
			/*
			$h = DBase::getApp()->htmlRow->addNewRow(
				array('columns' =>
					array(
						array(
						'name' => 'Kasthuri'
						),
						array(
						'name'=>'Prabha'
						)
					)
			 	),
				DTag::AFTER_HEADER); */					
					//print_r($h);					
		/*prepare->dispatch->display*/
		echo ($c->getModule()->modName);
		$c->endWidget(); 
		$c->render(
			$this->getId()
		);		
		$this->getController()->prepare()->dispatch();
	}
}