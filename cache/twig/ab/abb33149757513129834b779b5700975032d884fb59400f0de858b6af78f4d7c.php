<?php

/* partials/pagination.html.twig */
class __TwigTemplate_2e38deb09072fbe56f83e3695585c87a53cf01c7178ed0e531e12136b4064a2c extends Twig_Template
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
        if ((twig_length_filter($this->env, (isset($context["pagination"]) ? $context["pagination"] : null)) > 1)) {
            // line 2
            echo "
<ul class=\"pagination\">
    ";
            // line 4
            if ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "hasPrev", array())) {
                // line 5
                echo "        ";
                $context["url"] = twig_replace_filter((((isset($context["base_url"]) ? $context["base_url"] : null) . $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "params", array())) . $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "prevUrl", array())), array("//" => "/"));
                // line 6
                echo "        <li><a rel=\"prev\" href=\"";
                echo (isset($context["url"]) ? $context["url"] : null);
                echo "\">&laquo;</a></li>
    ";
            } else {
                // line 8
                echo "        <li><span>&laquo;</span></li>
    ";
            }
            // line 10
            echo "
    ";
            // line 11
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["pagination"]) ? $context["pagination"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["paginate"]) {
                // line 12
                echo "
        ";
                // line 13
                if ($this->getAttribute($context["paginate"], "isCurrent", array())) {
                    // line 14
                    echo "            <li><span>";
                    echo $this->getAttribute($context["paginate"], "number", array());
                    echo "</span></li>
        ";
                } elseif ($this->getAttribute(                // line 15
$context["paginate"], "isInDelta", array())) {
                    // line 16
                    echo "            ";
                    $context["url"] = twig_replace_filter((((isset($context["base_url"]) ? $context["base_url"] : null) . $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "params", array())) . $this->getAttribute($context["paginate"], "url", array())), array("//" => "/"));
                    // line 17
                    echo "            <li><a href=\"";
                    echo (isset($context["url"]) ? $context["url"] : null);
                    echo "\">";
                    echo $this->getAttribute($context["paginate"], "number", array());
                    echo "</a></li>
        ";
                } elseif ($this->getAttribute(                // line 18
$context["paginate"], "isDeltaBorder", array())) {
                    // line 19
                    echo "            <li class=\"gap\"><span>&hellip;</span></li>
        ";
                }
                // line 21
                echo "
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['paginate'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 23
            echo "    ";
            if ($this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "hasNext", array())) {
                // line 24
                echo "        ";
                $context["url"] = twig_replace_filter((((isset($context["base_url"]) ? $context["base_url"] : null) . $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "params", array())) . $this->getAttribute((isset($context["pagination"]) ? $context["pagination"] : null), "nextUrl", array())), array("//" => "/"));
                // line 25
                echo "        <li><a rel=\"next\" href=\"";
                echo (isset($context["url"]) ? $context["url"] : null);
                echo "\">&raquo;</a></li>
    ";
            } else {
                // line 27
                echo "        <li><span>&raquo;</span></li>
    ";
            }
            // line 29
            echo "</ul>

";
        }
    }

    public function getTemplateName()
    {
        return "partials/pagination.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  98 => 29,  94 => 27,  88 => 25,  85 => 24,  82 => 23,  75 => 21,  71 => 19,  69 => 18,  62 => 17,  59 => 16,  57 => 15,  52 => 14,  50 => 13,  47 => 12,  43 => 11,  40 => 10,  36 => 8,  30 => 6,  27 => 5,  25 => 4,  21 => 2,  19 => 1,);
    }
}
/* {% if pagination|length > 1 %}*/
/* */
/* <ul class="pagination">*/
/*     {% if pagination.hasPrev %}*/
/*         {% set url =  (base_url ~ pagination.params ~ pagination.prevUrl)|replace({'//':'/'}) %}*/
/*         <li><a rel="prev" href="{{ url }}">&laquo;</a></li>*/
/*     {% else %}*/
/*         <li><span>&laquo;</span></li>*/
/*     {% endif %}*/
/* */
/*     {% for paginate in pagination %}*/
/* */
/*         {% if paginate.isCurrent %}*/
/*             <li><span>{{ paginate.number }}</span></li>*/
/*         {% elseif paginate.isInDelta %}*/
/*             {% set url =  (base_url ~ pagination.params ~ paginate.url)|replace({'//':'/'}) %}*/
/*             <li><a href="{{ url }}">{{ paginate.number }}</a></li>*/
/*         {% elseif paginate.isDeltaBorder %}*/
/*             <li class="gap"><span>&hellip;</span></li>*/
/*         {% endif %}*/
/* */
/*     {% endfor %}*/
/*     {% if pagination.hasNext %}*/
/*         {% set url =  (base_url ~ pagination.params ~ pagination.nextUrl)|replace({'//':'/'}) %}*/
/*         <li><a rel="next" href="{{ url }}">&raquo;</a></li>*/
/*     {% else %}*/
/*         <li><span>&raquo;</span></li>*/
/*     {% endif %}*/
/* </ul>*/
/* */
/* {% endif %}*/
/* */
