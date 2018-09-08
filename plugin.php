<?php
/**
 * 2018年最新版Google Analytics 统计 Universal Analytics
 * 在 mutoo 做的 Google Analytics1.0 插件基础上进行了GA代码更新。
 * mutoo 的原版链接：https://2013.mutoo.im/google-analytics.html
 * @package Universal Analytics
 * @author 銮尊
 * @version 1.0.2
 * @link https://luanzun.com/
 */
class UniversalAnalytics_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('UniversalAnalytics_Plugin', 'render');
        // Typecho_Plugin::factory('admin/header.php')->header = array('UniversalAnalytics_Plugin', 'render');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 分类名称 */
        $account = new Typecho_Widget_Helper_Form_Element_Text('account', NULL, ' ', _t('Universal Analytics 帐号'), _t('此帐号可在 GA 管理平台查询；格式为 UA-XXXXXXX-X 。'));
        $form->addInput($account);
		// $website = new Typecho_Widget_Helper_Form_Element_Text('website', NULL, 'auto', _t('Universal Analytics注册的域名'), _t('此域名是在GA注册的顶级域名或者二级域名，如xxx.com。也可以写自动识别：auto '));
        // $form->addInput($website);
        $optimize = new Typecho_Widget_Helper_Form_Element_Text('optimize', NULL, '', _t('Google Optimize 优化工具容器ID'), _t('容器ID在https://optimize.google.com/ 可查看，如GTM-XXXXXXX'));
        $form->addInput($optimize);
		$otherid = new Typecho_Widget_Helper_Form_Element_Text('otherid', NULL, '', _t('其它统计代码'), _t('其它统计代码'));
        $form->addInput($otherid);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
        $account = Typecho_Widget::widget('Widget_Options')->plugin('UniversalAnalytics')->account;
        // $website = Typecho_Widget::widget('Widget_Options')->plugin('UniversalAnalytics')->website;
        $optimize = Typecho_Widget::widget('Widget_Options')->plugin('UniversalAnalytics')->optimize;
        $otherid = Typecho_Widget::widget('Widget_Options')->plugin('UniversalAnalytics')->otherid;
        if ($optimize){
            echo "
            <style>.async-hide { opacity: 0 !important} </style>
            <script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
            h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
            (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
            })(window,document.documentElement,'async-hide','dataLayer',4000,
            {'{$optimize}':true});</script>";
        }
        if ($account) {
            echo "
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src=\"https://www.googletagmanager.com/gtag/js?id=$account\"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '$account');
            </script>";
        }
        if ($otherid) {
            echo "{$otherid}";
        }
    }
}
