<?php
class HF_Controller
{
    protected $config;
    protected $tpl;
    protected $core;

    public function __construct($config, $core, $tpl = null)
    {
        $this->config = $config;
        $this->tpl = $tpl;
        $this->core = $core;
    }

    public function loadRender($template, $parameters=array())
    {
        $this->tpl->loadTemplate($template);
        return $this->tpl->render($parameters);
    }

}