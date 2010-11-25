{*if(is_array($this->sectionContent) && empty($this->sectionContent[0])){*}
							<ul class="bulletNav">

			{foreach key=key item=value from=$section->getContent()}
				{if !is_array($value)}
								<li><a href="{$value}">{$key}</a></li>
				{/if}
				{*else if(!empty($value['link']))*}
				{*{*}
					{*if($key != "Admin" && $key != "Admin Home")*}
					{*{*}
						{*$content .= '*}
								{*<li><a href="'.$value['link'].'">'.$key.'</a></li>';*}
					{*}*}
				{*}*}
				{*else*}
				{*{*}
					{*$content .= '*}
							{*<li>'.$key.'*}
								{*<ul>';*}

					{*foreach($value as $text => $link)*}
					{*{*}
						{*$content .= '*}
									{*<li><a href="'.$link.'">'.$text.'</a></li>';*}
					{*}*}

					{*$content .= '*}
								{*</ul>*}
							{*</li>';*}
				{*}*}
			{*}*}{/foreach}

			{*$content .= '*}
							</ul>
		{*}else if(is_array($this->sectionContent)){*}
			{*foreach($this->sectionContent as $contentPiece)*}
			{*{*}
				{*$content .= $contentPiece;*}
				{*if(is_object($contentPiece) && is_a($contentPiece,'ContentObject'))*}
				{*{*}
					{*$this->checkDependencies($contentPiece);*}
				{*}*}
			{*}*}
		{*}else{*}
			{*$content .= $this->sectionContent;*}
			{*if(is_object($this->sectionContent) && is_a($this->sectionContent,'ContentObject'))*}
			{*{*}
				{*$this->checkDependencies($this->sectionContent);*}
			{*}*}
		