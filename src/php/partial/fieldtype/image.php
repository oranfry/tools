<div id="bio-view-container"><?php
    if (@$value) {
        ?><img style="width: 400px; height: 400px" src="<?= 'data: image/jpg;base64,' . base64_encode($value) ?>"><?php
    } else {
        ?>None<?php
    }
?></div><?php

?><div id="bio-preview-container" style="display: none"><img id="bio-preview" style="width: 400px"></div><?php
?><label id="bio-upload-trigger" for="bio-upload" style="text-decoration: underline; cursor: pointer;"><?= @$value ? 'Replace' : 'Upload' ?></label><?php
?><a id="bio-reset" onclick="document.getElementById('bio-upload').value = ''; clearBioPreview();" style="display: none; text-decoration: underline; cursor: pointer;">Reset</a><?php
?><input name="bio_image" type="file" id="bio-upload" style="display: none" onchange="showBioPreview(event)" ><?php
