<?php

//
// The class in this module takes care of forwarding meta data changes from a Layout (or optionally a Dossier) 
// to the child files (typically Images and Articles). 
//
// It's used in the runafter for SaveObjects and SetObjectProperties.
//
// The feature is configurable in the config.php file
//
//

class PropagateMetaData {

	public static function runAfter( $parent ) {
	
		require_once( BASEDIR.'/server/bizclasses/BizObject.class.php');
	
		//
		// only apply on Layout or Dossier, but only when configured in config.php
		//
		$parenttype = $parent->MetaData->BasicMetaData->Type;

		if (!((($parenttype == 'Layout') && PMD_APPLY_ON_LAYOUT) 
			|| (($parenttype == 'Dossier') && PMD_APPLY_ON_DOSSIER)))
	        return;
	        
		//
		// get all child relations for the parent object
		// in case of Layout these are Placed relations
		// in case of Dossier these are Contained relations
		//
		
		if (!isset($parent->Relations)) {
			$parent = BizObject::getObject( $parent->MetaData->BasicMetaData->ID, 
											BizSession::getShortUserName(), 
											false, 
											'none', 
											array('Pages','Relations','Targets') );
			
		}
	
		//
		// prepare the status mapping array as configured in config.php
		// 
		$statusmapping = unserialize(PMD_STATUS_MAPPING_LIST);
		$newparentstatus = $parent->MetaData->WorkflowMetaData->State->Name;
		
		//
		// now process all relations of the parent object, one by one
		//
		foreach($parent->Relations as $relation) {
		
			if ($relation->Type == 'Placed' || $relation->Type == 'Contained') {
				
				//
				// get the meta data of the child object
				//
				$childobject = BizObject::getObject(  $relation->Child, 
														BizSession::getShortUserName(), 
														false, 
														'none',
														array('Targets'));	
																		
				$childtype = $childobject->MetaData->WorkflowMetaData->State->Type;
				$childtargets = $childobject->Targets;

				// //
				// //	child objects will always inherit layout targets
				// //  this fails in a multi channel set-up
				// //
				// $parenttargets = $parent->Targets;
				
				//
				//	first check if the parent has moved to a different Brand
				//	if this is the case, the child object has to get a new (default) status, 
				//	as status ids are unique for a brand
				// 
				if ($parent->MetaData->BasicMetaData->Publication->Id != $childobject->MetaData->BasicMetaData->Publication->Id) {

					// update publication (== brand) of child object
					$childobject->MetaData->BasicMetaData->Publication = $parent->MetaData->BasicMetaData->Publication;
			
					// look up a status with identical name in the new brand/publication
					$statuscache = DBWorkflow::listStatesCached( $parent->MetaData->BasicMetaData->Publication->Id, null, null, 
					$childobject->MetaData->WorkflowMetaData->State->Type  );

					$currentstate = $childobject->MetaData->WorkflowMetaData->State->Name;	
					$childobject->MetaData->WorkflowMetaData->State->Id = $statuscache[0]['id'];
					foreach ($statuscache as $s) {
						if ($s['stat'] == $currentstate) {
							$childobject->MetaData->WorkflowMetaData->State->Id = $s['id'];
							break;
						}
					}			
				}

				// 
				// now start to copy meta data from parent to child
				// category and status propagation only possible when parent and child are in the same Brand
				if ($parent->MetaData->BasicMetaData->Publication->Id == $childobject->MetaData->BasicMetaData->Publication->Id) {
				
					// copy the category from layout to article or image
					if (PMD_COPY_CATEGORY) {
	 					$childobject->MetaData->BasicMetaData->Category = $parent->MetaData->BasicMetaData->Category;
					}

					// prepare status lookup
					$statuscache = DBWorkflow::listStatesCached( $parent->MetaData->BasicMetaData->Publication->Id, null, null, 
								$childobject->MetaData->WorkflowMetaData->State->Type  );


					//
					// check if the status mapping contains a new status for the child object,
					// depends on object type and new status of the parent
					//
					if (array_key_exists($newparentstatus, $statusmapping)){
						if (array_key_exists($childtype, $statusmapping[$newparentstatus])) {
							$newchildstatus = $statusmapping[$newparentstatus][$childtype];
							
							foreach ($statuscache as $s) {
								if ($s['state'] == $newchildstatus) {
									$childobject->MetaData->WorkflowMetaData->State->Id = $s['id'];
									$childobject->MetaData->WorkflowMetaData->State->Name = $s['state'];
									break;
								}
							}		
						}
					}
				}
				
				//
				// propagation always possible, independent of Brand
				//
				if (PMD_COPY_DEADLINE) {
					$childobject->MetaData->WorkflowMetaData->Deadline 	= $parent->MetaData->WorkflowMetaData->Deadline;
					$childobject->MetaData->WorkflowMetaData->DeadlineSoft = $parent->MetaData->WorkflowMetaData->DeadlineSoft;
				}

				//
				// and update the metadata for this child object
				//
				$user = BizSession::getShortUserName();
				BizObject::setObjectProperties( $childobject->MetaData->BasicMetaData->ID,
												$user,
												$childobject->MetaData,
												$childtargets );

			}
		}	
	}

	

// =====================================================================================
//
//	get/set a custom meta data value in an object's meta data structure
//  custom meta data is stored as an object in the ->ExtraMetaData array
//	Each ExtraMetaData object has a Property (field name) and a Values array
//
	private static function getSetCustomField($meta, $key, $value = null) {
		$oldvalue = null;
		$xmeta = null;
		
		foreach ($meta->ExtraMetaData as $index => &$metadata) {
			if ($metadata->Property == $key) {
				$xmeta = $metadata;
				break;
			}
		}

		if ($xmeta == null) {
			$xmeta = new ExtraMetaData( $key, $value);
			$meta->ExtraMetaData[] = $xmeta;
		}
		else {	
			if ( count( $xmeta->Values ) > 0 ) {
				$oldvalue = $xmeta->Values;
			} else {
				// get the first value
				$oldvalue = reset($xmeta->Values);
			}	

			if ($value != null) {
				if ( is_array($value) ) {
					$xmeta->Values = $value;
				} else {
					$xmeta->Values[0] = $value;
				}
			}
		}
		
		return $oldvalue;
	}
}