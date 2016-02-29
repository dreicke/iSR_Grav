<?php

/* form.html.twig */
class __TwigTemplate_cf2c7e3752d00ed1e85c7ef769a7961b86ec5eb077bb3eb2f550082eaf6494ab extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("partials/base.html.twig", "form.html.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "partials/base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
    ";
        // line 5
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
    ";
        // line 6
        $this->loadTemplate("forms/form.html.twig", "form.html.twig", 6)->display($context);
        // line 7
        echo "
";
    }

    public function getTemplateName()
    {
        return "form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  40 => 7,  38 => 6,  34 => 5,  31 => 4,  28 => 3,  11 => 1,);
    }
}
/* {% extends 'partials/base.html.twig' %}*/
/* */
/* {% block content %}*/
/* */
/*     {{ content }}*/
/*     {% include "forms/form.html.twig" %}*/
/* */
/* {% endblock %}*/
/* */
