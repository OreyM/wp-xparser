<?php
/**
 * Error handler & debuger class
 */

class Debug
{
    public function __construct()
    {
        error_reporting(0);
        ob_start();
        set_error_handler( [$this, 'errorHandler'] );
        register_shutdown_function( [$this, 'fatalErrorHandler'] );
        set_exception_handler( [$this, 'exeptionHandler'] );
    }

    /**
     * Метод преобразования кода ошибки в текст
     * @param int $errno   код ошибки
     * @return string
     */
    private function errorType ( $errno )
    {
        $errors = array(
            E_ERROR => '[ERROR] Фатальная Ошибка',
            E_WARNING => '[WARNING] Предупреждение',
            E_PARSE => '[PARSE] Ошибка разбора исходного кода',
            E_NOTICE => '[NOTICE] Уведомление',
            E_CORE_ERROR => '[CORE ERROR] Ошибка ядра',
            E_CORE_WARNING => '[CORE WARNING] Предупреждение ядра',
            E_COMPILE_ERROR => '[COMPILE ERROR] Ошибка на этапе компиляции',
            E_COMPILE_WARNING => '[COMPILE WARNING] Предупреждение на этапе компиляции',
            E_USER_ERROR => '[USERERROR] Пользовательская ошибка',
            E_USER_WARNING => '[USER WARNING] Пользовательское предупреждение',
            E_USER_NOTICE => '[USER NOTICE] Пользовательское уведомление',
            E_STRICT => '[STRICT] Уведомление времени выполнения',
            E_RECOVERABLE_ERROR => '[RECOVERABLE ERROR] Отлавливаемая фатальная ошибка',
            E_DEPRECATED => '[DEPRECATED] Использовании устаревших конструкций',
            E_USER_DEPRECATED => '[USER DEPRECATED] Использовании устаревших конструкций, сгенерированные пользователем',
            'Exception' => '[Exception] Вызов исключения'
        );

        return $errors[$errno];
    }

    /**
     * Метод проверки, произошла ли фатальная ошибка
     * @return array|bool
     */
    private function checkFatalError ()
    {
        return ( $error = error_get_last() ) && $error['type'] & ( E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)  ? $error : FALSE;
    }

    /**
     * Метод формирования вывода данных для дебага массива или объекта
     * @param array|object $data   данные для дебага
     * @param array $newArray   данные для вывода
     */
    private static function varExtract ( $data, &$newArray )
    {

        foreach ($data as $key => $value)
        {
            $type = gettype($value);
            $indexType = gettype($key);

            if ( is_object($data) )
            {
                $key = "->{$key}";
            }
            else
            {
                $key = $indexType === 'integer' ? "[{$key}]" : "['{$key}']";
            }

            switch($type)
            {
                case 'boolean':
                    $value = $value ? 'TRUE' : 'FALSE';
                    $newArray[] = "<tr><td class=\"boolean\">({$type})</td><td>{$key}</td><td>=></td><td>{$value}</td></tr>";
                break;

                case 'integer':
                    $newArray[] = "<tr><td class=\"integer\">({$type})</td><td>{$key}</td><td>=></td><td>{$value}</td></tr>";
                break;

                case 'double':
                    $newArray[] = "<tr><td class=\"double\">({$type})</td><td>{$key}</td><td>=></td><td>{$value}</td></tr>";
                break;

                case 'string':
                    $strlen = strlen( $value );
                    if ( $strlen > 38 )
                    {
                        $newArray[] = '</tbody></table>';
                        $newArray[] = '<details class="long-string">';
                            $newArray[] = "<summary><span class=\"string\">({$type})</span> {$key} ::: <span>Длинна строки [{$strlen}]. Показать:</span></summary>";
                            $newArray[] = "<summary>{$value}</summary>";
                        $newArray[] = '</details>';
                        $newArray[] = '<table><tbody>';
                    }
                    else
                    {
                        $newArray[] = "<tr><td class=\"string\">({$type})</td><td>{$key}</td><td>=></td><td>{$value}</td></tr>";
                    }

                break;

                case 'resource':
                    $resType = get_resource_type($value);
                    $newArray[] = '</tbody></table>';
                    $newArray[] = '<details class="offset">';
                        $newArray[] = "<summary class=\"resource\">|Ресурс| {$key} (Тип реурса: {$resType})</summary>";
                        $newArray[] = "<summary>{$value}</summary>";
                    $newArray[] = '</details>';
                    $newArray[] = '<table><tbody>';
                break;

                case 'NULL':
                    $newArray[] = "<tr><td class=\"null\">({$type})</td><td>{$key}</td><td>=></td><td>Без значения</td></tr>";
                break;

                case 'unknown type':
                    $newArray[] = "<tr><td class=\"unknown - type\">({$type})</td><td>{$key}</td><td>=></td><td>Неизветный тип</td></tr>";
                break;

                case 'array':
                    $newArray[] = '</tbody></table>';
                    $newArray[] = '<details class=\"offset\">';
                    $newArray[] = "<summary class=\"array\">[Массив] {$key} (Размер: " . count($value) . ')</summary>';

                    $newArray[] = '<table><tbody>';
                    self::varExtract ( $value, $newArray );
                    $newArray[] = '</tbody></table>';

                    $newArray[] = '</details>';
                    $newArray[] = '<table><tbody>';
                break;

                case 'object':
                    $newArray[] = '</tbody></table>';
                    $newArray[] = '<details class=\"offset\">';
                    $newArray[] = "<summary class=\"object\">{Объект} {$key} (Размер: " . count($value) . ')</summary>';

                    $newArray[] = '<table><tbody>';
                    self::varExtract ( $value, $newArray );
                    $newArray[] = '</tbody></table>';

                    $newArray[] = '</details>';
                    $newArray[] = '<table><tbody>';
                break;
            }
        }
    }

    /**
     * Вывод данных об ошибке
     * @param int $errno   код ошибки
     * @param string $errstr   текст ошибки
     * @param string $errfile   файл, в котором произошла ошибка
     * @param int $errline   строка, на которой произошла ошибка
     * @param int $response   код сервера
     * @return void
     */
    protected function showError ( $errno, $errstr, $errfile, $errline, $response = 0 )
    {
        $errType = $this->errorType( $errno );

        require_once 'error/style.php';
        require 'error/devError.php';
    }

    /**
     * Метод логирования ошибок
     * @param int $errno   код ошибки
     * @param string $errstr   текст ошибки
     * @param string $errfile   файл, в котором произошла ошибка
     * @param int $errline   строка, на которой произошла ошибка
     */
    protected function errorsLog ( $errno, $errstr, $errfile, $errline )
    {
        $errLogStr =
            '[' . date( 'Y-m-d H:i:s' ) . '] Текст ошибки: ' . $errstr . ' Файл: ' . $errfile . ' | Строка: ' . $errline;

//        error_log( $errLogStr . "\n", 3, '/log/error.log');
    }

    /**
     * Метод обработки ошибок
     * @param int $errno   код ошибки
     * @param string $errstr   текст ошибки
     * @param string $errfile   файл, в котором произошла ошибка
     * @param int $errline   строка, на которой произошла ошибка
     * @return bool
     */
    public function errorHandler ( $errno, $errstr, $errfile, $errline )
    {
        $this->errorsLog( $errno, $errstr, $errfile, $errline );
        $this->showError( $errno, $errstr, $errfile, $errline );

        return TRUE;
    }

    /**
     * Метод обработки фатальных ошибок
     * @return void
     */
    public function fatalErrorHandler ()
    {
        if ( FALSE !== ( $error = $this->checkFatalError() ) )
        {
            $this->errorsLog( $error['type'], $error['message'], $error['file'], $error['line'] );
            $this->showError( $error['type'], $error['message'], $error['file'], $error['line'] );
        }
    }

    /**
     * Метод обработки исключений
     * @param \Exception $e
     */
    public function exeptionHandler ( $e )
    {
        $this->errorsLog( 'Exception', $e->getMessage(), $e->getFile(), $e->getLine() );
        $this->showError( 'Exception', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode() );
    }

    /**
     * Метод формирования вывода дебага
     * @param mixed ...$debugData   данные для дебага
     */
    public static function debug( ...$debugData )
    {
        $backtrace = debug_backtrace();
        $debugInfo = array();

        foreach ( $debugData as $data )
        {
            switch ( gettype( $data ) )
            {
                case 'integer':
                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'var' => $data
                    ];
                break;
                case 'double':
                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'var' => $data
                    ];
                break;
                case 'string':
                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'var' => $data
                    ];
                break;
                case 'boolean':
                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'var' => $data ? 'TRUE' : 'FALSE'
                    ];
                break;
                case 'NULL':
                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'addInfoType' => ' | Переменная без значения',
                        'var' => 'NULL'
                    ];
                break;
                case 'resource':
                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'addInfoType' => ' | Cсылка на внешний ресурс',
                        'var' => get_resource_type($data)
                    ];
                break;
                case 'unknown type':
                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'addInfoType' => ' | Неизвестный тип данных',
                        'var' => FALSE
                    ];
                break;
                case 'array':
                    $newArray = array();
                    $newArray[] = '<details open class="offset"><summary class="array">[Массив] (Размер: ' . count($data) . ')</summary>';

                    $newArray[] = '<table><tbody>';
                    self::varExtract ( $data, $newArray );
                    $newArray[] = '</tbody></table></details>';

                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'addInfoType' => ' | Массив',
                        'var' => $newArray
                    ];

                break;
                case 'object':
                    $newArray = array();
                    $newArray[] = '<details open class="offset"><summary class="array">{Объект} (Размер: ' . count($data) . ')</summary>';

                    $newArray[] = '<table><tbody>';
                    self::varExtract ( $data, $newArray );
                    $newArray[] = '</tbody></table></details>';

                    $debugInfo = [
                        'varType' => gettype( $data ),
                        'addInfoType' => ' | Объект',
                        'var' => $newArray
                    ];

                break;
            }
            require_once 'error/style.php';
            require 'error/debug.php';
        }
    }
}