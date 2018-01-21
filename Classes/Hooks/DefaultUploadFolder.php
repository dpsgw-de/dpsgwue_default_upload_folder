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
        //$table = $params['table'];
        //$field = $params['field'];
        $pageTs = BackendUtility::getPagesTSconfig($params['pid']);
        $filemountConfig = $backendUserAuthentication->getTSConfig(
            'dpsgwue_default_upload_filemount', $pageTs);
        $folderConfig = $backendUserAuthentication->getTSConfig(
            'dpsgwue_default_upload_filemount.folder', $pageTs);
        if (isset($filemountConfig) && isset($filemountConfig['value'])
            && is_numeric($filemountConfig['value'])) {
          $fileStorages = $backendUserAuthentication->getFileStorages();

          $storage = $fileStorages[$filemountConfig['value']];
          if ($storage->isWritable()) {
            $foundFolder = false;
            if (isset($folderConfig) && isset($folderConfig['value'])) {
              try {
                $tmpUploadFolder = $storage->getFolder($folderConfig['value']);
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
                $tmpUploadFolder = $storage->getDefaultFolder();
                if ($tmpUploadFolder->checkActionPermission('write')) {
                  $uploadFolder = $tmpUploadFolder;
                  $foundFolder = true;
                }
                $tmpUploadFolder = null;
              } catch (\TYPO3\CMS\Core\Resource\Exception $folderAccessException) {
                // If the folder is not accessible (no permissions / does not exist), ignore.
              }
            }
          }
        }

        return $uploadFolder;
    }
}
