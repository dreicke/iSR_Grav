<?php

/* partials/simplesearch_searchbox.html.twig */
class __TwigTemplate_fe25db459d0e590afdf8fbae68c9277ba49f6068e79601a0681420fae487242b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<input type=\"text\" placeholder=\"Search...\" value=\"";
        echo (isset($context["query"]) ? $context["query"] : null);
        echo "\" data-search-input=\"";
        echo (isset($context["base_url"]) ? $context["base_url"] : null);
        echo $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "simplesearch", array()), "route", array());
        echo "/query\" />
<script>
jQuery(document).ready(function(\$){
    var input = \$('[data-search-input]');

    input.on('keypress', function(event) {
        if (event.which == 13 && input.val().length > 3) {
            event.preventDefault();
            window.location.href = input.data('search-input') + '";
        // line 9
        echo $this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "system", array()), "param_sep", array());
        echo "' + input.val();
        }
    });
});
</script>
";
    }

    public function getTemplateName()
    {
        return "partials/simplesearch_searchbox.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  34 => 9,  19 => 1,);
    }
}
/* <input type="text" placeholder="Search..." value="{{ query }}" data-search-input="{{ base_url }}{{ config.plugins.simplesearch.route}}/query" />*/
/* <script>*/
/* jQuery(document).ready(function($){*/
/*     var input = $('[data-search-input]');*/
/* */
/*     input.on('keypress', function(event) {*/
/*         if (event.which == 13 && input.val().length > 3) {*/
/*             event.preventDefault();*/
/*             window.location.href = input.data('search-input') + '{{ config.system.param_sep }}' + input.val();*/
/*         }*/
/*     });*/
/* });*/
/* </script>*/
/* */
