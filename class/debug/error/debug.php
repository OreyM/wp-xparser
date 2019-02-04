<div class="container-hl">
        <div class="background-error-hl">
            <div class="error-header-hl debug-header-hl">
                <span>[DEBUG]</span>
            </div>
            <div class="error-content-hl">
                <p class="p-hl">
                    <span>Тип переменной: </span>
                    <strong>
                        <?= $debugInfo['varType'] ?>
                        <?php if ( isset( $debugInfo['addInfoType'] ) ): ?>
                            <?= $debugInfo['addInfoType'] ?>
                        <?php endif; ?>
                    </strong>
                </p>

                <?php if ( $debugInfo['varType'] === 'resource' ): ?>
                    <p><span>Тип ресурса: </span><?= $debugInfo['var'] ?></p>
                <?php elseif ( $debugInfo['varType'] === 'unknown type' && $debugInfo['var'] === FALSE ): ?>
                    <!-- NO INFO -->
                <?php elseif ( $debugInfo['varType'] === 'array' || $debugInfo['varType'] === 'object' ): ?>
                        <?php foreach ( $debugInfo['var'] as $debugData ): ?>
                            <?= $debugData ?>
                        <?php endforeach; ?>
                <?php else: ?>
                    <p><span>Значение: </span><strong><?= $debugInfo['var'] ?></strong></p>
                <?php endif; ?>
            </div>
            <div class="debug-footer-hl">
                <span>
                    # Дебаг вызван: <?= $backtrace[0]['file']; ?> || строка: <?= $backtrace[0]['line']; ?>
                </span>
            </div>
        </div>
</div>