<?php

/* partials/types.html.twig */
class __TwigTemplate_528084e6df14046524fdd9dedcdfe8925d6715a7492c67b34cf621859bd55a3b extends Twig_Template
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
        echo "<div class=\"gpm gpm-themes\">
    <h1>
        ";
        // line 3
        echo $this->env->getExtension('AdminTwigExtension')->tuFilter(twig_escape_filter($this->env, "PLUGIN_DATA_MANAGER.DATA_TYPES"));
        echo "
    </h1>
    <div class=\"gpm gpm-themes themes card-row grid fixed-blocks pure-g\">
        ";
        // line 6
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["grav"]) ? $context["grav"] : null), "twig", array()), "types", array()));
        foreach ($context['_seq'] as $context["type"] => $context["count"]) {
            // line 7
            echo "            <a href=\"data-manager/";
            echo $context["type"];
            echo "\" class=\"pure-u-1-3 card-item\">
                <div class=\"theme\">
                    <div class=\"gpm-name\">
                        ";
            // line 10
            echo ((twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "data-manager", array(), "array"), "types", array()), $context["type"], array(), "array"), "name", array()))) ? (twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "data-manager", array(), "array"), "types", array()), $context["type"], array(), "array"), "name", array()))) : (twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $context["type"]))));
            echo "
                    </div>
                    <p>";
            // line 12
            echo $context["count"];
            echo " ";
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter(twig_escape_filter($this->env, "PLUGIN_DATA_MANAGER.ITEMS"));
            echo "</p>
                </div>
            </a>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['type'], $context['count'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 16
        echo "    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "partials/types.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 16,  45 => 12,  40 => 10,  33 => 7,  29 => 6,  23 => 3,  19 => 1,);
    }
}
/* <div class="gpm gpm-themes">*/
/*     <h1>*/
/*         {{"PLUGIN_DATA_MANAGER.DATA_TYPES"|e|tu}}*/
/*     </h1>*/
/*     <div class="gpm gpm-themes themes card-row grid fixed-blocks pure-g">*/
/*         {% for type, count in grav.twig.types %}*/
/*             <a href="data-manager/{{ type }}" class="pure-u-1-3 card-item">*/
/*                 <div class="theme">*/
/*                     <div class="gpm-name">*/
/*                         {{ config.plugins['data-manager'].types[type].name|e ?: type|capitalize|e }}*/
/*                     </div>*/
/*                     <p>{{ count }} {{"PLUGIN_DATA_MANAGER.ITEMS"|e|tu}}</p>*/
/*                 </div>*/
/*             </a>*/
/*         {% endfor %}*/
/*     </div>*/
/* </div>*/
/* */
