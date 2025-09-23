<?php

?><a<?php
    ?> href="#"<?php
    ?> class="trigger-logout<?php

    if (!AUTH_TOKEN) {
        echo ' disabled';
    }

    ?>"<?php
?>><?php
    ?><i class="icon icon--gray icon--leave" title="Logout"></i> Logout<?php
?></a><?php