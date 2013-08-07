<?php defined('APPLICATION') or die();

echo Wrap($this->Data('Title'), 'h1');
echo Wrap('Check any of the categories below to hide the discussions in that category from discussion view.', 'div', array('class' => 'Info'));

echo $this->Form->Open();
echo $this->Form->Errors();

echo $this->Form->CheckBoxList('Plugins.ExcludeCategories.CategoryIDs', $this->CategoryData, $this->ExcludeCategory, array('ValueField' => 'CategoryID', 'TextField' => 'Name'));

echo $this->Form->Button('Save');
echo $this->Form->Close();
