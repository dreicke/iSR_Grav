<?php

/* partials/archives.html.twig */
class __TwigTemplate_1b6691e3bd4060e9f5c18fb5a00374f7de9747ef78f964c50d245e39e6602d0a extends Twig_Template
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
        echo "<ul class=\"archives\">

";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["archives_data"]) ? $context["archives_data"] : null));
        foreach ($context['_seq'] as $context["month"] => $context["items"]) {
            // line 4
            echo "    <li>
    \t<a href=\"";
            // line 5
            echo (isset($context["base_url"]) ? $context["base_url"] : null);
            echo "/archives_month";
            echo $this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "system", array()), "param_sep", array());
            echo twig_escape_filter($this->env, twig_lower_filter($this->env, twig_date_format_filter($this->env, $context["month"], "M_Y")), "url");
            echo "\">
        ";
            // line 6
            if ((isset($context["archives_show_count"]) ? $context["archives_show_count"] : null)) {
                // line 7
                echo "        <span class=\"label\">";
                echo twig_length_filter($this->env, $context["items"]);
                echo "</span>
        ";
            }
            // line 9
            echo "        <span class=\"archive_date\">";
            echo $this->env->getExtension('GravTwigExtension')->translateArray("MONTHS_OF_THE_YEAR", (twig_date_format_filter($this->env, $context["month"], "n") - 1));
            echo " ";
            echo twig_date_format_filter($this->env, $context["month"], "Y");
            echo "</span>
        </a>
    </li>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['month'], $context['items'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 13
        echo "</ul>
";
    }

    public function getTemplateName()
    {
        return "partials/archives.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  58 => 13,  45 => 9,  39 => 7,  37 => 6,  30 => 5,  27 => 4,  23 => 3,  19 => 1,);
    }
}
/* <ul class="archives">*/
/* */
/* {% for month,items in archives_data %}*/
/*     <li>*/
/*     	<a href="{{ base_url }}/archives_month{{ config.system.param_sep }}{{ month|date('M_Y')|lower|e('url') }}">*/
/*         {% if archives_show_count %}*/
/*         <span class="label">{{ items|length }}</span>*/
/*         {% endif %}*/
/*         <span class="archive_date">{{ 'MONTHS_OF_THE_YEAR'|ta(month|date('n') -1) }} {{ month|date('Y') }}</span>*/
/*         </a>*/
/*     </li>*/
/* {% endfor %}*/
/* </ul>*/
/* */
