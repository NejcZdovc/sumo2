<?php /* Smarty version Smarty-3.1.13, created on 2013-01-17 13:17:38
         compiled from "modules/rc.3zsistemi.si/mod_last_article/html/view_default_on.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15160711450853b22f28244-30316889%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a0f2ad94215528d027be5f6575d8d5be48c0238' => 
    array (
      0 => 'modules/rc.3zsistemi.si/mod_last_article/html/view_default_on.tpl',
      1 => 1345760132,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15160711450853b22f28244-30316889',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_50853b230fe2e6_21761537',
  'variables' => 
  array (
    'title' => 0,
    'content' => 0,
    'i' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50853b230fe2e6_21761537')) {function content_50853b230fe2e6_21761537($_smarty_tpl) {?><script type="text/javascript">
	$(document).ready(function(){	
		$("#slider").easySlider({
			auto: true,
			continuous: true,
			controlsShow: false,
			speed: 800
		});
	});	
</script>	
<div class="last_default">
    <div class="module_title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</div>
    <div>
       <div id="slider">
       	<ul class="animation_ul">
       		<?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['content']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
?>
    			<li><?php echo $_smarty_tpl->tpl_vars['i']->value['title'];?>
</li>
			<?php } ?>
       	</ul>
       </div>
    </div>
</div><?php }} ?>