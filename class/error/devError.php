<div class="container-hl">
        <div class="background-error-hl">
            <div class="error-header-hl"><?= $errType; ?> [code:<?= $errno; ?>]</div>
            <div class="error-content-hl">
                <p class="error-text"><?= $errstr; ?></p>
                <p><strong>Файл:</strong> <?= $errfile; ?></p>
                <p><strong>Строка:</strong> <?= $errline; ?></p>
            </div>
        </div>
</div>