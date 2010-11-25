$entity = '
			<div class="entityBlock '.$this->type.'">
	      		<dl>
	        		<dt>'.$this->title.'</dt>';
		foreach($this->details as $detail)
		{
			$entity .= '
						<dd>'.$detail.'</dd>';
		}
		$entity .= '
						<dd>
							<ul class="actions">';

		foreach($primaryActions as $text => $details)
		{
			if(empty($this->activeActions[$text]))
			{
				$entity .= '
								<li class="'.strtolower($text).'-item"><a class="item-action" href="'.$details['link'].$this->id.'"';
				if(!empty($details['confirmation']))
				{
					$entity .= ' onclick="if (confirm('."'".$this->preConf.$details['confirmation']."'".')) return true; else return false;"';
				}

				$entity .= '>';

				$entity .= $text.'</a></li>';
			}
		}

		$entity .= '
							</ul>';

		if(!empty($this->extraActionRows))
		{
			foreach($this->extraActionRows as $row)
			{
				$entity .= '
							<ul class="actions">';
				foreach($row as $actions)
				{
					if(!isset($actions['active']) || $actions['active'] == TRUE)
					{
						$entity .= '
									<li class="item-action '.$actions['class'].'"><a href="'.$actions['link'].$this->id.'"';
						if(!empty($actions["$action_text"]['confirmation']))
						{
							$entity .= ' onclick="if (confirm('."'".$this->preConf.$actions["$action_text"]['confirmation']."'".')) return true; else return false;"';
						}

						$entity .= '>'.$actions['text'].'</a></li>';
					}
				}
				$entity .= '
						</ul>';
			}
		}
		$entity .= '
					</dd>
  				</dl>
  			</div>