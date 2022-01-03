<?php
namespace DpsgWue\DpsgWueDefaultUploadFolder\Hooks;
/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 06-04-2016
 * All code (c) Beech Applications B.V. all rights reserved
 */
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Resource\Exception\FolderDoesNotExistException;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class DefaultUploadFolder
 */
class DefaultUploadFolder
{

    /**
     * Get default upload folder
     *
     * @param array $params
     * @param BackendUserAuthentication $backendUserAuthentication
     * @return Folder
     */
    public function getDefaultUploadFolder($params, BackendUserAuthentication $backendUserAuthentication)
    {
        /** @var Folder $uploadFolder */
        $uploadFolder = $params['uploadFolder'];
        $pageTs = BackendUtility::getPagesTSconfig($params['pid']);
        //debug($pageTs, 'pageTs');
        $filemountConfig = $pageTs['dpsgwue_default_upload_filemount'] ?? '';
        $folderConfig = $pageTs['dpsgwue_default_upload_filemount.']['folder'] ?? '';
        //debug($filemountConfig, 'filemount');
        //debug($folderConfig, 'folder');
        if (is_numeric($filemountConfig)) {
          $fileMounts = $backendUserAuthentication->getFileMountRecords();
          $fileStorages = $backendUserAuthentication->getFileStorages();

          $fileMount_arr = array_filter($fileMounts, fn($element) => $element['uid'] == $filemountConfig);
          if (count($fileMount_arr) == 1) {
            $fileMount = array_pop($fileMount_arr);
            $storage = $fileStorages[$fileMount['base']];
            if ($storage->isWritable()) {
              $foundFolder = false;
              if (!empty($folderConfig)) {
                try {
                  $tmpUploadFolder = $storage->getFolder($folderConfig);
                  if ($tmpUploadFolder->checkActionPermission('write')) {
                    $uploadFolder = $tmpUploadFolder;
                    $foundFolder = true;
                  }
                  $tmpUploadFolder = null;
                } catch (\TYPO3\CMS\Core\Resource\Exception $folderAccessException) {
                  // If the folder is not accessible (no permissions / does not exist), ignore.
                }
              }
              if (!$foundFolder) {
                try {
                  $tmpUploadFolder = $storage->getFolder($fileMount['path']);
                  if ($tmpUploadFolder->checkActionPermission('write')) {
                    $uploadFolder = $tmpUploadFolder;
                  }
                  $tmpUploadFolder = null;
                } catch (\TYPO3\CMS\Core\Resource\Exception $folderAccessException) {
                  // If the folder is not accessible (no permissions / does not exist), ignore.
                }
              }
            }
          }
        }

        return $uploadFolder;
    }
}
