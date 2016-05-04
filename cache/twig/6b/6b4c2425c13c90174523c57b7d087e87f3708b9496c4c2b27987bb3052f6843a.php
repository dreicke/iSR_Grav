<?php

/* partials/sidebar.html.twig */
class __TwigTemplate_61ab36261361ad1e4e5dfc1407617483b3cc7f72accbf33ff6c838fda49b3aa1 extends Twig_Template
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
        $context["feed_url"] = (((($this->getAttribute((isset($context["blog"]) ? $context["blog"] : null), "url", array()) == "/") || ($this->getAttribute((isset($context["blog"]) ? $context["blog"] : null), "url", array()) == (isset($context["base_url_relative"]) ? $context["base_url_relative"] : null)))) ? ((((isset($context["base_url_relative"]) ? $context["base_url_relative"] : null) . "/") . $this->getAttribute((isset($context["blog"]) ? $context["blog"] : null), "slug", array()))) : ($this->getAttribute((isset($context["blog"]) ? $context["blog"] : null), "url", array())));
        // line 2
        $context["new_base_url"] = ((($this->getAttribute((isset($context["blog"]) ? $context["blog"] : null), "url", array()) == "/")) ? ("") : ($this->getAttribute((isset($context["blog"]) ? $context["blog"] : null), "url", array())));
        // line 3
        echo "

<div id=\"editors\">
    <h3>From the Editors</h3>
    <h4><a href=\"/iSR_Grav/entries/editor-letter\">Behind the Scenes of Issue One</a></h4>
    <span class=\"byline\">By The iSchool Review</span>
</div>
<div id=\"careers\">
    <h3>careers</h3>
    <h4><a href=\"/iSR_Grav/entries/what-is-a-pm\">What Is a Product Manager (at a Tech Company)?</a></h4>
    <span class=\"byline\">By Rebecca K. Anderson, Director of Career Services at the UC Berkeley School of Information</span>
    
</div>
<div id=\"case-studies\">
    <h3>case studies</h3>
    <h4><a href=\"/iSR_Grav/entries/spacelords-organizing-orbital-real-estate\">Spacelords: Organizing Orbital Real Estate</a></h4>
    <span class=\"byline\">By Daniel Brenners</span>
    <h4><a href=\"/iSR_Grav/entries/citizen-science\">The Design Decisions That Shaped a Citizen Science Project</a></h4>
    <span class=\"byline\">By Laura Montini</span>
    
</div>

";
        // line 68
        echo " ";
    }

    public function getTemplateName()
    {
        return "partials/sidebar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 68,  23 => 3,  21 => 2,  19 => 1,);
    }
}
/* {% set feed_url = blog.url == '/' or blog.url == base_url_relative ? (base_url_relative~'/'~blog.slug) : blog.url %}*/
/* {% set new_base_url = blog.url == '/' ? '' : blog.url %}*/
/* */
/* */
/* <div id="editors">*/
/*     <h3>From the Editors</h3>*/
/*     <h4><a href="/iSR_Grav/entries/editor-letter">Behind the Scenes of Issue One</a></h4>*/
/*     <span class="byline">By The iSchool Review</span>*/
/* </div>*/
/* <div id="careers">*/
/*     <h3>careers</h3>*/
/*     <h4><a href="/iSR_Grav/entries/what-is-a-pm">What Is a Product Manager (at a Tech Company)?</a></h4>*/
/*     <span class="byline">By Rebecca K. Anderson, Director of Career Services at the UC Berkeley School of Information</span>*/
/*     */
/* </div>*/
/* <div id="case-studies">*/
/*     <h3>case studies</h3>*/
/*     <h4><a href="/iSR_Grav/entries/spacelords-organizing-orbital-real-estate">Spacelords: Organizing Orbital Real Estate</a></h4>*/
/*     <span class="byline">By Daniel Brenners</span>*/
/*     <h4><a href="/iSR_Grav/entries/citizen-science">The Design Decisions That Shaped a Citizen Science Project</a></h4>*/
/*     <span class="byline">By Laura Montini</span>*/
/*     */
/* </div>*/
/* */
/* {# */
/* */
/* {% if config.plugins.simplesearch.enabled %}*/
/* <div class="sidebar-content">*/
/*     <h4>SimpleSearch</h4>*/
/*     {% include 'partials/simplesearch_searchbox.html.twig' %}*/
/* </div>*/
/* {% endif %}*/
/* {% if config.plugins.relatedpages.enabled and related_pages|length > 0 %}*/
/*     <h4>Related Posts</h4>*/
/*     {% include 'partials/relatedpages.html.twig' %}*/
/* {% endif %}*/
/* {% if config.plugins.random.enabled %}*/
/* <div class="sidebar-content">*/
/* 	<h4>Random Article</h4>*/
/* 	<a class="button" href="{{ base_url }}/random"><i class="fa fa-retweet"></i> I'm Feeling Lucky!</a>*/
/* </div>*/
/* {% endif %}*/
/* <div class="sidebar-content">*/
/* 	<h4>Some Text Widget</h4>*/
/* 	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>*/
/* </div>*/
/* {% if config.plugins.taxonomylist.enabled %}*/
/* <div class="sidebar-content">*/
/*     <h4>Popular Tags</h4>*/
/*     {% include 'partials/taxonomylist.html.twig' with {'base_url':new_base_url, 'taxonomy':'tag'} %}*/
/* </div>*/
/* {% endif %}*/
/* {% if config.plugins.archives.enabled %}*/
/* <div class="sidebar-content">*/
/*     <h4>Archives</h4>*/
/* 	{% include 'partials/archives.html.twig' with {'base_url':new_base_url} %}*/
/* </div>*/
/* {% endif %}*/
/* */
/* */
/* {% if config.plugins.feed.enabled %}*/
/* <div class="sidebar-content syndicate">*/
/*     <a class="button" href="{{ feed_url }}.atom"><i class="fa fa-rss-square"></i> Atom 1.0</a>*/
/*     <a class="button" href="{{ feed_url }}.rss"><i class="fa fa-rss-square"></i> RSS</a>*/
/* </div>*/
/* {% endif %}*/
/* */
/* #} */
