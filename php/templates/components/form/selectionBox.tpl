<label for="{$field->getName()}">{$field->getLabel()}</label>
<select name="{$field->getName()}" id="{$field->getName()}" class="textInput">
{include file='components/form/options.tpl' options=$field->getOptions()}
		{*$form .= $this->optionsToString($this->options);			*}
		{*if(!empty($this->optGroups))*}
		{*{*}
			{*foreach($this->optGroups as $optGroup => $options)*}
			{*{				*}
				{*$form .= '*}
					{*<optgroup label="'.$optGroup.'">';*}
					{**}
				{*$form .= $this->optionsToString($options);*}
					{**}
				{*$form .= '*}
					{*</optgroup>';*}
			{*}*}
		{*}*}
</select>