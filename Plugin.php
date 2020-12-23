<?php 
/**
 * 一个简单的 Element 风格公告栏
 * 
 * @package SewNotice
 * @author 三水非冰
 * @version 1.0.0
 * @link https://www.sanshuifeibing.com
 */
class SewNotice_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate(){
        Typecho_Plugin::factory('Widget_Archive')->footer = array('SewNotice_Plugin', 'footer');
        Typecho_Plugin::factory('Widget_Archive') ->header = array('SewNotice_Plugin', 'header');
    	return'启用成功！请设置您的标题和内容';
    }
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
    	return'禁用成功！插件已经停用';
    }

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
        
        $duration = new Typecho_Widget_Helper_Form_Element_Text('duration',null, '5000', _t('公告栏显示时长'), '表示公告栏在页面中持续多久之后消失，填 0 则永久显示，单位：毫秒');
        $form->addInput($duration);
        
        $autoTitle = new Typecho_Widget_Helper_Form_Element_Radio(
            'autoTitle',
            array(
                'open' => _t('开启'),
                'close' => _t('关闭')
            ),
            'open',
            _t('自动标题'),
            _t('开启后公告标题将根据时间段显示问候语，如：上午好！')
        );
        $form->addInput($autoTitle);
        
        $title = new Typecho_Widget_Helper_Form_Element_Text('title', null, '', _t('公告标题'), '这里填写公告栏的标题，需要自动标题处于关闭状态才能生效');
        $form->addInput($title);
        
        $text = new Typecho_Widget_Helper_Form_Element_Text('text', null, '', _t('公告内容'), '这里填写公告栏的内容');
        $form->addInput($text);
        
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
    public static function render(){}
    public static function header(){}


    public static function footer(){
     $config = Typecho_Widget::widget('Widget_Options')->plugin('SewNotice');
        
        $title = Typecho_Widget::widget('Widget_Options') -> Plugin('SewNotice') -> title;
        $text = Typecho_Widget::widget('Widget_Options') -> Plugin('SewNotice') -> text;
        $autoTitle = Typecho_Widget::widget('Widget_Options') -> Plugin('SewNotice') -> autoTitle;
        $duration = Typecho_Widget::widget('Widget_Options') -> Plugin('SewNotice') -> duration;
        echo '<script src="'.Helper::options()->pluginUrl .'/SewNotice/Notice.js" type="text/javascript" charset="utf-8"></script>';
        echo <<<EOF

<script>
        function OnBtnClick(autoTitle) {
            var content = {
                title: "$title" ,
                content: "$text",
                duration: "$duration"
            };
            notice.showNotice(autoTitle, content);
        }
       
    </script>
    
    <script type = "text/javascript">
        window.onload = function()
        {
            OnBtnClick("$autoTitle");
        }
    </script>

EOF;
    }
}
?>