<?php

/* SiciarekChatBundle::base.html.twig */
class __TwigTemplate_f651dcbf7abdf38b9d51e733c84f730e04838230d6fab3fb9fe70ef16598dc06 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
            'body' => array($this, 'block_body'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_8d82942e359129e42708fbfd078b8555cdcffdb3b0990dbff2fa085abcdfce3d = $this->env->getExtension("native_profiler");
        $__internal_8d82942e359129e42708fbfd078b8555cdcffdb3b0990dbff2fa085abcdfce3d->enter($__internal_8d82942e359129e42708fbfd078b8555cdcffdb3b0990dbff2fa085abcdfce3d_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "SiciarekChatBundle::base.html.twig"));

        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"utf-8\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Chat window</title>

        ";
        // line 11
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 14
        echo "    </head>
    
    <body>
        ";
        // line 17
        $this->displayBlock('body', $context, $blocks);
        // line 20
        echo "
        ";
        // line 21
        $this->displayBlock('javascripts', $context, $blocks);
        // line 24
        echo "    </body>
</html>
";
        
        $__internal_8d82942e359129e42708fbfd078b8555cdcffdb3b0990dbff2fa085abcdfce3d->leave($__internal_8d82942e359129e42708fbfd078b8555cdcffdb3b0990dbff2fa085abcdfce3d_prof);

    }

    // line 11
    public function block_stylesheets($context, array $blocks = array())
    {
        $__internal_23aa6333666b1d9871e28fd4f1bf5ceae1dcae4d9bb8b961c47c5d73a031ad37 = $this->env->getExtension("native_profiler");
        $__internal_23aa6333666b1d9871e28fd4f1bf5ceae1dcae4d9bb8b961c47c5d73a031ad37->enter($__internal_23aa6333666b1d9871e28fd4f1bf5ceae1dcae4d9bb8b961c47c5d73a031ad37_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 12
        echo "
        ";
        
        $__internal_23aa6333666b1d9871e28fd4f1bf5ceae1dcae4d9bb8b961c47c5d73a031ad37->leave($__internal_23aa6333666b1d9871e28fd4f1bf5ceae1dcae4d9bb8b961c47c5d73a031ad37_prof);

    }

    // line 17
    public function block_body($context, array $blocks = array())
    {
        $__internal_6e3c134c45e0834a3810d5982edd3335c4e0e6114021088e53a4cc6362c2cfbc = $this->env->getExtension("native_profiler");
        $__internal_6e3c134c45e0834a3810d5982edd3335c4e0e6114021088e53a4cc6362c2cfbc->enter($__internal_6e3c134c45e0834a3810d5982edd3335c4e0e6114021088e53a4cc6362c2cfbc_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 18
        echo "
        ";
        
        $__internal_6e3c134c45e0834a3810d5982edd3335c4e0e6114021088e53a4cc6362c2cfbc->leave($__internal_6e3c134c45e0834a3810d5982edd3335c4e0e6114021088e53a4cc6362c2cfbc_prof);

    }

    // line 21
    public function block_javascripts($context, array $blocks = array())
    {
        $__internal_9c5de00122fcf436a8e84755ff9cf1bf4cb18fc736ab3e4854e5cf2d745d715a = $this->env->getExtension("native_profiler");
        $__internal_9c5de00122fcf436a8e84755ff9cf1bf4cb18fc736ab3e4854e5cf2d745d715a->enter($__internal_9c5de00122fcf436a8e84755ff9cf1bf4cb18fc736ab3e4854e5cf2d745d715a_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "javascripts"));

        // line 22
        echo "
        ";
        
        $__internal_9c5de00122fcf436a8e84755ff9cf1bf4cb18fc736ab3e4854e5cf2d745d715a->leave($__internal_9c5de00122fcf436a8e84755ff9cf1bf4cb18fc736ab3e4854e5cf2d745d715a_prof);

    }

    public function getTemplateName()
    {
        return "SiciarekChatBundle::base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  94 => 22,  88 => 21,  80 => 18,  74 => 17,  66 => 12,  60 => 11,  51 => 24,  49 => 21,  46 => 20,  44 => 17,  39 => 14,  37 => 11,  25 => 1,);
    }
}
/* <!DOCTYPE html>*/
/* <html>*/
/*     <head>*/
/*         <meta charset="utf-8">*/
/*         <meta http-equiv="X-UA-Compatible" content="IE=edge">*/
/*         <meta name="viewport" content="width=device-width, initial-scale=1">*/
/*         <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->*/
/* */
/*         <title>Chat window</title>*/
/* */
/*         {% block stylesheets %}*/
/* */
/*         {% endblock %}*/
/*     </head>*/
/*     */
/*     <body>*/
/*         {% block body %}*/
/* */
/*         {% endblock %}*/
/* */
/*         {% block javascripts %}*/
/* */
/*         {% endblock %}*/
/*     </body>*/
/* </html>*/
/* */
