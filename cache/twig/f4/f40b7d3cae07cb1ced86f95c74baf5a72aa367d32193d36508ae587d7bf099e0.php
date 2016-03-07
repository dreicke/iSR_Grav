<?php

/* data-manager.html.twig */
class __TwigTemplate_86d56bd6080b9d3c5c838dacdb09b1f7a0dc8988a1c5759adae6de5d3a05f791 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("partials/base.html.twig", "data-manager.html.twig", 1);
        $this->blocks = array(
            'titlebar' => array($this, 'block_titlebar'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "partials/base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        $context["mode"] = "types";
        // line 4
        if ((($this->getAttribute($this->getAttribute((isset($context["uri"]) ? $context["uri"] : null), "paths", array()), 1, array(), "array") == "data-manager") && $this->getAttribute($this->getAttribute((isset($context["uri"]) ? $context["uri"] : null), "paths", array()), 2, array(), "array"))) {
            // line 5
            $context["mode"] = "items";
            // line 6
            $context["type"] = $this->getAttribute($this->getAttribute((isset($context["uri"]) ? $context["uri"] : null), "paths", array()), 2, array(), "array");
            // line 8
            if ($this->getAttribute($this->getAttribute((isset($context["uri"]) ? $context["uri"] : null), "paths", array()), 3, array(), "array")) {
                // line 9
                $context["mode"] = "item";
                // line 10
                $context["item"] = $this->getAttribute($this->getAttribute((isset($context["uri"]) ? $context["uri"] : null), "paths", array()), 3, array(), "array");
            }
        }
        // line 15
        if ($this->getAttribute((isset($context["admin"]) ? $context["admin"] : null), "route", array())) {
            // line 16
            $context["context"] = $this->getAttribute((isset($context["admin"]) ? $context["admin"] : null), "page", array(0 => true), "method");
        }
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 19
    public function block_titlebar($context, array $blocks = array())
    {
        // line 20
        echo "    ";
        if (((isset($context["mode"]) ? $context["mode"] : null) == "types")) {
            // line 21
            echo "        <div class=\"button-bar\">
            <a class=\"button\" href=\"";
            // line 22
            echo (isset($context["base_url"]) ? $context["base_url"] : null);
            echo "\"><i class=\"fa fa-reply\"></i> ";
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter("PLUGIN_ADMIN.BACK");
            echo "</a>
        </div>
        <h1><i class=\"fa fa-fw fa-file-text-o\"></i> ";
            // line 24
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter("PLUGIN_DATA_MANAGER.DATA_TYPES");
            echo "</h1>
    ";
        } elseif ((        // line 25
(isset($context["mode"]) ? $context["mode"] : null) == "items")) {
            // line 26
            echo "        <div class=\"button-bar\">
            <a class=\"button\" href=\"";
            // line 27
            echo (isset($context["base_url"]) ? $context["base_url"] : null);
            echo "/data-manager\"><i class=\"fa fa-reply\"></i> ";
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter("PLUGIN_ADMIN.BACK");
            echo "</a>
        </div>
        <h1><i class=\"fa fa-fw fa-file-text-o\"></i> ";
            // line 29
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter("PLUGIN_DATA_MANAGER.ITEMS_LIST");
            echo "</h1>
    ";
        } elseif ((        // line 30
(isset($context["mode"]) ? $context["mode"] : null) == "item")) {
            // line 31
            echo "        <div class=\"button-bar\">
            <a class=\"button\" href=\"";
            // line 32
            echo (isset($context["base_url"]) ? $context["base_url"] : null);
            echo "/data-manager/";
            echo (isset($context["type"]) ? $context["type"] : null);
            echo "\"><i class=\"fa fa-reply\"></i> ";
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter("PLUGIN_ADMIN.BACK");
            echo "</a>
        </div>
        <h1><i class=\"fa fa-fw fa-file-text-o\"></i> ";
            // line 34
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter("PLUGIN_DATA_MANAGER.ITEM_DETAILS");
            echo "</h1>
    ";
        }
    }

    // line 38
    public function block_content($context, array $blocks = array())
    {
        // line 39
        echo "
    <style>
    .page-data ul { padding-left: 60px; display: block; list-style-type: square; }
    .page-data ul li .row { padding-left: 0px }
    </style>

    <div class=\"page-data\">
        ";
        // line 46
        if (((isset($context["mode"]) ? $context["mode"] : null) == "types")) {
            // line 47
            echo "            ";
            $this->loadTemplate("partials/types.html.twig", "data-manager.html.twig", 47)->display($context);
            // line 48
            echo "        ";
        }
        // line 49
        echo "
        ";
        // line 50
        if (((isset($context["mode"]) ? $context["mode"] : null) == "items")) {
            // line 51
            echo "            ";
            $this->loadTemplate(array(0 => (("partials/data-manager/" . (isset($context["type"]) ? $context["type"] : null)) . "/items.html.twig"), 1 => "partials/items.html.twig"), "data-manager.html.twig", 51)->display($context);
            // line 52
            echo "        ";
        }
        // line 53
        echo "
        ";
        // line 54
        if (((isset($context["mode"]) ? $context["mode"] : null) == "item")) {
            // line 55
            echo "            ";
            $this->loadTemplate(array(0 => (("partials/data-manager/" . (isset($context["type"]) ? $context["type"] : null)) . "/item.html.twig"), 1 => "partials/item.html.twig"), "data-manager.html.twig", 55)->display($context);
            // line 56
            echo "        ";
        }
        // line 57
        echo "    </div>
";
    }

    public function getTemplateName()
    {
        return "data-manager.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 57,  147 => 56,  144 => 55,  142 => 54,  139 => 53,  136 => 52,  133 => 51,  131 => 50,  128 => 49,  125 => 48,  122 => 47,  120 => 46,  111 => 39,  108 => 38,  101 => 34,  92 => 32,  89 => 31,  87 => 30,  83 => 29,  76 => 27,  73 => 26,  71 => 25,  67 => 24,  60 => 22,  57 => 21,  54 => 20,  51 => 19,  47 => 1,  44 => 16,  42 => 15,  38 => 10,  36 => 9,  34 => 8,  32 => 6,  30 => 5,  28 => 4,  26 => 2,  11 => 1,);
    }
}
/* {% extends 'partials/base.html.twig' %}*/
/* {% set mode = 'types' %}*/
/* */
/* {% if uri.paths[1] == 'data-manager' and uri.paths[2] %}*/
/*     {% set mode = 'items' %}*/
/*     {% set type = uri.paths[2] %}*/
/* */
/*     {% if uri.paths[3] %}*/
/*         {% set mode = 'item' %}*/
/*         {% set item = uri.paths[3] %}*/
/*     {% endif %}*/
/* */
/* {% endif %}*/
/* */
/* {% if admin.route %}*/
/*     {% set context = admin.page(true) %}*/
/* {% endif %}*/
/* */
/* {% block titlebar %}*/
/*     {% if mode == 'types' %}*/
/*         <div class="button-bar">*/
/*             <a class="button" href="{{ base_url }}"><i class="fa fa-reply"></i> {{ "PLUGIN_ADMIN.BACK"|tu }}</a>*/
/*         </div>*/
/*         <h1><i class="fa fa-fw fa-file-text-o"></i> {{ "PLUGIN_DATA_MANAGER.DATA_TYPES"|tu }}</h1>*/
/*     {% elseif mode == 'items' %}*/
/*         <div class="button-bar">*/
/*             <a class="button" href="{{ base_url }}/data-manager"><i class="fa fa-reply"></i> {{ "PLUGIN_ADMIN.BACK"|tu }}</a>*/
/*         </div>*/
/*         <h1><i class="fa fa-fw fa-file-text-o"></i> {{ "PLUGIN_DATA_MANAGER.ITEMS_LIST"|tu }}</h1>*/
/*     {% elseif mode == 'item' %}*/
/*         <div class="button-bar">*/
/*             <a class="button" href="{{ base_url }}/data-manager/{{ type }}"><i class="fa fa-reply"></i> {{ "PLUGIN_ADMIN.BACK"|tu }}</a>*/
/*         </div>*/
/*         <h1><i class="fa fa-fw fa-file-text-o"></i> {{ "PLUGIN_DATA_MANAGER.ITEM_DETAILS"|tu }}</h1>*/
/*     {% endif %}*/
/* {% endblock %}*/
/* */
/* {% block content %}*/
/* */
/*     <style>*/
/*     .page-data ul { padding-left: 60px; display: block; list-style-type: square; }*/
/*     .page-data ul li .row { padding-left: 0px }*/
/*     </style>*/
/* */
/*     <div class="page-data">*/
/*         {% if mode == 'types' %}*/
/*             {% include "partials/types.html.twig" %}*/
/*         {% endif %}*/
/* */
/*         {% if mode == 'items' %}*/
/*             {% include ['partials/data-manager/' ~ type ~ '/items.html.twig', 'partials/items.html.twig'] %}*/
/*         {% endif %}*/
/* */
/*         {% if mode == 'item' %}*/
/*             {% include ['partials/data-manager/' ~ type ~ '/item.html.twig', 'partials/item.html.twig'] %}*/
/*         {% endif %}*/
/*     </div>*/
/* {% endblock %}*/
/* */
