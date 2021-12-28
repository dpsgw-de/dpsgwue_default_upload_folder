Default upload folder
=====================

Make it possible to configure the default upload folder for subtrees of the page tree.

Based on extension default_upload_folder (https://github.com/beechit/default_upload_folder).

**How to use:**

1. Download from Github
2. Install dpsgwue_default_upload_folder via the extension manager
3. Create the default folders *(folder need to exists and editor needs to have access to the folder)*
4. Add configuration to pageTs

```
    dpsgwue_default_upload_filemount = 1
    dpsgwue_default_upload_filemount.folder = /user_upload/
```

**Requirements:**

    TYPO3 10 LTS
