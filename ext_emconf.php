<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "default_upload_folder"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'DPSG Würzburg Default upload folder',
    'description' => 'Make it possible to configure the default upload folder in page TypoScript',
    'category' => 'be',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Frans Saris and DPSG Würzburg',
    'author_email' => 'technik@dpsg-wuerzburg.de',
    'author_company' => 'Beech.it and DPSG Würzburg',
    'version' => '1.0.1',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '7.6.4-8.7.99',
                ],
            'conflicts' =>
                [
                ],
            'suggests' =>
                [
                ],
        ],
    'clearcacheonload' => true,
];

