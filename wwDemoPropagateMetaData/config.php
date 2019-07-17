<?php
/****************************************************************************
   Copyright 2013 WoodWing Software BV

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

require_once( dirname(__FILE__).'/PropagateMetaData.class.php' );

/**
 * PMD_APPLY_ON_LAYOUT
 * 
 * Set to true to copy a selection of meta data fields 
 * from layout to placed images and articles 
 * 
 */
if( !defined('PMD_APPLY_ON_LAYOUT') ) {
    define( 'PMD_APPLY_ON_LAYOUT', true );
}

/**
 * PMD_APPLY_ON_DOSSIER
 * 
 * Set to true to copy a selection of meta data fields 
 * from dossier to contained files
 * 
 */
if( !defined('PMD_APPLY_ON_DOSSIER') ) {
    define( 'PMD_APPLY_ON_DOSSIER', true );
}

/**
 * PMD_COPY_CATEGORY
 * 
 * Set to true to copy a changed Category 
 * from parent to child object
 * 
 */
if( !defined('PMD_COPY_CATEGORY') ) {
    define( 'PMD_COPY_CATEGORY', true );
}

/**
 * PMD_COPY_DEADLINE
 * 
 * Set to true to copy a changed Deadline 
 * from parent to child object
 * 
 */
if( !defined('PMD_COPY_DEADLINE') ) {
    define( 'PMD_COPY_DEADLINE', true );
}

/**
 * PMD_STATUS_MAPPING_LIST
 * 
 * Define related statuses for child relations depending on
 * the new parent status. 
 * 
 */
if( !defined('PMD_STATUS_MAPPING_LIST') ) {
    define( 'PMD_STATUS_MAPPING_LIST', serialize(

        // when layout status       => then "Type" status is set to: "Status" 
        // is set to:                   
        array(
            'New'           => 		array(	"Image" => "New",
                                            "Article"  => "New"),	
            'Final+pdf'    => 		array(	"Image" => "Final" ),	
            'CORRECTION'    => 		array(	"Image" => "PRET",
                                            "Article" => "Final" ),
            )

    ));
}

