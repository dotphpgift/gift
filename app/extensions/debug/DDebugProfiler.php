<?php

class DDebugProfiler extends CProfileLogRoute
{	

	protected function displaySummary($logs)
	{
		$stack=array();
		foreach($logs as $log)
		{
			if($log[1]!==CLogger::LEVEL_PROFILE)
				continue;
			$message=$log[0];
			if(!strncasecmp($message,'begin:',6))
			{
				$log[0]=substr($message,6);
				$stack[]=$log;
			}
			else if(!strncasecmp($message,'end:',4))
			{
				$token=substr($message,4);
				if(($last=array_pop($stack))!==null && $last[0]===$token)
				{
					$delta=$log[3]-$last[3];
					if(!$this->groupByToken)
						$token=$log[2];
					if(isset($results[$token]))
						$results[$token]=$this->aggregateResult($results[$token],$delta);
					else
						$results[$token]=array($token,1,$delta,$delta,$delta);
				}
				else
					throw new CException(Yii::t('yii','DDebugProfiler found a mismatching code block "{token}". Make sure the calls to Yii::beginProfile() and Yii::endProfile() be properly nested.',
						array('{token}'=>$token)));
			}
		}

		$now=microtime(true);
		while(($last=array_pop($stack))!==null)
		{
			$delta=$now-$last[3];
			$token=$this->groupByToken ? $last[0] : $last[2];
			if(isset($results[$token]))
				$results[$token]=$this->aggregateResult($results[$token],$delta);
			else
				$results[$token]=array($token,1,$delta,$delta,$delta);
		}

		$entries=array_values($results);
		$func=create_function('$a,$b','return $a[4]<$b[4]?1:0;');
		usort($entries,$func);
		
		DDebugDB::setInfo($entries);
		//$this->render('profile-summary',$entries);
	}
}