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
        $this->loadTemplate("blog.html.twig", "blog.html.twig", 1, "34216601")->display($context);
        // line 97
        echo "
<script>
// from http://stackoverflow.com/questions/17534661/make-anchor-link-go-some-pixels-above-where-its-linked-to
\$(document).ready(function () {
    \$('a').on('click', function (e) {
        // e.preventDefault();

        var target = this.hash,
            \$target = \$(target);

       \$('html, body').stop().animate({
        'scrollTop': \$target.offset().top-100
    }, 900, 'swing', function () {
    });

        console.log(window.location);

        return false;
    });

});
</script>


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
        return array (  21 => 97,  19 => 1,);
    }
}


/* blog.html.twig */
class __TwigTemplate_4992cea23d6e3585bbe88cbcc760161118370cb24e57bd618a1a59b9b9838b5c_34216601 extends Twig_Template
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
\t\t\t<h3><a href=\"#infoviz\">info viz</a></h3>
\t\t\t<h3 class=\"sep\">&middot;</h3>
\t\t\t<h3><a href=\"#lis\">LIS</a></h3>
\t\t\t<h3 class=\"sep\">&middot;</h3>
\t\t\t<h3><a href=\"#hci\">hci</a></h3>
\t\t\t<h3 class=\"sep\">&middot;</h3>
\t\t\t<h3><a href=\"#socio\">business & society</a></h3>
\t\t\t</div>

\t\t\t</div>
\t\t</div>




\t\t<div class=\"content-wrapper blog-content-list grid pure-g\">
\t\t\t<div id=\"listing\" class=\"block pure-u-2-3\">
\t\t\t<div id=\"infoviz\">
\t\t\t\t<h3>info viz</h3>

\t\t\t\t";
        // line 37
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["taxonomy"]) ? $context["taxonomy"] : null), "findTaxonomy", array(0 => array("tag" => "infoviz")), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 38
            echo "\t\t\t\t\t\t<div class=\"articulo\">
\t\t\t\t\t\t    <a href=\"";
            // line 39
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><img class=\"indexthumb\" src=\"";
            echo $this->getAttribute($this->getAttribute($context["post"], "header", array()), "header_image", array());
            echo "\"></a>
\t\t\t\t\t\t    <a href=\"";
            // line 40
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><h4>";
            echo $this->getAttribute($context["post"], "title", array());
            echo "</h4></a>
\t\t\t\t\t\t    <span class=\"byline\">";
            // line 41
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "name", array());
            echo "</span>
\t\t\t\t\t\t    <span class=\"by-org\">";
            // line 42
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "org", array());
            echo "</span>
\t\t\t\t\t    </div>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 45
        echo "
            </div>
            <div id=\"lis\">
\t            <h3>LIS</h3>

\t\t\t\t\t";
        // line 50
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["taxonomy"]) ? $context["taxonomy"] : null), "findTaxonomy", array(0 => array("tag" => "is")), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 51
            echo "\t\t\t\t\t\t<div class=\"articulo featured\">
\t\t\t\t\t\t    <a href=\"";
            // line 52
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><img class=\"indexthumb\" src=\"";
            echo $this->getAttribute($this->getAttribute($context["post"], "header", array()), "header_image", array());
            echo "\"></a>
\t\t\t\t\t\t    <a href=\"";
            // line 53
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><h4>";
            echo $this->getAttribute($context["post"], "title", array());
            echo "</h4></a>
\t\t\t\t\t\t    <span class=\"byline\">";
            // line 54
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "name", array());
            echo "</span>
\t\t\t\t\t\t    <span class=\"by-org\">";
            // line 55
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "org", array());
            echo "</span>
\t\t\t\t\t    </div>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 58
        echo "

            </div>
            <div id=\"hci\">
\t\t\t\t<h3>HCI</h3>

\t\t\t\t\t";
        // line 64
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["taxonomy"]) ? $context["taxonomy"] : null), "findTaxonomy", array(0 => array("tag" => "HCI")), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 65
            echo "\t\t\t\t\t\t<div class=\"articulo\">
\t\t\t\t\t\t    <a href=\"";
            // line 66
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><img class=\"indexthumb\" src=\"";
            echo $this->getAttribute($this->getAttribute($context["post"], "header", array()), "header_image", array());
            echo "\"></a>
\t\t\t\t\t\t    <a href=\"";
            // line 67
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><h4>";
            echo $this->getAttribute($context["post"], "title", array());
            echo "</h4></a>
\t\t\t\t\t\t    <span class=\"byline\">";
            // line 68
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "name", array());
            echo "</span>
\t\t\t\t\t\t    <span class=\"by-org\">";
            // line 69
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "org", array());
            echo "</span>
\t\t\t\t\t    </div>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 72
        echo "\t\t\t\t

            </div>
            <div id=\"socio\">
\t\t\t\t<h3>Business & Society</h3>

\t\t\t\t";
        // line 78
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["taxonomy"]) ? $context["taxonomy"] : null), "findTaxonomy", array(0 => array("tag" => "social issues")), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 79
            echo "\t\t\t\t\t<div class=\"articulo\">
\t\t\t\t\t    <a href=\"";
            // line 80
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><img class=\"indexthumb\" src=\"";
            echo $this->getAttribute($this->getAttribute($context["post"], "header", array()), "header_image", array());
            echo "\"></a>
\t\t\t\t\t    <a href=\"";
            // line 81
            echo $this->getAttribute($context["post"], "url", array());
            echo "\"><h4>";
            echo $this->getAttribute($context["post"], "title", array());
            echo "</h4></a>
\t\t\t\t\t    <span class=\"byline\">";
            // line 82
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "name", array());
            echo "</span>
\t\t\t\t\t    <span class=\"by-org\">";
            // line 83
            echo $this->getAttribute($this->getAttribute($this->getAttribute($context["post"], "header", array()), "author", array()), "org", array());
            echo "</span>
\t\t\t\t    </div>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 86
        echo "\t\t\t\t

            </div>
\t\t\t</div>
\t\t\t<div id=\"sidebar\" class=\"block size-1-3 pure-u-1-3\">
\t\t\t\t";
        // line 91
        $this->loadTemplate("partials/sidebar.html.twig", "blog.html.twig", 91)->display(array_merge($context, array("blog" => (isset($context["page"]) ? $context["page"] : null))));
        // line 92
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
        return array (  302 => 92,  300 => 91,  293 => 86,  284 => 83,  280 => 82,  274 => 81,  268 => 80,  265 => 79,  261 => 78,  253 => 72,  244 => 69,  240 => 68,  234 => 67,  228 => 66,  225 => 65,  221 => 64,  213 => 58,  204 => 55,  200 => 54,  194 => 53,  188 => 52,  185 => 51,  181 => 50,  174 => 45,  165 => 42,  161 => 41,  155 => 40,  149 => 39,  146 => 38,  142 => 37,  114 => 13,  110 => 11,  104 => 9,  102 => 8,  99 => 7,  96 => 6,  93 => 5,  89 => 1,  87 => 3,  73 => 1,  21 => 97,  19 => 1,);
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
/* 			<h3><a href="#infoviz">info viz</a></h3>*/
/* 			<h3 class="sep">&middot;</h3>*/
/* 			<h3><a href="#lis">LIS</a></h3>*/
/* 			<h3 class="sep">&middot;</h3>*/
/* 			<h3><a href="#hci">hci</a></h3>*/
/* 			<h3 class="sep">&middot;</h3>*/
/* 			<h3><a href="#socio">business & society</a></h3>*/
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
/* 			<div id="infoviz">*/
/* 				<h3>info viz</h3>*/
/* */
/* 				{% for post in taxonomy.findTaxonomy({'tag':'infoviz'}) %}*/
/* 						<div class="articulo">*/
/* 						    <a href="{{ post.url }}"><img class="indexthumb" src="{{ post.header.header_image }}"></a>*/
/* 						    <a href="{{ post.url }}"><h4>{{ post.title }}</h4></a>*/
/* 						    <span class="byline">{{ post.header.author.name }}</span>*/
/* 						    <span class="by-org">{{ post.header.author.org }}</span>*/
/* 					    </div>*/
/* 				{% endfor %}*/
/* */
/*             </div>*/
/*             <div id="lis">*/
/* 	            <h3>LIS</h3>*/
/* */
/* 					{% for post in taxonomy.findTaxonomy({'tag':'is'}) %}*/
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
/* 					{% for post in taxonomy.findTaxonomy({'tag':'HCI'}) %}*/
/* 						<div class="articulo">*/
/* 						    <a href="{{ post.url }}"><img class="indexthumb" src="{{ post.header.header_image }}"></a>*/
/* 						    <a href="{{ post.url }}"><h4>{{ post.title }}</h4></a>*/
/* 						    <span class="byline">{{ post.header.author.name }}</span>*/
/* 						    <span class="by-org">{{ post.header.author.org }}</span>*/
/* 					    </div>*/
/* 					{% endfor %}*/
/* 				*/
/* */
/*             </div>*/
/*             <div id="socio">*/
/* 				<h3>Business & Society</h3>*/
/* */
/* 				{% for post in taxonomy.findTaxonomy({'tag':'social issues'}) %}*/
/* 					<div class="articulo">*/
/* 					    <a href="{{ post.url }}"><img class="indexthumb" src="{{ post.header.header_image }}"></a>*/
/* 					    <a href="{{ post.url }}"><h4>{{ post.title }}</h4></a>*/
/* 					    <span class="byline">{{ post.header.author.name }}</span>*/
/* 					    <span class="by-org">{{ post.header.author.org }}</span>*/
/* 				    </div>*/
/* 				{% endfor %}*/
/* 				*/
/* */
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
/* <script>*/
/* // from http://stackoverflow.com/questions/17534661/make-anchor-link-go-some-pixels-above-where-its-linked-to*/
/* $(document).ready(function () {*/
/*     $('a').on('click', function (e) {*/
/*         // e.preventDefault();*/
/* */
/*         var target = this.hash,*/
/*             $target = $(target);*/
/* */
/*        $('html, body').stop().animate({*/
/*         'scrollTop': $target.offset().top-100*/
/*     }, 900, 'swing', function () {*/
/*     });*/
/* */
/*         console.log(window.location);*/
/* */
/*         return false;*/
/*     });*/
/* */
/* });*/
/* </script>*/
/* */
/* */
/* */
