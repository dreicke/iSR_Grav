<?php

/* partials/users-details.html.twig */
class __TwigTemplate_d13e11cdd174b6b5320c098af0ca7da1a985bf2ee69b450d85ef68017ae978d9 extends Twig_Template
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
        echo "<div class=\"user-details\">
    <img src=\"https://www.gravatar.com/avatar/";
        // line 2
        echo $this->env->getExtension('GravTwigExtension')->md5Filter($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "email", array()));
        echo "?s=128\" />
    <p class=\"gravatar\">";
        // line 3
        echo $this->env->getExtension('AdminTwigExtension')->tuFilter("PLUGIN_ADMIN.AVATAR_BY");
        echo " <a href=\"http://gravatar.com\" target=\"_blank\">gravatar.com</a></p>
    <h2>";
        // line 4
        echo $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "fullname", array());
        echo "</h2>
    ";
        // line 5
        if ($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "title", array())) {
            echo "<h5>";
            echo $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "title", array());
            echo "</h5>";
        }
        // line 6
        echo "</div>

";
        // line 8
        $this->loadTemplate("partials/blueprints.html.twig", "partials/users-details.html.twig", 8)->display(array_merge($context, array("data" => (isset($context["user"]) ? $context["user"] : null), "blueprints" => $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "blueprints", array()))));
    }

    public function getTemplateName()
    {
        return "partials/users-details.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 8,  40 => 6,  34 => 5,  30 => 4,  26 => 3,  22 => 2,  19 => 1,);
    }
}
/* <div class="user-details">*/
/*     <img src="https://www.gravatar.com/avatar/{{ user.email|md5 }}?s=128" />*/
/*     <p class="gravatar">{{ "PLUGIN_ADMIN.AVATAR_BY"|tu }} <a href="http://gravatar.com" target="_blank">gravatar.com</a></p>*/
/*     <h2>{{ user.fullname }}</h2>*/
/*     {% if user.title %}<h5>{{ user.title }}</h5>{% endif %}*/
/* </div>*/
/* */
/* {% include 'partials/blueprints.html.twig' with { data: user, blueprints: user.blueprints } %}*/
/* */
