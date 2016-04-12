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
        $this->loadTemplate("blog.html.twig", "blog.html.twig", 1, "935546601")->display($context);
        // line 84
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
        return array (  21 => 84,  19 => 1,);
    }
}


/* blog.html.twig */
class __TwigTemplate_4992cea23d6e3585bbe88cbcc760161118370cb24e57bd618a1a59b9b9838b5c_935546601 extends Twig_Template
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




\t\t<div class=\"content-wrapper blog-content-list grid pure-g\">
\t\t\t<div id=\"listing\" class=\"block pure-u-2-3\">
\t\t\t<div id=\"techpolicy\">
\t\t\t<h3>tech policy</h3>

\t\t\t";
        // line 35
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["taxonomy"]) ? $context["taxonomy"] : null), "findTaxonomy", array(0 => array("tag" => "tech policy")), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 36
            echo "\t\t\t\t\t<div class=\"articulo\">
\t\t\t\t\t    <a href=\"";
            // line 37
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><img class=\"indexthumb\" src=\"";
            echo $this->getAttribute($this->getAttribute($context["post"], "header", array()), "header_image", array());
            echo "\"></a>
\t\t\t\t\t    <a href=\"";
            // line 38
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><h4>";
            echo $this->getAttribute($context["post"], "title", array());
            echo "</h4></a>
\t\t\t\t\t    <span class=\"byline\">";
            // line 39
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "name", array());
            echo "</span>
\t\t\t\t\t    <span class=\"by-org\">";
            // line 40
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "org", array());
            echo "</span>
\t\t\t\t    </div>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 43
        echo "
            </div>
            <div id=\"infoviz\">
\t            <h3>Information Visualization</h3>

\t\t\t\t\t";
        // line 48
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["taxonomy"]) ? $context["taxonomy"] : null), "findTaxonomy", array(0 => array("tag" => "infoviz")), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 49
            echo "\t\t\t\t\t\t<div class=\"articulo featured\">
\t\t\t\t\t\t    <a href=\"";
            // line 50
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><img class=\"indexthumb\" src=\"";
            echo $this->getAttribute($this->getAttribute($context["post"], "header", array()), "header_image", array());
            echo "\"></a>
\t\t\t\t\t\t    <a href=\"";
            // line 51
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><h4>";
            echo $this->getAttribute($context["post"], "title", array());
            echo "</h4></a>
\t\t\t\t\t\t    <span class=\"byline\">";
            // line 52
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "name", array());
            echo "</span>
\t\t\t\t\t\t    <span class=\"by-org\">";
            // line 53
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "org", array());
            echo "</span>
\t\t\t\t\t    </div>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 56
        echo "

            </div>
            <div id=\"hci\">
\t\t\t\t<h3>HCI</h3>

\t\t\t\t";
        // line 62
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["taxonomy"]) ? $context["taxonomy"] : null), "findTaxonomy", array(0 => array("tag" => "HCI")), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 63
            echo "\t\t\t\t\t<div class=\"articulo\">
\t\t\t\t\t    <a href=\"";
            // line 64
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><img class=\"indexthumb\" src=\"";
            echo $this->getAttribute($this->getAttribute($context["post"], "header", array()), "header_image", array());
            echo "\"></a>
\t\t\t\t\t    <a href=\"";
            // line 65
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><h4>";
            echo $this->getAttribute($context["post"], "title", array());
            echo "</h4></a>
\t\t\t\t\t    <span class=\"byline\">";
            // line 66
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "name", array());
            echo "</span>
\t\t\t\t\t    <span class=\"by-org\">";
            // line 67
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "org", array());
            echo "</span>
\t\t\t\t    </div>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 70
        echo "\t\t\t\t

                ";
        // line 72
        if (($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "plugins", array()), "pagination", array()), "enabled", array()) && $this->getAttribute($this->getAttribute((isset($context["collection"]) ? $context["collection"] : null), "params", array()), "pagination", array()))) {
            // line 73
            echo "                    ";
            $this->loadTemplate("partials/pagination.html.twig", "blog.html.twig", 73)->display(array_merge($context, array("base_url" => $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "url", array()), "pagination" => $this->getAttribute($this->getAttribute((isset($context["collection"]) ? $context["collection"] : null), "params", array()), "pagination", array()))));
            // line 74
            echo "                ";
        }
        // line 75
        echo "            </div>
\t\t\t</div>
\t\t\t<div id=\"sidebar\" class=\"block size-1-3 pure-u-1-3\">
\t\t\t\t";
        // line 78
        $this->loadTemplate("partials/sidebar.html.twig", "blog.html.twig", 78)->display(array_merge($context, array("blog" => (isset($context["page"]) ? $context["page"] : null))));
        // line 79
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
        return array (  248 => 79,  246 => 78,  241 => 75,  238 => 74,  235 => 73,  233 => 72,  229 => 70,  220 => 67,  216 => 66,  210 => 65,  204 => 64,  201 => 63,  197 => 62,  189 => 56,  180 => 53,  176 => 52,  170 => 51,  164 => 50,  161 => 49,  157 => 48,  150 => 43,  141 => 40,  137 => 39,  131 => 38,  125 => 37,  122 => 36,  118 => 35,  92 => 13,  88 => 11,  82 => 9,  80 => 8,  77 => 7,  74 => 6,  71 => 5,  67 => 1,  65 => 3,  51 => 1,  21 => 84,  19 => 1,);
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
/* */
/* */
/* */
/* 		<div class="content-wrapper blog-content-list grid pure-g">*/
/* 			<div id="listing" class="block pure-u-2-3">*/
/* 			<div id="techpolicy">*/
/* 			<h3>tech policy</h3>*/
/* */
/* 			{% for post in taxonomy.findTaxonomy({'tag':'tech policy'}) %}*/
/* 					<div class="articulo">*/
/* 					    <a href="{{ post.url }}"><img class="indexthumb" src="{{ post.header.header_image }}"></a>*/
/* 					    <a href="{{ post.url }}"><h4>{{ post.title }}</h4></a>*/
/* 					    <span class="byline">{{ post.header.author.name }}</span>*/
/* 					    <span class="by-org">{{ post.header.author.org }}</span>*/
/* 				    </div>*/
/* 			{% endfor %}*/
/* */
/*             </div>*/
/*             <div id="infoviz">*/
/* 	            <h3>Information Visualization</h3>*/
/* */
/* 					{% for post in taxonomy.findTaxonomy({'tag':'infoviz'}) %}*/
/* 						<div class="articulo featured">*/
/* 						    <a href="{{ post.url }}"><img class="indexthumb" src="{{ post.header.header_image }}"></a>*/
/* 						    <a href="{{ post.url }}"><h4>{{ post.title }}</h4></a>*/
/* 						    <span class="byline">{{ post.header.author.name }}</span>*/
/* 						    <span class="by-org">{{ post.header.author.org }}</span>*/
/* 					    </div>*/
/* 					{% endfor %}*/
/* */
/* */
/*             </div>*/
/*             <div id="hci">*/
/* 				<h3>HCI</h3>*/
/* */
/* 				{% for post in taxonomy.findTaxonomy({'tag':'HCI'}) %}*/
/* 					<div class="articulo">*/
/* 					    <a href="{{ post.url }}"><img class="indexthumb" src="{{ post.header.header_image }}"></a>*/
/* 					    <a href="{{ post.url }}"><h4>{{ post.title }}</h4></a>*/
/* 					    <span class="byline">{{ post.header.author.name }}</span>*/
/* 					    <span class="by-org">{{ post.header.author.org }}</span>*/
/* 				    </div>*/
/* 				{% endfor %}*/
/* 				*/
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
