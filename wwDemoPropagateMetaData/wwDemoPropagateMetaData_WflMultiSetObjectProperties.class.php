<?php
/****************************************************************************
   Copyright 2018 WoodWing Software BV

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

require_once BASEDIR . '/server/interfaces/services/wfl/WflMultiSetObjectProperties_EnterpriseConnector.class.php';

class wwDemoPropagateMetaData_WflMultiSetObjectProperties extends WflMultiSetObjectProperties_EnterpriseConnector
{
	final public function getPrio()     { return self::PRIO_DEFAULT; }
	final public function getRunMode()  { return self::RUNMODE_BEFOREAFTER; }

	final public function runBefore( WflMultiSetObjectPropertiesRequest &$req )
	{
		LogHandler::Log( 'wwDemoPropagateMetaData', 'DEBUG', 'Called: wwDemoPropagateMetaData_WflMultiSetObjectProperties->runBefore()' );
		require_once dirname(__FILE__) . '/config.php';

		// TODO: Add your code that hooks into the service request.
		// NOTE: Replace RUNMODE_BEFOREAFTER with RUNMODE_AFTER when this hook is not needed.

		LogHandler::Log( 'wwDemoPropagateMetaData', 'DEBUG', 'Returns: wwDemoPropagateMetaData_WflMultiSetObjectProperties->runBefore()' );
	} 

	final public function runAfter( WflMultiSetObjectPropertiesRequest $req, WflMultiSetObjectPropertiesResponse &$resp )
	{
		LogHandler::Log( 'wwDemoPropagateMetaData', 'DEBUG', 'Called: wwDemoPropagateMetaData_WflMultiSetObjectProperties->runAfter()' );
		require_once dirname(__FILE__) . '/config.php';

		// TODO: Add your code that hooks into the service request.
		// NOTE: Replace RUNMODE_BEFOREAFTER with RUNMODE_BEFORE when this hook is not needed.

		LogHandler::Log( 'wwDemoPropagateMetaData', 'DEBUG', 'Returns: wwDemoPropagateMetaData_WflMultiSetObjectProperties->runAfter()' );
	} 
	
	final public function onError( WflMultiSetObjectPropertiesRequest $req, BizException $e )
	{
		LogHandler::Log( 'wwDemoPropagateMetaData', 'DEBUG', 'Called: wwDemoPropagateMetaData_WflMultiSetObjectProperties->onError()' );
		require_once dirname(__FILE__) . '/config.php';

		LogHandler::Log( 'wwDemoPropagateMetaData', 'DEBUG', 'Returns: wwDemoPropagateMetaData_WflMultiSetObjectProperties->onError()' );
	} 
	
	// Not called.
	final public function runOverruled( WflMultiSetObjectPropertiesRequest $req )
	{
	}
}
