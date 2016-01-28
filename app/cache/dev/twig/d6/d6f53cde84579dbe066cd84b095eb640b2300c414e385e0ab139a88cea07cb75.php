<?php

/* SiciarekChatBundle:Default:index.html.twig */
class __TwigTemplate_bc605be35280213415117fcbd91609f932b2797bed349aea6895a9d2dd273afc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("SiciarekChatBundle::base.html.twig", "SiciarekChatBundle:Default:index.html.twig", 1);
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
        $__internal_a33ed8eeb74ba21fd714bc9c3791d0490b54a46ea3ec9ae0a3c6a4889f3801e5 = $this->env->getExtension("native_profiler");
        $__internal_a33ed8eeb74ba21fd714bc9c3791d0490b54a46ea3ec9ae0a3c6a4889f3801e5->enter($__internal_a33ed8eeb74ba21fd714bc9c3791d0490b54a46ea3ec9ae0a3c6a4889f3801e5_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "SiciarekChatBundle:Default:index.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_a33ed8eeb74ba21fd714bc9c3791d0490b54a46ea3ec9ae0a3c6a4889f3801e5->leave($__internal_a33ed8eeb74ba21fd714bc9c3791d0490b54a46ea3ec9ae0a3c6a4889f3801e5_prof);

    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        $__internal_42fc1ddd64433b455dd6b09142c274213c5e178b2ac089224879afc7fe097217 = $this->env->getExtension("native_profiler");
        $__internal_42fc1ddd64433b455dd6b09142c274213c5e178b2ac089224879afc7fe097217->enter($__internal_42fc1ddd64433b455dd6b09142c274213c5e178b2ac089224879afc7fe097217_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 4
        echo "<h1>INDEX</h1>

<p><a href=\"";
        // line 6
        echo $this->env->getExtension('routing')->getUrl("default.private");
        echo "\">Chat</a>

";
        
        $__internal_42fc1ddd64433b455dd6b09142c274213c5e178b2ac089224879afc7fe097217->leave($__internal_42fc1ddd64433b455dd6b09142c274213c5e178b2ac089224879afc7fe097217_prof);

    }

    public function getTemplateName()
    {
        return "SiciarekChatBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 6,  40 => 4,  34 => 3,  11 => 1,);
    }
}
/* {% extends 'SiciarekChatBundle::base.html.twig' %}*/
/* */
/* {% block body %}*/
/* <h1>INDEX</h1>*/
/* */
/* <p><a href="{{url('default.private')}}">Chat</a>*/
/* */
/* {% endblock %}*/
/* */
/* */
