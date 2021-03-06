<?php if (!defined('APPLICATION')) exit();

$PluginInfo['ExcludeCategories'] = array(
    'Description' => 'Exclude categories from discussion index. Inspired by peregrines HideCategories',
    'Version' => '0.1',
    'RequiredApplications' => array('Vanilla' => '2.0.18'),
    'RequiredTheme' => FALSE, 
    'RequiredPlugins' => FALSE,
    'HasLocale' => TRUE,
    'SettingsUrl' => '/settings/excludecategories',
    'SettingsPermission' => 'Garden.AdminUser.Only',
    'Author' => 'Robin'
);

class ExcludeCategoriesPlugin extends Gdn_Plugin {
    public function Setup() {
    } // End of Setup

   public function OnDisable() {
        RemoveFromConfig('Plugins.ExcludeCategories.CategoryIDs');
   } // End of OnDisable

    // thanks to hgtonight for pointing out how this could be done
    public function DiscussionModel_BeforeGet_Handler($Sender) {
        $wheres = $Sender->EventArguments['Wheres'];
        // all discussions
        if(empty($wheres))
        {
            $Sender->SQL->WhereNotIn('d.CategoryID', C('Plugins.ExcludeCategories.CategoryIDs'));
        }
    } // End of DiscussionModel_BeforeGet_Handler
   
    // stolen from addon Anonymouse
    public function SettingsController_ExcludeCategories_Create($Sender) {
        $Sender->Permission('Garden.AdminUser.Only');
  	$Sender->Title('Exclude Categories Settings');
		$Sender->AddSideMenu('settings/excludecategories');
		
		$Validation = new Gdn_Validation();
    	$Validation->ApplyRule('Plugins.ExcludeCategories.CategoryIDs', 'RequiredArray');
		$ConfigurationModel = new Gdn_ConfigurationModel($Validation);
		$ConfigurationModel->SetField(array('Plugins.ExcludeCategories.CategoryIDs'));
		
    	$Form = $Sender->Form;
		$Sender->Form->SetModel($ConfigurationModel);
		
		if ($Sender->Form->AuthenticatedPostBack() != FALSE) {
            if ($Sender->Form->Save() != FALSE) {
				$Sender->StatusMessage = T('Saved');
            }
		} else {
			$Sender->Form->SetData($ConfigurationModel->Data);
		}
	
		$CategoryModel = new Gdn_Model('Category');
		$Sender->CategoryData = $CategoryModel->GetWhere(array('AllowDiscussions' => 1, 'CategoryID <>' => -1));
		
		$Sender->ExcludeCategory = C('Plugins.ExcludeCategories.CategoryIDs');

		$Sender->View = $this->GetView('settings.php');
		$Sender->Render();
	} // End of SettingsController_ExcludeCategories_Create

} // End of ExcludeCategoriesPlugin
