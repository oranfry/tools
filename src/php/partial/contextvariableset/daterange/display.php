<div class="navset">
    <div class="inline-rel">
        <div class="inline-modal">
            <div class="nav-dropdown nav-dropdown--always">
                 <?php foreach ($this->periods as $shortname => $period): ?>
                    <?php if ($current = ($shortname == $this->period_id)) {
                        $currentShortname = $shortname;
                    }; ?>
                    <a class="<?= $current ? 'current' : '' ?>" href="<?= strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['period' => $shortname, 'date' => $this->date]); ?>"><?= $period->label() ?></a>
                <?php endforeach ?>
                <input class="cv-surrogate" data-for="<?= $this->prefix ?>__date" type="text" value="<?= $this->chunk->start() ?>" style="margin: 0.5em; width: 7em">
            </div>
        </div>
        <a class="inline-modal-trigger"><?= $currentShortname ?></a>

        <?php if ($this->chunk->start() !== null || $this->chunk->end() !== null): ?>
            <?php if ($this->chunk->start() !== null): ?>
                <div class="drnav <?= $highlight[0] ?>"><a class="icon icon--gray icon--arrowleft" href="<?= strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['date'=> $prev->start()]); ?>"></a></div>
            <?php endif ?>

            <div class="drnav <?= $highlight[1] ?>"><a class="icon icon--gray icon--dot" href="<?= strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['date'=> date('Y-m-d')]); ?>"></a></div>

            <?php if ($this->chunk->end() !== null): ?>
                <div class="drnav <?= $highlight[2] ?>"><a class="icon icon--gray icon--arrowright" href="<?= strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['date'=> $next->start()]); ?>"></a></div>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>