<?php
/****************************************************************************
   Copyright 2014 WoodWing Software BV

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
****************************************************************************/

require_once BASEDIR . '/server/interfaces/plugins/connectors/CustomObjectMetaData_EnterpriseConnector.class.php';

class wwDemoPropagateMetaData_CustomObjectMetaData extends CustomObjectMetaData_EnterpriseConnector
{
	/**
	 * See introduction at module header above.
	 *
	 * The $coreInstallation is set to:
	 * L> True: When the core calls it in the context of installation procedure or from server plugin page.
	 * L> False: In case you provide your own admin page that should take over this installation,
	 *           call BizProperty::validateAndInstallCustomProperties() and pass in $coreInstallation = False.
	 *           Being called in your connector, check for this flag($installAutomatically) and only return
	 *           custom properties when set to false.
	 *
	 *
	 * The connector should return the following structure:
	 *    array[brand id][object type] => array of PropertyInfo
	 * Use zero (0) for brand id or object type to indicate 'all'.
	 *
	 * For example:
	 *    $retVal = array();
	 *    // custom properties to appear for all brand, all object types:
	 *    $retVal[0][0] = array( PropertyInfo(...), PropertyInfo(...) );
	 *    // custom properties to appear for articles owned by brand id 123:
	 *    $retVal[123]['Article'] = array( PropertyInfo(...), PropertyInfo(...) );
	 *    return $retVal;
	 *
	 * Best practices for Publish Form (Templates):
	 *    $propInfo = new PropertyInfo();
	 * 	  ...
	 * 	  $propInfo->AdminUI = false;
	 * 	  $propInfo->PublishSystem = '<PublishSystemName>'; // String value - The name of the Publishing Connector Plug-in
	 * 	  $propInfo->TemplateId = <TemplateId>; // Integer value - The object id of the Publish Form Template
	 *
	 *    $retVal = array();
	 *    $retVal[0]['PublishForm'] = array( $propInfo );
	 *    return $retVal;
	 *
	 * This way the custom properties are only available for Publish Forms. This will save time because they won't be retrieved
	 * for other object types. The AdminUI property makes sure this is only available to users when opening a Publish Form. The PublishSystem
	 * and TemplateId properties are optional but very useful when having a lot of custom properties.
	 *
	 * @param bool $coreInstallation See function header above.
	 * @return array See function header above how to structure.
	 */
	public function collectCustomProperties( $coreInstallation ) 
	{		
		require_once "config.php";
		
		$props = array();

		/**
		 * @param string               $Name                 
		 * @param string               $DisplayName          
		 * @param string               $Category             Nullable.
		 * @param PropertyType         $Type                 
		 * @param string               $DefaultValue         Nullable.
		 * @param array of String      $ValueList            Nullable.
		 * @param string               $MinValue             Nullable.
		 * @param string               $MaxValue             Nullable.
		 * @param int                  $MaxLength            Nullable.
		 * @param array of PropertyValue $PropertyValues       Nullable.
		 * @param string               $ParentValue          Nullable.
		 * @param array of Property    $DependentProperties  Nullable.
		 * @param string               $MinResolution        Nullable.
		 * @param string               $MaxResolution        Nullable.
		 * @param array of DialogWidget $Widgets              Nullable.
		 * @param string               $ObjectId             Nullable.
		 */
	
	//								 Name					Display name		Group	Type		Default		Value list
	    $props[] = new PropertyInfo( "C_PARENT_STATUS",		"Layout Status",	'',		'string',	'' 	);

		$retVal= array();
		$retVal[0][0] = $props;
		return $retVal;
		
	}

	public function getPrio() { return self::PRIO_DEFAULT; }


}
