<?php

/* ucp_notifications.html */
class __TwigTemplate_e6137a72c90abcf02e4770887367077df016f6224a8751f5b164c787cefa1ff6 extends Twig_Template
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
        $location = "ucp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_header.html", "ucp_notifications.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<form id=\"ucp\" method=\"post\" action=\"";
        // line 3
        echo (isset($context["S_UCP_ACTION"]) ? $context["S_UCP_ACTION"] : null);
        echo "\"";
        echo (isset($context["S_FORM_ENCTYPE"]) ? $context["S_FORM_ENCTYPE"] : null);
        echo ">

<h2>";
        // line 5
        echo (isset($context["TITLE"]) ? $context["TITLE"] : null);
        echo "</h2>
<div class=\"panel\">
\t<div class=\"inner\">

\t\t<p>";
        // line 9
        echo (isset($context["TITLE_EXPLAIN"]) ? $context["TITLE_EXPLAIN"] : null);
        echo "</p>

\t\t";
        // line 11
        if (((isset($context["MODE"]) ? $context["MODE"] : null) == "notification_options")) {
            // line 12
            echo "\t\t\t<table class=\"table1\">
\t\t\t\t<thead>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>";
            // line 15
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NOTIFICATION_TYPE");
            echo "</th>
\t\t\t\t\t\t";
            // line 16
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_methods", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["notification_methods"]) {
                // line 17
                echo "\t\t\t\t\t\t\t<th class=\"mark\">";
                echo $this->getAttribute($context["notification_methods"], "NAME", array());
                echo "</th>
\t\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_methods'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 19
            echo "\t\t\t\t\t</tr>
\t\t\t\t</thead>
\t\t\t\t<tbody>
\t\t\t\t";
            // line 22
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_types", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["notification_types"]) {
                // line 23
                echo "\t\t\t\t\t";
                if ($this->getAttribute($context["notification_types"], "GROUP_NAME", array())) {
                    // line 24
                    echo "\t\t\t\t\t\t<tr class=\"bg3\">
\t\t\t\t\t\t\t<td colspan=\"";
                    // line 25
                    echo (isset($context["NOTIFICATION_TYPES_COLS"]) ? $context["NOTIFICATION_TYPES_COLS"] : null);
                    echo "\">";
                    echo $this->getAttribute($context["notification_types"], "GROUP_NAME", array());
                    echo "</td>
\t\t\t\t\t\t</tr>
\t\t\t\t\t";
                } else {
                    // line 28
                    echo "\t\t\t\t\t\t<tr class=\"";
                    if (($this->getAttribute($context["notification_types"], "S_ROW_COUNT", array()) % 2 == 1)) {
                        echo "bg1";
                    } else {
                        echo "bg2";
                    }
                    echo "\">
\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t";
                    // line 30
                    echo $this->getAttribute($context["notification_types"], "NAME", array());
                    echo "
\t\t\t\t\t\t\t\t";
                    // line 31
                    if ($this->getAttribute($context["notification_types"], "EXPLAIN", array())) {
                        echo "<br />&nbsp; &nbsp;";
                        echo $this->getAttribute($context["notification_types"], "EXPLAIN", array());
                    }
                    // line 32
                    echo "\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t";
                    // line 33
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["notification_types"], "notification_methods", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["notification_methods"]) {
                        // line 34
                        echo "\t\t\t\t\t\t\t\t<td class=\"mark\"><input type=\"checkbox\" name=\"";
                        echo $this->getAttribute($context["notification_types"], "TYPE", array());
                        echo "_";
                        echo $this->getAttribute($context["notification_methods"], "METHOD", array());
                        echo "\"";
                        if (($this->getAttribute($context["notification_methods"], "AVAILABLE", array()) && $this->getAttribute($context["notification_methods"], "SUBSCRIBED", array()))) {
                            echo " checked=\"checked\"";
                        }
                        if ( !$this->getAttribute($context["notification_methods"], "AVAILABLE", array())) {
                            echo " disabled=\"disabled\"";
                        }
                        echo " /></td>
\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_methods'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 36
                    echo "\t\t\t\t\t\t</tr>
\t\t\t\t\t";
                }
                // line 38
                echo "\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_types'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            echo "\t\t\t\t</tbody>
\t\t\t</table>
\t\t";
        } else {
            // line 42
            echo "\t\t\t";
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list", array()))) {
                // line 43
                echo "\t\t\t\t<div class=\"action-bar bar-top\">
\t\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t\t";
                // line 45
                if ((isset($context["U_MARK_ALL"]) ? $context["U_MARK_ALL"] : null)) {
                    echo "<a href=\"";
                    echo (isset($context["U_MARK_ALL"]) ? $context["U_MARK_ALL"] : null);
                    echo "\" class=\"mark\">";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NOTIFICATIONS_MARK_ALL_READ");
                    echo "</a> &bull; ";
                }
                // line 46
                echo "\t\t\t\t\t\t";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NOTIFICATIONS");
                echo " [<strong>";
                echo (isset($context["TOTAL_COUNT"]) ? $context["TOTAL_COUNT"] : null);
                echo "</strong>]
\t\t\t\t\t\t";
                // line 47
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination", array()))) {
                    // line 48
                    echo "\t\t\t\t\t\t\t";
                    $location = "pagination.html";
                    $namespace = false;
                    if (strpos($location, '@') === 0) {
                        $namespace = substr($location, 1, strpos($location, '/') - 1);
                        $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                        $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                    }
                    $this->loadTemplate("pagination.html", "ucp_notifications.html", 48)->display($context);
                    if ($namespace) {
                        $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                    }
                    // line 49
                    echo "\t\t\t\t\t\t";
                } else {
                    // line 50
                    echo "\t\t\t\t\t\t\t &bull; ";
                    echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                    echo "
\t\t\t\t\t\t";
                }
                // line 52
                echo "\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t<div class=\"notification_list\">
\t\t\t\t<ul class=\"topiclist two-columns\">
\t\t\t\t\t<li class=\"header\">
\t\t\t\t\t\t<dl>
\t\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 59
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NOTIFICATIONS");
                echo "</div></dt>
\t\t\t\t\t\t\t<dd class=\"mark\">";
                // line 60
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_READ");
                echo "</dd>
\t\t\t\t\t\t</dl>
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t\t<ul class=\"topiclist cplist two-columns\">
\t\t\t\t\t";
                // line 65
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["notification_list"]) {
                    // line 66
                    echo "\t\t\t\t\t\t<li class=\"row";
                    if ($this->getAttribute($context["notification_list"], "UNREAD", array())) {
                        echo " bg3";
                    } else {
                        if (($this->getAttribute($context["notification_list"], "S_ROW_COUNT", array()) % 2 == 1)) {
                            echo " bg1";
                        } else {
                            echo " bg2";
                        }
                    }
                    if ($this->getAttribute($context["notification_list"], "STYLING", array())) {
                        echo " ";
                        echo $this->getAttribute($context["notification_list"], "STYLING", array());
                    }
                    echo "\">
\t\t\t\t\t\t\t<dl>
\t\t\t\t\t\t\t\t<dt>
\t\t\t\t\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t\t\t\t\t";
                    // line 70
                    if ($this->getAttribute($context["notification_list"], "AVATAR", array())) {
                        echo $this->getAttribute($context["notification_list"], "AVATAR", array());
                    } else {
                        echo "<img src=\"";
                        echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
                        echo "/images/no_avatar.gif\" alt=\"\" />";
                    }
                    // line 71
                    echo "\t\t\t\t\t\t\t\t\t\t<div class=\"notifications\">
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 72
                    if ($this->getAttribute($context["notification_list"], "URL", array())) {
                        echo "<a href=\"";
                        if ($this->getAttribute($context["notification_list"], "UNREAD", array())) {
                            echo $this->getAttribute($context["notification_list"], "U_MARK_READ", array());
                        } else {
                            echo $this->getAttribute($context["notification_list"], "URL", array());
                        }
                        echo "\">";
                    }
                    // line 73
                    echo "\t\t\t\t\t\t\t\t\t\t\t<p class=\"notifications_title\">";
                    echo $this->getAttribute($context["notification_list"], "FORMATTED_TITLE", array());
                    if ($this->getAttribute($context["notification_list"], "REFERENCE", array())) {
                        echo " ";
                        echo $this->getAttribute($context["notification_list"], "REFERENCE", array());
                    }
                    echo "</p>
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 74
                    if ($this->getAttribute($context["notification_list"], "URL", array())) {
                        echo "</a>";
                    }
                    // line 75
                    echo "\t\t\t\t\t\t\t\t\t\t\t";
                    if ($this->getAttribute($context["notification_list"], "FORUM", array())) {
                        echo "<p class=\"notifications_forum\">";
                        echo $this->getAttribute($context["notification_list"], "FORUM", array());
                        echo "</p>";
                    }
                    // line 76
                    echo "\t\t\t\t\t\t\t\t\t\t\t";
                    if ($this->getAttribute($context["notification_list"], "REASON", array())) {
                        echo "<p class=\"notifications_reason\">";
                        echo $this->getAttribute($context["notification_list"], "REASON", array());
                        echo "</p>";
                    }
                    // line 77
                    echo "\t\t\t\t\t\t\t\t\t\t\t<p class=\"notifications_time\">";
                    echo $this->getAttribute($context["notification_list"], "TIME", array());
                    echo "</p>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t</dt>

\t\t\t\t\t\t\t\t<dd class=\"mark\">&nbsp;<input type=\"checkbox\" name=\"mark[]\" value=\"";
                    // line 82
                    echo $this->getAttribute($context["notification_list"], "NOTIFICATION_ID", array());
                    echo "\"";
                    if ( !$this->getAttribute($context["notification_list"], "UNREAD", array())) {
                        echo " disabled=\"disabled\"";
                    }
                    echo " /> <dfn>";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_READ");
                    echo "</dfn>&nbsp;</dd>
\t\t\t\t\t\t\t</dl>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_list'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 86
                echo "\t\t\t\t</ul>
\t\t\t</div>

\t\t\t<div class=\"action-bar bar-bottom\">
\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t";
                // line 91
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NOTIFICATIONS");
                echo " [<strong>";
                echo (isset($context["TOTAL_COUNT"]) ? $context["TOTAL_COUNT"] : null);
                echo "</strong>]
\t\t\t\t\t";
                // line 92
                if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination", array()))) {
                    // line 93
                    echo "\t\t\t\t\t\t";
                    $location = "pagination.html";
                    $namespace = false;
                    if (strpos($location, '@') === 0) {
                        $namespace = substr($location, 1, strpos($location, '/') - 1);
                        $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                        $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                    }
                    $this->loadTemplate("pagination.html", "ucp_notifications.html", 93)->display($context);
                    if ($namespace) {
                        $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                    }
                    // line 94
                    echo "\t\t\t\t\t";
                } else {
                    // line 95
                    echo "\t\t\t\t\t\t  &bull; ";
                    echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                    echo "
\t\t\t\t\t";
                }
                // line 97
                echo "\t\t\t\t</div>
\t\t\t</div>

\t\t\t";
            } else {
                // line 101
                echo "\t\t\t\t<p><strong>";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NO_NOTIFICATIONS");
                echo "</strong></p>
\t\t\t";
            }
            // line 103
            echo "
\t\t";
        }
        // line 105
        echo "\t</div>
</div>

";
        // line 108
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_types", array())) || twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notification_list", array())))) {
            // line 109
            echo "<fieldset class=\"display-actions\">
\t<input type=\"hidden\" name=\"form_time\" value=\"";
            // line 110
            echo (isset($context["FORM_TIME"]) ? $context["FORM_TIME"] : null);
            echo "\" />
\t";
            // line 111
            echo (isset($context["S_HIDDEN_FIELDS"]) ? $context["S_HIDDEN_FIELDS"] : null);
            echo "
\t<input type=\"submit\" name=\"submit\" value=\"";
            // line 112
            if (((isset($context["MODE"]) ? $context["MODE"] : null) == "notification_options")) {
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SUBMIT");
            } else {
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_READ");
            }
            echo "\" class=\"button1\" />
\t<div><a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', true); return false;\">";
            // line 113
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', false); return false;\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("UNMARK_ALL");
            echo "</a></div>
\t";
            // line 114
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
</fieldset>
";
        }
        // line 117
        echo "
</form>

";
        // line 120
        $location = "ucp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_footer.html", "ucp_notifications.html", 120)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ucp_notifications.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  419 => 120,  414 => 117,  408 => 114,  402 => 113,  394 => 112,  390 => 111,  386 => 110,  383 => 109,  381 => 108,  376 => 105,  372 => 103,  366 => 101,  360 => 97,  354 => 95,  351 => 94,  338 => 93,  336 => 92,  330 => 91,  323 => 86,  307 => 82,  298 => 77,  291 => 76,  284 => 75,  280 => 74,  271 => 73,  261 => 72,  258 => 71,  250 => 70,  230 => 66,  226 => 65,  218 => 60,  214 => 59,  205 => 52,  199 => 50,  196 => 49,  183 => 48,  181 => 47,  174 => 46,  166 => 45,  162 => 43,  159 => 42,  154 => 39,  148 => 38,  144 => 36,  126 => 34,  122 => 33,  119 => 32,  114 => 31,  110 => 30,  100 => 28,  92 => 25,  89 => 24,  86 => 23,  82 => 22,  77 => 19,  68 => 17,  64 => 16,  60 => 15,  55 => 12,  53 => 11,  48 => 9,  41 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "ucp_notifications.html", "");
    }
}
