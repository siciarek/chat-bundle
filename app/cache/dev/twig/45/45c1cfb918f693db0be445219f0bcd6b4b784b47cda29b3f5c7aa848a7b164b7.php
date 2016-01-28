<?php

/* TwigBundle:Exception:exception_full.html.twig */
class __TwigTemplate_aceaefe71bc85d20bfb255c3ce27aaaea5a74ff80db24e5ecb3f2eb03d2f075b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("TwigBundle::layout.html.twig", "TwigBundle:Exception:exception_full.html.twig", 1);
        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "TwigBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_607d75b9c5422fcbf10680ec8d11d61a6cfbdc33d6fe27ad463115af78985a94 = $this->env->getExtension("native_profiler");
        $__internal_607d75b9c5422fcbf10680ec8d11d61a6cfbdc33d6fe27ad463115af78985a94->enter($__internal_607d75b9c5422fcbf10680ec8d11d61a6cfbdc33d6fe27ad463115af78985a94_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "TwigBundle:Exception:exception_full.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_607d75b9c5422fcbf10680ec8d11d61a6cfbdc33d6fe27ad463115af78985a94->leave($__internal_607d75b9c5422fcbf10680ec8d11d61a6cfbdc33d6fe27ad463115af78985a94_prof);

    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        $__internal_8a070981908580903ab25fbe8c566629593ac1ae1b2d3bdcce6f1243b15c4967 = $this->env->getExtension("native_profiler");
        $__internal_8a070981908580903ab25fbe8c566629593ac1ae1b2d3bdcce6f1243b15c4967->enter($__internal_8a070981908580903ab25fbe8c566629593ac1ae1b2d3bdcce6f1243b15c4967_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "head"));

        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('request')->generateAbsoluteUrl($this->env->getExtension('asset')->getAssetUrl("bundles/framework/css/exception.css")), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
";
        
        $__internal_8a070981908580903ab25fbe8c566629593ac1ae1b2d3bdcce6f1243b15c4967->leave($__internal_8a070981908580903ab25fbe8c566629593ac1ae1b2d3bdcce6f1243b15c4967_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_6cfbe180abc06b075f51418aa19cbde812ced22bbf567f2c484d629b28a76e90 = $this->env->getExtension("native_profiler");
        $__internal_6cfbe180abc06b075f51418aa19cbde812ced22bbf567f2c484d629b28a76e90->enter($__internal_6cfbe180abc06b075f51418aa19cbde812ced22bbf567f2c484d629b28a76e90_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        // line 8
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "message", array()), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["status_text"]) ? $context["status_text"] : $this->getContext($context, "status_text")), "html", null, true);
        echo ")
";
        
        $__internal_6cfbe180abc06b075f51418aa19cbde812ced22bbf567f2c484d629b28a76e90->leave($__internal_6cfbe180abc06b075f51418aa19cbde812ced22bbf567f2c484d629b28a76e90_prof);

    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        $__internal_400daadd73afd2c2343d39d863bcfab6bdccdc66c4a5f879b312a5f368f5f35d = $this->env->getExtension("native_profiler");
        $__internal_400daadd73afd2c2343d39d863bcfab6bdccdc66c4a5f879b312a5f368f5f35d->enter($__internal_400daadd73afd2c2343d39d863bcfab6bdccdc66c4a5f879b312a5f368f5f35d_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 12
        echo "    ";
        $this->loadTemplate("TwigBundle:Exception:exception.html.twig", "TwigBundle:Exception:exception_full.html.twig", 12)->display($context);
        
        $__internal_400daadd73afd2c2343d39d863bcfab6bdccdc66c4a5f879b312a5f368f5f35d->leave($__internal_400daadd73afd2c2343d39d863bcfab6bdccdc66c4a5f879b312a5f368f5f35d_prof);

    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:exception_full.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 12,  72 => 11,  58 => 8,  52 => 7,  42 => 4,  36 => 3,  11 => 1,);
    }
}
/* {% extends 'TwigBundle::layout.html.twig' %}*/
/* */
/* {% block head %}*/
/*     <link href="{{ absolute_url(asset('bundles/framework/css/exception.css')) }}" rel="stylesheet" type="text/css" media="all" />*/
/* {% endblock %}*/
/* */
/* {% block title %}*/
/*     {{ exception.message }} ({{ status_code }} {{ status_text }})*/
/* {% endblock %}*/
/* */
/* {% block body %}*/
/*     {% include 'TwigBundle:Exception:exception.html.twig' %}*/
/* {% endblock %}*/
/* */
