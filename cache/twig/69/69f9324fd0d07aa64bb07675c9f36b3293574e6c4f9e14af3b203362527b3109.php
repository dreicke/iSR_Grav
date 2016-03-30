<?php

/* partials/blog_item.html.twig */
class __TwigTemplate_e92e96e45507a22d0b07ad0d3f7aebe60702250f6662fcdeec33e9859315b9d9 extends Twig_Template
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
        echo "<div class=\"list-item\">

    ";
        // line 3
        $context["header_image"] = $this->env->getExtension('GravTwigExtension')->definedDefaultFilter($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "header_image", array()), true);
        // line 4
        echo "    ";
        $context["header_image_width"] = $this->env->getExtension('GravTwigExtension')->definedDefaultFilter($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "header_image_width", array()), 400);
        // line 5
        echo "    ";
        $context["header_image_height"] = $this->env->getExtension('GravTwigExtension')->definedDefaultFilter($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "header_image_height", array()), 300);
        // line 6
        echo "    ";
        $context["header_image_file"] = $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "header_image_file", array());
        // line 7
        echo "
    <div class=\"list-blog-header\">

        ";
        // line 10
        if ((isset($context["header_image"]) ? $context["header_image"] : null)) {
            // line 11
            echo "            ";
            if ((isset($context["header_image_file"]) ? $context["header_image_file"] : null)) {
                // line 12
                echo "                ";
                $context["header_image_media"] = $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "media", array()), "images", array()), (isset($context["header_image_file"]) ? $context["header_image_file"] : null), array(), "array");
                // line 13
                echo "            ";
            } else {
                // line 14
                echo "                ";
                $context["header_image_media"] = twig_first($this->env, $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "media", array()), "images", array()));
                // line 15
                echo "            ";
            }
            // line 16
            echo "            ";
            echo $this->getAttribute($this->getAttribute((isset($context["header_image_media"]) ? $context["header_image_media"] : null), "cropZoom", array(0 => (isset($context["header_image_width"]) ? $context["header_image_width"] : null), 1 => (isset($context["header_image_height"]) ? $context["header_image_height"] : null)), "method"), "html", array());
            echo "
        ";
        }
        // line 18
        echo "
        ";
        // line 19
        if ($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "link", array())) {
            // line 20
            echo "            <h1>
                ";
            // line 21
            if ( !($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "continue_link", array()) === false)) {
                // line 22
                echo "                <a href=\"";
                echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "url", array());
                echo "\"><i class=\"fa fa-angle-double-right\"></i></a>
                ";
            }
            // line 24
            echo "                <a href=\"";
            echo $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "link", array());
            echo "\">";
            echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "title", array());
            echo "</a>
            </h1>
        ";
        } else {
            // line 27
            echo "            <h4><a href=\"";
            echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "url", array());
            echo "\">";
            echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "title", array());
            echo "</a></h4>
        ";
        }
        // line 29
        echo "        
        <div id=\"author-info\">
        ";
        // line 31
        if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "author", array()), "name", array())) {
            // line 32
            echo "                <span class=\"byline\"> BY: ";
            echo $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "author", array()), "name", array());
            echo "</span>
        ";
        }
        // line 34
        echo "
        ";
        // line 35
        if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "author", array()), "org", array())) {
            // line 36
            echo "                <span class=\"by-org\">";
            echo $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "author", array()), "org", array());
            echo "</span>
        ";
        }
        // line 38
        echo "        </div>

        ";
        // line 40
        if ($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "taxonomy", array()), "tag", array())) {
            // line 41
            echo "        <span class=\"tags\">
            ";
            // line 42
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "taxonomy", array()), "tag", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                // line 43
                echo "            <a href=\"";
                echo $this->env->getExtension('GravTwigExtension')->rtrimFilter($this->getAttribute((isset($context["blog"]) ? $context["blog"] : null), "url", array()), "/");
                echo "/tag";
                echo $this->getAttribute($this->getAttribute((isset($context["config"]) ? $context["config"] : null), "system", array()), "param_sep", array());
                echo $context["tag"];
                echo "\">";
                echo $context["tag"];
                echo "</a>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 45
            echo "        </span>
        ";
        }
        // line 47
        echo "        
        


    </div>

    <div class=\"list-blog-padding\">



    ";
        // line 57
        if (($this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "continue_link", array()) === false)) {
            // line 58
            echo "        ";
            echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array());
            echo "
        ";
            // line 59
            if ( !(isset($context["truncate"]) ? $context["truncate"] : null)) {
                // line 60
                echo "        ";
                $context["show_prev_next"] = true;
                // line 61
                echo "        ";
            }
            // line 62
            echo "    ";
        } elseif (((isset($context["truncate"]) ? $context["truncate"] : null) && ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "summary", array()) != $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array())))) {
            // line 63
            echo "        <p>";
            echo "</p>
    ";
        } elseif (        // line 64
(isset($context["truncate"]) ? $context["truncate"] : null)) {
            // line 65
            echo "        ";
            if (($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "summary", array()) != $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array()))) {
                // line 66
                echo "            ";
                echo call_user_func_array($this->env->getFilter('truncate')->getCallable(), array($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array()), 550));
                echo "
        ";
            } else {
                // line 68
                echo "            ";
                echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array());
                echo "
        ";
            }
            // line 70
            echo "        <p><a href=\"";
            echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "url", array());
            echo "\">Continue Reading...</a></p>
    ";
        } else {
            // line 72
            echo "        ";
            echo $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array());
            echo "
        ";
            // line 73
            $context["show_prev_next"] = true;
            // line 74
            echo "    ";
        }
        // line 75
        echo "


    ";
        // line 78
        if ((isset($context["show_prev_next"]) ? $context["show_prev_next"] : null)) {
            // line 79
            echo "
        <p class=\"prev-next\">
            ";
            // line 81
            if ( !$this->getAttribute((isset($context["page"]) ? $context["page"] : null), "isFirst", array())) {
                // line 82
                echo "                <a class=\"button\" href=\"";
                echo $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "nextSibling", array()), "url", array());
                echo "\"><i class=\"fa fa-chevron-left\"></i> Next Post</a>
            ";
            }
            // line 84
            echo "
            ";
            // line 85
            if ( !$this->getAttribute((isset($context["page"]) ? $context["page"] : null), "isLast", array())) {
                // line 86
                echo "                <a class=\"button\" href=\"";
                echo $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "prevSibling", array()), "url", array());
                echo "\">Previous Post <i class=\"fa fa-chevron-right\"></i></a>
            ";
            }
            // line 88
            echo "        </p>
    ";
        }
        // line 90
        echo "
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "partials/blog_item.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  250 => 90,  246 => 88,  240 => 86,  238 => 85,  235 => 84,  229 => 82,  227 => 81,  223 => 79,  221 => 78,  216 => 75,  213 => 74,  211 => 73,  206 => 72,  200 => 70,  194 => 68,  188 => 66,  185 => 65,  183 => 64,  179 => 63,  176 => 62,  173 => 61,  170 => 60,  168 => 59,  163 => 58,  161 => 57,  149 => 47,  145 => 45,  131 => 43,  127 => 42,  124 => 41,  122 => 40,  118 => 38,  112 => 36,  110 => 35,  107 => 34,  101 => 32,  99 => 31,  95 => 29,  87 => 27,  78 => 24,  72 => 22,  70 => 21,  67 => 20,  65 => 19,  62 => 18,  56 => 16,  53 => 15,  50 => 14,  47 => 13,  44 => 12,  41 => 11,  39 => 10,  34 => 7,  31 => 6,  28 => 5,  25 => 4,  23 => 3,  19 => 1,);
    }
}
/* <div class="list-item">*/
/* */
/*     {% set header_image = page.header.header_image|defined(true) %}*/
/*     {% set header_image_width  = page.header.header_image_width|defined(400) %}*/
/*     {% set header_image_height = page.header.header_image_height|defined(300) %}*/
/*     {% set header_image_file = page.header.header_image_file %}*/
/* */
/*     <div class="list-blog-header">*/
/* */
/*         {% if header_image %}*/
/*             {% if header_image_file %}*/
/*                 {% set header_image_media = page.media.images[header_image_file] %}*/
/*             {% else %}*/
/*                 {% set header_image_media = page.media.images|first %}*/
/*             {% endif %}*/
/*             {{ header_image_media.cropZoom(header_image_width, header_image_height).html }}*/
/*         {% endif %}*/
/* */
/*         {% if page.header.link %}*/
/*             <h1>*/
/*                 {% if page.header.continue_link is not sameas(false) %}*/
/*                 <a href="{{ page.url }}"><i class="fa fa-angle-double-right"></i></a>*/
/*                 {% endif %}*/
/*                 <a href="{{ page.header.link }}">{{ page.title }}</a>*/
/*             </h1>*/
/*         {% else %}*/
/*             <h4><a href="{{ page.url }}">{{ page.title }}</a></h4>*/
/*         {% endif %}*/
/*         */
/*         <div id="author-info">*/
/*         {% if page.header.author.name %}*/
/*                 <span class="byline"> BY: {{ page.header.author.name }}</span>*/
/*         {% endif %}*/
/* */
/*         {% if page.header.author.org %}*/
/*                 <span class="by-org">{{ page.header.author.org }}</span>*/
/*         {% endif %}*/
/*         </div>*/
/* */
/*         {% if page.taxonomy.tag %}*/
/*         <span class="tags">*/
/*             {% for tag in page.taxonomy.tag %}*/
/*             <a href="{{ blog.url|rtrim('/') }}/tag{{ config.system.param_sep }}{{ tag }}">{{ tag }}</a>*/
/*             {% endfor %}*/
/*         </span>*/
/*         {% endif %}*/
/*         */
/*         */
/* */
/* */
/*     </div>*/
/* */
/*     <div class="list-blog-padding">*/
/* */
/* */
/* */
/*     {% if page.header.continue_link is sameas(false) %}*/
/*         {{ page.content }}*/
/*         {% if not truncate %}*/
/*         {% set show_prev_next = true %}*/
/*         {% endif %}*/
/*     {% elseif truncate and page.summary != page.content %}*/
/*         <p>{# <a href="{{ page.url }}">Continue Reading...</a> #}</p>*/
/*     {% elseif truncate %}*/
/*         {% if page.summary != page.content %}*/
/*             {{ page.content|truncate(550) }}*/
/*         {% else %}*/
/*             {{ page.content }}*/
/*         {% endif %}*/
/*         <p><a href="{{ page.url }}">Continue Reading...</a></p>*/
/*     {% else %}*/
/*         {{ page.content }}*/
/*         {% set show_prev_next = true %}*/
/*     {% endif %}*/
/* */
/* */
/* */
/*     {% if show_prev_next %}*/
/* */
/*         <p class="prev-next">*/
/*             {% if not page.isFirst %}*/
/*                 <a class="button" href="{{ page.nextSibling.url }}"><i class="fa fa-chevron-left"></i> Next Post</a>*/
/*             {% endif %}*/
/* */
/*             {% if not page.isLast %}*/
/*                 <a class="button" href="{{ page.prevSibling.url }}">Previous Post <i class="fa fa-chevron-right"></i></a>*/
/*             {% endif %}*/
/*         </p>*/
/*     {% endif %}*/
/* */
/*     </div>*/
/* </div>*/
