<?php

/* partials/items.html.twig */
class __TwigTemplate_65c1800b290109d8e6127d01bb771c8fcd8b804bbe652f08dbdc969b535d8c73 extends Twig_Template
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
        echo "<h1>
    ";
        // line 2
        echo (($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "data-manager", array(), "array"), "types", array()), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "list", array()), "title", array())) ? ($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "data-manager", array(), "array"), "types", array()), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "list", array()), "title", array())) : (((twig_capitalize_string_filter($this->env, twig_escape_filter($this->env, (isset($context["type"]) ? $context["type"] : null))) . " ") . $this->env->getExtension('AdminTwigExtension')->tuFilter(twig_escape_filter($this->env, "PLUGIN_DATA_MANAGER.ITEMS_LIST")))));
        echo "
</h1>
<div class=\"row\">
    ";
        // line 5
        if ($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "data-manager", array(), "array"), "types", array()), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "list", array()), "columns", array())) {
            // line 6
            echo "        <table>
            <thead>
                <tr>
                    ";
            // line 9
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "data-manager", array(), "array"), "types", array()), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "list", array()), "columns", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                // line 10
                echo "                        <th>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["column"], "label", array()));
                echo "</th>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 12
            echo "                </tr>
            </thead>
            <tbody>
                ";
            // line 15
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["grav"]) ? $context["grav"] : null), "twig", array()), "items", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 16
                echo "                    <tr>
                        ";
                // line 17
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "data-manager", array(), "array"), "types", array()), (isset($context["type"]) ? $context["type"] : null), array(), "array"), "list", array()), "columns", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                    // line 18
                    echo "                            <td>
                                <a href=\"";
                    // line 19
                    echo (isset($context["type"]) ? $context["type"] : null);
                    echo "/";
                    echo $this->getAttribute($context["item"], "route", array());
                    echo "\">
                                    ";
                    // line 20
                    if (twig_test_iterable($this->getAttribute($context["column"], "field", array()))) {
                        // line 21
                        echo "                                        ";
                        $context["value"] = $this->getAttribute($context["item"], "content", array());
                        // line 22
                        echo "                                        ";
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["column"], "field", array()));
                        foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
                            // line 23
                            echo "                                            ";
                            $context["value"] = $this->getAttribute((isset($context["value"]) ? $context["value"] : null), $context["field"], array(), "array");
                            // line 24
                            echo "                                        ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 25
                        echo "                                        ";
                        echo twig_escape_filter($this->env, (isset($context["value"]) ? $context["value"] : null));
                        echo "
                                    ";
                    } else {
                        // line 27
                        echo "                                        ";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["item"], "content", array()), $this->getAttribute($context["column"], "field", array()), array(), "array"));
                        echo "
                                    ";
                    }
                    // line 29
                    echo "                                </a>
                            </td>
                        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 32
                echo "                    </tr>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 34
            echo "            </tbody>
        </table>
    ";
        } else {
            // line 37
            echo "        <ul class=\"pages-list depth-0\">
           ";
            // line 38
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["grav"]) ? $context["grav"] : null), "twig", array()), "items", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 39
                echo "               <li class=\"page-item\">
                   <div class=\"row\">
                       <a href=\"";
                // line 41
                echo (isset($context["type"]) ? $context["type"] : null);
                echo "/";
                echo $this->getAttribute($context["item"], "route", array());
                echo "\" class=\"page-edit\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "route", array()));
                echo "</a>
                   </div>
               </li>
           ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 45
            echo "        </ul>
    ";
        }
        // line 47
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "partials/items.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  153 => 47,  149 => 45,  135 => 41,  131 => 39,  127 => 38,  124 => 37,  119 => 34,  112 => 32,  104 => 29,  98 => 27,  92 => 25,  86 => 24,  83 => 23,  78 => 22,  75 => 21,  73 => 20,  67 => 19,  64 => 18,  60 => 17,  57 => 16,  53 => 15,  48 => 12,  39 => 10,  35 => 9,  30 => 6,  28 => 5,  22 => 2,  19 => 1,);
    }
}
/* <h1>*/
/*     {{ config.plugins['data-manager'].types[type].list.title ?: type|e|capitalize ~ " " ~ "PLUGIN_DATA_MANAGER.ITEMS_LIST"|e|tu }}*/
/* </h1>*/
/* <div class="row">*/
/*     {% if config.plugins['data-manager'].types[type].list.columns %}*/
/*         <table>*/
/*             <thead>*/
/*                 <tr>*/
/*                     {% for column in config.plugins['data-manager'].types[type].list.columns %}*/
/*                         <th>{{ column.label|e }}</th>*/
/*                     {% endfor %}*/
/*                 </tr>*/
/*             </thead>*/
/*             <tbody>*/
/*                 {% for item in grav.twig.items %}*/
/*                     <tr>*/
/*                         {% for column in config.plugins['data-manager'].types[type].list.columns %}*/
/*                             <td>*/
/*                                 <a href="{{ type }}/{{ item.route }}">*/
/*                                     {% if column.field is iterable %}*/
/*                                         {% set value = item.content %}*/
/*                                         {% for field in column.field %}*/
/*                                             {% set value = value[field] %}*/
/*                                         {% endfor %}*/
/*                                         {{ value|e }}*/
/*                                     {% else %}*/
/*                                         {{ item.content[column.field]|e }}*/
/*                                     {% endif %}*/
/*                                 </a>*/
/*                             </td>*/
/*                         {% endfor %}*/
/*                     </tr>*/
/*                 {% endfor %}*/
/*             </tbody>*/
/*         </table>*/
/*     {% else %}*/
/*         <ul class="pages-list depth-0">*/
/*            {% for item in grav.twig.items %}*/
/*                <li class="page-item">*/
/*                    <div class="row">*/
/*                        <a href="{{ type }}/{{ item.route }}" class="page-edit">{{ item.route|e }}</a>*/
/*                    </div>*/
/*                </li>*/
/*            {% endfor %}*/
/*         </ul>*/
/*     {% endif %}*/
/* </div>*/
/* */
