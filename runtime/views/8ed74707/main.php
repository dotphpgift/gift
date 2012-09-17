<?php /* source file: E:\xampp\htdocs\gift\app\views/main.php */ ?>
<?php defined('ACCESS') or die("No direct script access allowed");
$this->designer('application.lib.pages.DLayout', 
	array(
		'docType' =>  array(
			'class'=>'DDoctype',
			'type' => 'xhtml11'  
		),
		'content'=>$content,
		'controller' => $this
	)
);

/*

$dataProvider=new DDataProvider('config');

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
                // display the 'title' attribute
        array(
	   	'name' => 'value', 'header'=>'Kas',
		'value'=> '$data->value',
		'sortable' => true,
		'type'=>'email',
		'visible'=>true
		),  // display the 'name' attribute of the 'category' relation
		
		'key:url:dhanapal',  
    ),
));


/*
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'title',          // display the 'title' attribute
        'category.name',  // display the 'name' attribute of the 'category' relation
        'content:html',   // display the 'content' attribute as purified HTML
        array(            // display 'create_time' using an expression
            'name'=>'create_time',
            'value'=>'date("M j, Y", $data->create_time)',
        ),
        array(            // display 'author.username' using an expression
            'name'=>'authorName',
            'value'=>'$data->author->username',
        ),
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));*/