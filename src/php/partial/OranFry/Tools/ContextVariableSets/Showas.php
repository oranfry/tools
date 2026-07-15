<div class="navset">
    <div class="nav-modal">
        <div class="nav-dropdown">
            <?php
                foreach ($this->options as $showas):
                    $href = strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['value' => $showas]);
                    $current = $showas == $this->value ? 'current' : '';
                    $icon = static::$icons[$showas];

                    ?><a class="showas-trigger <?= $current ?>" href="<?= $href ?>"><?php
                        ?><i class="icon icon--gray icon--<?= $icon ?>" alt="<?= $showas ?>"></i><?php
                    ?></a><?php
                endforeach;
            ?>
        </div>
    </div>
    <i class="nav-modal-trigger only-sub1200 current icon icon--gray icon--<?= static::$icons[$this->value] ?>" alt="<?= $this->value ?>"></i>
</div>
