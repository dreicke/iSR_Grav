<?php

/* blog.html.twig */
class __TwigTemplate_4992cea23d6e3585bbe88cbcc760161118370cb24e57bd618a1a59b9b9838b5c extends Twig_Template
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
        $this->loadTemplate("blog.html.twig", "blog.html.twig", 1, "731124273")->display($context);
        // line 63
        echo "

";
    }

    public function getTemplateName()
    {
        return "blog.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  21 => 63,  19 => 1,);
    }
}


/* blog.html.twig */
class __TwigTemplate_4992cea23d6e3585bbe88cbcc760161118370cb24e57bd618a1a59b9b9838b5c_731124273 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("partials/base.html.twig", "blog.html.twig", 1);
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
        // line 3
        $context["collection"] = $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "collection", array(), "method");
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "\t\t";
        $context["blog_image"] = $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(twig_first($this->env, $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "media", array()), "images", array())), "grayscale", array(), "method"), "contrast", array(0 => 20), "method"), "brightness", array(0 =>  -100), "method"), "colorize", array(0 =>  -35, 1 => 81, 2 => 122), "method");
        // line 7
        echo "
\t\t";
        // line 8
        if ((isset($context["blog_image"]) ? $context["blog_image"] : null)) {
            // line 9
            echo "\t\t<div class=\"flush-top blog-header blog-header-image\" style=\"background-image: url(";
            echo $this->getAttribute((isset($context["blog_image"]) ? $context["blog_image"] : null), "url", array());
            echo ");\">
\t\t";
        } else {
            // line 11
            echo "\t\t<div class=\"blog-header\">
\t\t";
        }
        // line 13
        echo "\t\t\t";
        echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array());
        echo "
\t\t\t<div id=\"issuehead\">
\t\t\t<img src=\"user/pages/images/isrlogo-sq.png\" class=\"index-logo\"><h1>ISSUE ONE</h1>
\t\t\t<div id=\"categories\">
\t\t\t<h3><a href=\"#\">tech policy</a></h3>
\t\t\t<h3>&middot;</h3>
\t\t\t<h3><a href=\"#\">hci</a></h3>
\t\t\t<h3>&middot;</h3>
\t\t\t<h3><a href=\"#\">data science</a></h3>
\t\t\t</div>

\t\t\t</div>
\t\t</div>

\t\t\t";
        // line 27
        if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "breadcrumbs", array()), "enabled", array())) {
            // line 28
            echo "\t\t\t\t";
            // line 29
            echo "\t\t\t";
        }
        // line 30
        echo "
\t\t<div class=\"content-wrapper blog-content-list grid pure-g\">
\t\t\t<div id=\"listing\" class=\"block pure-u-2-3\">
\t\t\t<div id=\"techpolicy\">
\t\t\t<h3>tech policy</h3>

\t\t\t\t";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_slice($this->env, (isset($context["collection"]) ? $context["collection"] : null), 1, 2));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
            // line 37
            echo "\t\t\t        \t";
            $this->loadTemplate("partials/blog_item.html.twig", "blog.html.twig", 37)->display(array_merge($context, array("page" => $context["child"], "truncate" => true)));
            // line 38
            echo "\t\t\t    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "
                ";
        // line 40
        if (($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "pagination", array()), "enabled", array()) && $this->getAttribute($this->getAttribute((isset($context["collection"]) ? $context["collection"] : null), "params", array()), "pagination", array()))) {
            // line 41
            echo "                    ";
            $this->loadTemplate("partials/pagination.html.twig", "blog.html.twig", 41)->display(array_merge($context, array("base_url" => $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "url", array()), "pagination" => $this->getAttribute($this->getAttribute((isset($context["collection"]) ? $context["collection"] : null), "params", array()), "pagination", array()))));
            // line 42
            echo "                ";
        }
        // line 43
        echo "            </div>
            <div id=\"HCI\">
\t\t\t<h3>HCI</h3>

\t\t\t\t";
        // line 47
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_slice($this->env, (isset($context["collection"]) ? $context["collection"] : null), 0, 1));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
            // line 48
            echo "\t\t\t        \t";
            $this->loadTemplate("partials/blog_item.html.twig", "blog.html.twig", 48)->display(array_merge($context, array("page" => $context["child"], "truncate" => true)));
            // line 49
            echo "\t\t\t    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 50
        echo "
                ";
        // line 51
        if (($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "pagination", array()), "enabled", array()) && $this->getAttribute($this->getAttribute((isset($context["collection"]) ? $context["collection"] : null), "params", array()), "pagination", array()))) {
            // line 52
            echo "                    ";
            $this->loadTemplate("partials/pagination.html.twig", "blog.html.twig", 52)->display(array_merge($context, array("base_url" => $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "url", array()), "pagination" => $this->getAttribute($this->getAttribute((isset($context["collection"]) ? $context["collection"] : null), "params", array()), "pagination", array()))));
            // line 53
            echo "                ";
        }
        // line 54
        echo "            </div>
\t\t\t</div>
\t\t\t<div id=\"sidebar\" class=\"block size-1-3 pure-u-1-3\">
\t\t\t\t";
        // line 57
        $this->loadTemplate("partials/sidebar.html.twig", "blog.html.twig", 57)->display(array_merge($context, array("blog" => (isset($context["page"]) ? $context["page"] : null))));
        // line 58
        echo "\t\t\t</div>
\t\t</div>
\t";
    }

    public function getTemplateName()
    {
        return "blog.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  228 => 58,  226 => 57,  221 => 54,  218 => 53,  215 => 52,  213 => 51,  210 => 50,  196 => 49,  193 => 48,  176 => 47,  170 => 43,  167 => 42,  164 => 41,  162 => 40,  159 => 39,  145 => 38,  142 => 37,  125 => 36,  117 => 30,  114 => 29,  112 => 28,  110 => 27,  92 => 13,  88 => 11,  82 => 9,  80 => 8,  77 => 7,  74 => 6,  71 => 5,  67 => 1,  65 => 3,  51 => 1,  21 => 63,  19 => 1,);
    }
}
/* {% embed 'partials/base.html.twig' %}*/
/* */
/* 	{% set collection = page.collection() %}*/
/* */
/* 	{% block content %}*/
/* 		{% set blog_image = page.media.images|first.grayscale().contrast(20).brightness(-100).colorize(-35,81,122) %}*/
/* */
/* 		{% if blog_image %}*/
/* 		<div class="flush-top blog-header blog-header-image" style="background-image: url({{ blog_image.url }});">*/
/* 		{% else %}*/
/* 		<div class="blog-header">*/
/* 		{% endif %}*/
/* 			{{ page.content }}*/
/* 			<div id="issuehead">*/
/* 			<img src="user/pages/images/isrlogo-sq.png" class="index-logo"><h1>ISSUE ONE</h1>*/
/* 			<div id="categories">*/
/* 			<h3><a href="#">tech policy</a></h3>*/
/* 			<h3>&middot;</h3>*/
/* 			<h3><a href="#">hci</a></h3>*/
/* 			<h3>&middot;</h3>*/
/* 			<h3><a href="#">data science</a></h3>*/
/* 			</div>*/
/* */
/* 			</div>*/
/* 		</div>*/
/* */
/* 			{% if config.plugins.breadcrumbs.enabled %}*/
/* 				{# {% include 'partials/breadcrumbs.html.twig' %} #}*/
/* 			{% endif %}*/
/* */
/* 		<div class="content-wrapper blog-content-list grid pure-g">*/
/* 			<div id="listing" class="block pure-u-2-3">*/
/* 			<div id="techpolicy">*/
/* 			<h3>tech policy</h3>*/
/* */
/* 				{% for child in collection|slice (1, 2) %}*/
/* 			        	{% include 'partials/blog_item.html.twig' with {'page':child, 'truncate':true} %}*/
/* 			    {% endfor %}*/
/* */
/*                 {% if config.plugins.pagination.enabled and collection.params.pagination %}*/
/*                     {% include 'partials/pagination.html.twig' with {'base_url':page.url, 'pagination':collection.params.pagination} %}*/
/*                 {% endif %}*/
/*             </div>*/
/*             <div id="HCI">*/
/* 			<h3>HCI</h3>*/
/* */
/* 				{% for child in collection|slice (0,1) %}*/
/* 			        	{% include 'partials/blog_item.html.twig' with {'page':child, 'truncate':true} %}*/
/* 			    {% endfor %}*/
/* */
/*                 {% if config.plugins.pagination.enabled and collection.params.pagination %}*/
/*                     {% include 'partials/pagination.html.twig' with {'base_url':page.url, 'pagination':collection.params.pagination} %}*/
/*                 {% endif %}*/
/*             </div>*/
/* 			</div>*/
/* 			<div id="sidebar" class="block size-1-3 pure-u-1-3">*/
/* 				{% include 'partials/sidebar.html.twig' with {'blog':page} %}*/
/* 			</div>*/
/* 		</div>*/
/* 	{% endblock %}*/
/* */
/* {% endembed %}*/
/* */
/* */
/* */
