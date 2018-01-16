<?php

/* auth_provider_oauth.html */
class __TwigTemplate_c4023703d4fa35c6ad3d6a7ac170e94cbb3aeda525d1a90e446bea3d3cf6c242 extends Twig_Template
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
        echo "
<div id=\"auth_oauth_settings\">
\t<p>";
        // line 3
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AUTH_PROVIDER_OAUTH_EXPLAIN");
        echo "</p>

\t";
        // line 5
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "oauth_services", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["oauth_services"]) {
            // line 6
            echo "\t<fieldset>
\t\t<legend>";
            // line 7
            echo $this->getAttribute($context["oauth_services"], "ACTUAL_NAME", array());
            echo "</legend>
\t\t<dl>
\t\t\t<dt><label for=\"oauth_service_";
            // line 9
            echo $this->getAttribute($context["oauth_services"], "NAME", array());
            echo "_key\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AUTH_PROVIDER_OAUTH_KEY");
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
            echo "</label></dt>
\t\t\t<dd><input type=\"text\" id=\"oauth_service_";
            // line 10
            echo $this->getAttribute($context["oauth_services"], "NAME", array());
            echo "_key\" size=\"40\" name=\"config[auth_oauth_";
            echo $this->getAttribute($context["oauth_services"], "NAME", array());
            echo "_key]\" value=\"";
            echo $this->getAttribute($context["oauth_services"], "KEY", array());
            echo "\" /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"oauth_service_";
            // line 13
            echo $this->getAttribute($context["oauth_services"], "NAME", array());
            echo "_secret\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AUTH_PROVIDER_OAUTH_SECRET");
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
            echo "</label></dt>
\t\t\t<dd><input type=\"text\" id=\"oauth_service_";
            // line 14
            echo $this->getAttribute($context["oauth_services"], "NAME", array());
            echo "_secret\" size=\"40\" name=\"config[auth_oauth_";
            echo $this->getAttribute($context["oauth_services"], "NAME", array());
            echo "_secret]\" value=\"";
            echo $this->getAttribute($context["oauth_services"], "SECRET", array());
            echo "\" /></dd>
\t\t</dl>
\t</fieldset>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['oauth_services'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 18
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "auth_provider_oauth.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 18,  64 => 14,  57 => 13,  47 => 10,  40 => 9,  35 => 7,  32 => 6,  28 => 5,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "auth_provider_oauth.html", "");
    }
}
