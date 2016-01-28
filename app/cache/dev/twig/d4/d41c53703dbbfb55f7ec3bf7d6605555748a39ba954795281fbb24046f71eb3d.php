<?php

/* SiciarekChatBundle:Default:private.html.twig */
class __TwigTemplate_fa4937b02f44f1b5001175a82762120ceda84f09f6d232c67c0170d4c7e6519a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("SiciarekChatBundle::base.html.twig", "SiciarekChatBundle:Default:private.html.twig", 1);
        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SiciarekChatBundle::base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_c5722f1ba3f7c669f5a29c5d04b4de4cc0ff690df9c62c634f57d20401f6ae39 = $this->env->getExtension("native_profiler");
        $__internal_c5722f1ba3f7c669f5a29c5d04b4de4cc0ff690df9c62c634f57d20401f6ae39->enter($__internal_c5722f1ba3f7c669f5a29c5d04b4de4cc0ff690df9c62c634f57d20401f6ae39_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "SiciarekChatBundle:Default:private.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_c5722f1ba3f7c669f5a29c5d04b4de4cc0ff690df9c62c634f57d20401f6ae39->leave($__internal_c5722f1ba3f7c669f5a29c5d04b4de4cc0ff690df9c62c634f57d20401f6ae39_prof);

    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        $__internal_2c7e74b7b4132c3a4c368f0bb75257c25cc3c8fe6bf03c905127470b5900dc4a = $this->env->getExtension("native_profiler");
        $__internal_2c7e74b7b4132c3a4c368f0bb75257c25cc3c8fe6bf03c905127470b5900dc4a->enter($__internal_2c7e74b7b4132c3a4c368f0bb75257c25cc3c8fe6bf03c905127470b5900dc4a_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 4
        echo "<h1>PRIVATE</h1>

";
        // line 6
        echo $this->env->getExtension('ladybug_extension')->ladybug_dump($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user", array()), "username", array()));
        echo "

<p><a href=\"";
        // line 8
        echo $this->env->getExtension('routing')->getUrl("fos_user_security_logout");
        echo "\">Wyloguj</a>

";
        
        $__internal_2c7e74b7b4132c3a4c368f0bb75257c25cc3c8fe6bf03c905127470b5900dc4a->leave($__internal_2c7e74b7b4132c3a4c368f0bb75257c25cc3c8fe6bf03c905127470b5900dc4a_prof);

    }

    public function getTemplateName()
    {
        return "SiciarekChatBundle:Default:private.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 8,  44 => 6,  40 => 4,  34 => 3,  11 => 1,);
    }
}
/* {% extends 'SiciarekChatBundle::base.html.twig' %}*/
/* */
/* {% block body %}*/
/* <h1>PRIVATE</h1>*/
/* */
/* {{ ld(app.user.username) }}*/
/* */
/* <p><a href="{{url('fos_user_security_logout')}}">Wyloguj</a>*/
/* */
/* {% endblock %}*/
/* */
/* */
