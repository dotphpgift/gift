<?php
$str = 'Dhanam';
$row=array(
		'header' => array(
			'columns' => array(
				array(
					'name' => 'Dhanam',
					'offset' => '0',
					'type' => 'text',
					'value' => '$clipMenu',
				),
				array(
					'name' => 'Sakthi'
				)
			),
		),
		'footer' =>array(
			'columns' => array(
				array(
					'name' => 'Kasthuri',
					'offset' => '1',
					'type' => 'html'
				)	
			)			
		)		
	);
$cssColumnName = array(
		1 => 'one',
		2 => 'two',
		3 => 'three',
		4 => 'four',
		5 => 'five',
		6 => 'six',
		7 => 'seven',
		8 => 'eight',
		9 => 'nine',
		10 => 'ten',
		11 => 'eleven',
		12 => 'twelve',
		13 => 'thirteen',
		14 => 'fourteen',
		15 => 'fifteen',
		16 => 'sixteen'
	);
	
function r()
{
	global $cssColumnName;
	$colOffset     = array(0,5);
	$colSum        = array_sum($colOffset); 
	$numColumns    = count($colOffset);
	$defaultOffset = 12 / $numColumns; 
	$remainingCol  = 12;
	
	$adj=array();
	
	if($numColumns > 1 && 12 > ($colSum*$numColumns))
	{
		for($i=0; $i<$numColumns; ++$i)
		{ 
			if($colOffset[$i] > 0)
			{
				$colOffset[$i] += $defaultOffset;
				$remainingCol  -= $colOffset[$i];
			}
			else
			{
				$adj[$i] = $colOffset[$i];
				unset($colOffset[$i]);
			}		
		}
		$diff = ($remainingCol - ($remainingCol % count($adj))) / count($adj);
		
		foreach(array_keys($adj) as $k) 
		{
			$adj[$k] = $diff;
		}
		$new_diff = 12 - array_sum($colOffset + $adj);
		
		if($new_diff > 0)
		{
			$share = ($new_diff>count($colOffset)) ? $new_diff/count($colOffset) : $new_diff;
		}
	}
	else
	{
		for($i=0; $i<$numColumns; ++$i)
		{
			$colOffset[$i] = $defaultOffset;
		}
	} $dd = $colOffset + $adj;
	echo '<br/>'; sort($dd);
	print_r(strtr($dd[0], $cssColumnName));
}

echo r();
function modulo($n, $m)
{
   $r = $n % $m;
   return $r < 0 ? $r + abs($m) : $r; // replace abs($m) with $m if you know that $m > 0
} 

function up_or_dn($x, $y) {
    if ($x == 0) return 0;
    if ($y == 0) return FALSE;
    return ($x % $y >= $y / 2) ?
        (($x - ($x % $y)) / $y) + 1 : ($x - ($x % $y)) / $y;
}

function int_divide($x, $y) {
    return ($x - ($x % $y)) / $y;
}
//echo up_or_dn(8,3);
