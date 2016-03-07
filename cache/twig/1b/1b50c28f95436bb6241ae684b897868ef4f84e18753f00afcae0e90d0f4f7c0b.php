<?php

/* forms/fields/frontmatter/frontmatter.html.twig */
class __TwigTemplate_f1bb890fa94cff90aee08c7556d52058d2130c15f14420d7dcae9dd6fe1b60ef extends Twig_Template
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
        $context["value"] = (((null === (isset($context["value"]) ? $context["value"] : null))) ? ($this->getAttribute((isset($context["field"]) ? $context["field"] : null), "default", array())) : ((isset($context["value"]) ? $context["value"] : null)));
        // line 2
        echo "
<div class=\"form-field\">
    <div class=\"form-label\">
        <label>
            ";
        // line 6
        if ($this->getAttribute((isset($context["field"]) ? $context["field"] : null), "help", array())) {
            // line 7
            echo "            <span class=\"tooltip\" data-asTooltip-position=\"w\" title=\"";
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter(twig_escape_filter($this->env, $this->getAttribute((isset($context["field"]) ? $context["field"] : null), "help", array())));
            echo "\">";
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter($this->getAttribute((isset($context["field"]) ? $context["field"] : null), "label", array()));
            echo "</span>
            ";
        } else {
            // line 9
            echo "            ";
            echo $this->env->getExtension('AdminTwigExtension')->tuFilter($this->getAttribute((isset($context["field"]) ? $context["field"] : null), "label", array()));
            echo "
            ";
        }
        // line 11
        echo "            ";
        echo ((twig_in_filter($this->getAttribute($this->getAttribute((isset($context["field"]) ? $context["field"] : null), "validate", array()), "required", array()), array(0 => "on", 1 => "true", 2 => 1))) ? ("<span class=\"required\">*</span>") : (""));
        echo "
        </label>
    </div>
    <div class=\"form-data\">
        <div class=\"form-frontmatter-wrapper ";
        // line 15
        echo $this->getAttribute((isset($context["field"]) ? $context["field"] : null), "size", array());
        echo "\">
            <textarea name=\"";
        // line 16
        echo $this->env->getExtension('GravTwigExtension')->fieldNameFilter(((isset($context["scope"]) ? $context["scope"] : null) . $this->getAttribute((isset($context["field"]) ? $context["field"] : null), "name", array())));
        echo "\" id=\"frontmatter\">";
        echo twig_join_filter((isset($context["value"]) ? $context["value"] : null), "
");
        echo "</textarea>
            <div class=\"dragbar\"></div>
        </div>
    </div>
</div>
<script>
    ((function(){
        var editor = CodeMirror.fromTextArea(document.getElementById('frontmatter'), {
            mode: 'yaml',
            theme: 'paper',
            ";
        // line 26
        if ($this->getAttribute((isset($context["field"]) ? $context["field"] : null), "autofocus", array())) {
            // line 27
            echo "            autofocus: true,
            ";
        }
        // line 29
        echo "            indentUnit: 4,
            indentWithTabs: false,
            extraKeys: {Tab: function(cm) { cm.replaceSelection(\"    \", \"end\"); }}
        });
        editor.on('change', editor.save);

        \$('.dragbar').mousedown(function(e){
            e.preventDefault();
            var parentOffset = \$(this).parent().offset();
            \$(document).mousemove(function(e){
              \$('.form-frontmatter-wrapper .CodeMirror').css('height',e.pageY - parentOffset.top);
           })
        });
        \$(document).mouseup(function(e){
           \$(document).unbind('mousemove');
        });
    })());
</script>
";
    }

    public function getTemplateName()
    {
        return "forms/fields/frontmatter/frontmatter.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  77 => 29,  73 => 27,  71 => 26,  55 => 16,  51 => 15,  43 => 11,  37 => 9,  29 => 7,  27 => 6,  21 => 2,  19 => 1,);
    }
}
/* {% set value = (value is null ? field.default : value) %}*/
/* */
/* <div class="form-field">*/
/*     <div class="form-label">*/
/*         <label>*/
/*             {% if field.help %}*/
/*             <span class="tooltip" data-asTooltip-position="w" title="{{ field.help|e|tu }}">{{ field.label|tu }}</span>*/
/*             {% else %}*/
/*             {{ field.label|tu }}*/
/*             {% endif %}*/
/*             {{ field.validate.required in ['on', 'true', 1] ? '<span class="required">*</span>' }}*/
/*         </label>*/
/*     </div>*/
/*     <div class="form-data">*/
/*         <div class="form-frontmatter-wrapper {{ field.size }}">*/
/*             <textarea name="{{ (scope ~ field.name)|fieldName }}" id="frontmatter">{{ value|join("\n") }}</textarea>*/
/*             <div class="dragbar"></div>*/
/*         </div>*/
/*     </div>*/
/* </div>*/
/* <script>*/
/*     ((function(){*/
/*         var editor = CodeMirror.fromTextArea(document.getElementById('frontmatter'), {*/
/*             mode: 'yaml',*/
/*             theme: 'paper',*/
/*             {% if field.autofocus %}*/
/*             autofocus: true,*/
/*             {% endif %}*/
/*             indentUnit: 4,*/
/*             indentWithTabs: false,*/
/*             extraKeys: {Tab: function(cm) { cm.replaceSelection("    ", "end"); }}*/
/*         });*/
/*         editor.on('change', editor.save);*/
/* */
/*         $('.dragbar').mousedown(function(e){*/
/*             e.preventDefault();*/
/*             var parentOffset = $(this).parent().offset();*/
/*             $(document).mousemove(function(e){*/
/*               $('.form-frontmatter-wrapper .CodeMirror').css('height',e.pageY - parentOffset.top);*/
/*            })*/
/*         });*/
/*         $(document).mouseup(function(e){*/
/*            $(document).unbind('mousemove');*/
/*         });*/
/*     })());*/
/* </script>*/
/* */
