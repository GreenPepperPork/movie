<?php
namespace zaor;

class Handle
{
    protected $report;

    protected $ignoreReport = [];

    function __construct()
    {
    }

    public function report(Exception $e)
    {
        if (!$this->isIgnoreReport($e)) {
            $this->report = [
                'code'    => $e->getCode(),
                'name'    => $e->getFile(),
                'line'    => $e->getLine(),
                'message' => $e->getMessage()
            ];
        }

        trigger_error($e->getMessage());
        // TODO write to log
    }

    public function isIgnoreReport(Exception $e)
    {
        foreach ($this->ignoreReport as $class) {
            if ($e instanceof $class) {
                return true;
            }
        }

        return false;
    }

    public function render($tpl)
    {
        $errorTpl = is_file($tpl) ? $tpl : Config::get($tpl);

        ob_start();
        include $errorTpl;
        $content = ob_get_clean();

        echo $content;
    }

    function __get($name)
    {
        return isset($this->report[$name]) ? $this->report[$name] : '';
    }

}
